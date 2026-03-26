<?php
/**
 * The control file of auditcl module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     activity
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class auditcl extends control
{
    /**
     * Browse auditcls.
     *
     * @param  int    $processID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($processID = 0, $browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('auditclList', $uri);

        $browseType = strtolower($browseType);

        if($browseType != 'bysearch' and $browseType != $this->session->model) $this->session->set('model', $browseType);

        $this->auditcl->setMenu($this->session->model);

        /* Build the search form. */
        $method    = $this->session->model == 'waterfall' ? 'browse' : $this->session->model . 'browse';
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('auditcl', $method, "processID=$processID&browseType=bysearch&queryID=myQueryID");
        $this->auditcl->buildSearchForm($queryID, $actionURL);

        /* Get the activities and outputs under each process. */
        $processObject = array('activity' => array(0), 'zoutput' => array(0));
        $processList   = $this->auditcl->getProcessList(false, $processID, '', 'order_desc');

        $processModelList = $this->auditcl->getProcessList(false, '', $this->session->model, 'order_desc');
        foreach($processList as $id => $name)
        {
            $activity = $this->auditcl->getObjectByID($id, 'activity');
            foreach($activity as $activityID => $activityTitle)
            {
                $processObject['activity'][$activityID] = $id;
                $zoutput = $this->auditcl->getObjectByID($activityID, 'zoutput');

                foreach($zoutput as $outputID => $output) $processObject['zoutput'][$outputID] = $id;
            }
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Get activitys and outputs. */
        $activityKeys = array_keys($processObject['activity']);
        $outputKeys   = array_keys($processObject['zoutput']);
        $auditcls     = $this->auditcl->getList($browseType, $param, $orderBy, $pager, $activityKeys, $outputKeys);

        /* Get the number of activities and outputs corresponding to the process. */
        $processCount = array();
        foreach($processList as $id => $process) $processCount[$id] = array('count' => 0, 'activity' => 0, 'zoutput' => 0);
        foreach($auditcls as $id => $auditcl)
        {
            $auditcl->process = isset($processObject[$auditcl->objectType][$auditcl->objectID]) ? $processObject[$auditcl->objectType][$auditcl->objectID] : 0;
            if($processID && $auditcl->process != $processID)
            {
                unset($auditcls[$id]);
                continue;
            }
            $processModelList[$auditcl->process] = $processList[$auditcl->process];

            $processCount[$auditcl->process]['count']              +=1;
            $processCount[$auditcl->process][$auditcl->objectType] +=1;
        }

        #/* Sort by process. */
        $sortObjects = array('process' => array(), 'objectType' => array(), 'objectID' => array());
        foreach($auditcls as $auditcl)
        {
            $sortObjects['process'][]    = $auditcl->process;
            $sortObjects['objectType'][] = $auditcl->objectType;
            $sortObjects['objectID'][]   = $auditcl->objectID;
        }
        array_multisort($sortObjects['process'], $sortObjects['objectType'], $sortObjects['objectID'], SORT_ASC, $auditcls);

        $this->view->title      = $this->lang->auditcl->common . $this->lang->hyphen . $this->lang->auditcl->browse;
        $this->view->position[] = $this->lang->auditcl->browse;

        $auditcls = $this->auditcl->sortByProcess($auditcls, $processList, 'process');
        $this->view->auditcls     = $auditcls;
        $this->view->browseType   = $browseType;
        $this->view->model        = $this->session->model;
        $this->view->activityList = $this->auditcl->getObjectList('activity');
        $this->view->zoutputList  = $this->auditcl->getObjectList('zoutput');
        $this->view->processList  = $processModelList;
        $this->view->process      = $this->loadModel('process')->getByID($processID);
        $this->view->param        = $param;
        $this->view->processCount = $processCount;
        $this->view->processID    = $processID;
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Scurm browse.
     *
     * @param  int    $processID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function scrumBrowse($processID = 0, $browseType = 'scrum', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        echo $this->fetch('auditcl', 'browse', "processID=$processID&browseType=$browseType&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Agile plus browse.
     *
     * @param  int    $processID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function agilePlusBrowse($processID = 0, $browseType = 'agile', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        echo $this->fetch('auditcl', 'browse', "processID=$processID&browseType=$browseType&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Waterfall plus browse.
     *
     * @param  int    $processID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function waterfallPlusBrowse($processID = 0, $browseType = 'waterfallplus', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        echo $this->fetch('auditcl', 'browse', "processID=$processID&browseType=$browseType&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Edit a auditcl.
     *
     * @param  int    $auditclID
     * @access public
     * @return void
     */
    public function edit($auditclID)
    {
        if($_POST)
        {
            $changes = $this->auditcl->update($auditclID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action');
            if(!empty($changes))
            {
                $actionID = $this->action->create('auditcl', $auditclID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['locate'] = $this->session->auditclList;
            return $this->send($response);
        }

        $auditcl    = $this->auditcl->getById($auditclID);
        $activities = $this->auditcl->getObjectList('activity', $auditcl->process, 0, true);
        $zoutputs   = arrayUnion(array('' => ''), $this->auditcl->getObjectList('zoutput', $auditcl->process, 0, true));

        $this->auditcl->setMenu($auditcl->model);

        $this->view->title      = $this->lang->auditcl->common . $this->lang->hyphen . $this->lang->auditcl->edit;
        $this->view->position[] = $this->lang->auditcl->edit;

        $this->view->auditcl    = $auditcl;
        $this->view->processes  = $this->auditcl->getProcessList(true);
        $this->view->activities = $activities;
        $this->view->zoutputs   = $zoutputs;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->display();
    }

    /**
     * Delete a auditcl.
     *
     * @param  int    $auditclID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($auditclID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->auditcl->confirmDelete, inlink('delete', "auditclID=$auditclID&confirm=yes"), ''));
        }
        else
        {
            $this->auditcl->delete(TABLE_AUDITCL, $auditclID);

            die(js::locate($this->session->auditclList, 'parent'));
        }
    }

    /**
     * View a auditcl.
     *
     * @param  int    $auditclID
     * @access public
     * @return void
     */
    public function view($auditclID)
    {
        $this->loadModel('action');

        $this->view->title      = $this->lang->auditcl->common . $this->lang->hyphen . $this->lang->auditcl->view;
        $this->view->position[] = $this->lang->auditcl->view;

        $this->view->auditcl = $this->auditcl->getById($auditclID);
        $this->view->actions = $this->action->getList('auditcl', $auditclID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Batch Create .
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        if($this->session->model) $this->auditcl->setMenu($this->session->model);

        if($_POST)
        {
            $this->auditcl->batchCreate();
            $locate = $this->session->auditclList;
            if(dao::isError()) return $this->send(array('result' => 'failed', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->auditcl->addSuccess, 'locate' => $locate));
        }

        /* Get the activities and outputs under each process. */
        $option        = array();
        $allOption     = array();
        $processObject = array();
        $processList   = $this->auditcl->getProcessList('', '', $this->session->model);

        foreach($processList as $id => $name)
        {
            $option[$id]['name']     = $name;
            $option[$id]['activity'] = $this->auditcl->getObjectByID($id, 'activity');
            $option[$id]['zoutput']  = array();

            foreach($option[$id]['activity'] as $activityID => $activityTitle)
            {
                $processObject['activity'][$activityID] = $id;
                $zoutput = $this->auditcl->getObjectByID($activityID, 'zoutput');

                foreach($zoutput as $outputID => $title)
                {
                    $option[$id]['zoutput'][$outputID]   = $activityTitle . '/' . $title;
                    $processObject['zoutput'][$outputID] = $id;
                }
            }
        }

        /* Judge the corresponding process of audit. */
        $auditcls = $this->auditcl->getList($this->session->model);
        foreach($auditcls as $auditcl)
        {
            if($auditcl->objectType == 'zoutout') $auditcl->objectType = 'zoutput';
            if(isset($processObject[$auditcl->objectType][$auditcl->objectID]))
            {
                $auditcl->process = $processObject[$auditcl->objectType][$auditcl->objectID];
                $option[$auditcl->process][$auditcl->objectType][] = $auditcl;
            }
        }

        $this->view->title      = $this->lang->auditcl->batchCreate;
        $this->view->position[] = $this->lang->auditcl->batchCreate;
        $this->view->model      = $this->session->model;
        $this->view->option     = $option;

        $this->display();
    }

    /**
     * Batch edit auditcls.
     *
     * @access public
     * @return void
     */
    public function batchEdit($model = '')
    {
        $this->auditcl->setMenu($model);

        if($this->post->title)
        {
            $this->auditcl->batchUpdate();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            die(js::locate($this->session->auditclList, 'parent'));
        }

         /* Get the activities and outputs under each process. */
         $processObject = array('activity' => array(0), 'zoutput' => array(0));
         $processList   = $this->auditcl->getProcessList(false, 0, '', 'order_desc');

         $processModelList = $this->auditcl->getProcessList(false, '', $this->session->model, 'order_desc');
         foreach($processList as $id => $name)
         {
             $activity = $this->auditcl->getObjectByID($id, 'activity');
             foreach($activity as $activityID => $activityTitle)
             {
                 $processObject['activity'][$activityID] = $id;
                 $zoutput = $this->auditcl->getObjectByID($activityID, 'zoutput');

                 foreach($zoutput as $outputID => $output) $processObject['zoutput'][$outputID] = $id;
             }
         }

        $auditclIDList = $this->post->auditclIDList ? array_unique($this->post->auditclIDList) : die(js::locate($this->session->auditclList, 'parent'));
        $auditcls = $this->dao->select('*')->from(TABLE_AUDITCL)->where('id')->in($auditclIDList)->fetchAll('id');
        foreach($auditcls as $auditcl) $auditcl->process = isset($processObject[$auditcl->objectType][$auditcl->objectID]) ? $processObject[$auditcl->objectType][$auditcl->objectID] : 0;
        /* Assign. */
        $this->view->title      = $this->lang->auditcl->batchEdit;
        $this->view->position[] = $this->lang->auditcl->batchEdit;
        $this->view->auditcls     = $auditcls;
        $this->view->processList  = $processList;
        $this->view->activityList = $this->auditcl->getObjectList('activity');
        $this->view->zoutputList  = $this->auditcl->getObjectList('zoutput');
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->display();
    }

    /**
     * ajax get all review.
     *
     * @param  string $objectType
     * @param  int    $processID
     * @param  int    $activityID
     * @param  string $objectID
     * @access public
     * @return void
     */
    public function ajaxGetAll($objectType = 'activity', $processID = 0, $activityID = 0, $objectID = '')
    {
        $object = $this->auditcl->getObjectList($objectType, $processID, $activityID, true);
        if($objectType == 'zoutput') $object = arrayUnion(array('' => ''), $object);

        $onchange   = $objectType == 'activity' ? "onchange=changeActivity(this.value)" : '';
        $objectHtml = html::select($objectType, $object, $objectID, "class='form-control chosen' $onchange");

        die($objectHtml);
    }
}
