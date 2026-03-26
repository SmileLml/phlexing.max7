<?php
/**
 * The control file of reviewissue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     reviewissue
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        https://www.zentao.net
 */
class reviewissue extends control
{
    /**
     * Reviewissue Common action.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function commonAction($projectID)
    {
        $this->loadModel('project')->setMenu($projectID);
    }

    /**
     * Get list of issues.
     *
     * @param  int    $projectID
     * @param  int    $reviewID
     * @param  string $status
     * @param  string $orderBy
     * @param  string $browseType
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function issue($projectID = 0, $reviewID = 0, $status = 'active', $orderBy = 'id_desc', $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->loadModel('project')->setMenu($projectID);

        $project = $this->project->fetchByID($projectID);
        $this->lang->pageActions = common::canModify('project', $project) && common::hasPriv('reviewissue', 'create') ? html::a($this->createLink('reviewissue', 'create', "project=$projectID"), '<i class="icon icon-sm icon-plus"></i>' . $this->lang->reviewissue->create, '', 'class="btn btn-primary"') : '';

        $browseType = strtolower($browseType);
        $queryID    = $browseType == 'bysearch' ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get issueList and reviewInfo. */
        if($browseType != 'bysearch')
        {
            $issueList = $this->reviewissue->getList($projectID, $reviewID, $status, $orderBy, $pager);
        }
        else
        {
            $issueList = $this->reviewissue->getBySearch($projectID, $reviewID, $queryID, $orderBy, $pager);
        }
        $reviewInfo = empty($reviewID) ? array() : $this->loadModel('review')->getByID($reviewID);

        $actionURL = $this->createLink('reviewissue', 'issue', "projectID=$projectID&reviewID=$reviewID&status=$status&orderBy=$orderBy&browseType=bysearch&param=myQueryID");
        $this->reviewissue->buildSearchForm($projectID, $param, $actionURL);

