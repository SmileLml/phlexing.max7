<?php
/**
 * The control file of trainplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     trainplan
 * @version     $Id: control.php 5107 2021-05-28 09:06:12Z hfz $
 * @link        https://www.zentao.net
 */
class trainplan extends control
{
    /**
     * Browse trainplans.
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
    public function browse($projectID = 0, $browseType = 'all', $param = '', $orderBy = 'begin_asc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project')->setMenu($projectID);

        $uri = $this->app->getURI(true);
        $this->session->set('trainplanList', $uri, 'project');
        $this->session->set('project', $projectID);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('trainplan', 'browse', "projectID=$projectID&browseType=bysearch&queryID=myQueryID");
        $this->trainplan->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $users      = $this->loadModel('user')->getPairs('noletter');
        $trainplans = $this->trainplan->getList($projectID, $browseType, $param, $orderBy, $pager);
        $trainees   = array();
        foreach($trainplans as $trainplanID => $trainplan)
        {
            $trainplan->trainee = explode(',', $trainplan->trainee);
            $trainees[$trainplanID] = '';
            foreach($trainplan->trainee as $trainee) $trainees[$trainplanID] .= zget($users, $trainee) . ' ';
        }

        $this->view->title      = $this->lang->trainplan->common . $this->lang->hyphen . $this->lang->trainplan->browse;
        $this->view->position[] = $this->lang->trainplan->browse;
        $this->view->trainplans = $trainplans;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->projectID  = $projectID;
        $this->view->project    = $this->project->fetchByID($projectID);
        $this->view->pager      = $pager;
        $this->view->users      = $users;
        $this->view->trainees   = $trainees;

        $this->display();
    }

    /**
     * Create a trainplan.
     *
     * @param  int  $projectID
     * @access public
     * @return void
     */
    public function create($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $trainplanID = $this->trainplan->create($projectID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(!$trainplanID)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('trainplan', $trainplanID, 'Opened');

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $trainplanID));

