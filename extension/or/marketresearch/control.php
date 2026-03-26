<?php
/**
 * The control file of marketresearch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hu Fangzhou <hufangzhou@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
class marketresearch extends control
{
    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        global $lang;
        $this->loadModel('execution');
        $this->loadModel('market');
        $this->loadModel('task');

        $lang->execution->common = $this->lang->execution->stage;
        $lang->projectCommon     = $this->lang->marketresearch->common;
        $lang->executionCommon   = $this->lang->execution->stage;
    }

    /**
     * Create marketresearch.
     *
     * @param  int    $marketID
     * @param  string $extras
     * @access public
     * @return void
     */
    public function create($marketID = 0, $extras = '')
    {
        $extras = str_replace(array(',', ' ', '*'), array('&', '', '-'), $extras);
        parse_str($extras, $params);

        $this->loadModel('market')->setMenu($marketID);
        $this->app->loadLang('project');
        if($_POST)
        {
            $marketresearchID = $this->marketresearch->create();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($marketresearchID) $this->loadModel('action')->create('marketresearch', $marketresearchID, 'created');

            $locateLink = $this->session->marketresearchList ? $this->session->marketresearchList : $this->inlink('all', "marketID=$marketID");
            if($marketID && $marketID != $_POST['market']) $locateLink = $this->inlink('browse', "marketID=$_POST[market]");

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locateLink));
        }

        $this->view->title      = $this->lang->marketresearch->create;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->marketID   = $marketID;
        $this->view->marketList = $this->loadModel('market')->getPairs();
        $this->view->newMarket  = !empty($params['newMarket']) ? true : false;
        $this->view->loadUrl    = $this->createLink('marketresearch', 'create', "marketID=$marketID&extras=newMarket={newMarket}");
        $this->display();
    }

    /**
     * Edit a market research.
     *
     * $param  int    $researchID
     * @access public
     * @return void
     */
    public function edit($researchID = 0)
    {
        $oldResearch = $this->marketresearch->getById($researchID);
        $this->loadModel('market')->setMenu($oldResearch->market);
        $this->app->loadLang('project');
        $this->app->loadConfig('execution');
        if($_POST)
        {
            $changes = $this->marketresearch->update($oldResearch);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('marketresearch', $researchID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $locateLink = $this->session->marketresearchList ? $this->session->marketresearchList : $this->inlink('stage', "researchID=$researchID");

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $locateLink;

            $this->send($response);
        }

        $this->view->title      = $this->lang->marketresearch->create;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->researchID = $researchID;
        $this->view->marketList = $this->market->getPairs();
        $this->view->research   = $oldResearch;
        $this->view->marketID   = $oldResearch->market;
        $this->display();
    }

    /**
     * View a research.
     *
     * @param  int    $researchID
     * @access public
     * @return void
     */
    public function view($researchID = 0)
    {
        $this->app->loadLang('execution');
        $this->app->loadLang('marketreport');
        $this->session->set('teamList', $this->app->getURI(true), 'marketresearch');

        $research = $this->loadModel('project')->getById($researchID);

        $this->loadModel('market')->setMenu($research->market);

        if(empty($research))
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('research', 'browse')));
        }

        /* Check exist extend fields. */
        $isExtended = false;
        if($this->config->edition != 'open')
        {
            $extend = $this->loadModel('workflowaction')->getByModuleAndAction('marketresearch', 'view');
            if(!empty($extend) and $extend->extensionType == 'extend') $isExtended = true;
        }

        $this->executeHooks($researchID);

        $userPairs = $this->loadModel('user')->getPairs('noletter');

        $this->view->title       = $this->lang->overview;
        $this->view->position    = $this->lang->overview;
        $this->view->researchID  = $researchID;
        $this->view->research    = $research;
        $this->view->reports     = $this->loadModel('marketreport')->getPairsByResearch($researchID);
        $this->view->actions     = $this->loadModel('action')->getList('marketresearch', $researchID);
        $this->view->users       = $userPairs;
        $this->view->teamMembers = $this->project->getTeamMembers($researchID);
        $this->view->workhour    = $this->project->getWorkhour($researchID);
        $this->view->dynamics    = $this->action->getDynamic('all', 'all', 'date_desc', 30, 'all', $researchID);
        $this->view->isExtended  = $isExtended;
        $this->view->userList    = $this->user->getListByAccounts(array_keys($userPairs), 'account');
        $this->view->marketID    = $research->market;

        $this->display();
    }

    /**
     * All market researches.
     *
     * @param  int    $marketID
     * @param  string $browseType  all|doing|closed
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function all($marketID = 0, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('datatable');
        $this->loadModel('market');

        $browseType = strtolower($browseType);
        $market     = $this->market->getByID($marketID);

        $this->market->setMenu($marketID);

        $this->session->set('marketresearchList', $this->app->getURI(true));
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Refresh stats fields of projects. */
        $this->loadModel('program')->refreshStats();

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $involved = $this->cookie->involvedResearch ? $this->cookie->involvedResearch : 0;

        $this->view->title      = $marketID ? $market->name . '-' . $this->lang->marketresearch->browse : $this->lang->marketresearch->browse;
        $this->view->researches = $this->marketresearch->getList($marketID, $browseType, $orderBy, $involved, $pager);
        $this->view->marketID   = $marketID;
        $this->view->markets    = $this->market->getPairs();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType = $browseType;
        $this->view->orderBy    = $orderBy;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Browse market's researches.
     *
     * @param  int    $marketID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($marketID = 0, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('marketresearch', 'all', "marketID=$marketID&browseType=$browseType&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Start research.
     *
     * @param  int    $researchID
     * @access public
     * @return void
     */
    public function start($researchID)
    {
        $this->loadModel('action');
        $this->loadModel('project');
        $research = $this->project->getByID($researchID);

        if(!empty($_POST))
        {
            $postData = form::data($this->config->project->form->start)
              ->add('status', 'doing')
              ->add('lastEditedBy', $this->app->user->account)
              ->add('lastEditedDate', helper::now())
              ->get();

            $changes = $this->project->start($researchID, $postData);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('marketresearch', $researchID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($researchID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => 'table'));
        }

        $this->view->title            = $this->lang->marketresearch->start;
        $this->view->position[]       = $this->lang->marketresearch->start;
        $this->view->research         = $research;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions          = $this->action->getList('marketresearch', $researchID);
        $this->view->marketresearchID = $researchID;
        $this->display();
    }

    /**
     * Close a research.
     *
     * @param  int    $researchID
     * @access public
     * @return void
     */
    public function close($researchID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->marketresearch->close($researchID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('marketresearch', $researchID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($researchID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => 'table'));
        }

        $this->view->title            = $this->lang->marketresearch->close;
        $this->view->position[]       = $this->lang->marketresearch->close;
        $this->view->research         = $this->loadModel('project')->getByID($researchID);
        $this->view->users            = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions          = $this->action->getList('marketresearch', $researchID);
        $this->view->marketresearchID = $researchID;

        $this->display();
    }

    /**
     * Activate stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function activateStage($stageID)
    {
        $this->loadStageLang();
        $stage = $this->loadModel('execution')->getById($stageID);
        if(!empty($_POST))
        {
            $this->marketresearch->activateStage($stageID);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('programplan')->computeProgress($stageID, 'activate');

            $this->executeHooks($stageID);
            return $this->sendSuccess(array('clodeModal' => true, 'load' => true));
        }

        $newBegin = date('Y-m-d');
        $dateDiff = helper::diffDate($newBegin, $stage->begin);
        $newEnd   = date('Y-m-d', strtotime($stage->end) + $dateDiff * 24 * 3600);

        $this->view->title      = $this->lang->marketresearch->activateStage;
        $this->view->position[] = html::a($this->createLink('marketresearch', 'stage', "stageID=$stageID"), $stage->name);
        $this->view->position[] = $this->lang->marketresearch->activateStage;
        $this->view->stage      = $stage;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('execution', $stageID);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->display();
    }

    /**
     * Load stage lang.
     *
     * @access public
     * @return void
     */
    public function loadStageLang()
    {
        $this->lang->researchstage = new stdclass();
        $this->lang->researchstage->status     = $this->lang->marketresearch->status;
        $this->lang->researchstage->realEnd    = $this->lang->marketresearch->realEnd;
        $this->lang->researchstage->closedBy   = $this->lang->marketresearch->closedBy;
        $this->lang->researchstage->closedDate = $this->lang->marketresearch->closedDate;
        $this->lang->researchstage->begin      = $this->lang->marketresearch->begin;
        $this->lang->researchstage->end        = $this->lang->marketresearch->end;
    }

    /**
     * Close stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function closeStage($stageID)
    {
        $this->loadStageLang();
        $stage = $this->loadModel('execution')->getById($stageID);

        if(!empty($_POST))
        {
            $this->marketresearch->closeStage($stageID);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->executeHooks($stageID);
            return $this->sendSuccess(array('clodeModal' => true, 'load' => true));
        }

        $this->view->title      = $this->lang->marketresearch->closeStage;
        $this->view->stage      = $stage;
        $this->view->position[] = $this->lang->marketresearch->closeStage;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('execution', $stageID);
        $this->display();
    }

    /**
     * Delete research
     *
     * @param  int    $researchID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($researchID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $research = $this->loadModel('project')->getByID($researchID);
            return print(js::confirm(sprintf($this->lang->marketresearch->confirmDelete, $research->name), $this->createLink('marketresearch', 'delete', "researchID=$researchID&confirm=yes"), ''));
        }
        $this->dao->update(TABLE_MARKETRESEARCH)->set('deleted')->eq('1')->where('id')->eq($researchID)->exec();
        $this->loadModel('action')->create('marketresearch', $researchID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        $locateLink = $this->session->marketresearchList ? $this->session->marketresearchList : $this->inlink('browse', "marketID={$research->market}");
        return print(js::locate($locateLink, 'parent'));
    }

    /**
     * Activate research.
     *
     * @param  int    $researchID
     * @access public
     * @return void
     */
    public function activate($researchID)
    {
        $this->loadModel('action');
        $this->app->loadLang('execution');
        $research = $this->loadModel('project')->getByID($researchID);

        if(!empty($_POST))
        {
            if($_POST['begin'] != '' && $_POST['end'] != '' && $_POST['begin'] > $_POST['end'])
            {
                dao::$errors['end'] = sprintf($this->lang->marketresearch->cannotGe, $this->lang->marketresearch->begin, $_POST['begin'], $this->lang->marketresearch->end, $_POST['end']);
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }
            $changes = $this->marketresearch->activate($researchID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('marketresearch', $researchID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($researchID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => 'table'));
        }

        $newBegin = date('Y-m-d');
        $dateDiff = helper::diffDate($newBegin, $research->begin);
        $newEnd   = date('Y-m-d', strtotime($research->end) + $dateDiff * 24 * 3600);

        $this->view->title            = $this->lang->marketresearch->activate;
        $this->view->position[]       = $this->lang->marketresearch->activate;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions          = $this->action->getList('marketresearch', $researchID);
        $this->view->newBegin         = $newBegin;
        $this->view->newEnd           = $newEnd;
        $this->view->research         = $research;
        $this->view->marketresearchID = $researchID;

        $this->display();
    }

    /*
     * Stage list.
     *
     * @param int    $researchID
     * @param string $browseType
     * @param int    $param
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function task($researchID = 0, $browseType = 'unclosed', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->loadModel('task');
        $this->loadModel('execution');
        $this->loadModel('researchtask');
        $this->app->loadLang('programplan');
        $this->session->set('researchBrowseType', $browseType);
        $this->session->set('taskList', $this->app->getURI(true), 'market');

        $browseType = strtolower($browseType);

        /* Refresh stats fields of projects. */
        $this->loadModel('program')->refreshStats();

        /* Get research info. */
        $research = $this->loadModel('project')->getByID($researchID);

        /* Set menu. */
        $this->market->setMenu($research->market);

        /* Set queryID. */
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Build the search form. */
        $actionURL = $this->createLink('marketresearch', 'task', "researchID=$researchID&browseType=bySearch&param=myQueryID");
        $this->marketresearch->buildTaskSearchForm($researchID, $queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml' || $this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build stage list structure. */
        $researchTasks = $this->marketresearch->getTasks($researchID, $browseType, $queryID, $orderBy);
        $stageStats    = $this->marketresearch->getStatData($researchID, $researchTasks, 'order_asc,status_asc', $pager);

        $this->marketresearchZen->getSummary($stageStats);

        /* Set session. */
        $this->app->session->set('marketstageList', $this->app->getURI(true));

        /* Assign. */
        $this->view->title      = $research->name . $this->lang->hyphen . $this->lang->researchtask->common;
        $this->view->researchID = $researchID;
        $this->view->research   = $research;
        $this->view->taskStats  = $stageStats;
        $this->view->taskTotal  = $this->session->researchTaskTotal;
        $this->view->pager      = $pager;
        $this->view->recTotal   = $pager->recTotal;
        $this->view->recPerPage = $pager->recPerPage;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|all');
        $this->view->param      = $param;
        $this->view->marketID   = $research->market;
        $this->display();
    }

    /**
     * Setting stage.
     *
     * @param  int    $researchID
     * @param  int    $stageID
     * @param  string $executionType
     * @access public
     * @return void
     */
    public function createStage($researchID = 0, $stageID = 0, $executionType = 'stage')
    {
        /* Load module and lang. */
        $this->app->loadLang('project');
        $this->app->loadLang('stage');
        $this->loadModel('programplan');
        $this->loadModel('execution');
        $this->loadStageLang();

        $project     = $this->loadModel('project')->getById($researchID);
        $plans       = $this->programplan->getStage($stageID ? $stageID : $researchID, 0, 'parent', 'order_asc,status_asc');
        $programPlan = $this->project->getById($stageID, 'stage');

        $this->loadModel('market')->setMenu($project->market);

        if($_POST)
        {
            $this->programplan->create(array($stageID), $researchID, 0, $stageID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = $this->createLink('marketresearch', 'task', "researchID=$researchID");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        /* Set visible and required fields. */
        $visibleFields  = array();
        $requiredFields = array();
        foreach(explode(',', $this->config->marketresearch->customCreateFields) as $field) $customFields[$field] = $this->lang->programplan->$field;
        $showFields = $this->config->marketresearch->custom->createFields;
        foreach(explode(',', $showFields) as $field)
        {
            if($field) $visibleFields[$field] = '';
        }

        foreach(explode(',', $this->config->programplan->create->requiredFields) as $field)
        {
            if($field)
            {
                $requiredFields[$field] = '';
                if(strpos(",{$this->config->marketresearch->customCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
            }
        }

        /* Assign. */
        $this->view->title              = $this->lang->programplan->create . $this->lang->hyphen . $project->name;
        $this->view->project            = $project;
        $this->view->plans              = $plans;
        $this->view->stageID            = $stageID;
        $this->view->type               = 'lists';
        $this->view->executionType      = $executionType;
        $this->view->PMUsers            = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $project->PM);
        $this->view->custom             = 'custom';
        $this->view->customFields       = $customFields;
        $this->view->showFields         = $showFields;
        $this->view->visibleFields      = $visibleFields;
        $this->view->requiredFields     = $requiredFields;
        $this->view->colspan            = count($visibleFields) + 3;
        $this->view->programPlan        = $programPlan;
        $this->view->enableOptionalAttr = (empty($programPlan) or (!empty($programPlan) and $programPlan->attribute == 'mix'));
        $this->view->marketID           = $project->market;

        $this->display();
    }

    /**
     * Batch create stage.
     *
     * @param int     $researchID
     * @param int     $stageID
     * @access public
     * @return void
     */
    public function batchStage($researchID = 0, $stageID = 0)
    {
        echo $this->fetch('marketresearch', 'createStage', "researchID=$researchID&stageID=$stageID&executionType=stage");
    }

    /**
     * Delete stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function deleteStage($stageID = 0)
    {
        $this->loadStageLang();
        $this->loadModel('execution');
        $this->loadModel('project');

        /* Delete execution. */
        $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq(1)->where('id')->eq($stageID)->exec();
        $this->loadModel('action')->create('researchstage', $stageID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
        $this->loadModel('user')->updateUserView(array($stageID), 'sprint');
        $this->loadModel('common')->syncPPEStatus($stageID);
        $this->loadModel('programplan')->computeProgress($stageID);

        $message = $this->executeHooks($stageID);
        if(!$message) $message = $this->lang->saveSuccess;

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => true));
    }

    /**
     * Edit stage.
     *
     * @param int $stageID
     * @access public
     * @return void
     */
    public function editStage($stageID = 0, $projectID = 0)
    {
        echo $this->fetch('programplan', 'edit', "stageID=$stageID&projectID=$projectID");
    }

    /**
     * Start stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function startStage($stageID = 0)
    {
        echo $this->fetch('execution', 'start', "stageID=$stageID");
    }

    /**
     * Browse team of a research.
     *
     * @param  int    $researchID
     * @access public
     * @return void
     */
    public function team($researchID = 0)
    {
        $this->loadModel('market');
        $this->loadModel('project');
        $this->app->loadLang('execution');

        $research = $this->marketresearch->getById($researchID);

        $this->market->setMenu($research->market);

        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;

        $this->view->title       = $research->name . $this->lang->hyphen . $this->lang->project->team;
        $this->view->researchID  = $researchID;
        $this->view->teamMembers = $this->project->getTeamMembers($researchID);
        $this->view->deptUsers   = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        $this->view->recTotal    = count($this->view->teamMembers);
        $this->view->marketID    = $research->market;

        $this->display();
    }

    /**
     * Manage market research members.
     *
     * @param  int    $researchID
     * @param  int    $dept
     * @access public
     * @return void
     */
    public function manageMembers($researchID, $dept = '')
    {
        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');
        $this->loadModel('project');
        $this->app->loadLang('execution');
        $this->app->loadConfig('execution');

        $research = $this->marketresearch->getById($researchID);
        $this->market->setMenu($research->market);

        if(!empty($_POST))
        {
            $members = form::batchData($this->config->project->form->manageMembers)->get();
            $this->project->manageMembers($researchID, $members);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('team', $researchID, 'ManagedTeam');

            return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'locate' => $this->createLink('marketresearch', 'team', "researchID=$researchID")));
        }

        $users          = $this->user->getPairs('noclosed|nodeleted|devfirst');
        $roles          = $this->user->getUserRoles(array_keys($users));
        $deptUsers      = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);
        $currentMembers = $this->project->getTeamMembers($researchID);

        $this->view->title          = $this->lang->project->manageMembers . $this->lang->hyphen . $research->name;
        $this->view->research       = $research;
        $this->view->users          = $users;
        $this->view->deptUsers      = $deptUsers;
        $this->view->roles          = $roles;
        $this->view->dept           = $dept;
        $this->view->depts          = arrayUnion(array('' => ''), $this->dept->getOptionMenu());
        $this->view->currentMembers = $currentMembers;
        $this->view->teams2Import   = arrayUnion(array('' => ''), $this->loadModel('personnel')->getCopiedObjects($researchID, 'project', true));
        $this->view->research       = $research;
        $this->view->teamMembers    = $this->marketresearchZen->buildMembers($currentMembers, $deptUsers, $research->days);
        $this->view->marketID       = $research->market;
        $this->display();
    }

    /**
     * Remove member from research.
     *
     * @param  int    $researchID
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function unlinkMember($researchID, $userID)
    {
        echo $this->fetch('project', 'unlinkMember', "researchID=$researchID&userID=$userID");
    }

    /**
     * Browse research reports.
     *
     * @param  int    $researchID
     * @access public
     * @return void
     */
    public function reports($researchID = 0)
    {
        if($researchID) $research = $this->marketresearch->getByID($researchID);
        $marketID     = $researchID ? $research->market : 0;
        $reportsCount = $this->loadModel('marketreport')->countReports($researchID);

        $this->lang->market->homeMenu->report['subModule'] = 'marketresearch';
        $this->lang->market->menu->report['subModule']     = 'marketresearch';

        if($reportsCount)
        {
            echo $this->fetch('marketreport', 'browse', "marketID=$marketID&browseType=published&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1");
        }
        else
        {
            echo $this->fetch('marketreport', 'create', "marketID=$marketID");
        }
    }
}
