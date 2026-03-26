<?php
/**
 * The control file of workflowcondition module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowcondition
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowcondition extends control
{
    /**
     * Browse conditions of an action.
     *
     * @param  int    $action
     * @access public
     * @return void
     */
    public function browse($action)
    {
        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);

        $this->view->title      = $this->lang->workflowcondition->browse;
        $this->view->fields     = $this->loadModel('workflowfield', 'flow')->getFieldPairs($action->module);
        $this->view->flow       = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->actions    = $this->workflowaction->getList($action->module);
        $this->view->action     = $action;
        $this->view->modalWidth = 1100;
        $this->display();
    }

    /**
     * Create a condition.
     *
     * @param  int    $action
     * @access public
     * @return void
     */
    public function create($action)
    {
        if($_POST)
        {
            $this->workflowcondition->create($action);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "action=$action")));
        }

        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);

        $this->view->title  = $action->name . $this->lang->minus . $this->lang->workflowcondition->create;
        $this->view->flow   = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->fields = $this->loadModel('workflowfield', 'flow')->getFieldPairs($action->module);
        $this->view->action = $action;
        $this->display();
    }

    /**
     * Edit a condition.
     *
     * @param  int    $action
     * @param  int    $key
     * @access public
     * @return void
     */
    public function edit($action, $key)
    {
        if($_POST)
        {
            $this->workflowcondition->update($action, $key);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "action=$action")));
        }

        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);

        $this->view->title  = $this->lang->workflowcondition->edit;
        $this->view->flow   = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->fields = $this->loadModel('workflowfield', 'flow')->getFieldPairs($action->module);
        $this->view->action = $action;
        $this->view->key    = $key;
        $this->display();
    }

    /**
     * Delete a condition.
     *
     * @param  int    $action
     * @param  int    $key
     * @access public
     * @return void
     */
    public function delete($action, $key)
    {
        $this->workflowcondition->delete($action, $key);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * Record user know tips.
     *
     * @access public
     * @return void
     */
    public function know()
    {
        $this->loadModel('setting')->setItem("{$this->app->user->account}.flow.workflowcondition.knowTips", 1);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }
}
