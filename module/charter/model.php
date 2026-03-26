<?php
class charterModel extends model
{
    /**
     * 根据立项ID获取立项信息。
     * Get charter info by id.
     *
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function getByID($charterID = 0)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getCharter();

        $charter = $this->dao->findByID($charterID)->from(TABLE_CHARTER)->fetch();
        if(!$charter) return false;

        $charter->files        = $this->loadModel('file')->getByObject('charter', $charterID);
        $charter->charterFiles = $charter->charterFiles ? json_decode($charter->charterFiles , true) : array();
        $charter->approval     = $this->getApprovalID($charterID);

        $approvalList = $this->getApprovalList(array($charterID), true);
        foreach($approvalList[$charterID] as $key => $approval) $charter->{$key . 'Desc'} = $approval['desc'];
        $charter->approvalList = $approvalList[$charterID];

        foreach($charter->files as $fileInfo) {$fileInfo->name = $fileInfo->title;}
        foreach($charter->charterFiles as $key => $file)
        {
            $fileInfo = $charter->files[$file['id']];
            $fileInfo->extra = 'projectApproval' . '-' . $key;
            $charter->files[$file['id']] = $fileInfo;
        }
        return $this->file->replaceImgURL($charter, 'spec,projectApprovalDesc,completionApprovalDesc,cancelProjectApprovalDesc,activateProjectApprovalDesc');
    }

    /**
     * Review charter
     *
     * @param  object $charter
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function review($charter, $charterID)
    {
        $this->loadModel('approval');

        $approval = new stdclass();
        $approval->id          = $charterID;
        $approval->opinion     = $charter->reviewOpinion;
        $approval->createdDate = helper::today();
        $approval->setReviewer = $charter->setReviewer;

        $reviewFunc = $charter->reviewResult;
        $result     = $this->approval->$reviewFunc('charter', $approval);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $approval = $this->approval->getByObject('charter', $charterID);
        $approval = $this->approval->getApprovalObjectByID($approval->id);

        if(is_array($result))
        {
            $result['result']   = $result['result'] == 'fail' ? 'reject' : '';
            $charter->status       = $result['finished'] ? $result['result'] : 'doing';
            $charter->reviewStatus = $result['finished'] ? $result['result'] : '';
        }
        else
        {
            if($result === true)
            {
                $charter->status       = $approval->result == 'fail' ? 'reject' : 'pass';
                $charter->reviewStatus = $approval->result == 'fail' ? 'reject' : 'pass';
            }
            else
            {
                $charter->status       = 'doing';
                $charter->reviewStatus = '';
            }
        }

        $reviewFunc = $approval->extra;
        if(!$reviewFunc) return false;

        $reviewFunc = $reviewFunc . 'Review';
        return $this->$reviewFunc($charter, $approval->id);
    }

    /**
     * 审批发起立项申请。
     * Review project approval.
     *
     * @param  object $charter
     * @param  int    $approvalID
     * @access public
     * @return bool
     */
    public function projectApprovalReview($charter, $approvalID)
    {
        $this->loadModel('action');

        $oldCharter      = $this->getByID($charter->id);
        $charter->status = $charter->status == 'pass' ? 'launched' : $oldCharter->status;
        $reviewResult    = $charter->reviewStatus;
        $reviewStatus    = 'projectDoing';
        if($charter->reviewStatus == 'pass')    $reviewStatus = 'projectPass';
        if($charter->reviewStatus == 'reject')  $reviewStatus = 'projectReject';
        $charter->reviewStatus = $reviewStatus;

        $charter = $this->loadModel('file')->processImgURL($charter, $this->config->charter->editor->create['id'], $this->post->uid);
        $this->dao->update(TABLE_CHARTER)->data($charter, 'setReviewer,reviewOpinion,reviewResult')->autoCheck()->checkFlow()->where('id')->eq($charter->id)->exec();
        if(dao::isError()) return false;

        $approvalObject = new stdclass();
        $approvalObject->opinion = $charter->reviewOpinion;
        $approvalObject->result  = $reviewResult;
        $approvalObject->status  = $charter->reviewStatus;
        $this->dao->update(TABLE_APPROVALOBJECT)->data($approvalObject)->where('objectType')->eq('charter')->andWhere('objectID')->eq($charter->id)->andWhere('approval')->eq($approvalID)->exec();
        if(dao::isError()) return false;

        if($charter->reviewStatus != 'projectDoing')
        {
            $roadmap = new stdclass();
            $roadmap->status = $charter->reviewStatus == 'projectReject' ? 'reject' : 'launched';
            $this->dao->update(TABLE_ROADMAP)->data($roadmap)->where('id')->in($oldCharter->roadmap)->exec();
            if(dao::isError()) return false;
        }

        if($charter->reviewStatus == 'projectPass' && $oldCharter->product && !empty(trim($oldCharter->roadmap, ','))) $this->updateRoadmapStoriesStage($oldCharter);

        $reviewResult = $charter->reviewResult ? $charter->reviewResult : $oldCharter->reviewResult;
        $changes      = common::createChanges($oldCharter, $charter);
        $actionID     = $this->action->create('charter', $charter->id, 'reviewbycharter', '', ucfirst($reviewResult));
        $this->action->logHistory($actionID, $changes);
        return !dao::isError();
    }

    /**
     * 审批结项申请。
     * Review completion project approval.
     *
     * @param  object $charter
     * @param  int    $approvalID
     * @access public
     * @return bool
     */
    public function completionApprovalReview($charter, $approvalID)
    {
        $oldCharter      = $this->getByID($charter->id);
        $charter->status = $charter->status == 'pass' ? 'completed' : $oldCharter->status;
        $reviewResult    = $charter->reviewStatus;
        $reviewStatus    = 'completionDoing';
        if($charter->reviewStatus == 'pass')    $reviewStatus = 'completionPass';
        if($charter->reviewStatus == 'reject')  $reviewStatus = 'completionReject';
        $charter->reviewStatus = $reviewStatus;

        $charter = $this->loadModel('file')->processImgURL($charter, $this->config->charter->editor->create['id'], $this->post->uid);
        $this->dao->update(TABLE_CHARTER)->data($charter, 'setReviewer,reviewOpinion,reviewResult')->autoCheck()->checkFlow()->where('id')->eq($charter->id)->exec();
        if(dao::isError()) return false;

        $approvalObject = new stdclass();
        $approvalObject->opinion = $charter->reviewOpinion;
        $approvalObject->result  = $reviewResult;
        $approvalObject->status  = $charter->reviewStatus;
        $this->dao->update(TABLE_APPROVALOBJECT)->data($approvalObject)->where('objectType')->eq('charter')->andWhere('objectID')->eq($charter->id)->andWhere('approval')->eq($approvalID)->exec();
        if(dao::isError()) return false;

        $reviewResult = $charter->reviewResult ? $charter->reviewResult : $oldCharter->reviewResult;
        $changes      = common::createChanges($oldCharter, $charter);
        $actionID     = $this->loadModel('action')->create('charter', $charter->id, 'reviewbycharter', '', ucfirst($reviewResult));
        $this->action->logHistory($actionID, $changes);
        return !dao::isError();
    }

