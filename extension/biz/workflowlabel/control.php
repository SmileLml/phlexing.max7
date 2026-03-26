<?php
/**
 * The control file of workflowlabel module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowlabel
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowlabel extends control
{
    /**
     * Browse labels of a flow.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function browse($module, $recTotal = 3, $recPerPage = 10, $pageID = 1)
    {
        $this->preview($module, $recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->workflowlabel->browse;
        $this->view->labels     = $this->workflowlabel->getList($module);
        $this->view->flow       = $this->loadModel('workflow', 'flow')->getByModule($module);
        $this->view->editorMode = 'advanced';
        $this->view->moduleMenu = false;
        $this->display();
    }

    /**
     * Create a label of a flow.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function create($module)
    {
        if($_POST)
        {
            $labelID = $this->workflowlabel->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('workflowlabel', $labelID, 'created');

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title  = $this->lang->workflowlabel->create;
        $this->view->fields = $this->loadModel('workflowfield', 'flow')->getFieldPairs($module);
        $this->view->module = $module;
        $this->display();
    }

    /**
     * Edit a label of a flow.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        if($_POST)
        {
            $changes = $this->workflowlabel->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('workflowlabel', $id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $label = $this->workflowlabel->getByID($id);

        $this->view->title  = $this->lang->workflowlabel->edit;
        $this->view->fields = $this->loadModel('workflowfield', 'flow')->getFieldPairs($label->module);
        $this->view->label  = $label;
        $this->display();
    }

    /**
     * Delete a label of a flow.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->dao->delete()->from(TABLE_WORKFLOWLABEL)->where('id')->eq($id)->exec();
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * Sort labels of a flow.
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
                $this->dao->update(TABLE_WORKFLOWLABEL)->set('order')->eq($order)->where('id')->eq($id)->exec();
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }
    }

    /**
     * Preview the browse page of a flow.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function preview($module, $recTotal = 3, $recPerPage = 10, $pageID = 1)
    {
        $action = 'browse';

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->flow         = $this->loadModel('workflow', 'flow')->getByModule($module);
        $this->view->action       = $this->loadModel('workflowaction', 'flow')->getByModuleAndAction($module, $action);
        $this->view->fields       = $this->workflowaction->getFields($module, $action, false);
        $this->view->batchActions = $this->loadModel('flow')->buildBatchActions($module);
        $this->view->pager        = $pager;
    }

    /**
     * Remove feature tips for current user.
     *
     * @access public
     * @return string
     */
    public function removeFeatureTips()
    {
        $featureTipsClosers  = zget($this->config->workflowlabel, 'featureTipsClosers', ',');
        $featureTipsClosers .= "{$this->app->user->account},";
        $this->loadModel('setting')->setItem('system.flow.workflowlabel.featureTipsClosers', $featureTipsClosers);
        die('success');
    }
}
