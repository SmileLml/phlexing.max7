<?php
/**
 * The model file of reviewissue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     reviewissue
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        https://www.zentao.net
 */
class reviewissueModel extends model
{
    /**
     * Get all issue for review.
     *
     * @param  int    $projectID
     * @param  int    $reviewID
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getList($projectID, $reviewID, $status, $orderBy, $pager)
    {
        return $this->dao->select('t1.*,t2.title as reviewtitle')->from(TABLE_REVIEWISSUE)->alias('t1')
            ->leftJoin(TABLE_REVIEW)->alias('t2')
            ->on('t1.review=t2.id')
            ->Where('t1.deleted')->eq('0')
            ->andWhere('t2.project')->eq($projectID)
            ->beginIF($reviewID)->andWhere('t2.id')->eq($reviewID)->fi()
            ->beginIF(in_array($status, array('active', 'closed', 'resolved')))->andWhere('t1.status')->eq($status)->fi()
            ->beginIF($status == 'createdBy')->andWhere('t1.createdBy')->eq($this->app->user->account)->fi()
            ->beginIF($status == 'review' || $status == 'audit')->andWhere('t1.type')->eq($status)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Solve the issue.
     *
     * @param  int    $issueID
     * @param  string $status
     * @param  string $resolution
     * @access public
     * @return void
     */
    public function updateStatus($issueID = 0, $status = '', $resolution = '')
    {
        if($status == 'resolved')
        {
            foreach(explode(',', $this->config->reviewissue->resolve->requiredFields) as $requiredField)
            {
                if(!isset($_POST[$requiredField]) or strlen(trim($_POST[$requiredField])) == 0)
                {
                    $fieldName = $requiredField;
                    if(isset($this->lang->reviewissue->$requiredField)) $fieldName = $this->lang->reviewissue->$requiredField;

                    dao::$errors[] = sprintf($this->lang->error->notempty, $fieldName);
                    if(dao::isError()) return false;
                }
            }
        }

        $this->dao->update(TABLE_REVIEWISSUE)
            ->set('status')->eq($status)
            ->beginIF($status == 'resolved')->set('resolution')->eq($resolution)->set('resolutionDate')->eq(helper::today())->set('resolutionBy')->eq($this->app->user->account)->fi()
            ->where('id')->eq($issueID)
            ->exec();

        /* Determine if there are any outstanding issues. */
        $issue = $this->getByID($issueID);
        $count = $this->dao->select('COUNT(1) AS count')->from(TABLE_REVIEWISSUE)
            ->where('review')->eq($issue->review)
            ->andWhere('status')->eq('active')
            ->andWhere('deleted')->eq(0)
            ->fetch('count');

        /* If there are no outstanding issues, determine whether the review has passed automatically. */
        if($count == 0)
        {
            $review = $this->loadModel('review')->getByID($issue->review);

            if($review->result == 'needfix')
            {
                $this->dao->update(TABLE_REVIEW)
                    ->set('status')->eq('pass')
                    ->set('result')->eq('pass')
                    ->where('id')->eq($issue->review)
                    ->exec();

                $this->dao->update(TABLE_REVIEWRESULT)
                    ->set('result')->eq('pass')
                    ->where('review')->eq($issue->review)
                    ->exec();
            }

            $this->dao->update(TABLE_REVIEWRESULT)->set('remainIssue')->eq('0')->where('review')->eq($issue->review)->exec();
        }
    }

    /**
     * Get information by id.
     *
     * @param  int   $issueID
     * @return array
     */
    public function getByID($issueID)
    {
        return $this->dao->select('t1.*, t2.id as reviewID, t2.title as reviewTitle,t2.status as reviewStatus')->from(TABLE_REVIEWISSUE)->alias('t1')
            ->leftJoin(TABLE_REVIEW)->alias('t2')
            ->on('t1.review=t2.id')
            ->where('t1.id')->eq($issueID)
            ->fetch();
    }

    /**
     * Get all issue for the assigned review.
     *
     * @param  int    $reviewID
     * @param  int    $projectID
     * @param  string $type
     * @param  string $status
     * @param  string $scope
     * @param  int    $approval
     * @access public
     * @return object
     */
    public function getIssueByReview($reviewID, $projectID, $type = 'review', $status = 'noclosed', $scope = 'self', $approval = 0)
    {
        return $this->dao->select('t1.*')->from(TABLE_REVIEWISSUE)->alias('t1')
            ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.review=t2.id')
            ->where('t1.review')->eq($reviewID)
            ->andWhere('t1.type')->eq($type)
            ->andWhere('t2.project')->eq($projectID)
            ->beginIF($scope == 'self')->andWhere('t1.createdBy')->eq($this->app->user->account)->fi()
            ->beginIF($status == 'noclosed')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($approval)->andWhere('t1.approval')->eq($approval)->fi()
            ->fetchAll('id', false);
    }

