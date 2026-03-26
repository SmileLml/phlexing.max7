<?php
/**
 * The control file of workflowlinkage module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowlinkage
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowlinkage extends control
{
    /**
     * Browse linkage list.
     *
     * @param  int    $action
     * @param  int    $ui
     * @access public
     * @return void
     */
    public function browse($action, $ui = 0)
    {
        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);

        $this->view->title      = $this->lang->workflowlinkage->browse;
        $this->view->flow       = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->fields     = $this->loadModel('workflowaction', 'flow')->getFields($action->module, $action->action, true, null, $ui);
        $this->view->actions    = $this->workflowaction->getList($action->module);
        $this->view->action     = $action;
        $this->view->ui         = $ui;
        $this->view->modalWidth = 1100;
        $this->display();
    }

    /**
     * Create a linkage.
     *
     * @param  int    $action
     * @param  int    $ui
     * @access public
     * @return void
     */
    public function create($action, $ui = 0)
    {
        if($_POST)
        {
            $this->workflowlinkage->create($action, $ui);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "action=$action&ui=$ui")));
        }

        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);

        $this->view->title  = $action->name . $this->lang->minus . $this->lang->workflowlinkage->create ;
        $this->view->flow   = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->fields = arrayUnion(array('' => ''), $this->loadModel('workflowlayout')->getFields($action->module, $action->action, $ui));
        $this->view->action = $action;
        $this->view->ui     = $ui;
        $this->display();
    }

    /**
     * Edit a linkage.
     *
     * @param  int    $action
     * @param  int    $key
     * @param  int    $ui
     * @access public
     * @return void
     */
    public function edit($action, $key, $ui = 0)
    {
        if($_POST)
        {
            $this->workflowlinkage->update($action, $key, $ui);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "action=$action&ui=$ui")));
        }

        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);

        $this->view->title  = $this->lang->workflowlinkage->edit;
        $this->view->flow   = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->fields = arrayUnion(array('' => ''), $this->loadModel('workflowlayout')->getFields($action->module, $action->action, $ui));
        $this->view->action = $action;
        $this->view->key    = $key;
        $this->view->ui     = $ui;
        $this->display();
    }

    /**
     * Delete a linkage.
     *
     * @param  int    $action
     * @param  int    $key
     * @param  int    $ui
     * @access public
     * @return void
     */
    public function delete($action, $key, $ui = 0)
    {
        $this->workflowlinkage->delete($action, $key, $ui);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'locate' => inlink('browse', "action=$action&ui=$ui")));
    }
}
