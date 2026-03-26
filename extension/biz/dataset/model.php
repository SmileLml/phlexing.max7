<?php
/**
 * The model file of dataset module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class datasetModel extends model
{
    /**
     * Get by id.
     *
     * @param int $datasetID
     * @access public
     * @return void
     */
    public function getByID($datasetID)
    {
        $dataset = $this->dao->select('*')->from(TABLE_DATASET)->where('id')->eq($datasetID)->fetch();
        $dataset->fieldSettings = json_decode($dataset->fields);
        $dataset->fields = array();
        foreach($dataset->fieldSettings as $field => $settings)
        {
            $dataset->fields[] = $field;
        }

        $dataset->vars = $this->getVars($dataset->sql);

        return $dataset;
    }

    /**
     * Strip vars.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function stripVars($sql)
    {
        $vars = array();
        if(preg_match_all("/(where|and|or)(:?.(?!(where|and|or)))+?[\$]+[a-z.A-Z]+/i", $sql, $out))
        {
            foreach($out[0] as $match)
            {
                if(strpos($match, '($')) $match .= ')';
                $begin = substr($match, 0, 1);
                switch($begin)
                {
                case 'A':
                case 'a':
                    $sql = str_ireplace($match, 'AND 1', $sql);
                    break;
                case 'O':
                case 'o':
                    $sql = str_ireplace($match, 'OR 1', $sql);
                    break;
                case 'W':
                case 'w':
                    $sql = str_ireplace($match, 'WHERE 1', $sql);
                    break;
                }
            }
        }

        return $sql;
    }

    /**
     * Get vars.
     *
     * @param string $sql
     * @access private
     * @return void
     */
    private function getVars($sql)
    {
        $vars = array();
        if(preg_match_all("/[\$]+[a-z.A-Z]+/", $sql, $out))
        {
            foreach($out[0] as $match)
            {
                $var = explode('.', $match);
                if(count($var) != 2) return $this->send(array('result' => 'fail', 'message' => $this->lang->dataset->varError));
                $vars[] = substr($match, 1);
            }
        }
        return $vars;
    }

    /**
     * Get table info.
     *
     * @param  string $key
     * @access public
     * @return object
     */
    public function getTableInfo($key)
    {
        $this->app->loadLang('chart');

        if(strpos($key, 'custom_') === 0)
        {
            $key = substr($key, 7);
            return $this->getDatasetInfo($key);
        }

        $table = new stdclass();

        $tableInfo     = $this->lang->dataset->tables[$key];
        $table->key    = $key;
        $table->name   = $tableInfo['name'];
        $table->desc   = $tableInfo['desc'];
        $table->schema = $this->includeTable($key);

        return $table;
    }

    private function getDatasetInfo($id)
    {
        $dataset = $this->dao->select('id AS `key`, name, fields, `sql` AS `desc`')
            ->from(TABLE_DATASET)
            ->where('id')->eq($id)
            ->fetch();
        $dataset->schema = new stdclass();
        $dataset->schema->sql          = $dataset->desc;
        $dataset->schema->primaryTable = 'custom_';
        $dataset->schema->fields       = json_decode($dataset->fields, true);

        return $dataset;
    }

    /**
     * Include table
     *
     * @access private
     * @return void
     */
    private function includeTable($table)
    {
        $path = $this->app->getModuleRoot() . 'dataview' . DS . 'table' . DS . "$table.php";
        if(file_exists($path)) include $path;

        $path = $this->app->getExtensionRoot() . 'custom' . DS . 'dataset' . DS . 'table' . DS . "$table.php";
        if(file_exists($path)) include $path;

        return $schema;
    }

    /**
     * Get tables.
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getTables($sql)
    {
        $sql = trim($sql, ';');
        $sql = str_replace(array("\r\n", "\n"), ' ', $sql);
        $sql = str_replace('`', '', $sql);
        preg_match_all('/^select (.+) from (.+)$/i', $sql, $tables);
        if(empty($tables[2][0])) return false;

        $fields = $tables[1][0];
        $tables = $tables[2][0];
        if(stripos($tables, 'where') !== false) $tables = trim(substr($tables, 0, stripos($tables, 'where')));
        if(stripos($tables, 'limit') !== false) $tables = trim(substr($tables, 0, stripos($tables, 'limit')));
        if(stripos($tables, 'having') !== false) $tables = trim(substr($tables, 0, stripos($tables, 'having')));
        if(stripos($tables, 'group by') !== false) $tables = trim(substr($tables, 0, stripos($tables, 'group by')));

        /* Remove such as "left join|right join|join", "on (t1.id=t2.id)", result like t1, t2 as t3. */
        $tables  = $tables . ' ';
        if(stripos($tables, 'join') !== false) $tables = preg_replace(array('/join\s+([A-Z]+_\w+ .*)on/Ui', '/,\s*on\s+[^,]+/i'), array(',$1,on', ''), $tables);

        /* Match t2 as t3 */
        $fields = explode(',', $fields);
        preg_match_all('/(\w+) +as +(\w+)/i', $tables, $out);
        foreach($fields as $i => $field)
        {
            if($field) $asField = '';
            if(strrpos($field, ' as ') !== false) list($field, $asField) = explode(' as ', $field);

            $field     = trim($field);
            $asField   = trim($asField);
            $fieldName = $field;
            if(strrpos($field, '.') !== false)
            {
                $table     = substr($field, 0, strrpos($field, '.'));
                $fieldName = substr($field, strrpos($field, '.') + 1);
                if(!empty($out[0]) and in_array($table, $out[2])) $field = str_replace($table . '.', $out[1][array_search($table, $out[2])] . '.', $field);

                if($fieldName == '*') $fieldName = $field;
            }

            $fieldName = $asField ? $asField : $fieldName;
            $fields[$fieldName] = $field;
            unset($fields[$i]);
        }

        $tables = preg_replace('/as +\w+/i', ' ', $tables);
        $tables = trim(str_replace(array('(', ')', ','), ' ', $tables));
        $tables = preg_replace('/ +/', ' ', $tables);

        $tables = explode(' ', $tables);
        return array('tables' => $tables, 'fields' => $fields);
    }

    /**
     * Merge fields.
     *
     * @param  int    $dataFields
     * @param  int    $sqlFields
     * @param  int    $moduleNames
     * @access public
     * @return void
     */
    public function mergeFields($dataFields, $sqlFields, $moduleNames)
    {
        $mergeFields = array();
        foreach($dataFields as $field)
        {
            $mergeFields[$field] = $field;
            /* Such as $sqlFields['id'] = zt_task.id. */
            if(isset($sqlFields[$field]) and strrpos($sqlFields[$field], '.') !== false)
            {
                $sqlField  = $sqlFields[$field];
                $table     = substr($sqlField, 0, strrpos($sqlField, '.'));
                $fieldName = substr($sqlField, strrpos($sqlField, '.') + 1);

                if(isset($moduleNames[$table]))
                {
                    $moduleName = $moduleNames[$table];
                    if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);
                    $mergeFields[$field] = isset($this->lang->$moduleName->$fieldName) ? $this->lang->$moduleName->$fieldName : $field;
                    continue;
                }
            }

            if(strpos(join(',', $sqlFields), '.*') !== false)
            {
                /* Such as $sqlFields['zt_task.*'] = zt_task.*. */
                $existField = false;
                foreach($sqlFields as $sqlField)
                {
                    if(strrpos($sqlField, '.*') !== false)
                    {
                        $table = substr($sqlField, 0, strrpos($sqlField, '.'));
                        if(isset($moduleNames[$table]))
                        {
                            $moduleName = $moduleNames[$table];
                            if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);
                            $mergeFields[$field] = isset($this->lang->$moduleName->$field) ? $this->lang->$moduleName->$field : $field;
                            $existField = true;
                            break;
                        }
                    }
                }
                if($existField) continue;
            }

            foreach($moduleNames as $table => $moduleName)
            {
                if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);
                if(isset($this->lang->$moduleName) and isset($this->lang->$moduleName->$field))
                {
                    $mergeFields[$field] = $this->lang->$moduleName->$field;
                    break;
                }
                $mergeFields[$field] = $field;
            }
        }

        foreach($mergeFields as $fieldName => $fieldValue)
        {
            if(empty($fieldValue)) $mergeFields[$fieldName] = $fieldName;
        }

        foreach($mergeFields as $field => $name) $mergeFields[$field] = $this->replace4Workflow($name);
        return $mergeFields;
    }

    /**
     * Get table data.
     *
     * @param  string $table
     * @access public
     * @return array
     */
    public function replaceDbTablePrefix($table)
    {
        return  str_replace('zt_', $this->config->db->prefix, $table);
    }

    /**
     * Get table data.
     *
     * @param object $schema
     * @param string $orderBy
     * @param int    $limit
     * @param bool   $getSql
     * @access public
     * @return array
     */
    public function getTableData($schema, $orderBy = 'id_desc', $limit = 0, $getSql = false)
    {
        if(!isset($schema->sql))
        {
            $fields = array();
            foreach($schema->fields as $field => $info)
            {
                if($info['type'] == 'object')
                {
                    list($infoTable, $infoField) = explode('.', $info['show']);
                    $alias = str_replace('.', '_', $info['show']);
                    $fields[] = "`$infoTable`.`$infoField` AS `$alias`";

                    $object = isset($info['object']) ? $info['object'] : '';
                    if(!empty($object) and isset($schema->objects[$object]))
                    {
                        foreach($schema->objects[$object] as $fieldID => $fieldName)
                        {
                            $addedField = str_replace("$object.", '', $info['show']);
                            if($fieldID == $addedField) continue;
                            $fields[] = "`{$object}`.`{$fieldID}` AS `{$object}_{$fieldID}`";
                        }
                    }
                }
                else
                {
                    $alias = str_replace('.', '_', $field);
                    $fields[] = "`$schema->primaryTable`.`$field` AS `$alias`";
                }
            }

            $joins = array();
            foreach($schema->joins as $table => $relation)
            {
                $joins[] = " LEFT JOIN ". $this->replaceDbTablePrefix($schema->tables[$table]) . ' AS `' . $table . "` ON $relation";
            }

            $from = ' FROM ' . $this->replaceDbTablePrefix($schema->tables[$schema->primaryTable]) . " AS `$schema->primaryTable` ";
            $sql  = 'SELECT ' . implode(',', array_unique($fields)) . $from . implode(' ', $joins);

            if(isset($schema->showDeleted) and $schema->showDeleted === false) $sql .= " where `$schema->primaryTable`.deleted = '0'";
        }
        else
        {
            $vars = $this->getVars($schema->sql);
            $sysOptions = array();
            foreach($vars as $var)
            {
                $vs = explode('.', $var);
                if($vs[1] == 'id')
                {
                    if($vs[0] == 'build')
                    {
                        $sysOptions['project']   = array();
                        $sysOptions['execution'] = array();
                    }
                    if($vs[0] == 'execution')
                    {
                        $sysOptions['project'] = array();
                    }
                    $sysOptions[$vs[0]] = array();
                }
            }
            list($sysOptions, $defaults) = $this->getSysOptions($sysOptions);
            foreach($defaults as $key => $value)
            {
                if(strpos($key, '.') === false) $key = $key . '.id';
                $schema->sql = str_ireplace('$' . $key, $value, $schema->sql);
            }
            $sql = $schema->sql;
        }

        if($limit) $sql .= " LIMIT $limit";
        if($getSql) return $sql;
        return $this->dao->query($sql)->fetchAll();
    }

    /**
     * Print cell of data.
     *
     * @param mixed $data
     * @param mixed $field
     * @param mixed $info
     * @access public
     * @return void
     */
    public function printCell($data, $field, $info)
    {
        $attr  = $info['type'] == 'object' ? str_replace('.', '_', $info['show']) : $field;
        $value = strip_tags($data->$attr);
        switch($info['type'])
        {
            case 'date':
                if(strpos($value, '0000-00-00') === 0) $value = '';
                break;
            case 'option':
                $value = zget($info['options'], $value, $value);
                break;
        }
        echo $value;
    }

    /**
     * Get filters.
     *
     * @param array $datasets
     * @access public
     * @return array
     */
    public function getFilters($datasets)
    {
        $objects = array('product', 'productline', 'project', 'program', 'execution', 'build', 'caselib', 'casemodule');
        $optionFields = array();
        $dateFields   = array();
        foreach($datasets as $dataset)
        {
            if(!$dataset or strpos($dataset, 'custom_') === 0) continue;

            $schema = $this->includeTable($dataset);
            $table = $schema->primaryTable;
            if(in_array($table, $objects)) $optionFields[$table . '.id'] = array('name' => $this->lang->dataset->objects[$table], 'options' => array(), 'type' => $table);

            foreach($schema->fields as $key => $field)
            {
                if($field['type'] == 'object')
                {
                    $object = $field['object'];
                    if(in_array($object, $objects)) $optionFields[$object . '.id'] = array('name' => $this->lang->dataset->objects[$object], 'options' => array(), 'type' => $object);
                    foreach($schema->objects[$object] as $subKey => $subField)
                    {
                        if($subField['type'] == 'option')
                        {
                            $optionFields[$object . '.' . $subKey] = array('name' => $this->lang->dataset->objects[$object] . '.' . $subField['name'], 'options' => $subField['options'], 'type' => 'option');
                        }
                        else if($subField['type'] == 'user')
                        {
                            $optionFields[$object . '.' . $subKey] = array('name' => $this->lang->dataset->objects[$object] . '.' . $subField['name'], 'options' => array(), 'type' => 'user');
                        }
                        else if(in_array($subField['type'], array('date', 'time', 'datetime')))
                        {
                            $dateFields[$object . '.' . $subKey] = array('name' => $this->lang->dataset->objects[$object] . '.' . $subField['name']);
                        }
                    }
                }
                else if($field['type'] == 'option')
                {
                    $optionFields[$table . '.' . $key] = array('name' => $this->lang->dataset->objects[$table] . '.' . $field['name'], 'options' => $field['options'], 'type' => 'option');
                }
                else if($field['type'] == 'user')
                {
                    $optionFields[$table . '.' . $key] = array('name' => $this->lang->dataset->objects[$table] . '.' . $field['name'], 'options' => array(), 'type' => 'user');
                }
                else if(in_array($field['type'], array('date', 'time', 'datetime')))
                {
                    $dateFields[$table . '.' . $key] = array('name' => $this->lang->dataset->objects[$table] . '.' . $field['name']);
                }
            }
            unset($schema);
        }

        return array('option' => $optionFields, 'date' => $dateFields);
    }

    /**
     * Get options of system.
     *
     * @param array $sysOptions
     * @access public
     * @return array
     */
    public function getSysOptions($sysOptions)
    {
        $sysOptions['user'] = array(); // All options must have user.

        $defaults = array();
        foreach($sysOptions as $type => $option)
        {
            $options = array();
            switch($type)
            {
                case 'user':
                    $users = $this->loadModel('user')->getPairs();
                    foreach($users as $key => $user) $options[] = array('value' => $key, 'label' => $user);
                    break;
                case 'product':
                    $products = $this->loadModel('product')->getPairs();
                    foreach($products as $key => $product) $options[] = array('value' => (string)$key, 'label' => $product);
                    $defaults['product'] = (int)$this->product->checkAccess(0, $products);
                    break;
                case 'project':
                    $projects = $this->loadModel('project')->getPairsByProgram();
                    foreach($projects as $key => $project) $options[] = array('value' => (string)$key, 'label' => $project);
                    $defaults['project'] = (string)$this->project->checkAccess(0, $projects);
                    break;
                case 'execution':
                    $executions = $this->loadModel('execution')->getPairs($defaults['project']);
                    foreach($executions as $key => $execution) $options[] = array('value' => (string)$key, 'label' => $execution);
                    $defaults['execution'] = (string)$this->execution->checkAccess(0, $executions);
                    break;
                case 'build':
                    $builds = $this->loadModel('build')->getExecutionBuilds($defaults['execution']);
                    foreach($builds as $build) $options[] = array('value' => (string)$build->id, 'label' => $build->name);
                    $defaults['build'] = (string)key($builds);
                    break;
                case 'caselib':
                    $libs = $this->loadModel('caselib')->getLibraries();
                    foreach($libs as $key => $lib) $options[] = array('value' => (string)$key, 'label' => $lib);
                    $defaults['caselib'] = (string)key($libs);
                    break;
                case 'casemodule':
                    if(isset($defaults['build']))
                    {
                        $testtasks = $this->dao->select('id')->from(TABLE_TESTTASK)->where('build')->eq($defaults['build'])->fetchPairs('id');
                    }
                    else if(isset($defaults['execution']))
                    {
                        $testtasks = $this->dao->select('id')->from(TABLE_TESTTASK)->where('execution')->eq($defaults['execution'])->fetchPairs('id');
                    }
                    else if(isset($defaults['project']))
                    {
                        $testtasks = $this->dao->select('id')->from(TABLE_TESTTASK)->where('project')->eq($defaults['project'])->fetchPairs('id');
                    }
                    else
                    {
                        $defaults['casemodule'] = '';
                        break;
                    }
                    if(empty($testtasks))
                    {
                        $defaults['casemodule'] = '';
                        break;
                    }

                    $moduleIdList = $this->dao->select('distinct module')->from(TABLE_CASE)->alias('t1')
                        ->leftJoin(TABLE_TESTRUN)->alias('t2')
                        ->on('t1.id = t2.case')
                        ->where('t2.task')->in($testtasks)
                        ->fetchPairs();

                    $modules    = $this->dao->select('id, name, path, branch')->from(TABLE_MODULE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchAll('path');
                    $allModules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in(join(array_keys($modules)))->andWhere('deleted')->eq(0)->fetchPairs('id', 'name');
                    $moduleTree = new stdclass();
                    foreach($modules as $module)
                    {
                        $paths = explode(',', trim($module->path, ','));
                        $this->genTreeOptions($moduleTree, $allModules, $paths);
                    }

                    $options = isset($moduleTree->children) ? $moduleTree->children : array();
                    $defaults['casemodule'] = '';
                    break;
            }

            $sysOptions[$type] = $options;
        }

        return array($sysOptions, $defaults);
    }

    /**
     * Gen tree options.
     *
     * @param object $tree
     * @param array  $values
     * @param array  $paths
     * @access public
     * @return void
     */
    public function genTreeOptions(&$moduleTree, $values, $paths)
    {
        $path = $paths[0];
        if(!isset($moduleTree->children))$moduleTree->children = array();

        foreach($moduleTree->children as $child)
        {
            if($child->value == $path)
            {
                if(count($paths) > 1) return $this->genTreeOptions($child, $values, array_slice($paths, 1));
                return;
            }
        }

        $child = new stdclass();
        $child->title = $values[$path];
        $child->value = $path;
        $moduleTree->children[] = $child;
        if(count($paths) > 1) return $this->genTreeOptions($child, $values, array_slice($paths, 1));
    }

    /**
     * Get datasets.
     *
     * @param string $type
     * @access public
     * @return void
     */
    public function getList($type)
    {
        if($type == 'internal')
        {
            $datasets = array();
            foreach($this->lang->dataset->tables as $code => $table)
            {
                $table['id']   = 0;
                $table['code'] = $code;
                $datasets[]    = $table;
            }
            return $datasets;
        }

        $result = array();
        $datasets = $this->dao->select('*')->from(TABLE_DATASET)
            ->where('deleted')->eq(0)
            ->fetchAll();
        foreach($datasets as $dataset)
        {
            $result[] = array('id' => $dataset->id, 'name' => $dataset->name, 'code' => 'custom_' . $dataset->id, 'desc' => $dataset->sql);
        }

        return $result;
    }

    /**
     * Get type options
     *
     * @param string $objectName
     * @access public
     * @return array
     */
    public function getTypeOptions($objectName)
    {
        $schema  = $this->includeTable($objectName);
        $options = array();
        foreach($schema->fields as $key => $field)
        {
            if($field['type'] == 'object') continue;
            $options[$key] = $field;
        }
        return $options;
    }

    /**
     * Create dataset.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $data = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->skipSpecial('fields,objects,sql')
            ->get();

        $this->dao->insert(TABLE_DATASET)->data($data)
            ->batchCheck($this->config->dataset->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * Update dataset.
     *
     * @param $datasetID
     * @access public
     * @return void
     */
    public function update($datasetID)
    {
        $data = fixer::input('post')
            ->skipSpecial('fields,objects,sql')
            ->get();

        /* Set options. */
        $map = array();
        $fields = json_decode($data->fields);
        foreach($fields as $key => $field)
        {
            if($field->type == 'option')
            {
                if(!isset($map[$field->object])) $map[$field->object] = $this->includeTable($field->object);
                $fields->$key->options  = $map[$field->object]->fields[$field->field]['options'];
            }
        }
        $data->fields = json_encode($fields);

        $this->dao->update(TABLE_DATASET)->data($data)
            ->batchCheck($this->config->dataset->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($datasetID)
            ->exec();
    }

    /**
     * Get filters from sql vars.
     *
     * @param  array $vars
     * @access public
     * @return array
     */
    public function getVarFilters($vars)
    {
        $filters = array();
        foreach($vars as $var)
        {
            $filter = new stdclass();
            $filter->multiple = false;
            $filter->var      = $var;
            $filter->options  = array();
            $vars = explode('.', $var);

            if($vars[1] == 'id')
            {
                switch($vars[0])
                {
                case 'user':
                    $filter->options = $this->loadModel('user')->getPairs();
                    break;
                case 'product':
                    $filter->options = $this->loadModel('product')->getPairs();
                    break;
                case 'project':
                    $filter->options = $this->loadModel('project')->getPairsByProgram();
                    break;
                case 'execution':
                    $filter->options = $this->loadModel('execution')->getPairs();
                    break;
                case 'caselib':
                    $filter->options = $this->loadModel('caselib')->getLibraries();
                    break;
                case 'casemodule':
                    $filter->options = array();
                    break;
                }
            }
            else
            {
                $schema = $this->includeTable($vars[0]);
                $filter->options = $schema->fields[$vars[1]]->options;
            }
            $filters[] = $filter;
        }

        return $filters;
    }

    /**
     * Replace title for workflow.
     *
     * @param  string $title
     * @access public
     * @return string
     */
    public function replace4Workflow($title)
    {
        $clientLang = $this->app->getClientLang();
        $productCommonList   = isset($this->config->productCommonList[$clientLang]) ? $this->config->productCommonList[$clientLang] : $this->config->productCommonList['en'];
        $projectCommonList = isset($this->config->projectCommonList[$clientLang]) ? $this->config->projectCommonList[$clientLang] : $this->config->projectCommonList['en'];
        $productCommon = $productCommonList[0];
        $projectCommon = $projectCommonList[0];
        if(strpos($title, strtolower($productCommon)) !== false) $title = str_replace(strtolower($productCommon), strtolower($this->lang->productCommon), $title);
        if(strpos($title, $productCommon) !== false)             $title = str_replace($productCommon, $this->lang->productCommon, $title);
        return $title;
    }
}
