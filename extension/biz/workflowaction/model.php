<?php
/**
 * The model file of workflowaction module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowaction
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowactionModel extends model
{
    /**
     * Get an action by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $action = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)->where('id')->eq($id)->fetch();
        if($action) $action = $this->decode($action);

        return $action;
    }

    /**
     * Get an action by module and action.
     *
     * @param  string $module
     * @param  string $actionName
     * @access public
     * @return object
     */
    public function getByModuleAndAction($module, $actionName, $groupID = null)
    {
        $groupID = !is_null($groupID) ? $groupID : $this->session->workflowGroupID;
        $action  = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->andWhere('action')->eq($actionName)
            ->andWhere('group')->eq((int)$groupID)
            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
            ->fetch();

        if(!$action) $action = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->andWhere('action')->eq($actionName)
            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
            ->fetch();

        if($action) $action = $this->decode($action);

        return $action;
    }

    /**
     * Get action list.
     *
     * @param  string $module
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getList($module, $orderBy = 'status_desc,order_asc', $groupID = null)
    {
        $groupID = !is_null($groupID) ? $groupID : $this->session->workflowGroupID;
        $actions = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->andWhere('group')->eq((int)$groupID)
            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id', false);

        if(!$actions)
        {
            $actions = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)
                ->where('module')->eq($module)
                ->andWhere('group')->eq(0)
                ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
                ->orderBy($orderBy)
                ->fetchAll('id', false);
        }

        foreach($actions as $action) $action = $this->decode($action);

        return $actions;
    }

    /**
     * Get action list by group.
     *
     * @param  string $status       '' | enable | disable
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getGroupList($status = '', $orderBy = 'status_desc,order_asc')
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWACTION)
            ->where('vision')->eq($this->config->vision)
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->orderBy($orderBy)
            ->fetchGroup('module');
    }

    /**
     * Get action pairs.
     *
     * @param  string $module
     * @param  string $status       '' | enable | disable
     * @access public
     * @return array
     */
    public function getPairs($module, $status = '')
    {
        $actions = $this->dao->select('id, name')->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
            ->fetchPairs();

        return arrayUnion(array('' => ''), $actions);
    }

    /**
     * Get report actions.
     *
     * @access public
     * @return array
     */
    public function getReportActions()
    {
        return $this->dao->select('module, name')->from(TABLE_WORKFLOWACTION)->where('action')->eq('report')->fetchPairs();
    }

    /**
     * New a field object.
     *
     * @access public
     * @return object
     */
    public function getNewField()
    {
        $field = new stdclass();
        $field->id           = 0;
        $field->module       = '';
        $field->field        = '';
        $field->type         = '';
        $field->length       = '';
        $field->name         = '';
        $field->control      = '';
        $field->expression   = '';
        $field->buildin      = '0';
        $field->canExport    = '0';
        $field->canSearch    = '0';
        $field->isValue      = '0';
        $field->show         = '0';
        $field->width        = 'auto';
        $field->position     = '';
        $field->readonly     = '0';
        $field->mobileShow   = '0';
        $field->summary      = '';
        $field->defaultValue = '';
        $field->rules        = '';
        $field->layoutRules  = '';
        $field->options      = array();

        return $field;
    }

    /**
     * 获取操作类的动作。
     * Get operate actions.
     *
     * @access public
     * @return array
     */
    public function getOperateActions($moduleList = array())
    {
        return $this->dao->select('module,action,name')->from(TABLE_WORKFLOWACTION)
            ->where('group')->eq('0')
            ->andWhere('method')->in('create,edit,delete,operate')
            ->beginIF($moduleList)->andWhere('module')->in($moduleList)->fi()
            ->andWhere('vision')->eq('rnd')
            ->fetchGroup('module');
    }

    /**
     * 获取工作流的所有字段和配置。
     * Get all fields and configurations of a workflow.
     *
     * @param  string $module
     * @param  string $action
     * @param  bool   $getRealOptions
     * @param  array  $datas
     * @param  int    $ui
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getFields($module, $action, $getRealOptions = true, $datas = array(), $ui = 0, $groupID = null)
    {
        $groupID       = !is_null($groupID) ? (int)$groupID : (int)$this->session->workflowGroupID;
        $lowerAction   = strtolower($action);
        $relatedModule = isset($this->config->workflowaction->buildin->relatedModules[$module][$lowerAction]) ? $this->config->workflowaction->buildin->relatedModules[$module][$lowerAction] : '';
        $actionFields  = $this->getActionFields($module, $action, $ui, $relatedModule, $groupID);

        if($relatedModule) $module = $relatedModule;

        $datas     = $this->mergeDefaultDatas($actionFields, $datas);
        $flow      = $this->loadModel('workflow')->getByModule($module, false, $groupID);
        $subTables = $this->workflow->getPairs($module, $type = 'table', '', 'normal', $groupID);
        $activeSubTables = array();
        if($subTables)
        {
            $subFields = $this->getActionFields(implode(',', array_keys($subTables)), $action, $ui, '', $groupID);
            foreach($subFields as $field) $activeSubTables["sub_{$field->module}"] = $field->module;
        }
        if($flow and $flow->type == 'flow') $action = $this->getByModuleAndAction($module, $action);

        $fieldList = $this->loadModel('workflowfield', 'flow')->getList($module);
        $fieldList = $this->processFields($fieldList, $getRealOptions, $datas);

        foreach($actionFields as $key => $field)
        {
            $field = $this->workflowfield->processFieldOptions($field);

            if(!isset($fieldList[$field->field])) continue;

            $field->options = $fieldList[$field->field]->options;
            if(!empty($fieldList[$field->field]->default) && empty($field->default)) $field->default = $fieldList[$field->field]->default;
        }

        $fields = array();
        if(empty($actionFields))
        {
            foreach($fieldList as $field)
            {
                if(!$field) continue;
                if(zget($flow, 'type') == 'table' && $field->field == 'mailto') continue;

                $fields[$field->field] = $this->getNewField();
                $fields[$field->field]->id          = $field->id;
                $fields[$field->field]->module      = $module;
                $fields[$field->field]->field       = $field->field;
                $fields[$field->field]->type        = $field->type;
                $fields[$field->field]->length      = $field->length;
                $fields[$field->field]->name        = $field->name;
                $fields[$field->field]->control     = $field->control;
                $fields[$field->field]->expression  = $field->expression;
                $fields[$field->field]->buildin     = $field->buildin;
                $fields[$field->field]->canExport   = $field->canExport;
                $fields[$field->field]->canSearch   = $field->canSearch;
                $fields[$field->field]->isValue     = $field->isValue;
                $fields[$field->field]->options     = $field->options;
                $fields[$field->field]->default     = $field->default;
                $fields[$field->field]->role        = $field->role;
                $fields[$field->field]->placeholder = $field->placeholder;
                $fields[$field->field]->hasAction   = false;
            }

            if(zget($action, 'method') == 'browse' && zget($action, 'extensionType') == 'override')
            {
                $fields['actions'] = $this->getNewField();
                $fields['actions']->id     = 'actions';
                $fields['actions']->module = $module;
                $fields['actions']->field  = 'actions';
                $fields['actions']->name   = $this->lang->actions;
                $fields['actions']->show   = '1';
                $fields['actions']->width  = 'auto';
            }

            if(zget($flow, 'type') == 'flow' && zget($action, 'method') != 'browse' && zget($action, 'type') != 'batch')
            {
                foreach($subTables as $subModule => $subName)
                {
                    /* 为了把明细表代号和主表字段区分开，增加前缀。In order to distinguish the schedule code from the main table field, add a prefix. */
                    $subModule = 'sub_' . $subModule;

                    $fields[$subModule] = $this->getNewField();
                    $fields[$subModule]->id     = $subModule;
                    $fields[$subModule]->module = $module;
                    $fields[$subModule]->field  = $subModule;
                    $fields[$subModule]->name   = $subName;
                    $fields[$subModule]->show   = isset($activeSubTables[$subModule]);
                }
            }
        }
        else
        {
            foreach($actionFields as $key => $field)
            {
                if(!$field) continue;

                if($field->layoutRules)
                {
                    $rules = "{$field->rules},{$field->layoutRules}";
                    $rules = explode(',', $rules);

                    $field->rules = implode(',', array_unique($rules));
                }

                $fields[$key] = $this->getNewField();
                $fields[$key]->id           = ($key == 'actions') ? $key : $field->id;
                $fields[$key]->module       = $module;
                $fields[$key]->field        = $field->field;
                $fields[$key]->type         = $field->type;
                $fields[$key]->length       = $field->length;
                $fields[$key]->name         = zget($subTables, str_replace('sub_', '', $key), $field->name);    // 把前缀去掉来获得明细表名。
                $fields[$key]->control      = $field->control;
                $fields[$key]->expression   = $field->expression;
                $fields[$key]->buildin      = $field->buildin;
                $fields[$key]->canExport    = $field->canExport;
                $fields[$key]->canSearch    = $field->canSearch;
                $fields[$key]->isValue      = $field->isValue;
                $fields[$key]->show         = '1';
                $fields[$key]->width        = $field->width ? $field->width : 'auto';
                $fields[$key]->position     = $field->position;
                $fields[$key]->readonly     = $field->readonly;
                $fields[$key]->mobileShow   = $field->mobileShow;
                $fields[$key]->summary      = $field->summary;
                $fields[$key]->defaultValue = $field->defaultValue;
                $fields[$key]->rules        = $field->rules;
                $fields[$key]->sql          = empty($field->sql) ? '' : $field->sql;
                $fields[$key]->layoutRules  = $field->layoutRules;
                $fields[$key]->options      = $field->options;
                $fields[$key]->default      = $field->default;
                $fields[$key]->role         = $field->role;
                $fields[$key]->placeholder  = $field->placeholder;
                $fields[$key]->hasAction    = true;

                if($key == 'actions') $fields[$key]->name = $this->lang->actions;
                if($key == 'actions' && zget($action, 'method') == 'browse') $fields[$key]->show = 1;
            }

            foreach($fieldList as $id => $field)
            {
                if(!$field or isset($actionFields[$field->field])) continue;
                if(zget($flow, 'type') == 'table' && $field->field == 'mailto') continue;

                $fields[$field->field] = $this->getNewField();
                $fields[$field->field]->id         = $field->id;
                $fields[$field->field]->module     = $module;
                $fields[$field->field]->field      = $field->field;
                $fields[$field->field]->type       = $field->type;
                $fields[$field->field]->length     = $field->length;
                $fields[$field->field]->name       = $field->name;
                $fields[$field->field]->control    = $field->control;
                $fields[$field->field]->expression = $field->expression;
                $fields[$field->field]->buildin    = $field->buildin;
                $fields[$field->field]->canExport  = $field->canExport;
                $fields[$field->field]->canSearch  = $field->canSearch;
                $fields[$field->field]->isValue    = $field->isValue;
                $fields[$field->field]->options    = $field->options;
                $fields[$field->field]->default    = $field->default;
                $fields[$field->field]->role       = $field->role;
            }

            if(zget($action, 'method') == 'browse' && zget($action, 'extensionType') == 'override' && !isset($fields['actions']))
            {
                $fields['actions'] = $this->getNewField();
                $fields['actions']->id      = 'actions';
                $fields['actions']->module  = $module;
                $fields['actions']->field   = 'actions';
                $fields['actions']->name    = $this->lang->actions;
                $fields['actions']->show    = '1';
                $fields['actions']->width   = 'auto';
                $fields['actions']->default = '';
            }

            if(zget($flow, 'type') == 'flow' && zget($action, 'method') != 'browse' && zget($action, 'type') != 'batch')
            {
                foreach($subTables as $subModule => $subName)
                {
                    if(!$subModule) continue;

                    /* 为了把明细表代号和主表字段区分开，增加前缀。In order to distinguish the schedule code from the main table field, add a prefix. */
                    if($subModule) $subModule = 'sub_' . $subModule;
                    if(isset($actionFields[$subModule])) continue;

                    $fields[$subModule] = $this->getNewField();
                    $fields[$subModule]->id      = $subModule;
                    $fields[$subModule]->module  = $module;
                    $fields[$subModule]->field   = $subModule;
                    $fields[$subModule]->name    = $subName;
                    $fields[$subModule]->show    = isset($activeSubTables[$subModule]);
                    $fields[$subModule]->default = '';
                }
            }
        }

        return $fields;
    }

    /**
     * 获取动作页面上展示的字段。
     * Get fields of an action.
     *
     * @param  string $module
     * @param  string $action
     * @param  bool   $getRealOptions
     * @param  array  $datas
     * @param  int    $ui
     * @param  string $relatedModule
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getActionFields($module, $action, $ui = 0, $relatedModule = '', $groupID = 0)
    {
        $flows  = $this->dao->select('id,type,module')->from(TABLE_WORKFLOW)->where('module')->in($module)->andWhere('group')->eq($groupID)->fetchGroup('type', 'module');
        $fields = array();
        foreach($flows as $type => $modules)
        {
            $joinCondition = 't1.module=t2.module AND t1.field=t2.field AND t1.`group`=t2.`group`';
            if($relatedModule)  $joinCondition  = "t2.module='{$relatedModule}' AND t1.field=t2.field";

            $actionFields = $this->dao->select("t1.*, t2.id, t2.name, t2.control, t2.expression, t2.options, t2.type, t2.length, t2.rules, t2.placeholder, t2.canExport, t2.canSearch, t2.isValue, t2.desc, t2.buildin, t2.default, t2.role")
                ->from(TABLE_WORKFLOWLAYOUT)->alias('t1')
                ->leftJoin(TABLE_WORKFLOWFIELD)->alias('t2')->on($joinCondition)
                ->where('t1.module')->in(array_keys($modules))
                ->andWhere('t1.action')->eq($action)
                ->andWhere('t1.ui')->eq($ui)
                ->andWhere('t1.`group`')->eq($groupID)
                ->beginIF(!empty($this->config->vision))->andWhere('t1.vision')->eq($this->config->vision)->fi()
                ->orderBy('t1.order, t2.order, t2.id')
                ->fetchAll('field', false);
            $fields = arrayUnion($fields, $actionFields);
        }
        return $fields;
    }

    /**
     * 简化版的getFields，只获取页面上展示的字段及其配置。
     * Get only show fields of a page.
     *
     * @param  string $module
     * @param  string $action
     * @param  bool   $getRealOptions
     * @param  array  $datas
     * @param  int    $ui
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getPageFields($module, $action, $getRealOptions = true, $datas = array(), $ui = 0, $groupID = 0)
    {
        $lowerAction   = strtolower($action);
        $relatedModule = isset($this->config->workflowaction->buildin->relatedModules[$module][$lowerAction]) ? $this->config->workflowaction->buildin->relatedModules[$module][$lowerAction] : '';
        $actionFields  = $this->getActionFields($module, $action, $ui, $relatedModule, $groupID);

        if(empty($actionFields)) return array();
        if($relatedModule) $module = $relatedModule;

        $datas = $this->mergeDefaultDatas($actionFields, $datas);
        $flow  = $this->loadModel('workflow')->getByModule($module, false, $groupID);
        if($flow and $flow->type == 'flow') $action = $this->getByModuleAndAction($module, $action, $groupID);

        $subTables    = $flow->buildin ? [] : $this->workflow->getPairs($module, $type = 'table');
        $actionFields = $this->processFields($actionFields, $getRealOptions, $datas);
        $activeSubTables = array();
        if($subTables)
        {
            $subFields = $this->getActionFields(implode(',', array_keys($subTables)), $action->action, $ui, '', $groupID);
            foreach($subFields as $field) $activeSubTables["sub_{$field->module}"] = $field->module;
        }

        $fields = array();
        foreach($actionFields as $key => $field)
        {
            if(!$field) continue;

            if($field->layoutRules)
            {
                $rules = "{$field->rules},{$field->layoutRules}";
                $rules = explode(',', $rules);

                $field->rules = implode(',', array_unique($rules));
            }

            $fields[$key] = $this->getNewField();
            $fields[$key]->id           = ($key == 'actions') ? $key : $field->id;
            $fields[$key]->module       = $module;
            $fields[$key]->field        = $field->field;
            $fields[$key]->type         = $field->type;
            $fields[$key]->length       = $field->length;
            $fields[$key]->name         = zget($subTables, str_replace('sub_', '', $key), $field->name);    // 把前缀去掉来获得明细表名。
            $fields[$key]->control      = $field->control;
            $fields[$key]->expression   = $field->expression;
            $fields[$key]->buildin      = $field->buildin;
            $fields[$key]->canExport    = $field->canExport;
            $fields[$key]->canSearch    = $field->canSearch;
            $fields[$key]->isValue      = $field->isValue;
            $fields[$key]->show         = '1';
            $fields[$key]->width        = $field->width ? $field->width : 'auto';
            $fields[$key]->position     = $field->position;
            $fields[$key]->readonly     = $field->readonly;
            $fields[$key]->mobileShow   = $field->mobileShow;
            $fields[$key]->summary      = $field->summary;
            $fields[$key]->defaultValue = $field->defaultValue;
            $fields[$key]->rules        = $field->rules;
            $fields[$key]->sql          = empty($field->sql) ? '' : $field->sql;
            $fields[$key]->layoutRules  = $field->layoutRules;
            $fields[$key]->options      = $field->options;
            $fields[$key]->default      = $field->default;
            $fields[$key]->placeholder  = $field->placeholder;

            if($key == 'actions') $fields[$key]->name = $this->lang->actions;
            if($key == 'actions' && zget($action, 'method') == 'browse') $fields[$key]->show = 1;
        }

        if(zget($flow, 'type') == 'flow' && zget($action, 'method') != 'browse' && zget($action, 'type') != 'batch')
        {
            foreach($subTables as $subModule => $subName)
            {
                if(!$subModule) continue;

                /* 为了把明细表代号和主表字段区分开，增加前缀。In order to distinguish the schedule code from the main table field, add a prefix. */
                if($subModule) $subModule = 'sub_' . $subModule;
                if(isset($actionFields[$subModule])) continue;

                $fields[$subModule] = $this->getNewField();
                $fields[$subModule]->id      = $subModule;
                $fields[$subModule]->module  = $module;
                $fields[$subModule]->field   = $subModule;
                $fields[$subModule]->name    = $subName;
                $fields[$subModule]->default = '';
                $fields[$subModule]->show    = isset($activeSubTables[$subModule]);
            }
        }

        return $fields;
    }

    /**
     * Get real options.
     *
     * @param  object $field
     * @param  array  $values
     * @param  bool   $importData
     * @access public
     * @return array
     */
    public function getRealOptions($field, $values = array(), $importData = false)
    {
        $options = array();
        if(in_array($field->control, $this->config->workflowfield->optionControls))
        {
            $options = $this->loadModel('workflowfield', 'flow')->getFieldOptions($field, true, $values, '', $this->config->flowLimit, $importData);
        }

        if($field->options == 'user' || $field->options == 'dept' || $field->control == 'date' || $field->control == 'datetime')
        {
            $emptyKey = in_array($field->type, $this->config->workflowfield->numberTypes) ? 0 : '';
            unset($options[$emptyKey]);

            $optionKey = $field->options;
            if($field->control == 'date' || $field->control == 'datetime') $optionKey = 'time';

            $this->app->loadLang('workflowlayout', 'flow');
            $options = arrayUnion(array($emptyKey => ''), $this->lang->workflowlayout->default->$optionKey, $options);
        }

        return $options;
    }

    /**
     * Get users to notice.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getUsers2Notice($module)
    {
        $this->app->loadLang('workflowdatasource', 'flow');

        $toList = array('deptManager' => $this->lang->workflowdatasource->options['deptManager']);
        $fields = $this->loadModel('workflowfield', 'flow')->getList($module);
        if($fields)
        {
            foreach($fields as $field)
            {
                if($field->options == 'user') $toList[$field->field] = $field->name;
            }
        }

        return arrayUnion($toList, $this->loadModel('user')->getDeptPairs('nodeleted,noforbidden,noclosed'));
    }

    /**
     * Merge field's default values and datas.
     *
     * @param  array  $fields
     * @param  mixed  $datas
     * @access public
     * @return array
     */
    public function mergeDefaultDatas($fields, $datas)
    {
        $data = array();
        foreach($fields as $field)
        {
            if(!empty($field->default))      $data[$field->field] = explode(',', $field->default);
            if(!empty($field->defaultValue)) $data[$field->field] = explode(',', $field->defaultValue);
        }

        if(!empty($data))
        {
            if(empty($datas))
            {
                $datas = array($data);
            }
            else
            {
                if(is_array($datas))
                {
                    $datas[] = $data;
                }
                else
                {
                    $datas = array($datas, $data);
                }
            }
        }

        return $datas;
    }

    /**
     * Process field's real options and make sure the real options contain the data.
     *
     * @param  array  $fields
     * @param  bool   $getRealOptions
     * @param  array  $datas
     * @param  bool   $importData
     * @access public
     * @return array
     */
    public function processFields($fields, $getRealOptions = true, $datas = array(), $importData = false)
    {
        $this->loadModel('workflowfield', 'flow');

        $values = array();
        foreach($fields as $field)
        {
            $field = $this->workflowfield->processFieldOptions($field);

            if(!empty($field->default))      $values[$field->field] = explode(',', $field->default);
            if(!empty($field->defaultValue)) $values[$field->field] = explode(',', $field->defaultValue);
        }

        if($getRealOptions)
        {
            if(!empty($datas) && (is_array($datas) or is_object($datas)))
            {
                if(!is_array($datas)) $datas = array($datas);

                foreach($datas as $data)
                {
                    foreach($data as $key => $value)
                    {
                        if(!isset($fields[$key])) continue;

                        $field = $fields[$key];

                        if(!in_array($field->control, $this->config->workflowfield->optionControls)) continue;

                        if(!is_array($value)) $value = explode(',', $value);

                        if(isset($values[$key]))
                        {
                            $values[$key] = array_merge($values[$key], $value);
                        }
                        else
                        {
                            $values[$key] = $value;
                        }
                    }
                }
            }

            foreach($values as $field => $value) $values[$field] = array_unique(array_filter($value));
            $values = array_filter($values);

            foreach($fields as $field) $field->options = $this->getRealOptions($field, zget($values, $field->field, ''), $importData);
        }

        return $fields;
    }

    /**
     * Create an action.
     *
     * @access public
     * @return bool | int
     */
    public function create()
    {
        $action = fixer::input('post')
            ->add('method', $this->post->type == 'single' ? 'operate' : 'batchoperate')
            ->add('extensionType', 'override')
            ->add('conditions', '[]')
            ->add('hooks', '[]')
            ->add('status', 'enable')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->add('vision', $this->config->vision)
            ->add('group', (int)$this->session->workflowGroupID)
            ->setIF($this->post->type == 'batch', 'position', 'browse')
            ->setIF($this->post->type == 'batch', 'show', 'direct')
            ->setForce('action', strtolower(str_replace(' ', '', $this->post->action)))
            ->get();

        if(in_array($action->action, $this->config->workflowaction->defaultActions))
        {
            dao::$errors['action'][] = sprintf($this->lang->workflowaction->error->builtinCode, $action->action);
        }

        if(!empty($action->action) && !validater::checkREG($action->action, '|^[a-z]+$|'))
        {
            dao::$errors['action'][] = sprintf($this->lang->workflowaction->error->wrongCode, $this->lang->workflowaction->action);
        }

        if($action->action == 'action') dao::$errors['action'][] = sprintf($this->lang->workflowaction->error->conflict, $action->action);

        if(dao::isError()) return false;

        $maxOrder = $this->dao->select('MAX(`order`) AS `order`')->from(TABLE_WORKFLOWACTION)->where('module')->eq($action->module)->fetch('order');
        $action->order = $maxOrder + 1;

        $this->dao->insert(TABLE_WORKFLOWACTION)->data($action)
            ->autoCheck()
            ->batchCheck($this->config->workflowaction->require->create, 'notempty')
            ->batchCheck($this->config->workflowaction->uniqueFields, 'unique', "module='$action->module' AND `group`='$action->group'")
            ->exec();

        if(dao::isError()) return false;

        $actionID = $this->dao->lastInsertId();

        /* Create the actionBy and actionDate fields. */
        if($action->action)
        {
            $action->id = $actionID;
            $result = $this->createFields($action);
            if(!$result) $this->delete($actionID);
        }

        return $actionID;
    }

    /**
     * Create the actionBy and actionDate fields for an action.
     *
     * @param  object $action
     * @access public
     * @return bool
     */
    public function createFields($action)
    {
        $code  = $action->action . 'By';
        $flow  = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $field = $this->loadModel('workflowfield', 'flow')->getByField($flow->module, $code);
        if(!$field)
        {
            $sql = "ALTER TABLE `$flow->table` ADD `$code` varchar(30) NOT NULL";

            try
            {
                $this->dbh->query($sql);

                $orderField = $this->workflowfield->getLastField($flow->module);
                $order      = $orderField->order;

                $field = new stdclass();
                $field->module      = $flow->module;
                $field->field       = $code;
                $field->type        = 'varchar';
                $field->length      = 30;
                $field->name        = sprintf($this->lang->workflowaction->actionBy, $action->name);
                $field->control     = 'select';
                $field->options     = 'user';
                $field->order       = $order + 1;
                $field->createdBy   = $this->app->user->account;
                $field->createdDate = helper::now();
                $field->readonly    = '1';  // Make sure user can not change the field.

                $this->dao->insert(TABLE_WORKFLOWFIELD)->data($field)->exec();

                $this->dao->update(TABLE_WORKFLOWFIELD)->set('`order` = `order` + 1')
                    ->where('module')->eq($flow->module)
                    ->andWhere('field')->ne($code)
                    ->andWhere('`order`')->gt($order)
                    ->exec();

                if(dao::isError())
                {
                    $sql = "ALTER TABLE `$flow->table` DROP `$code`";
                    $this->dbh->query($sql);
                }
            }
            catch(PDOException $exception)
            {
                dao::$errors[] = $exception->getMessage();
            }
        }

        if(dao::isError()) return false;

        $code  = $action->action . 'Date';
        $field = $this->workflowfield->getByField($flow->module, $code);
        if(!$field)
        {
            $sql = "ALTER TABLE `$flow->table` ADD `$code` datetime NULL";
            try
            {
                $this->dbh->query($sql);

                $orderField = $this->workflowfield->getLastField($flow->module);
                $order      = $orderField->order;

                $field = new stdclass();
                $field->module      = $flow->module;
                $field->field       = $code;
                $field->type        = 'datetime';
                $field->name        = sprintf($this->lang->workflowaction->actionDate, $action->name);
                $field->control     = 'datetime';
                $field->options     = '[]';
                $field->order       = $order + 1;
                $field->createdBy   = $this->app->user->account;
                $field->createdDate = helper::now();
                $field->readonly    = '1';

                $this->dao->insert(TABLE_WORKFLOWFIELD)->data($field)->exec();

                $this->dao->update(TABLE_WORKFLOWFIELD)->set('`order` = `order` + 1')
                    ->where('module')->eq($flow->module)
                    ->andWhere('field')->ne($code)
                    ->andWhere('`order`')->gt($order)
                    ->exec();

                if(dao::isError())
                {
                    $sql = "ALTER TABLE `$flow->table` DROP `$code`";
                    $this->dbh->query($sql);
                }
            }
            catch(PDOException $exception)
            {
                dao::$errors[] = $exception->getMessage();
            }
        }

        return !dao::isError();
    }

    /**
     * Update an action.
     *
     * @param  int    $id
     * @access public
     * @return array
     */
    public function update($id)
    {
        $oldAction = $this->getByID($id);

        $action = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->add('group', (int)$this->session->workflowGroupID)
            ->setIF($this->post->type == 'batch', 'position', 'browse')
            ->setIF($this->post->type == 'batch', 'show', 'direct')
            ->setIF($this->post->action, 'action', strtolower(str_replace(' ', '', $this->post->action)))
            ->setIF(in_array($oldAction->action, $this->config->workflowaction->noDisableActions), 'status', 'enable')
            ->setDefault('status', $oldAction->status)
            ->get();

        if(!empty($action->action) && !validater::checkREG($action->action, '|^[A-Za-z]+$|'))
        {
            dao::$errors['action'][] = sprintf($this->lang->workflowaction->error->wrongCode, $this->lang->workflowaction->action);
        }

        if(dao::isError()) return false;

        $isDefault       = in_array($oldAction->action, $this->config->workflowaction->defaultActions);
        $uniqueCondition = !empty($this->uniqueCondition) ? $this->uniqueCondition : "id!='$id' AND module='$action->module'";

        $this->dao->update(TABLE_WORKFLOWACTION)->data($action)
            ->where('id')->eq($id)
            ->autoCheck()
            ->batchCheckIF($isDefault, 'name', 'notempty')
            ->batchCheckIF(!$isDefault, $this->config->workflowaction->require->edit, 'notempty')
            ->batchCheck($this->config->workflowaction->uniqueFields, 'unique', $uniqueCondition)
            ->exec();

        if(dao::isError()) return false;

        if($action->status != 'enable')
        {
            $this->dao->delete()->from(TABLE_GROUPPRIV)
                ->where('module')->eq($oldAction->module)
                ->andWhere('method')->eq($oldAction->action)
                ->exec();
        }

        if($oldAction->name != $action->name)
        {
            /* Update action's text in flowchart. */
            $flow      = $this->loadModel('workflow', 'flow')->getByModule($oldAction->module);
            $flowchart = json_decode($flow->flowchart);
            if($flowchart)
            {
                foreach($flowchart as $chartItem)
                {
                    if(empty($chartItem->code)) continue;
                    if($chartItem->code != $oldAction->action) continue;

                    $chartItem->text = $action->name;
                }
                $flowchart = helper::jsonEncode($flowchart);

                $this->dao->update(TABLE_WORKFLOW)->set('flowchart')->eq($flowchart)->where('id')->eq($flow->id)->exec();
            }
        }

        return commonModel::createChanges($oldAction, $action);
    }

    /**
     * Delete an action.
     *
     * @param  int    $id
     * @param  object $null
     * @access public
     * @return bool
     */
    public function delete($id, $null = null)
    {
        $action = $this->getByID($id);
        if(!$action) return true;

        $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq($action->module)->andWhere('action')->eq($action->action)->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('id')->eq($id)->exec();

        return !dao::isError();
    }

    /**
     * Decode properties of an action.
     *
     * @param  object $action
     * @access public
     * @return object
     */
    public function decode($action)
    {
        if(is_string($action->conditions))    $action->conditions    = json_decode($action->conditions);
        if(is_string($action->verifications)) $action->verifications = json_decode($action->verifications);
        if(is_string($action->hooks))         $action->hooks         = json_decode($action->hooks);
        if(is_string($action->linkages))      $action->linkages      = json_decode($action->linkages);

        $linkages = array();
        if(!empty($action->linkages))
        {
            foreach($action->linkages as $linkage)
            {
                $ui = (int)zget($linkage, 'ui', 0);
                $linkages[$ui][] = $linkage;
            }
        }
        $action->rawLinkages = array_values((array)$action->linkages);
        $action->linkages    = $linkages;

        /* Make sure hooks and linkages is indexed array. */
        $action->conditions = array_values((array)$action->conditions);
        $action->hooks      = array_values((array)$action->hooks);

        return $action;
    }

    /**
     * Save verification of an action.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function saveVerification($id)
    {
        $data = new stdclass();
        $data->type    = $this->post->type;
        $data->message = $this->post->message;

        $errors = array();
        if($data->type == 'data')
        {
            $fields     = array();
            $sqlVars    = array();
            $formVars   = array();
            $recordVars = array();

            foreach($this->post->verifications['field'] as $key => $field)
            {
                if(!$field) continue;

                $paramType = $this->post->verifications['paramType'][$key];
                $param     = $this->post->verifications['param'][$key];

                if(is_array($param))
                {
                    $param = array_values(array_filter($param));
                    asort($param);
                    if(is_array($param)) $param = implode(',', $param);
                }

                $verification = new stdclass();
                $verification->field           = $field;
                $verification->logicalOperator = $this->post->verifications['logicalOperator'][$key];
                $verification->operator        = $this->post->verifications['operator'][$key];
                $verification->paramType       = $paramType;

                if($paramType == 'form')
                {
                    $verification->param = $param;
                    $formVars[$field]    = $param;
                }
                elseif($paramType == 'record')
                {
                    $verification->param = $param;
                    $recordVars[$field]  = $param;
                }
                elseif(!empty($paramType) && strpos(',today,now,actor,deptManager,', ",$paramType,") !== false)
                {
                    $verification->param = $paramType;
                    $sqlVars[$field]     = $paramType;
                }
                else
                {
                    $verification->param = $param;
                }
                $fields[] = $verification;
            }

            if(!$fields)
            {
                $errors['verificationsfield'] = sprintf($this->lang->error->notempty, $this->lang->workflowverification->field);
            }
            else
            {
                $data->fields     = $fields;
                $data->sqlVars    = $sqlVars;
                $data->formVars   = $formVars;
                $data->recordVars = $recordVars;
            }
        }
        elseif($data->type == 'sql')
        {
            if(!$this->post->sql)
            {
                $errors['sql'] = sprintf($this->lang->error->notempty, $this->lang->workflowverification->sql);
            }
            else
            {
                $vars      = array();
                $varValues = array();
                foreach($this->post->varName as $key => $varName)
                {
                    if(!$varName) continue;

                    $param = $this->post->param[$key];
                    if(is_array($param)) $param = implode(',', array_values(array_filter($param)));

                    $var = new stdclass();
                    $var->varName   = $varName;
                    $var->paramType = $this->post->paramType[$key];
                    $var->param     = $param;

                    $varValues[$varName] = $var->param;
                    $vars[] = $var;
                }

                $checkResult = $this->loadModel('workflowfield', 'flow')->checkSqlAndVars($this->post->sql, $varValues);
                if($checkResult !== true) $errors['sql'] = $checkResult;

                $data->sql       = $this->post->sql;
                $data->sqlVars   = $vars;
                $data->sqlResult = $this->post->sqlResult;
            }
        }

        if(!$this->post->message) $errors['message'] = sprintf($this->lang->error->notempty, $this->lang->workflowverification->message);

        if(!empty($errors))
        {
            dao::$errors = $errors;
            return false;
        }

        $this->dao->update(TABLE_WORKFLOWACTION)
            ->set('verifications')->eq(helper::jsonEncode($data))
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();

        return !dao::isError();
    }

    /**
     * Save notice of an action.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function saveNotice($id)
    {
        $toList = ',' . trim(implode(',', $this->post->toList), ',') . ',';
        $this->dao->update(TABLE_WORKFLOWACTION)->set('toList')->eq($toList)->where('id')->eq($id)->exec();
        return !dao::isError();
    }

    /**
     * Save actions defined in flwochart of a flow.
     *
     * @param  string $module
     * @param  array  $chartItems
     * @access public
     * @return bool
     */
    public function saveActions($module, $chartItems)
    {
        $defaultActions = $this->config->workflowaction->defaultActions;

        $codes  = array();
        $errors = array();
        foreach($chartItems as $chartItem)
        {
            if($chartItem->type != 'process') continue;

            if(empty($chartItem->text)) $errors[$chartItem->id] = $this->lang->workflowaction->error->emptyName;
            if(empty($chartItem->code))
            {
                $errors[$chartItem->id] = $this->lang->workflowaction->error->emptyCode;
            }
            else
            {
                if($chartItem->code != 'create' && $chartItem->code != 'edit' && in_array($chartItem->code, $defaultActions))
                {
                    $errors[$chartItem->id] = sprintf($this->lang->workflowaction->error->builtinCode, $chartItem->code);
                    continue;
                }

                if(isset($codes[$chartItem->code])) $errors[$chartItem->id] = sprintf($this->lang->workflowaction->error->existCode, $chartItem->code);
                if(!validater::checkREG($chartItem->code, '|^[a-z]+$|')) $errors[$chartItem->id] = sprintf($this->lang->workflowaction->error->wrongCode, $this->lang->workflowaction->action);

                $codes[$chartItem->code] = $chartItem->code;
            }
        }
        if($errors)
        {
            dao::$errors = $errors;
            return false;
        }

        $actions  = $this->dao->select('action')->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->fetchPairs();
        $maxOrder = $this->dao->select('MAX(`order`) AS `order`')->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->fetch('order');

        $action = new stdclass();
        $action->module      = $module;
        $action->open        = 'normal';
        $action->createdBy   = $this->app->user->account;
        $action->createdDate = helper::now();
        foreach($chartItems as $chartItem)
        {
            if($chartItem->type != 'process') continue;

            if(isset($actions[$chartItem->code]))
            {
                $this->dao->update(TABLE_WORKFLOWACTION)->set('name')->eq(htmlspecialchars($chartItem->text))
                    ->where('module')->eq($module)
                    ->andWhere('action')->eq($chartItem->code)
                    ->exec();
            }
            else
            {
                $action->name   = htmlspecialchars($chartItem->text);
                $action->action = htmlspecialchars($chartItem->code);
                $action->order  = $maxOrder + 1;

                $this->dao->insert(TABLE_WORKFLOWACTION)->data($action)->exec();

                if(dao::isError()) return false;

                $maxOrder++;

                /* Create fields for action. */
                $this->createFields($action);
            }

            if(dao::isError()) return false;
        }

        return true;
    }

    /**
     * Get position list.
     *
     * @param  array    $blocks
     * @access public
     * @return array
     */
    public function getPositionList($blocks)
    {
        $this->app->loadLang('workflowlayout', 'flow');
        $positionList = $this->lang->workflowlayout->positionList['view'];

        if(!empty($blocks))
        {
            unset($positionList['basic']);

            foreach($blocks as $blockKey => $block)
            {
                if(!empty($block->tabs))
                {
                    foreach($block->tabs as $tabKey => $tabName)
                    {
                        $positionList["block{$blockKey}_tab{$tabKey}"] = $block->name . $this->lang->slash . $tabName;
                    }
                }
                else
                {
                    $positionList["block{$blockKey}"] = $block->name;
                }
            }
        }

        return $positionList;
    }

    /**
     * Check if the button is clickable.
     *
     * @param  object $action
     * @param  string $methodName
     * @access public
     * @return bool
     */
    public function isClickable($action, $methodName)
    {
        if($action->status != 'enable') return false;

        $actionConfig = $this->config->workflowaction;
        $methodName   = strtolower($methodName);

        if($action->virtual && $methodName != 'browsecondition') return false; // The virtual action can only set conditions.
        if($action->module == 'product' && in_array($action->method, array('requirement', 'epic')) && $methodName != 'admin') return false;

        $isClickable = (($action->buildin && $action->extensionType != 'none') || !$action->buildin);
        if($methodName == 'browsecondition') return commonModel::hasPriv('workflowcondition', 'browse') && $isClickable && (!in_array($action->method, $actionConfig->noConditionActions) || $action->virtual == '1');
        if($methodName == 'browsehook')      return commonModel::hasPriv('workflowhook',      'browse') && $isClickable && !in_array($action->method, $actionConfig->noHookActions);
        if($methodName == 'browselinkage')   return commonModel::hasPriv('workflowlinkage',   'browse') && $isClickable && !in_array($action->method, $actionConfig->noLinkageActions) && $action->open != 'none' && $action->type == 'single';
        if($methodName == 'admin')           return commonModel::hasPriv('workflowlayout',    'admin')  && $isClickable && $action->open != 'none' ;

        $isClickable = (($action->buildin && $action->extensionType == 'override') || !$action->buildin);
        if($methodName == 'setnotice')       return commonModel::hasPriv('workflowaction', 'setNotice')       && $isClickable && !in_array($action->method, $actionConfig->noNoticeActions);
        if($methodName == 'setverification') return commonModel::hasPriv('workflowaction', 'setVerification') && $isClickable && !in_array($action->method, $actionConfig->noVerificationActions) && $action->open != 'none';

        $isClickable = (($action->buildin && $action->extensionType != 'none' && $action->method != 'browse') || !$action->buildin);
        if($methodName == 'setjs')  return commonModel::hasPriv('workflowaction', 'setJS')  && $isClickable && ($action->open != 'none' && !in_array($action->method, $actionConfig->noJSActions));
        if($methodName == 'setcss') return commonModel::hasPriv('workflowaction', 'setCSS') && $isClickable && ($action->open != 'none' && !in_array($action->method, $actionConfig->noCSSActions));
        if($methodName == 'delete') return commonModel::hasPriv('workflowaction', 'delete') && $action->role == 'custom';
    }
}