    /**
     * 审批取消立项申请。
     * Review cancel project approval.
     *
     * @param  object $charter
     * @param  int    $approvalID
     * @access public
     * @return bool
     */
    public function cancelProjectApprovalReview($charter, $approvalID)
    {
        $oldCharter                  = $this->getByID($charter->id);
        $charter->prevCanceledStatus = $charter->status == 'pass' ? $oldCharter->status : '';
        $charter->status             = $charter->status == 'pass' ? 'canceled'          : $oldCharter->status;
        $reviewResult                = $charter->reviewStatus;
        $reviewStatus                = 'cancelDoing';
        if($charter->reviewStatus == 'pass')    $reviewStatus = 'cancelPass';
        if($charter->reviewStatus == 'reject')  $reviewStatus = 'cancelReject';
        $charter->reviewStatus = $reviewStatus;

        $charter = $this->loadModel('file')->processImgURL($charter, $this->config->charter->editor->create['id'], $this->post->uid);
        $this->dao->update(TABLE_CHARTER)->data($charter, 'setReviewer,reviewOpinion,reviewResult')->autoCheck()->checkFlow()->where('id')->eq($charter->id)->exec();
        if(dao::isError()) return false;

        $approvalObject = new stdclass();
        $approvalObject->opinion = $charter->reviewOpinion;
        $approvalObject->result  = $reviewResult;
        $approvalObject->status  = $charter->reviewStatus;
        $this->dao->update(TABLE_APPROVALOBJECT)->data($approvalObject)->where('objectType')->eq('charter')->andWhere('objectID')->eq($charter->id)->andWhere('approval')->eq($approvalID)->exec();
        if(dao::isError()) return false;

        if($charter->status == 'canceled') $this->dao->update(TABLE_ROADMAP)->set('status')->eq('wait')->where('id')->in(trim($oldCharter->roadmap, ','))->exec();

        unset($charter->prevCanceledStatus);
        $reviewResult = $charter->reviewResult ? $charter->reviewResult : $oldCharter->reviewResult;
        $changes      = common::createChanges($oldCharter, $charter);
        $actionID     = $this->loadModel('action')->create('charter', $charter->id, 'reviewbycharter', '', ucfirst($reviewResult));
        $this->action->logHistory($actionID, $changes);
        return !dao::isError();
    }

    /**
     * 审批激活立项申请。
     * Review active project approval.
     *
     * @param  object $charter
     * @param  int    $approvalID
     * @access public
     * @return bool
     */
    public function activateProjectApprovalReview($charter, $approvalID)
    {
        $oldCharter                  = $this->getByID($charter->id);
        $charter->status             = $charter->reviewStatus == 'pass' ? $oldCharter->prevCanceledStatus : $oldCharter->status;
        $charter->prevCanceledStatus = $charter->reviewStatus == 'pass' ? '' : $oldCharter->prevCanceledStatus;
        $reviewResult                = $charter->reviewStatus;
        $reviewStatus                = 'activateDoing';
        if($charter->reviewStatus == 'pass')    $reviewStatus = 'activatePass';
        if($charter->reviewStatus == 'reject')  $reviewStatus = 'activateReject';
        $charter->reviewStatus = $reviewStatus;

        $charter = $this->loadModel('file')->processImgURL($charter, $this->config->charter->editor->create['id'], $this->post->uid);
        $this->dao->update(TABLE_CHARTER)->data($charter, 'setReviewer,reviewOpinion,reviewResult')->autoCheck()->checkFlow()->where('id')->eq($charter->id)->exec();
        if(dao::isError()) return false;

        $approvalObject = new stdclass();
        $approvalObject->opinion = $charter->reviewOpinion;
        $approvalObject->result  = $reviewResult;
        $approvalObject->status  = $charter->reviewStatus;
        $this->dao->update(TABLE_APPROVALOBJECT)->data($approvalObject)->where('objectType')->eq('charter')->andWhere('objectID')->eq($charter->id)->andWhere('approval')->eq($approvalID)->exec();
        if(dao::isError()) return false;

        if($charter->status == 'launched') $this->dao->update(TABLE_ROADMAP)->set('status')->eq('launched')->where('id')->in(trim($oldCharter->roadmap, ','))->exec();

        unset($charter->prevCanceledStatus);
        $reviewResult = $charter->reviewResult ? $charter->reviewResult : $oldCharter->reviewResult;
        $changes      = common::createChanges($oldCharter, $charter);
        $actionID     = $this->loadModel('action')->create('charter', $charter->id, 'reviewbycharter', '', ucfirst($reviewResult));
        $this->action->logHistory($actionID, $changes);
        return !dao::isError();
    }

    /**
     * 撤销审批申请。
     * Cancel approval.
     *
     * @param  int    $charterID
     * @access public
     * @return bool
     */
    public function approvalCancel($charterID)
    {
        $approval = $this->loadModel('approval')->getByObject('charter', $charterID);

        $this->loadModel('approval')->cancel('charter', $charterID);

        $beforeStatus = $this->dao->select('status')->from(TABLE_APPROVALOBJECT)->where('objectType')->eq('charter')->andWhere('objectID')->eq($charterID)->andWhere('approval')->ne($approval->id)->andWhere('result')->ne('')->orderBy('approval_desc')->limit(1)->fetch('status');

        $charter = new stdclass();
        $charter->reviewStatus = $beforeStatus ? $beforeStatus : 'wait';
        $this->dao->update(TABLE_CHARTER)->data($charter)->autoCheck()->checkFlow()->where('id')->eq($charterID)->exec();
        if(dao::isError()) return false;

        $approvalObject = new stdclass();
        $approvalObject->opinion = '';
        $approvalObject->result  = 'cancel';
        $approvalObject->status  = $beforeStatus ? $beforeStatus : 'wait';
        $this->dao->update(TABLE_APPROVALOBJECT)->data($approvalObject)->where('objectType')->eq('charter')->andWhere('objectID')->eq($charterID)->andWhere('approval')->eq($approval->id)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action')->create('charter', $charterID, 'cancelapproval');
        return !dao::isError();
    }

