<?php
/**
 * The control file of auditplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     auditplan
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class auditplan extends control
{
    public function commonAction($projectID, $from = 'project')
    {
        if($from == 'project' || $from == 'execution') $this->loadModel($from)->setMenu($projectID);
        $this->loadModel('project');
        if($from == 'execution')
        {
            $this->executions = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
            if(!$this->executions and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('execution', 'create'));
            $execution = $this->loadModel('execution')->getByID($projectID);
            if($execution->attribute != 'dev' && !empty($execution->attribute)) $this->locate($this->createLink('execution', 'task', "taskID=$execution->id"));
        }
    }

    /**
     * Browse auditplans.
     *
     * @param  int    $projectID
     * @param  string $from
     * @param  int    $processID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $from = 'project', $processID = 0, $browseType = 'all', $orderBy = 'id_desc', $param = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('datatable');
        $this->commonAction($projectID, $from);
        $uri = $this->app->getURI(true);
        $this->session->set('auditplanList', $uri, $this->app->tab);

        $objectID = $projectID;
        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $objectID  = $execution->project;
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $project = $this->loadModel('project')->getByID($objectID);
        $processList = $this->loadModel('auditcl')->getProcessList(false, 0, $project->model);

        $classify = $project->model == 'waterfall' ? 'classify' : $project->model . 'Classify';

        $browseType = strtolower($browseType);
        $queryID    = $browseType == 'bysearch' ? (int)$param : 0;
        if($browseType != 'bysearch')
        {
            $auditplans = $this->auditplan->getList($projectID, $browseType, $param, $orderBy, $pager, $processID);
        }
        else
        {
            $auditplans = $this->auditplan->getBySearch($projectID, $queryID, $orderBy, $pager, $processID);
        }

        $actionURL = $this->createLink('auditplan', 'browse', "projectID=$projectID&from=$from&processID=$processID&browseType=bysearch&orderBy=$orderBy&param=myQueryID");
        $this->auditplan->buildSearchForm($project->model, $objectID, $param, $actionURL);
        if(empty($project->multiple))
        {
            unset($this->config->auditplan->datatable->fieldList['execution']);
            $this->config->auditplan->datatable->defaultField = array('id', 'objectID', 'objectType', 'status', 'assignedTo', 'checkDate', 'realCheckDate', 'nc', 'actions');
        }

        $this->view->title           = $this->lang->auditplan->common . $this->lang->hyphen . $this->lang->auditplan->browse;
        $this->view->auditplans      = $auditplans;
        $this->view->browseType      = $browseType;
        $this->view->param           = $param;
        $this->view->processList     = $processList;
        $this->view->process         = $this->loadModel('process')->getByID($processID);
        $this->view->processTypeList = $this->lang->process->$classify;
        $this->view->processID       = $processID;
        $this->view->orderBy         = $orderBy;
        $this->view->pager           = $pager;
        $this->view->from            = $from;
        $this->view->projectID       = $projectID;
        $this->view->object          = $from == 'execution' && isset($execution) ? $execution : $project;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->processes       = $this->loadModel('pssp')->getProcesses($objectID);
        $this->view->activities      = $this->pssp->getActivityPairs($objectID);
        $this->view->outputs         = $this->pssp->getOutputPairs($objectID);
        $this->view->executions      = $this->loadModel('execution')->getPairs($objectID);

        $this->display();
    }

    /**
     * Create an auditplan.
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
        $project = $this->loadModel('project')->getByID($projectID);
        if($_POST)
        {
            $auditplanID = $this->auditplan->create($projectID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('auditplan', $auditplanID, 'Opened', $this->post->comment);
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->session->auditplanList;
            return $this->send($response);
        }

        $executionList = array(0 => '') + $this->loadModel('execution')->getPairs($projectID, 'sprint,stage', 'all');

        $this->view->title      = $this->lang->auditplan->create;
        $this->view->processes  = array(0 => '') + $this->loadModel('pssp')->getProcesses($projectID, $project->model);
        $this->view->users      = $this->project->getTeamMemberPairs($projectID);
        $this->view->projectID  = $projectID;
        $this->view->executions = $executionList;
        $this->view->project    = $project;
        $this->view->from       = $from;
        $this->display();
    }

    /**
     * Edit an auditplan.
     *
     * @param  int    $auditplanID
     * @param  string $from
     * @access public
     * @return void
     */
    public function edit($auditplanID = 0, $from = 'project')
    {
        $auditplan = $this->auditplan->getByID($auditplanID);
        if($from == 'project') $this->commonAction($auditplan->project, $from);
        if($from == 'execution') $this->commonAction($auditplan->execution, $from);
        if($_POST)
        {
            $changes = $this->auditplan->update($auditplanID);
            if($changes or $this->post->comment)
            {
                $actionID = $this->loadModel('action')->create('auditplan', $auditplanID, 'Edited', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->session->auditplanList;
            if(isonlybody()) $response['locate'] = 'parent';
            return $this->send($response);
        }

        if($auditplan->objectType == 'zoutput')
        {
            $this->view->activityID = $this->dao->select('activity')->from(TABLE_ZOUTPUT)->where('id')->eq($auditplan->objectID)->fetch('activity');
        }
        else
        {
            $this->view->activityID = $auditplan->objectID;
        }

        $executionList = array(0 => '') + $this->loadModel('execution')->getPairs($auditplan->project, 'sprint,stage', 'all');

        $this->view->title      = $this->lang->auditplan->edit;
        $this->view->processes  = $this->loadModel('pssp')->getProcesses($auditplan->project);
        $this->view->users      = $this->loadModel('project')->getTeamMemberPairs($auditplan->project);
        $this->view->auditplan  = $this->auditplan->getComment($auditplan);
        $this->view->executions = $executionList;
        $this->view->from       = $from;
        $this->view->actions    = $this->loadModel('action')->getList('auditplan', $auditplanID);
        $this->view->project    = $this->loadModel('project')->fetchByID((int)$auditplan->project);

        $this->display();
    }

    /**
     * Batch create auditplans.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchCreate($projectID = 0, $from = 'project')
    {
        $this->commonAction($projectID, $from);

        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
            $this->view->executionID = $execution->id;
        }

        $project = $this->loadModel('project')->getByID($projectID);

        if($_POST)
        {
            $result = $this->auditplan->batchCreate($projectID);
            if(!$result)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->session->auditplanList;
            return $this->send($response);
        }

        $executionList = array(0 => '') + $this->loadModel('execution')->getPairs($projectID, 'sprint,stage', 'all');

        $checkList = $this->dao->select('t1.process,t2.name as activityName,t1.activity,t3.output,t4.name as outputName')->from(TABLE_PROGRAMACTIVITY)->alias('t1')
            ->leftjoin(TABLE_ACTIVITY)->alias('t2')->on('t1.activity=t2.id')
            ->leftjoin(TABLE_PROGRAMOUTPUT)->alias('t3')->on('t1.activity=t3.activity')
            ->leftjoin(TABLE_ZOUTPUT)->alias('t4')->on('t3.output=t4.id')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.result')->eq('yes')
            ->fetchAll();

        $checkGroup   = array();
        $activityList = array();
        $outputList   = array();
        foreach($checkList as $list)
        {
            $checkGroup[$list->process][$list->activity][$list->output] = $list->output;
            $activityList[$list->process][$list->activity] = $this->lang->auditplan->activity . '：' . $list->activityName;
            if($list->output) $outputList[$list->activity][$list->output]    = $list->output ? $this->lang->auditplan->zoutput . '：' . $list->outputName : '';
        }

        $this->view->title        = $this->lang->auditplan->batchCreate;
        $this->view->processes    = $this->loadModel('pssp')->getProcesses($projectID, $project->model);
        $this->view->users        = $this->project->getTeamMemberPairs($projectID);
        $this->view->projectID    = $projectID;
        $this->view->project      = $project;
        $this->view->executions   = $executionList;
        $this->view->from         = $from;
        $this->view->checkGroup   = $checkGroup;
        $this->view->activityList = $activityList;
        $this->view->outputList   = $outputList;

        $this->display();
    }

    /**
     * Delete an auditplan.
     *
     * @param  int    $auditplanID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($auditplanID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->auditplan->confirmDelete, inLink('delete', "auditplanID=$auditplanID&confirm=yes")));
        }
        else
        {
            $this->auditplan->delete(TABLE_AUDITPLAN, $auditplanID);

            $auditResults = $this->auditplan->getResults($auditplanID);
            foreach($auditResults as $auditResult) $this->auditplan->delete(TABLE_AUDITRESULT, $auditResult->id);

            $ncs = $this->auditplan->getNc($auditplanID);
            foreach($ncs as $nc) $this->auditplan->delete(TABLE_NC, $nc->id);

            if($this->app->tab == 'my') return $this->send(array('result' => 'success', 'load' => true));
            die(js::locate($this->session->auditplanList, 'parent'));
        }
    }

    /**
     * Batch edit.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchEdit($projectID = 0, $from = 'project')
    {
        $this->commonAction($projectID, $from);

        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
            $this->view->executionID = $execution->id;
        }
        $project = $this->loadModel('project')->getByID($projectID);

        if($this->post->process)
        {
            $this->auditplan->batchUpdate($projectID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->session->auditplanList;
            return $this->send($response);
        }

        if(empty($_POST['auditIDList'])) die(js::locate(inlink('browse', "projectID=$projectID"), 'parent'));
        $auditIdList   = $this->post->auditIDList;
        $auditplans    = $this->auditplan->getByIdList($auditIdList);
        $executionList = array(0 => '') + $this->loadModel('execution')->getPairs($projectID, 'sprint,stage', 'all');

        $activityIdList      = array();
        $objectTypeList      = array();
        $objectIdList        = array();
        $zoutputObjectIdList = array();
        foreach($auditplans as $auditplan)
        {
            $objectTypeList[$auditplan->id] = $auditplan->objectType;
            $objectIdList[$auditplan->id]   = $auditplan->objectID;
            if($auditplan->objectType == 'zoutput')
            {
                $zoutputObjectIdList[$auditplan->id] = $auditplan->objectID;
            }
            else
            {
                $activityIdList[$auditplan->id] = $auditplan->objectID;
            }
        }

        if($zoutputObjectIdList)
        {
            $zoutputActivityList = $this->dao->select('id,activity')->from(TABLE_ZOUTPUT)->where('id')->in($zoutputObjectIdList)->fetchPairs('id', 'activity');
            foreach($zoutputObjectIdList as $auditplanID => $zoutputID) $activityIdList[$auditplanID] = $zoutputActivityList[$zoutputID];
        }

        $this->view->title          = $this->lang->auditplan->batchEdit;
        $this->view->auditplans     = $auditplans;
        $this->view->processes      = $this->loadModel('pssp')->getProcesses($projectID, $project->model);
        $this->view->users          = $this->project->getTeamMemberPairs($projectID);
        $this->view->projectID      = $projectID;
        $this->view->project        = $project;
        $this->view->from           = $from;
        $this->view->executions     = $executionList;
        $this->view->activityIdList = $activityIdList;
        $this->view->objectTypeList = $objectTypeList;
        $this->view->objectIdList   = $objectIdList;
        $this->display();
    }

    /**
     * Batch check auditplans.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchCheck($projectID = 0, $from = 'project')
    {
        $this->commonAction($projectID, $from);

        $this->app->loadLang('nc');
        if($this->app->tab == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
        }

        if($this->post->result)
        {
            $this->auditplan->batchCheck($projectID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->session->auditplanList;
            return $this->send($response);
        }

        $auditIDList = $this->post->auditIDList ? $this->post->auditIDList : die(js::locate(inlink('browse', "projectID=$projectID"), 'parent'));
        $draftResults = array();
        foreach($auditIDList as $auditID) $draftResults[$auditID] = $this->auditplan->getResults($auditID, 'draft');

        $this->view->checkList    = $this->auditplan->getCheckListByList($auditIDList);
        $this->view->draftResults = $draftResults;
        $this->view->activities   = $this->loadModel('pssp')->getActivityPairs($projectID);
        $this->view->outputs      = $this->pssp->getOutputPairs($projectID);
        $this->display();
    }

    /**
     * Check auditplan.
     *
     * @param  int    $auditplanID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function check($auditplanID, $projectID = 0)
    {
        $auditplan = $this->auditplan->getByID($auditplanID);

        $this->app->loadLang('nc');
        if($this->app->tab == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
        }
        if($_POST)
        {
            $this->auditplan->check($auditplanID, $projectID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            $this->loadModel('action')->create('auditplan', $auditplanID, 'Checked');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->app->tab == 'my' ? inlink('result', "auditplanID=$auditplanID", '', true) : 'parent';
            return $this->send($response);
        }

        $this->view->auditplan    = $auditplan;
        $this->view->checkList    = $this->auditplan->getCheckList($auditplan->objectType, $auditplan->objectID);
        $this->view->draftResults = $this->auditplan->getResults($auditplanID, 'draft');
        $this->view->object       = $this->auditplan->getObjectName($auditplan->objectType, $auditplan->objectID);
        $this->display();
    }

    /**
     * Update assign of auditplan.
     *
     * @param  int    $auditplanID
     * @access public
     * @return void
     */
    public function assignTo($auditplanID)
    {
        $auditplan = $this->auditplan->getByID($auditplanID);

        if($_POST)
        {
            $changes = $this->auditplan->assign($auditplanID);
            if(dao::isError()) die(js::error(dao::getError()));
            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('auditplan', $auditplanID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            die(js::reload('parent.parent'));
        }

        $this->view->auditplan = $auditplan;
        $this->view->users     = $this->loadModel('project')->getTeamMemberPairs($auditplan->project);

        $this->display();
    }

    /**
     * Non-conformity.
     *
     * @param  int    $auditplanID
     * @access public
     * @return void
     */
    public function nc($auditplanID)
    {
        $this->loadModel('nc');
        $auditplan = $this->auditplan->getByID($auditplanID);

        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->auditplan = $auditplan;
        $this->view->ncs       = $this->auditplan->getNC($auditplanID);
        $this->display();
    }

    /**
     * Result.
     *
     * @param  int    $auditplanID
     * @access public
     * @return void
     */
    public function result($auditplanID)
    {
        $auditplan = $this->auditplan->getByID($auditplanID);

        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->auditplan = $auditplan;
        $this->view->results   = $this->auditplan->getResults($auditplanID, 'normal');
        $this->view->object    = $this->auditplan->getObjectName($auditplan->objectType, $auditplan->objectID);
        $this->view->checkList = $this->auditplan->getCheckList($auditplan->objectType, $auditplan->objectID);
        $this->display();
    }

    /**
     * Ajax get activity.
     *
     * @param  int    $projectID
     * @param  int    $processID
     * @param  int    $i
     * @param  string $from
     * @access public
     * @return void
     */
    public function ajaxGetActivity($projectID = 0, $processID = 0, $i = 0, $from = '')
    {
        $idList = $this->dao->select('activity')->from(TABLE_PROGRAMACTIVITY)
            ->where('process')->eq($processID)
            ->beginIf($from == 'project')->andWhere('execution')->eq(0)->fi()
            ->andWhere('project')->eq($projectID)
            ->andWhere('result')->eq('yes')
            ->fetchPairs('activity');

        $activities = $this->dao->select('id, name')->from(TABLE_ACTIVITY)
            ->where('id')->in($idList)
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();

        foreach($activities as $id => $activity) $activities[$id] = $this->lang->auditplan->activity . '：' . $activity;
        if($i == 0)
        {
            die(html::select('activity', array(0 => '') + $activities, '', "class='form-control chosen' onchange=getOutput(this)"));
        }
        else
        {
            die(html::select("activity[$i]", array(0 => '') + $activities, '', "class='form-control chosen' onchange=getOutput(this)"));
        }
    }

    /**
     * Ajax getCheckList
     * @param  int $auditplan
     * @return void
     */
    public function ajaxGetCheckList($auditplanID)
    {
        if($auditplanID)
        {
            $auditplan = $this->auditplan->getByID($auditplanID);
            $checkList = $this->auditplan->getCheckList($auditplan->objectType, $auditplan->objectID);
            foreach($checkList as $id => $check) $checkList[$id] = $check->title;
            die(html::select('listID', array(0 => '') + $checkList, '', "class='form-control chosen'"));
        }
        else
        {
            die(html::select('listID', array(0 => ''), '', "class='form-control chosen'"));
        }
    }

    /**
     * Ajax get output.
     *
     * @param  int    $projectID
     * @param  int    $activityID
     * @param  int    $i
     * @access public
     * @return void
     */
    public function ajaxGetOutput($projectID = 0, $activityID = 0, $i = 0)
    {
        $outputs = $this->dao->select('t1.id, t1.name')->from(TABLE_ZOUTPUT)->alias('t1')
            ->leftJoin(TABLE_PROGRAMOUTPUT)->alias('t2')->on('t1.id=t2.output')
            ->where('t1.activity')->eq($activityID)
            ->andWhere('t2.result')->eq('yes')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchPairs();

        foreach($outputs as $id => $output) $outputs[$id] = $this->lang->auditplan->zoutput . '：' . $output;
        if($i == 0)
        {
            die(html::select('output', array(0 => '') + $outputs, '', "class='form-control chosen'"));
        }
        else
        {
            die(html::select("output[$i]", array(0 => '') + $outputs, '', "class='form-control chosen'"));
        }
    }

    /**
     * Ajax get output.
     *
     * @param  int    $projectID
     * @param  int    $activityID
     * @param  int    $i
     * @access public
     * @return void
     */
    public function ajaxGetAuditplan($projectID = 0, $executionID = 0)
    {
        $auditplans = $this->dao->select('id')->from(TABLE_AUDITPLAN)
            ->where('deleted')->eq(0)
            ->beginIf($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIf($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->andWhere('status')->eq('wait')
            ->fetchPairs();
        $execution = $this->loadModel('execution')->getByID($executionID);
        foreach($auditplans as $id => $auditplan) $auditplans[$id] = $this->lang->auditplan->common .' - ' . $auditplan;
        die(html::select('auditplan', array(0 => '') + $auditplans, '', "class='form-control chosen' onchange=changeCheckList(this)"));
    }
}
