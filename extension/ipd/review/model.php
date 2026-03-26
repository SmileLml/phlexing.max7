<?php
/**
 * The model file of review module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     model
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class reviewModel extends model
{
    /**
     * Get review list.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getList($projectID = 0, $browseType = '', $orderBy = '', $pager = null, $queryID = 0)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getReviews();

        $reviewQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('reviewQuery', $query->sql);
                $this->session->set('reviewForm', $query->form);
            }
            if($this->session->reviewQuery == false) $this->session->set('reviewQuery', ' 1=1');
            $reviewQuery = $this->session->reviewQuery;
            $reviewQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $reviewQuery);
            $reviewQuery = preg_replace('/t1\.(`object`)/', 't2.`category`', $reviewQuery);
            $reviewQuery = preg_replace('/t1\.(`version`)/', 't2.`version`', $reviewQuery);
            $reviewQuery = preg_replace('/t1\.(`product`)/', 't2.`product`', $reviewQuery);
        }

        if($browseType == 'wait')
        {
            $pendingList = $this->loadModel('approval')->getPendingReviews('review');
            $reviews     = $this->getByList($projectID, $pendingList, $orderBy, $pager);
        }
        else
        {
            $reviews = $this->dao->select('t1.*, t2.version, t2.category, t2.product')->from(TABLE_REVIEW)->alias('t1')
                ->leftJoin(TABLE_OBJECT)->alias('t2')
                ->on('t1.object=t2.id')
                ->where('t1.deleted')->eq(0)
                ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
                ->beginIF($browseType == 'reviewing')->andWhere('t1.status')->eq('reviewing')->fi()
                ->beginIF($browseType == 'done')->andWhere('t1.status')->eq('done')->fi()
                ->beginIF($browseType == 'reviewedbyme')
                ->andWhere("CONCAT(',', t1.reviewedBy, ',')")->like("%,{$this->app->user->account},%")
                ->fi()
                ->beginIF($browseType == 'createdbyme')
                ->andWhere('t1.createdBy')->eq($this->app->user->account)
                ->fi()
                ->beginIF($browseType == 'bysearch')->andWhere($reviewQuery)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'review');

        $approvals = $this->dao->select('max(approval) as approval,objectID')->from(TABLE_APPROVALOBJECT)
            ->where('objectType')->eq('review')
            ->andWhere('objectID')->in(array_keys($reviews))
            ->groupBy('objectID')
            ->fetchAll('objectID');
        $pendingReviews = $this->loadModel('approval')->getPendingReviews('review');

        $reviewObjects = array_column($reviews, 'object', 'id');
        $baselines = $this->dao->select('`from`, count(1) as count')->from(TABLE_OBJECT)
            ->where('deleted')->eq(0)
            ->andWhere('from')->in($reviewObjects)
            ->andWhere('type')->eq('taged')
            ->groupBy('`from`')
            ->fetchPairs();

        foreach($reviews as $id => $review)
        {
            $reviews[$id]->approval  = isset($approvals[$id]) ? $approvals[$id]->approval : 0;
            $reviews[$id]->isPending = !empty($pendingReviews[$id]);
            $reviews[$id]->baselines = isset($reviewObjects[$id]) && isset($baselines[$reviewObjects[$id]]) ? $baselines[$reviewObjects[$id]] : 0;
        }

        return $reviews;
    }

    /**
     * Get by list.
     *
     * @param  int    $projectID
     * @param  array  $idList
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return void
     */
    public function getByList($projectID = 0, $idList = array(), $orderBy = 'id_desc', $pager = null)
    {
        $reviews = $this->dao->select('t1.*, t2.version, t2.category, t2.product')->from(TABLE_REVIEW)->alias('t1')
            ->leftJoin(TABLE_OBJECT)->alias('t2')
            ->on('t1.object=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.id')->in($idList)->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $approvals = $this->dao->select('max(approval) as approval,objectID')->from(TABLE_APPROVALOBJECT)
            ->where('objectType')->eq('review')
            ->andWhere('objectID')->in(array_keys($reviews))
            ->groupBy('objectID')
            ->fetchAll('objectID');

        foreach($reviews as $id => $review)
        {
            $reviews[$id]->approval = isset($approvals[$id]) ? $approvals[$id]->approval : 0;
        }

        return $reviews;
    }

    /**
     * Get review pairs.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  bool   $withVersion true|false
     * @access public
     * @return void
     */
    public function getPairs($projectID, $productID, $withVersion = false)
    {
        $reviews = $this->dao->select('t1.id, t1.title, t2.version')->from(TABLE_REVIEW)->alias('t1')
            ->leftJoin(TABLE_OBJECT)->alias('t2')
            ->on('t1.object=t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF($productID)->andWhere('t2.product')->eq($productID)->fi()
            ->orderBy('t1.id asc')
            ->fetchAll();

        $pairs = array();
        foreach($reviews as $id => $review) $pairs[$review->id] = $withVersion ? $review->title . '-' . $review->version : $review->title;

        return $pairs;
    }

    /**
     * Get review by id.
     *
     * @param  int    $reviewID
     * @access public
     * @return object|false
     */
    public function getByID($reviewID)
    {
        if(!$reviewID) return false;

        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getReview();

        $review = $this->dao->select('t1.*, t2.id as objectID, t2.version, t2.category, t2.project, t2.product, t2.data, t2.range')->from(TABLE_REVIEW)->alias('t1')
            ->leftJoin(TABLE_OBJECT)->alias('t2')
            ->on('t1.object=t2.id')
            ->where('t1.id')->eq((int)$reviewID)
            ->fetch();

        $pointLatestReviews = array();
        if($this->config->edition == 'ipd' && $review) $pointLatestReviews = $this->getPointLatestReviews($review->project);

        if(!empty($review))
        {
            $review->latestReview = !empty($pointLatestReviews[$review->category]) ? $pointLatestReviews[$review->category]->id : $reviewID;
            $review->files        = $this->loadModel('file')->getByObject('review', $review->id);

            $pendingReview = $this->loadModel('approval')->getPendingReviews('review', $review->id);
            if(isset($pendingReview[$review->id])) $review->isPending = !empty($pendingReview[$review->id]);
        }
        return $review;
    }

    /**
     * Get user review.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return void
     */
    public function getUserReviews($browseType, $orderBy, $pager = null)
    {
        return $this->dao->select('t1.*, t2.version, t2.category, t2.product')->from(TABLE_REVIEW)->alias('t1')
            ->leftJoin(TABLE_OBJECT)->alias('t2')
            ->on('t1.object=t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($browseType == 'reviewing')->andWhere('t1.status')->eq('reviewing')->fi()
            ->beginIF($browseType == 'done')->andWhere('t1.status')->eq('done')->fi()
            ->beginIF($browseType == 'needreview')
            ->andWhere('t1.status')->in('wait,reviewing')
            ->andWhere("CONCAT(',', t1.reviewedBy, ',')")->like("%,{$this->app->user->account},%")
            ->fi()
            ->beginIF($browseType == 'reviewedbyme')
            ->andWhere('t1.status')->ne('wait')
            ->andWhere("CONCAT(',', t1.reviewedBy, ',')")->like("%,{$this->app->user->account},%")
            ->fi()
            ->beginIF($browseType == 'createdbyme')
            ->andWhere('t1.createdBy')->eq($this->app->user->account)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get review result by user.
     *
     * @param  int    $reviewID
     * @param  string $type
     * @access public
     * @return void
     */
    public function getResultByUser($reviewID, $type = 'review')
    {
        $result = $this->dao->select('*')->from(TABLE_REVIEWRESULT)
            ->where('review')->eq($reviewID)
            ->andWhere('reviewer')->eq($this->app->user->account)
            ->andWhere('type')->eq($type)
            ->fetch();

        if($result) $result = $this->loadModel('file')->replaceImgURL($result, 'opinion');
        return $result;
    }

    /**
     * Get review result by user list.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function getResultByUserList($reviewID, $type = 'review')
    {
        $results = $this->dao->select('*')->from(TABLE_REVIEWRESULT)
            ->where('review')->eq($reviewID)
            ->andWhere('type')->eq($type)
            ->fetchAll('reviewer');

        foreach($results as $user => $result)
        {
            if($result) $result = $this->loadModel('file')->replaceImgURL($result, 'opinion');
            $results[$user] = $result;
        }

        return $results;
    }

    /**
     * Get review pairs of a user.
     *
     * @param  string $account
     * @param  int    $limit
     * @param  string $status all|draft|wait|reviewing|pass|fail|auditing|done
     * @param  array  $skipProjectIDList
     * @access public
     * @return array
     */
    public function getUserReviewPairs($account, $limit = 0, $status = 'all', $skipProjectIDList = array())
    {
        $stmt = $this->dao->select('t1.id, t1.title, t2.name as project')
            ->from(TABLE_REVIEW)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where("CONCAT(',', t1.reviewedBy, ',')")->like("%,{$account},%")
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->beginIF(!empty($skipProjectIDList))->andWhere('t1.project')->notin($skipProjectIDList)->fi()
            ->beginIF($limit)->limit($limit)->fi()
            ->query();

        $reviews = array();
        while($review = $stmt->fetch())
        {
            $reviews[$review->id] = $review->project . ' / ' . $review->title;
        }
        return $reviews;
    }

    /**
     * Get book id.
     *
     * @param  object $review
     * @access public
     * @return void
     */
    public function getBookID($review)
    {
        return $this->dao->select('id')->from(TABLE_DOC)
            ->where('product')->eq($review->product)
            ->andWhere('templateType')->eq($review->category)
            ->andWhere('lib')->ne('')->fetch('id');
    }

    /**
     * Get object scale.
     *
     * @param  object $review
     * @access public
     * @return void
     */
    public function getObjectScale($review)
    {
        $productID   = $review->product;
        $objectScale = $this->dao->select('sum(estimate) as objectScale')->from(TABLE_STORY)
            ->where('product')->eq($productID)
            ->andWhere('type')->in('story,requirement')
            ->andWhere('deleted')->eq(0)
            ->fetch('objectScale');

        return $objectScale;
    }

    /**
     * Create a review.
     *
     * @param  int    $projectID
     * @param  string $reviewRange
     * @param  string $checkedItem
     * @access public
     * @return void
     */
    public function create($projectID = 0, $reviewRange = 'all', $checkedItem = '')
    {
        $today = helper::today();
        $data  = fixer::input('post')
            ->setDefault('template', 0)
            ->setDefault('doc', 0)
            ->remove('comment,uid,reviewer,ccer,doclib')
            ->get();

        if($data->content == 'template')
        {
            $data->template = $this->dao->select('id')->from(TABLE_DOC)
                ->where('templateType')->eq($data->object)
                ->andWhere('builtIn')->eq('1')
                ->fetch('id');
        }

        foreach(explode(',', $this->config->review->create->requiredFields) as $requiredField)
        {
            if(!isset($data->$requiredField) or strlen(trim($data->$requiredField)) == 0)
            {
                $fieldName = $requiredField;
                if(isset($this->lang->review->$requiredField)) $fieldName = $this->lang->review->$requiredField;
                dao::$errors[$requiredField] = sprintf($this->lang->error->notempty, $fieldName);
                return false;
            }
        }
        if(dao::isError()) return false;

        $objectData = empty($data->template) ? '' : $this->getTemplateBlockData($data->template, $projectID, $data->product);

        $object = new stdclass();
        $object->project     = $projectID;
        $object->product     = $data->product;
        $object->title       = zget($this->lang->baseline->objectList, $data->object);
        $object->category    = $data->object;
        $object->version     = $this->loadModel('reviewsetting')->getVersionName($data->object);
        $object->type        = 'reviewed';
        $object->range       = $checkedItem ? $checkedItem : $reviewRange;
        $object->data        = $objectData;
        $object->createdBy   = $this->app->user->account;
        $object->createdDate = $today;

        $this->dao->insert(TABLE_OBJECT)->data($object)->batchCheck('product', 'notempty')->exec();
        if(dao::isError()) return false;

        $objectID = $this->dao->lastInsertID();

        $docID      = 0;
        $docVersion = 0;
        if(is_array($data->doc))
        {
            $docs = $this->loadModel('doc')->getByIdList($data->doc);
            foreach($docs as $doc)
            {
                $docIDList[]      = $doc->id;
                $docVersionList[] = $doc->docVersion ? $doc->docVersion : 0;
            }
            $docID      = implode(',', $docIDList);
            $docVersion = implode(',', $docVersionList);
        }
        else if($data->doc)
        {
            $doc = $this->loadModel('doc')->getByID($data->doc);
            if(!empty($doc))
            {
                $docID      = $doc->id;
                $docVersion = $doc->version;
            }
        }

        $review = new stdclass();
        $review->title       = $data->title;
        $review->project     = $projectID;
        $review->object      = $objectID;
        $review->template    = $data->template;
        $review->doc         = $docID;
        $review->docVersion  = $docVersion;
        $review->status      = 'wait';
        $review->createdBy   = $this->app->user->account;
        $review->createdDate = $today;
        $review->deadline    = !empty($data->deadline) ? $data->deadline : null;
        if(!empty($data->begin)) $review->begin = $data->begin;

        $this->dao->insert(TABLE_REVIEW)->data($review)
            ->autoCheck()
            ->batchCheck($this->config->review->create->requiredFields, 'notempty')
            ->exec();

        $reviewID = $this->dao->lastInsertID();
        $this->loadModel('file')->saveUpload('review', $reviewID);

        $reviewers = $this->post->reviewer ? $this->post->reviewer : array();
        $ccers     = $this->post->ccer     ? $this->post->ccer     : array();
        $idList    = $this->post->id       ? $this->post->id       : array();

        if($reviewID) $this->loadModel('action')->create('review', $reviewID, 'Opened', $this->post->comment);

        $result = $this->loadModel('approval')->createApprovalObject($projectID, $reviewID, 'review', $reviewers, $ccers, $idList, $this->post->object);
        if(!empty($result['result'])) $this->dao->update(TABLE_REVIEW)->set('result')->eq($result['result'])->set('status')->eq($result['result'])->where('id')->eq($reviewID)->exec();

        if(!dao::isError()) return $reviewID;

        return false;
    }

    /**
     * Edit a review.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function update($reviewID)
    {
        $oldReview = $this->getByID($reviewID);
        $today = helper::today();
        $data  = fixer::input('post')
            ->setDefault('template', 0)
            ->setDefault('doc', 0)
            ->setDefault('deleteFiles', array())
            ->remove('comment,uid,filed')
            ->get();

        $data->deadline = !empty($data->deadline) ? $data->deadline : null;

        $object = new stdclass();
        $object->product = $data->product;
        $object->title   = $data->title;
        $object->end     = $data->deadline;

        $this->dao->update(TABLE_OBJECT)->data($object)->where('id')->eq($oldReview->objectID)->exec();

        $review = new stdclass();
        $review->title          = $data->title;
        $review->deadline       = $data->deadline;
        $review->lastEditedBy   = $this->app->user->account;
        $review->lastEditedDate = date('Y-m-d');

        $this->dao->update(TABLE_REVIEW)->data($review, 'deleteFiles')
            ->autoCheck()
            ->batchCheck($this->config->review->create->requiredFields, 'notempty')
            ->where('id')->eq($reviewID)
            ->exec();

        $review->product = $object->product;
        $date = $this->loadModel('file')->processImgURL($review, $this->config->review->editor->edit['id'], $this->post->uid);
        $this->file->processFile4Object('review', $oldReview, $data);
        if(!dao::isError()) return common::createChanges($oldReview, $data);

        return false;
    }

    /**
     * Submit a review.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function submit($reviewID)
    {
        $oldReview = $this->getByID($reviewID);
        $today     = helper::today();
        $review    = fixer::input('post')
            ->add('status', 'wait')
            ->setIF($oldReview->status == 'fail', 'status', 'reviewing')
            ->remove('comment,uid,reviewer,ccer,id')
            ->get();

        if($oldReview->doc)
        {
            $doc = $this->loadModel('doc')->getByID($oldReview->doc);
            if($oldReview->docVersion != $doc->version) $review->docVersion = $doc->version;
        }

        $this->dao->update(TABLE_REVIEW)->data($review)->where('id')->eq($reviewID)->exec();

        if(!dao::isError())
        {
            $changes   = common::createChanges($oldReview, $review);
            $review    = $this->getByID($reviewID);
            $reviewers = $this->post->reviewer ? $this->post->reviewer : array();
            $ccers     = $this->post->ccer     ? $this->post->ccer     : array();
            $idList    = $this->post->id       ? $this->post->id       : array();

            $result = $this->loadModel('approval')->createApprovalObject($review->project, $reviewID, 'review', $reviewers, $ccers, $idList, $review->category);
            if(!empty($result['result'])) $this->dao->update(TABLE_REVIEW)->set('result')->eq($result['result'])->set('status')->eq($result['result'])->where('id')->eq($reviewID)->exec();

            return $changes;
        }
        return false;
    }

    /**
     * Set review to audit.
     *
     * @param  int    $reviewID
     * @access public
     * @return bool
     */
    public function toAudit($reviewID)
    {
        $auditedBy = $this->post->auditedBy;
        if(!$auditedBy)
        {
            dao::$errors['auditedBy'] = sprintf($this->lang->error->notempty, $this->lang->review->auditedBy);
            return false;
        }

        $this->dao->update(TABLE_REVIEW)
            ->set('auditedBy')->eq($auditedBy)
            ->set('toAuditBy')->eq($this->app->user->account)
            ->set('toAuditDate')->eq(helper::now())
            ->set('status')->eq('auditing')
            ->where('id')->eq($reviewID)->exec();

        return !dao::isError();
    }

    /**
     * Save review result.
     *
     * @param  int    $reviewID
     * @param  string $type
     * @access public
     * @return void
     */
    public function saveResult($reviewID, $type = 'review')
    {
        $this->loadModel('approval');

        $review  = $this->getById($reviewID);
        $account = $this->app->user->account;
        $today   = helper::today();
        $data    = fixer::input('post')
            ->setDefault('reviewer', $this->app->user->account)
            ->setDefault('setReviewer', '')
            ->setIF(is_numeric($this->post->consumed), 'consumed', (float)$this->post->consumed)
            ->stripTags($this->config->review->editor->assess['id'], $this->config->allowedTags)
            ->get();

        $required = $this->config->review->assess->requiredFields;
        if(strpos(",$required,", ',setReviewer,') !== false && isset($_POST['setReviewer']) && empty($data->setReviewer)) dao::$errors['setReviewer'] = sprintf($this->lang->error->notempty, $this->lang->review->setReviewer);
        if(strpos(",$required,", ',opinion,')     !== false && isset($_POST['opinion'])     && empty($data->opinion))
        {
            dao::$errors['opinion'] = sprintf($this->lang->error->notempty, $this->lang->review->finalOpinion);
        }
        else if(!empty($data->result) && $data->result == 'fail' && isset($_POST['opinion']) && empty($data->opinion))
        {
            dao::$errors['opinion'] = sprintf($this->lang->error->notempty, $this->lang->review->finalOpinion);
        }
        if(dao::isError()) return false;

        $result = new stdclass();
        $result->id          = $reviewID;
        $result->result      = $data->result;
        $result->opinion     = $data->opinion;
        $result->createdDate = $data->createdDate ? $data->createdDate : helper::today();
        $result->consumed    = (int)$data->consumed;
        $result->setReviewer = $data->setReviewer;

        $result = $this->loadModel('file')->processImgURL($result, $this->config->review->editor->assess['id'], $this->post->uid);

        /* Log first for approval send message. */
        $action   = $type == 'review' ? 'Reviewed' : 'Audited';
        $actionID = $this->loadModel('action')->create('review', $reviewID, $action, $this->post->opinion, ucfirst($result->result));

        if($type == 'review')
        {
            $reviewers = explode(',', $review->reviewedBy);
            if(array_search($account, $reviewers) === false) $reviewers[] = $account;
            $reviewers = implode(',', $reviewers);

            $this->dao->update(TABLE_REVIEW)
                ->set('lastReviewedBy')->eq($this->app->user->account)
                ->set('lastReviewedDate')->eq($today)
                ->set('reviewedBy')->eq($reviewers)
                ->where('id')->eq($reviewID)
                ->exec();

            if($data->result == 'pass')
            {
                $res = $this->approval->pass('review', $result, $result->consumed);
                if($res)
                {
                    $approval = $this->loadModel('approval')->getByObject('review', $reviewID);
                    /* 如果是百分比通过，并且需要所有人评审，则最后一个人即使通过，整体结果也可能是拒绝的。 */
                    $reviewResult = $approval->result == 'fail' ? 'fail' : 'pass';
                    $this->dao->update(TABLE_REVIEW)->set('result')->eq($reviewResult)->set('status')->eq($reviewResult)->where('id')->eq($reviewID)->exec();
                }
                else
                {
                    if($review->status == 'wait') $this->dao->update(TABLE_REVIEW)->set('status')->eq('reviewing')->where('id')->eq($reviewID)->exec();
                }
            }
            elseif($data->result == 'fail')
            {
                $res = $this->approval->reject('review', $result, $result->consumed);
                if($res['finished'])
                {
                    $this->dao->update(TABLE_REVIEW)->set('result')->eq($res['result'])->set('status')->eq($res['result'])->where('id')->eq($reviewID)->exec();
                }
                else
                {
                    $this->dao->update(TABLE_REVIEW)->set('status')->eq('reviewing')->where('id')->eq($reviewID)->exec();
                }
            }

            /* Save file. */
            $approval = $this->loadModel('approval')->getByObject('review', $reviewID);
            $approvalNodeID = $this->dao->select('*')->from(TABLE_APPROVALNODE)
                ->where('approval')->eq($approval->id)
                ->andWhere('type')->eq('review')
                ->andWhere('result')->eq($result->result)
                ->andWhere('reviewedBy')->eq($this->app->user->account)
                ->orderBy('id_desc')
                ->fetch('id');
            if($approvalNodeID) $this->loadModel('file')->saveUpload('approvalnode', $approvalNodeID);

            $this->loadModel('effort')->create('review', $reviewID, $result->consumed, $review->title, $approval->id, $result->createdDate);
        }
        else
        {
            $audit  = new stdclass();
            $audit->auditResult     = $result->result;
            $audit->auditedBy       = $this->app->user->account;
            $audit->lastAuditedBy   = $this->app->user->account;
            $audit->lastAuditedDate = helper::today();

            if($result->result == 'pass') $audit->status = 'done';

            if($result->result == 'fail')
            {
                $audit->status    = 'wait';
                $audit->result    = '';
                $audit->auditedBy = '';

                $this->loadModel('approval')->restart('review', $reviewID);
            }

            if($result->result == 'needfix')
            {
                $audit->status    = 'pass';
                $audit->auditedBy = '';
            }

            $this->dao->update(TABLE_REVIEW)->data($audit)->where('id')->eq($reviewID)->exec();
            $this->loadModel('effort')->create('review', $reviewID, $result->consumed, $this->lang->review->audit . $review->title, '', $result->createdDate);
        }

        if(dao::isError())
        {
            $this->dao->delete()->from(TABLE_ACTION)->where('id')->eq($actionID)->exec();
            return false;
        }


        /* Save review issues. */
        $issueResult = isset($data->issueResult) ? $data->issueResult : array();
        if(empty($issueResult)) return true;

        $checkListPairs = $type == 'review' ? $this->loadModel('reviewcl')->getByList(array_keys($issueResult)) : $this->loadModel('cmcl')->getByList(array_keys($issueResult));

        $approval = $this->loadModel('approval')->getApprovalIDByObjectID($reviewID);
        $currentApprovalID = end($approval);

        foreach($issueResult as $id => $result)
        {
            if($result != 0) continue;

            $issue = new stdclass();
            $issue->title       = zget($checkListPairs, $id, $data->issueOpinion[$id]);
            $issue->type        = $type;
            $issue->review      = $reviewID;
            $issue->listID      = $id;
            $issue->status      = 'active';
            $issue->opinion     = $data->issueOpinion[$id];
            $issue->createdBy   = $this->app->user->account;
            $issue->createdDate = helper::today();
            $issue->approval    = $currentApprovalID ? $currentApprovalID : 0;
            if(isset($data->opinionDate[$id])) $issue->opinionDate = $data->opinionDate[$id];

            $this->dao->insert(TABLE_REVIEWISSUE)->data($issue)->autoCheck()->exec();
            $issueID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('reviewissue', $issueID, 'opened', $issue->opinion);
        }
    }

    /**
     * 回退评审。
     * Revert a review.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function revert($reviewID)
    {
        $data = fixer::input('post')
            ->stripTags('revertOpinion', $this->config->allowedTags)
            ->get();

        $approval = $this->loadModel('approval')->getByObject('review', $reviewID);
        $this->approval->revert($approval->id, $data->currentNodeID, $data->toNodeID, $data->revertType);
        if($data->toNodeID == 'start') $this->dao->update(TABLE_REVIEW)->set('status')->eq('reverting')->where('id')->eq($reviewID)->exec();

        $this->loadModel('action')->create('review', $reviewID, 'Reverted', $data->revertOpinion, $data->currentNodeID);
    }

    /**
     * 转交评审。
     * Forward a review.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function forward($reviewID)
    {
        $data = fixer::input('post')
            ->stripTags('forwardOpinion', $this->config->allowedTags)
            ->get();

        $this->loadModel('approval')->forward($data->currentNodeID, $data->forwardTo, $data->forwardOpinion);
        $this->loadModel('action')->create('review', $reviewID, 'forwarded', $data->revertOpinion);
    }

    /**
     * get audit by reviewID
     *
     * @param  int $reviewID
     * @access public
     * @return $audit
     */
    public function getAuditByReviewID($reviewID)
    {
        $audit = $this->dao->select('*')->from(TABLE_REVIEWRESULT)->where('review')->eq($reviewID)->andWhere('type')->eq('audit')->fetch();
        if($audit) $audit = $this->loadModel('file')->replaceImgURL($audit, 'opinion');

        return $audit;
    }

    /**
     * Get object data.
     *
     * @param  int     $projectID
     * @param  string  $objectType
     * @param  int     $productID
     * @param  string  $reviewRange
     * @param  string  $checkedItem
     * @access public
     * @return void
     */
    public function getDataByObject($projectID, $objectType, $productID, $reviewRange, $checkedItem)
    {
        $data = array();
        if($objectType == 'PP')  $data = $this->getDataFromPP($projectID, $objectType, $productID);
        if($objectType == 'SRS') $data = $this->getDataFromStory($projectID, 'story', $productID, $reviewRange, $checkedItem);
        if($objectType == 'ERS') $data = $this->getDataFromStory($projectID, 'epic', $productID, $reviewRange, $checkedItem);
        if($objectType == 'URS') $data = $this->getDataFromStory($projectID, 'requirement', $productID, $reviewRange, $checkedItem);
        if($objectType == 'HLDS' || $objectType == 'DDS' || $objectType == 'DBDS' || $objectType == 'ADS') $data = $this->getDataFromDesign($projectID, $objectType, $productID, $reviewRange, $checkedItem);
        if($objectType == 'ITTC' || $objectType == 'STTC') $data = $this->getDataFromCase($projectID, $objectType, $productID, $reviewRange, $checkedItem);

        return $data;
    }

    /**
     * Get data from story.
     *
     * @param  int     $projectID
     * @param  string  $storyType
     * @param  int     $productID
     * @param  string  $reviewRange
     * @param  string  $checkedItem
     * @access public
     * @return void
     */
    public function getDataFromStory($projectID, $storyType, $productID, $reviewRange, $checkedItem)
    {
        $data = array();

        $stories = $this->dao->select('t1.module, t1.estimate, t2.*')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id = t2.story and t1.version = t2.version')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id = t3.story')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t3.project')->eq($projectID)
            ->andWhere('t3.product')->eq($productID)
            ->andWhere('t1.type')->eq($storyType)
            ->beginIF($reviewRange != 'all')->andWhere('t1.id')->in($checkedItem)->fi()
            ->orderBy('story_asc')
            ->fetchAll('story');

        $storyEst = $this->dao->select('sum(estimate) as storyEst')->from(TABLE_STORY)
            ->where('id')->in(array_keys($stories))
            ->andWhere('deleted')->eq(0)
            ->fetch('storyEst');

        $data['story']    = $stories;
        $data['storyEst'] = $storyEst;
        return $data;
    }

    /**
     * Get data from design.
     *
     * @param  int     $projectID
     * @param  string  $objectType
     * @param  int     $productID
     * @param  string  $reviewRange
     * @param  string  $checkedItem
     * @access public
     * @return void
     */
    public function getDataFromDesign($projectID, $objectType, $productID, $reviewRange, $checkedItem)
    {
        $data = array();
        $designs = $this->dao->select('t2.*')->from(TABLE_DESIGN)->alias('t1')
            ->leftJoin(TABLE_DESIGNSPEC)->alias('t2')
            ->on('t1.id=t2.design and t1.version=t2.version')
            ->where('(t1.product')->eq($productID)
            ->orWhere('t1.product')->eq(0)
            ->markRight(1)
            ->andWhere('t1.type')->eq($objectType)
            ->andWhere('t1.project')->eq($projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($reviewRange != 'all')->andWhere('t1.id')->in($checkedItem)->fi()
            ->orderBy('version_desc')
            ->fetchAll('design');

        $data['design'] = $designs;
        return $data;
    }

    /**
     * Get data from case.
     *
     * @param  int     $projectID
     * @param  string  $objectType
     * @param  int     $productID
     * @param  string  $reviewRange
     * @param  string  $checkedItem
     * @access public
     * @return void
     */
    public function getDataFromCase($projectID, $objectType, $productID, $reviewRange, $checkedItem)
    {
        $data  = array();
        $stage = $objectType == 'ITTC' ? 'intergrate' : 'system';
        $cases = $this->dao->select('t1.id as caseID, t1.module, t1.title, t2.*')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_CASESTEP)->alias('t2')
            ->on('t1.id=t2.case')
            ->where('t1.product')->eq($productID)
            ->andWhere('t1.stage')->like("%$stage%")
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($reviewRange != 'all')->andWhere('t1.id')->in($checkedItem)->fi()
            ->fetchAll('caseID');

        $data['case'] = $cases;
        return $data;
    }

    /**
     * Get data from project plan.
     *
     * @param  int    $projectID
     * @param  string $objectType
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function getDataFromPP($projectID, $objectType, $productID)
    {
        $data   = array();
        $stages = $this->dao->select('t1.*')->from(TABLE_PROJECTSPEC)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project=t2.id and t1.version=t2.version')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')
            ->on('t2.id=t3.project')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t2.project')->eq($projectID)
            ->andWhere('t3.product')->eq($productID)
            ->fetchAll('project');

        $data['stage'] = $stages;

        $projects = $this->dao->select('t1.id')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')
            ->on('t1.id=t2.project')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t1.type')->eq('stage')
            ->andWhere('t2.product')->eq($productID)
            ->fetchPairs();

        $tasks = $this->dao->select('t1.*, t2.estimate, t2.type')->from(TABLE_TASKSPEC)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')
            ->on('t1.task=t2.id and t1.version=t2.version')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t2.status')->ne('cancel')
            ->andWhere('t2.parent')->le(0)
            ->andWhere('t2.project')->in($projects)
            ->fetchAll('task');

        /* Sum estimate by type.*/
        $taskEst = $requestEst = $testEst = $devEst = $designEst = 0;
        foreach($tasks as $task)
        {
            $taskEst += $task->estimate;
            if($task->type == 'request') $requestEst += $task->estimate;
            if($task->type == 'devel')   $devEst     += $task->estimate;
            if($task->type == 'test')    $testEst    += $task->estimate;
            if($task->type == 'design')  $designEst  += $task->estimate;
        }

        $data['task']        = $tasks;
        $data['taskEst']     = $taskEst;
        $data['requestEst']  = $requestEst;
        $data['devEst']      = $devEst;
        $data['testEst']     = $testEst;
        $data['designEst']   = $designEst;
        return $data;
    }

    /**
     * Get reviewer by object.
     *
     * @param  int    $projectID
     * @param  string $object
     * @access public
     * @return void
     */
    public function getReviewerByObject($projectID, $object = '')
    {
        $this->app->loadConfig('reviewsetting');
        $roleList = isset($this->config->reviewsetting->reviewer->$object) ? $this->config->reviewsetting->reviewer->$object : array();
        if(empty($roleList)) return array();

        $users = $this->dao->select('t1.account, t1.realname')->from(TABLE_USER)->alias('t1')
            ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.role')->in($roleList)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.root')->eq($projectID)
            ->fetchPairs();

        return !empty($users) ? $users : array('' => '');
    }

    /**
     * Judge button if can clickable.
     *
     * @param  object $review
     * @param  string $action
     * @access public
     * @return void
     */
    public static function isClickable($review, $action)
    {
        global $app, $config;
        $action = strtolower($action);
        $status = isset($review->rawStatus) ? $review->rawStatus : $review->status;

        if($action == 'create')  return !$status;
        if($action == 'edit')    return ($status == 'wait' || $status == 'draft' || $status == 'fail');
        if($action == 'assess')  return ($status == 'wait' || $status == 'reviewing') && (!isset($review->isPending) || !empty($review->isPending));
        if($action == 'submit')
        {
            $isLatestReview = ($config->edition == 'ipd' && isset($review->latestReview) && $review->latestReview != $review->id) ? false : true;
            return (($status == 'draft' || $status == 'reverting' || ($status == 'fail' && $review->result == 'fail')) && $isLatestReview);
        }
        if($action == 'recall')  return $status == 'wait';
        if($action == 'toaudit') return $status == 'pass' and !$review->auditedBy;
        if($action == 'audit')   return $status == 'auditing' and $review->auditedBy == $app->user->account;
        if($action == 'report')  return $review->result;
        if($action == 'createbaseline') return empty($review->baselines) && $status == 'done';
        if($action == 'progress') return common::hasPriv('approval', 'progress') && !empty($review->review);
        if($action == 'delete' && $config->edition == 'ipd' && in_array($review->category, $config->review->ipdPointOrder)) return $review->status != 'pass';
        return true;
    }

    /**
     * Get reviewer by review id list.
     *
     * @param  int|array    $reviewIdList
     * @access public
     * @return array
     */
    public function getReviewerByIdList($reviewIdList)
    {
        $reviewerGroup = $this->dao->select("id,objectID,nodes")->from(TABLE_APPROVAL)
            ->where('objectType')->eq('review')
            ->andWhere('objectID')->in($reviewIdList)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetchGroup('objectID', 'id');

        $reviewers = array();
        foreach($reviewerGroup as $reviewID => $reviewList)
        {
            $reviewers[$reviewID] = isset($reviewers[$reviewID]) ? $reviewers[$reviewID] : array();

            $latestNode = current($reviewList);
            $nodes      = json_decode($latestNode->nodes, true);
            foreach($nodes as $reviewerList)
            {
                $approverList = isset($reviewerList->reviewers) ? $reviewerList->reviewers : array();
                if(empty($approverList)) continue;

                foreach($approverList as $users)
                {
                    if(!empty($users->users)) $reviewers[$reviewID] = array_unique(array_merge($reviewers[$reviewID], $users->users));
                }
            }
        }

        return $reviewers;
    }

    /**
     * Build search form.
     *
     * @param  string $actionURL
     * @param  int    $queryID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function buildSearchForm($actionURL = '', $queryID = 0, $projectID = 0)
    {
        $project  = $this->loadModel('project')->getById($projectID);
        $products = $this->loadModel('product')->getProductPairsByProject($projectID);

        $objectList = $this->lang->baseline->plusObjectList;
        if($project && $project->model == 'ipd') $objectList = array_merge($this->lang->baseline->ipd->pointList, $objectList);
        $this->config->review->search['params']['object']['values']  = $objectList;
        $this->config->review->search['params']['product']['values'] = arrayUnion(array('' => ''), $products);

        $this->config->review->search['actionURL'] = $actionURL;
        $this->config->review->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->review->search);
    }

    /**
     * Build book tree.
     *
     * @param  object $book
     * @param  object $review
     * @param  int    $docID
     * @access public
     * @return array
     */
    public function buildBookTree($book, $review, $docID = 0)
    {
        $nodeItems = array();
        $bookID    = $book->id;
        $serials   = $this->loadModel('doc')->computeSN($bookID, 'baseline');
        $nodeList  = $this->doc->getNodeList($bookID);
        foreach($nodeList as $nodeInfo)
        {
            $nodeContent = '';
            $serial      = $nodeInfo->type != 'book' ? $serials[$nodeInfo->id] : '';
            if($nodeInfo->type == 'chapter')
            {
                $nodeContent .= "<span class='item' title='{$nodeInfo->title}'>{$serial} {$nodeInfo->title}</span>";
                if($nodeInfo->chapterType == 'system') $nodeContent .= $this->doc->getCmStructure($nodeInfo->template, $serial, $review);
            }
            elseif($nodeInfo->type == 'article')
            {
                $nodeContent .= "<span class='item' title='{$nodeInfo->title}'>{$serial} " . html::a(helper::createLink('baseline', 'view', "docID=$nodeInfo->id"), $nodeInfo->title, '', "data-app='admin'") . "</span>";
            }

            $nodeItem = array('text' => array('html' => $nodeContent));
            if(!empty($nodeInfo->children)) $nodeItem['children'] = $this->doc->getFrontCatalogItems($nodeInfo->children, $serials, $docID);
            $nodeItems[] = $nodeItem;
        }

        return array(array('content' => array('html' => "<span title='{$book->title}'></i> {$book->title}</span>"), 'icon' => 'folder-outline', 'children' => $nodeItems));
    }

    /**
     * 获取审批的相关人员。
     * Get to and cc list.
     *
     * @param  object $object
     * @param  string $action
     * @access public
     * @return array
     */
    public function getToAndCcList($object, $action)
    {
        $toList = $ccList = '';
        if($action == 'toaudit') $toList = $object->auditedBy;
        if($action == 'audited') $toList = $object->toAuditBy;
        return array($toList, $ccList);
    }

    /**
     * 获取模板区块内容。
     * Get block data of template.
     *
     * @param  int    $templateID
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function getTemplateBlockData($templateID, $projectID, $productID)
    {
        $this->loadModel('doc');
        $template = $this->dao->select('t1.templateType AS type, t2.rawContent')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id = t2.doc')
            ->where('t1.id')->eq($templateID)
            ->orderBy('t2.version_desc')
            ->fetch();

        $blockType = $template->type;
        if($template->type == 'PP')  $blockType = 'gantt';
        if($template->type == 'SRS') $blockType = 'projectStory';
        if(strpos(',ITTC,STTC,', $template->type) !== false) $blockType = 'projectCase';

        $docblock = new stdClass();
        $docblock->type     = $blockType;
        $docblock->doc      = $templateID;
        $docblock->settings = helper::createLink('doc', 'buildZentaoConfig', "type=$blockType&oldBlockID={blockID}");
        $docblock->extra    = 'fromReview';

        $blockContent = new stdClass();
        $blockContent->searchTab = $blockType == 'projectStory' ? 'allstory' : 'all';
        $blockContent->project   = array($projectID);
        $blockContent->product   = array($productID);
        $blockContent->caseStage = $template->type == 'ITTC' ? 'intergrate' : 'system';

        if($blockType == 'gantt')
        {
            $blockContent->ganttOptions = $this->doc->getGanttData($projectID);
            $blockContent->showFields   = $this->config->programplan->ganttCustom->ganttFields;
            $blockContent->ganttFields  = $this->doc->getGanttFields();
        }
        else
        {
            $isDesign      = strpos(',HLDS,DDS,DBDS,ADS,', ",{$blockType},") !== false;
            $getColsMethod = $isDesign ? 'getDesignTableCols' : "get{$blockType}TableCols";
            $getDataMethod = $isDesign ? 'getDesignTableData' : "get{$blockType}TableData";
            if(method_exists($this->doc, $getColsMethod)) $blockContent->cols = call_user_func_array(array($this->doc, $getColsMethod), array());
            if(method_exists($this->doc, $getDataMethod))
            {
                foreach($this->config->doc->getTableDataParams[$blockType] as $paramKey)
                {
                    if($paramKey == 'searchTab') $params[$paramKey] = 'all';
                    if($paramKey == 'project')   $params[$paramKey] = array($projectID);
                    if($paramKey == 'product')   $params[$paramKey] = array($productID);
                    if($paramKey == 'caseStage' && $template->type == 'ITTC') $params[$paramKey] = 'intergrate';
                    if($paramKey == 'caseStage' && $template->type == 'STTC') $params[$paramKey] = 'system';
                    if($paramKey == 'searchTab' && $template->type == 'SRS')  $params[$paramKey] = 'allstory';
                }
                if($isDesign) $params['type'] = $blockType;

                $blockContent->data = call_user_func_array(array($this->doc, $getDataMethod), $params);
            }
        }

        $docblock->content = json_encode($blockContent);
        $this->dao->insert(TABLE_DOCBLOCK)->data($docblock)->exec();
        $blockID = $this->dao->lastInsertID();
        if(dao::isError()) return '';

        return preg_replace("/__TML_ZENTAOLIST__\d+(?!\d)/", "__TML_ZENTAOLIST__{$blockID}", $template->rawContent);
    }
}
