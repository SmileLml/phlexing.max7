<?php
/**
 * The control file of review module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     review
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class review extends control
{
    /**
     * Review Common action.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function commonAction($projectID)
    {
        $this->app->loadLang('baseline');
        if(!$projectID) $projectID = $this->loadModel('project')->checkAccess($projectID, $this->project->getPairsByProgram());

        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($projectID);

        $project = $this->loadModel('project')->getByID($projectID);
        $this->session->set('hasProduct', $project->hasProduct);
        $this->view->project = $project;
    }

    /**
     * 浏览评审列表。
     * Browse reviews.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     * @param int $param
     */
    public function browse($projectID = 0, $browseType = 'all', $orderBy = 't1.id_desc', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->commonAction($projectID);
        $this->loadModel('datatable');
        $this->session->set('reviewList', $this->app->getURI(true), 'project');
        $browseType = strtolower($browseType);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL  = $this->createLink('review', 'browse', "projectID=$projectID&browseType=bysearch&orderBy=$orderBy&param=myQueryID");
        $this->review->buildSearchForm($actionURL, $queryID, $projectID);

        $reviewList = $this->review->getList($projectID, $browseType, $orderBy, $pager, $queryID);

        $this->view->title = $this->lang->review->browse;

        $this->view->reviewList     = $reviewList;
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter|all');
        $this->view->pager          = $pager;
        $this->view->param          = $param;
        $this->view->recTotal       = $recTotal;
        $this->view->recPerPage     = $recPerPage;
        $this->view->pageID         = $pageID;
        $this->view->orderBy        = $orderBy;
        $this->view->browseType     = $browseType;
        $this->view->products       = $this->loadModel('product')->getPairs($projectID);
        $this->view->projectID      = $projectID;
        $this->view->pendingReviews = $this->loadModel('approval')->getPendingReviews('review');
        $this->view->reviewers      = $this->review->getReviewerByIdList(array_keys($reviewList));

        $this->display();
    }

    /**
     * Assess a review.
     *
     * @param  int    $reviewID
     * @param  string $from  work|contribute
     * @param  string $type  gantt|assignedTo
     * @access public
     * @return void
     */
    public function assess($reviewID = 0, $from = '', $type = 'gantt')
    {
        $this->config->morphUpdate = false;

        $this->loadModel('stage');
        $this->loadModel('project');

        $review = $this->review->getByID($reviewID);
        if(!$this->review->isClickable($review, 'assess')) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->review->cannotReview, 'locate' => $this->createLink('review', 'browse', "projectID={$review->project}"))));
        $this->commonAction($review->project);

        $this->reviewZen->processApprovalForView($reviewID);

        if($_POST)
        {
            $this->review->saveResult($reviewID, 'review');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $reviewList = $this->session->reviewList ? $this->session->reviewList : inlink('browse', "project=$review->project");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $reviewList));
        }

        if($this->app->tab == 'my')
        {
            if($from == 'contribute') $this->lang->my->menu->contribute['subModule'] = 'review';
        }

        $project      = $this->loadModel('project')->getByID($review->project);
        $projectModel = $project->model == 'ipd' ? 'waterfall' : $project->model;

        $this->setViewData($review, $type);
        $this->view->title        = $this->lang->review->common;
        $this->view->review       = $review;
        $this->view->object       = $review;
        $this->view->projectID    = $review->project;
        $this->view->result       = $this->review->getResultByUser($reviewID);
        $this->view->issues       = $this->loadmodel('reviewissue')->getIssueByReview($reviewID, $review->project);
        $this->view->reviewcl     = $this->loadModel('reviewcl')->getList($review->category, 'id_desc', null, $project->model);
        $this->view->categoryList = $this->lang->reviewcl->{$projectModel . 'CategoryList'};
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->reviewID     = $reviewID;
        $this->view->type         = $type;
        $this->view->setReviewer  = $this->loadModel('approval')->getNextPendingReviewer('review', $reviewID);
        $this->view->approval     = $this->approval->getByObject('review', $reviewID);
        $this->display();
    }

    /**
     * Set data to review page.
     *
     * @param  object $review
     * @param  string $type
     * @access public
     * @return void
     */
    public function setViewData($review, $type = '')
    {
        if(empty($review->template) && empty($review->doc) && $review->category == 'PP')
        {
            $selectCustom = 0;
            $dateDetails  = 1;
            if($review->category == 'PP')
            {
                $owner        = $this->app->user->account;
                $module       = 'programplan';
                $section      = 'browse';
                $object       = 'stageCustom';
                $selectCustom = $this->loadModel('setting')->getItem("owner={$owner}&module={$module}&section={$section}&key={$object}");

                if(strpos($selectCustom, 'date') !== false) $dateDetails = 0;
            }

            if($type == 'assignedTo')
            {
                $this->view->plans = $this->loadModel('programplan')->getDataForGanttGroupByAssignedTo($review->project, $review->product, 0, $selectCustom, false);
            }
            else
            {
                $this->view->plans = $this->loadModel('programplan')->getDataForGantt($review->project, $review->product, 0, $selectCustom, false);
            }

            $this->view->selectCustom = $selectCustom;
            $this->view->dateDetails  = $dateDetails;
        }
        else
        {
            if($review->doc) $this->view->doc = $this->loadModel('doc')->getById($review->doc, $review->docVersion);
            if(!$review->template) return;

            $template = $this->loadModel('doc')->getByID($review->template);
            if($template->type == 'book')
            {
                $this->view->bookID = $template->id;
                $this->view->book   = $template;
            }

            $this->view->template = $template;
        }
    }

    /**
     * Create a review.
     *
     * @param  int     $projectID
     * @param  string  $object
     * @param  int     $productID
     * @param  string  $reviewRange
     * @param  string  $checkedItem
     * @access public
     * @return void
     */
    public function create($projectID = 0, $object = '', $productID = 0, $reviewRange = 'all', $checkedItem = '')
    {
        $this->commonAction($projectID);

        if($_POST)
        {
            $this->review->create($projectID, $reviewRange, $checkedItem);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => inlink('browse', "project=$projectID")));
        }

        $libs       = $this->loadModel('doc')->getLibsByObject('project', $projectID);
        $executions = $this->dao->select('id,path,name')->from(TABLE_EXECUTION)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
        foreach($libs as $libID => $lib)
        {
            $libNamePrefix = '';
            if(!empty($lib->execution))
            {
                $execution     = $executions[$lib->execution];
                $executionPath = explode(',', trim($execution->path, ','));
                array_shift($executionPath); // Remove project id.

                foreach($executionPath as $path) if(isset($executions[$path])) $libNamePrefix .= $executions[$path]->name . '/';
            }

            $libs[$libID] = $libNamePrefix . $lib->name;
        }

        if(!$this->session->hasProduct) $productID = $this->loadModel('product')->getProductIDByProject($projectID);

        $this->view->title     = $this->lang->review->create;
        $this->view->object    = $object;
        $this->view->projectID = $projectID;
        $this->view->productID = $productID;
        $this->view->libs      = arrayUnion(array('' => ''), $libs);
        $this->view->products  = $this->loadModel('product')->getProductPairsByProject($projectID);
        $this->view->backLink  = $this->session->reviewList ? $this->session->reviewList : inlink('browse', "project=$projectID");
        $this->display();
    }

    /**
     * Edit a review.
     *
     * @param  int  $reviewID
     * @access public
     * @return void
     */
    public function edit($reviewID)
    {
        $review = $this->review->getByID($reviewID);
        $this->commonAction($review->project);

        if($_POST)
        {
            $changes = $this->review->update($reviewID);
            $files   = $this->loadModel('file')->saveUpload('review', $reviewID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes or $this->post->comment or !empty($files))
            {
                $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
                $actionID = $this->loadModel('action')->create('review', $reviewID, 'Edited', $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => inlink('browse', "project=$review->project")));
        }
        $fileList = array();
        foreach($review->files as $file)
        {
            $file->name = $file->title;
            $file->url  = $this->createLink('file', 'download', "fileID={$file->id}");
            $fileList[] = $file;
        }

        $this->view->title      = $this->lang->review->edit;
        $this->view->review     = $review;
        $this->view->project    = $this->loadModel('project')->getByID($review->project);
        $this->view->products   = $this->loadModel('product')->getProductPairsByProject($review->project);
        $this->view->members    = $this->project->getTeamMemberPairs($review->project);
        $this->view->fileList   = $fileList;
        $this->display();
    }

    /**
     * View a review.
     *
     * @param  int  $reviewID
     * @access public
     * @return void
     */
    public function view($reviewID)
    {
        $review = $this->review->getByID($reviewID);
        $this->commonAction($review->project);

        $approval = $this->loadModel('approval')->getByObject('review', $reviewID);
        $results  = $this->approval->getNodeReviewers($approval->id);

        $reviewerResult = array();
        foreach($results as $nodeList)
        {
            if(empty($nodeList['reviewers'])) continue;

            foreach($nodeList['reviewers'] as $user)
            {
                if(strpos($user['account'], 'pending-') !== false) continue;
                if(empty($reviewerResult[$user['account']])) $reviewerResult[$user['account']] = array();
                array_push($reviewerResult[$user['account']], $user['result']);
            }
        }

        $this->setViewData($review, 'gantt');
        $this->view->title          = $this->lang->review->view;
        $this->view->review         = $review;
        $this->view->object         = $review;
        $this->view->actions        = $this->loadModel('action')->getList('review', $reviewID);
        $this->view->approval       = $approval;
        $this->view->projectID      = $review->project;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->pendingReviews = $this->approval->getPendingReviews('review');
        $this->view->reviewers      = $this->review->getReviewerByIdList($reviewID);
        $this->view->reviewerResult = $reviewerResult;
        $this->display();
    }

    /**
     * 删除一个评审。
     * Delete a review.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function delete($reviewID)
    {
        $review = $this->review->getByID($reviewID);
        $this->review->delete(TABLE_REVIEW, $reviewID);
        if(dao::isError()) return $this->sendError(dao::getError());

        $reviewIssues = $this->loadModel('reviewissue')->getIssueByReview($reviewID, $review->project, 'review', '', '');
        if(!empty($reviewIssues))
        {
            foreach($reviewIssues as $reviewIssue) $this->reviewissue->delete(TABLE_REVIEWISSUE, $reviewIssue->id);
        }
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }


    /**
     * 提交一个评审。
     * Submit a review.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function submit($reviewID)
    {
        $review = $this->review->getByID($reviewID);

        if($_POST)
        {
            $changes = $this->review->submit($reviewID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('review', $reviewID, 'Submit', $this->post->comment);
            if($changes) $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $this->view->title      = $this->lang->review->submit;
        $this->view->position[] = $this->lang->review->submit;
        $this->view->review     = $review;
        $this->view->actions    = $this->loadModel('action')->getList('review', $reviewID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->members    = $this->loadModel('project')->getTeamMemberPairs($review->project);
        $this->display();
    }

    /**
     * Recall a review.
     *
     * @param  int 	   $reviewID
     * @access public
     * @return void
     */
    public function recall($reviewID)
    {
        $this->dao->update(TABLE_REVIEW)->set('status')->eq('draft')->where('id')->eq($reviewID)->exec();
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create('review', $reviewID, 'Recall');
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('approval')->cancel('review', $reviewID);
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Review report.
     *
     * @param  int  $reviewID
     * @param  int  $approvalID
     * @access public
     * @return void
     */
    public function report($reviewID, $approvalID = 0)
    {
        $this->app->loadLang('baseline');
        $review         = $this->review->getByID($reviewID);
        $approvalIDList = $this->loadModel('approval')->getApprovalIDByObjectID($reviewID, 'review');
        $approvalID     = empty($approvalID) ? end($approvalIDList) : $approvalID;
        $approvalNode   = $this->loadModel('approval')->getApprovalNodeByApprovalID($approvalID);
        $this->loadModel('project')->setMenu($review->project);

        /* Get reviewers. */
        $reviewers = array();
        foreach($approvalNode as $node)
        {
            if(!empty($node->reviewedBy) and !in_array($node->reviewedBy, $reviewers))
            {
                $reviewers[] = $node->reviewedBy;
            }
        }

        $this->view->reviewID        = $reviewID;
        $this->view->title           = $this->lang->review->submit;
        $this->view->review          = $review;
        $this->view->objectScale     = $this->review->getObjectScale($review);
        $this->view->results         = $this->review->getResultByUserList($reviewID);
        $this->view->issues          = $this->loadModel('reviewissue')->getIssueByReview($reviewID, $review->project, 'review', 'all', 'all', $approvalID);
        $this->view->users           = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->approvalNode    = $approvalNode;
        $this->view->reviewer        = $reviewers;
        $this->view->reviewerCount   = count($reviewers);
        $this->view->approvalIDList  = $approvalIDList;
        $this->view->approvalID      = $approvalID;
        $this->view->approval        = $this->loadModel('approval')->getByID($approvalID);
        $this->view->efforts         = $this->loadModel('effort')->getByObject('review', $reviewID, 'id', $approvalID);
        $this->view->projectID       = $review->project;

        $this->display();
    }

    /**
     * 导出评审报告。
     * Export review report.
     *
     * @param  int  $reviewID
     * @param  int  $approvalID
     * @access public
     * @return void
     */
    public function exportReport($reviewID, $approvalID = 0)
    {
        if($_POST)
        {
            $review = $this->review->getByID($reviewID);
            $data   = fixer::input('post')->get();
            if(empty($data->fileName)) $_POST['fileName'] = $this->lang->review->untitled;

            if($data->fileType == 'word')
            {
                $this->reviewZen->exportWord($review, $approvalID, $data);
            }
            elseif($data->fileType == 'html')
            {
                $this->reviewZen->exportHTML($review, $approvalID, $data);
            }
        }
        $this->view->reviewID   = $reviewID;
        $this->view->approvalID = $approvalID;
        $this->display();
    }

    /**
     * 获取评审节点。
     * Ajax get role for review.
     *
     * @param  int     $projectID
     * @param  string  $object
     * @param  int     $productID
     * @param  int     $reviewID
     * @access public
     * @return void
     */
    public function ajaxGetNodes($projectID = 0, $object = '', $productID = 0, $reviewID = 0)
    {
        $this->commonAction($projectID);

        $flowID = $this->loadModel('approvalflow')->getFlowIDByObject($projectID, $object);

        $this->view->projectID         = $projectID;
        $this->view->object            = $object;
        $this->view->productID         = $productID;
        $this->view->users             = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed|all');
        $this->view->nodes             = $this->loadModel('approval')->getNodesToConfirm($flowID, 0, $projectID, $productID);
        $this->view->nodeReviewerPairs = $reviewID ? $this->reviewZen->getNodeReviewerPairs($reviewID) : array();
        $this->display();
    }

    /**
     * AJAX: return reviews of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id
     * @param  string $status
     * @access public
     * @return void
     */
    public function ajaxGetUserReviews($userID = '', $id = '', $status = 'all')
    {
        $reviews = $this->loadModel('my')->getReviewingList('all');
        foreach($reviews as $review) $items[] = array('text' => $review->title, 'value' => $review->id);
        return print(json_encode(array('name' => $id ? "reviews[{$id}]" : 'review', 'items' => $items)));
    }

    /**
     * Set review auditer.
     *
     * @param  int     $reviewID
     * @access public
     * @return void
     */
    public function toAudit($reviewID)
    {
        $review = $this->review->getByID($reviewID);
        if($_POST)
        {
            $this->review->toAudit($reviewID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('review', $reviewID, 'Toaudit', $this->post->comment, $this->post->auditedBy);
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->title       = $this->lang->review->toAudit;
        $this->view->position[]  = $this->lang->review->toAudit;
        $this->view->review      = $review;
        $this->view->teamMembers = $this->loadModel('project')->getTeamMemberPairs($review->project);
        $this->view->users       = $this->loadModel('user')->getPairs();
        $this->view->actions     = $this->loadModel('action')->getList('review', $reviewID);
        $this->display();
    }

    /**
     * Audit a review.
     *
     * @param  int   $reviewID
     * @access public
     * @return void
     */
    public function audit($reviewID)
    {
        $review = $this->review->getByID($reviewID);
        $this->commonAction($review->project);

        if($_POST)
        {
            $this->review->saveResult($reviewID, 'audit');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => inlink('browse', "project=$review->project")));
        }

        $this->app->loadLang('reviewissue');
        $reviewer = explode(',', $review->reviewedBy);

        $this->setViewData($review);

        $project = $this->loadModel('project')->getByID($review->project);

        $this->view->title      = $this->lang->review->audit;
        $this->view->review     = $review;
        $this->view->object     = $review;
        $this->view->result     = $this->review->getResultByUser($reviewID, 'audit');
        $this->view->issues     = $this->loadModel('reviewissue')->getIssueByReview($reviewID, $review->project, 'audit', 'all', 'all');
        $this->view->cmcl       = $this->loadModel('cmcl')->getList('all', 'id_desc', null, $project->model);
        $this->view->typeList   = $this->lang->cmcl->typeList;
        $this->view->items      = $this->lang->cmcl->titleList;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->projectID  = $review->project;
        $this->display();
    }

    /**
     * @param int $bookID
     * @param int $reviewID
     * @param int $docID
     */
    public function book($bookID, $reviewID, $docID)
    {
        $this->view->bookID = $bookID;
        $this->view->book   = $this->loadModel('doc')->getByID($bookID);
        $this->view->review = $this->review->getByID($reviewID);
        $this->view->docID  = $docID;
        $this->display();
    }

    /**
     * 检查是否可以评审。
     * Check if it can be reviewed.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function ajaxCheckReviewInfo($reviewID)
    {
        return print($this->loadModel('approval')->isReviewed('review', $reviewID));
    }
}