    /**
     * Add a issue to the review.
     *
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $listID    = $this->post->listID;
        $checkData = $this->loadModel('reviewcl')->getByID($listID);
        $title     = empty($checkData) ? '' : $checkData->title;
        $data = fixer::input('post')
            ->add('title', $title)
            ->add('status', 'active')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', date('Y-m-d'))
            ->add('deleted', 0)
            ->add('project', $this->session->project)
            ->remove('category')
            ->get();

        $this->dao->insert(TABLE_REVIEWISSUE)->data($data)
            ->batchCheck($this->config->reviewissue->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Update a issue.
     *
     * @access public
     * @return array|bool
     */
    public function update($issueID)
    {
        $oldIssue = $this->getByID($issueID);
        $data     = fixer::input('post')->get();
        $this->dao->update(TABLE_REVIEWISSUE)->data($data)->where('id')->eq($issueID)->autoCheck()->exec();

        if(!dao::isError()) return common::createChanges($oldIssue, $data);
        return false;
    }

    /**
     * Access to review data based on status and results.
     *
     * @param  int    $projectID
     * @param  array  $status
     * @param  array  $result
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getReviewList($projectID, $status, $result, $orderBy, $pager = null)
    {
        return $this->dao->select('t1.*,t2.category')->from(TABLE_REVIEW)->alias('t1')
            ->leftJoin(TABLE_OBJECT)->alias('t2')
            ->on('t1.object=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.project')->eq($projectID)
            ->andWhere('t1.status')->in($status)
            ->andWhere('t1.result')->in($result)
            ->page($pager)
            ->orderBy($orderBy)
            ->fetchAll();
    }

    /**
     * Stage of project review.
     *
     * @param  int    $reviewID
     * @access public
     * @return array
     */
    public function getReviewStage($reviewID)
    {
        $review = $this->loadModel('review')->getByID($reviewID);

        $stages = array();
        if(!empty($review))
        {
            $this->loadModel('project');
            $project       = $this->project->getByID($review->project);
            $executionList = $this->loadModel('execution')->getByProject($project->id);
            foreach($executionList as $item) $stages[$item->id] = $item->name;
        }

        return $stages;
    }

    /**
     * Access to review category.
     *
     * @param  int    $reviewID
     * @access public
     * @return array
     */
    public function getReviewCategory($reviewID)
    {
        $reviews = $this->loadModel('review')->getByID($reviewID);
        $category = array();
        if(!empty($reviews))
        {
            $object       = $reviews->category;
            $project      = $this->loadModel('project')->getByID($reviews->project);
            $checkData    = $this->loadModel('reviewcl')->getList($object, 'id_desc', null, $project->model);
            $categoryList = $this->lang->reviewcl->{$project->model . 'CategoryList'};
            foreach($checkData as $object => $check) $category[$object] = $categoryList[$object];
        }
        return $category;
    }

    /**
     * Access to review checklists.
     *
     * @param  int    $reviewID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getReviewCheck($reviewID, $type)
    {
        $checkList = array();
        $reviews = $this->loadModel('review')->getByID($reviewID);
        if(!empty($reviews))
        {
            $project   = $this->loadModel('project')->getByID($reviews->project);
            $checkData = $this->loadModel('reviewcl')->getList($reviews->category, 'id_desc', null, $project->model);
            foreach ($checkData as $category => $check)
            {
                if($category == $type) foreach ($check as $item) $checkList[$item->id] = $item->title;
            }
        }
        return $checkList;
    }

    /**
     * Get issue created by the specified user.
     *
     * @param  int    $reviewID
     * @param  string $createdBy
     * @access public
     * @return object
     */
    public function getUserIssue($reviewID, $createdBy)
    {
        return $this->dao->select('*')->from(TABLE_REVIEWISSUE)
            ->where('deleted')->eq(0)
            ->andWhere('review')->eq($reviewID)
            ->andWhere('createdBy')->eq($createdBy)
            ->orderBy('id_desc')
            ->fetchAll();
    }

