<?php
/**
 * The control file of workflowreport module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Dongdong Jia <jiadongdong@easycorp.ltd>
 * @package     workflowreport
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowreport extends control
{
    /**
     * Set reports of a flow.
     *
     * @param  string $module
     * @param  int    $id
     * @access public
     * @return void
     */
    public function browse($module, $id = 0)
    {
        $this->view->title      = $this->lang->workflowreport->browse;
        $this->view->reports    = $this->workflowreport->getList($module);
        $this->view->flow       = $this->loadModel('workflow', 'flow')->getByModule($module);
        $this->view->module     = $module;
        $this->view->id         = $id;
        $this->view->editorMode = 'advanced';
        $this->display();
    }

    /**
     * Create a report of a flow.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function create($module)
    {
        if($_POST)
        {
            $reportID = $this->workflowreport->create($module);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('workflowreport', $reportID, 'created');

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "module={$module}&id=$reportID")));
        }

        $flow = $this->loadModel('workflow', 'flow')->getByModule($module);
        list($dimension, $controlPairs) = $this->workflowreport->getDimension($flow);

        $this->view->title        = $this->lang->workflowreport->create;
        $this->view->dimension    = $dimension;
        $this->view->controlPairs = $controlPairs;
        $this->view->fields       = $this->workflowreport->getFields($flow);
        $this->view->module       = $module;
        $this->display();
    }

    /**
     * Edit a report of a flow.
     *
     * @param  int      $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        if($_POST)
        {
            $changes = $this->workflowreport->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Record operate log. */
			if(!empty($changes))
			{
				$action = $this->loadModel('action')->create('workflowreport', $id, 'edited');
				$this->action->logHistory($action, $changes);
			}

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "module={$this->post->module}&id=$id")));
        }

        $report = $this->workflowreport->getByID($id);
        $flow   = $this->loadModel('workflow', 'flow')->getByModule($report->module);
        list($dimension, $controlPairs) = $this->workflowreport->getDimension($flow);

        $this->view->title        = $this->lang->workflowreport->edit;
        $this->view->report       = $report;
        $this->view->dimension    = $dimension;
        $this->view->controlPairs = $controlPairs;
        $this->view->fields       = $this->workflowreport->getFields($flow);
        $this->display();
    }

    /**
     * Delete a report of a flow.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->workflowreport->delete($id);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * Sort reports of a flow.
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
                $this->dao->update(TABLE_WORKFLOWREPORT)->set('order')->eq($order)->where('id')->eq($id)->exec();
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success'));
        }
    }

    /**
     * Preview the workflow report page by ajax.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function ajaxPreview($id)
    {
        $report = $this->workflowreport->getByID($id);
        if(!$report) die();

        $this->view->report = $report;
        $this->display();
    }
}
