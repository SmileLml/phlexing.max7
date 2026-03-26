<?php
/**
 * The control file of nc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     nc
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class nc extends control
{
    public function commonAction($projectID, $from = 'project')
    {
        if($from == 'project' || $from == 'execution') $this->loadModel($from)->setMenu($projectID);
        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            if($execution->attribute != 'dev' && !empty($execution->attribute)) $this->locate($this->createLink('execution', 'task', "taskID=$execution->id"));
        }
    }

    /**
     * Browse no conformity.
     *
     * @param  int    $projectID
     * @param  string $from
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $from = 'project', $browseType = 'unclosed', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('datatable');
        $this->commonAction($projectID, $from);

        $this->session->set('ncList', $this->app->getURI(true), $this->app->tab);
        $browseType = strtolower($browseType);
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $objectID = $projectID;
        if($this->app->tab == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $objectID  = $execution->project;
            $ncs = $this->nc->getNcs($execution->project, $browseType, $param, $orderBy, $pager, $projectID);
        }
        else
        {
            $ncs = $this->nc->getNcs($projectID, $browseType, $param, $orderBy, $pager);
        }

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'nc', false);

        /* Build the search form. */
        $queryID   = $browseType == 'bysearch' ? (int)$param : 0;
        $actionURL = $this->createLink('nc', 'browse', "project=$projectID&from=$from&browseType=bySearch&param=myQueryID");
        $this->nc->buildSearchForm($objectID, $queryID, $actionURL);

        $this->view->title      = $this->lang->nc->browse;
        $this->view->position[] = $this->lang->nc->browse;
        $this->view->ncs        = $ncs;
        $this->view->from       = $from;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->projectID  = $projectID;
        $this->view->project    = $this->loadModel('project')->fetchById($projectID);
        $this->view->browseType = $browseType;
        $this->view->activities = $this->loadModel('pssp')->getActivityPairs($this->session->project);
        $this->view->outputs    = $this->pssp->getOutputPairs($this->session->project);
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->param      = $param;
        $this->display();
    }

    /**
     * Create no conformity.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function create($projectID = 0, $from = 'project')
    {
        $this->app->loadLang('auditplan');
        $this->commonAction($projectID, $from);
        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
            $this->view->executionID = $execution->id;
        }

        if($_POST)
        {
            $this->nc->create($_POST['auditplan'], $projectID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('auditplan', $_POST['auditplan'], 'Checked');
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->session->ncList;
            $this->send($response);
        }

        $executionList = array(0 => '') + $this->loadModel('execution')->getPairs($projectID, 'sprint,stage', 'all');

        $this->view->title      = $this->lang->nc->create;
        $this->view->executions = $executionList;
        $this->view->projectID  = $projectID;
        $this->view->project    = $this->loadModel('project')->fetchByID($projectID);
        $this->view->users      = $this->project->getTeamMemberPairs($projectID);

        $this->display();
    }

    /**
     * Edit no conformity.
     *
     * @param  int    $ncID
     * @param  string $from
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function edit($ncID, $from = 'project', $projectID = 0)
    {
        $this->commonAction($projectID, $from);
        $nc = $this->nc->getByID($ncID);

        $auditplan  = $this->loadModel('auditplan')->getByID($nc->auditplan);
        $auditplans = $this->dao->select('id')->from(TABLE_AUDITPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('execution')->eq($auditplan->execution)
            ->andWhere('status')->eq('wait')
            ->orWhere('id')->eq($nc->auditplan)
            ->fetchPairs('id');

        foreach($auditplans as $id => $audit) $auditplans[$id] = $this->lang->auditplan->common .' - ' . $audit;

        $checkList = $this->auditplan->getCheckList($auditplan->objectType, $auditplan->objectID);
        foreach($checkList as $id => $check) $checkList[$id] = $check->title;

        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
        }

        if($_POST)
        {
            $changes = $this->nc->update($ncID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $actionID = $this->loadModel('action')->create('nc', $ncID, 'Edited');
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            if($nc->assignedTo != $this->post->assignedTo)
            {
                $nc = $this->nc->getByID($ncID);
                $this->action->create('nc', $ncID, 'Assigned', $this->post->comment, $this->post->assignedTo);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->session->ncList;
            return $this->send($response);
        }

        $executionList = array(0 => '') + $this->loadModel('execution')->getPairs($nc->project, 'sprint,stage', 'all');

        $this->view->nc         = $nc;
        $this->view->title      = $this->lang->nc->edit;
        $this->view->position[] = $this->lang->nc->edit;
        $this->view->users      = $this->loadModel('project')->getTeamMemberPairs($nc->project);
        $this->view->executions = $executionList;
        $this->view->auditplan  = $auditplan;
        $this->view->auditplans = $auditplans;
        $this->view->checkList  = $checkList;
        $this->view->project    = $this->loadModel('project')->fetchByID((int)$nc->project);
        $this->display();
    }

    /**
     * Resolve no conformity.
     *
     * @param  int    $ncID
     * @access public
     * @return void
     */
    public function resolve($ncID)
    {
        $nc = $this->nc->getByID($ncID);
        if($_POST)
        {
            $changes = $this->nc->resolve($ncID);

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('nc', $ncID, 'Resolved', '', $this->post->resolution);
                $this->action->logHistory($actionID, $changes);

                $response['result']  = 'success';
                $response['message'] = $this->lang->saveSuccess;
                $response['locate']  = 'parent';
                return $this->send($response);
            }

            $response['result']  = 'fail';
            $response['message'] = dao::getError();
            return $this->send($response);
        }

        $this->view->nc      = $nc;
        $this->view->actions = $this->loadModel('action')->getList('nc', $ncID);
        $this->view->users   = $this->loadModel('project')->getTeamMemberPairs($nc->project);
        $this->display();
    }

    /**
     * Activate a nc.
     *
     * @param  int    $ncID
     * @access public
     * @return void
     */
    public function activate($ncID)
    {
        $nc = $this->nc->getById($ncID);

        if($_POST)
        {
            $changes = $this->nc->activate($ncID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('nc', $ncID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('nc', 'browse', "projectID=$nc->project"), 'parent'));
        }

        $this->view->title      = $this->lang->nc->common . $this->lang->hyphen . $this->lang->nc->activate;
        $this->view->position[] = $this->lang->nc->activate;

        $this->view->users = $this->loadModel('project')->getTeamMemberPairs($nc->project);
        $this->view->nc    = $nc;

        $this->display();
    }

    /**
     * Update assign of nc.
     *
     * @param  int    $ncID
     * @access public
     * @return void
     */
    public function assignTo($ncID)
    {
        $nc = $this->nc->getByID($ncID);

        if($_POST)
        {
            $changes = $this->nc->assign($ncID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('nc', $ncID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->session->ncList, 'parent.parent'));
        }

        $this->view->title = $this->lang->nc->common . $this->lang->hyphen . $this->lang->nc->assignedTo;
        $this->view->nc    = $nc;
        $this->view->users = $this->loadModel('project')->getTeamMemberPairs($nc->project);

        $this->display();
    }

    /**
     * View no conformity.
     *
     * @param  int    $ncID
     * @param  string $from
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function view($ncID, $from = 'project', $projectID = 0)
    {
        $nc = $this->nc->getByID($ncID);
        if(!$projectID and $from == 'project') $projectID = $nc->project;
        if(!$projectID and $from == 'execution') $projectID = $nc->execution;
        $this->commonAction($projectID, $from);

        $auditplan = $this->loadModel('auditplan')->getByID($nc->auditplan);
        $execution = $this->loadModel('execution')->getByID($auditplan->execution);

        $this->view->title      = $this->lang->nc->edit;
        $this->view->nc         = $nc;
        $this->view->execution  = $execution;
        $this->view->projectID  = $projectID;
        $this->view->project    = $this->loadModel('project')->fetchByID((int)$nc->project);
        $this->view->from       = $from;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions    = $this->loadModel('action')->getList('nc', $ncID);
        $this->view->activities = $this->loadModel('pssp')->getActivityPairs($nc->project);
        $this->view->outputs    = $this->pssp->getOutputPairs($nc->project);
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('nc', $ncID);

        $this->display();
    }

    /**
     * Export nc.
     *
     * @param  int    $projectID
     * @param  string $from
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($projectID = 0, $from = 'project', $browseType = 'all', $orderBy = 'id_desc')
    {
        $project = $this->loadModel('project')->fetchByID($projectID);
        if($_POST)
        {
            $this->loadModel('file');
            $ncLang   = $this->lang->nc;
            $ncConfig = $this->config->nc;

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $ncConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($ncLang->$fieldName) ? $ncLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }
            if(empty($project->multiple)) unset($fields['execution']);

            /* Get ncs. */
            $sql = $this->session->ncQueryCondition;
            if($orderBy) $sql .= " ORDER BY " . strtr($orderBy, '_', ' ');

            $stmt       = $this->dbh->query($sql);
            $listIdList = array();
            while($row = $stmt->fetch())
            {
                $ncs[$row->id] = $row;

                if($row->listID) $listIdList[$row->listID] = $row->listID;
            }

            /* Get users, executions. */
            $this->app->loadLang('auditplan');

            $users = $this->loadModel('user')->getPairs('noletter');
            if($from == 'project')
            {
                $executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'all');
                $activities = $this->loadModel('pssp')->getActivityPairs($projectID);
                $outputs    = $this->pssp->getOutputPairs($projectID);
            }
            elseif($from == 'execution')
            {
                $execution  = $this->loadModel('execution')->getByID($projectID);
                $executions = !empty($project->multiple) ? $this->loadModel('execution')->getPairs($execution->project, 'all', 'all') : array();
                $activities = $this->loadModel('pssp')->getActivityPairs($execution->project);
                $outputs    = $this->pssp->getOutputPairs($execution->project);
            }

            $checkList = array();
            if($listIdList) $checkList = $this->dao->select('id,title')->from(TABLE_AUDITCL)->where('id')->in($listIdList)->fetchPairs('id', 'title');

            foreach($ncs as $nc)
            {
                if($this->post->fileType == 'csv')
                {
                    $nc->desc = str_replace("<br />", "\n", $nc->desc);
                    $nc->desc = str_replace('"', '""', $nc->desc);
                    $nc->desc = str_replace('&nbsp;', ' ', $nc->desc);
                }

                /* fill some field with useful value. */
                $nc->execution = !isset($executions[$nc->execution]) ? '' : $executions[$nc->execution] . "(#$nc->execution)";
                $nc->object    = $nc->objectType == 'activity' ? zget($activities, $nc->objectID) : zget($outputs, $nc->objectID);
                if($nc->auditplan) $nc->auditplan = $this->lang->auditplan->common .' - ' . $nc->auditplan;
                if($nc->listID)    $nc->listID    = zget($checkList, $nc->listID);

                if(isset($ncLang->severityList[$nc->severity]))     $nc->severity   = $ncLang->severityList[$nc->severity];
                if(isset($ncLang->typeList[$nc->type]))             $nc->type       = $ncLang->typeList[$nc->type];
                if(isset($ncLang->statusList[$nc->status]))         $nc->status     = $ncLang->statusList[$nc->status];
                if(isset($ncLang->resolutionList[$nc->resolution])) $nc->resolution = $ncLang->resolutionList[$nc->resolution];

                if(!empty($users[$nc->assignedTo]))   $nc->assignedTo  = zget($users, $nc->assignedTo) . "(#$nc->assignedTo)";
                if(!empty($users[$nc->createdBy]))    $nc->createdBy   = zget($users, $nc->createdBy);
                if(!empty($users[$nc->resolvedBy]))   $nc->resolvedBy  = zget($users, $nc->resolvedBy);
                if(!empty($users[$nc->activateBy]))   $nc->activateBy  = zget($users, $nc->activateBy);
                if(!empty($users[$nc->closedBy]))     $nc->closedBy    = zget($users, $nc->closedBy);

                if(helper::isZeroDate($nc->deadline))     $nc->deadline     = '';
                if(helper::isZeroDate($nc->resolvedDate)) $nc->resolvedDate = '';
                if(helper::isZeroDate($nc->activateDate)) $nc->activateDate = '';
                if(helper::isZeroDate($nc->closedDate))   $nc->closedDate   = '';

                $nc->title = htmlspecialchars_decode($nc->title, ENT_QUOTES);

                unset($nc->deleted);
            }

            $fieldWidths = array();
            $fieldWidths['object']      = '20';
            $fieldWidths['auditplan']   = '20';
            $fieldWidths['listID']      = '20';
            $fieldWidths['createdDate'] = '20';

            $this->post->set('fields', $fields);
            $this->post->set('rows', $ncs);
            $this->post->set('width', $fieldWidths);
            $this->post->set('kind', 'nc');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        unset($this->lang->exportTypeList['selected']);

        $fileName    = $this->lang->nc->common;
        $browseType  = zget($this->lang->nc->featureBar['browse'], $browseType, '');
        $fileName    = $project->name . $this->lang->dash . $browseType . $fileName;

        $this->view->title    = $this->lang->nc->export;
        $this->view->fileName = $fileName;
        $this->display();
    }

    /**
     * Close no conformity.
     *
     * @param  int    $ncID
     * @access public
     * @return void
     */
    public function close($ncID)
    {
        $nc = $this->nc->getByID($ncID);
        if($_POST)
        {
            $changes = $this->nc->close($ncID);

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('nc', $ncID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);

                $response['result']  = 'success';
                $response['message'] = $this->lang->saveSuccess;
                $response['locate']  = 'parent';
                return $this->send($response);
            }

            $response['result']  = 'fail';
            $response['message'] = dao::getError();
            return $this->send($response);
        }

        $this->view->nc      = $nc;
        $this->view->actions = $this->loadModel('action')->getList('nc', $ncID);
        $this->view->users   = $this->loadModel('project')->getTeamMemberPairs($nc->project);
        $this->display();
    }

    /**
     * Delete no conformity.
     *
     * @param  int    $ncID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($ncID, $from = 'project',$confirm = 'no')
    {
        $nc = $this->nc->getById($ncID);
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->nc->confirmDelete, $this->createLink('nc', 'delete', "ncID=$ncID&from=$from&confirm=yes"), '');
            exit;
        }
        else
        {
            $this->nc->delete(TABLE_NC, $ncID);

            die(js::locate($this->session->ncList, 'parent'));
        }
    }
}
