<?php
/**
 * The control file of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     meeting
 * @version     $Id: control.php 5107 2021-06-09 10:40:12Z lyc $
 * @link        https://www.zentao.net
 */
class meeting extends control
{
    /**
      * Common actions.
      *
      * @param  int    $projectID
      * @param  string $from
      * @access public
      * @return object current object
      */
     public function commonAction($projectID = 0, $from = 'project')
     {
        /* Set menu. */
        if($from == 'execution' || $from == 'project') $this->loadModel($from)->setMenu($projectID);
        if($from == 'execution')
        {
            $this->executions = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
            if(!$this->executions and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('execution', 'create'));
            $execution = $this->loadModel('execution')->fetchByID($projectID);
            if(!empty($execution) && $execution->attribute != 'dev' && !empty($execution->attribute)) $this->locate($this->createLink('execution', 'task', "taskID=$execution->id"));
        }
     }

    /**
     * Browse meetings.
     *
     * @param  int    $objectID
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
    public function browse($objectID = 0, $from = 'project', $browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->commonAction($objectID, $from);
        $uri = $this->app->getURI(true);
        $this->session->set('meetingList', $uri, $this->app->tab);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('meeting', 'browse', "objectID=$objectID&from=$from&browseType=bysearch&queryID=myQueryID");
        $this->meeting->buildSearchForm($queryID, $actionURL, $objectID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $meetings = $this->meeting->getList($objectID, $browseType, $param, $orderBy, $pager);

        $projectID   = $from == 'project'   ? $objectID : 0;
        $executionID = $from == 'execution' ? $objectID : 0;

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'meeting');

        $this->view->title       = $this->lang->meeting->common . $this->lang->hyphen . $this->lang->meeting->browse;
        $this->view->meetings    = $meetings;
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->from        = $from;
        $this->view->orderBy     = $orderBy;
        $this->view->objectID    = $objectID;
        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
        $this->view->project     = $this->loadModel('project')->fetchByID($objectID);
        $this->view->pager       = $pager;
        $this->view->typeList    = arrayUnion(array('' => ''), $this->loadModel('pssp')->getProcesses($objectID));
        $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
        $this->view->users       = $this->loadModel('user')->getPairs('all,noletter');
        $this->view->projects    = array(0 => '') + $this->loadModel('project')->getPairsByProgram(0, 'all', true);
        $this->view->executions  = array(0 => '') + $this->loadModel('execution')->getPairs(0, 'all', 'nocode|multiple');
        $this->view->rooms       = array(0 => '') + $this->loadModel('meetingroom')->getPairs();

        $this->display();
    }

    /**
     * Create a meeting.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $from
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function create($projectID = 0, $executionID = 0, $from = 'project', $objectType = '')
    {
        $this->loadModel('project');

        $execution = $this->loadModel('execution')->fetchByID($executionID);
        if(empty($projectID) && !empty($execution->project)) $projectID = (int)$execution->project;

        $objectID = $from == 'project' ? $projectID : $executionID;
        $this->commonAction($objectID, $from);

        if($_POST)
        {
            $meetingID = $this->meeting->create($projectID);
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(!$meetingID)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('meeting', $meetingID, 'Opened');

            /* Return meeting id when call the API. */
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $meetingID));

            $link = $this->session->meetingList;
            if(!$link)
            {
                if($from == 'project') $link = $this->createLink('meeting', 'browse', "objectID=$projectID");
                if($this->app->tab == 'my') $link = $this->createLink('my', 'meeting');
            }

            $response['locate'] = $link;
            return $this->send($response);
        }

        $project = $projectID ? $this->project->getByID($projectID) : '';
        if(!empty($project) and $project->model == 'waterfall') $this->view->typeList  = $this->loadModel('pssp')->getProcesses($projectID);

        $executions = $this->loadModel('execution')->getByProject($projectID, 'all', 0, true);
        if(!isset($executions[$executionID])) $executionID = 0;

        $this->view->title         = $this->lang->meeting->common . $this->lang->hyphen . $this->lang->meeting->create;
        $this->view->users         = $projectID || $executionID ? $this->loadModel('user')->getAllMembers($executionID ? $executionID : $projectID) : $this->loadModel('user')->getPairs('all,noletter,noclosed');
        $this->view->project       = $project;
        $this->view->depts         = $this->loadModel('dept')->getOptionMenu();
        $this->view->rooms         = $this->loadModel('meetingroom')->getPairs();
        $this->view->projectID     = $projectID;
        $this->view->executionID   = $executionID;
        $this->view->projects      = $this->project->getPairsByModel('all', 'noclosed');
        $this->view->executions    = $executions;
        $this->view->noSprintPairs = $this->project->getProjectExecutionPairs();
        $this->view->from          = $from;
        $this->view->objects       = $this->meeting->getObjectsByType($projectID, $objectType);

        $this->display();
    }

    /**
     * Edit a meeting.
     *
     * @param  int    $meetingID
     * @param  string $from
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function edit($meetingID, $from = 'project', $projectID = 0, $executionID = 0, $objectType = '')
    {
        $meeting = $this->meeting->getById($meetingID);

        if(empty($projectID))   $projectID   = $meeting->project;
        if(empty($executionID)) $executionID = $meeting->execution;
        if(empty($objectType))  $objectType  = $meeting->objectType;

        $this->loadModel('project');
        if($from == 'project') $this->commonAction($projectID, $from);
        if($from == 'execution') $this->commonAction($executionID, $from);

        if($_POST)
        {
            $changes = $this->meeting->update($meetingID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('meeting', $meetingID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $link = inLink('view', "meetingID=$meetingID&from=$from");
            if($from == 'project')      $link = $this->createLink('meeting', 'browse', "objectID={$projectID}&from=$from");
            if($from == 'execution')    $link = $this->createLink('meeting', 'browse', "objectID={$executionID}&from=$from");
            if($this->app->tab == 'my') $link = $this->createLink('my', 'meeting');

            $response['locate'] = $link;
            return $this->send($response);
        }

        $project = $projectID ? $this->project->getByID($projectID) : '';
        if(!empty($project) and $project->model == 'waterfall') $this->view->typeList = $this->loadModel('pssp')->getProcesses($projectID);

        $this->view->title         = $this->lang->meeting->common . $this->lang->hyphen . $this->lang->meeting->edit;
        $this->view->meeting       = $meeting;
        $this->view->from          = $from;
        $this->view->project       = $project;
        $this->view->depts         = $this->loadModel('dept')->getOptionMenu();
        $this->view->rooms         = $this->loadModel('meetingroom')->getPairs();
        $this->view->objects       = $this->meeting->getObjectsByType($projectID, $objectType);
        $this->view->users         = $this->loadModel('user')->getPairs('all,noletter,noclosed');
        $this->view->executions    = $this->loadModel('execution')->getByProject($projectID, 'all', 0, true);
        $this->view->projects      = $this->project->getPairsByModel('all', 'noclosed');
        $this->view->noSprintPairs = $this->project->getProjectExecutionPairs();
        $this->view->projectID     = $projectID;
        $this->view->executionID   = $executionID;
        $this->display();
    }

    /**
     * View a meeting.
     *
     * @param  int    $meetingID
     * @access public
     * @return void
     */
    public function view($meetingID, $from = 'project')
    {
        $meetingID = (int)$meetingID;
        $meeting   = $this->meeting->getById($meetingID);
        if(empty($meeting)) return print(js::error($this->lang->notFound) . js::locate('back'));
        $projectID = $this->app->tab == 'execution' ? $meeting->execution : $meeting->project;
        if($from == 'project') $this->commonAction($projectID, $from);
        if($from == 'execution') $this->commonAction($meeting->execution, $from);

        $this->view->title      = $this->lang->meeting->common . $this->lang->hyphen . $this->lang->meeting->view;
        $this->view->meeting    = $meeting;
        $this->view->projectID  = $projectID;
        $this->view->from       = $from;
        $this->view->actions    = $this->loadModel('action')->getList('meeting', $meetingID);
        $this->view->typeList   = arrayUnion(array('' => ''), $this->loadModel('pssp')->getProcesses($meeting->project));
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->rooms      = arrayUnion(array('' => ''), $this->loadModel('meetingroom')->getPairs());
        $this->view->users      = $this->loadModel('user')->getPairs('all,noletter');
        $this->view->execution  = $this->loadModel('execution')->getById($meeting->execution);
        $this->view->project    = $this->loadModel('project')->getById($meeting->project);
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('meeting', $meetingID);

        $this->display();
    }

    /**
     * Minutes a meeting.
     *
     * @param  int    $meetingID
     * @access public
     * @return void
     */
    public function minutes($meetingID)
    {
        if($_POST)
        {
            $changes = $this->meeting->minutes($meetingID);
            if(dao::isError()) return $this->sendError(dao::getError());

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('meeting', $meetingID, 'minuted');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true));
        }

        $this->view->minutesFiles = $this->loadModel('file')->getByObject('meeting', $meetingID, 'minutesFiles');
        $this->view->meeting      = $this->meeting->getByID($meetingID);
        $this->view->actions      = $this->loadModel('action')->getList('meeting', $meetingID);
        $this->view->users        = $this->loadModel('user')->getPairs('all,noletter');

        $this->display();
    }

    /**
     * Delete a meeting.
     *
     * @param  int    $meetingID
     * @param  string $from
     * @access public
     * @return void
     */
    public function delete($meetingID, $from = 'project')
    {
        $meeting = $this->meeting->getByID($meetingID);
        $this->meeting->delete(TABLE_MEETING, $meetingID);

        /* Delete todos. */
        $this->dao->update(TABLE_TODO)->set('deleted')->eq(1)->where('type')->eq('meeting')->andWhere('objectID')->eq($meetingID)->exec();

        $link = $this->session->meetingList;
        if(!$link)
        {
            if($this->app->tab == 'project') $link = $this->createLink('meeting', 'browse', "objectID={$meeting->project}&from=$from");
            if($this->app->tab == 'my')      $link = $this->createLink('my', 'meeting');
        }

        return $this->sendSuccess(array('load' => $link));
    }

    /**
     * Ajax get objects.
     *
     * @param  int    $projectID
     * @param  string $objectType
     * @access public
     * @return string
     */
    public function ajaxGetObjects($projectID = 0, $objectType = '')
    {
        $objects = $this->meeting->getObjectsByType($projectID, $objectType);
        return print(html::select('objectID', arrayUnion(array('' => ''), $objects), '', "class='form-control chosen'"));
    }

    /**
     * AJAX: get team members by projectID/executionID.
     *
     * @param  int    $objectID
     * @param  string $selected
     * @access public
     * @return string
     */
    public function ajaxGetTeamMembers($objectID, $selected = '')
    {
        $type = $this->dao->findById($objectID)->from(TABLE_PROJECT)->fetch('type');
        if($type != 'project') $type = 'execution';
        $users   = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $members = $this->loadModel('user')->getAllMembers($objectID, $type);

        return print(html::select('participant[]', $objectID ? $members : $users, $selected, "class='form-control picker-select' multiple"));
    }

    /**
     * Ajax get contact users.
     *
     * @param  int    $contactListID
     * @access public
     * @return void
     */
    public function ajaxGetContactUsers($contactListID)
    {
        $this->loadModel('user');
        $list  = $contactListID ? $this->user->getContactListByID($contactListID) : '';
        $users = $this->user->getPairs('devfirst|nodeleted|noclosed', $list ? $list->userList : '', $this->config->maxCount);

        if(!$contactListID) return print(html::select('participant[]', $users, '', "class='form-control picker-select' multiple"));

        return print(html::select('participant[]', $users, $list->userList, "class='form-control picker-select' multiple"));
    }
}
