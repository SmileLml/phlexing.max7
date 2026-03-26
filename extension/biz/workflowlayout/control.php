<?php
/**
 * The control file of workflowlayout module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowlayout
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowlayout extends control
{
    /**
     * Admin layout of an aciton.
     *
     * @param  string $module
     * @param  string $action
     * @param  string $mode
     * @param  int    $ui
     * @access public
     * @return void
     */
    public function admin($module, $action, $mode = 'view', $ui = 0)
    {
        if($this->server->request_method == 'POST')
        {
            $this->workflowlayout->save($module, $action, $ui);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('admin', "module=$module&action=$action&mode=view&ui=$ui")));
        }

        $this->app->loadConfig('workflowaction');
        $lowerAction   = strtolower($action);
        $relatedModule = isset($this->config->workflowaction->buildin->relatedModules[$module][$lowerAction]) ? $this->config->workflowaction->buildin->relatedModules[$module][$lowerAction] : $module;

        /* Check custom fields. */
        $currentFlow  = $this->loadModel('workflow')->getByModule($relatedModule);
        $customFields = $this->loadModel('workflowfield')->getCustomFields($relatedModule);
        if(!$customFields)
        {
            $this->view->title             = $this->lang->workflowlayout->admin;
            $this->view->module            = $module;
            $this->view->emptyCustomFields = true;
            die($this->display());
        }

        /* Process actions can admin layout and get fields created by action. */
        $actionFields = array();
        $actions      = $this->loadModel('workflowaction')->getList($relatedModule);
        foreach($actions as $key => $actionObject)
        {
            $actionBy   = $actionObject->action . 'By';
            $actionDate = $actionObject->action . 'Date';

            $actionFields[$actionBy]   = $actionBy;
            $actionFields[$actionDate] = $actionDate;
        }

        /* Remove built-in fields when extend a built-in action. */
        $fields = $this->workflowaction->getFields($module, $action, true, null, $ui);
        if($mode == 'edit')
        {
            $hasShowFields = false;
            array_map(function($field) use(&$hasShowFields) { if($field->show == '1') $hasShowFields = true; }, $fields);
            if(!$hasShowFields && $ui) $fields = $this->workflowaction->getFields($module, $action);
            if(!empty($currentFlow->belong) && ($action == 'create' || $action == 'batchcreate') && empty($fields[$currentFlow->belong]->show))
            {
                $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');
                $fields[$currentFlow->belong]->show        = '1';
                $fields[$currentFlow->belong]->layoutRules = $notEmptyRule->id;
            }
            if($lowerAction == 'approvalsubmit') unset($fields['reviewers'], $fields['reviewOpinion'], $fields['reviewResult'], $fields['reviewStatus'], $fields['approval']);
        }

        $action = $this->workflowaction->getByModuleAndAction($module, $action);
        if($action)
        {
            if($module == 'project') unset($fields['type'], $fields['project']);
            if($action->buildin && $action->extensionType == 'extend')
            {
                foreach($fields as $key => $field)
                {
                    if($field->buildin == '1') unset($fields[$key]);
                    if($action->type == 'batch' && in_array($field->control, array('richtext', 'file'))) unset($fields[$key]);
                }
            }
        }

        /* Set default position. */
        $defaultPositions = array();
        $defaultFields    = array_keys($this->config->workflowfield->default->fields);
        foreach($fields as $key => $field)
        {
            $defaultPositions[$field->field] = 'info';
            if(in_array($field->field, $defaultFields)) $defaultPositions[$field->field] = 'basic';
            if(in_array($field->field, $actionFields))  $defaultPositions[$field->field] = 'basic';
        }

        $flowPairs   = array();
        $subTables   = array();
        $prevModules = array();
        $flows       = $this->workflow->getList('browse', $type = '');  // Must assign a empty string to the parameter $type.
        $relations   = $this->loadModel('workflowrelation')->getPrevList($module, 'prev');

        /* Process related datas. */
        foreach($flows as $flow)
        {
            /* Process flow pairs. */
            $flowPairs[$flow->module] = $flow->name;

            if(!$currentFlow->buildin && $flow->type == 'flow' && $flow->status == 'normal')
            {
                /* Process prev modules. */
                if(isset($relations[$flow->module]))
                {
                    $relation = $relations[$flow->module];
                    if(strpos(",$relation->actions,", ',many2one,') === false) $prevModules[$flow->module] = $this->workflowrelation->getLayoutFields($flow->module, $module, $action->action);
                }
            }

            /* Process sub tables. */
            if($flow->type == 'table' && $flow->parent == $module) $subTables['sub_' . $flow->module] = $this->workflowaction->getFields($flow->module, $action->action, true, null, $ui);
        }

        $actionMethod = $action->method;
        if($action->module == 'product' && in_array($action->method, array('requirement', 'epic'))) $actionMethod = 'browse';
        if($action->module == 'caselib' && $action->action == 'editCase') $actionMethod = 'edit';
        $positionList = zget($this->lang->workflowlayout->positionList, $actionMethod, array());
        if($action->method == 'view')
        {
            $blocks = json_decode($action->blocks);
            $positionList = $this->loadModel('workflowaction', 'flow')->getPositionList($blocks);
        }

        if($action->method == 'view')
        {
            $sortedFields = array();
            foreach($positionList as $positionKey => $positionName)
            {
                foreach($fields as $key => $field)
                {
                    if($field->position == $positionKey) $sortedFields[$key] = $field;
                }
            }

            foreach($fields as $key => $field)
            {
                if(!isset($positionList[$field->position])) $sortedFields[$key] = $field;
            }
        }

        $this->view->title            = $this->lang->workflowlayout->admin;
        $this->view->fields           = $action->method == 'view' ? $sortedFields : $fields;
        $this->view->action           = $action;
        $this->view->rules            = $this->loadModel('workflowrule', 'flow')->getPairs();
        $this->view->flow             = $currentFlow;
        $this->view->flowPairs        = $flowPairs;
        $this->view->subTables        = $subTables;
        $this->view->prevModules      = $prevModules;
        $this->view->defaultPositions = $defaultPositions;
        $this->view->positionList     = $positionList;
        $this->view->mode             = $mode;
        $this->view->ui               = $ui;
        $this->view->uiPairs          = $this->workflowlayout->getUIPairs($module, $action);
        $this->view->modalWidth       = 1100;
        $this->display();
    }

    /**
     * Set block in right part.
     *
     * @access public
     * @return void
     */
    public function block($module)
    {
        $action = $this->loadModel('workflowaction')->getByModuleAndAction($module, 'view');

        if($_POST)
        {
            $blocks = $action->blocks ? json_decode($action->blocks, true) : array();
            $this->workflowlayout->saveBlocks($module, $blocks);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title  = $this->lang->workflowlayout->block;
        $this->view->action = $action;
        $this->display();
    }

    /**
     * Add new UI.
     *
     * @param  string $module
     * @param  string $action
     * @access public
     * @return void
     */
    public function addUI($module, $action)
    {
        if($_POST)
        {
            $ui = $this->workflowlayout->createUI($module, $action);
            $locateURL = $this->createLink('workflowlayout', 'admin', "module=$module&action=$action&view=edit&ui=$ui");
            return $this->workflowlayoutZen->afterResponseForUI($locateURL);
        }

        $this->workflowlayoutZen->assignVarsForUI($module, $action);
        $this->view->title = $this->lang->workflowlayout->addUI;
        $this->display();
    }

    /**
     * Edit UI
     *
     * @param  int    $uiID
     * @access public
     * @return void
     */
    public function editUI($uiID)
    {
        $ui = $this->workflowlayout->getUIByID($uiID);
        if($_POST)
        {
            $this->workflowlayout->updateUI($uiID);
            $locateURL = $this->createLink('workflowlayout', 'admin', "module={$ui->module}&action={$ui->action}&view=view&ui={$uiID}");
            return $this->workflowlayoutZen->afterResponseForUI($locateURL);
        }

        $this->workflowlayoutZen->assignVarsForUI($ui->module, $ui->action, $ui->id);
        $this->view->title = $this->lang->workflowlayout->editUI;
        $this->view->ui    = $ui;
        $this->display();
    }

    /**
     * Delete UI
     *
     * @param  int    $uiID
     * @access public
     * @return void
     */
    public function deleteUI($uiID)
    {
        $ui = $this->workflowlayout->getUIByID($uiID);
        $this->dao->delete()->from(TABLE_WORKFLOWUI)->where('id')->eq($uiID)->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)->where('ui')->eq($uiID)->exec();
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('admin', "module=$ui->module&action=$ui->action&mode=view")));
    }
}