    /**
     * Update a charter.
     *
     * @access int $charterID
     * @access public
     * @return void
     */
    public function update($charterID)
    {
        $oldCharter = $this->getByID($charterID);
        $charter = fixer::input('post')
            ->setDefault('deleteFiles', array())
            ->add('roadmap', '')
            ->add('product', '')
            ->add('plan', '')
            ->remove('uid,files,labels,branch')
            ->stripTags($this->config->charter->editor->edit['id'], $this->config->allowedTags)
            ->get();

        if($charter->type == 'plan')    $this->checkProductplan($this->post->plan);
        if($charter->type == 'roadmap') $this->checkProductRoadmap($this->post->roadmap);
        if(dao::isError()) return false;

        $charter = $this->loadModel('file')->processImgURL($charter, $this->config->charter->editor->edit['id'], $this->post->uid);

        $charterProducts = $this->processCharterProducts($charter);

        $this->loadModel('project');
        if(!empty($charter->budget))
        {
            $this->app->loadLang('project');
            if(!is_numeric($charter->budget))                           dao::$errors['budget'] = sprintf($this->lang->project->error->budgetNumber);
            if(is_numeric($charter->budget) and ($charter->budget < 0)) dao::$errors['budget'] = sprintf($this->lang->project->error->budgetGe0);
            $charter->budget = round((float)$this->post->budget, 2);
        }

        if(dao::isError()) return false;

        $this->dao->update(TABLE_CHARTER)->data($charter, 'deleteFiles')->autoCheck()
            ->batchCheck($this->config->charter->edit->requiredFields, 'notempty')
            ->where('id')->eq($charterID)
            ->exec();

        if(!dao::isError())
        {
            if(!empty($charter->deleteFiles))
            {
                $deleteFiles = $charter->deleteFiles;

                $this->dao->delete()->from(TABLE_FILE)->where('id')->in($deleteFiles)->exec();
                foreach($deleteFiles as $fileID) $this->loadModel('file')->unlinkFile($oldCharter->files[$fileID]);
            }

            /* Update product branch plans and roadmaps. */
            $this->dao->delete()->from(TABLE_CHARTERPRODUCT)->where('charter')->eq($charterID)->exec();
            foreach($charterProducts as $charterProduct)
            {
                $charterProduct->charter = $charterID;
                $this->dao->insert(TABLE_CHARTERPRODUCT)->data($charterProduct)->exec();
            }

            $this->saveUpload('charter', $charterID, '', 'files', 'labels', 'projectApproval');
            if($charter->type == 'roadmap')
            {
                $oldRoadmaps = explode(',', trim($oldCharter->roadmap, ','));
                $newRoadmaps = explode(',', trim($charter->roadmap, ','));

                /* 两个数组取差集，再取差集的结果，就是新增的roadmap。 */
                $addRoadmaps = array_diff($newRoadmaps, $oldRoadmaps);
                foreach($addRoadmaps as $roadmapID)
                {
                    $this->dao->update(TABLE_ROADMAP)->set('status')->eq('launching')->where('id')->eq($roadmapID)->exec();
                    $this->loadModel('action')->create('roadmap', $roadmapID, 'linked2charter', '', $charterID);
                }
            }

            return common::createChanges($oldCharter, $charter);
        }

        return false;
    }

    /**
     * Get charter list.
     *
     * @param  int    $browseType
     * @param  int    $queryID
     * @param  int    $orderBy
     * @param  int    $pager
     * @param  string $extra
     * @access public
     * @return void
     */
    public function getList($browseType, $queryID = 0, $orderBy = 'id_desc', $pager = null, $extra = '')
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getCharters();

