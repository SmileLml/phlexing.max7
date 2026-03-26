<?php
/**
 * The control file of zoutput module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yong Lei <leiyong@easycorp.ltd>
 * @package     zoutput
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class zoutput extends control
{
    /**
     * Get output list data.
     *
     * @param  string $model
     * @param  string $browseType bySearch|all
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($model = '', $browseType = 'all', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        if(empty($model) and empty($this->session->model)) $model = 'waterfall';
        if(!empty($model)) $this->session->set('model', $model);
        $this->loadModel('auditcl')->setMenu($model);

        /* Load pager */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $processes  = $this->loadModel('activity')->getProcessPairs($model);
        $activity   = $this->loadModel('activity')->getParams(array_keys($processes));

        $this->config->zoutput->search['params']['activity']['values'] = arrayUnion(array('' => ''), $activity);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('zoutput', 'browse', "model={$model}&browseType=bysearch&queryID=myQueryID");
        $this->zoutput->buildSearchForm($actionURL, $queryID);

        $this->view->title      = $this->lang->zoutput->common . $this->lang->hyphen . $this->lang->zoutput->browse;
        $this->view->position[] = $this->lang->zoutput->browse;

        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->activity   = $activity;
        $this->view->model      = $model;
        $this->view->outputs    = $this->zoutput->getList($browseType, $queryID, $orderBy, $pager, array_keys($activity));
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');

        $this->display();
    }

    /**
     *  View an output.
     *
     * @param  int    $outputID
     * @access public
     * @return void
     */
    public function view($outputID)
    {
        $this->commonAction($outputID, 'zoutput');
        $output = $this->zoutput->getById($outputID);

        $this->view->title      = $this->lang->zoutput->common . $this->lang->hyphen . $output->name;
        $this->view->position[] = $this->lang->zoutput->common;
        $this->view->position[] = $this->lang->zoutput->basicInfo;

        $this->view->users    = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->output   = $output;
        $this->view->activity = $this->loadModel('activity')->getParams();
        $this->display();
    }

    /**
     * Common actions of zoutput module.
     *
     * @param  int    $outputID
     * @param  int    $object
     * @access public
     * @return void
     */
    public function commonAction($outputID, $object)
    {
        $this->view->actions = $this->loadModel('action')->getList($object, $outputID);
        $this->loadModel('auditcl')->setMenu($this->session->model);
    }

    /**
     * Create an output.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $this->loadModel('auditcl')->setMenu($this->session->model);
        if($_POST)
        {
            $outputID = $this->zoutput->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('zoutput', $outputID, 'Opened');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', "model={$this->session->model}&browseType=all")));
        }
        $processes  = $this->loadModel('activity')->getProcessPairs($this->session->model);

        $this->view->title      = $this->lang->zoutput->common . $this->lang->hyphen . $this->lang->zoutput->create;
        $this->view->position[] = $this->lang->zoutput->common;
        $this->view->position[] = $this->lang->zoutput->create;

        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->activity = $this->loadModel('activity')->getParams(array_keys($processes));

        $this->display();
    }

    /**
     * Batch create outputs.
     *
     * @param  int $activityID
     * @access public
     * @return void
     */
    public function batchCreate($activityID = 0)
    {
        $this->loadModel('auditcl')->setMenu($this->session->model);
        if($_POST)
        {
            $outputs = $this->zoutput->batchCreate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($outputs as $outputID) $this->loadModel('action')->create('zoutput', $outputID, 'Opened');

            die(js::locate($this->inLink('browse', "model={$this->session->model}"), 'parent'));
        }
        $processes  = $this->loadModel('activity')->getProcessPairs($this->session->model);

        $this->view->title      = $this->lang->zoutput->common . $this->lang->hyphen . $this->lang->zoutput->batchCreate;
        $this->view->position[] = $this->lang->zoutput->common;
        $this->view->position[] = $this->lang->zoutput->batchCreate;
        $this->view->activityID = $activityID;

        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->activity = $this->loadModel('activity')->getParams(array_keys($processes));

        $this->display();
    }

    /**
     * Edit an output.
     *
     * @param  int    $outputID
     * @access public
     * @return void
     */
    public function edit($outputID)
    {
        $zoutput = $this->zoutput->getById($outputID);
        $this->loadModel('auditcl')->setMenu($this->session->model);

        if($_POST)
        {
            $changes = $this->zoutput->update($outputID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('zoutput', $outputID, 'Edited');
            $this->action->logHistory($actionID, $changes);

            $locate = isonlybody() ? $this->createLink('activity', 'outputList', "activityID=$zoutput->activity") : inLink('browse', "model={$this->session->model}&browseType=all");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->view->title      = $this->lang->zoutput->common . $this->lang->hyphen . $this->lang->zoutput->edit;
        $this->view->position[] = $this->lang->zoutput->common;
        $this->view->position[] = $this->lang->zoutput->edit;

        $processes  = $this->loadModel('activity')->getProcessPairs($this->session->model);
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->zoutput  = $zoutput;
        $this->view->activity = $this->loadModel('activity')->getParams(array_keys($processes));

        $this->display();
    }

    /**
     * Batch edit outputs.
     *
     * @access public
     * @return void
     */
    public function batchEdit()
    {
        $outputID = $this->post->zoutput;
        $this->loadModel('auditcl')->setMenu($this->session->model);
        if(!empty($outputID))
        {
            $this->view->outputs = $this->dao->select('*')->from(TABLE_ZOUTPUT)->where('id')->in($outputID)->andWhere('deleted')->eq('0')->orderBy('order_desc')->fetchAll('id');
        }
        elseif($_POST)
        {
            $changes = $this->zoutput->batchEdit();
            $this->loadModel('action');

            foreach($changes as $outputID => $change)
            {
                $actionID = $this->action->create('zoutput', $outputID, 'Edited');
                $this->action->logHistory($actionID, $change);
            }
            die(js::locate($this->inLink('browse', "model={$this->session->model}"), 'parent'));
        }

        $this->view->title      = $this->lang->zoutput->common . $this->lang->hyphen . $this->lang->zoutput->batchEdit;
        $this->view->position[] = $this->lang->zoutput->common;
        $this->view->position[] = $this->lang->zoutput->batchEdit;

        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->activity = $this->loadModel('activity')->getParams();

        $this->display();
    }

    /**
     * Update output sort.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        $outputs = explode(',', trim($this->post->outputs, ','));
        $orderBy = $this->post->orderBy;

        foreach($outputs as $id => $value)
        {
            if($value == 'undefined') unset($outputs[$id]);
        }

        if(strpos($orderBy, 'order') === false) return false;
        $data = $this->dao->select('id, `order`')->from(TABLE_ZOUTPUT)->where('id')->in($outputs)->orderBy($orderBy)->fetchPairs('order', 'id');

        foreach($data as $order => $id)
        {
            $newID = array_shift($outputs);
            if($id == $newID) continue;
            $this->dao->update(TABLE_ZOUTPUT)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }

    /**
     * Delete an output.
     *
     * @param  int    $outputID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($outputID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->zoutput->confirmDelete, inLink('delete', "outputID=$outputID&confirm=yes")));
        }
        else
        {
            $this->zoutput->delete(TABLE_ZOUTPUT, $outputID);
            $this->dao->update(TABLE_PROGRAMOUTPUT)->set('deleted')->eq(1)->where('output')->eq($outputID)->exec();
            die(js::locate(inLink('browse', "model={$this->session->model}"), 'parent'));
        }
    }
}
