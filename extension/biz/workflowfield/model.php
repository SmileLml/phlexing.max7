<?php
/**
 * The model file of workflowfield module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowfield
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowfieldModel extends model
{
    /**
     * Check sql vars.
     *
     * @param  string $sql
     * @param  string $sign
     * @access public
     * @return array
     */
    public function checkSqlVar($sql = '', $sign = '\$')
    {
        $sql = $sql . ' ';
        preg_match_all("/{$sign}(\w+)/i", $sql, $out);
        return array_unique($out[1]);
    }

    /**
     * replace defined table names.
     *
     * @param  string $sql
     * @access public
     * @return void
     */
    public function replaceTableNames($sql)
    {
        if(preg_match_all("/TABLE_[A-Z]+/", $sql, $out))
        {
            rsort($out[0]);
            foreach($out[0] as $table)
            {
                if(!defined($table)) continue;
                $sql = str_replace($table, trim(constant($table), '`'), $sql);
            }
        }
        $sql = preg_replace("/= *'\!/U", "!='", $sql);
        return $sql;
    }

    /**
     * Check input sql and vars.
     *
     * @param  string $sql
     * @param  array  $vars
     * @access public
     * @return string || bool
     */
    public function checkSqlAndVars($sql = '', $vars = array())
    {
        $sqlVars = $this->checkSqlVar($sql, '\$');
        if($sqlVars)
        {
            foreach($sqlVars as $sqlVar)
            {
                if(isset($vars[$sqlVar])) $sql = str_replace("'$" . $sqlVar . "'", $this->dbh->quote($vars[$sqlVar]), $sql);
            }
        }
        $formVars = $this->checkSqlVar($sql, '\#');
        if($formVars)
        {
            foreach($formVars as $formVar)
            {
                if(isset($vars[$formVar])) $sql = str_replace("'#" . $formVar . "'", $this->dbh->quote($vars[$formVar]), $sql);
            }
        }
        $recordVars = $this->checkSqlVar($sql, '\@');
        if($recordVars)
        {
            foreach($recordVars as $recordVar)
            {
                if(isset($vars[$recordVar])) $sql = str_replace("'@" . $formVar . "'", $this->dbh->quote($vars[$recordVar]), $sql);
            }
        }

        $sql = $this->replaceTableNames($sql);

        try
        {
            $dataList = $this->dbh->query($sql)->fetchAll();
        }
        catch(PDOException $exception)
        {
            return $this->lang->workflowfield->error->wrongSQL . str_replace("'", "\'", $exception->getMessage());
        }
        return true;
    }

    /**
     * Get sql and vars.
     *
     * @param  string  $module
     * @param  string  $field
     * @param  string  $action
     * @access public
     * @return object
     */
    public function getSqlAndVars($module, $field = '', $action = '')
    {
        $data = $this->dao->select('*')->from(TABLE_WORKFLOWSQL)
            ->where('module')->eq($module)
            ->beginIF($field)->andWhere('field')->eq($field)->fi()
            ->beginIF($action)->andWhere('action')->eq($action)->fi()
            ->limit(1)
            ->fetch();
        if($data) $data->vars = json_decode($data->vars);
        return $data;
    }

    /**
     * Create sql and vars.
     *
     * @param  string  $module
     * @param  string  $field
     * @param  string  $sql
     * @param  array   $sqlVars
     * @param  array   $varValues
     * @access public
     * @return void
     */
    public function createSqlAndVars($module, $field, $sql, $sqlVars = array(), $varValues = array())
    {
        if(is_array($sqlVars))
        {
            foreach($sqlVars as $varName => $sqlVar)
            {
                $sqlVar = json_decode($sqlVar);
                if(!empty($varValues[$varName])) $sqlVar->default = $varValues[$varName];
                $sqlVars[$varName] = $sqlVar;
            }
        }

        $data = new stdclass();
        $data->module      = $module;
        $data->field       = $field;
        $data->sql         = $sql;
        $data->vars        = helper::jsonEncode($sqlVars);
        $data->createdBy   = $this->app->user->account;
        $data->createdDate = helper::now();
        $this->dao->insert(TABLE_WORKFLOWSQL)->data($data)->autoCheck()->exec();

        unset($_SESSION['sqlVars']);
    }

    /**
     * Delete sql and vars.
     *
     * @param  string $module
     * @param  string $field
     * @param  string $action
     * @access public
     * @return bool
     */
    public function deleteSqlAndVars($module = 0, $field = 0, $action = 0)
    {
        $this->dao->delete()->from(TABLE_WORKFLOWSQL)
            ->where('module')->eq($module)
            ->beginIF($field)->andWhere('field')->eq($field)->fi()
            ->beginIF($action)->andWhere('action')->eq($action)->fi()
            ->exec();
        return !dao::isError();
    }

    /**
     * Get a field by ID.
     *
     * @param  int    $id
     * @param  bool   $mergeOptions
     * @access public
     * @return object
     */
    public function getByID($id, $mergeOptions = true)
    {
        $field = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('id')->eq($id)->fetch();

        if($field) $field = $this->processFieldOptions($field, $mergeOptions);

        return $field;
    }

    /**
     * Get a field by field.
     *
     * @param  string $module
     * @param  string $field
     * @param  bool   $mergeOptions
     * @access public
     * @return object
     */
    public function getByField($module, $field, $mergeOptions = true)
    {
        $field = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->andWhere('field')->eq($field)
            ->fetch();

        if($field) $field = $this->processFieldOptions($field, $mergeOptions);

        return $field;
    }

    /**
     * Get last field.
     *
     * @param  string $module
     * @access public
     * @return object
     */
    public function getLastField($module)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->orderBy('order desc')->limit(1)->fetch();
    }

    /**
     * Get field list.
     *
     * @param  string $module
     * @param  string $orderBy
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getList($module, $orderBy = '`order`, id', $groupID = null)
    {
        $groupID = is_null($groupID) ? (int)$this->session->workflowGroupID : (int)$groupID;
        $fields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->andWhere('group')->eq($groupID)
            ->orderBy($orderBy)
            ->fetchAll('field', false);
        if($this->config->systemMode == 'light') unset($fields['program'], $fields['charter']);

        foreach($fields as $field) $field = $this->processFieldOptions($field);

        return $fields;
    }

    /**
     * Get field list by group.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getGroupList($orderBy = 'order')
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->orderBy($orderBy)->fetchGroup('module');
    }

    /**
     * Get id => $name pairs of field.
     *
     * @param  string $module
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPairs($module, $orderBy = 'order', $excludeFileds = array())
    {
        return $this->dao->select('id, name')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->beginIF(!empty($excludeFileds))->andWhere('field')->notin($excludeFileds)->fi()
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * Get field => name pairs.
     *
     * @param  string $module
     * @param  string $buildin
     * @param  bool   $emptyOption
     * @param  string $orderBy
     * @param  array  $excludeFileds
     * @param  array  $excludeControls
     * @access public
     * @return array
     */
    public function getFieldPairs($module, $buildin = 'all', $emptyOption = true, $orderBy = 'order', $excludeFileds = array(), $excludeControls = array())
    {
        $fields = $this->dao->select('field, name')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->beginIF($buildin != 'all')->andWhere('buildin')->eq($buildin)->fi()
            ->beginIF(!empty($excludeFileds))->andWhere('field')->notin($excludeFileds)->fi()
            ->beginIF(!empty($excludeControls))->andWhere('control')->notin($excludeControls)->fi()
            ->orderBy($orderBy)
            ->fetchPairs();
        if($this->config->systemMode == 'light') unset($fields['program'], $fields['charter']);

        if($emptyOption) $fields = array_merge(array('' => ''), $fields);

        return $fields;
    }

    /**
     * filter the useless fields by config.
     *
     * @param  array $fields
     * @access public
     * @return array
     */
    public function filterUselessFields($fields)
    {
        $disabledFields = $this->config->workflowfield->disabledFields['subTables'];
        foreach($fields as $fieldID => $field)
        {
            if($disabledFields and strpos(",{$disabledFields},", ",{$fieldID},") !== false) unset($fields[$fieldID]);
        }
        return $fields;
    }

    /**
     * Get field, control of a flow.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getControlPairs($module, $excludeFileds = array())
    {
        return $this->dao->select('field, control')
            ->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->beginIF(!empty($excludeFileds))->andWhere('field')->notin($excludeFileds)->fi()
            ->fetchPairs();
    }

    /**
     * Get number fields exclude id, parent and the formula fields.
     *
     * @param  string $module
     * @param  bool   $includeFormula
     * @access public
     * @return array
     */
    public function getNumberFields($module, $includeFormula = false)
    {
        return $this->dao->select('field, name')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->andWhere('options')->notin('category,prevModule')
            ->andWhere('field')->notin('id, parent')
            ->andWhere('type')->in($this->config->workflowfield->numberTypes)
            ->beginIF($includeFormula)->andWhere('control')->in('integer,decimal,formula')->fi()
            ->beginIF(!$includeFormula)->andWhere('control')->in('integer,decimal')->fi()
            ->orderBy('order, id')
            ->fetchPairs();
    }

    /**
     * Get category field list.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getCategoryFields($module = '')
    {
        $categoryDatasources = $this->loadModel('workflowdatasource')->getCategoryDatasources();

        $flows  = $this->dao->select('module, name')->from(TABLE_WORKFLOW)->where('module')->eq($module)->orWhere('parent')->eq($module)->fetchPairs();
        $fields = $this->dao->select('module, field, name, options')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->in(array_keys($flows))
            ->andWhere('options', true)->eq('category')
            ->orWhere('options')->in($categoryDatasources)
            ->markRight(1)
            ->orderBy('module, order, id')
            ->fetchGroup('module');

        $fieldList = array();
        foreach($flows as $flowModule => $flowName)
        {
            if(empty($fields[$flowModule])) continue;

            $flowFields = $fields[$flowModule];
            foreach($flowFields as $field)
            {
                $type = $field->options == 'category' ? $field->module . '_' . $field->field : 'datasource_' . $field->options;

                if($field->module != $module) $field->name = $flowName . $field->name;

                if(empty($fieldList[$type])) $fieldList[$type] = $field;
            }
        }

        return $fieldList;
    }

    /**
     * Get category field list group.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getCategoryFieldGroups()
    {
        $categoryDatasources = $this->loadModel('workflowdatasource')->getCategoryDatasources();

        $flows  = $this->dao->select('module, parent, name')->from(TABLE_WORKFLOW)->fetchAll();
        $fields = $this->dao->select('module, field, name, options')->from(TABLE_WORKFLOWFIELD)
            ->where('options')->eq('category')
            ->orWhere('options')->in($categoryDatasources)
            ->orderBy('module, order, id')
            ->fetchGroup('module');

        $groups = array();
        foreach($flows as $flow)
        {
            if(empty($fields[$flow->module])) continue;

            $module = $flow->parent ? $flow->parent : $flow->module;

            $flowFields = $fields[$flow->module];
            foreach($flowFields as $field)
            {
                $name = $flow->parent ? $flow->name . $field->name : $field->name;
                $type = $field->options == 'category' ? $field->module . '_' . $field->field : 'datasource_' . $field->options;

                if(empty($groups[$module][$type])) $groups[$module][$type] = $name;
            }
        }

        return $groups;
    }

    /**
     * Get id => field pairs.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function getCustomFields($module)
    {
        return $this->dao->select('id, field')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->andWhere('field')->notin(array_keys($this->config->workflowfield->default->fields))
            ->fetchPairs();
    }

    /**
     * Get fields can export.
     *
     * @param  string $module
     * @param  string $buildin
     * @access public
     * @return array
     */
    public function getExportFields($module, $buildin = '')
    {
        return $this->dao->select('field, name')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->beginIF($buildin != '')->andWhere('buildin')->eq($buildin)->fi()
            ->andWhere('canExport')->eq('1')
            ->orderBy('`exportOrder`, `order`')
            ->fetchPairs();
    }

    /**
     * Get fields can search.
     *
     * @param  string $module
     * @param  string $buildin
     * @access public
     * @return array
     */
    public function getSearchFields($module, $buildin = 'all')
    {
        return $this->dao->select('field, name')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->beginIF(is_numeric($buildin))->andWhere('buildin')->eq($buildin)->fi()
            ->andWhere('canSearch')->eq('1')
            ->orderBy('`searchOrder`, `order`')
            ->fetchPairs();
    }

    /**
     * Get value fields of a module.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getValueFields($module)
    {
        return $this->dao->select('field')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('isValue')->eq('1')->orderBy('order, id')->fetchPairs();
    }

    /**
     * Get field control.
     *
     * @param  string  $module
     * @param  string  $field
     * @param  string  $value
     * @param  string  $elementName
     * @param  string  $elementID
     * @param  string  $attr
     * @access public
     * @return string
     */
    public function getFieldControl($module, $field, $value = '', $elementName = '', $elementID = '', $attr = '')
    {
        $html  = '';
        $value = $value ? urldecode(base64_decode($value)) : '';
        $name  = $elementName ? urldecode(base64_decode($elementName)) : 'values[]';
        $id    = $elementID ? "id='$elementID'" : "id='" . str_replace(array('[', ']'), '', $name) . "'";
        $field = $this->getByField($module, $field);

        if(!$field) return html::input($name, $value, "$id class='form-control' $attr autocomplete='off'");

        if(strpos(',select,multi-select,radio,checkbox,', ",{$field->control},") === false)
        {
            $class = $field->control == 'date' ? 'form-date' : ($field->control == 'datetime' ? 'form-datetime' : '');
            return html::input($name, $value, "$id class='form-control $class' $attr autocomplete='off'");
        }

        $options = $this->getFieldOptions($field, true, $value, '', $this->config->flowLimit);
        if($field->options == 'user')
        {
            $this->app->loadLang('workflowlayout', 'flow');
            $options = arrayUnion($this->lang->workflowlayout->default->user, $options);
        }
        elseif($field->options == 'dept')
        {
            $this->app->loadLang('workflowlayout', 'flow');
            $options = arrayUnion($this->lang->workflowlayout->default->dept, $options);
        }

        $data = "data-module='{$module}' data-field='{$field->field}'";

        if($field->control == 'select' or $field->control == 'radio') return html::select($name, $options, $value, "$id class='form-control picker-select' $attr $data");

        return html::select($name . '[]', $options, $value, "$id class='form-control picker-select' $attr multiple='multiple' $data");
    }

    /**
     * Get field options.
     *
     * @param  object $field
     * @param  bool   $emptyOption
     * @param  string $keys
     * @param  string $search
     * @param  int    $limit
     * @param  bool   $importData
     * @access public
     * @return array
     */
    public function getFieldOptions($field, $emptyOption = true, $keys = '', $search = '', $limit = 0, $importData = false)
    {
        if($this->app->clientDevice == 'mobile' && $this->app->getViewType() == 'mhtml') $limit = 0;

        $emptyKey = in_array($field->type, $this->config->workflowfield->numberTypes) ? 0 : '';
        $options  = empty($field->options) ? array($emptyKey => '') : (array)$field->options;

        if($field->options == 'sql')
        {
            if(empty($field->sql) or empty($field->sqlVars)) $field = $this->processFieldOptions($field);

            $options = $this->getOptionsBySql($field->sql, $field->sqlVars);
        }
        elseif($field->options == 'user')
        {
            $options = $this->loadModel('user')->getDeptPairs('noclosed,nodeleted,noforbidden');
        }
        elseif($field->options == 'dept')
        {
            $options = $this->loadModel('dept')->getDeptPairs();
        }
        elseif($field->options == 'category')
        {
            $type    = $field->module . '_' . $field->field;
            $options = $this->loadModel('tree')->getOptionMenu(0, $type);
        }
        elseif($field->options == 'program')
        {
            $options = $this->loadModel('program')->getPairs();
            $field->default = (int)$this->session->program;
        }
        elseif($field->options == 'product')
        {
            $options = $this->loadModel('product')->getPairs();
            $field->default = (int)$this->session->product;
        }
        elseif($field->options == 'project')
        {
            $options = $this->loadModel('project')->getPairs();
            $field->default = (int)$this->session->project;
        }
        elseif($field->options == 'execution')
        {
            $options = $this->loadModel('execution')->getPairs();
            $field->default = (int)$this->session->execution;
        }
        elseif($field->options == 'prevModule')
        {
            $prevModule = $this->dao->select('prev')->from(TABLE_WORKFLOWRELATION)
                ->where('next')->eq($field->module)
                ->andWhere('field')->eq($field->field)
                ->fetch('prev');
            if(!$prevModule) return array('');

            $prevFlow = $this->loadModel('workflow', 'flow')->getByModule($prevModule);
            if(!$prevFlow) return array('');

            $options = $this->loadModel('flow')->getDataPairs($prevFlow);
        }
        elseif(is_int($field->options) or (is_string($field->options) && (int)$field->options > 0))
        {
            $options = $this->getOptionsByDatasource($field->options, $keys, $search, $limit);
        }

        if($limit > 0)
        {
            $results = array();
            if(!empty($keys))
            {
                if(!is_array($keys)) $keys = explode(',', $keys);
                $keys = array_unique(array_filter($keys));
                foreach($keys as $key)
                {
                    // If the data are imported, the keys is values.
                    // 如果是导入的数据，keys其实是值。
                    if($importData)
                    {
                        $k = zget(array_flip($options), $key, '');
                        if($k) $results[$k] = $key;
                    }
                    else
                    {
                        if(isset($options[$key])) $results[$key] = $options[$key];
                    }
                }
            }

            $index = 1;
            foreach($options as $key => $value)
            {
                if($index > $limit)
                {
                    if(empty($search)) $results['ajax_search_more'] = $this->lang->more . $this->lang->ellipsis;
                    break;
                }

                if(empty($search))
                {
                    $results[$key] = $value;
                    if($value) $index++;
                }
                elseif(stripos($value, $search) !== false)
                {
                    $results[$key] = $value;
                    $index++;
                }
            }

            $options = $results;
        }

        /* $options中可能包含 0=>'' 或者 ''=>'' 的键值对，需要去掉其中一个。*/
        if($emptyKey === '' && empty($options[0])) unset($options[0]);
        if($emptyKey === 0)
        {
            if(empty($options[''])) unset($options['']);
            foreach($options as $key => $option)
            {
                if(!is_numeric($key)) unset($options[$key]);    // If the field's type is number, unset the option with a string key.
            }
        }

        if((zget($field, 'control') == 'select' or zget($field, 'control') == 'multi-select') && $emptyOption && !isset($options[$emptyKey]))
        {
            $options = arrayUnion(array($emptyKey => ''), $options);
        }

        return $options;
    }

    /**
     * Get options by sql and sqlvars.
     *
     * @param  string $sql
     * @param  array  $sqlVars
     * @access public
     * @return array
     */
    public function getOptionsBySql($sql = '', $sqlVars = array())
    {
        if(!$sql) return array();

        if(is_array($sqlVars))
        {
            foreach($sqlVars as $sqlVar)
            {
                $sql = str_replace("'$" . $sqlVar->varName . "'", $this->dbh->quote($sqlVar->default), $sql);
            }
        }
        $sql = $this->replaceTableNames($sql);

        try
        {
            $options  = array('' => '');
            $dataList = $this->dbh->query($sql)->fetchAll();
            foreach($dataList as $data)
            {
                $data = (array)$data;
                if(count($data) > 1)
                {
                    $key   = current($data);
                    $value = next($data);
                    $options[$key] = $value;
                }
                elseif(count($data) > 0)
                {
                    $key   = current($data);
                    $value = current($data);
                    $options[$key] = $value;
                }
            }

            return $options;
        }
        catch(PDOException $exception)
        {
            return array();
        }
    }

    /**
     * Get options by view.
     *
     * @param  object $datasource
     * @param  string $keys
     * @param  string $search
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getOptionsByView($datasource, $keys = '', $search = '', $limit = 0)
    {
        if($limit == 0) $limit = $this->config->flowLimit;
        if($this->app->clientDevice == 'mobile' && $this->app->getViewType() == 'mhtml') $limit = 0;

        $keyField   = $datasource->keyField;
        $valueField = $datasource->valueField;
        if(!empty($search))
        {
            $options = $this->dao->select("`{$keyField}`, `{$valueField}`")->from($datasource->view)
                ->where("`{$valueField}`")->like("%{$search}%")
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchPairs();

            return $options;
        }
        else
        {
            if($limit > 0)
            {
                $options = array();
                if($keys) $options = arrayUnion($options, $this->dao->select("`{$keyField}`, `{$valueField}`")->from($datasource->view)->where("`{$keyField}`")->in($keys)->fetchPairs());

                $count = count($options);
                if($limit > $count)
                {
                    $extraOptions = $this->dao->select("`{$keyField}`, `{$valueField}`")->from($datasource->view)
                        ->where(1)
                        ->beginIF($options)->andWhere("`{$keyField}`")->notin(array_keys($options))->fi()
                        ->limit($limit - $count)->fi()
                        ->fetchPairs();
                    $options = arrayUnion($options, $extraOptions);
                }

                ksort($options);

                if(count($options) >= $limit) $options['ajax_search_more'] = $this->lang->more . $this->lang->ellipsis;

                return $options;
            }
            else
            {
                return $this->dao->select("`{$keyField}`, `{$valueField}`")->from($datasource->view)->fetchPairs();
            }
        }
    }

    /**
     * Get options by a datasource.
     *
     * @param  int    $datasourceID
     * @param  string $keys
     * @param  string $search
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getOptionsByDatasource($datasourceID, $keys = '', $search = '', $limit = 0)
    {
        $datasource = $this->loadModel('workflowdatasource', 'flow')->getByID($datasourceID);
        if(!$datasource) return array();

        if($datasource->type == 'option') return json_decode($datasource->datasource, true);

        if($datasource->type == 'sql') return $this->getOptionsByView($datasource, $keys, $search, $limit);

        if($datasource->type == 'category') return $this->loadModel('tree')->getOptionMenu(0, 'datasource_' . $datasource->id);

        if($datasource->type == 'func') return array();

        if($datasource->type == 'system')
        {
            $module = $datasource->module;
            $method = $datasource->method;

            $defaultParams = $this->workflowdatasource->getDefaultParams($module, $method);
            foreach($datasource->params as $param)
            {
                if($param->name == 'limit') $param->value = 0;   // Set the limit param to 0 to query all values from database.

                if(isset($defaultParams[$param->name])) $defaultParams[$param->name] = $param->value;
            }

            $className = class_exists('bizext' . ucwords($module)) ? ('bizext' . ucwords($module)) : ($module . 'model');
            $class     = new $className();
            $options   = call_user_func_array(array($class, $method), $defaultParams);

            if(!is_array($options)) return array();

            foreach($options as $option)
            {
                if(is_object($option) || is_array($option)) return array();
            }

            if(!isset($options['']))
            {
                $options = array_reverse($options, true);
                $options[''] = '';
                $options = array_reverse($options, true);
            }

            return $options;
        }

        if($datasource->type == 'lang')
        {
            if($datasource->datasource == 'currency') return $this->loadModel('common')->getCurrencyList();

            $options = $this->config->workflowdatasource->langList[$datasource->datasource];
            $app     = zget($options, 'app', '');
            $module  = zget($options, 'module', '');
            $field   = zget($options, 'field', '');

            if(!$module or !$field) return array();

            $this->app->loadLang($module, $app);

            if(($module == 'common' && !isset($this->lang->$field)) || ($module != 'common' && !isset($this->lang->$module->$field))) return array();

            $options = $module == 'common' ? $this->lang->$field : $this->lang->$module->$field;

            if(!in_array('', $options)) $options = arrayUnion(array('' => ''), $options);

            return $options;

        }

        return array();
    }

    /**
     * Get datasource pairs of field.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getDatasourcePairs($type)
    {
        $datasources = $this->loadModel('workflowdatasource', 'flow')->getPairs('noempty');
        foreach($datasources as $key => $datasource)
        {
            if(strpos(',deptManager,actor,today,now,form,record,', ",{$key},") !== false) unset($datasources[$key]);
        }

        $datasources = arrayUnion($datasources, $this->lang->workflowfield->optionTypeList);
        if($type == 'table') unset($datasources['prevModule']);

        return $datasources;
    }

    /**
     * Process field length.
     *
     * @param  object $field
     * @access public
     * @return array | object
     */
    public function processFieldLength($field = null)
    {
        if($field->type != 'decimal' && $field->type != 'char' && $field->type != 'varchar')
        {
            $field->length = '';
            return $field;
        }

        $error  = array();
        $config = $this->config->workflowfield;
        $lang   = $this->lang->workflowfield;
        $length = str_replace(array('.','，'), ',', $field->length);
        if($field->type == 'decimal')
        {
            $integerDigits = (int)$field->integerDigits;
            $decimalDigits = (int)$field->decimalDigits;

            if($integerDigits < $config->min->integerDigits or $integerDigits > $config->max->integerDigits) $error['integerDigits'][] = sprintf($lang->error->length, $lang->integerDigits, $config->min->integerDigits, $config->max->integerDigits);
            if($decimalDigits < $config->min->decimalDigits or $decimalDigits > $config->max->decimalDigits) $error['decimalDigits'][] = sprintf($lang->error->length, $lang->decimalDigits, $config->min->decimalDigits, $config->max->decimalDigits);
            if($integerDigits < $decimalDigits)                                                              $error['integerDigits'][] = sprintf($lang->error->digits, $lang->integerDigits, $lang->decimalDigits);

            $length = $integerDigits . ',' . $decimalDigits;

            if((int)$length == 0) $length = $config->default->integerDigits . ',' . $config->default->decimalDigits;
        }
        else
        {
            $length    = (int)$length;
            $lengthVar = $field->type . 'Length';

            if($length < $config->min->$lengthVar or $length > $config->max->$lengthVar) $error['length'] = sprintf($lang->error->length, $lang->length, $config->max->$lengthVar, $config->min->$lengthVar);
            if($length == 0) $length = $config->default->$lengthVar;
        }

        if($error) return array('result' => 'fail', 'message' => $error);

        $field->length = $length;

        return $field;
    }

    /**
     * Process field options.
     *
     * @param  object $field
     * @param  bool   $mergeOptions
     * @access public
     * @return object
     */
    public function processFieldOptions($field, $mergeOptions = true)
    {
        if($field->options == 'sql')
        {
            if(empty($field->sql) && !empty($field->module) && !empty($field->field))
            {
                $data = $this->getSqlAndVars($field->module, $field->field);
                if($data)
                {
                    $field->sql     = $data->sql;
                    $field->sqlVars = $data->vars;
                }
            }

            if(empty($field->sql))     $field->sql     = '';
            if(empty($field->sqlVars)) $field->sqlVars = array();

            return $field;
        }

        if(empty($field->sql))     $field->sql     = '';
        if(empty($field->sqlVars)) $field->sqlVars = array();

        if(in_array($field->options, array('user', 'dept', 'category', 'prevModule', 'program', 'product', 'project', 'execution'))) return $field;
        if(is_int($field->options)) return $field;

        if(is_string($field->options)) $field->options = json_decode($field->options, true);
        if(!$field->options) $field->options = (array)$field->options;

        /* 把子状态的options由二维数组转成一维数组。 */
        if($mergeOptions && $field->field == 'subStatus' && is_array($field->options))
        {
            foreach($field->options as $parent => $subStatus)
            {
                if(!is_array($subStatus)) continue;

                unset($field->options[$parent]);

                $options = zget($subStatus, 'options', array());

                foreach($options as $code => $name) $field->options[$code] = $name;
            }
        }

        return $field;
    }

    /**
     * Create a field.
     *
     * @param  string $module
     * @param  object $field
     * @param  int    $groupID
     * @param  bool   $duplicateName
     * @access public
     * @return bool|array
     */
    public function create($module, $field = null, $groupID = null, $duplicateName = false)
    {
        $groupID = is_null($groupID) ? (int)$this->session->workflowGroupID : (int)$groupID;
        $flow    = $this->loadModel('workflow', 'flow')->getByModule($module, false, $groupID);
        if(!$flow) return array('result' => 'fail', 'message' => $this->lang->workflow->error->notFound);

        if(!$field)
        {
            $field = fixer::input('post')
                ->add('module', $module)
                ->add('group', $groupID)
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', helper::now())
                ->join('rules', ',')
                ->setForce('field', str_replace(' ', '', $this->post->field))
                ->setDefault('length', 0)
                ->skipSpecial('expression')
                ->get();
            if(!in_array($field->control, array('textarea', 'richtext', 'checkbox', 'multi-select')) && $this->post->default) $field->default = $this->post->default;
        }

        if($field->field == 'delay' && in_array($field->module, $this->config->workflowfield->hasDelayModules)) return array('result' => 'fail', 'message' => array('field' => sprintf($this->lang->workflowfield->error->existDelay, $field->field)));

        $field  = $this->processField($field);
        $result = $this->checkField($field);
        if(is_array($result) && zget($result, 'result') == 'fail') return $result;
        $field = $result;

        $existNameFields = !$duplicateName ? $this->dao->select('id,`group`')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($field->module)->andWhere('name')->eq($field->name)->fetchAll('group') : array();
        $existCodeFields = $this->dao->select('id,`group`')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($field->module)->andWhere('field')->eq($field->field)->fetchAll('group');
        $uniqueErrors    = array('name' => '', 'code' => '');

        if(isset($existNameFields[$groupID])) $uniqueErrors['name'] = sprintf($this->lang->error->unique , $this->lang->workflowfield->name, $field->name);
        if(isset($existCodeFields[$groupID])) $uniqueErrors['code'] = sprintf($this->lang->error->unique , $this->lang->workflowfield->field, $field->field);
        unset($existNameFields[$groupID], $existCodeFields[$groupID]);

        if(empty($uniqueErrors['name']) && !empty($existNameFields)) $uniqueErrors['name'] = sprintf($this->lang->workflowfield->error->unique , $this->lang->workflowfield->name);
        if(empty($uniqueErrors['code']) && !empty($existCodeFields)) $uniqueErrors['code'] = sprintf($this->lang->workflowfield->error->unique , $this->lang->workflowfield->field);
        if(!empty($uniqueErrors['name'])) dao::$errors['name'][]  = $uniqueErrors['name'];
        if(!empty($uniqueErrors['code'])) dao::$errors['field'][] = $uniqueErrors['code'];
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $skipFields     = 'integerDigits, decimalDigits, optionType, sql';
        $requiredFields = $this->config->workflowfield->require->create;
        if($field->control == 'formula') $requiredFields .= ',expression';
        $this->dao->insert(TABLE_WORKFLOWFIELD)->data($field, $skipFields)->autoCheck()
            ->batchCheck($requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $id         = $this->dao->lastInsertId();
        $isNumber   = in_array($field->type, $this->config->workflowfield->numberTypes);
        $hasDefault = strpos(',date,datetime,text,', ",{$field->type},") === false;

        if($field->length)
        {
            if($field->type == 'decimal')
            {
                list($integerDigits, $decimalDigits) = explode(',', $field->length);
                $field->length = $integerDigits + $decimalDigits . ',' . $decimalDigits;
            }

            $field->type .= "($field->length)";
        }

        $fields = $this->dao->descTable($flow->table);
        if(!isset($fields[$field->field]))
        {
            $sql  = "ALTER TABLE `$flow->table` ADD `$field->field` $field->type";
            $sql .= $hasDefault ? ' NOT NULL' : ' NULL';

            if($hasDefault)
            {
                if($field->default)
                {
                    $magicQuote = (version_compare(phpversion(), '5.4', '<') and function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc());
                    if($magicQuote) $field->default = stripslashes($field->default);
                    $field->default = $this->dbh->quote($field->default);
                    $sql .= " DEFAULT $field->default";
                }
                else
                {
                    $sql .= $isNumber ? ' DEFAULT 0' : " DEFAULT ''";
                }
            }

            try
            {
                $this->dbh->query($sql);
            }
            catch(PDOException $exception)
            {
                $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('id')->eq($id)->exec();
                return array('result' => 'fail', 'message' => $exception->getMessage());
            }
        }

        /* Create sql and vars. */
        if($field->optionType == 'sql') $this->createSqlAndVars($module, $field->field, $field->sql);

        $this->syncSubTableFields($flow, $id);

        return $id;
    }

    /**
     * 同步子表字段
     * Sync sub table fields.
     *
     * @param  object $currentFlow
     * @param  int    $fieldID
     * @access public
     * @return void
     */
    public function syncSubTableFields($currentFlow, $fieldID)
    {
        if($currentFlow->type != 'table') return;

        $field = $this->dao->findById($fieldID)->from(TABLE_WORKFLOWFIELD)->fetch();
        unset($field->id);

        $flows = $this->dao->select('`group`')->from(TABLE_WORKFLOW)->where('module')->eq($currentFlow->module)->andWhere('group')->ne($currentFlow->group)->fetchAll();
        foreach($flows as $flow)
        {
            $field->group = $flow->group;
            $this->dao->insert(TABLE_WORKFLOWFIELD)->data($field)->exec();
        }
    }

    /**
     * Create fields from imported file.
     *
     * @param  string $module
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function createFromImport($module, $groupID = null)
    {
        $groupID = is_null($groupID) ? (int)$this->session->workflowGroupID : (int)$groupID;
        $flow    = $this->loadModel('workflow')->getByModule($module, false, $groupID);

        $fields  = array();
        $errors  = array();
        $field   = new stdclass();
        $field->module = $module;
        $field->group  = $groupID;
        foreach($this->post->name as $key => $name)
        {
            if(!$name or !$this->post->field[$key]) continue;

            $field->name          = $name;
            $field->field         = $this->post->field[$key];
            $field->control       = $this->post->control[$key];
            $field->type          = $this->post->type[$key];
            $field->length        = $this->post->length[$key];
            $field->integerDigits = $this->post->integerDigits[$key];
            $field->decimalDigits = $this->post->decimalDigits[$key];
            $field->optionType    = isset($this->post->optionType[$key]) ? $this->post->optionType[$key] : '';
            $field->sql           = $this->post->sql[$key];
            $field->default       = $this->post->default[$key];

            if(strpos(',select,multi-select,checkbox,radio,', ",{$field->control},") !== false)
            {
                $field->options = array();

                $options = explode("\n", $this->post->options[$key]);
                foreach($options as $option)
                {
                    $commaPosition = strpos($option, ',');
                    if($commaPosition === false)
                    {
                        $code = $option;
                        $name = '';
                    }
                    else
                    {
                        $code = substr($option, 0, $commaPosition);
                        $name = substr($option, $commaPosition + 1);
                    }

                    $field->options['code'][] = $code;
                    $field->options['name'][] = $name;
                }
            }

            $result = $this->create($module, $field);
            if(is_array($result) && zget($result, 'result') == 'fail')
            {
                if(is_string($result['message'])) $errors = $result['message'];
                if(is_array($result['message']))
                {
                    foreach($result['message'] as $elementID => $message) $errors[$elementID . $key][] = $message;
                }
                continue;
            }

            $fields[$result] = $field->field;
        }

        if($errors && $fields)
        {
            try
            {
                $flow = $this->loadModel('workflow', 'flow')->getByModule($module);

                foreach($fields as $field)
                {
                    $sql = "ALTER TABLE `{$flow->table}` DROP `{$field}`";
                    $this->dbh->query($sql);
                }
            }
            catch(PDOException $exception)
            {
                $errors = $exception->getMessage() . ". The sql is : " . $sql;
            }

            $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('id')->in(array_keys($fields))->exec();
        }

        return $errors;
    }

    /**
     * Update a field.
     *
     * @param  int    $id
     * @access public
     * @return bool|array
     */
    public function update($id)
    {
        $oldField = $this->getByID($id);
        if(!$oldField) return false;

        $flow = $this->loadModel('workflow')->getByModule($oldField->module, false, $oldField->group);
        if(!$flow) return false;

        $fixer = fixer::input('post')
            ->setIF(!$this->post->isValue, 'isValue', '0')
            ->setIF(in_array($this->post->control, $this->config->workflowfield->hiddenPlaceholder), 'placeholder', '')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now());

        /* 内置字段中subStatus可以编辑，其他不可编辑。默认字段中status、subStatus可以编辑，其他不可编辑。非内置且非默认字段可以编辑。*/
        /* The subStatus in the built-in field can be edited, and the others cannot be edited. The status, status, and status in the default field can be edited, and others cannot be edited. Non-built-in and non-default fields can be edited. */
        $readonly = (($oldField->buildin && $oldField->field != 'subStatus') or (!$oldField->buildin && $oldField->readonly));
        if($readonly)
        {
            $field = $fixer->get();
            $this->dao->update(TABLE_WORKFLOWFIELD)->data($field)
                ->autoCheck()
                ->check('name', 'unique', "module='$oldField->module' && id!=$id")
                ->where('id')->eq($id)
                ->exec();
            return !dao::isError();
        }

        $field = $fixer->join('rules', ',')
            ->setForce('field', $oldField->field)
            ->setDefault('length', 0)
            ->setDefault('default', '')
            ->setIF(!$this->post->rules, 'rules', '')
            ->skipSpecial('expression')
            ->get();

        if($field->field == 'delay' && in_array($oldField->module, $this->config->workflowfield->hasDelayModules)) return array('result' => 'fail', 'message' => array('field' => sprintf($this->lang->workflowfield->error->existDelay, $field->field)));

        $field = $this->processField($field);
        if(!in_array($field->control, array('textarea', 'richtext', 'checkbox', 'multi-select'))) $field->default = $this->post->default ? $this->post->default : $field->default;

        $result = $this->checkField($field);
        if(is_array($result) && zget($result, 'result') == 'fail') return $result;
        $field = $result;

        $isCustomOptions = ($oldField->control == $field->control && in_array($field->control, array('select', 'multi-select', 'radio', 'checkbox')) && $field->optionType == 'custom' && is_array($oldField->options));
        if($oldField->role == 'quote' && $isCustomOptions)
        {
            $this->loadModel('workflowgroup');
            $otherTemplateFields = $this->dao->select('t1.id,t1.`group`,t1.`field`,t1.`options`,t2.name as groupName')->from(TABLE_WORKFLOWFIELD)->alias('t1')
                ->leftJoin(TABLE_WORKFLOWGROUP)->alias('t2')->on('t1.`group`=t2.id')
                ->where('t1.module')->eq($oldField->module)
                ->andWhere('t1.field')->eq($oldField->field)
                ->andWhere('t1.id')->ne($id)
                ->fetchAll();
            $options     = json_decode($field->options, true);
            $flipOptions = array_flip($options);
            $errors      = array();
            foreach($otherTemplateFields as $otherField)
            {
                $otherOptions = json_decode($otherField->options, true);
                $groupName    = $otherField->groupName;
                if($otherField->group == 0) $groupName = $this->lang->workflowgroup->workflow->exclusiveList[0];
                foreach($otherOptions as $key => $value)
                {
                    if(isset($options[$key])       && $options[$key] != $value)     $errors[] = sprintf($this->lang->workflowfield->error->usedKey, $key, $groupName, $value);
                    if(isset($flipOptions[$value]) && $flipOptions[$value] != $key) $errors[] = sprintf($this->lang->workflowfield->error->usedValue, $value, $groupName, $key);
                }
            }
            if($errors) dao::$errors[] = implode("\n", $errors);
            if(dao::isError()) return false;
        }

        $skipFields     = 'integerDigits,decimalDigits,optionType,sql,parentCode,parentName,optionCode,optionName,optionDefault';
        $requiredFields = $this->config->workflowfield->require->edit;
        if($field->control == 'formula') $requiredFields .= ',expression';
        $this->dao->update(TABLE_WORKFLOWFIELD)->data($field, $skipFields)->autoCheck()
            ->check('field', 'unique', "module='$oldField->module' && id!=$id && `group`='{$oldField->group}'")
            ->check('name', 'unique', "module='$oldField->module' && id!=$id && `group`='{$oldField->group}'")
            ->batchCheck($requiredFields, 'notempty')
            ->where('id')->eq($id)
            ->exec();

        if(dao::isError()) return false;

        if($oldField->role == 'custom')
        {
            if($flow->type == 'flow')
            {
                $skipFields .= ',rules,default';
                if($isCustomOptions) $skipFields .= ',options';
            }

            $this->dao->update(TABLE_WORKFLOWFIELD)->data($field, $skipFields)->autoCheck()->where('module')->eq($oldField->module)->andWhere('field')->eq($oldField->field)->andWhere('group')->ne($oldField->group)->exec();
        }

        $result = $this->processTable($flow->table, $oldField, $field);
        if(is_array($result)) return $result;

        if($field->optionType == 'sql')
        {
            $this->deleteSqlAndVars($oldField->module, $oldField->field);
            /* Create sql and vars. */
            $this->createSqlAndVars($oldField->module, $field->field, $field->sql);
        }

        if($oldField->field != $field->field)
        {
            $result = $this->updateRelated($flow, $oldField, $field->field);
            if(is_array($result)) return $result;
        }

        return true;
    }

    /**
     * Update related tables when the field code changed.
     *
     * @param  object $flow
     * @param  object $oldField
     * @param  string $newField
     * @access public
     * @return bool
     */
    public function updateRelated($flow, $oldField, $newField)
    {
        $alerts  = array();
        $groupID = (int)($flow->group ?? 0);

        /* Field */
        $fields = $this->dao->select('id, expression')->from(TABLE_WORKFLOWFIELD)
            ->where('control')->eq('formula')
            ->andWhere('expression')->like("%{$oldField->field}%")
            ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($groupID)->fi()
            ->beginIF($flow->type == 'flow')->andWhere('module')->eq($flow->module)->fi()
            ->beginIF($flow->type == 'table')
            ->andWhere('module', true)->eq($flow->parent)
            ->orWhere('module')->eq($flow->module)
            ->markRight(1)
            ->fi()
            ->fetchPairs();
        foreach($fields as $id => $expression)
        {
            $items = json_decode($expression);
            foreach($items as $key => $item)
            {
                if($item->type == 'target' && $item->module == $flow->module && $item->field == $oldField->field)
                {
                    $item->field = $newField;
                    $items[$key] = $item;
                }
            }

            $expression = helper::jsonEncode($items);
            $this->dao->update(TABLE_WORKFLOWFIELD)->set('expression')->eq($expression)->where('id')->eq($id)->exec();
        }

        /* Layout */
        $this->dao->update(TABLE_WORKFLOWLAYOUT)->set('field')->eq($newField)->where('module')->eq($flow->module)
            ->andWhere('field')->eq($oldField->field)
            ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($groupID)->fi()
            ->exec();

        /* Action */
        $this->loadModel('workflowaction', 'flow');
        $actions = $this->dao->select('id, name, conditions, verifications, hooks, linkages')->from(TABLE_WORKFLOWACTION)
            ->where('1=1')
            ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($groupID)->fi()
            ->beginIF($flow->type == 'flow')->andWhere('module')->eq($flow->module)->fi()
            ->beginIF($flow->type == 'table')->andWhere('module')->eq($flow->parent)->fi()
            ->andWhere('conditions', true)->like("%{$oldField->field}%")
            ->orWhere('verifications')->like("%{$oldField->field}%")
            ->orWhere('hooks')->like("%{$oldField->field}%")
            ->orWhere('linkages')->like("%{$oldField->field}%")
            ->markRight(1)
            ->fetchAll('id');
        foreach($actions as $id => $action)
        {
            $action = $this->workflowaction->decode($action);

            /* Hook */
            if($action->hooks)
            {
                foreach($action->hooks as $hookKey => $hook)
                {
                    /* Fields */
                    foreach($hook->fields as $fieldKey => $field)
                    {
                        if($field->field == $oldField->field && $flow->module == $hook->table)
                        {
                            $field->field = $newField;
                            $hook->sql    = str_replace("`{$oldField->field}`", "`{$newField}`", $hook->sql);
                        }

                        if($field->param == $oldField->field && $flow->type == 'flow' && ($field->paramType == 'form' or $field->paramType == 'record')) $field->param = $newField;

                        if($field->paramType == 'formula')
                        {
                            $items = json_decode($field->param);
                            foreach($items as $key => $item)
                            {
                                if($item->type == 'target' && $item->module == $flow->module && $item->field == $oldField->field)
                                {
                                    $item->field = $newField;
                                    $items[$key] = $item;
                                }
                            }

                            $field->param = helper::jsonEncode($items);
                        }

                        $hook->fields[$fieldKey] = $field;
                    }

                    /* Formula vars */
                    if(isset($hook->formulaVars))   // The formulaVars property is added in biz5.2, need to check if it's exist.
                    {
                        foreach($hook->formulaVars as $key => $var)
                        {
                            $oldTarget = "{$flow->module}_{$oldField->field}";
                            $newTarget = "{$flow->module}_{$newField}";
                            if($flow->type == 'flow' && $var == $oldTarget)
                            {
                                $var       = $newTarget;
                                $hook->sql = str_replace("'&{$oldTarget}'", "'&{$newTarget}'", $hook->sql);
                            }
                            elseif($flow->type == 'table' && strpos($var, "{$oldTarget}_") === 0)
                            {
                                $var       = str_replace("{$oldTarget}_", "{$newTarget}_", $var);
                                $hook->sql = str_replace("'&{$oldTarget}_", "''&{$newTarget}_", $hook->sql);
                            }

                            $hook->formulaVars[$key] = $var;
                        }
                    }

                    /* Wheres */
                    foreach($hook->wheres as $key => $where)
                    {
                        if($where->field == $oldField->field && $flow->module == $hook->table)
                        {
                            $where->field = $newField;
                            $hook->sql    = str_replace("`{$oldField->field}`", "`{$newField}`", $hook->sql);
                        }

                        if($where->param == $oldField->field && $flow->type == 'flow' && ($where->paramType == 'form' or $where->paramType == 'record')) $where->param = $newField;

                        $hook->wheres[$key] = $where;
                    }

                    if($flow->type == 'flow')
                    {
                        /* Form vars */
                        foreach($hook->formVars as $key => $var)
                        {
                            if($var != $oldField->field) continue;

                            $hook->formVars[$key] = $newField;
                            $hook->sql            = str_replace("'#{$oldField->field}'", "'#{$newField}'", $hook->sql);
                        }

                        /* Record vars */
                        foreach($hook->recordVars as $key => $var)
                        {
                            if($var != $oldField->field) continue;

                            $hook->recordVars[$key] = $newField;
                            $hook->sql              = str_replace("'@{$oldField->field}'", "'@{$newField}'", $hook->sql);
                        }

                        /* Conditions */
                        if($hook->conditionType == 'sql')
                        {
                            foreach($hook->conditions->sqlVars as $key => $var)
                            {
                                if($var->param == $oldField->field && ($var->paramType == 'form' or $var->paramType == 'record'))
                                {
                                    $var->param = $newField;
                                    $hook->conditions->sqlVars[$key] = $var;
                                }
                            }

                            if(strpos($hook->conditions->sql, $flow->module) !== false && strpos($hook->conditions->sql, $oldField->field) !== false) $alerts[$action->name][] = 'hookConditionSql';
                        }
                        elseif($hook->conditionType == 'data')
                        {
                            foreach($hook->conditions as $key => $condition)
                            {
                                if($condition->field == $oldField->field) $condition->field = $newField;
                                if($condition->param == $oldField->field && ($condition->paramType == 'form' or $condition->paramType == 'record')) $condition->param = $newField;

                                $hook->conditions[$key] = $condition;
                            }
                        }
                    }

                    $action->hooks[$hookKey] = $hook;
                }
            }

            if($flow->type == 'flow')
            {
                /* Conditions */
                if($action->conditions)
                {
                    foreach($action->conditions as $conditionKey => $condition)
                    {
                        if($condition->conditionType == 'sql')
                        {
                            if(strpos($condition->sql, $flow->module) !== false && strpos($condition->sql, $oldField->field) !== false) $alerts[$action->name][] = 'conditionSql';
                        }
                        elseif($condition->conditionType == 'data')
                        {
                            foreach($condition->fields as $key => $field)
                            {
                                if($field->field != $oldField->field) continue;

                                $field->field = $newField;
                                $condition->fields[$key] = $field;
                            }

                            $action->conditions[$conditionKey] = $condition;
                        }
                    }
                }

                /* Verifications */
                if($action->verifications)
                {
                    if($action->verifications->type == 'sql')
                    {
                        foreach($action->verifications->sqlVars as $key => $var)
                        {
                            if($var->param == $oldField->field && ($var->paramType == 'form' or $var->paramType == 'record'))
                            {
                                $var->param = $newField;
                                $action->verifications->sqlVars[$key] = $var;
                            }
                        }

                        if(strpos($action->verifications->sql, $flow->module) !== false && strpos($action->verifications->sql, $oldField->field) !== false) $alerts[$action->name][] = 'verificationSql';
                    }
                    elseif($action->verifications->type == 'data')
                    {
                        foreach($action->verifications->fields as $key => $field)
                        {
                            if($field->field == $oldField->field) $field->field = $newField;
                            if($field->param == $oldField->field && ($field->paramType == 'form' or $field->paramType == 'record')) $field->param = $newField;

                            $action->verifications->fields[$key] = $field;
                        }
                    }
                }

                /* Linkages */
                if($action->rawLinkages)
                {
                    foreach($action->rawLinkages as $linkageKey => $linkage)
                    {
                        foreach($linkage->sources as $key => $source)
                        {
                            if($source->field != $oldField->field) continue;

                            $source->field          = $newField;
                            $linkage->sources[$key] = $source;
                        }
                        foreach($linkage->targets as $key => $target)
                        {
                            if($target->field != $oldField->field) continue;

                            $target->field          = $newField;
                            $linkage->targets[$key] = $target;
                        }

                        $action->rawLinkages[$linkageKey] = $linkage;
                    }
                }
            }

            $action->hooks         = helper::jsonEncode($action->hooks);
            $action->conditions    = helper::jsonEncode($action->conditions);
            $action->verifications = helper::jsonEncode($action->verifications);
            $action->linkages      = helper::jsonEncode($action->rawLinkages);

            $this->dao->update(TABLE_WORKFLOWACTION)->data($action, $skip = 'id')->where('id')->eq($id)->exec();
        }

        /* Report */
        $reports = $this->dao->select('id, dimension, fields')->from(TABLE_WORKFLOWREPORT)
            ->where(1)
            ->beginIF($flow->type == 'flow')->andWhere('module')->eq($flow->module)->fi()
            ->beginIF($flow->type == 'table')->andWhere('module')->eq($flow->parent)->fi()
            ->andWhere('dimension', true)->like("%{$oldField->field}%")
            ->orWhere('fields')->like("%{$oldField->field}%")
            ->markRight(1)
            ->fetchAll('id');
        foreach($reports as $id => $report)
        {
            $dimension = json_decode($report->dimension);
            if($dimension->module == $flow->module && $dimension->field == $oldField->field) $dimension->field = $newField;

            $fields = json_decode($report->fields);
            foreach($fields as $key => $field)
            {
                if($field->module == $flow->module && $field->field == $oldField->field)
                {
                    $field->field = $newField;
                    $fields[$key] = $field;
                }
            }

            $report->dimension = helper::jsonEncode($dimension);
            $report->fields    = helper::jsonEncode($fields);

            $this->dao->update(TABLE_WORKFLOWREPORT)->data($report, $skip = 'id')->where('id')->eq($id)->exec();
        }

        /* Category */
        if($oldField->options == 'category') $this->dao->update(TABLE_DEPT)->set('type')->eq("{$flow->module}_{$newField}")->where('type')->eq("{$flow->module}_{$oldField->field}")->exec();

        if($flow->type == 'flow')
        {
            /* Label */
            $labels = $this->dao->select('id, params, `orderBy`')->from(TABLE_WORKFLOWLABEL)
                ->where('module')->eq($flow->module)
                ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($groupID)->fi()
                ->andWhere('params', true)->like("%{$oldField->field}%")
                ->orWhere('`order`')->like("%{$oldField->field}%")
                ->markRight(1)
                ->fetchAll('id');
            foreach($labels as $id => $label)
            {
                $params = json_decode($label->params);
                if($params)
                {
                    foreach($params as $key => $param)
                    {
                        if($param->field != $oldField->field) continue;
                        $param->field = $newField;
                    }
                }

                $orderBy = json_decode($label->orderBy);
                if($orderBy)
                {
                    foreach($orderBy as $key => $field)
                    {
                        if($field->field != $oldField->field) continue;
                        $field->field = $newField;
                    }
                }

                $label->params  = helper::jsonEncode($params);
                $label->orderBy = helper::jsonEncode($orderBy);

                $this->dao->update(TABLE_WORKFLOWLABEL)->data($label, $skip = 'id')->where('id')->eq($id)->exec();
            }

            /* UI */
            $uiList = $this->dao->select('id, conditions')->from(TABLE_WORKFLOWUI)->where('module')->eq($flow->module)
                ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($oldField->group ?? 0)->fi()
                ->andWhere('conditions')->like("%{$oldField->field}%")
                ->fetchAll('id');
            foreach($uiList as $id => $ui)
            {
                $conditions = json_decode($ui->conditions);
                foreach($conditions as $key => $condition)
                {
                    if($condition->field != $oldField->field) continue;
                    $condition->field = $newField;
                }

                $this->dao->update(TABLE_WORKFLOWUI)->set('conditions')->eq(json_encode(array_values($conditions)))->where('id')->eq($id)->exec();
            }

            /* Relation */
            $this->dao->update(TABLE_WORKFLOWRELATION)->set('field')->eq($newField)->where('next')->eq($flow->module)->andWhere('field')->eq($oldField->field)->exec();
        }

        if($alerts)
        {
            $clientLang = $this->app->getClientLang();
            $message    = $this->lang->workflowfield->alert->update . '<br>';
            foreach($alerts as $action => $actionAlerts)
            {
                foreach($actionAlerts as $alertType)
                {
                    if($clientLang == 'zh-cn' or $clientLang == 'zh-tw') $message .= sprintf($this->lang->workflowfield->alert->types[$alertType], $flow->name, $action) . '<br>';
                    if($clientLang != 'zh-cn' && $clientLang != 'zh-tw') $message .= sprintf($this->lang->workflowfield->alert->types[$alertType], $flow->name, $action) . '<br>';
                }
            }

            return array('result' => 'success', 'alert' => $message, 'locate' => 'reload');
        }

        return !dao::isError();
    }

    /**
     * Process a field.
     *
     * @param  object $field
     * @access public
     * @return object
     */
    public function processField($field)
    {
        if(!empty($field->default) && is_array($field->default)) $field->default = trim(implode(',', $field->default), ',');
        $field->sql = html_entity_decode($field->sql, ENT_QUOTES);

        switch($field->control)
        {
        case 'file':
            $field->type   = 'varchar';
            $field->length = 255;
            break;
        case 'integer':
        case 'date':
        case 'datetime':
            $field->length = 0;
        case 'textarea':
        case 'richtext':
        case 'checkbox':
        case 'multi-select':
            $field->default = '';
            break;
        }

        return $field;
    }

    /**
     * Get max, min, step of number field.
     *
     * @param  object $field
     * @access public
     * @return object
     */
    public function processNumberField($field)
    {
        if($field->type == 'decimal')
        {
            list($integerDigits, $decimalDigits) = explode(',', $field->length);

            $field->max  = str_pad('', $integerDigits, 9) . '.' . str_pad('', $decimalDigits, 9);
            $field->min  = -$field->max;
            $field->step = 1 / pow(10, $decimalDigits);

            return $field;
        }

        if(isset($this->config->workflowfield->typeList['integer'][$field->type]))
        {
            $field->max  = zget($this->config->workflowfield->max, $field->type);
            $field->min  = zget($this->config->workflowfield->min, $field->type);
            $field->step = 1;

            return $field;
        }

        return $field;
    }

    /**
     * Process table of the flow.
     *
     * @param  string $table
     * @param  object $oldField
     * @param  object $field
     * @access public
     * @return bool | array
     */
    public function processTable($table, $oldField, $field)
    {
        /* Can't change the built-in fields or readonly fields. 内置字段或只读字段不允许修改。 */
        if($oldField->buildin or $oldField->readonly) return true;

        $sql = '';
        if($oldField->field != $field->field || $oldField->type != $field->type || $oldField->length != $field->length || $oldField->default != zget($field, 'default', ''))
        {
            $isNumber   = in_array($field->type, $this->config->workflowfield->numberTypes);
            $hasDefault = strpos(',date,datetime,text,', ",{$field->type},") === false;

            if($field->type == 'decimal')
            {
                list($integerDigits, $decimalDigits) = explode(',', $field->length);
                $field->length = $integerDigits + $decimalDigits . ',' . $decimalDigits;
            }

            if($field->length and strpos("|decimal|char|varchar|", "|{$field->type}|") !== false) $field->type .= "($field->length)";

            /* The subStatus field can only edit default value. subStatus只能更改默认值。*/
            $fieldCode = $oldField->field == 'subStatus' ? $oldField->field : $field->field;
            $fieldType = $oldField->field == 'subStatus' ? $oldField->type . '(' . $oldField->length . ')' : $field->type;

            $sql  = "ALTER TABLE `$table` CHANGE `$oldField->field` `$fieldCode` $fieldType";
            $sql .= $hasDefault ? ' NOT NULL' : ' NULL';

            if($hasDefault)
            {
                if($field->default)
                {
                    $magicQuote = (version_compare(phpversion(), '5.4', '<') and function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc());
                    if($magicQuote) $field->default = stripslashes($field->default);
                    $field->default = $this->dbh->quote($field->default);
                    $sql .= " DEFAULT $field->default";
                }
                else
                {
                    $sql .= $isNumber ? ' DEFAULT 0' : " DEFAULT ''";
                }
            }
        }

        if(!$sql) return true;

        try
        {
            $this->dbh->query($sql);

            return true;
        }
        catch(PDOException $exception)
        {
            if(is_array($oldField->options) or is_object($oldField->options))
            {
                $oldField->options = helper::jsonEncode($oldField->options);
            }

            $this->dao->update(TABLE_WORKFLOWFIELD)->data($oldField, $skip = 'id, module, placeholder, buildin, desc, createdBy, createdDate, editedBy, editedDate, sql, sqlVars')->autoCheck()
                ->batchCheck($this->config->workflowfield->require->edit, 'notempty')
                ->where('id')->eq($oldField->id)
                ->exec();

            return array('result' => 'fail', 'message' => $exception->getMessage() . ". The sql is : " . $sql);
        }
    }

    /**
     * Check a field.
     *
     * @param  object $field
     * @access public
     * @return array | object
     */
    public function checkField($field)
    {
        if(!empty($field->field) && !validater::checkREG($field->field, '|^[A-Za-z]+$|')) return array('result' => 'fail', 'message' => array('field' => sprintf($this->lang->workflowfield->error->wrongCode, $this->lang->workflowfield->field)));
        if(in_array($field->field, $this->config->workflowfield->remainFields)) return array('result' => 'fail', 'message' => array('field' => sprintf($this->lang->workflowfield->error->remainFields, $field->field)));

        $result = $this->processFieldLength($field);
        if(is_array($result) && $result['result'] == 'fail') return $result;

        $field = $result;

        if(strpos(',select,multi-select,radio,checkbox,', ",$field->control,") === false)
        {
            $field->options = '[]';
        }
        else
        {
            if($field->optionType == 'sql')
            {
                if(empty($field->sql)) return array('result' => 'fail', 'message' => array('sql' => sprintf($this->lang->error->notempty, $this->lang->workflowfield->sql)));

                $result = $this->checkSqlAndVars($field->sql);
                if($result !== true) return array('result' => 'fail', 'message' => array('sql' => $result));

                $field->options = $field->optionType;
            }
            elseif($field->optionType == 'custom')
            {
                $options = array();
                if($field->field == 'subStatus')
                {
                    if(empty($field->parentCode)) return array('result' => 'fail', 'message' => $this->lang->workflowfield->tips->emptyStatus);

                    $errors = array();  // Log errors.

                    /* Check if the code or the name of sub status is duplicated. */
                    $subCodes     = array();
                    $subNames     = array();
                    $emptySubList = array();
                    foreach($field->parentCode as $key => $parentCode)
                    {
                        $optionCode = array_filter($field->optionCode[$parentCode]);
                        $optionName = array_filter($field->optionName[$parentCode]);

                        if(empty($optionCode) or empty($optionName))
                        {
                            $emptySubList[] = $field->parentName[$key];
                            continue;
                        }

                        foreach($optionCode as $code) $subCodes[] = $code;
                        foreach($optionName as $name) $subNames[] = $name;
                    }

                    $duplicatedCodes = array_diff_assoc($subCodes, array_unique($subCodes));
                    //$duplicatedNames = array_diff_assoc($subNames, array_unique($subNames));

                    if($emptySubList)    $errors['optionsDIV'][] = sprintf($this->lang->workflowfield->tips->emptySubStatus, implode(',', array_unique($emptySubList)));
                    if($duplicatedCodes) $errors['optionsDIV'][] = sprintf($this->lang->workflowfield->error->duplicatedCode, implode(',', array_unique($duplicatedCodes)));
                    //if($duplicatedNames) $errors['optionsDIV'][] = sprintf($this->lang->workflowfield->error->duplicatedName, implode(',', array_unique($duplicatedNames)));

                    if($errors) return array('result' => 'fail', 'message' => $errors);

                    foreach($field->parentCode as $key => $parentCode)
                    {
                        $parentName = $field->parentName[$key];

                        if($parentCode == '' or $parentName == '') continue;

                        /* Init the default property and options property for a parent status. */
                        $options[$parentCode]['default'] = zget($field->optionDefault, $parentCode, '');
                        $options[$parentCode]['options'] = array();

                        foreach($field->optionCode[$parentCode] as $key => $code)
                        {
                            if(strlen($code) > 30)
                            {
                                return array('result' => 'fail', 'message' => array("optionDefault$parentCode" => sprintf($this->lang->workflowfield->error->longCode, $this->lang->workflowfield->key)));
                            }

                            $name = $field->optionName[$parentCode][$key];

                            if($code == '' or $name == '') continue;

                            $options[$parentCode]['options'][$code] = $name;
                        }

                        $optionsCount = count($options[$parentCode]['options']);

                        if(!$options[$parentCode]['default'])
                        {
                            /* If the sub status is empty, set parent status as sub status. */
                            if($optionsCount == 0)
                            {
                                $options[$parentCode]['options'][$parentCode] = $parentName;
                                $options[$parentCode]['default'] = $parentCode;
                            }

                            if($optionsCount == 1) $options[$parentCode]['default'] = key($options[$parentCode]['options']);

                            if($optionsCount > 1) $errors["optionDefault$parentCode"] = $this->lang->workflowfield->error->emptyDefault;
                        }
                    }

                    if($errors) return array('result' => 'fail', 'message' => $errors);
                }
                elseif(!empty($field->options))
                {
                    //$duplicatedNames = array_diff_assoc($field->options['name'], array_unique($field->options['name']));
                    //if($duplicatedNames) return array('result' => 'fail', 'message' => array('optionsDIV' => sprintf($this->lang->workflowfield->error->duplicatedName, implode(',', array_unique($duplicatedNames)))));

                    $longCode = false;
                    foreach($field->options['code'] as $key => $code)
                    {
                        $name = $field->options['name'][$key];

                        if($code == '' or $name == '') continue;

                        if(strlen($code) > 30)
                        {
                            return array('result' => 'fail', 'message' => array('optionsDIV' => sprintf($this->lang->workflowfield->error->longCode, $this->lang->workflowfield->key)));
                        }

                        if(!validater::checkCode($code))
                        {
                            return array('result' => 'fail', 'message' => array('optionsDIV' => sprintf($this->lang->error->code, $this->lang->workflowfield->key)));
                        }

                        $options[$code] = $name;
                    }
                }

                /* If options is empty, return error. */
                if(empty($options))
                {
                    return array('result' => 'fail', 'message' => array('optionsDIV' => $this->lang->workflowfield->error->emptyOptions));
                }
                $field->options = helper::jsonEncode($options);
            }
            else
            {
                $field->options = $field->optionType;
            }
        }

        if(!empty($field->default))
        {
            $checkResult = $this->checkDefaultValue($field);
            if(is_array($checkResult) && zget($checkResult, 'result') == 'fail') return $checkResult;
        }

        /* If this options's value of field is user, set value of type and value of length. */
        if($field->options == 'user' && $field->type != 'text')
        {
            $field->type   = 'varchar';
            $field->length = 30;
        }

        return $field;
    }

    /**
     * Check default value of fields.
     *
     * @param  object  $field
     * @access public
     * @return string
     */
    public function checkDefaultValue($field)
    {
        if(!empty($field->length))
        {
            $length = explode(',', rtrim($field->length, ','));
            $field->integerDigits = zget($length, 0, 0);
            $field->decimalDigits = zget($length, 1, 0);
        }

        switch($field->type)
        {
            case 'text':
                return true;
            case 'varchar':
            case 'char':
                if(is_array($field->default)) $field->default = implode(",", $field->default);
                if(!is_string($field->default)) break;
                if(!empty($field->length) && strlen($field->default) > $field->length) return array('result' => 'fail', 'message' => array('default' => sprintf($this->lang->workflowfield->error->defaultValue, $field->length)));
                break;
            case 'date':
                if(is_string($field->default) && ('today' == $field->default || 'currentTime' == $field->default)) break;
                $checkResult = DateTime::createFromFormat('Y-m-d', $field->default);
                if(!$checkResult || $checkResult->format('Y-m-d') !== $field->default) return array('result' => 'fail', 'message' => array('default' => $this->lang->workflowfield->error->dateFormat));
                break;
            case 'datetime':
                if(is_string($field->default) && ('now' == $field->default || 'currentTime' == $field->default)) break;
                $checkResult = DateTime::createFromFormat('Y-m-d H:i', $field->default);
                if(!$checkResult || $checkResult->format('Y-m-d H:i') !== $field->default) return array('result' => 'fail', 'message' => array('default' => $this->lang->workflowfield->error->timeFormat));
                break;
            case 'decimal':
                if(!is_numeric($field->default)) return array('result' => 'fail', 'message' => array('default' => sprintf($this->lang->workflowfield->error->float)));
                /* Compute max and min value.*/
                $min = (float)(-str_pad('', $field->integerDigits, 9) . '.' . str_pad('', $field->decimalDigits, 9));
                $max = abs($min);
                if((float)$field->default > $max || (float)$field->default < $min) return array('result' => 'fail', 'message' => array('default' => sprintf($this->lang->workflowfield->error->intSize, $min, $max)));
                break;
            case 'int':
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
                if(!is_numeric($field->default) || (int)$field->default > $this->config->workflowfield->max->{$field->type} || (int)$field->default < $this->config->workflowfield->min->{$field->type}) return array('result' => 'fail', 'message' => array('default' => sprintf($this->lang->workflowfield->error->intSize, $this->config->workflowfield->min->{$field->type}, $this->config->workflowfield->max->{$field->type})));
                break;
        }

        /* If length is not set, check fields by config. */
        foreach($this->config->workflowfield->lengthList as $length => $controlList)
        {
            if(strpos($controlList, ",{$field->control},") !== false and strlen($field->default) > $length)
            {
                if($field->default !== 'currentTime') return array('result' => 'fail', 'message' => array('default' => sprintf($this->lang->workflowfield->error->defaultValue, $length)));
            }
        }
    }

    /**
     * Delete a field.
     *
     * @param  int    $id
     * @param  object $null
     * @access public
     * @return bool
     */
    public function delete($id, $null = null)
    {
        $field = $this->getByID($id);
        if(!$field) return false;

        $flow = $this->loadModel('workflow', 'flow')->getByModule($field->module, false, $field->group);
        if(!$flow) return false;

        $result = $this->deleteRelated($flow, $field);
        if(is_array($result)) return $result;

        $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('id')->eq($id)->exec();
        if(dao::isError()) return false;

        if($field->role != 'quote')
        {
            $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('module')->eq($field->module)->andWhere('field')->eq($field->field)->exec();
            $sql = "ALTER TABLE `$flow->table` DROP `$field->field`";
            if(!$this->dbh->query($sql)) return false;
            if($field->options == 'sql') $this->deleteSqlAndVars($field->module, $field->field);
        }

        return true;
    }

    /**
     * Delete the related datas when delete the field.
     *
     * @param  object $flow
     * @param  object $oldField
     * @access public
     * @return bool
     */
    public function deleteRelated($flow, $oldField)
    {
        $alerts = array();

        /* Field */
        $fields = $this->dao->select('module, field, name, expression')->from(TABLE_WORKFLOWFIELD)
            ->where('control')->eq('formula')
            ->andWhere('expression')->like("%{$oldField->field}%")
            ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($oldField->group)->fi()
            ->beginIF($flow->type == 'flow')->andWhere('module')->eq($flow->module)->fi()
            ->beginIF($flow->type == 'table')
            ->andWhere('module', true)->eq($flow->parent)
            ->orWhere('module')->eq($flow->module)
            ->markRight(1)
            ->fi()
            ->fetchAll();
        foreach($fields as $field)
        {
            $items = json_decode($field->expression);
            foreach($items as $key => $item)
            {
                if($item->type == 'target' && $item->module == $flow->module && $item->field == $oldField->field)
                {
                    $alerts[$field->module]['field']['fieldExpression'][$field->field] = $field->name;
                }
            }
        }

        /* Action */
        $this->loadModel('workflowaction', 'flow');
        $actions = $this->dao->select('id, module, name, conditions, verifications, hooks, linkages')->from(TABLE_WORKFLOWACTION)
            ->where('1=1')
            ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($oldField->group)->fi()
            ->beginIF($flow->type == 'flow')->andWhere('module')->eq($flow->module)->fi()
            ->beginIF($flow->type == 'table')->andWhere('module')->eq($flow->parent)->fi()
            ->andWhere('conditions', true)->like("%{$oldField->field}%")
            ->orWhere('verifications')->like("%{$oldField->field}%")
            ->orWhere('hooks')->like("%{$oldField->field}%")
            ->orWhere('linkages')->like("%{$oldField->field}%")
            ->markRight(1)
            ->fetchAll('id');
        foreach($actions as $id => $action)
        {
            $action = $this->workflowaction->decode($action);

            /* Hook */
            if($action->hooks)
            {
                foreach($action->hooks as $hookKey => $hook)
                {
                    /* Fields */
                    foreach($hook->fields as $fieldKey => $field)
                    {
                        if($field->field == $oldField->field && $flow->module == $hook->table)
                        {
                            $alerts[$action->module]['action'][$action->name]['hookField'][$field->field] = $field->field;
                        }
                        if($field->param == $oldField->field && $flow->type == 'flow' && ($field->paramType == 'form' or $field->paramType == 'record'))
                        {
                            $alerts[$action->module]['action'][$action->name]['hookField'][$field->field] = $field->field;
                        }

                        if($field->paramType == 'formula')
                        {
                            $items = json_decode($field->param);
                            foreach($items as $key => $item)
                            {
                                if($item->type == 'target' && $item->module == $flow->module && $item->field == $oldField->field)
                                {
                                    $alerts[$action->module]['action'][$action->name]['hookFieldFormula'][$field->field] = $field->field;
                                }
                            }
                        }
                    }

                    /* Wheres */
                    foreach($hook->wheres as $key => $where)
                    {
                        if($where->field == $oldField->field && $flow->module == $hook->table)
                        {
                            $alerts[$action->module]['action'][$action->name]['hookWhere'][$where->field] = $where->field;
                        }

                        if($where->param == $oldField->field && $flow->type == 'flow' && ($where->paramType == 'form' or $where->paramType == 'record'))
                        {
                            $alerts[$action->module]['action'][$action->name]['hookWhere'][$where->field] = $where->field;
                        }
                    }

                    if($flow->type == 'flow')
                    {
                        /* Conditions */
                        if($hook->conditionType == 'sql')
                        {
                            foreach($hook->conditions->sqlVars as $key => $var)
                            {
                                if($var->param == $oldField->field && ($var->paramType == 'form' or $var->paramType == 'record'))
                                {
                                    $alerts[$action->module]['action'][$action->name]['hookConditionSqlVar'][$var->varName] = $var->varName;
                                }
                            }

                            if(strpos($hook->conditions->sql, $flow->module) !== false && strpos($hook->conditions->sql, $oldField->field) !== false)
                            {
                                $alerts[$action->module]['action'][$action->name]['hookConditionSql']['sql'] = 'sql';
                            }
                        }
                        elseif($hook->conditionType == 'data')
                        {
                            foreach($hook->conditions as $key => $condition)
                            {
                                if($condition->field == $oldField->field)
                                {
                                    $alerts[$action->module]['action'][$action->name]['hookConditionField'][$condition->field] = $condition->field;
                                }
                                if($condition->param == $oldField->field && ($condition->paramType == 'form' or $condition->paramType == 'record'))
                                {
                                    $alerts[$action->module]['action'][$action->name]['hookConditionField'][$condition->field] = $condition->field;
                                }
                            }
                        }
                    }
                }
            }

            if($flow->type == 'flow')
            {
                /* Conditions */
                if($action->conditions)
                {
                    foreach($action->conditions as $conditionKey => $condition)
                    {
                        if($condition->conditionType != 'sql') continue;

                        if(strpos($condition->sql, $flow->module) !== false && strpos($condition->sql, $oldField->field) !== false)
                        {
                            $alerts[$action->module]['action'][$action->name]['conditionSql']['sql'] = 'sql';
                        }
                    }
                }

                /* Verifications */
                if($action->verifications)
                {
                    if($action->verifications->type != 'sql') continue;

                    foreach($action->verifications->sqlVars as $key => $var)
                    {
                        if($var->param == $oldField->field && ($var->paramType == 'form' or $var->paramType == 'record'))
                        {
                            $alerts[$action->module]['action'][$action->name]['verificationSqlVar'][$var->varName] = $var->varName;
                        }
                    }

                    if(strpos($action->verifications->sql, $flow->module) !== false && strpos($action->verifications->sql, $oldField->field) !== false)
                    {
                        $alerts[$action->module]['action'][$action->name]['verificationSql']['sql'] = 'sql';
                    }
                }
            }
        }

        if($alerts)
        {
            $flowFields = array();
            $fields     = $this->dao->select('module, field, name')->from(TABLE_WORKFLOWFIELD)->where('group')->eq($oldField->group)->fetchAll();
            foreach($fields as $field) $flowFields[$field->module][$field->field] = $field->name;

            $clientLang = $this->app->getClientLang();
            $alertLang  = $this->lang->workflowfield->alert;
            $message    = $this->lang->workflowfield->alert->delete . '<br>';
            $flowName   = $flow->name;
            foreach($alerts as $module => $moduleAlerts)
            {
                if(!empty($moduleAlerts['field']))
                {
                    foreach($moduleAlerts['field'] as $type => $typeAlerts)
                    {
                        if($type == 'fieldExpression')
                        {
                            if($clientLang == 'zh-cn' or $clientLang == 'zh-tw') $message .= sprintf($alertLang->types[$type], $flowName, implode($alertLang->separater, $typeAlerts)) . '<br>';
                            if($clientLang != 'zh-cn' && $clientLang != 'zh-tw') $message .= sprintf($alertLang->types[$type], implode($alertLang->separater, $typeAlerts), $flowName) . '<br>';
                        }
                    }
                }

                if(!empty($moduleAlerts['action']))
                {
                    foreach($moduleAlerts['action'] as $actionName => $actionAlerts)
                    {
                        foreach($actionAlerts as $type => $actionAlerts)
                        {
                            if($type == 'conditionSql' or $type == 'verificationSql' or $type == 'hookConditionSql')
                            {
                                $message .= sprintf($alertLang->types[$type], $flowName, $actionName) . '<br>';
                            }
                            else
                            {
                                foreach($actionAlerts as $key => $field) $actionAlerts[$key] = isset($flowFields[$module][$field]) ? $flowFields[$module][$field] : $field;

                                if($clientLang == 'zh-cn' or $clientLang == 'zh-tw') $message .= sprintf($alertLang->types[$type], $flowName, $actionName, implode($alertLang->separater, $actionAlerts)) . '<br>';
                                if($clientLang != 'zh-cn' && $clientLang != 'zh-tw') $message .= sprintf($alertLang->types[$type], implode($alertLang->separater, $actionAlerts), $flowName, $actionName) . '<br>';
                            }
                        }
                    }
                }
            }

            return array('result' => 'fail', 'message' => $message);
        }

        /* Report */
        $reports = array();
        if($oldField->role != 'quote')
        {
            $reports = $this->dao->select('id, dimension, fields')->from(TABLE_WORKFLOWREPORT)
                ->where(1)
                ->beginIF($flow->type == 'flow')->andWhere('module')->eq($flow->module)->fi()
                ->beginIF($flow->type == 'table')->andWhere('module')->eq($flow->parent)->fi()
                ->andWhere('dimension', true)->like("%{$oldField->field}%")
                ->orWhere('fields')->like("%{$oldField->field}%")
                ->markRight(1)
                ->fetchAll('id');
        }
        foreach($reports as $id => $report)
        {
            $dimension = json_decode($report->dimension);
            if($dimension->module == $flow->module && $dimension->field == $oldField->field) $dimension = '';

            $fields = json_decode($report->fields);
            foreach($fields as $key => $field)
            {
                if($field->module == $flow->module && $field->field == $oldField->field) unset($fields[$key]);
            }

            if(empty($report->dimension) or empty($fields))
            {
                /* Delete the report. */
                $this->dao->delete()->from(TABLE_WORKFLOWREPORT)->where('id')->eq($id)->exec();
                continue;
            }

            $report->dimension = helper::jsonEncode($dimension);
            $report->fields    = helper::jsonEncode(array_values($fields));

            $this->dao->update(TABLE_WORKFLOWREPORT)->data($report, $skip = 'id')->where('id')->eq($id)->exec();
        }

        /* Category */
        if($oldField->options == 'category') $this->dao->delete()->from(TABLE_DEPT)->where('type')->eq("{$flow->module}_{$oldField->field}")->exec();

        if($flow->type == 'flow')
        {
            foreach($actions as $id => $action)
            {
                $action = $this->workflowaction->decode($action);

                /* Conditions */
                if($action->conditions)
                {
                    foreach($action->conditions as $conditionKey => $condition)
                    {
                        if($condition->conditionType != 'data') continue;

                        foreach($condition->fields as $key => $field)
                        {
                            if($field->field == $oldField->field) unset($condition->fields[$key]);
                        }

                        if(!empty($condition->fields))
                        {
                            $condition->fields = array_values($condition->fields);  // Make sure fields is an indexed array.

                            $action->conditions[$conditionKey] = $condition;
                        }
                        else
                        {
                            unset($action->conditions[$conditionKey]);
                        }
                    }
                }

                /* Verifications */
                if($action->verifications)
                {
                    if($action->verifications->type != 'data') continue;

                    foreach($action->verifications->fields as $key => $field)
                    {
                        if($field->field == $oldField->field) unset($action->verifications->fields[$key]);
                        if($field->param == $oldField->field && ($field->paramType == 'form' or $field->paramType == 'record')) unset($action->verifications->fields[$key]);
                    }

                    if(!empty($action->verifications->fields))
                    {
                        $action->verifications->fields = array_values($action->verifications->fields);  // Make sure fields is an indexed array.
                    }
                    else
                    {
                        $action->verifications = '';
                    }
                }

                /* Linkages */
                if($action->rawLinkages)
                {
                    foreach($action->rawLinkages as $linkageKey => $linkage)
                    {
                        foreach($linkage->sources as $key => $source)
                        {
                            if($source->field == $oldField->field) unset($linkage->sources[$key]);
                        }
                        foreach($linkage->targets as $key => $target)
                        {
                            if($target->field == $oldField->field) unset($linkage->targets[$key]);
                        }

                        if(!empty($linkage->sources) && !empty($linkage->targets))
                        {
                            /* Make sure sources and targets are indexed array. */
                            $linkage->sources = array_values($linkage->sources);
                            $linkage->targets = array_values($linkage->targets);

                            $action->rawLinkages[$linkageKey] = $linkage;
                        }
                        else
                        {
                            unset($action->rawLinkages[$linkageKey]);
                        }
                    }
                }

                $newAction = new stdClass();
                $newAction->conditions    = $action->conditions    ? helper::jsonEncode(array_values($action->conditions))  : '[]';
                $newAction->verifications = $action->verifications ? helper::jsonEncode($action->verifications)             : '[]';
                $newAction->linkages      = $action->rawLinkages   ? helper::jsonEncode(array_values($action->rawLinkages)) : '[]';
                $this->dao->update(TABLE_WORKFLOWACTION)->data($newAction)->where('id')->eq($id)->exec();
            }

            /* Label */
            $labels = $this->dao->select('id, params, `orderBy`')->from(TABLE_WORKFLOWLABEL)
                ->where('module')->eq($flow->module)
                ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($oldField->group)->fi()
                ->andWhere('params', true)->like("%{$oldField->field}%")
                ->orWhere('`order`')->like("%{$oldField->field}%")
                ->markRight(1)
                ->fetchAll('id');
            foreach($labels as $id => $label)
            {
                $params = json_decode($label->params);
                if($params)
                {
                    foreach($params as $key => $param)
                    {
                        if($param->field == $oldField->field) unset($params[$key]);
                    }
                }

                if(empty($params))
                {
                    $this->dao->delete()->from(TABLE_WORKFLOWLABEL)->where('id')->eq($id)->exec();
                    continue;
                }

                $orderBy = json_decode($label->orderBy);
                if($orderBy)
                {
                    foreach($orderBy as $key => $field)
                    {
                        if($field->field == $oldField->field) unset($orderBy[$key]);
                    }
                }

                $label->params  = $params  ? helper::jsonEncode(array_values($params))  : '[]';
                $label->orderBy = $orderBy ? helper::jsonEncode(array_values($orderBy)) : '[]';

                $this->dao->update(TABLE_WORKFLOWLABEL)->data($label, $skip = 'id')->where('id')->eq($id)->exec();
            }

            /* UI */
            $uiList = $this->dao->select('id, conditions')->from(TABLE_WORKFLOWUI)->where('module')->eq($flow->module)
                ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($oldField->group)->fi()
                ->andWhere('conditions')->like("%{$oldField->field}%")
                ->fetchAll('id');
            foreach($uiList as $id => $ui)
            {
                $conditions = json_decode($ui->conditions);
                foreach($conditions as $key => $condition)
                {
                    if($condition->field == $oldField->field) unset($conditions[$key]);
                }

                if(empty($conditions)) $this->dao->delete()->from(TABLE_WORKFLOWUI)->where('id')->eq($id)->exec();
                if(!empty($conditions))$this->dao->update(TABLE_WORKFLOWUI)->set('conditions')->eq(json_encode(array_values($conditions)))->where('id')->eq($id)->exec();
            }

            /* Relation */
            if($oldField->role != 'quote') $this->dao->delete()->from(TABLE_WORKFLOWRELATION)->where('next')->eq($flow->module)->andWhere('field')->eq($oldField->field)->exec();
        }

        /* Layout */
        $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)
            ->where('module')->eq($flow->module)
            ->andWhere('field')->eq($oldField->field)
            ->beginIF($oldField->role == 'quote')->andWhere('group')->eq($oldField->group)->fi()
            ->exec();

        return !dao::isError();
    }

    /**
     * Set fields to display in another flow.
     *
     * @access public
     * @return bool
     */
	public function setValue()
    {
        if(!$this->post->modules) return false;

        $this->dao->update(TABLE_WORKFLOWFIELD)->set('isValue')->eq('0')->where('module')->in($this->post->modules)->exec();

        if(!$this->post->fields) return !dao::isError();

        /* Loop update isValue of field. */
        foreach($this->post->fields as $module => $fields)
        {
            $this->dao->update(TABLE_WORKFLOWFIELD)->set('isValue')->eq('1')
                ->where('module')->eq($module)
                ->andWhere('field')->in($fields)
                ->exec();
        }

        return !dao::isError();
    }

    /**
     * Set fields to export.
     *
     * @param  string $module
     * @access public
     * @return bool
     */
    public function setExport($module)
    {
        if(!$this->post->modules) return false;

        $this->dao->update(TABLE_WORKFLOWFIELD)->set('canExport')->eq('0')->where('module')->in($this->post->modules)->exec();

        if(!$this->post->fields)
        {
            $this->processPrivileges($module, 'export');
            return !dao::isError();
        }

        /* Loop update canSearch and serachOrder field. */
        foreach($this->post->fields as $exportModule => $fields)
        {
            foreach($fields as $order => $field)
            {
                $this->dao->update(TABLE_WORKFLOWFIELD)->set('canExport')->eq('1')->set('exportOrder')->eq($order)
                    ->where('module')->eq($exportModule)
                    ->andWhere('field')->eq($field)
                    ->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * Set fields to search.
     *
     * @param  string $module
     * @access public
     * @return bool
     */
	public function setSearch($module)
    {
        if(!$this->post->modules) return false;

        $this->dao->update(TABLE_WORKFLOWFIELD)->set('canSearch')->eq('0')->where('module')->in($this->post->modules)->exec();

        if(!$this->post->fields)
        {
            $this->processPrivileges($module, 'search');
            return !dao::isError();
        }

        /* Loop update canSearch and serachOrder field. */
        foreach($this->post->fields as $searchModule => $fields)
        {
            foreach($fields as $order => $field) $this->dao->update(TABLE_WORKFLOWFIELD)->set('canSearch')->eq('1')->set('searchOrder')->eq($order)->where('module')->eq($searchModule)->andWhere('field')->eq($field)->exec();
        }

        unset($_SESSION[$module . 'Query']);
        unset($_SESSION[$module . 'Form']);
        unset($_SESSION['searchParams']);
        unset($_SESSION['queryID']);

        return !dao::isError();
    }

    /**
     * Process group privileges.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return bool
     */
    public function processPrivileges($module, $method)
    {
        $flow = $this->loadModel('workflow', 'flow')->getByModule($module);
        if($flow->buildin) return true;

        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq($module)->andWhere('method')->eq($method)->fetchPairs();
        if(!$groups) return true;

        /* Delete the privilege from groups. */
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('module')->eq($module)->andWhere('method')->eq($method)->exec();

        /* Mark the user privileges need update. */
        $this->loadModel('group');
        foreach($groups as $group) $this->group->updateAccounts($group);

        return !dao::isError();
    }

    /**
     * Process export data.
     *
     * @param  array    $datas
     * @access public
     * @return array
     */
    public function processExportData($datas)
    {
        $module       = $this->app->getModuleName();
        $fields       = $this->loadModel('workflowfield', 'flow')->getList($module);
        $fields       = $this->loadModel('workflowaction', 'flow')->processFields($fields, true, $datas);
        $extendFields = $this->getExportFields($module);

        foreach($datas as $data)
        {
            foreach($data as $key => $value)
            {
                if(!empty($extendFields[$key]))
                {
                    $field = $fields[$key];
                    if(!is_array($field->options)) continue;

                    if($field->control == 'multi-select' or $field->control == 'checkbox')
                    {
                        $values = explode(',', $value);
                        foreach($values as $k => $v)
                        {
                            $values[$k] = zget($field->options, $v);
                        }
                        $data->$key = implode(',', array_unique(array_filter($values)));
                    }
                    else
                    {
                        $data->$key = zget($field->options, $value);
                    }
                }
            }
        }

        return $datas;
    }

    /**
     * Process export options
     *
     * @param  object    $data
     * @access public
     * @return object
     */
    public function processExportOptions($data)
    {
        $module       = $this->app->getModuleName();
        $method       = $this->app->getMethodName();
        $actionFields = $this->loadModel('workflowaction', 'flow')->getFields($module, $method);

        foreach($actionFields as $field)
        {
            if(!empty($field->options) && is_array($field->options))
            {
                $listFields[] = $field->field;

                $fieldList[$field->field . 'List'] = $field->options;
            }
        }

        foreach($fieldList as $listName => $listArray)
        {
            if(empty($data->$listName)) $data->$listName = $listArray;
        }

        $data->sysDataList = array_merge($data->sysDataList, $listFields);
        $data->listStyle   = array_merge($data->listStyle, $listFields);

        return $data;
    }

    /**
     * Process import data.
     *
     * @param  array    $datas
     * @access public
     * @return array
     */
    public function processImportData($datas)
    {
        $module       = $this->app->getModuleName();
        $fields       = $this->loadModel('workflowfield', 'flow')->getList($module);
        $fields       = $this->loadModel('workflowaction', 'flow')->processFields($fields, true, $datas);
        $extendFields = $this->getExportFields($module);

        foreach($datas as $data)
        {
            foreach($data as $key => $value)
            {
                if(!empty($extendFields[$key]))
                {
                    $field = $fields[$key];
                    if(!is_array($field->options)) continue;

                    if($field->control == 'multi-select' or $field->control == 'checkbox')
                    {
                        $values = explode(',', $value);
                        foreach($values as $k => $v)
                        {
                            $values[$k] = zget(array_flip($field->options), $v);
                        }
                        $data->$key = implode(',', array_unique(array_filter($values)));
                    }
                    else
                    {
                        $data->$key = zget(array_flip($field->options), $value);
                    }
                }
            }
        }

        return $datas;
    }

    /**
     * 根据数据获取模板名
     * Guery group name by data list.
     *
     * @param  string $module
     * @param  array  $fields
     * @param  int    $groupID
     * @param  type   $type      field|table
     * @access public
     * @return array
     */
    public function queryGroupNameByList($module, $dataList, $groupID = 0, $type = 'field')
    {
        $groups     = array();
        $queryField = $type == 'field' ? 'field' : 'module';
        $quoteData  = array_filter(array_map(function($data) use($type, $queryField){if($data->role == 'quote') return $type == 'field' ? $data->{$queryField} : $data->{$queryField};}, $dataList));
        $dataGroups = array();
        if($quoteData)
        {
            if($type == 'field') $dataGroups = $this->dao->select('`group`,field')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('field')->in($quoteData)->andWhere('role')->eq('custom')->fetchPairs('field', 'group');
            if($type == 'table') $dataGroups = $this->dao->select('`group`,module')->from(TABLE_WORKFLOW)->where('parent')->eq($module)->andWhere('module')->in($quoteData)->andWhere('type')->eq('table')->andWhere('role')->eq('custom')->fetchPairs('module', 'group');
            $groups = $this->dao->select('id,name')->from(TABLE_WORKFLOWGROUP)->where('id')->in($dataGroups)->fetchPairs('id', 'name');
        }

        $group = $this->loadModel('workflowgroup')->getById($groupID);
        if($group) $groups[$groupID] = $group->name;

        foreach($dataList as $data)
        {
            $commonTemplate = $this->lang->workflowgroup->workflow->exclusiveList[0];
            $data->groupName = empty($data->group) ? $commonTemplate : zget($groups, $data->group, '');
            if($data->role == 'default') $data->groupName = $commonTemplate;
            if(isset($dataGroups[$data->{$queryField}])) $data->groupName = empty($dataGroups[$data->{$queryField}]) ? $commonTemplate : zget($groups, $dataGroups[$data->{$queryField}], '');
        }

        return $dataList;
    }

    /**
     * 获取引用字段
     * Get quote fields.
     *
     * @param  string $module
     * @param  string $field
     * @access public
     * @return array
     */
    public function getQuoteFields($module, $field)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('field')->eq($field)->andWhere('role')->eq('quote')->fetchAll('id', false);
    }

    public function batchGetQuoteFields($module, $fields)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('field')->in($fields)->andWhere('role')->eq('quote')->fetchPairs('field', 'field');
    }

    /**
     * 获取自创建的字段
     * Get customed fields.
     *
     * @param  string $module
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getCustomedFields($module, $groupID)
    {
        return $this->dao->select('id,`group`,module,`field`,name')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('role')->eq('custom')->andWhere('group')->ne($groupID)->fetchGroup('group', 'id');
    }

    /**
     * 保存其他模板的引用字段。
     * Save quote fields.
     *
     * @param  string $module
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function saveQuote($module, $groupID = 0)
    {
        if(!$this->post->fields) return;

        $fields = fixer::input('post')->get('fields');
        $fields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('role')->eq('custom')->andWhere('field')->in($fields)->fetchAll('', false);

        $this->loadModel('workflowgroup');
        foreach($fields as $field) $this->workflowgroup->insertGroupObject($field, 'flowfield', $groupID);
    }
}
