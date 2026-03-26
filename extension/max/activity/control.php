<?php
/**
 * The control file of activity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     activity
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class activity extends control
{
    /**
     * Browse activities.
     *
     * @param  string $model
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($model = '', $browseType = 'all', $param = '', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->session->set('activityList', $this->app->getURI(true));

        if(empty($model) and empty($this->session->model)) $model = 'waterfall';
        if(!empty($model)) $this->session->set('model', $model);
        $this->loadModel('auditcl')->setMenu($model);

        /* Build the search form. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL  = $this->createLink('activity', 'browse', "model={$model}&browseType=bySearch&param=myQueryID");
        $processes  = $this->activity->getProcessPairs($model);

        $this->config->activity->search['params']['process']['values'] = $processes;
        $this->activity->buildSearchForm($queryID, $actionURL);

        /* Init pager and get activities. */
        $this->app->loadClass('pager', $static = true);
        $pager      = pager::init($recTotal, $recPerPage, $pageID);
        $activities = $this->activity->getList($browseType, $queryID, $orderBy, $pager, array_keys($processes));

        $this->view->title      = $this->lang->activity->common . $this->lang->hyphen . $this->lang->activity->browse;
        $this->view->activities = $activities;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->model      = $model;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->processes  = $this->activity->getProcessPairs();

        $this->display();
    }

    /**
     * Create an activity.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $this->loadModel('auditcl')->setMenu($this->session->model);
        if($_POST)
        {
            $activityID = $this->activity->create();

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('activity', $activityID, 'created');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse', "model={$this->session->model}");
            return $this->send($response);
        }

        $this->view->title      = $this->lang->activity->common . $this->lang->hyphen . $this->lang->activity->create;
        $this->view->position[] = $this->lang->activity->create;

        $this->view->users     = $this->loadModel('user')->getPairs('noclosed');
        $this->view->processes = $this->activity->getProcessPairs($this->session->model);

        $this->display();
    }

    /**
     * Batch create activities.
     *
     * @param  int $processID
     * @access public
     * @return void
     */
    public function batchCreate($processID = 0)
    {
        $this->loadModel('auditcl')->setMenu($this->session->model);
        if($_POST)
        {
            $this->activity->batchCreate();

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse', "model={$this->session->model}");

            return $this->send($response);
        }

        $this->view->title      = $this->lang->activity->common . $this->lang->hyphen . $this->lang->activity->batchCreate;
        $this->view->position[] = $this->lang->activity->batchCreate;
        $this->view->processID  = $processID;
        $this->view->processes  = $this->activity->getProcessPairs($this->session->model);
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');

        $this->display();
    }

    /**
     * View an activity.
     *
     * @param  int    $activityID
     * @access public
     * @return void
     */
    public function view($activityID = 0)
    {
        $activity = $this->activity->getById($activityID);
        $this->loadModel('auditcl')->setMenu($this->session->model);

        $this->view->title      = $this->lang->activity->common . $this->lang->hyphen . $this->lang->activity->view;
        $this->view->position[] = $this->lang->activity->view;

        $this->view->activity  = $activity;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions   = $this->loadModel('action')->getList('activity', $activity->id);
        $this->view->processes = $this->activity->getProcessPairs();

        $this->display();
    }

    /**
     * Edit an activity.
     *
     * @param  int    $activityID
     * @access public
     * @return void
     */
    public function edit($activityID = 0)
    {
        $activity = $this->activity->getById($activityID);
        $this->loadModel('auditcl')->setMenu($this->session->model);

        if($_POST)
        {
            $changes = $this->activity->update($activityID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('activity', $activityID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = isonlybody() ? $this->createLink('process', 'activityList', "processID=$activity->process") : $this->createLink('activity', 'view', "id=$activity->id");
            return $this->send($response);
        }

        $this->view->title      = $this->lang->activity->common . $this->lang->hyphen . $this->lang->activity->edit;
        $this->view->position[] = $this->lang->activity->edit;

        $this->view->activity = $activity;
        $this->view->process  = $this->activity->getProcessPairs($this->session->model);

        $this->display();
    }

    /**
     * Delete an activity.
     *
     * @param  int    $activityID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($activityID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->activity->confirmDelete, inlink('delete', "activtityID=$activityID&cofirm=yes")));
        }
        else
        {
            $this->activity->delete(TABLE_ACTIVITY, $activityID);
            if(isonlybody())
            {
                return print(js::reload('parent'));
            }
            else
            {
                return print(js::locate($this->session->activityList, 'parent'));
            }
        }
    }

    /**
     * Assign to user of activity.
     *
     * @param  int    $activity
     * @access public
     * @return void
     */
    public function assignTo($activityID = 0)
    {
        if($_POST)
        {
            $changes = $this->activity->assign($activityID);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action');
            if(!empty($changes))
            {
                $actionID = $this->action->create('activity', $activityID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('activity', 'browse', "model={$this->session->model}"), 'parent'));
        }

        $this->view->title      = $this->lang->activity->common . $this->lang->hyphen . $this->lang->activity->assignedTo;
        $this->view->position[] = $this->lang->activity->assignedTo;

        $this->view->activity = $this->activity->getById($activityID);
        $this->view->users    = $this->loadModel('user')->getPairs();

        $this->display();
    }

    /**
     * Output List.
     *
     * @param  int    $activityID
     * @access public
     * @return void
     */
    public function outputList($activityID = 0)
    {
        $this->view->outputList = $this->activity->getOutputPairs($activityID);
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
        $idList  = explode(',', trim($this->post->activity, ','));
        $orderBy = $this->post->orderBy;
        foreach($idList as $id => $value)
        {
            if($value == 'undefined') unset($idList[$id]);
        }

        if(strpos($orderBy, 'order') === false) return false;

        $data = $this->dao->select('id, `order`')->from(TABLE_ACTIVITY)->where('id')->in($idList)->orderBy($orderBy)->fetchPairs('order', 'id');
        foreach($data as $order => $id)
        {
            $newID = array_shift($idList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_ACTIVITY)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }
}
