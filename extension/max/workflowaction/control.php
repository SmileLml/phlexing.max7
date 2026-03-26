<?php
/**
 * The control file of workflowaction module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowaction
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowaction extends control
{
    /**
     * Browse actions of a module.
     *
     * @param  string $module
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function browse($module, $orderBy = 'status_desc,order_asc')
    {
        $this->view->title      = $this->lang->workflowaction->browse;
        $this->view->flow       = $this->loadModel('workflow', 'flow')->getByModule($module);;
        $this->view->actions    = $this->workflowaction->getList($module, $orderBy);
        $this->view->orderBy    = $orderBy;
        $this->view->editorMode = 'advanced';

        $this->display();
    }

    /**
     * Create an action.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function create($module)
    {
        if($_POST)
        {
            $id = $this->workflowaction->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('workflowaction', $id, 'created');

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('workflowaction', 'browse', "module=$module")));
        }

        $this->view->title = $this->lang->workflowaction->create;
        $this->view->flow  = $this->loadModel('workflow', 'flow')->getByModule($module);
        $this->display();
    }

    /**
     * Edit an action.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        if($_POST)
        {
            $changes = $this->workflowaction->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $action = $this->loadModel('action')->create('workflowaction', $id , 'edited');
                $this->action->logHistory($action, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $action = $this->workflowaction->getByID($id);

        $this->view->title  = $action->name . $this->lang->minus . $this->lang->workflowaction->edit;
        $this->view->flow   = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->action = $action;
        $this->display();
    }

    /**
     * View an action.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id)
    {
        $action = $this->workflowaction->getByID($id);

        $this->view->title  = $this->lang->workflowaction->view;
        $this->view->flow   = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->users  = $this->loadModel('user')->getDeptPairs();
        $this->view->action = $action;
        $this->display();
    }

    /**
     * Delete an action.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->workflowaction->delete($id);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * Sort actions of a flow.
     *
     * @access public
     * @return void
     */
    public function sort()
    {
        if($_POST)
        {
            foreach($_POST as $id => $order)
            {
                $this->dao->update(TABLE_WORKFLOWACTION)->set('order')->eq($order)->where('id')->eq($id)->exec();
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }
    }

    /**
     * Set verification of an action.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function setVerification($id)
    {
        if($_POST)
        {
            if($_POST['type'] == 'no')
            {
                $this->dao->update(TABLE_WORKFLOWACTION)->set('verifications')->eq('')->where('id')->eq($id)->exec();

                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
            }
            $this->workflowaction->saveVerification($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $action      = $this->workflowaction->getByID($id);
        $datasources = $this->loadModel('workflowdatasource', 'flow')->getPairs('noempty');
        if($action->action == 'create') unset($datasources['record']);

        $this->view->title        = $action->name . $this->lang->minus . $this->lang->workflowaction->setVerification;
        $this->view->fields       = $this->loadModel('workflowfield', 'flow')->getFieldPairs($action->module);
        $this->view->flow         = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->datasources  = $datasources;
        $this->view->action       = $action;
        $this->view->modalWidth   = 800;
        $this->display();
    }

    /**
     * Set notice of an action.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function setNotice($id)
    {
        if($_POST)
        {
            $this->workflowaction->saveNotice($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $action = $this->workflowaction->getByID($id);

        $this->view->title  = $action->name . $this->lang->minus . $this->lang->workflowaction->setNotice;
        $this->view->users  = $this->workflowaction->getUsers2Notice($action->module);
        $this->view->action = $action;
        $this->display();
    }

    /**
     * Set js.
     *
     * @param  int    $id
     * @param  string $type     flow | action
     * @access public
     * @return void
     */
    public function setJS($id, $type = 'action')
    {
        die($this->fetch('workflow', 'setJS', "id=$id&type=$type"));
    }

    /**
     * Set css.
     *
     * @param  int    $id
     * @param  string $type     flow | action
     * @access public
     * @return void
     */
    public function setCSS($id, $type = 'action')
    {
        die($this->fetch('workflow', 'setCSS', "id=$id&type=$type"));
    }

    /**
     * Preview the action page by ajax.
     *
     * @param  string $module
     * @param  string $action
     * @access public
     * @return void
     */
    public function ajaxPreview($module, $action, $recTotal = 3, $recPerPage = 10, $pageID = 1)
    {
        $layoutFields = $this->dao->select('field')->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq($module)->andWhere('action')->eq($action)->fetchPairs();
        if(empty($layoutFields)) die();

        $this->loadModel('flow');

        $flow   = $this->loadModel('workflow', 'flow')->getByModule($module);
        $action = $this->workflowaction->getByModuleAndAction($module, $action);
        $fields = $this->workflowaction->getFields($module, $action->action);

        if($action->action != 'browse')
        {
            $childFields  = array();
            $childModules = $this->workflow->getList('browse', 'table', '', $module);
            foreach($childModules as $childModule)
            {
                $key = 'sub_' . $childModule->module;

                if(isset($fields[$key]) && $fields[$key]->show)
                {
                    $childFields[$key] = $this->workflowaction->getFields($childModule->module, $action->action);
                }
            }
            $this->view->childFields = $childFields;
        }

        if($action->action == 'browse')
        {
            $viewFile = 'previewbrowse';

            $this->app->loadClass('pager', true);
            $pager = new pager($recTotal, $recPerPage, $pageID);

            $this->view->labels       = $this->loadModel('workflowlabel', 'flow')->getPairs($module);
            $this->view->batchActions = $this->flow->buildBatchActions($module);
            $this->view->pager        = $pager;
        }
        else if($action->action == 'view')
        {
            $viewFile = 'previewdetail';

            $this->app->loadLang('action');

            $blocks = json_decode($action->blocks);
            $this->view->processBlocks = $this->loadModel('flow', 'sys')->processBlocks($blocks, $fields);
        }
        else if($action->type == 'batch')
        {
            $viewFile = 'previewbatchoperate';

            if($action->batchMode == 'different') $fields = $this->flow->addDitto($fields);

            $this->view->notEmptyRule = $this->loadModel('workflowrule', 'flow')->getByTypeAndRule('system', 'notempty');
        }
        else
        {
            $viewFile = 'previewoperate';

            $editor = '';
            foreach($fields as $key => $field)
            {
                if($field->control == 'richtext' && $field->show) $editor .= ',' . $field->field;
            }
            $editor = trim($editor, ',');

            if($editor)
            {
                $this->config->workflowaction->editor = new stdclass();
                $this->config->workflowaction->editor->ajaxpreview = array('id' => $editor, 'tools' => 'simple');
            }

            $this->view->editor = $editor;
            $this->view->users  = array('');
        }

        $this->view->flow   = $flow;
        $this->view->action = $action;
        $this->view->fields = $fields;
        $this->display('workflowaction', $viewFile);
    }
}
