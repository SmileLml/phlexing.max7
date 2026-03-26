<?php
/**
 * The model file of workflowhook module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowhook
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowhookModel extends model
{
    public function __construct($appName = '')
    {
        parent::__construct($appName);
        $dept    = zget($this->app->user, 'dept', '');
        $manager = $this->dao->select('manager')->from(TABLE_DEPT)->where('id')->eq($dept)->fetch('manager');
        $this->deptManager = trim($manager, ',');
    }

    /**
     * Get fields of a table.
     *
     * @param  string $table
     * @access public
     * @return array
     */
    public function getTableFields($table)
    {
        $fields = array();
        $module = $table;
        if(isset($this->config->workflowhook->tables[$module]))
        {
            $app    = zget($this->config->workflowhook->apps, $module);
            $table  = zget($this->config->workflowhook->tables, $table);

            $this->app->loadLang('common', $app);
            $this->app->loadLang($module, $app);

            $fieldQuery = $this->dao->query('desc ' . $table);
            while($field = $fieldQuery->fetch())
            {
                if(isset($this->config->workflowhook->skipFields[$module]) and in_array($field->Field, $this->config->workflowhook->skipFields[$module])) continue;
                $name = isset($this->lang->$module->{$field->Field}) ? $this->lang->$module->{$field->Field} : $field->Field;
                $fields[$field->Field] = $name;
            }
        }
        else
        {
            $fields = $this->loadModel('workflowfield', 'flow')->getFieldPairs($module);
        }

        return $fields;
    }

    /**
     * Get field param value by paramType.
     *
     * @param  string $param
     * @param  string $type
     * @access public
     * @return string
     */
    public function getParamRealValue($param, $type = 'view')
    {
        switch((string)$param)
        {
            case 'today'           : return date('Y-m-d');
            case 'now'             :
            case 'currentTime'     : return date('Y-m-d H:i:s');
            case 'actor'           :
            case 'currentUser'     : return $this->app->user->account;
            case 'currentDept'     : return ($this->app->user->dept || $type == 'view') ? $this->app->user->dept : $param;
            case 'deptManager'     : return ($this->deptManager || $type == 'view') ? $this->deptManager : $param;
            case 'scheduledPerson' : return $this->app->user->account;
            default                : return $param;
        }
    }

    /**
     * Get datasource pairs.
     *
     * @access public
     * @return bool
     */
    public function getDatasourcePairs()
    {
        $datasources = $this->loadModel('workflowdatasource', 'flow')->getPairs('noempty');;
        $datasources = arrayUnion($datasources, $this->lang->workflowhook->options);
        unset($datasources['currentUser']);

        return $datasources;
    }

    /**
     * Check a hook.
     *
     * @param  object $hook
     * @access public
     * @return array
     */
    public function check($hook)
    {
        $sql   = '';
        $error = '';

        if(isset($this->config->workflowhook->tables[$hook->table]))
        {
            $table = $this->config->workflowhook->tables[$hook->table];
        }
        else
        {
            $flow  = $this->loadModel('workflow', 'flow')->getByModule($hook->table);
            $table = "`$flow->table`";
        }

        if($hook->action == 'insert')
        {
            $sql = "INSERT INTO $table (";
        }
        elseif($hook->action == 'update')
        {
            $sql = "UPDATE $table ";
        }
        else
        {
            $sql = "DELETE FROM $table ";
        }

        if($hook->action == 'insert')
        {
            $values = '';
            foreach($hook->fields as $key => $field)
            {
                $sql .= "`{$field->field}`, ";
                if($field->paramType == 'form')
                {
                    $values .= "'#" . $field->param . "', ";
                }
                elseif($field->paramType == 'record')
                {
                    $values .= "'@" . $field->param . "', ";
                }
                elseif(strpos(',today,now,actor,deptManager,', ",$field->paramType,") !== false)
                {
                    $values .= "'$" . $field->param . "', ";
                }
                elseif(strpos(',currentUser,currentDept,deptManager,', ",$field->param,") !== false)
                {
                    $values .= "'$" . $field->param . "', ";
                }
                elseif($field->paramType == 'formula')
                {
                    $formula = '';
                    $params  = json_decode($field->param);
                    foreach($params as $param)
                    {
                        switch($param->type)
                        {
                        case 'target' :
                            /* Insert 时插入数据库的必须是实际值，不能用字段名代替。 */
                            $formula .= "'&" . $param->module . '_' . $param->field . (isset($param->function) ? '_' . $param->function : '') . "'";
                            break;
                        case 'operator' :
                            $formula .= ($param->operator == '(' or $param->operator == ')') ? $param->operator : " $param->operator ";
                            break;
                        case 'number' :
                            $formula .= $param->number;
                            break;
                        }
                    }
                    if(!$formula) continue;

                    $values .= "$formula, ";
                }
                else
                {
                    $values .= $this->dbh->quote($field->param ) . ', ';
                }
            }
            $values = rtrim($values, ', ');
            $sql    = rtrim($sql, ', ') . ") VALUES ({$values}) ";
        }
        elseif($hook->action == 'update')
        {
            $sql .= ' SET ';
            foreach($hook->fields as $key => $field)
            {
                if($field->paramType == 'form')
                {
                    $sql .= " `{$field->field}` = '" . '#' . $field->param . "', ";
                }
                elseif($field->paramType == 'record')
                {
                    $sql .= " `{$field->field}` = '" . '@' . $field->param . "', ";
                }
                elseif(strpos(',today,now,actor,deptManager,', ",$field->paramType,") !== false)
                {
                    $sql .= " `{$field->field}` = '" . '$' . $field->param . "', ";
                }
                elseif(strpos(',currentUser,currentDept,deptManager,', ",$field->param,") !== false)
                {
                    $sql .= " `{$field->field}` = '" . '$' . $field->param . "', ";
                }
                elseif($field->paramType == 'formula')
                {
                    $formula = '';
                    $params  = json_decode($field->param);
                    if(!$params) continue;

                    foreach($params as $param)
                    {
                        switch($param->type)
                        {
                        case 'target' :
                            if($param->module == $hook->table)
                            {
                                /* Use the column field replace the real value when the table of target is same as the table to update. */
                                $formula .= " `{$param->field}` ";
                            }
                            else
                            {
                                $formula .= "'&" . $param->module . '_' . $param->field . (isset($param->function) ? '_' . $param->function : '') . "'";
                            }
                            break;
                        case 'operator' :
                            $formula .= ($param->operator == '(' or $param->operator == ')') ? $param->operator : " $param->operator ";
                            break;
                        case 'number' :
                            $formula .= $param->value;
                            break;
                        }
                    }
                    if(!$formula) continue;

                    $sql .= " `{$field->field}` = " . $formula . ", ";
                }
                else
                {
                    $sql .= " `{$field->field}` = " . $this->dbh->quote($field->param) . ', ';
                }
            }
            $sql = rtrim($sql, ', ');
        }

        if($hook->action != 'insert')
        {
            $sql .= ' WHERE 1=1 AND (1=1 ';
            foreach($hook->wheres as $where)
            {
                $operator = $this->config->workflowhook->operatorList[$where->operator];
                if($where->paramType == 'form')
                {
                    $sql .= ' ' . strtoupper($where->logicalOperator) . " `{$where->field}` {$operator} '" . '#' . $where->param . "' ";
                }
                elseif($where->paramType == 'record')
                {
                    $sql .= ' ' . strtoupper($where->logicalOperator) . " `{$where->field}` {$operator} '" . '@' . $where->param . "' ";
                }
                elseif(strpos(',today,now,actor,deptManager,', ",$where->paramType,") !== false)
                {
                    $sql .= ' ' . strtoupper($where->logicalOperator) . " `{$where->field}` {$operator} '" . '$' . $where->param . "' ";
                }
                elseif(strpos(',currentUser,currentDept,deptManager,', ",$where->param,") !== false)
                {
                    $sql .= ' ' . strtoupper($where->logicalOperator) . " `{$where->field}` {$operator} '" . '$' . $where->param . "' ";
                }
                else
                {
                    $sql .= ' ' . strtoupper($where->logicalOperator) . " `{$where->field}` {$operator} " . $this->dbh->quote($where->param) . ' ';
                }
            }
            $sql .= ')';
        }

        if($hook->action == 'insert')
        {
            $checkSql = $sql;
        }
        else
        {
            /* Add a false condition to ensure the sql won't be execute. */
            $checkSql = $sql . ' AND 1 = 2';
        }

        $sqlVars = $this->loadModel('workflowfield', 'flow')->checkSqlVar($checkSql, '\$');
        foreach($sqlVars as $var)
        {
            $value    = $this->getParamRealValue($var);
            $checkSql = str_replace("'$" . $var . "'", $this->dbh->quote($value), $checkSql);
        }

        $formVars = $this->workflowfield->checkSqlVar($checkSql, '#');
        foreach($formVars as $var)
        {
            $value    = '';
            $checkSql = str_replace("'#" . $var . "'", $this->dbh->quote($value), $checkSql);
        }

        $recordVars = $this->workflowfield->checkSqlVar($checkSql, '@');
        foreach($recordVars as $var)
        {
            $value    = '';
            $checkSql = str_replace("'@" . $var . "'", $this->dbh->quote($value), $checkSql);
        }

        $formulaVars = $this->workflowfield->checkSqlVar($checkSql, '&');
        foreach($formulaVars as $var)
        {
            /* Replace the target in expression with 1 when check the sql. */
            $value    = '1';
            $checkSql = str_replace("'&" . $var . "'", $this->dbh->quote($value), $checkSql);
        }

        try
        {
            $this->dbh->exec($checkSql);
            if($hook->action == 'insert')
            {
                $id = $this->dbh->lastInsertID();
                $this->dbh->exec("DELETE FROM $table WHERE `id` = '{$id}'");
            }
        }
        catch(PDOException $exception)
        {
            $error = $this->lang->workflowhook->error->wrongSQL . str_replace("'", "\'", $exception->getMessage());
        }

        return array($sql, $error);
    }

    /**
     * Process post data of a hook.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function processPostData($module)
    {
        $errors      = array();
        $fields      = array();
        $sqlVars     = array();
        $formVars    = array();
        $recordVars  = array();
        $formulaVars = array();

        $controlPairs = $this->loadModel('workflowfield', 'flow')->getControlPairs($module);

        $hook = new stdclass();
        $hook->action        = $this->post->action;
        $hook->table         = $this->post->table;
        $hook->conditionType = $this->post->conditionType;
        $hook->message       = $this->post->message;
        $hook->comment       = $this->post->comment;
        if($hook->action != 'delete')
        {
            foreach($this->post->fields['field'] as $key => $value)
            {
                if(!$value) continue;

                $paramType = $this->post->fields['paramType'][$key];
                $param     = zget($controlPairs, $value) != 'richtext' ? $this->post->fields['param'][$key] : fixer::stripDataTags($this->post->fields['param'][$key]);
                if(is_array($param)) $param = implode(',', array_values(array_unique(array_filter($param))));

                $field = new stdclass();
                $field->field     = $value;
                $field->paramType = $paramType;
                $field->param     = $param;

                if($paramType == 'form')
                {
                    $formVars[] = $param;
                }
                elseif($paramType == 'record')
                {
                    $recordVars[] = $param;
                }
                elseif(!empty($paramType) && strpos(',today,now,actor,deptManager,', ",$paramType,") !== false)
                {
                    $field->param = $paramType;
                    $sqlVars[]    = $paramType;
                }
                elseif(strpos(',currentUser,currentDept,deptManager,', ",$param,") !== false)
                {
                    $sqlVars[] = $param;
                }
                elseif($paramType == 'formula')
                {
                    $params = json_decode($field->param);
                    if($params)
                    {
                        foreach($params as $param)
                        {
                            if($param->type != 'target') continue;
                            if($hook->action == 'update' && $param->module == $hook->table) continue;

                            $formulaVars[] = $param->module . '_' . $param->field . (isset($param->function) ? '_' . $param->function : '');
                        }
                    }
                    else
                    {
                        $errors['fieldsparam' . $key] = sprintf($this->lang->error->notempty, $this->lang->workflowhook->formula->common);
                    }
                }
                $fields[] = $field;
            }
            if(empty($fields)) $errors['fieldsfield1'] = sprintf($this->lang->error->notempty, $this->lang->workflowhook->field);
        }
        $hook->fields = $fields;

        $conditions = array();
        if($this->post->condition == 1)
        {
            if($hook->conditionType == 'data')
            {
                foreach($this->post->conditions['field'] as $key => $field)
                {
                    if(!$field) continue;

                    $paramType = $this->post->conditions['paramType'][$key];
                    $param     = $this->post->conditions['param'][$key];
                    if(is_array($param)) $param = implode(',', array_values(array_unique(array_filter($param))));

                    $condition = new stdclass();
                    $condition->field           = $field;
                    $condition->logicalOperator = $this->post->conditions['logicalOperator'][$key];
                    $condition->operator        = $this->post->conditions['operator'][$key];
                    $condition->paramType       = $paramType;
                    $condition->param           = $param;
                    if($paramType == 'form')
                    {
                        $formVars[] = $param;
                    }
                    elseif($condition->paramType == 'record')
                    {
                        $recordVars[] = $param;
                    }
                    elseif(!empty($paramType) && strpos(',today,now,actor,deptManager,', ",$paramType,") !== false)
                    {
                        $condition->param = $paramType;
                        $sqlVars[]        = $paramType;
                    }
                    elseif(strpos(',currentUser,currentDept,deptManager,', ",$param,") !== false)
                    {
                        $sqlVars[] = $param;
                    }

                    $conditions[] = $condition;
                }
            }
            elseif($hook->conditionType == 'sql')
            {
                if(!$this->post->sql)
                {
                    $errors['sql'] = sprintf($this->lang->error->notempty, $this->lang->workflowhook->sql);
                }
                else
                {
                    $vars      = array();
                    $varValues = array();
                    foreach($this->post->varName as $key => $varName)
                    {
                        if(!$varName) continue;

                        $param = $this->post->param[$key];
                        if(is_array($param)) $param = implode(',', array_values(array_unique(array_filter($param))));

                        $var = new stdclass();
                        $var->varName   = $varName;
                        $var->paramType = $this->post->paramType[$key];
                        $var->param     = $param;

                        $varValues[$varName] = $param;
                        $vars[] = $var;
                    }

                    $checkResult = $this->loadModel('workflowfield', 'flow')->checkSqlAndVars($this->post->sql, $varValues);
                    if($checkResult !== true) $errors['sql'] = $checkResult;

                    $conditions = new stdclass();
                    $conditions->sql       = $this->post->sql;
                    $conditions->sqlVars   = $vars;
                    $conditions->sqlResult = $this->post->sqlResult;
                }
            }
        }
        $hook->conditions = $conditions;

        $wheres = array();
        if($hook->action != 'insert')
        {
            foreach($this->post->wheres['field'] as $key => $field)
            {
                if(!$field) continue;

                $paramType = $this->post->wheres['paramType'][$key];
                $param     = $this->post->wheres['param'][$key];
                if(is_array($param)) $param = implode(',', array_values(array_unique(array_filter($param))));

                $where = new stdclass();
                $where->field           = $field;
                $where->logicalOperator = $this->post->wheres['logicalOperator'][$key];
                $where->operator        = $this->post->wheres['operator'][$key];
                $where->paramType       = $paramType;
                $where->param           = $param;
                if($paramType == 'form')
                {
                    $formVars[] = $param;
                }
                elseif($paramType == 'record')
                {
                    $recordVars[] = $param;
                }
                elseif(!empty($paramType) && strpos(',today,now,actor,deptManager,', ",$paramType,") !== false)
                {
                    $where->param = $paramType;
                    $sqlVars[]    = $paramType;
                }
                elseif(strpos(',currentUser,currentDept,deptManager,', ",$param,") !== false)
                {
                    $sqlVars[] = $param;
                }
                $wheres[] = $where;
            }
            if(empty($wheres)) $errors['wheresfield1'] = sprintf($this->lang->error->notempty, $this->lang->workflowhook->field);
        }
        $hook->wheres = $wheres;

        list($sql, $error) = $this->check($hook);
        if(!$errors && $error) $errors = $error;

        $hook->sql         = $this->config->db->driver == 'dm' ? str_replace('`', '\"', $sql) : $sql;
        $hook->sqlVars     = array_unique($sqlVars);
        $hook->formVars    = array_unique($formVars);
        $hook->recordVars  = array_unique($recordVars);
        $hook->formulaVars = array_unique($formulaVars);

        return array($hook, $errors);
    }

    /**
     * Create a hook.
     *
     * @param  int    $action
     * @access public
     * @return void
     */
    public function create($action)
    {
        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);
        $hooks  = $action->hooks;

        list($hook, $errors) = $this->processPostData($action->module);

        if($errors)
        {
            dao::$errors = $errors;
            return false;
        }

        if(!is_array($hooks)) $hooks = array();

        $hooks[] = $hook;

        $hooksJson = json_encode($hooks);
        if($this->config->db->driver == 'dm') $hooksJson = str_replace(array('\"', '`'), array('\\\"', '\\\`'), $hooksJson);
        $this->dao->update(TABLE_WORKFLOWACTION)
            ->set('hooks')->eq($hooksJson)
            ->autoCheck()
            ->where('id')->eq($action->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update a hook.
     *
     * @param  int    $action
     * @param  int    $key
     * @access public
     * @return void
     */
    public function update($action, $key)
    {
        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);
        $hooks  = $action->hooks;

        list($hook, $errors) = $this->processPostData($action->module);

        if($errors)
        {
            dao::$errors = $errors;
            return false;
        }

        if(!is_array($hooks))
        {
            $hooks = array();
            $hooks[$key] = $hook;
        }
        else
        {
            $hooks[$key] = $hook;
        }

        $hooksJson = json_encode($hooks);
        if($this->config->db->driver == 'dm') $hooksJson = str_replace(array('\"', '`'), array('\\\"', '\\\`'), $hooksJson);
        $this->dao->update(TABLE_WORKFLOWACTION)
            ->set('hooks')->eq($hooksJson)
            ->autoCheck()
            ->where('id')->eq($action->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * Delete a hook.
     *
     * @param  int    $action
     * @param  int    $key
     * @access public
     * @return void
     */
    public function delete($action, $key)
    {
        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);
        $hooks  = $action->hooks;

        if(is_array($hooks)  and isset($hooks[$key])) unset($hooks[$key]);
        if(is_object($hooks) and isset($hooks->$key))
        {
            unset($hooks->$key);
            $hooks = (array)$hooks;
        }

        /* Make sure hooks is a indexed array. */
        $hooks = array_values($hooks);

        $this->dao->update(TABLE_WORKFLOWACTION)
            ->set('hooks')->eq(helper::jsonEncode($hooks))
            ->autoCheck()
            ->where('id')->eq($action->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * Execute hooks of an action.
     *
     * @param  object $flow
     * @param  object $action
     * @param  int    $dataID
     * @param  bool   $createAction
     * @access public
     * @return string
     */
    public function execute($flow, $action, $dataID, $createAction = false)
    {
        if(dao::isError()) return '';
        if($flow->buildin && $action->buildin && $action->extensionType == 'normal') return '';

        $message = array();
        $this->loadModel('flow');
        $this->loadModel('action');
        $this->loadModel('workflow', 'flow');
        $this->loadModel('workflowfield', 'flow');

        /* Check action hooks. */
        if(!empty($action->hooks) and $dataID)
        {
            foreach($action->hooks as $hook)
            {
                if(!$hook->sql) continue;

                /* Get data from db at the begin of each hook for it may be updated by other hooks. */
                $data = $this->flow->getDataByID($flow, $dataID, $decode = false);
                $type = zget($hook, 'conditionType', 'data');

                $canResult = true;
                if($type == 'sql')
                {
                    if(!empty($hook->conditions) && is_object($hook->conditions))
                    {
                        $sql = $hook->conditions->sql;
                        foreach($hook->conditions->sqlVars as $sqlVar)
                        {
                            if($sqlVar->paramType == 'form')
                            {
                                if(!isset($this->post->{$sqlVar->param}))
                                {
                                    $canResult = false;
                                    break;
                                }
                                $sqlVar->param = $this->post->{$sqlVar->param};
                            }
                            elseif($sqlVar->paramType == 'record')
                            {
                                if(!isset($data->{$sqlVar->param}))
                                {
                                    $canResult = false;
                                    break;
                                }
                                $sqlVar->param = $data->{$sqlVar->param};
                            }
                            elseif(!empty($sqlVar->paramType) && strpos(',today,now,actor,deptManager,', ",$sqlVar->paramType,") !== false)
                            {
                                $sqlVar->param = $this->getParamRealValue($sqlVar->paramType);
                            }
                            elseif(strpos(',currentUser,currentDept,deptManager,', ",$sqlVar->param,") !== false)
                            {
                                $sqlVar->param = $this->getParamRealValue($sqlVar->param);
                            }

                            if(is_array($sqlVar->param)) $sqlVar->param = implode(',', array_values(array_unique(array_filter($sqlVar->param))));

                            $sql = str_replace("'$" . $sqlVar->varName . "'", $this->dbh->quote($sqlVar->param), $sql);
                        }
                        $sql = $this->workflowfield->replaceTableNames($sql);

                        try
                        {
                            $sqlResult = $this->dbh->query($sql)->fetch();
                            if($hook->conditions->sqlResult == 'empty')
                            {
                                $canResult = empty($sqlResult);
                            }
                            elseif($hook->conditions->sqlResult == 'notempty')
                            {
                                $canResult = !empty($sqlResult);
                            }
                        }
                        catch(PDOException $exception)
                        {
                            $canResult = false;
                        }
                    }
                }
                if($type == 'data')
                {
                    if(!empty($hook->conditions) && is_array($hook->conditions))
                    {
                        foreach($hook->conditions as $condition)
                        {
                            if(!$condition->field || !$condition->operator) continue;

                            /* 扩展动作的触发条件中的字段数据源不包含表单数据和当前数据，仅需要判断下列3个类型。 */
                            /* The datasource of field in condition of action's result doesn't contain form data and current data. Just process the flow 3 types.*/
                            if(!empty($condition->paramType) && strpos(',today,now,actor,deptManager,', ",$condition->paramType,") !== false)
                            {
                                $condition->param = $this->getParamRealValue($condition->paramType);
                            }
                            elseif(strpos(',currentUser,currentDept,deptManager,', ",$condition->param,") !== false)
                            {
                                $condition->param = $this->getParamRealValue($condition->param);
                            }

                            $checkFunc = 'check' . $condition->operator;
                            $checkVar  = zget($data, $condition->field, '');

                            if(is_array($checkVar)) $checkVar = implode(',', array_values(array_unique(array_filter($checkVar))));

                            $curResult = validater::$checkFunc($checkVar, $condition->param);
                            $canResult = ($condition->logicalOperator == 'and') ? ($canResult && $curResult) : ($canResult || $curResult);
                        }
                    }
                }
                if($canResult)
                {
                    $sql = $hook->sql;
                    /* Replace vars as real value. */
                    foreach($hook->sqlVars as $var) $sql = str_replace("'$" . $var . "'",  $this->dbh->quote($this->getParamRealValue($var)), $sql);
                    foreach($hook->formVars as $var)
                    {
                        $postData = $this->post->$var;

                        if(!is_array($postData)) $postData = explode(',', $postData);
                        foreach($postData as $key => $value) $postData[$key] = $this->getParamRealValue($value);

                        $postData = array_values(array_unique(array_filter($postData)));
                        asort($postData);
                        $postData = implode(',', $postData);

                        $sql = str_replace("'#" . $var . "'",  $this->dbh->quote($postData), $sql);
                    }
                    foreach($hook->recordVars as $var)
                    {
                        $varData = is_array($data->$var) ? implode(',', $data->$var) : $data->$var;
                        $sql = str_replace("'@" . $var . "'",  $this->dbh->quote($varData), $sql);
                    }
                    if(!empty($hook->formulaVars))
                    {
                        foreach($hook->formulaVars as $var)
                        {
                            $varValue = 0;
                            $params   = explode('_', $var);
                            if(count($params) == 2)
                            {
                                list($module, $field) = $params;

                                $varValue = $data->$field;
                            }
                            if(count($params) == 3)
                            {
                                list($module, $field, $func) = $params;

                                static $childDatas = array();
                                static $fieldDatas = array();
                                if(empty($childDatas[$module][$dataID]))
                                {
                                    /* Fetch child datas from db. */
                                    $childFlow = $this->workflow->getByModule($module);
                                    $childDatas[$module][$dataID] = $this->flow->getDataList($childFlow, 'browse', '', '', $dataID);
                                }
                                if(empty($fieldDatas[$module][$field][$dataID]))
                                {
                                    /* Store field datas. */
                                    foreach($childDatas[$module][$dataID] as $childData) $fieldDatas[$module][$field][$dataID][] = zget($childData, $field, 0);
                                }
                                $fieldValues = isset($fieldDatas[$module][$field][$dataID]) ? $fieldDatas[$module][$field][$dataID] : array();

                                switch($func)
                                {
                                case 'sum' :
                                    $varValue = array_sum($fieldValues);
                                    break;
                                case 'average' :
                                    $varValue = array_sum($fieldValues) / count($fieldValues);
                                    break;
                                case 'max' :
                                    asort($fieldValues);
                                    $varValue = end($fieldValues);
                                    break;
                                case 'min' :
                                    asort($fieldValues);
                                    $varValue = reset($fieldValues);
                                    break;
                                case 'count' :
                                    $varValue = count($fieldValues);
                                    break;
                                }
                            }

                            $sql = str_replace("'&" . $var . "'",  $this->dbh->quote($varValue), $sql);
                        }
                    }

                    try
                    {
                        $this->dbh->exec($sql);

                        $this->dao->setTableCache($sql);

                        if(!empty($hook->message)) $message[] = $hook->message;

                        if($createAction)
                        {
                            $actionID = $this->action->create($flow->module, $dataID, 'executeHooks');
                            if($flow->module == $hook->table)
                            {
                                $newData = $this->flow->getDataByID($flow, $dataID, $decode = false);
                                if($newData)
                                {
                                    $changes = commonModel::createChanges($data, $newData);
                                    if($changes) $this->action->logHistory($actionID, $changes);
                                }
                            }
                        }
                    }
                    catch(PDOException $exception)
                    {
                        $this->action->create($flow->module, $dataID, 'executeHooksFail', $exception->getMessage());
                    }
                }
            }
        }

        return implode('; ', $message);
    }
}
