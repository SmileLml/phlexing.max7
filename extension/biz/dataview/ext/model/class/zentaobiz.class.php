<?php
class zentaobizDataview extends dataviewModel
{
    /**
     * Get a dataview by id.
     *
     * @param  int    $dataviewID
     * @access public
     * @return bool | object
     */
    public function getByID($dataviewID)
    {
        $dataview = $this->dao->select('*')->from(TABLE_DATAVIEW)->where('id')->eq($dataviewID)->fetch();
        if(!$dataview) return false;

        $dataview->vars = $this->getVars($dataview->sql);
        $dataview->used = $this->checkUsed($dataview);

        if(!empty($dataview->fields))
        {
            $dataview->fieldSettings = json_decode($dataview->fields);
            $dataview->fields        = array();

            foreach($dataview->fieldSettings as $field => $settings) $dataview->fields[] = $field;
        }
        return $dataview;
    }

    /**
     * Get a dataview by view.
     *
     * @param  string    $view
     * @access public
     * @return bool | object
     */
    public function getByView($view)
    {
        return $this->dao->select('*')->from(TABLE_DATAVIEW)->where('view')->eq($view)->fetch();
    }

    /**
     * Get dataview state.
     *
     * @param  int    $dataviewID
     * @access public
     * @return object
     */
    public function getDataviewState($dataviewID = 0)
    {
        $this->app->loadClass('dataviewstate', true);

        $dataview = $this->dao->select('*')->from(TABLE_DATAVIEW)->where('id')->eq($dataviewID)->fetch();
        if(!$dataview)
        {
            $dataview = new stdclass();
            $dataview->id     = 0;
            $dataview->group  = '';
            $dataview->name   = '';
            $dataview->code   = '';
            $dataview->mode   = 'builder';
            $dataview->view   = '';
            $dataview->sql    = '';
            $dataview->fields = array();
            $dataview->langs  = array();
        }

        $sqlbuilder = $this->loadModel('sqlbuilder')->getByObject($dataviewID, 'dataview');

        $state = new dataviewState($dataview, $this->app->getClientLang(), $sqlbuilder);
        $state->updateFromPost($_POST);
        $state->canChangeMode = $state->setCanChangeMode();

        if($state->mode == 'builder')
        {
            $state->sqlbuilder = $this->sqlbuilder->initSqlBuilder($state->sqlbuilder);
            $state->sql        = $state->sqlbuilder->sql;
        }

        return $state;
    }

    /**
     * Get a origin table name.
     *
     * @param  string    $table
     * @access public
     * @return bool | object
     */
    public function getTableName($table)
    {
        $tableName = '';

        if(empty($this->config->db->prefix) or strpos($table, $this->config->db->prefix) !== false)
        {
            if(strpos($table, $this->config->db->prefix . 'flow_') !== 0)
            {
                $subTable = substr($table, strpos($table, '_') + 1);
                $tableName = zget($this->lang->dev->tableList, $subTable, '');
            }
        }

        return $tableName;
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
                if(count($var) != 2) return $this->send(array('result' => 'fail', 'message' => $this->lang->dataview->varError));
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

        $tableInfo = $this->lang->dataview->tables[$key];

        $table = new stdclass();
        $table->key    = $key;
        $table->name   = $tableInfo['name'];
        $table->desc   = $tableInfo['desc'];
        $table->schema = $this->includeTable($key);

        return $table;
    }

    /**
     * Get dataset info.
     *
     * @param  int    $id
     * @access private
     * @return void
     */
    private function getDatasetInfo($id)
    {
        $dataview = $this->dao->select('id AS `key`, name, fields, `sql` AS `desc`')->from(TABLE_DATAVIEW)->where('id')->eq($id)->fetch();
        $dataview->schema = new stdclass();
        $dataview->schema->sql          = $dataview->desc;
        $dataview->schema->primaryTable = 'custom_';
        $dataview->schema->fields       = json_decode($dataview->fields, true);

        return $dataview;
    }