    /**
     * Get review records.
     *
     * @param  int    $projectID
     * @param  string $reviewID
     * @param  string $browseType
     * @access public
     * @return string
     */
    public function getReviewRecord($projectID, $reviewID, $browseType)
    {
        $reviewList = $this->dao->select('t1.id,t1.title,t2.version')->from(TABLE_REVIEW)->alias('t1')
            ->leftJoin(TABLE_OBJECT)->alias('t2')
            ->on('t1.object=t2.id')
            ->where('t2.project')->eq($projectID)
            ->orderBy('id_desc')
            ->fetchAll('id');

        $reviewLink = helper::createLink('reviewissue', 'issue', "project=$projectID&reviewID=%s&status=$browseType");
        $listLink   = '';
        foreach($reviewList as $key => $review)
        {
            $active    = $reviewID == $review->id ? 'active' : '';
            $listLink .= html::a(sprintf($reviewLink, $key), '<i class="icon icon-folder-outline"></i>' . $review->title . '--' . $review->version, '',  "class='$active'");
        }

        $html  = '<div class="table-row"><div class="table-col col-left"><div class="list-group">' . $listLink . '</div>';
        $html .= '<div class="col-footer">';
        $html .= html::a(sprintf($reviewLink, 0), '<i class="icon icon-cards-view muted"></i>' . $this->lang->exportTypeList['all'], '', 'class="not-list-item"');
        $html .= '</div></div>';
        $html .= '<div class="table-col col-right"><div class="list-group"></div>';

        return $html;
    }

    /**
     * Get project reviews.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getReviews($projectID)
    {
        $reviews = $this->dao->select('t1.object,t1.title,t2.version')->from(TABLE_REVIEW)->alias('t1')
            ->leftJoin(TABLE_OBJECT)->alias('t2')->on('t1.object=t2.id')
            ->where('t2.project')->eq($projectID)
            ->orderBy('t1.id_desc')
            ->fetchAll('object');
        foreach($reviews as $key => $review) $reviews[$key] = $review->title . '--' . $review->version;

        return $reviews;
    }

    /**
     * Capture all issues reviewed in the project.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function getProjectIssue($projectID, $type, $orderBy)
    {
        return $this->dao->select('t1.id as reviewID,t1.title as reviewTitle,t2.*')->from(TABLE_REVIEW)->alias('t1')
            ->leftJoin(TABLE_REVIEWISSUE)->alias('t2')->on('t1.id=t2.review')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t2.type')->eq($type)
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy($orderBy)
            ->fetchAll();
    }

    /**
     * Build search form.
     *
     * @param  int    $projectID
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($projectID, $queryID, $actionURL)
    {
        $this->config->reviewissue->search['queryID']   = $queryID;
        $this->config->reviewissue->search['actionURL'] = $actionURL;
        $this->config->reviewissue->search['params']['review']['values'] = arrayUnion(array('' => ''), $this->getReviews($projectID));
        $this->config->reviewissue->search['params']['status']['values'] = arrayUnion(array('' => ''), $this->lang->reviewissue->statusList);
        $this->config->reviewissue->search['params']['type']['values']   = arrayUnion(array('' => ''), $this->lang->reviewissue->issueType);

        $this->loadModel('search')->setSearchParams($this->config->reviewissue->search);
    }

    /**
     * Get reviewissues by search.
     *
     * @param  int    $projectID
     * @param  int    $reviewID
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBySearch($projectID, $reviewID, $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $this->loadModel('search');
        $moduleName = $this->app->moduleName;
        $methodName = $this->app->methodName;
        $query      = $queryID ? $this->search->getQuery($queryID) : '';

        $reviewissueQuery = 'reviewissueQuery';
        $reviewissueForm  = 'reviewissueForm';

        if($query)
        {
            $this->session->set($reviewissueQuery, $query->sql);
            $this->session->set($reviewissueForm,  $query->form);
        }

        if($this->session->reviewissueQuery == false) $this->session->set($reviewissueQuery, ' 1 = 1');

        $reviewissueQuery = $this->session->reviewissueQuery;
        $reviewissueQuery = preg_replace_callback('/`([^`]+)`/', function ($matches) {
            return "t1." . $matches[0];
        }, $this->session->reviewissueQuery);
        if(strpos($reviewissueQuery, 't1.`review`') !== false) $reviewissueQuery = str_replace('t1.`review`', 't2.`object`', $reviewissueQuery);

        return $this->dao->select('t1.*,t2.title as reviewtitle')->from(TABLE_REVIEWISSUE)->alias('t1')
            ->leftJoin(TABLE_REVIEW)->alias('t2')
            ->on('t1.review=t2.id')
            ->where($reviewissueQuery)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.project')->eq($projectID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }
}