        $this->view->title      = $this->lang->reviewissue->issueBrowse;
        $this->view->issueList  = $issueList;
        $this->view->reviewInfo = $reviewInfo;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager      = $pager;
        $this->view->projectID  = $projectID;
        $this->view->reviewID   = $reviewID;
        $this->view->status     = $status;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->display();
    }

    /**
     * Confirm the problem is resolved.
     *
     * @param  int $issueID
     * @param  string $status
     * @access public
     * @return void
     */
    public function updateStatus($issueID, $status)
    {
        $this->reviewissue->updateStatus($issueID, $status);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->saveStatusAction($issueID, $status);
    }

    /**
     *  Keep a record of the problem.
     *
     *  @param  int    $issueID
     *  @param  string $status
     *  @param  bool   $send
     *  @access public
     *  @return void
     */
    public function saveStatusAction($issueID, $status, $send = true)
    {
        /* Set action and get issue. */
        if($status == 'active')   $action = 'activated';
        if($status == 'resolved') $action = 'resolved';
        if($status == 'closed')   $action = 'closed';
        $issue = $this->reviewissue->getByID($issueID);

        $this->loadModel('action')->create('reviewissue', $issueID, $action, $issue->title);
        if($send) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('reviewissue', 'issue')));
    }

    /**
     *  Choose a solution to the problem.
     *
     *  @param  int $projectID
     *  @param  int $issueID
     *  @access public
     *  @return void
     */
    public function resolved($projectID, $issueID)
    {
        if($_POST)
        {
            $oldIssue = $this->reviewissue->getByID($issueID);

            $data = fixer::input('post')->get();
            $this->reviewissue->updateStatus($issueID, 'resolved', $data->resolution);
            if(dao::isError()) return print(js::error(dao::getError()));

            $issue = $this->reviewissue->getByID($issueID);

            $changes  = common::createChanges($oldIssue, $issue);
            $actionID = $this->loadModel('action')->create('reviewissue', $issueID, 'resolved', '', $issue->resolution);
            if($changes) $this->action->logHistory($actionID, $changes);

            return print(js::closeModal('parent.parent'));
        }

        $issue = $this->reviewissue->getByID($issueID);

        $this->view->title   = $this->lang->reviewissue->resolved;
        $this->view->issue   = $issue;
        $this->view->issueID = $issueID;

        $this->display();
    }

    /**
     * Reviewing add issue.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($projectID)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $issueID = $this->reviewissue->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('reviewissue', $issueID, 'opened', $this->post->opinion);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('reviewissue', 'issue', "project=$projectID")));
        }

        $reviews    = $this->reviewissue->getReviewList($projectID, array('fail', 'reviewing'), array('fail', 'needfix'), 'id_desc');
        $reviewID   = isset($reviews[0]) ? $reviews[0]->id : 0;
        $reviewList = array();
        foreach($reviews as $review) $reviewList[$review->id] = $review->title;

        $this->view->title      = $this->lang->reviewissue->create;
        $this->view->reviewList = $reviewList;
        $this->view->category   = $this->reviewissue->getReviewCategory($reviewID);
        $this->display();
    }

    /**
     * Reviewing edit issue.
     *
     * @param  int    $projectID
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function edit($projectID, $issueID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $changes = $this->reviewissue->update($issueID);
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('reviewissue', $issueID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('reviewissue', 'issue', "project=$projectID")));
        }

        $issue      = $this->reviewissue->getByID($issueID);
        $reviews    = $this->reviewissue->getReviewList($projectID, array('pass', 'fail', 'reviewing'), array('pass', 'fail', 'needfix'), 'id_desc');
        $reviewList = array();
        foreach ($reviews as $review) $reviewList[$review->id] = $review->title;

        $this->view->title      = $this->lang->reviewissue->edit;
        $this->view->issue      = $issue;
        $this->view->stages     = arrayUnion(array(''), $this->reviewissue->getReviewStage($issue->review));
        $this->view->reviewList = $reviewList;
        $this->display();
    }

    /**
     * Stage of project review.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function ajaxGetInjection($reviewID)
    {
        $stages = $this->reviewissue->getReviewStage($reviewID);
        die(html::select('injection', $stages, '', "class='form-control chosen'"));
    }

    /**
     * Access to review category.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function ajaxGetCategory($reviewID)
    {
        $category = $this->reviewissue->getReviewCategory($reviewID);
        die(html::select('category', $category, '', "class='form-control chosen' onchange='findCheck()'"));
    }

    /**
     * Access to review checklists.
     *
     * @param  int    $reviewID
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxGetCheck($reviewID = 0, $type = '')
    {
        $checkList = $this->reviewissue->getReviewCheck($reviewID, $type);
        die(html::select('listID', array(0 => '') + $checkList, '', "class='form-control chosen'"));
    }

    /**
     * Issue details.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function view($issueID)
    {
        $issue = $this->reviewissue->getByID($issueID);
        $this->commonAction($issue->project);

        $this->view->title     = $this->lang->reviewissue->issueInfo;
        $this->view->issue     = $this->reviewissue->getByID($issueID);
        $this->view->projectID = $issue->project;
        $this->view->issueID   = $issueID;
        $this->view->actions   = $this->loadModel('action')->getList('reviewissue', $issueID);
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Get review records
     *
     * @param  int    $projectID
     * @param  string $reviewID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function ajaxGetReview($projectID, $reviewID, $browseType)
    {
        echo $this->reviewissue->getReviewRecord($projectID, $reviewID, $browseType);
    }

    /**
     * Delete review issue
     *
     * @param  int    $issueID
     * @param  int    $projectID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($issueID, $projectID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->reviewissue->confirmDelete, inlink('delete', "issueID=$issueID&projectID=$projectID&confirm=yes")));
        }
        else
        {
            $this->reviewissue->delete(TABLE_REVIEWISSUE, $issueID);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'message' => $this->lang->saveSuccess));

            $locateLink = inlink('issue', "project=$projectID");
            return print(js::locate($locateLink, 'parent'));
        }
    }
}