    /**
     * Get table data.
     *
     * @param  string $table
     * @param  string $type
     * @param  int    $limit
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTableData($table, $type, $limit = 25, $pager = null)
    {
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

        if($type == 'table')
        {
            return $this->dao->select('*')->from($table)
            ->beginIF($limit)->limit($limit)->fi()
            ->page($pager)
            ->fetchAll('', false);
        }

        if($type == 'view' && is_numeric($table) !== false)
        {
            $table = $this->getByID($table);
            return $this->dao->select('*')->from($table->view)
                ->beginIF($limit)->limit($limit)->fi()
                ->page($pager)
                ->fetchAll('', false);
        }
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
     * @param array $dataviews
     * @access public
     * @return array
     */
    public function getFilters($dataviews)
    {
        $objects = array('product', 'productline', 'project', 'program', 'execution', 'build', 'caselib', 'casemodule');
        $optionFields = array();
        $dateFields   = array();
        foreach($dataviews as $dataview)
        {
            if(!$dataview or strpos($dataview, 'custom_') === 0) continue;

            $schema = $this->includeTable($dataview);
            $table = $schema->primaryTable;
            if(in_array($table, $objects)) $optionFields[$table . '.id'] = array('name' => $this->lang->dataview->objects[$table], 'options' => array(), 'type' => $table);

            foreach($schema->fields as $key => $field)
            {
                if($field['type'] == 'object')
                {
                    $object = $field['object'];
                    if(in_array($object, $objects)) $optionFields[$object . '.id'] = array('name' => $this->lang->dataview->objects[$object], 'options' => array(), 'type' => $object);
                    foreach($schema->objects[$object] as $subKey => $subField)
                    {
                        if($subField['type'] == 'option')
                        {
                            $optionFields[$object . '.' . $subKey] = array('name' => $this->lang->dataview->objects[$object] . '.' . $subField['name'], 'options' => $subField['options'], 'type' => 'option');
                        }
                        else if($subField['type'] == 'user')
                        {
                            $optionFields[$object . '.' . $subKey] = array('name' => $this->lang->dataview->objects[$object] . '.' . $subField['name'], 'options' => array(), 'type' => 'user');
                        }
                        else if(in_array($subField['type'], array('date', 'time', 'datetime')))
                        {
                            $dateFields[$object . '.' . $subKey] = array('name' => $this->lang->dataview->objects[$object] . '.' . $subField['name']);
                        }
                    }
                }
                else if($field['type'] == 'option')
                {
                    $optionFields[$table . '.' . $key] = array('name' => $this->lang->dataview->objects[$table] . '.' . $field['name'], 'options' => $field['options'], 'type' => 'option');
                }
                else if($field['type'] == 'user')
                {
                    $optionFields[$table . '.' . $key] = array('name' => $this->lang->dataview->objects[$table] . '.' . $field['name'], 'options' => array(), 'type' => 'user');
                }
                else if(in_array($field['type'], array('date', 'time', 'datetime')))
                {
                    $dateFields[$table . '.' . $key] = array('name' => $this->lang->dataview->objects[$table] . '.' . $field['name']);
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
                    $users = $this->loadModel('user')->getPairs('noletter');
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
     * Get dataviews.
     *
     * @param string $type
     * @access public
     * @return void
     */
    public function getList($type)
    {
        if($type == 'internal')
        {
            $dataviews = array();
            foreach($this->lang->dataview->tables as $code => $table)
            {
                $table['id']   = 0;
                $table['code'] = $code;
                $dataviews[]    = $table;
            }
            return $dataviews;
        }

        $result = array();
        $dataviews = $this->dao->select('*')->from(TABLE_DATAVIEW)->where('deleted')->eq(0)->fetchAll('', false);
        foreach($dataviews as $dataview)
        {
            $result[] = array('id' => $dataview->id, 'name' => $dataview->name, 'code' => 'custom_' . $dataview->id, 'desc' => $dataview->sql);
        }

        return $result;
    }

    /**
     * Create a dataview.
     *
     * @access public
     * @return bool | int
     */
    public function create()
    {
        $dataView = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('view', 'ztv_' . $this->post->code)
            ->setDefault('sql', '')
            ->skipSpecial('fields,objects,sql,langs')
            ->get();

        if(!$this->existView($dataView->view))
        {
            dao::$errors['view'][] = $this->lang->dataview->existView;
            return false;
        }

        $this->dao->insert(TABLE_DATAVIEW)->data($dataView, 'id')
            ->batchCheck($this->config->dataview->create->requiredFields, 'notempty')
            ->batchCheck('name,code', 'unique')
            ->check('code', 'code')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $viewID = $this->dao->lastInsertID();

        if(!empty($state->view) and !empty($state->sql)) $this->createViewInDB($viewID, $state->view, $state->sql);
        return $viewID;
    }

    /**
     * Save query a dataview.
     *
     * @param int $viewID
     * @access public
     * @return bool | int
     */
    public function querySave($viewID, $state)
    {
        $data = new stdclass();
        $data->fields = $state->getFields('json');
        $data->langs  = $state->getLangs('json');
        $data->sql    = $state->sql;
        $data->mode   = $state->mode;

        $this->dao->update(TABLE_DATAVIEW)->data($data)->where('id')->eq($viewID)->exec();
        if(dao::isError()) return false;

        $this->loadModel('sqlbuilder')->save($viewID, 'dataview', $state->sqlbuilder);

        $dataView = $this->dao->select('view,`sql`')->from(TABLE_DATAVIEW)->where('id')->eq($viewID)->fetch();

        $this->createViewInDB($viewID, $dataView->view, $dataView->sql);
        if(dao::isError()) return false;
    }

    /**
     * Create or replace a view in database.
     *
     * @param  int    $viewID
     * @param  string $viewName
     * @param  string $sql
     * @param  object $oldView
     * @access public
     * @return void
     */
    public function createViewInDB($viewID, $viewName, $sql, $oldView = null)
    {
        try
        {
            $this->dao->exec("CREATE OR REPLACE VIEW $viewName AS $sql");
        }
        catch(PDOException $exception)
        {
            if($oldView) $this->dao->update(TABLE_DATAVIEW)->data($oldView)->where('id')->eq($viewID)->exec();
            if(!$oldView) $this->dao->delete()->from(TABLE_DATAVIEW)->where('id')->eq($viewID)->exec();

            dao::$errors['sql'][] = $exception->getMessage();
        }
    }

    /**
     * Delete a view in database.
     *
     * @param  string $viewName
     * @access public
     * @return void
     */
    public function deleteViewInDB($viewName)
    {
        try
        {
            $this->dao->exec("DROP VIEW IF EXISTS $viewName");
        }
        catch(PDOException $exception)
        {
            dao::$errors['sql'][] = $exception->getMessage();
        }
    }

    /**
     * Check view exist.
     *
     * @param string @viewName
     * @access public
     * @return bool
     */
    public function existView($viewName)
    {
        if(isset($this->config->db->driver) and $this->config->db->driver == 'dm')
        {
            $sql = "SELECT * FROM ALL_VIEWS WHERE VIEW_NAME = '$viewName';";
        }
        else
        {
            $sql = "SELECT * FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_NAME = '$viewName'";
        }

        $dataView = $this->dbh->query($sql)->fetch();
        return empty($dataView);
    }

    /**
     * Update a dataview.
     *
     * @param  $dataViewID
     * @access public
     * @return bool | array
     */
    public function update($dataViewID)
    {
        $oldDataView = $this->getByID($dataViewID);

        $dataView = fixer::input('post')->get();

        $this->dao->update(TABLE_DATAVIEW)->data($dataView)
            ->batchCheck($this->config->dataview->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($dataViewID)
            ->exec();
        if(dao::isError()) return false;

        return common::createChanges($oldDataView, $dataView);
    }

    /**
     * Check usage of a dataview.
     *
     * @param  object $dataview
     * @access public
     * @return bool
     */
    public function checkUsed($dataview)
    {
        $views = $this->dao->select('id, name')->from(TABLE_DATAVIEW)->where('deleted')->eq('0')->andWhere('id')->ne($dataview->id)->andWhere('`sql`')->like("%{$dataview->view}%")->fetchPairs();
        if(!empty($views)) return true;

        $pivots = $this->dao->select('id, name')->from(TABLE_PIVOT)->where('deleted')->eq('0')->andWhere('`sql`')->like("%{$dataview->view}%")->fetchPairs();
        if(!empty($pivots)) return true;

        $charts = $this->dao->select('id, name')->from(TABLE_CHART)->where('deleted')->eq('0')->andWhere('`sql`')->like("%{$dataview->view}%")->fetchPairs();
        if(!empty($charts)) return true;

        $reports = $this->dao->select('id, name')->from(TABLE_REPORT)->where('`sql`')->like("%{$dataview->view}%")->fetchPairs();
        return !empty($reports);
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
     * Get fields of dataview.
     *
     * @param  int    $table
     * @access public
     * @return array
     */
    public function getFields($table)
    {
        $table = $this->dao->select('*')->from(TABLE_DATAVIEW)->beginIF(!empty($table))->where('id')->eq($table)->fi()->fetch();
        if(empty($table) or empty($table->view)) return array();

        if($this->existView($table->view)) return array();

        try
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $rawFields = $this->dao->descTable($table->view);
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        catch (PDOException $e)
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            dao::$errors[] = $e->getMessage();
            return false;
        }

        $this->loadModel('dev');
        $fields = array();
        foreach($rawFields as $rawField)
        {
            $firstPOS = strpos($rawField->type, '(');
            $type     = substr($rawField->type, 0, $firstPOS > 0 ? $firstPOS : strlen($rawField->type));
            $type     = str_replace(array('big', 'small', 'medium', 'tiny'), '', $type);
            $field    = array();

            $field['null']            = $rawField->null;
            $fields[$rawField->field] = $this->dev->setField($field, $rawField, $type, $firstPOS);
        }
        return $fields;
    }

    /**
     * Build origin tree menu.
     *
     * @param  string $selectedTable
     * @access public
     * @return string
     */
    public function getOriginTreeMenu($selectedTable = '')
    {
        $treeMenu = '';
        $tables   = $this->loadModel('dev')->getTables();
        $tables   = $this->processTable($tables);

        $originTreeMenu = array();
        foreach($this->lang->dataview->groupList as $group => $groupName)
        {
            $data = new stdclass();
            $data->key   = $group;
            $data->text  = $groupName;
            $data->url   = '';
            $data->items = array();

            if(isset($tables[$group]))
            {
                foreach($tables[$group] as $subTable => $table)
                {
                    $tableName = zget($this->lang->dev->tableList, $subTable, '');
                    if(!$tableName) continue;

                    $subTableData = new stdclass();
                    $subTableData->key  = $subTable;
                    $subTableData->text = $tableName;
                    $subTableData->url  = is_array($table) ? '' : helper::createLink('dataview', 'browse', "type=table&table=$table");

                    if(is_array($table))
                    {
                        foreach($table as $thirdTableKey => $thirdTable)
                        {
                            $thirdTableName = zget($this->lang->dev->tableList, $thirdTableKey, '');
                            if(empty($thirdTableName)) continue;

                            if(!isset($subTableData->items)) $subTableData->items = array();

                            $thirdTableData = new stdclass();
                            $thirdTableData->key  = $thirdTableKey;
                            $thirdTableData->text = $thirdTableName;
                            $thirdTableData->url  = helper::createLink('dataview', 'browse', "type=table&table=$thirdTable");

                            $subTableData->items[] = $thirdTableData;
                        }
                    }

                    $data->items[] = $subTableData;
                }
            }
            $originTreeMenu[] = $data;
        }

        return $originTreeMenu;
    }

    /**
     * Process table.
     *
     * @param  array $tables
     * @access public
     * @return array
     */
    public function processTable($tables)
    {
        foreach($tables as $group => $tableList)
        {
            if(!isset($this->config->dataview->groupChild[$group])) continue;

            foreach($this->config->dataview->groupChild[$group] as $secondName => $thirdTables)
            {
                $secondGroup = array();
                $thirdTables = explode(',', $thirdTables);

                foreach($thirdTables as $tableName)
                {
                    if(!isset($tableList[$tableName])) continue;

                    $secondGroup[$tableName] = $tableList[$tableName];
                    unset($tables[$group][$tableName]);
                }
                if($secondGroup) $tables[$group] = arrayUnion(array($secondName => $secondGroup), $tables[$group]);
            }
        }

        return $tables;
    }
}
