<?php
/**
 * The control file of gapanalysis module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     gapanalysis
 * @version     $Id: control.php 5107 2021-05-28 13:40:12Z hfz $
 * @link        https://www.zentao.net
 */
class gapanalysis extends control
{
    /**
     * Browse gapanalysises.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $browseType = 'all', $param = '', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project')->setMenu($projectID);

        $uri = $this->app->getURI(true);
        $this->session->set('gapanalysisList', $uri, 'project');
        $this->session->set('project', $projectID);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('gapanalysis', 'browse', "projectID=$projectID&browseType=bysearch&queryID=myQueryID");
        $this->gapanalysis->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $gapanalysises = $this->gapanalysis->getList($projectID, $browseType, $param, $orderBy, $pager);

        foreach($gapanalysises as $gapanalysis) $gapanalysisesIdList[] = $gapanalysis->id;
        $this->session->set('gapanalysisesIdList', isset($gapanalysisesIdList) ? $gapanalysisesIdList : '');

        $this->view->title         = $this->lang->gapanalysis->common . $this->lang->hyphen . $this->lang->gapanalysis->browse;
        $this->view->gapanalysises = $this->gapanalysis->getList($projectID, $browseType, $param, $orderBy, $pager);
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;
        $this->view->orderBy       = $orderBy;
        $this->view->projectID     = $projectID;
        $this->view->project       = $this->project->fetchByID($projectID);
        $this->view->pager         = $pager;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Create a gapanalysis.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $gapanalysisID = $this->gapanalysis->create($projectID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(!$gapanalysisID)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('gapanalysis', $gapanalysisID, 'Opened');

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $gapanalysisID));

            $response['locate'] = inlink('browse', "projectID=$projectID");
            return $this->send($response);
        }

        $members = $this->gapanalysis->getAnalyzableMembers($projectID);

        $rolePairs      = array();
        $teamMemberList = $this->project->getTeamMembers($projectID);
        foreach($teamMemberList as $account => $memberInfo) $rolePairs[$account] = $memberInfo->role;

        $this->view->title = $this->lang->gapanalysis->common . $this->lang->hyphen . $this->lang->gapanalysis->create;

        $this->view->members   = $members;
        $this->view->rolePairs = $rolePairs;
        $this->display();
    }

    /**
     * Batch create gapanalysises.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchCreate($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $gapanalysisList = $this->gapanalysis->batchCreate($projectID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $gapanalysisList));

            $response['locate'] = inlink('browse', "projectID=$projectID");
            return $this->send($response);
        }

        $members = $this->gapanalysis->getAnalyzableMembers($projectID);

        $rolePairs      = array();
        $teamMemberList = $this->project->getTeamMembers($projectID);
        foreach($teamMemberList as $account => $memberInfo) $rolePairs[$account] = $memberInfo->role;

        $this->view->title = $this->lang->gapanalysis->common . $this->lang->hyphen . $this->lang->gapanalysis->batchCreate;

        $this->view->members   = $members;
        $this->view->rolePairs = $rolePairs;

        $this->display();
    }

    /**
     * Edit a gapanalysis.
     *
     * @param  int    $gapanalysisID
     * @access public
     * @return void
     */
    public function edit($gapanalysisID)
    {
        $gapanalysis = $this->gapanalysis->getById($gapanalysisID);
        $this->loadModel('project')->setMenu($gapanalysis->project);

        if($_POST)
        {
            $changes = $this->gapanalysis->update($gapanalysisID);

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
                $actionID = $this->action->create('gapanalysis', $gapanalysisID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['locate'] = inlink('view', "gapanalysisID=$gapanalysisID");
            return $this->send($response);
        }

        $this->view->title      = $this->lang->gapanalysis->common . $this->lang->hyphen . $this->lang->gapanalysis->edit;
        $this->view->position[] = $this->lang->gapanalysis->edit;

        $this->view->gapanalysis = $gapanalysis;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->display();
    }

    /**
     * Batch edit gapanalysis.
     *
     * @param  int      $projectID
     * @access public
     * @return void
     */
    public function batchEdit($projectID)
    {
        $this->loadModel('project')->setMenu($projectID);

         if($this->post->gapanalysisIdList)
         {
             $allChanges = $this->gapanalysis->batchUpdate();
             if($allChanges)
             {
                 foreach($allChanges as $gapanalysisID => $changes)
                 {
                     if(empty($changes)) continue;

                     $actionID = $this->loadModel('action')->create('gapanalysis', $gapanalysisID, 'Edited');
                     $this->action->logHistory($actionID, $changes);
                 }
             }

             die(js::locate($this->session->gapanalysisList, "parent"));
         }

         $gapanalysisIDList = $this->post->gapanalysisIDList ? $this->post->gapanalysisIDList : die(js::locate($this->session->gapanalysisList));
         $gapanalysisIDList = array_unique($gapanalysisIDList);

         $this->view->title         = $this->lang->gapanalysis->common . $this->lang->hyphen . $this->lang->gapanalysis->batchEdit;
         $this->view->projectID     = $projectID;
         $this->view->users         = $this->loadModel('user')->getPairs('noletter|noclosed');
         $this->view->gapanalysises = $this->gapanalysis->getByList($gapanalysisIDList);

         $this->display();
    }

    /**
     * Delete a gapanalysis.
     *
     * @param  int    $gapanalysisID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($gapanalysisID, $confirm = 'no')
    {
        $gapanalysis = $this->gapanalysis->getById($gapanalysisID);
        $trainplans  = $this->dao->select('*')->from(TABLE_TRAINPLAN)->where('project')->eq($gapanalysis->project)->andWhere('trainee')->like("%,$gapanalysis->account%")->andWhere('status')->eq('wait')->fetchAll('id');

        if($confirm == 'no')
        {
            $message = $trainplans ? $this->lang->gapanalysis->confirmSyncTrainPlan : $this->lang->gapanalysis->confirmDelete;

            die(js::confirm($message, $this->createLink('gapanalysis', 'delete', "gapanalysis=$gapanalysisID&confirm=yes"), ''));
        }
        else
        {
            $this->gapanalysis->delete(TABLE_GAPANALYSIS, $gapanalysisID);

            if(!dao::isError() and $trainplans)
            {
                foreach($trainplans as $trainplanID => $oldTrainplan)
                {
                    $this->dao->update(TABLE_TRAINPLAN)->set("`trainee` = REPLACE(`trainee`, ',{$gapanalysis->account}', '')")->where('id')->eq($trainplanID)->exec();
                    $trainplan = $this->loadModel('trainplan')->getById($trainplanID);
                    $changes   = common::createChanges($oldTrainplan, $trainplan);

                    if(empty($changes)) continue;
                    $actionID = $this->loadModel('action')->create('trainplan', $trainplanID, 'UpdateTrainee');
                    $this->action->logHistory($actionID, $changes);
                }
            }

            die(js::locate(inlink('browse', "projectID=$gapanalysis->project"), 'parent'));
        }
    }

    /**
     * View a gapanalysis.
     *
     * @param  int    $gapanalysisID
     * @access public
     * @return void
     */
    public function view($gapanalysisID)
    {
        $gapanalysisID = (int)$gapanalysisID;
        $gapanalysis   = $this->gapanalysis->getById($gapanalysisID);
        if(empty($gapanalysis)) die(js::error($this->lang->notFound) . js::locate('back'));
        $this->loadModel('project')->setMenu($gapanalysis->project);

        $this->view->title       = $this->lang->gapanalysis->common . $this->lang->hyphen . $this->lang->gapanalysis->view;
        $this->view->position[]  = $this->lang->gapanalysis->view;
        $this->view->gapanalysis = $gapanalysis;
        $this->view->actions     = $this->loadModel('action')->getList('gapanalysis', $gapanalysisID);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->preAndNext  = $this->gapanalysis->getPreAndNext('gapanalysisesIdList', $gapanalysisID, 'gapanalysis', 'role');

        $this->display();
    }
}