        $charterQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('charterQuery', $query->sql);
                $this->session->set('charterForm', $query->form);
            }

            if($this->session->charterQuery == false) $this->session->set('charterQuery', ' 1 = 1');

            $charterQuery = str_replace("`reviewStatus` = ''", "(`status` = 'closed' OR (`status` = 'wait' AND `reviewStatus` = 'wait') OR (`status` = 'canceled' AND `prevCanceledStatus` = 'wait'))", $this->session->charterQuery);
            $this->session->set('charterQuery', " $charterQuery");
        }

        $charters = $this->dao->select('*')->from(TABLE_CHARTER)
            ->where('deleted')->eq('0')
            ->beginIF(!in_array($browseType, array('all', 'bysearch', 'reviewing', 'reviewbyme', 'createdbyme')))->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'createdbyme')->andWhere('createdBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'reviewing')->andWhere('reviewStatus')->in('projectDoing,completionDoing,cancelDoing,activateDoing')->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($charterQuery)->fi()
            ->beginIF($browseType == 'reviewbyme')
            ->andWhere('reviewStatus')->in('projectDoing,completionDoing,cancelDoing,activateDoing')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', appliedReviewer)", true)
            ->orWhere("FIND_IN_SET('{$this->app->user->account}', completedReviewer)")
            ->orWhere("FIND_IN_SET('{$this->app->user->account}', canceledReviewer)")
            ->orWhere("FIND_IN_SET('{$this->app->user->account}', activatedReviewer)")
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);

        $charterApprovals = $this->dao->select('t1.id,t1.objectID,t1.extra')->from(TABLE_APPROVAL)->alias('t1')
            ->leftJoin(TABLE_APPROVALOBJECT)->alias('t2')->on('t1.id=t2.approval')
            ->where('t1.objectType')->eq('charter')
            ->andWhere('t1.objectID')->in(array_keys($charters))
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.result')->ne('cancel')
            ->orderBy('id_desc')
            ->fetchGroup('objectID');

        $applicationInfoList = $this->getApprovalList(array_keys($charters), true);

        foreach($charters as $charterID => $charter)
        {
            $charterApproval = isset($charterApprovals[$charterID]) ? current($charterApprovals[$charterID]) : new stdclass();

            $charter->approval = isset($charterApproval->id) ? $charterApproval->id : 0;

            $currentApprovalType  = isset($charterApproval->extra) ? $charterApproval->extra : 0;
            $charter->appliedBy   = isset($applicationInfoList[$charterID][$currentApprovalType]) ? $applicationInfoList[$charterID][$currentApprovalType]['appliedBy']   : '';
            $charter->appliedDate = isset($applicationInfoList[$charterID][$currentApprovalType]) ? $applicationInfoList[$charterID][$currentApprovalType]['appliedDate'] : '';

            $charter->reviewStatusAB = $charter->reviewStatus;
            if($charter->status == 'closed' || ($charter->status == 'wait' && $charter->reviewStatus == 'wait') || ($charter->status == 'canceled' && $charter->prevCanceledStatus == 'wait')) $charter->reviewStatusAB = '';
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'charter', $browseType != 'bysearch');

        return $charters;
    }

    /**
     * Get charter pairs.
     *
     * @param  string $status
     * @param  string $excludeReviewStatus
     * @access public
     * @return void
     */
    public function getPairs($status = 'launched', $excludeReviewStatus = '')
    {
        return $this->dao->select('id, name')->from(TABLE_CHARTER)
            ->where('deleted')->eq('0')
            ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
            ->beginIF($excludeReviewStatus)->andWhere('reviewStatus')->notin($excludeReviewStatus)->fi()
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * Create a charter.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $now     = helper::now();
        $charter = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->setDefault('status', 'wait')
            ->add('product', '')
            ->add('roadmap', '')
            ->add('plan', '')
            ->setIF($this->post->appliedBy, 'appliedDate', $now)
            ->stripTags($this->config->charter->editor->create['id'], $this->config->allowedTags)
            ->remove('uid,files,labels,branch')
            ->get();

        if($charter->type == 'plan')    $this->checkProductplan($this->post->plan);
        if($charter->type == 'roadmap') $this->checkProductRoadmap($this->post->roadmap);
        if(dao::isError()) return false;

        $charter    = $this->loadModel('file')->processImgURL($charter, $this->config->charter->editor->create['id'], $this->post->uid);
        $objectType = $charter->type;

        $charterProducts = $this->processCharterProducts($charter);

        $this->loadModel('project');
        if(!empty($charter->budget))
        {
            if(!is_numeric($charter->budget)) dao::$errors['budget'] = sprintf($this->lang->project->error->budgetNumber);
            if(is_numeric($charter->budget) and ($charter->budget < 0)) dao::$errors['budget'] = sprintf($this->lang->project->error->budgetGe0);
            $charter->budget = round((float)$this->post->budget, 2);
        }

        if(dao::isError()) return false;

        $charter->filesConfig = $this->config->custom->charterFiles;
        $this->dao->insert(TABLE_CHARTER)->data($charter)
            ->autoCheck()
            ->batchCheck($this->config->charter->create->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        $charterID = $this->dao->lastInsertID();

        /* Save product branch plans and roadmaps. */
        foreach($charterProducts as $charterProduct)
        {
            $charterProduct->charter = $charterID;
            $this->dao->insert(TABLE_CHARTERPRODUCT)->data($charterProduct)->exec();
        }

        $this->file->updateObjectID($this->post->uid, $charterID, 'charter');
        $this->saveUpload('charter', $charterID, '', 'files', 'labels', 'projectApproval');

        if($objectType == 'roadmap')
        {
            foreach(explode(',', trim($charter->roadmap, ',')) as $roadmapID)
            {
                $this->dao->update(TABLE_ROADMAP)->set('status')->eq('launching')->where('id')->eq($roadmapID)->exec();
                $this->loadModel('action')->create('roadmap', (int)$roadmapID, 'linked2charter', '', $charterID);
            }
        }

        $this->loadModel('action')->create('charter', $charterID, 'created');

        return $charterID;
    }

    /**
     * Close a charter.
     *
     * @param  int    $charterID
     * @access public
     * @return bool
     */
    public function close($charterID)
    {
        $oldCharter = $this->dao->findById($charterID)->from(TABLE_CHARTER)->fetch();
        $now        = helper::now();
        $charter    = fixer::input('post')
            ->add('status', 'closed')
            ->setDefault('closedDate', $now)
            ->setDefault('closedBy', $this->app->user->account)
            ->setDefault('activatedBy', '')
            ->setDefault('activatedDate', null)
            ->remove('uid')
            ->get();

        $this->dao->update(TABLE_CHARTER)->data($charter, 'comment')
            ->autoCheck()
            ->where('id')->eq((int)$charterID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldCharter, $charter);
        if($changes || $this->post->comment != '')
        {
            $actionID = $this->loadModel('action')->create('charter', $charterID, 'Closed', $this->post->comment, $charter->status);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * Activate a charter.
     *
     * @param  int    $charterID
     * @access public
     * @return bool
     */
    public function activate($charterID)
    {
        $oldCharter = $this->dao->findById($charterID)->from(TABLE_CHARTER)->fetch();

        $now     = helper::now();
        $charter = fixer::input('post')
            ->add('status', 'wait')
            ->setDefault('activatedDate', helper::now())
            ->setDefault('activatedBy', $this->app->user->account)
            ->setDefault('closedBy', '')
            ->setDefault('closedDate', NULL)
            ->setDefault('closedReason', '')
            ->setDefault('prevCanceledStatus', '')
            ->remove('uid')
            ->get();

        $charter = $this->loadModel('file')->processImgURL($charter, 'comment', (string)$this->post->uid);

        $this->dao->update(TABLE_CHARTER)->data($charter, 'comment')
            ->autoCheck()
            ->where('id')->eq((int)$charterID)
            ->exec();

        if(dao::isError()) return false;

        unset($charter->prevCanceledStatus);
        $changes = common::createChanges($oldCharter, $charter);
        if(!empty($changes))
        {
            $actionID = $this->loadModel('action')->create('charter', $charterID, 'Activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }
    /**
     * Get info of uploaded files.
     *
     * @param  string $htmlTagName
     * @param  string $labelsName
     * @param  string $reviewType
     * @access public
     * @return array
     */
    public function getUpload($htmlTagName = 'files', $labelsName = 'labels', $reviewType = '')
    {
        $this->loadModel('file');
        $files = array();
        if(!isset($_FILES[$htmlTagName])) return $files;

        if(!is_array($_FILES[$htmlTagName]['error']) and $_FILES[$htmlTagName]['error'] != 0) return $_FILES[$htmlTagName];

        $this->app->loadClass('purifier', true);
        $config   = HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $purifier = new HTMLPurifier($config);

        extract($_FILES[$htmlTagName]);
        foreach($name as $id => $filename)
        {
            if(empty($filename)) continue;
            if(!validater::checkFileName($filename)) continue;

            $title             = isset($_POST[$labelsName][$id]) ? $_POST[$labelsName][$id] : '';
            $file['extension'] = $this->file->getExtension($filename);
            $file['pathname']  = $this->file->setPathName((int)$id, $file['extension']);
            $file['title']     = (!empty($title) and $title != $filename) ? htmlSpecialString($title) : $filename;
            $file['title']     = $purifier->purify($file['title']);
            $file['size']      = $size[$id];
            $file['tmpname']   = $tmp_name[$id];
            $file['type']      = $id;
            $file['extra']     = $reviewType . '-' . $id;
            $files[] = $file;
        }

        return $files;
    }

    /**
     * Save upload.
     *
     * @param  string $objectType
     * @param  string $objectID
     * @param  string $extra
     * @param  string $filesName
     * @param  string $labelsName
     * @param  string $reviewType
     * @access public
     * @return array
     */
    public function saveUpload($objectType = '', $objectID = '', $extra = '', $filesName = 'files', $labelsName = 'labels', $reviewType = '')
    {
        $this->loadModel('file');
        $fileTitles = array();
        $now        = helper::today();
        $files      = $this->getUpload($filesName, $labelsName, $reviewType);

        foreach($files as $id => $file)
        {
            $type = $file['type'];
            if($file['size'] == 0) continue;
            if(!move_uploaded_file($file['tmpname'], $this->file->savePath . $this->file->getSaveName($file['pathname']))) return false;

            $file = $this->file->compressImage($file);

            $file['objectType'] = $objectType;
            $file['objectID']   = $objectID;
            $file['addedBy']    = $this->app->user->account;
            $file['addedDate']  = $now;
            if($extra) $file['extra'] = $extra;
            unset($file['tmpname']);
            unset($file['type']);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
            $fileID = $this->dao->lastInsertId();
            $fileTitles[$type]['id']    = $fileID;
            $fileTitles[$type]['title'] = $file['title'];
        }
        return $fileTitles;
    }

    /**
     * Build charter operate menu.
     *
     * @param  object $charter
     * @param  string $type
     * @access public
     * @return void
     */
    public function buildOperateMenu($charter, $type = 'view')
    {
        $this->app->loadLang('story');
        $menu    = '';
        $params  = "id={$charter->id}";
        $account = $this->app->user->account;

        $title          = '';
        $disabled       = '';
        $reviewtitle    = '';
        $reviewdisabled = '';

        $roadmap      = $this->loadModel('roadmap')->getByID($charter->roadmap);
        $charterFiled = $this->dao->select(' id, name, reviewedResult')->from(TABLE_CHARTER)
            ->where('roadmap')->eq($charter->roadmap)
            ->fetchAll('id');

        foreach($charterFiled as $Filed)
        {
            if($Filed->reviewedResult === 'launched')
            {
                $charterInfo       =  new stdclass();
                $charterInfo->id   = $Filed->id;
                $charterInfo->name = $Filed->name;
            }
        }

        if($charter->status == 'closed')
        {
            $menu .= $this->buildMenu('charter', 'activate', $params, $charter, $type, 'magic', 'hiddenwin');
        }
        else
        {
            $menu .= $this->buildMenu('charter', 'close', $params, $charter, $type, 'off', '', 'iframe', true, '', $this->lang->close);
        }

        if($type == 'browse')
        {
            $menu .= $this->buildMenu('charter', 'edit', $params, $charter, $type);
            $menu .= $this->buildMenu('charter', 'review', $params, $charter, $type, 'confirm', 'hiddenwin',  "iframe $reviewdisabled", true, $reviewdisabled, $reviewtitle);
        }

        if($type == 'view')
        {
            $menu .= "<div class='divider'></div>";
            $menu .= $this->buildMenu('charter', 'edit', $params, $charter, $type);
            $menu .= $this->buildMenu('charter', 'review', $params, $charter, $type, 'confirm', 'hiddenwin',  "iframe $reviewdisabled", true, $reviewdisabled, $reviewtitle);

            if($charter->status == 'launched') $title = $this->lang->charter->confirmLaunchedNotice;
            if($charter->status != 'wait') $disabled = 'disabled';
            $menu .= $this->buildMenu('charter', 'delete', $params, $charter, 'button', 'trash', 'hiddenwin', "showinonlybody $disabled", true, '', $title);
        }

        return $menu;
    }

    /**
     * Judge btn is clickable.
     *
     * @param  object   $charter
     * @param  string   $action
     * @static
     * @access public
     * @return void
     */
    public static function isClickable($charter, $action)
    {
        global $app;
        $action = strtolower($action);

        if($charter->deleted) return false;

        if($action == 'close')                   return $charter->status == 'completed' || ($charter->status == 'canceled' && $charter->reviewStatus != 'activateDoing');
        if($action == 'review')                  return $app->control->loadModel('approval')->canApproval($charter);
        if($action == 'projectapproval')         return $charter->status == 'wait'     && $charter->reviewStatus != 'projectDoing';
        if($action == 'completionapproval')      return $charter->status == 'launched' && $charter->reviewStatus != 'completionDoing' && $charter->reviewStatus != 'cancelDoing';
        if($action == 'activateprojectapproval') return ($charter->status == 'canceled' || ($charter->status == 'closed' && $charter->closedReason == 'canceled')) && $charter->reviewStatus != 'activateDoing';
        if($action == 'edit')                    return self::isClickable($charter, 'projectapproval') || self::isClickable($charter, 'activateprojectapproval');
        if($action == 'delete')                  return $charter->reviewStatus == 'wait' && empty($charter->approval);
        if($action == 'cancelprojectapproval')   return self::isClickable($charter, 'projectapproval') || self::isClickable($charter, 'completionapproval');
        if($action == 'approvalcancel')          return $app->control->loadModel('approval')->canCancel($charter);

        return true;
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $charterFiles = json_decode($this->config->custom->charterFiles);
        $levelList    = array();
        foreach($charterFiles as $groups) $levelList[$groups->key] = $groups->level;

        unset($this->config->charter->search['params']['reviewStatus']['values']['wait']);

        $this->config->charter->search['actionURL'] = $actionURL;
        $this->config->charter->search['queryID']   = $queryID;
        $this->config->charter->search['params']['level']['values'] = $levelList;
        $this->loadModel('search')->setSearchParams($this->config->charter->search);
    }

    /**
     * 通过charterID获取产品路标或产品计划分组。
     * Get product - roadmaps or product - plans groups by charterID.
     *
     * @param  int    $charterID
     * @access public
     * @return array
     */
    public function getGroupDataByID($charterID = 0)
    {
        $charter = $this->getByID($charterID);
        if(!$charter) return array();

        if(empty(trim($charter->product, ','))) return array();

        $objectType = $charter->type;
        $table      = $objectType == 'roadmap' ? TABLE_ROADMAP : TABLE_PRODUCTPLAN;
        $name       = $objectType == 'roadmap' ? 'name' : 'title';

        $productGroups = $this->dao->select("t1.*, t2.name as productName, '$objectType' as linkedType, t1.$name as linkedName")->from($table)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.id')->in(trim($charter->$objectType, ','))
            ->fetchGroup('product', 'id');

        foreach(explode(',', trim($charter->product, ',')) as $productID)
        {
            if(!isset($productGroups[$productID])) $productGroups[$productID] = array();
        }

        return $productGroups;
    }

    /**
     * 删除附件时更新charterFiles。
     * Update charterFiles when deleting attachments.
     *
     * @param  object $deleteFile
     * @access public
     * @return bool
     */
    public function updateFileByDelete($deleteFile = null)
    {
        $charter = $this->dao->findByID($deleteFile->objectID)->from(TABLE_CHARTER)->fetch();
        if(empty($charter)) return false;

        $charter->files = $this->loadModel('file')->getByObject('charter', $charter->id);
        $charterFiles   = $charter->charterFiles ? json_decode($charter->charterFiles , true) : array();

        foreach($charterFiles as $key => $file)
        {
            if($deleteFile->id == $file['id']) unset($charterFiles[$key]);
        }

        $charterFiles = $charterFiles ? json_encode($charterFiles) : null;

        $this->dao->update(TABLE_CHARTER)->set('charterFiles')->eq($charterFiles)->where('id')->eq($charter->id)->exec();
        return true;
    }

    /**
     * 发起立项审批。
     * Initiate project approval.
     *
     * @param  int    $charterID
     * @access public
     * @return bool|array
     */
    public function projectApproval($charterID = 0)
    {
        $oldCharter = $this->getByID($charterID);
        $newCharter = $this->saveApprovalInfo($oldCharter, 'projectDoing', 'projectApproval');
        if(!$newCharter || dao::isError()) return false;

        $newCharter->appliedReviewer = $newCharter->reviewer;
        $this->dao->update(TABLE_CHARTER)->data($newCharter, 'reviewer')->where('id')->eq($charterID)->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldCharter, $newCharter);
        if(!empty($changes))
        {
            $actionID = $this->loadModel('action')->create('charter', $charterID, 'projectApproval');
            $this->action->logHistory($actionID, $changes);
        }

        if($newCharter->reviewStatus != 'projectDoing')
        {
            $roadmapStatus = $newCharter->reviewStatus == 'projectPass' ? 'launched' : 'reject';
            $this->dao->update(TABLE_ROADMAP)->set('status')->eq($roadmapStatus)->where('id')->in($oldCharter->roadmap)->exec();
        }
        if($newCharter->status == 'launched' && !empty(trim($oldCharter->roadmap, ','))) $this->updateRoadmapStoriesStage($oldCharter);

        return !dao::isError();
    }

    /**
     * 发起结项审批。
     * Initiate project approval.
     *
     * @param  int    $charterID
     * @access public
     * @return bool|array
     */
    public function completionApproval($charterID = 0)
    {
        $oldCharter = $this->getByID($charterID);
        $newCharter = $this->saveApprovalInfo($oldCharter, 'completionDoing', 'completionApproval');
        if(!$newCharter || dao::isError()) return false;

        $this->loadModel('file')->updateObjectID($this->post->uid, $charterID, 'charter');
        $this->saveUpload('charter', $charterID, '', 'files', 'labels', 'completeApproval');

        $newCharter->completedBy       = $this->app->user->account;
        $newCharter->completedDate     = helper::now();
        $newCharter->completedReviewer = $newCharter->reviewer;
        $this->dao->update(TABLE_CHARTER)->data($newCharter, 'reviewer')->where('id')->eq($charterID)->exec();
        if(dao::isError()) return false;

        if($this->post->deleteFiles)
        {
            $deleteFiles = $this->post->deleteFiles;
            $this->dao->delete()->from(TABLE_FILE)->where('id')->in($deleteFiles)->exec();
            foreach($deleteFiles as $fileID) $this->loadModel('file')->unlinkFile($oldCharter->files[$fileID]);
        }

        $changes = common::createChanges($oldCharter, $newCharter);
        if(!empty($changes))
        {
            $actionID = $this->loadModel('action')->create('charter', $charterID, 'CompletionApproval');
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 发起取消立项审批。
     * Cancel project approval.
     *
     * @param  int    $charterID
     * @access public
     * @return bool
     */
    public function cancelProjectApproval($charterID = 0)
    {
        $oldCharter = $this->getByID($charterID);
        $newCharter = $this->saveApprovalInfo($oldCharter, 'cancelDoing', 'cancelProjectApproval');
        if(!$newCharter || dao::isError()) return false;

        $this->loadModel('file')->updateObjectID($this->post->uid, $charterID, 'charter');
        $this->saveUpload('charter', $charterID, '', 'files', 'labels', 'cancelApproval');

        $newCharter->canceledBy       = $this->app->user->account;
        $newCharter->canceledDate     = helper::now();
        $newCharter->canceledReviewer = $newCharter->reviewer;
        $this->dao->update(TABLE_CHARTER)->data($newCharter, 'reviewer')->where('id')->eq($charterID)->exec();
        if(dao::isError()) return false;

        if($newCharter->status == 'canceled') $this->dao->update(TABLE_ROADMAP)->set('status')->eq('wait')->where('id')->in(trim($oldCharter->roadmap, ','))->exec();

        if($this->post->deleteFiles)
        {
            $deleteFiles = $this->post->deleteFiles;
            $this->dao->delete()->from(TABLE_FILE)->where('id')->in($deleteFiles)->exec();
            foreach($deleteFiles as $fileID) $this->loadModel('file')->unlinkFile($oldCharter->files[$fileID]);
        }

        unset($newCharter->prevCanceledStatus);
        $changes = common::createChanges($oldCharter, $newCharter);
        if(!empty($changes))
        {
            $actionID = $this->loadModel('action')->create('charter', $charterID, 'CancelProjectApproval');
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 取消待立项的立项。
     * Cancel a charter.
     *
     * @param  int    $charterID
     * @access public
     * @return bool
     */
    public function cancel($charterID = 0)
    {
        $oldCharter = $this->dao->findById($charterID)->from(TABLE_CHARTER)->fetch();

        $charter = fixer::input('post')->remove('uid')->get();
        $charter->status             = 'canceled';
        $charter->prevCanceledStatus = 'wait';
        $charter->canceledBy         = $this->app->user->account;
        $charter->canceledDate       = helper::now();

        $charter = $this->loadModel('file')->processImgURL($charter, 'comment', (string)$this->post->uid);

        $this->dao->update(TABLE_CHARTER)->data($charter, 'comment')->where('id')->eq($charterID)->exec();
        if(dao::isError()) return false;

        unset($charter->prevCanceledStatus);
        $changes = common::createChanges($oldCharter, $charter);
        if(!empty($changes))
        {
            $actionID = $this->loadModel('action')->create('charter', $charterID, 'Canceled', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 激活立项审批
     * Activate project approval.
     *
     * @param  int    $charterID
     * @access public
     * @return bool
     */
    public function activateProjectApproval($charterID = 0)
    {
        $oldCharter = $this->getByID($charterID);
        $newCharter = $this->saveApprovalInfo($oldCharter, 'activateDoing', 'activateProjectApproval');
        if(!$newCharter || dao::isError()) return false;

        $newCharter->activatedDate     = helper::now();
        $newCharter->activatedBy       = $this->app->user->account;
        $newCharter->activatedReviewer = $newCharter->reviewer;
        $newCharter->closedBy          = '';
        $newCharter->closedDate        = NULL;
        $newCharter->closedReason      = '';

        $this->dao->update(TABLE_CHARTER)->data($newCharter, 'reviewer')->where('id')->eq($charterID)->exec();
        if(dao::isError()) return false;

        if($newCharter->status == 'launched') $this->dao->update(TABLE_ROADMAP)->set('status')->eq('launched')->where('id')->in(trim($oldCharter->roadmap, ','))->exec();

        $changes = common::createChanges($oldCharter, $newCharter);
        if(!empty($changes))
        {
            $actionID = $this->loadModel('action')->create('charter', $charterID, 'ActivateProjectApproval');
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 保存审批信息。
     * Save approval info.
     *
     * @param  object $charter
     * @param  string $reviewStatus
     * @param  string $approvalType  projectApproval|completionApproval|cancelProjectApproval|activateProjectApproval
     * @access public
     * @return bool|object
     */
    public function saveApprovalInfo($charter, $reviewStatus = '', $approvalType = 'projectApproval')
    {
        $reviewers = $this->post->approval_reviewer ? $this->post->approval_reviewer : array();
        $ccers     = $this->post->approval_ccer     ? $this->post->approval_ccer     : array();
        $idList    = $this->post->approval_id       ? $this->post->approval_id       : array();

        $reviewerList = array();
        if($reviewers)
        {
            foreach($reviewers as $key => $reviewer)
            {
                $nodeID = $idList[$key];
                $reviewerList[$nodeID] = $reviewer;
            }
        }

        $ccerList = array();
        if($ccers)
        {
            foreach($ccers as $key => $ccer)
            {
                $nodeID = $idList[$key];
                $ccerList[$nodeID] = $ccer;
            }
        }

        $result = $this->loadModel('approval')->createApprovalObject(0, $charter->id, 'charter', $reviewerList, $ccerList, $idList);

        $approvalObject = form::data($this->config->charter->form->$approvalType)
            ->add('objectType', 'charter')
            ->add('objectID', $charter->id)
            ->add('approval', $result['approvalID'])
            ->add('reviewers', '')
            ->add('opinion', '')
            ->add('result', '')
            ->add('status', $reviewStatus)
            ->add('extra', $approvalType)
            ->setIF(!$this->post->desc, 'desc', '')
            ->get();

        $approvalObject = $this->loadModel('file')->processImgURL($approvalObject, 'desc', $this->post->uid);

        $newCharter = form::data(array())->get();
        $newCharter->status       = $charter->status;
        $newCharter->reviewStatus = $reviewStatus;
        if($approvalType == 'projectApproval')
        {
            $newCharter->appliedBy   = $approvalObject->appliedBy;
            $newCharter->appliedDate = $approvalObject->appliedDate;
        }

        if($result['result'] === 'pass')
        {
            $approvalObject->result = 'pass';

            if($reviewStatus == 'projectDoing')
            {
                $approvalObject->status     = 'projectPass';
                $newCharter->reviewStatus   = 'projectPass';
                $newCharter->status         = 'launched';
            }

            if($reviewStatus == 'completionDoing')
            {
                $approvalObject->status   = 'completionPass';
                $newCharter->reviewStatus = 'completionPass';
                $newCharter->status       = 'completed';
            }

            if($reviewStatus == 'cancelDoing')
            {
                $approvalObject->status         = 'cancelPass';
                $newCharter->reviewStatus       = 'cancelPass';
                $newCharter->status             = 'canceled';
                $newCharter->prevCanceledStatus = 'launched';
            }

            if($reviewStatus == 'activateDoing')
            {
                $approvalObject->status         = 'activatePass';
                $newCharter->reviewStatus       = 'activatePass';
                $newCharter->status             = 'launched';
                $newCharter->prevCanceledStatus = '';
            }
        }
        if($result['result'] === 'fail')
        {
            $approvalObject->result = 'reject';

            if($reviewStatus == 'projectDoing')
            {
                $approvalObject->status   = 'projectReject';
                $newCharter->reviewStatus = 'projectReject';
            }

            if($reviewStatus == 'completionDoing')
            {
                $approvalObject->status   = 'completionReject';
                $newCharter->reviewStatus = 'completionReject';
            }

            if($reviewStatus == 'cancelDoing')
            {
                $approvalObject->status   = 'cancelReject';
                $newCharter->reviewStatus = 'cancelReject';
            }

            if($reviewStatus == 'activateDoing')
            {
                $approvalObject->status   = 'activateReject';
                $newCharter->reviewStatus = 'activateReject';
            }
        }

        if(!empty($result['approvalID']))
        {
            $reviewers = $this->approval->getCurrentReviewers($result['approvalID']);
            $approvalObject->reviewers = implode(',', $reviewers);
        }

        $this->dao->update(TABLE_APPROVALOBJECT)->data($approvalObject)->where('objectType')->eq('charter')->andWhere('objectID')->eq($charter->id)->andWhere('approval')->eq($result['approvalID'])->exec();

        if(dao::isError()) return false;

        $newCharter->reviewer = $approvalObject->reviewers;

        return $newCharter;
    }

    /**
     * 获取审批列表。
     * Get approval list.
     *
     * @param  array  $charterIdList
     * @access public
     * @return array
     * @param bool $withoutCancel
     */
    public function getApprovalList($charterIdList, $withoutCancel = false)
    {
        $approvals = $this->dao->select('*')->from(TABLE_APPROVALOBJECT)
            ->where('objectID')->in($charterIdList)
            ->andWhere('objectType')->eq('charter')
            ->beginIF($withoutCancel)->andWhere('result')->ne('cancel')->fi()
            ->orderBy('id_asc')
            ->fetchAll('id', false);

        $approvalList = array();
        foreach($charterIdList as $charterID) $approvalList[$charterID] = array();
        foreach($approvals as $approval) $approvalList[$approval->objectID][$approval->extra] = json_decode(json_encode($approval), true);
        return $approvalList;
    }

    /**
     * 获取审批ID。
     * Get approval ID.
     *
     * @param  int    $charterID
     * @access public
     * @return int
     */
    public function getApprovalID($charterID)
    {
        $approvalID = $this->dao->select('id')->from(TABLE_APPROVAL)->where('objectType')->eq('charter')->andWhere('objectID')->eq($charterID)->andWhere('deleted')->eq('0')->orderBy('id_desc')->limit(1)->fetch('id');
        return $approvalID ? $approvalID : 0;
    }

    /**
     * 获取审批流code。
     * Get approval flow extra.
     *
     * @param  object  $charter
     * @access public
     * @return string
     */
    public function getApprovalFlowExtra($charter)
    {
        $extra = '';
        if($charter->status == 'wait')                                                         $extra = 'projectApproval';
        if($charter->status == 'launched' && $this->app->rawMethod == 'completionapproval')    $extra = 'completionApproval';
        if($charter->status == 'launched' && $this->app->rawMethod == 'cancelprojectapproval') $extra = 'cancelProjectApproval';
        if($charter->status == 'canceled' || $charter->status == 'closed')                     $extra = 'activateProjectApproval';

        return $extra;
    }

    /**
     * 更新路标需求状态。
     * Update roadmap's stories stage.
     *
     * @param  object  $oldCharter
     * @access public
     * @return bool
     */
    public function updateRoadmapStoriesStage($oldCharter)
    {
        $this->loadModel('story');

        $oldCharter->product = trim($oldCharter->product, ',');
        $oldCharter->roadmap = trim($oldCharter->roadmap, ',');

        $products = $this->loadModel('product')->getByIdList(explode(',', $oldCharter->product));
        foreach($products as $productID => $product)
        {
            if($product->status != 'wait') continue;

            $this->dao->update(TABLE_PRODUCT)->set('status')->eq('normal')->set('vision')->eq('or,rnd')->where('id')->eq($productID)->exec();
            $this->action->create('product', $productID, 'changedbycharter', '', $oldCharter->id);
        }

        $roadmapStories = $this->loadModel('roadmap')->getRoadmapStories($oldCharter->roadmap);
        $demands        = $this->loadModel('demand')->getList(0, 'all');

        foreach($roadmapStories as $story)
        {
            if($story->stage == 'incharter') continue;
            $this->dao->update(TABLE_STORY)
                ->set('stage')->eq('incharter')
                ->set('vision')->eq($story->vision . ',rnd')
                ->where('id')->eq($story->id)
                ->exec();

            if(is_array($story->parent)) $story->parent = current($story->parent);
            $this->story->computeParentStage($story);
            $this->action->create('story', $story->id, 'changedbycharter', '', $oldCharter->id);
            if($story->demand)
            {
                $this->demand->updateDemandStage(array($story->demand));
                if(!empty($demands[$story->demand]->feedback))
                {
                    $feedback = new stdclass();
                    $feedback->status        = 'commenting';
                    $feedback->processedBy   = $this->app->user->account;
                    $feedback->processedDate = helper::now();

                    $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($demands[$story->demand]->feedback)->exec();
                }
            }

            if($story->feedback)
            {
                $feedback = new stdclass();
                $feedback->status        = 'commenting';
                $feedback->processedBy   = $this->app->user->account;
                $feedback->processedDate = helper::now();

                $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($story->feedback)->exec();
            }
        }

        $this->loadModel('score')->create('charter', 'confirmCharter', $oldCharter);

        return !dao::isError();
    }

    /**
     * 检查产品计划是否可以立项。
     * Check if product plan can be launched.
     *
     * @param  array  $plans
     * @param  int    $charterID
     * @access public
     * @return bool
     */
    public function checkProductplan($plans, $charterID = 0)
    {
        $planIdList = array();
        foreach($plans as $planGroups) $planIdList = array_merge($planIdList, $planGroups);
        $planList = $this->loadModel('productplan')->getByIDList(array_unique($planIdList));

        $charters = $this->dao->select('id,product,plan,status,reviewStatus,closedReason')->from(TABLE_CHARTER)
            ->where('deleted')->eq(0)
            ->beginIF($charterID)->andWhere('id')->ne($charterID)->fi()
            ->fetchAll('id', false);

        foreach($plans as $index => $planGroups)
        {
            if(empty($planGroups)) continue;

            $repeatPlanTitle = '';
            $donePlanTitle   = '';
            foreach($planGroups as $planID)
            {
                if(empty($planID)) continue;

                $hasDone   = false;
                $hasRepeat = false;
                $plan      = $planList[$planID];

                if($plan->status == 'done' || ($plan->status == 'closed' && $plan->closedReason == 'cancel')) $hasDone = true;

                foreach($charters as $charter)
                {
                    if(strpos($charter->plan, ",$planID,") !== false)
                    {
                        if($plan->status == 'wait' || $plan->status == 'doing')
                        {
                            if($charter->reviewStatus == 'projectDoing' || $charter->reviewStatus == 'activateDoing' || $charter->status == 'launched' || $charter->status == 'completed')
                            {
                                $hasRepeat = true;
                                break;
                            }
                        }
                    }
                }

                if($hasRepeat)              $repeatPlanTitle .= '"' . $plan->title . '",';
                if(!$hasRepeat && $hasDone) $donePlanTitle   .= '"' . $plan->title . '",';
            }

            if($repeatPlanTitle || $donePlanTitle) dao::$errors["plan[$index][]"] = '';
            if($repeatPlanTitle) dao::$errors["plan[$index][]"]  = sprintf($this->lang->charter->tips->repeatProductplan, trim($repeatPlanTitle, ','));
            if($repeatPlanTitle && $donePlanTitle) dao::$errors["plan[$index][]"] .= "\n";
            if($donePlanTitle) dao::$errors["plan[$index][]"] .= sprintf($this->lang->charter->tips->doneProductplan, trim($donePlanTitle, ','));
        }

        return true;
    }

    /**
     * 检查产品路标是否可以立项。
     * Check if product roadmap can be launched.
     *
     * @param  array  $roadmaps
     * @param  int    $charterID
     * @access public
     * @return bool
     */
    public function checkProductRoadmap($roadmaps, $charterID = 0)
    {
        $roadmapIdList = array();
        foreach($roadmaps as $roadmapGroups) $roadmapIdList = array_merge($roadmapIdList, $roadmapGroups);
        $roadmapList = $this->loadModel('roadmap')->getByIdList(array_unique($roadmapIdList));

        $charters = $this->dao->select('id,product,roadmap,status,reviewStatus,closedReason')->from(TABLE_CHARTER)
            ->where('deleted')->eq(0)
            ->beginIF($charterID)->andWhere('id')->ne($charterID)->fi()
            ->fetchAll('id', false);

        foreach($roadmaps as $index => $roadmapGroups)
        {
            if(empty($roadmapGroups)) continue;

            $repeatRoadmapTitle = '';
            foreach($roadmapGroups as $roadmapID)
            {
                if(empty($roadmapID)) continue;

                if($roadmapList[$roadmapID]->status == 'launched')
                {
                    $repeatRoadmapTitle .= '"' . $roadmapList[$roadmapID]->name . '",';
                    break;
                }

                foreach($charters as $charter)
                {
                    if(strpos($charter->roadmap, ",$roadmapID,") !== false)
                    {
                        if($charter->status == 'completed' || ($charter->status == 'closed' && $charter->closedReason == 'done'))
                        {
                            $repeatRoadmapTitle .= '"' . $roadmapList[$roadmapID]->name . '",';
                            break;
                        }

                        if($charter->reviewStatus == 'projectDoing' || $charter->reviewStatus == 'activateDoing' || $charter->reviewStatus == 'completionDoing' || $charter->reviewStatus == 'cancelDoing')
                        {
                            $repeatRoadmapTitle .= '"' . $roadmapList[$roadmapID]->name . '",';
                            break;
                        }
                    }
                }
            }

            if($repeatRoadmapTitle) dao::$errors["roadmap[$index][]"] = sprintf($this->lang->charter->tips->repeatProductplan, trim($repeatRoadmapTitle, ','));
        }

        return true;
    }

    /**
     * 获取立项的项目集和项目列表。
     * Get program and project list.
     *
     * @param  int    $charterID
     * @param  bool   $withClosed
     * @access public
     * @return array
     */
    public function getProgramAndProject($charterID, $withClosed = true)
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('charter')->eq($charterID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$withClosed)->andWhere('status')->ne('closed')->fi()
            ->fetchAll('id');
    }

    /**
     * Get to and ccList.
     *
     * @param  object $feedback
     * @access public
     * @return array
     */
    public function getToAndCcList($charter)
    {
        /* Set toList and ccList. */
        $toList = $charter->appliedBy;
        $ccList = '';

        if(empty($toList))
        {
            if(empty($ccList)) return array();
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList = substr($ccList, 0, $commaPos);
                $ccList = substr($ccList, $commaPos + 1);
            }
        }

        return array($toList, $ccList);
    }

    /**
     * Get linked branch groups.
     *
     * @param  int    $charterID
     * @access public
     * @return array
     */
    public function getLinkedBranchGroups($charterID)
    {
        $branchGroups     = array();
        $charter          = $this->fetchByID($charterID);
        $charterProducts  = $this->dao->select('product,branch')->from(TABLE_CHARTERPRODUCT)->where('charter')->eq($charterID)->fetchAll();
        $allBranchPairs   = $this->loadModel('branch')->getAllPairs('noproductname');
        $multipleProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->andWhere('type')->in('branch,platform')->andWhere('id')->in($charter->product)->fetchPairs();
        foreach($charterProducts as $charterProduct)
        {
            if(!isset($multipleProducts[$charterProduct->product])) continue;
            if(!isset($branchGroups[$charterProduct->product])) $branchGroups[$charterProduct->product] = array();
            $branchGroups[$charterProduct->product][$charterProduct->branch] = $allBranchPairs[$charterProduct->branch];
        }
        return $branchGroups;
    }

    /**
     * Process charter products.
     *
     * @param  object $charter
     * @access public
     * @return array
     */
    public function processCharterProducts($charter)
    {
        $this->loadModel('project');
        $charterProducts  = array();
        $objectType       = $charter->type;
        $multipleProducts = $this->dao->select('id,type')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->andWhere('type')->in('branch,platform')->fetchPairs();
        foreach($this->post->product as $index => $productID)
        {
            if(empty($productID)) continue;

            if(empty($charter->product))     $charter->product     .= ',';
            if(empty($charter->$objectType)) $charter->$objectType .= ',';

            $charter->product .= $productID . ',';
            $objects           = zget($this->post->$objectType, $index, array());
            if($objects) $charter->$objectType .= implode(',', $objects) . ',';

            $charterProduct = new stdclass();
            $charterProduct->charter       = isset($charter->id) ? $charter->id : 0;
            $charterProduct->product       = $productID;
            $charterProduct->{$objectType} = implode(',', $objects);
            $branches = !empty($_POST['branch'][$index]) ? $_POST['branch'][$index] : array();
            if(isset($multipleProducts[$productID]))
            {
                foreach($branches as $key => $branch)
                {
                    if($branch === '')
                    {
                        unset($branches[$key]);
                        continue;
                    }
                    $charterProduct->branch = $branch;
                    $charterProducts[] = clone $charterProduct;
                }
                if(empty($branches)) dao::$errors["branch[{$index}][]"][] = sprintf($this->lang->error->notempty, $this->lang->product->branchName[$multipleProducts[$productID]]);
            }
            else
            {
                $charterProduct->branch = '0';
                $charterProducts[] = $charterProduct;
            }
        }
        return $charterProducts;
    }
}
