<?php
/**
 * The control file of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     opportunity
 * @version     $Id: control.php 5107 2021-05-26 09:06:12Z tsj $
 * @link        https://www.zentao.net
 */
class opportunity extends control
{
    public function commonAction($projectID, $from = 'project')
    {
        $this->loadModel('project');
        if($from == 'project')
        {
            $this->project->setMenu($projectID);
            $this->view->projectID = $projectID;
        }
        if($from == 'execution')
        {
            $this->loadModel('execution')->setMenu($projectID);
            $this->view->executionID = $projectID;
            $this->executions = $this->execution->getPairs(0, 'all', 'nocode');
            if(!$this->executions and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('execution', 'create'));
        }
    }

    /**
     * Browse opportunities.
     *
     * @param  int    $projectID
     * @param  string $from
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $from = 'project', $browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->commonAction($projectID, $from);
        $uri = $this->app->getURI(true);
        $this->session->set('opportunityList', $uri, $this->app->tab);
        $objectID = $projectID;
        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $objectID  = $execution->project;
        }
        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('opportunity', 'browse', "projectID=$projectID&from=$from&browseType=bysearch&queryID=myQueryID");
        $this->opportunity->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $opportunities = $this->opportunity->getList($projectID, $browseType, $param, $orderBy, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'opportunity');

        $this->view->title         = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->browse;
        $this->view->opportunities = $opportunities;
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;
        $this->view->from          = $from;
        $this->view->orderBy       = $orderBy;
        $this->view->projectID     = $projectID;
        $this->view->object        = $this->loadModel($from)->getByID($objectID);
        $this->view->pager         = $pager;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->members       = $this->project->getTeamMemberPairs($objectID);
        $this->view->approvers     = $this->loadModel('assetlib')->getApproveUsers('opportunity');
        $this->view->libs          = $this->assetlib->getPairs('opportunity');

        $this->display();
    }

    /**
     * Create an opportunity.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function create($projectID = 0, $from = 'project')
    {
        $this->commonAction($projectID, $from);

        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
            $this->view->executionID = $execution->id;
        }

        if($_POST)
        {
            $opportunityID = $this->opportunity->create($projectID);
            if(!$opportunityID) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('opportunity', $opportunityID, 'Opened');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->opportunityList, 'id' => $opportunityID));
        }

        $this->view->title      = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->create;
        $this->view->users      = $this->project->getTeamMemberPairs($projectID);
        $this->view->executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'leaf');
        $this->view->project    = $this->project->fetchByID($projectID);

        $this->display();
    }

    /**
     * Batch create opportunities.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchCreate($projectID = 0, $from = 'project')
    {
        $this->commonAction($projectID, $from);

        if($this->app->tab == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
            $this->view->executionID = $execution->id;
        }

        if($_POST)
        {
            $opportunityIDList = $this->opportunity->batchCreate($projectID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Return opportunity id list when call the API. */
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $opportunityIDList));

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->opportunityList));
        }

        $this->view->title      = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->batchCreate;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'leaf');
        $this->view->project    = $this->loadModel('project')->fetchByID($projectID);

        $this->display();
    }

    /**
     * Import from library.
     *
     * @param  int    $objectID
     * @param  string $from
     * @param  int    $libID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importFromLib($objectID, $from = 'project', $libID = 0, $orderBy = 'id_desc', $browseType = 'all', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->commonAction($objectID, $from);

        $browseType = strtolower($browseType);
        $queryID    = (int)$queryID;
        $projectID  = $objectID;

        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($objectID);
            $projectID = $execution->project;
        }
        $executionID = isset($execution) ? $execution->id : 0;

        if($_POST)
        {
            $this->opportunity->importFromLib($projectID, $executionID);
            if(dao::isError()) return $this->sendError(array('message' => dao::getError()));
            $this->sendSuccess(array('load' => true));
        }

        $libraries = $this->loadModel('assetlib')->getPairs('opportunity');
        if(empty($libraries)) $this->sendError($this->lang->assetlib->noLibrary, $this->session->opportunityList);
        if(empty($libID) or !isset($libraries[$libID])) $libID = key($libraries);

        /* Build the search form. */
        $actionURL = $this->createLink('opportunity', 'importFromLib', "projectID=$objectID&from=$from&libID=$libID&orderBy=$orderBy&browseType=bysearch&queryID=myQueryID");
        $this->config->opportunity->search['module'] = 'importOpportunity';
        $this->config->opportunity->search['fields']['lib'] = $this->lang->assetlib->lib;
        $this->config->opportunity->search['params']['lib'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $libID => $libraries[$libID], 'all' => $this->lang->opportunity->allLib));
        $needUnsetFields = array('status','identifiedDate','plannedClosedDate','actualClosedDate','resolvedBy','activatedBy','assignedTo','canceledBy','hangupedBy','lastCheckedBy');
        foreach($needUnsetFields as $fieldName) unset($this->config->opportunity->search['fields'][$fieldName]);
        $this->opportunity->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $opportunities = $this->opportunity->getNotImported($libraries, $libID, $projectID, $orderBy, $browseType, $queryID);
        $pager         = pager::init(count($opportunities), $recPerPage, $pageID);
        $opportunities = array_chunk($opportunities, $pager->recPerPage);

        $this->view->title = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->importFromLib;

        $this->view->libraries     = $libraries;
        $this->view->libID         = $libID;
        $this->view->projectID     = $objectID;
        $this->view->opportunities = empty($opportunities) ? $opportunities : $opportunities[$pageID - 1];
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->from          = $from;
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->queryID       = $queryID;

        $this->display();
    }

    /**
     * Edit an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function edit($opportunityID, $from = 'project')
    {
        $opportunity = $this->opportunity->getByID($opportunityID);
        if($from == 'project') $this->commonAction($opportunity->project);
        if($from == 'execution') $this->commonAction($opportunity->execution, $from);

        if($_POST)
        {
            $changes = $this->opportunity->update($opportunityID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            if(!empty($changes))
            {
                $actionID = $this->action->create('opportunity', $opportunityID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "opportunityID=$opportunityID&from=$from"), 'id' => $opportunityID));
        }

        $this->view->title       = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->edit;
        $this->view->opportunity = $opportunity;
        $this->view->from        = $from;
        $this->view->actions     = $this->loadModel('action')->getList('opportunity', $opportunityID);
        $this->view->users       = arrayUnion(array('' => ''), $this->project->getTeamMemberPairs($opportunity->project));
        $this->view->executions  = $this->loadModel('execution')->getPairs($opportunity->project, 'all', 'leaf');
        $this->view->project     = $this->loadModel('project')->fetchByID((int)$opportunity->project);

        $this->display();
    }

    /**
     * Batch edit opportunities.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchEdit($projectID, $from = 'project')
    {
        $this->commonAction($projectID, $from);

         if($this->post->name)
         {
             $allChanges = $this->opportunity->batchUpdate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

             if($allChanges)
             {
                 $this->loadModel('action');
                 foreach($allChanges as $opportunityID => $changes)
                 {
                     if(empty($changes)) continue;

                     $actionID = $this->action->create('opportunity', $opportunityID, 'Edited');
                     $this->action->logHistory($actionID, $changes);
                 }
             }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->opportunityList));
         }

         $opportunityIDList = $this->post->opportunityIDList ? $this->post->opportunityIDList : $this->locate($this->session->opportunityList);
         $opportunityIDList = array_unique($opportunityIDList);

         $this->view->title         = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->batchEdit;
         $this->view->projectID     = $projectID;
         $this->view->from          = $from;
         $this->view->opportunities = $this->opportunity->getByList($opportunityIDList);

         $this->display();
    }

    /**
     * View an opportunity.
     *
     * @param  int    $opportunityID
     * @param  string $from
     * @access public
     * @return void
     */
    public function view($opportunityID, $from = 'project')
    {
        $opportunityID = (int)$opportunityID;
        $opportunity   = $this->opportunity->getById($opportunityID);
        if(empty($opportunity)) die(js::error($this->lang->notFound) . js::locate($this->createLink('project', 'browse'), '$.apps.open'));

        if($from == 'project') $this->commonAction($opportunity->project);
        if($from == 'execution') $this->commonAction($opportunity->execution, $from);

        $this->view->title       = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->view;
        $this->view->opportunity = $opportunity;
        $this->view->from        = $from;
        $this->view->actions     = $this->loadModel('action')->getList('opportunity', $opportunityID);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->approvers   = $this->loadModel('assetlib')->getApproveUsers('opportunity');
        $this->view->libs        = $this->assetlib->getPairs('opportunity');
        $this->view->project     = $this->loadModel('project')->fetchByID($opportunity->project);
        $this->view->execution   = $this->loadModel('execution')->fetchByID($opportunity->execution);
        $this->view->preAndNext  = $this->loadModel('common')->getPreAndNextObject('opportunity', $opportunityID);

        $this->display();
    }

    /**
     * Delete an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function delete($opportunityID)
    {
        $this->opportunity->delete(TABLE_OPPORTUNITY, $opportunityID);

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
    }

    /**
     * Update assign of opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function assignTo($opportunityID)
    {
        $opportunity = $this->opportunity->getByID($opportunityID);

        if($_POST)
        {
            $changes = $this->opportunity->assign($opportunityID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('opportunity', $opportunityID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            if(isInModal() || isonlybody()) return $this->sendSuccess(array('load' => true, 'closeModal' => true));
            return $this->sendSuccess(array('load' => $this->createLink('opportunity', 'view', "opportunityID={$opportunityID}")));
        }

        $this->view->title       = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->assignedTo;
        $this->view->opportunity = $opportunity;
        $this->view->users       = $this->loadModel('project')->getTeamMemberPairs($opportunity->project);

        $this->display();
    }

    /**
     * Track an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function track($opportunityID)
    {
        $opportunity = $this->opportunity->getByID($opportunityID);

        if($_POST)
        {
            $changes = array();
            if($this->post->isChange) $changes = $this->opportunity->track($opportunityID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            if(!empty($changes) or $_POST['comment'])
            {
                $actionID = $this->action->create('opportunity', $opportunityID, 'Tracked', $_POST['comment']);
                $this->action->logHistory($actionID, $changes);
            }


            if(isInModal() || isonlybody()) return $this->sendSuccess(array('load' => true, 'closeModal' => true));
            return $this->sendSuccess(array('load' => $this->createLink('opportunity', 'view', "opportunityID={$opportunityID}")));
        }

        $this->view->title       = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->track;
        $this->view->opportunity = $opportunity;
        $this->view->users       = $this->loadModel('project')->getTeamMemberPairs($opportunity->project);
        $this->display();
    }

    /**
     * Close an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function close($opportunityID)
    {
        $opportunity = $this->opportunity->getByID($opportunityID);

        if($_POST)
        {
            $changes = $this->opportunity->close($opportunityID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('opportunity', $opportunityID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->title       = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->close;
        $this->view->users       = $this->loadModel('project')->getTeamMemberPairs($opportunity->project);
        $this->view->opportunity = $opportunity;
        $this->display();
    }

    /**
     * Cancel an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function cancel($opportunityID)
    {
        $opportunity = $this->opportunity->getByID($opportunityID);

        if($_POST)
        {
            $changes = $this->opportunity->cancel($opportunityID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('opportunity', $opportunityID, 'Canceled', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->title       = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->cancel;
        $this->view->opportunity = $opportunity;
        $this->display();
    }

    /**
     * Hangup an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function hangup($opportunityID)
    {
        $opportunity = $this->opportunity->getByID($opportunityID);

        if($_POST)
        {
            $changes = $this->opportunity->hangup($opportunityID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('opportunity', $opportunityID, 'Hangup', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->title       = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->hangup;
        $this->view->users       = $this->loadModel('project')->getTeamMemberPairs($opportunity->project);
        $this->view->opportunity = $opportunity;
        $this->display();
    }

    /**
     * Activate an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function activate($opportunityID)
    {
        $opportunity = $this->opportunity->getByID($opportunityID);

        if($_POST)
        {
            $changes = $this->opportunity->activate($opportunityID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('opportunity', $opportunityID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->title       = $this->lang->opportunity->common . $this->lang->hyphen . $this->lang->opportunity->activate;
        $this->view->users       = $this->loadModel('project')->getTeamMemberPairs($opportunity->project);
        $this->view->opportunity = $opportunity;
        $this->display();
    }

    /**
     * Import opportunity to opportunity lib.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function importToLib($opportunityID)
    {
        $this->opportunity->importToLib($opportunityID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'load' => true));
    }

    /**
     * Batch import to lib.
     *
     * @access public
     * @return void
     */
    public function batchImportToLib()
    {
        $opportunityIDList = $this->post->opportunityIDList;
        $this->opportunity->importToLib($opportunityIDList);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'locate' => 'reload'));
    }

    /**
     * Batch assign to opportunities.
     *
     * @param  int    $projectID
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function batchAssignTo($projectID, $assignedTo = '')
    {
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $opportunityIDList = $this->post->opportunityIDList ? $this->post->opportunityIDList : $this->locate($this->session->opportunityList);
            $opportunityIDList = array_unique($opportunityIDList);

            unset($_POST['opportunityIDList']);
            $_POST['assignedTo'] = $assignedTo;

            $opportunities = $this->opportunity->getByList($opportunityIDList);
            foreach($opportunities as $opportunityID => $opportunity)
            {
                if($opportunity->status != 'closed') $changes = $this->opportunity->assign($opportunityID);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $actionID = $this->action->create('opportunity', $opportunityID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }
    }

    /**
     * Batch close opportunities.
     *
     * @access public
     * @return void
     */
    public function batchClose()
    {
        if($this->post->opportunityIDList)
        {
            $opportunityIDList = $this->post->opportunityIDList;
            if($opportunityIDList) $opportunityIDList = array_unique($opportunityIDList);

            unset($_POST['opportunityIDList']);

            $this->loadModel('action');
            $opportunities = $this->opportunity->getByList($opportunityIDList);
            foreach($opportunities as $opportunityID => $opportunity)
            {
                if($opportunity->status == 'closed' or $opportunity->status == 'canceled') continue;

                $changes = $this->opportunity->close($opportunityID);
                if($changes)
                {
                    $actionID = $this->action->create('opportunity', $opportunityID, 'Closed', '');
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Batch cancel opportunities.
     *
     * @param  string $cancelReason
     * @access public
     * @return void
     */
    public function batchCancel($cancelReason)
    {
        $opportunityIDList = $this->post->opportunityIDList ? $this->post->opportunityIDList : $this->locate($this->session->opportunityList);
        $opportunityIDList = array_unique($opportunityIDList);

        $allChanges = $this->opportunity->batchCancel($opportunityIDList, $cancelReason);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action');
        foreach($allChanges as $opportunityID => $changes)
        {
            $actionID = $this->action->create('opportunity', $opportunityID, 'Canceled');
            $this->action->logHistory($actionID, $changes);
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Batch hangup opportunities.
     *
     * @access public
     * @return void
     */
    public function batchHangup()
    {
        if($this->post->opportunityIDList)
        {
            $opportunityIDList = $this->post->opportunityIDList;
            if($opportunityIDList) $opportunityIDList = array_unique($opportunityIDList);

            unset($_POST['opportunityIDList']);
            unset($_POST['assignedTo']);

            $this->loadModel('action');

            $opportunities = $this->opportunity->getByList($opportunityIDList);
            foreach($opportunities as $opportunityID => $opportunity)
            {
                if($opportunity->status != 'active') continue;

                $changes = $this->opportunity->hangup($opportunityID);
                if($changes)
                {
                    $actionID = $this->action->create('opportunity', $opportunityID, 'Hangup', '');
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Batch activate opportunities.
     *
     * @access public
     * @return void
     */
    public function batchActivate()
    {
        if($this->post->opportunityIDList)
        {
            $opportunityIDList = $this->post->opportunityIDList;
            if($opportunityIDList) $opportunityIDList = array_unique($opportunityIDList);

            unset($_POST['opportunityIDList']);
            unset($_POST['assignedTo']);

            $this->loadModel('action');
            $opportunities = $this->opportunity->getByList($opportunityIDList);
            foreach($opportunities as $opportunityID => $opportunity)
            {
                if($opportunity->status == 'active') continue;

                $changes = $this->opportunity->activate($opportunityID);
                if($changes)
                {
                    $actionID = $this->action->create('opportunity', $opportunityID, 'Activated', '');
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * AJAX: return opportunities of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id
     * @param  string $status
     * @access public
     * @return void
     */
    public function ajaxGetUserOpportunities($userID = '', $id = '', $status = 'all')
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $items = array();
        $opportunities = $this->opportunity->getUserOpportunityPairs($account, 0, $status);
        foreach($opportunities as $opportunityID => $opportunityTitle) $items[] = array('text' => $opportunityTitle, 'value' => $opportunityID);
        return print(json_encode(array('name' => $id ? "opportunities[{$id}]" : 'opportunity', 'items' => $items)));
    }

    /**
     * Export opportunity.
     *
     * @param  string $objectID
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($objectID, $browseType, $orderBy)
    {
        $object = $this->loadModel('project')->fetchByID($objectID);
        if($_POST)
        {
            $this->loadModel('file');
            $opportunityLang = $this->lang->opportunity;

            /* Create field lists. */
            $sort   = common::appendOrder($orderBy);
            $fields = explode(',', $this->config->opportunity->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($opportunityLang->$fieldName) ? $opportunityLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }
            if(empty($object->multiple)) unset($fields['execution']);

            /* Get opportunities. */
            $opportunities = $this->opportunity->getExportData($sort);

            /* Get executions. */
            if(!empty($object->multiple)) $executions = $this->loadModel('execution')->getByIdList(helper::arrayColumn($opportunities, 'execution'), 'all');

            /* Get users. */
            $users = $this->loadModel('user')->getPairs('noletter|noempty');

            $data = array();
            foreach($opportunities as $opportunity)
            {
                $tmp = new stdClass();
                $tmp->id                = $opportunity->id;
                $tmp->source            = zget($this->lang->opportunity->sourceList, $opportunity->source, '');
                $tmp->name              = $opportunity->name;
                $tmp->execution         = isset($executions[$opportunity->execution]) ? $executions[$opportunity->execution]->name . "(#{$opportunity->execution})" : '';
                $tmp->type              = zget($this->lang->opportunity->typeList, $opportunity->type, '');
                $tmp->strategy          = zget($this->lang->opportunity->strategyList, $opportunity->strategy, '');
                $tmp->status            = zget($this->lang->opportunity->statusList, $opportunity->status, '');
                $tmp->impact            = $opportunity->impact;
                $tmp->chance            = $opportunity->chance;
                $tmp->ratio             = $opportunity->ratio;
                $tmp->ratio             = $opportunity->ratio;
                $tmp->pri               = zget($this->lang->opportunity->priList, $opportunity->pri, '');
                $tmp->identifiedDate    = $opportunity->identifiedDate;
                $tmp->assignedTo        = isset($users[$opportunity->assignedTo]) ? $users[$opportunity->assignedTo] . "(#{$opportunity->assignedTo})" : '';
                $tmp->assignedDate      = $opportunity->assignedDate;
                $tmp->plannedClosedDate = $opportunity->plannedClosedDate;
                $tmp->actualClosedDate  = $opportunity->actualClosedDate;
                $tmp->resolvedBy        = isset($users[$opportunity->resolvedBy]) ? $users[$opportunity->resolvedBy] . "(#{$opportunity->resolvedBy})" : '';
                $tmp->resolvedDate      = $opportunity->resolvedDate;

                if($this->post->fileType == 'csv')
                {
                    $opportunity->desc = htmlspecialchars_decode($opportunity->desc);
                    $opportunity->desc = str_replace("<br />", "\n", $opportunity->desc);
                    $tmp->desc = str_replace('"', '""', $opportunity->desc);

                    $opportunity->prevention = htmlspecialchars_decode($opportunity->prevention);
                    $opportunity->prevention = str_replace("<br />", "\n", $opportunity->prevention);
                    $tmp->prevention = str_replace('"', '""', $opportunity->prevention);

                    $opportunity->resolution = htmlspecialchars_decode($opportunity->resolution);
                    $opportunity->resolution = str_replace("<br />", "\n", $opportunity->resolution);
                    $tmp->resolution = str_replace('"', '""', $opportunity->resolution);
                }
                else
                {
                    $tmp->desc       = $opportunity->desc;
                    $tmp->prevention = $opportunity->prevention;
                    $tmp->resolution = $opportunity->resolution;
                }

                $data[] = $tmp;
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $data);
            $this->post->set('kind', 'opportunity');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $fileName = zget($object, 'name',  '') . $this->lang->dash . zget($this->lang->opportunity->featureBar['browse'], $browseType, '') . $this->lang->opportunity->common;

        $this->view->fileName = $fileName;
        $this->display();
    }
}