            $response['locate']  = inlink('browse', "projectID=$projectID");
            return $this->send($response);
        }

        $this->view->title      = $this->lang->trainplan->common . $this->lang->hyphen . $this->lang->trainplan->create;
        $this->view->position[] = $this->lang->trainplan->create;

        $this->view->members = $this->trainplan->getTrainMembers($projectID);
        $this->display();
    }

    /**
     * Batch create trainplans.
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
            $trainplanList = $this->trainplan->batchCreate($projectID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $trainplanList));

            $response['locate'] = inlink('browse', "projectID=$projectID");
            return $this->send($response);
        }

        $this->view->title = $this->lang->trainplan->common . $this->lang->hyphen . $this->lang->trainplan->batchCreate;

        $this->view->members = $this->trainplan->getTrainMembers($projectID);
        $this->display();
    }

    /**
     * Edit a trainplan.
     *
     * @param  int    $trainplanID
     * @access public
     * @return void
     */
    public function edit($trainplanID)
    {
        $trainplan = $this->trainplan->getById($trainplanID);
        $this->loadModel('project')->setMenu($trainplan->project);

        if($_POST)
        {
            $changes = $this->trainplan->update($trainplanID);

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
                $actionID = $this->action->create('trainplan', $trainplanID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['locate'] = inlink('view', "trainplanID=$trainplanID");
            return $this->send($response);
        }

        $this->view->title      = $this->lang->trainplan->common . $this->lang->hyphen . $this->lang->trainplan->edit;
        $this->view->position[] = $this->lang->trainplan->edit;

        $this->view->trainplan = $trainplan;
        $this->view->members   = $this->trainplan->getTrainMembers($trainplan->project);
        $this->display();
    }

    /**
     * Batch edit trainplans.
     *
     * @param  int      $projectID
     * @access public
     * @return void
     */
    public function batchEdit($projectID)
    {
        $this->loadModel('project')->setMenu($projectID);

         if($this->post->name)
         {
             $allChanges = $this->trainplan->batchUpdate();
             if(dao::isError())
             {
                 $response['result']  = 'fail';
                 $response['message'] = dao::getError();
                 return $this->send($response);
             }

             if($allChanges)
             {
                 foreach($allChanges as $trainplanID => $changes)
                 {
                     if(empty($changes)) continue;

                     $actionID = $this->loadModel('action')->create('trainplan', $trainplanID, 'Edited');
                     $this->action->logHistory($actionID, $changes);
                 }
             }

             die(js::locate($this->session->trainplanList, "parent"));
         }

         $trainplanIDList = $this->post->trainplanIDList ? $this->post->trainplanIDList : die(js::locate($this->session->trainplanList));
         $trainplanIDList = array_unique($trainplanIDList);

         $trainplanList = $this->trainplan->getByList($trainplanIDList);
         foreach($trainplanIDList as $trainplanID)
         {
              if($trainplanList[$trainplanID]->status == 'done') unset($trainplanList[$trainplanID]);
         }

         if(empty($trainplanList)) die(js::alert($this->lang->trainplan->notAllowedEdit) . js::locate('back'));

         $this->view->title      = $this->lang->trainplan->common . $this->lang->hyphen . $this->lang->trainplan->batchEdit;
         $this->view->projectID  = $projectID;
         $this->view->members    = $this->trainplan->getTrainMembers($projectID);
         $this->view->trainplans = $trainplanList;

         $this->display();
    }

    /**
     * Delete a trainplan.
     *
     * @param  int    $trainplanID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($trainplanID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->trainplan->confirmDelete, $this->createLink('trainplan', 'delete', "trainplan=$trainplanID&confirm=yes"), ''));
        }
        else
        {
            $projectID = $this->dao->select('project')->from(TABLE_TRAINPLAN)->where('id')->eq($trainplanID)->fetch('project');
            $this->trainplan->delete(TABLE_TRAINPLAN, $trainplanID);

            die(js::locate(inlink('browse', "projectID=$projectID"), 'parent'));
        }
    }

    /**
     * View a trainplan.
     *
     * @param  int    $trainplanID
     * @access public
     * @return void
     */
    public function view($trainplanID)
    {
        $trainplanID = (int)$trainplanID;
        $trainplan   = $this->trainplan->getById($trainplanID);
        if(empty($trainplan)) die(js::error($this->lang->notFound) . js::locate($this->createLink('project', 'browse'), '$.apps.open'));
        $this->loadModel('project')->setMenu($trainplan->project);

        $users      = $this->loadModel('user')->getPairs('noletter');
        $trainees   = '';
        $trainplan->trainee = explode(',', $trainplan->trainee);
        foreach($trainplan->trainee as $trainee) $trainees .= zget($users, $trainee) . ' ';

        $this->view->title      = $this->lang->trainplan->common . $this->lang->hyphen . $this->lang->trainplan->view;
        $this->view->position[] = $this->lang->trainplan->view;
        $this->view->trainplan  = $trainplan;
        $this->view->trainees   = $trainees;
        $this->view->actions    = $this->loadModel('action')->getList('trainplan', $trainplanID);
        $this->view->users      = $users;

        $this->display();
    }

    /**
     * Finish a trainplan.
     *
     * @param  int    $trainplanID
     * @access public
     * @return void
     */
    public function finish($trainplanID)
    {
        $trainplan = $this->trainplan->getById($trainplanID);

        if($_POST)
        {
            $changes = $this->trainplan->finish($trainplanID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('trainplan', $trainplanID, 'Finished', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->trainplan->common . $this->lang->hyphen . $this->lang->trainplan->finish;
        $this->view->position[] = $this->lang->trainplan->finish;

        $this->view->trainplan  = $trainplan;
        $this->display();
    }

    /**
     * Batch finish trainplans.
     *
     * @access public
     * @return void
     */
    public function batchFinish()
    {
        if($this->post->trainplanIDList)
        {
            $trainplanIDList = $this->post->trainplanIDList;
            if($trainplanIDList) $trainplanIDList = array_unique($trainplanIDList);

            $this->loadModel('action');
            $trainplanIDList = implode(',', $trainplanIDList);
            $trainplans = $this->trainplan->getByList($trainplanIDList);
            foreach($trainplans as $trainplanID => $trainplan)
            {
                if($trainplan->status == 'done') continue;

                $changes = $this->trainplan->finish($trainplanID);
                if($changes)
                {
                    $actionID = $this->action->create('trainplan', $trainplanID, 'finished');
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }
        die(js::reload('parent'));
    }

    /**
     * Commit a summary of trainplan.
     *
     * @param  int    $trainplanID
     * @access public
     * @return void
     */
    public function summary($trainplanID)
    {
        $trainplan = $this->trainplan->getById($trainplanID);

        if($_POST)
        {
            $changes = $this->trainplan->summary($trainplanID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('trainplan', $trainplanID, 'CommitSummary');
                $this->action->logHistory($actionID, $changes);
            }

            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->trainplan->common . $this->lang->hyphen . $this->lang->trainplan->finish;
        $this->view->position[] = $this->lang->trainplan->finish;

        $this->view->trainplan = $trainplan;
        $this->view->actions   = $this->loadModel('action')->getList('trainplan', $trainplanID);
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }
}
