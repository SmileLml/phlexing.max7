<?php
/**
 * The control file of process module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     process
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class process extends control
{

    /**
     * Get process list data.
     *
     * @param  string $browseType bySearch|all
     * @param  int    param
     * @param  string orderBy
     * @param  int    recTotal
     * @param  int    recPerPage
     * @param  int    pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        $model = $browseType == 'bysearch' ? $this->session->model : $browseType;

        $this->loadModel('auditcl')->setMenu($model);
        if($browseType != 'bysearch') $this->session->set('model', $browseType);

        /* Load pager */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $classify = $model == 'waterfall' ? 'classify' : $model . 'Classify';
        $classify = $this->lang->process->$classify;
        $this->config->process->search['params']['type']['values'] = $classify;

        /* Build the search form. */
        $queryID      = ($browseType == 'bysearch') ? (int)$param : 0;
        $browseMethod = $browseType == 'scrum' ? 'scrumbrowse' : 'browse';
        $actionURL    = $this->createLink('process', $browseMethod, "browseType=bysearch&queryID=myQueryID");
        $this->process->buildSearchForm($actionURL, $queryID);

        $this->view->title      = $this->lang->process->common . $this->lang->hyphen . $this->lang->process->browse;
        $this->view->position[] = $this->lang->process->browse;

        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->browseType  = $browseType;
        $this->view->model       = $model;
        $this->view->processList = $this->process->getList($browseType, $queryID, $orderBy, $pager);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->classify    = $classify;

        $this->display();
    }

    /**
     * Scrum browse.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function scrumBrowse($browseType = 'scrum', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('process', 'browse', "browseType=$browseType&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Agile plus browse.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function agilePlusBrowse($browseType = 'agileplus', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('process', 'browse', "browseType=$browseType&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Waterfall plus browse.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function waterfallPlusBrowse($browseType = 'waterfallplus', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('process', 'browse', "browseType=$browseType&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Create a process.
     *
     * @access public
     * @return void
     */
    public function create($model = '')
    {
        $this->loadModel('auditcl')->setMenu($model);

        if($_POST)
        {
            $processID = $this->process->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('process', $processID, 'Opened');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', "browseType=$model")));
        }

        $this->view->title      = $this->lang->process->common . $this->lang->hyphen . $this->lang->process->create;
        $this->view->position[] = $this->lang->process->common;
        $this->view->position[] = $this->lang->process->create;
        $this->view->model      = $model;
        $this->view->classify   = $model == 'waterfall' ? 'classify' : $model . 'Classify';

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

   /**
    * Batch create processes.
    *
    * @access public
    * @return void
    */
    public function batchCreate($model = '')
    {
        $this->loadModel('auditcl')->setMenu($model);

        if($_POST)
        {
            $results = $this->process->batchCreate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', "browseType=$model")));
        }

        $this->view->title      = $this->lang->process->common . $this->lang->hyphen . $this->lang->process->batchCreate;
        $this->view->position[] = $this->lang->process->common;
        $this->view->position[] = $this->lang->process->batchCreate;

        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->model    = $model;
        $this->view->classify = $model == 'waterfall' ? 'classify' : $model . 'Classify';

        $this->display();
    }

    /**
     * Delete a process.
     *
     * @param  int    $processID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($processID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->process->confirmDelete, inLink('delete', "processID=$processID&confirm=yes")));
        }
        else
        {
            $this->process->deleteProess($processID);
            $model = $this->session->model;
            $model = isset($model) ? $model : 'all';
            die(js::locate(inLink('browse', "browseType=$model"), 'parent'));
        }
    }

    /**
     * Edit a process.
     *
     * @param  int    $processID
     * @access public
     * @return void
     */
    public function edit($processID)
    {
        $process = $this->process->getByID($processID);
        $this->loadModel('auditcl')->setMenu($process->model);

        if($_POST)
        {
            $changes = $this->process->update($processID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('process', $processID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->title      = $this->lang->process->common . $this->lang->hyphen . $this->lang->process->edit;
        $this->view->position[] = $this->lang->process->common;
        $this->view->position[] = $this->lang->process->edit;

        $this->view->model    = $this->session->model;
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->process  = $process;
        $this->view->classify = $process->model == 'waterfall' ? 'classify' : $process->model . 'Classify';

        $this->display();
    }

    /**
     *  View a process.
     *
     * @param  int    $processID
     * @access public
     * @return void
     */
    public function view($processID)
    {
        $this->commonAction($processID, 'process');
        $process = $this->process->getByID($processID);
        $this->loadModel('auditcl')->setMenu($process->model);

        $this->view->title      = $this->lang->process->common . $this->lang->hyphen . $process->name;
        $this->view->position[] = $this->lang->process->common;
        $this->view->position[] = $this->lang->process->basicInfo;

        $this->view->users    = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->process  = $process;
        $this->view->model    = $this->session->model;
        $this->view->classify = $process->model == 'waterfall' ? 'classify' : $process->model . 'Classify';
        $this->display();
    }

    /**
     * Common actions of process module.
     *
     * @param  int    $processID
     * @param  int    $object
     * @access public
     * @return void
     */
    public function commonAction($processID, $object)
    {
        $this->view->actions = $this->loadModel('action')->getList($object, $processID);
    }

    /**
     * Get activities of process module.
     *
     * @param  int    $processID
     * @access public
     * @return void
     */
    public function activityList($processID)
    {
        $this->view->activities = $this->process->activityList($processID);
        $this->display();
    }


    /**
     * Update order.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        $idList  = explode(',', trim($this->post->process, ','));
        $orderBy = $this->post->orderBy;
        foreach($idList as $id => $value)
        {
            if($value == 'undefined') unset($idList[$id]);
        }

        if(strpos($orderBy, 'order') === false) return false;

        $data = $this->dao->select('id, `order`')->from(TABLE_PROCESS)->where('id')->in($idList)->orderBy($orderBy)->fetchPairs('order', 'id');
        foreach($data as $order => $id)
        {
            $newID = array_shift($idList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_PROCESS)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }
}
