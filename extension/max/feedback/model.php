<?php
/**
 * The model file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class feedbackModel extends model
{
    /**
     * Create a feedback.
     *
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $needReview = !$this->forceNotReview();
        if($needReview)
        {
            $assignedTo = $this->config->feedback->reviewer;
            if(empty($assignedTo)) $assignedTo = $this->loadModel('dept')->getManager($this->app->user->dept);
            if($assignedTo == 'feedbackmanager') $assignedTo = $this->dao->select('feedback')->from(TABLE_PRODUCT)->where('id')->eq($this->post->product)->fetch('feedback');
        }
        else
        {
            $assignedTo = $this->dao->findByID($this->post->product)->from(TABLE_PRODUCT)->fetch('feedback');
        }

        $now      = helper::now();
        $status   = $this->getStatus('create', $needReview);
        $feedback = fixer::input('post')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->add('status', $status)
            ->setDefault('module', 0)
            ->setIF(!empty($assignedTo), 'assignedTo', $assignedTo)
            ->setIF(!empty($assignedTo), 'assignedDate', $now)
            ->setDefault('product', 0)
            ->join('mailto', ',')
            ->stripTags($this->config->feedback->editor->create['id'])
            ->remove('files,labels,uid,contactList,fileList,deleteFiles,renameFiles')
            ->get();

        $feedback = $this->loadModel('file')->processImgURL($feedback, $this->config->feedback->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_FEEDBACK)->data($feedback)
            ->autoCheck()
            ->batchCheck($this->config->feedback->create->requiredFields, 'notempty')
            ->checkIF($feedback->notifyEmail, 'notifyEmail', 'email')
            ->checkFlow()
            ->exec();

        if(!dao::isError())
        {
            $feedbackID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $feedbackID, 'feedback');
            $this->file->saveUpload('feedback', $feedbackID);
            $this->updateSubStatus($feedbackID, $status);
            return $feedbackID;
        }
        return false;
    }

    /**
     * 批量创建反馈。
     * Batch create feedback.
     *
     * @param  int    $productID
     * @param  array  $feedbacks
     * @access public
     * @return array
     */
    public function batchCreate($productID = 0, $feedbacks = array())
    {
        $this->loadModel('action');

        $needReview = !$this->forceNotReview();
        if($needReview)
        {
            $assignedTo = $this->config->feedback->reviewer;
            if(empty($assignedTo)) $assignedTo = $this->loadModel('dept')->getManager($this->app->user->dept);
            if($assignedTo == 'feedbackmanager') $assignedTo = $this->dao->select('feedback')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch('feedback');
        }
        else
        {
            $assignedTo = $this->dao->findByID($this->post->product)->from(TABLE_PRODUCT)->fetch('feedback');
        }

        $feedbackIdList = array();
        $now            = helper::now();
        $status         = $this->getStatus('create', $needReview);
        foreach($feedbacks as $rowIndex => $feedback)
        {
            if($feedback->notifyEmail && !filter_var($feedback->notifyEmail, FILTER_VALIDATE_EMAIL)) dao::$errors["notifyEmail[{$rowIndex}]"] = sprintf($this->lang->error->email, $this->lang->feedback->notifyEmail);
        }
        if(dao::isError()) return false;

        foreach($feedbacks as $rowIndex => $feedback)
        {
            $feedback->product    = $productID;
            $feedback->status     = $status;
            $feedback->openedBy   = $this->app->user->account;
            $feedback->openedDate = $now;
            $feedback->assignedTo = $assignedTo;
            if(!empty($assignedTo)) $feedback->assignedDate = $now;

            $this->dao->insert(TABLE_FEEDBACK)->data($feedback)
                ->autoCheck()
                ->checkFlow()
                ->exec();

            if(dao::isError()) return false;

            $feedbackID = $this->dao->lastInsertID();
            $this->action->create('feedback', $feedbackID, 'Opened');

            $feedbackIdList[$feedbackID] = $feedbackID;
            if($this->post->uploadImage && $this->post->uploadImage[$rowIndex]) $this->doSaveUploadImage($feedbackID, $this->post->uploadImage[$rowIndex], $feedback->desc);
            $this->updateSubStatus($feedbackID, $status);
            if(dao::isError()) return false;
        }
        if($this->session->feedbackImagesFile)
        {
            $file     = current($_SESSION['feedbackImagesFile']);
            $realPath = dirname($file['realpath']);
            if(is_dir($realPath)) $this->app->loadClass('zfile')->removeDir($realPath);
            unset($_SESSION['feedbackImagesFile']);
        }
        if($this->session->feedbackImagesFile)
        {
            $file     = current($_SESSION['feedbackImagesFile']);
            $realPath = dirname($file['realpath']);
            if(is_dir($realPath)) $this->app->loadClass('zfile')->removeDir($realPath);
            unset($_SESSION['feedbackImagesFile']);
        }
        return $feedbackIdList;
    }

    /**
     * 保存上传图片作为反馈内容。
     * Do save upload image.
     *
     * @param  int       $feedbackID
     * @param  string    $fileName
     * @param string $desc
     * @access public
     * @return void
     */
    public function doSaveUploadImage($feedbackID, $fileName, $desc)
    {
        $feedbackImageFiles = $this->session->feedbackImagesFile;
        if(empty($feedbackImageFiles)) return;
        if(empty($feedbackImageFiles[$fileName])) return;

        $file     = $feedbackImageFiles[$fileName];
        $realPath = $file['realpath'];
        unset($file['realpath']);
        if(!file_exists($realPath)) return;

        $this->loadModel('file');
        if(!is_dir($this->file->savePath)) mkdir($this->file->savePath, 0777, true);
        if($realPath && rename($realPath, $this->file->savePath . $this->file->getSaveName($file['pathname'])))
        {
            $file['addedBy']    = $this->app->user->account;
            $file['addedDate']  = helper::now();
            $file['objectType'] = 'feedback';
            $file['objectID']   = $feedbackID;

            $isImage = in_array($file['extension'], $this->config->file->imageExtensions);
            if($isImage) $file['extra'] = 'editor';

            $this->dao->insert(TABLE_FILE)->data($file)->exec();
            $fileID = $this->dao->lastInsertID();

            if($isImage) $desc .= '<img src="{' . $fileID . '.' . $file['extension'] . '}" alt="" />';
            $this->dao->update(TABLE_FEEDBACK)->set('desc')->eq($desc)->where('id')->eq($feedbackID)->exec();
        }

    }

    /**
     * Update a feedback.
     *
     * @param  int    $id
     * @access public
     * @return array|bool
     */
    public function update($id)
    {
        $oldFeedback = $this->getById($id);
        if(strpos('noreview|wait|clarify', $oldFeedback->status) === false) return false;

        $oldProduct       = $this->loadModel('product')->fetchByID($oldFeedback->product);
        $changeAssignedTo = ($oldFeedback->product != $this->post->product) && ($oldFeedback->assignedTo == $oldProduct->feedback || empty($oldFeedback->assignedTo));

        $needReview = !$this->forceNotReview();
        $status     = $this->getStatus('update', $needReview, $oldFeedback->status);

        $assignedTo = $oldFeedback->assignedTo;
        if($status == 'noreview')
        {
            $assignedTo = $this->config->feedback->reviewer;
            if(empty($assignedTo)) $assignedTo = $this->loadModel('dept')->getManager($this->app->user->dept);
            if($assignedTo == 'feedbackmanager') $assignedTo = $this->dao->select('feedback')->from(TABLE_PRODUCT)->where('id')->eq($this->post->product)->fetch('feedback');
        }
        elseif($changeAssignedTo)
        {
            $assignedTo = $this->dao->findByID($this->post->product)->from(TABLE_PRODUCT)->fetch('feedback');
        }

        $now      = helper::now();
        $feedback = fixer::input('post')
            ->add('id', $id)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->add('status', $status)
            ->setDefault('module', 0)
            ->setDefault('product', 0)
            ->setDefault('deleteFiles', array())
            ->setIF($this->post->public != 1, 'public', 0)
            ->setIF($this->post->notify != 1, 'notify', 0)
            ->setIF($assignedTo != $oldFeedback->assignedTo, 'assignedTo', $assignedTo)
            ->stripTags($this->config->feedback->editor->edit['id'])
            ->join('mailto', ',')
            ->remove('files,labels,uid,contactList,renameFiles')
            ->get();

        $feedback = $this->loadModel('file')->processImgURL($feedback, $this->config->feedback->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_FEEDBACK)->data($feedback, 'deleteFiles')
            ->autoCheck()
            ->batchCheck($this->config->feedback->edit->requiredFields, 'notempty')
            ->checkIF($feedback->notifyEmail, 'notifyEmail', 'email')
            ->checkFlow()
            ->where('id')->eq($id)->exec();

        if(dao::isError()) return false;

        $this->file->processFile4Object('feedback', $oldFeedback, $feedback);
        return common::createChanges($oldFeedback, $feedback);
    }

    /**
     * Batch update feedbacks.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $data = fixer::input('post')->get();
        $oldFeedbacks = $this->getByList(array_keys($data->title));
        $extendFields = $this->getFlowExtendFields();

        $now       = helper::now();
        $changes   = array();
        $feedbacks = array();
        foreach($data->title as $feedbackID => $title)
        {
            $oldFeedback = $oldFeedbacks[$feedbackID];

            $feedback = new stdclass();
            $feedback->id         = $feedbackID;
            $feedback->title      = $title;
            $feedback->editedBy   = $this->app->user->account;
            $feedback->editedDate = $now;
            $feedback->product    = $data->product[$feedbackID];
            $feedback->module     = $data->module[$feedbackID];
            $feedback->assignedTo = $data->assignedTo[$feedbackID];
            if($feedback->assignedTo != $oldFeedback->assignedTo) $feedback->assignedDate = $now;

            foreach($extendFields as $extendField)
            {
                $feedback->{$extendField->field} = $this->post->{$extendField->field}[$feedbackID];
                if(is_array($feedback->{$extendField->field})) $feedback->{$extendField->field} = join(',', $feedback->{$extendField->field});

                $feedback->{$extendField->field} = htmlspecialchars($feedback->{$extendField->field});
            }

            $this->dao->update(TABLE_FEEDBACK)->data($feedback)
                ->batchCheck($this->config->feedback->edit->requiredFields, 'notempty')
                ->checkFlow();

            if(dao::isError())
            {
                $errors = dao::getError();
                foreach($errors as $key => $error) $feedbackErrors[$key . $feedbackID] = $error;
            }

            $feedbacks[$feedbackID] = $feedback;
        }

        if(!empty($feedbackErrors))
        {
            dao::$errors = $feedbackErrors;
            return false;
        }

        foreach($feedbacks as $feedbackID => $feedback)
        {
            $oldFeedback = $oldFeedbacks[$feedbackID];

            $this->dao->update(TABLE_FEEDBACK)->data($feedback)
                ->batchCheck($this->config->feedback->edit->requiredFields, 'notempty')
                ->checkFlow()
                ->where('id')->eq($feedbackID)
                ->exec();

            if(dao::isError()) return false;

            $changes[$feedbackID] = common::createChanges($oldFeedback, $feedback);
        }

        return $changes;
    }

    /**
     * Batch close story.
     *
     * @access public
     * @return void
     */
    public function batchClose()
    {
        /* Init vars. */
        $feedbacks  = array();
        $allChanges = array();
        $now        = helper::now();
        $data       = fixer::input('post')->get();

        $feedbackIdList = array_keys($data->comments);
        $oldFeedbacks   = $this->getByList($feedbackIdList);

        foreach($feedbackIdList as $feedbackID)
        {
            $oldFeedback = $oldFeedbacks[$feedbackID];
            if($oldFeedback->status == 'closed') continue;
            $feedback = new stdclass();

            $feedback->editedBy     = $this->app->user->account;
            $feedback->editedDate   = $now;
            $feedback->closedBy     = $this->app->user->account;
            $feedback->closedDate   = $now;
            $feedback->assignedTo   = 'closed';
            $feedback->assignedDate = $now;
            $feedback->status       = 'closed';

            if(empty($oldFeedback->prevStatus)) $feedback->prevStatus = $oldFeedback->status;

            $feedback->closedReason   = $data->closedReasons[$feedbackID];
            $feedback->repeatFeedback = $data->repeatFeedbackIDList[$feedbackID] ? $data->repeatFeedbackIDList[$feedbackID] : 0;

            $feedbacks[$feedbackID] = $feedback;
            unset($feedback);
        }

        $errors = array();
        foreach($feedbacks as $feedbackID => $feedback)
        {
            $oldFeedback = $oldFeedbacks[$feedbackID];

            $this->dao->update(TABLE_FEEDBACK)->data($feedback)
                ->autoCheck()
                ->checkIF($feedback->repeatFeedback == 0 and $feedback->closedReason == 'repeat', 'repeatFeedback', 'notempty')
                ->where('id')->eq($feedbackID)->exec();

            if(!dao::isError())
            {
                $allChanges[$feedbackID] = common::createChanges($oldFeedback, $feedback);
                $this->loadModel('score')->create('feedback', 'close', $feedbackID);
            }
            else
            {
                $errors['repeat'][] = 'feedback#' . $feedbackID . dao::getError(true);
            }
        }

        if(!empty($errors)) dao::$errors = $errors;

        return $allChanges;
    }

    /**
     * Assign a feedback to a user again.
     *
     * @param  int    $feedbackID
     * @access public
     * @return string
     */
    public function assign($feedbackID)
    {
        $oldFeedback = $this->getById($feedbackID);
        $feedback = form::data($this->config->feedback->form->assignTo, $feedbackID)
            ->add('id', $feedbackID)
            ->setDefault('editedBy', $this->app->user->account)
            ->remove('comment')
            ->get();

        $feedback = $this->loadModel('file')->processImgURL($feedback, $this->config->feedback->editor->assignto['id'], $this->post->uid);
        $this->dao->update(TABLE_FEEDBACK)
            ->data($feedback)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($feedbackID)->exec();

        if(!dao::isError()) return common::createChanges($oldFeedback, $feedback);
    }

    /**
     * Batch active feedbacks.
     *
     * @param  string $assignedTo
     * @access public
     * @return array
     */
    public function batchAssign($assignedTo)
    {
        $oldFeedbacks    = $this->getByList($this->post->feedbackIDList);
        $ignoreFeedbacks = '';

        $now        = helper::now();
        $allChanges = array();
        foreach($this->post->feedbackIDList as $feedbackID)
        {
            $oldFeedback = $oldFeedbacks[$feedbackID];
            if($oldFeedback->status == 'closed')
            {
                $ignoreFeedbacks .= "#{$feedbackID},";
                continue;
            }
            if($oldFeedback->assignedTo == $assignedTo) continue;

            $feedback = new stdclass();
            $feedback->assignedTo   = $assignedTo;
            $feedback->assignedDate = $now;
            $feedback->editedBy     = $this->app->user->account;
            $feedback->editedDate   = $now;

            $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($feedbackID)->exec();
            $allChanges[$feedbackID] = common::createChanges($oldFeedback, $feedback);
        }

        $this->loadModel('action');
        if(!empty($allChanges))
        {
            foreach($allChanges as $feedbackID => $changes)
            {
                if(empty($changes)) continue;
                $actionID = $this->action->create('feedback', $feedbackID, 'Assigned', '', $assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
        }
        if($ignoreFeedbacks)
        {
            $ignoreFeedbacks = trim($ignoreFeedbacks, ',');
            return sprintf($this->lang->feedback->ignoreClosedFeedback, $ignoreFeedbacks);
        }
        return $this->lang->saveSuccess;
    }

    /**
     * Batch change the module of feedback.
     *
     * @param  array  $feedbackIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModule($feedbackIDList, $moduleID)
    {
        $now          = helper::now();
        $allChanges   = array();
        $oldFeedbacks = $this->getByList($feedbackIDList);
        foreach($feedbackIDList as $feedbackID)
        {
            $oldFeedback = $oldFeedbacks[$feedbackID];
            if($moduleID == $oldFeedback->module) continue;

            $feedback = new stdclass();
            $feedback->module = $moduleID;

            $this->dao->update(TABLE_FEEDBACK)->data($feedback)->autoCheck()->where('id')->eq((int)$feedbackID)->exec();
            if(!dao::isError()) $allChanges[$feedbackID] = common::createChanges($oldFeedback, $feedback);
        }
        return $allChanges;
    }

    /**
     * Review a feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return bool
     */
    public function review($feedbackID)
    {
        if($this->post->result == false)
        {
            dao::$errors['result'] = $this->lang->feedback->mustChooseResult;
            return false;
        }

        $now          = helper::now();
        $oldFeedback  = $this->dao->findById($feedbackID)->from(TABLE_FEEDBACK)->fetch();
        $status       = $this->getStatus('review', true);
        if($this->post->result == 'pass')
        {
            $assignedTo   = $this->post->assignedTo;
            $assignedDate = $now;
        }
        if($this->post->result == 'clarify')
        {
            $assignedTo   = $oldFeedback->openedBy;
            $assignedDate = $now;
        }

        $feedback = fixer::input('post')
            ->remove('result,comment')
            ->add('id', $feedbackID)
            ->setDefault('reviewedDate', $now)
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', $now)
            ->setIF($status, 'status', $status)
            ->setIF($status, 'assignedTo', $assignedTo)
            ->setIF($status, 'assignedDate', $assignedDate)
            ->stripTags($this->config->feedback->editor->review['id'])
            ->join('reviewedBy', ',')
            ->get();

        $feedback = $this->loadModel('file')->processImgURL($feedback, $this->config->feedback->editor->review['id'], $this->post->uid);
        $this->dao->update(TABLE_FEEDBACK)->data($feedback)->autoCheck()->checkFlow()->where('id')->eq($feedbackID)->exec();
        if(dao::isError()) return false;

        return common::createChanges($oldFeedback, $feedback);
    }

    /**
     * Batch review feedbacks.
     *
     * @param  array  $feedbackIdList
     * @param  string $result
     * @access public
     * @return array
     */
    public function batchReview($feedbackIdList, $result)
    {
        if(empty($feedbackIdList)) return true;

        $now     = helper::now();
        $actions = array();
        $this->loadModel('action');

        $oldFeedbacks = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->in($feedbackIdList)->fetchAll('id', false);
        foreach($oldFeedbacks as $oldFeedback) $products[$oldFeedback->product] = $oldFeedback->product;
        $productFeedbacks = $this->dao->select('id,feedback')->from(TABLE_PRODUCT)->where('id')->in($products)->fetchPairs('id', 'feedback');
        foreach($feedbackIdList as $feedbackID)
        {
            $oldFeedback = $oldFeedbacks[$feedbackID];
            if($oldFeedback->status != 'noreview') continue;

            $feedback = new stdClass();
            $feedback->reviewedBy   = $this->app->user->account;
            $feedback->reviewedDate = $now;
            $feedback->editedBy     = $this->app->user->account;
            $feedback->editedDate   = $now;
            if($result == 'pass')
            {
                $feedback->status = 'wait';
                if(isset($productFeedbacks[$oldFeedback->product]))
                {
                    $feedback->assignedTo   = $productFeedbacks[$oldFeedback->product];
                    $feedback->assignedDate = $now;
                }
            }
            if($result == 'clarify')
            {
                $feedback->status       = 'clarify';
                $feedback->assignedTo   = $oldFeedback->openedBy;
                $feedback->assignedDate = $now;
            }
            $this->dao->update(TABLE_FEEDBACK)->data($feedback)->autoCheck()->where('id')->eq($feedbackID)->exec();
            $actions[$feedbackID] = $this->action->create('feedback', $feedbackID, 'Reviewed', '', ucfirst($result));
        }

        return $actions;
    }

    /**
     * Like a feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return string
     */
    public function like($feedbackID)
    {
        $feedback = $this->dao->select('id, likes')->from(TABLE_FEEDBACK)->where('id')->eq($feedbackID)->fetch();

        $likeBy = ',' . $this->app->user->account . ',';
        $likes  = ',' . trim($feedback->likes, ',') . ',';
        if(strpos($likes, $likeBy) !== false)
        {
            $likes = trim(str_replace($likeBy, '', $likes), ',');
        }
        else
        {
            $likes = ltrim($likes, ',') . $this->app->user->account;
        }
        $this->dao->update(TABLE_FEEDBACK)->set('likes')->eq($likes)->where('id')->eq($feedbackID)->exec();

        return $likes;
    }

    /**
     * Get feedback list.
     *
     * @param  string $browseType wait|doing|toclosed|unclosed|all|public|tostory|totask|tobug|totodo|review|assigntome
     * @param  string $orderBy
     * @param  object $pager
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function getList($browseType = 'wait', $orderBy = 'id_desc', $pager = null, $moduleID = 0)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getFeedbacks();

        $modules  = ($moduleID and $this->session->browseType == 'byModule') ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $products = $this->getGrantProducts(true, false, 'all');

        $feedbackIDList = '';
        if($browseType == 'toticket') $feedbackIDList = $this->dao->select('DISTINCT feedback')->from(TABLE_TICKET)->where('feedback')->gt(0)->andWhere('deleted')->eq('0')->fetchPairs();

        $feedbackSQL = '';
        if($browseType == 'assignedbyme' || $browseType == 'reviewedbyme')
        {
            $action      = $browseType == 'assignedbyme' ? 'assigned' : 'reviewed';
            $feedbackSQL = $this->dao->select('objectID')->from(TABLE_ACTION)
                ->where('actor')->eq($this->app->user->account)
                ->andWhere('objectType')->eq('feedback')
                ->andWhere('action')->eq($action)
                ->get();
        }

        return $this->dao->select('t1.*,t2.dept')->from(TABLE_FEEDBACK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->beginIF($browseType == 'unclosed')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($browseType == 'wait')->andWhere('t1.status')->in('wait,clarify,asked,noreview')->fi()
            ->beginIF($browseType == 'doing')->andWhere('t1.status')->eq('commenting')->fi()
            ->beginIF($browseType == 'toclosed')->andWhere('t1.status')->eq('replied')->fi()
            ->beginIF($browseType == 'public')->andWhere('t1.public')->eq('1')->fi()
            ->beginIF($browseType == 'tostory')->andWhere('t1.solution')->eq('tostory')->fi()
            ->beginIF($browseType == 'touserstory')->andWhere('t1.solution')->eq('touserstory')->fi()
            ->beginIF($browseType == 'toepic')->andWhere('t1.solution')->eq('toepic')->fi()
            ->beginIF($browseType == 'tobug')->andWhere('t1.solution')->eq('tobug')->fi()
            ->beginIF($browseType == 'totask')->andWhere('t1.solution')->eq('totask')->fi()
            ->beginIF($browseType == 'totodo')->andWhere('t1.solution')->eq('totodo')->fi()
            ->beginIF($browseType == 'todemand')->andWhere('t1.solution')->eq('todemand')->fi()
            ->beginIF($browseType == 'toticket')->andWhere('t1.id')->in($feedbackIDList)->fi()
            ->beginIF($browseType == 'assigntome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'openedbyme')->andWhere('t1.openedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'reviewedbyme')->andWhere('t1.id')->subIn($feedbackSQL)->fi()
            ->beginIF($browseType == 'closedbyme')->andWhere('t1.closedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'assignedbyme')->andWhere('t1.id')->subIn($feedbackSQL)->fi()
            ->beginIF($this->session->feedbackProduct != 'all' and $this->app->getModuleName() != 'my')->andWhere('t1.product')->eq((int)$this->session->feedbackProduct)->fi()
            ->beginIF(!empty($modules))->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($browseType != 'public' and $this->app->getMethodName() == 'browse' and !$this->app->user->admin)
            ->andWhere('((t1.openedBy')->eq($this->app->user->account)
            ->andWhere('t1.public')->eq('0')
            ->markRight(1)
            ->orWhere('t1.public')->eq('1')
            ->markRight(1)
            ->fi()
            ->andWhere('t1.product', true)->in(array_keys($products))
            ->markRight(1)
            ->beginIF($browseType == 'review')
            ->andWhere('t1.status')->in('noreview')
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get info of a feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return array
     */
    public function getById($feedbackID)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getFeedback();

        $feedback = $this->dao->select('*')->from(TABLE_FEEDBACK)
            ->where('id')->eq($feedbackID)
            ->fetch();
        if(empty($feedback)) return false;

        $feedback->likesCount = $feedback->likes ? count(explode(',', $feedback->likes)) : 0;
        $feedback->files      = $this->loadModel('file')->getByObject('feedback', $feedbackID);

        $feedback = $this->file->replaceImgURL($feedback, 'desc');
        $feedback->desc = $this->file->setImgSize($feedback->desc);

        return $feedback;
    }

    /**
     * Get feedback list.
     *
     * @param  array  $idList
     * @param  string $type openedbyme
     * @access public
     * @return array
     */
    public function getByList($idList, $type = '')
    {
        return $this->dao->select('*')->from(TABLE_FEEDBACK)
            ->where('id')->in($idList)
            ->beginIF($type == 'openedbyme' and !$this->app->user->admin)->andWhere('openedBy')->eq($this->app->user->account)->fi()
            ->fetchAll('id', false);
    }

    /**
     * Get feedbacks by search.
     *
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBySearch($queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $this->loadModel('search');
        $moduleName = $this->app->moduleName;
        $methodName = $this->app->methodName;
        $query      = $queryID ? $this->search->getQuery($queryID) : '';
        $products   = $this->getGrantProducts(true, false, 'all');

        $feedbackQuery = 'feedbackQuery';
        $feedbackForm  = 'feedbackForm';

        if($query)
        {
            $this->session->set($feedbackQuery, $query->sql);
            $this->session->set($feedbackForm, $query->form);
        }

        if($this->session->$feedbackQuery == false) $this->session->set($feedbackQuery, ' 1 = 1');

        /* Distinguish between repeated fields. */
        $feedbackQuery = $this->session->$feedbackQuery;
        if(strpos($feedbackQuery, '`id`')     !== false) $feedbackQuery = str_replace('`id`', 't1.`id`', $feedbackQuery);
        if(strpos($feedbackQuery, '`type`')   !== false) $feedbackQuery = str_replace('`type`', 't1.`type`', $feedbackQuery);
        if(strpos($feedbackQuery, '`status`') !== false) $feedbackQuery = str_replace('`status`', 't1.`status`', $feedbackQuery);

        return $this->dao->select('t1.*,t2.dept')->from(TABLE_FEEDBACK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
            ->where($feedbackQuery)
            ->andWhere('t1.deleted')->eq('0')
            ->beginIF($methodName == 'admin')
            ->andWhere('t1.product')->in(array_keys($products))
            ->fi()
            ->beginIF($methodName == 'browse')
            ->andWhere('t1.public', true)->eq('1')
            ->orWhere('t1.openedBy')->eq($this->app->user->account)
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get feedbacks pairs.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getFeedbackPairs($type = 'feedback')
    {
        $userPairs = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq(0)
            ->beginIF($type == 'feedback')->andWhere('feedback')->eq(1)->fi()
            ->beginIF($type != 'feedback')->andWhere('feedback')->eq(0)->fi()
            ->fetchPairs('account', 'realname');

        foreach($userPairs as $account => $realname)
        {
            if(empty($realname))$userPairs[$account] = $account;
        }
        return $userPairs;
    }

    /**
     * Get user feedbacks pairs.
     *
     * @param  string $account
     * @param  int    $limit
     * @param  int    $appendID
     * @access public
     * @return array
     */
    public function getUserFeedbackPairs($account = '', $limit = 10, $appendID = '')
    {
        $feedbackPairs = $this->dao->select('t1.id, t1.title')->from(TABLE_FEEDBACK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('(t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.assignedTo')->eq($account)
            ->andWhere('t1.status')->ne('closed')
            ->markRight(1)
            ->beginIF(!empty($appendID))->orWhere('t1.id')->in($appendID)->fi()
            ->orderBy('t1.id_desc')
            ->limit($limit)
            ->fetchPairs();

        return $feedbackPairs;
    }

    /**
     * Get feedback view.
     *
     * @param  array  $products
     * @access public
     * @return array
     */
    public function getFeedbackView($products)
    {
        return $this->dao->select('*')->from(TABLE_FEEDBACKVIEW)
            ->where('account')->notIN(trim($this->app->company->admins, ','))
            ->beginIF($products)->andWhere('product')->in($products)->fi()
            ->fetchGroup('product', 'account');
    }

    /**
     * Get feedback products.
     *
     * @param  object  $pager
     * @param  bool    $isPairs
     * @access public
     * @return array
     */
    public function getFeedbackProducts($pager = NULL, $isPairs = true)
    {
        $productSettingList = isset($this->config->global->productSettingList) ? json_decode($this->config->global->productSettingList, true) : array();
        $stmt = $this->dao->select("t1.*, IF(INSTR(' closed', t1.status) < 2, 0, 1) AS isClosed")->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF(!empty($productSettingList))->andWhere('t1.id')->in($productSettingList)->fi()
            ->andWhere('t1.status')->eq('normal')
            ->filterTpl('skip')
            ->orderBy('isClosed, t2.order_asc, t1.line_desc, t1.order_asc')
            ->page($pager);

        $products = $isPairs ? $stmt->fetchPairs('id', 'name') : $stmt->fetchAll('id', false);

        /* 使用键名比较计算数组的交集 */
        $intersectKey = array_intersect_key(array_flip($productSettingList), $products);
        /* 使用后面数组的值替换第一个数组的值 */
        $products = array_replace($intersectKey, $products);

        return $products;
    }

    /**
     * Get grant products.
     *
     * @param  bool   $isPairs
     * @param  bool   $isDefault
     * @param  string $params
     * @access public
     * @return array
     */
    public function getGrantProducts($isPairs = true, $isDefault = false, $params = '')
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getProductPairs();

        $products = $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('deleted')->eq('0')
            ->beginIF(strpos(",$params,", 'all') === false)->andWhere('status')->eq('normal')->fi()
            ->fetchAll('id', false);

        $feedbackView = $this->getFeedbackView(array_keys($products));

        $account = $this->app->user->account;
        $admin   = $this->app->user->admin;
        if(!$admin)
        {
            foreach($products as $productID => $product)
            {
                if(isset($feedbackView[$productID]) && !isset($feedbackView[$productID][$account])) unset($products[$productID]);
            }
        }

        $productSettingList = isset($this->config->global->productSettingList) ? json_decode($this->config->global->productSettingList, true) : array();
        $allottedProducts = array();
        if(empty($products)) $allottedProducts = $this->dao->select('DISTINCT product')->from(TABLE_FEEDBACKVIEW)->fetchPairs('product', 'product');

        $stmt = $this->dao->select('t1.*')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program=t2.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF(strpos(",$params,", 'all') === false)->andWhere('t1.status')->eq('normal')->fi()
            ->andWhere("FIND_IN_SET('rnd', t1.vision)")
            ->andWhere('1=1', true)
            ->beginIF(!empty($products) and !$admin)->andWhere('t1.id')->in(array_keys($products))->fi()
            ->beginIF(!empty($productSettingList))->andWhere('t1.id')->in($productSettingList)->fi()
            ->beginIF(!empty($allottedProducts) and !$admin)->andWhere('t1.id')->notin($allottedProducts)->fi()
            ->beginIF($this->app->rawModule == 'feedback')->orWhere('t1.feedback')->eq($this->app->user->account)->fi()
            ->beginIF($this->app->rawModule == 'ticket')->orWhere('t1.ticket')->eq($this->app->user->account)->fi()
            ->markRight(1)
            ->filterTpl('skip')
            ->orderBy('t2.order_asc,t1.line_desc,t1.order_asc');
        $pairs = $isPairs ? $stmt->fetchPairs('id', 'name') : $stmt->fetchAll('id', false);
        return $isDefault ? arrayUnion(array('' => ''), $pairs) : $pairs;
    }

    /**
     * Check has priv.
     *
     * @param  int    $productID
     * @access public
     * @return bool
     */
    public function hasPriv($productID)
    {
        $grantProducts = $this->getGrantProducts();
        return isset($grantProducts[$productID]);
    }

    /**
     * Get product module map for feedback or ticket.
     *
     * @param  string $type
     * @param  bool   $all
     * @param  string $productPrefix
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getModuleList($type, $isPairs = false, $productPrefix = 'yes', $productIdList = array())
    {
        if(empty($productIdList)) $productIdList = $this->loadModel('feedback')->getGrantProducts();

        $moduleList = array();
        /* Group by module for cascade. */
        if(empty($productIdList)) return $moduleList;

        foreach($productIdList as $productID => $product)
        {
            $modules = $this->loadModel('tree')->getOptionMenu($productID, $type, 0, 'all');
            if($isPairs and !empty($modules))
            {
                foreach($modules as $moduleID => $modulePath)
                {
                    if(empty($moduleID)) continue;
                    $moduleList[$moduleID] = $productPrefix == 'yes' ? $product . $modulePath : $modulePath;
                }
            }
            else
            {
                $moduleList[$productID] = $modules;
            }
        }

        return $isPairs ? arrayUnion(array('' => ''), $moduleList) : $moduleList;
    }

    /**
     * Get trace.
     *
     * @param  int    $feedbackID
     * @access public
     * @return string
     */
    public function getTrace($feedbackID)
    {
        $this->loadModel('action');
        $feedback  = $this->getById($feedbackID);
        $users     = $this->loadModel('user')->getPairs('noletter');
        $actions   = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('feedback')->andWhere('objectID')->eq($feedbackID)->orderBy('date')->fetchAll('id', false);
        $treemap   = "<ul class='tree' id='feedbackTree'>";
        $preAction = '';
        foreach($actions as $action)
        {
            if($preAction == "{$action->actor}-{$action->action}") continue;
            $preAction = "{$action->actor}-{$action->action}";

            if(isset($this->lang->action->label->{$action->action})) $actionName = $this->lang->action->label->{$action->action};
            $user = zget($users, $action->actor);
            $treemap .= "<li class='item-feedback'><span class='title'>{$user}</span><span class='label label-action'>{$actionName}</span><span class='label label-type'>{$this->lang->feedback->common}</span><span class='label label-id'>#{$feedbackID}</span>";
            if($action->extra and is_numeric($action->extra))
            {
                $table = '';
                if($action->action == 'tostory') $table = TABLE_STORY;
                if($action->action == 'tobug')   $table = TABLE_BUG;
                if($action->action == 'totodo')  $table = TABLE_TODO;
                if($action->action == 'totask')  $table = TABLE_TASK;
                if($table)
                {
                    $objectType    = substr($action->action, 2);
                    $object        = $this->dao->select('*')->from($table)->where('id')->eq($action->extra)->fetch();
                    $objectActions = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($action->extra)->orderBy('date')->fetchAll('id', false);
                    $subPreAction  = '';
                    $this->app->loadLang($objectType);

                    $treemap .= "<ul>";
                    foreach($objectActions as $objectAction)
                    {
                        if($objectAction->action == 'opened' or $objectAction->action == 'fromfeedback') continue;
                        if($subPreAction == "{$objectAction->actor}-{$objectAction->action}") continue;
                        $subPreAction = "{$objectAction->actor}-{$objectAction->action}";

                        if(isset($this->lang->action->label->{$objectAction->action})) $actionName = $this->lang->action->label->{$objectAction->action};
                        $user     = zget($users, $objectAction->actor);
                        $treemap .= "<li class='item-{$objectType}'><span class='title'>{$user}</span><span class='label label-action'>{$actionName}</span><span class='label label-type'>{$this->lang->$objectType->common}</span><span class='label label-id'>#{$objectAction->objectID}</span>";

                        $tasks = array();
                        if($objectType == 'story' and $objectAction->action == 'linked2execution')
                        {
                            $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('story')->eq($objectAction->objectID)->andWhere('execution')->eq($objectAction->extra)->andWhere('deleted')->eq('0')->fetchAll('id', false);
                        }
                        elseif($objectType == 'bug' and $objectAction->action == 'totask')
                        {
                            $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('fromBug')->eq($objectAction->objectID)->fetchAll('id', false);
                        }
                        if($tasks)
                        {
                            $this->app->loadLang('task');
                            $taskActions = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->in(array_keys($tasks))->orderBy('date')->fetchGroup('objectID', 'id');
                            foreach($tasks as $taskID => $task)
                            {
                                $treemap .= '<ul>';
                                foreach($taskActions[$taskID] as $taskAction)
                                {
                                    if(isset($this->lang->action->label->{$taskAction->action})) $actionName = $this->lang->action->label->{$taskAction->action};
                                    $user     = zget($users, $taskAction->actor);
                                    $treemap .= "<li class='item-task'><span class='title'>{$user}</span><span class='label label-action'>{$actionName}</span><span class='label label-type'>{$this->lang->task->common}</span><span class='label label-id'>#{$taskID}</span>";
                                }
                                $treemap .= '</ul>';
                            }
                        }

                        $treemap .= '</li>';
                    }
                    $treemap .= '</ul>';
                }
            }
            $treemap .= '</li>';
        }
        $treemap .= '</ul>';
        return $treemap;
    }

    /**
     * Get feedback relations.
     *
     * @param  int    $feedbackID
     * @param  object $feedback
     * @access public
     * @return array
     */
    public function getRelations($feedbackID, $feedback = '')
    {
        if(!$feedback) $feedback = $this->getById($feedbackID);

        $relations = array();
        if($feedback->solution)
        {
            $types = $this->config->feedback->relationTypes;
            if($this->config->vision == 'rnd') unset($types['demand']);
            foreach($types as $type => $table)
            {
                $objects = $this->dao->select('*')->from($table)
                    ->where('feedback')->eq($feedbackID)
                    ->andWhere('deleted')->eq(0)
                    ->beginIF($type == 'story')->andWhere('type')->eq('story')->fi()
                    ->beginIF($type == 'userStory')->andWhere('type')->eq('requirement')->fi()
                    ->beginIF($type == 'epic')->andWhere('type')->eq('epic')->fi()
                    ->orderBy('id_desc')
                    ->fetchAll('id', false);

                if(empty($objects)) continue;

                $module = $type == 'userStory' ? 'requirement' : $type;

                $this->loadModel($module);
                foreach($objects as $object)
                {
                    if($object->status == 'closed' && !empty($object->duplicateStory)) $object = $this->$module->fetchById($object->duplicateStory);
                    if($type == 'task' || $type == 'todo') $object->title = $object->name;
                    $object->statusLabel = $this->processStatus($module, $object);
                    $relations[$type][$object->id] = $object;
                }
            }
        }
        return $relations;
    }

    /**
     * 检查反馈是否有未关闭的转化对象
     * Check the feedback for unclosed objects.
     *
     * @param  array $feedbackIdList
     * @access public
     * @return array
     */
    public function checkUnclosedObjectsForFeedbacks($feedbackIdList)
    {
        $types = $this->config->feedback->relationTypes;
        if($this->config->vision == 'rnd') unset($types['demand']);
        $hasUnclosedObject = array();
        foreach($types as $type => $table)
        {
            $objects = $this->dao->select('count(1) as count,feedback')->from($table)
                ->where('feedback')->in($feedbackIdList)
                ->andWhere('feedback')->ne('0')
                ->andWhere('deleted')->eq(0)
                ->andWhere('status')->ne('closed')
                ->beginIF($type == 'story')->andWhere('type')->eq('story')->fi()
                ->beginIF($type == 'userStory')->andWhere('type')->eq('requirement')->fi()
                ->beginIF($type == 'epic')->andWhere('type')->eq('epic')->fi()
                ->groupBy('feedback')
                ->orderBy('id_desc')
                ->fetchGroup('feedback');
            unset($objects[''], $objects[0]);
            $hasUnclosedObject = array_merge($hasUnclosedObject, array_keys($objects));
        }
        $hasUnclosedObject = array_unique($hasUnclosedObject);
        $hasUnclosedObject = array_filter($hasUnclosedObject);
        return $hasUnclosedObject;
    }

    /**
     * Process actions.
     *
     * @param  int    $feedbackID
     * @param  object $actions
     * @access public
     * @return array
     */
    public function processActions($feedbackID, $actions)
    {
        $totalActions  = array();
        $storyIdList   = array();
        $sourceActions = $this->dao->select('*')->from(TABLE_ACTION)->where('id')->in(array_keys($actions))->fetchAll('id', false);
        foreach($sourceActions as $actionID => $action)
        {
            $totalActions[$actionID] = $actions[$actionID];

            $objectType = substr($action->action, 2);
            if(in_array($objectType, array('userstory', 'epic'))) $objectType = 'story';

            if(strpos('story|bug|todo|task|ticket|demand', $objectType) === false) continue;
            $objectActions = $this->loadModel('action')->getList($objectType, (int)$action->extra);
            foreach($objectActions as $objectActionID => $objectAction)
            {
                if($objectAction->action == 'fromfeedback') continue;
                $objectAction->from = 'feedback';
                $totalActions[$objectActionID] = $objectAction;

                if($objectAction->objectType == 'story') $storyIdList[$objectAction->objectID] = $objectAction->objectID;
            }
        }

        if($storyIdList)
        {
            $stories = $this->dao->select('id,type')->from(TABLE_STORY)->where('id')->in($storyIdList)->andWhere('type')->ne('story')->fetchPairs();
            if($stories)
            {
                foreach($totalActions as $action)
                {
                    if($action->objectType == 'story' && isset($stories[$action->objectID])) $action->objectType = $stories[$action->objectID];
                }
            }
        }

        $actions   = array();
        $orderBy   = isset($_COOKIE['historyOrder']) && $this->cookie->historyOrder == 'desc' ? 'date_desc, id_desc' : 'date_asc, id_asc';
        $orderList = $this->dao->select('id')->from(TABLE_ACTION)->where('id')->in(array_keys($totalActions))->orderBy($orderBy)->fetchPairs('id');
        foreach($orderList as $orderID)
        {
            if(!isset($totalActions[$orderID])) continue;
            $actions[$orderID] = $totalActions[$orderID];
        }

        return $actions;
    }

    /**
     * Manage product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function manageProduct($productID)
    {
        $this->dao->delete()->from(TABLE_FEEDBACKVIEW)->where('product')->eq($productID)->exec();
        if(empty($_POST['accounts'])) return;
        $accounts = fixer::input('post')->remove('allchecker')->get('accounts');
        foreach($accounts as $account)
        {
            $view = new stdclass();
            $view->account = $account;
            $view->product = $productID;
            $this->dao->replace(TABLE_FEEDBACKVIEW)->data($view)->exec();
        }
    }

    /**
     * Activate feedback estimate.
     *
     * @param  int    $feedbackID
     * @access public
     * @return int
     */
    public function activate($feedbackID)
    {
        $oldFeedback = $this->getById($feedbackID);
        if(!in_array($oldFeedback->status, array('replied', 'done', 'closed'))) return false;

        $now  = helper::now();
        $feedback = fixer::input('post')
            ->setDefault('status', empty($oldFeedback->prevStatus) || $oldFeedback->prevStatus == 'replied' ? 'commenting' : $oldFeedback->prevStatus)
            ->setDefault('prevStatus', '')
            ->setDefault('assignedDate', $now)
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', $now)
            ->setDefault('activatedBy', $this->app->user->account)
            ->setDefault('activatedDate', $now)
            ->setDefault('closedBy, closedReason', '')
            ->setDefault('closedDate', null)
            ->stripTags($this->config->feedback->editor->activate['id'], $this->config->allowedTags)
            ->remove('comment,files,labels')
            ->get();

        $feedback = $this->loadModel('file')->processImgURL($feedback, $this->config->feedback->editor->activate['id'], $this->post->uid);

        $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($feedbackID)->exec();
        if(!dao::isError()) return common::createChanges($oldFeedback, $feedback);
    }

    /**
     * Close a feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function close($feedbackID)
    {
        $feedback = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->eq($feedbackID)->fetch();
        if($feedback->status != 'closed' and ($feedback->openedBy == $this->app->user->account or empty($this->app->user->feedback)))
        {
            $data = fixer::input('post')
                ->setDefault('status', 'closed')
                ->setDefault('repeatFeedback', 0)
                ->setDefault('prevAssignedTo', $feedback->assignedTo)
                ->setIF(empty($feedback->prevStatus), 'prevStatus', $feedback->status)
                ->add('id', $feedbackID)
                ->add('closedBy', $this->app->user->account)
                ->add('closedDate', helper::now())
                ->add('editedBy', $this->app->user->account)
                ->add('editedDate', helper::now())
                ->add('assignedTo', 'closed')
                ->stripTags($this->config->feedback->editor->close['id'])
                ->remove('comment')
                ->get();

            $data = $this->loadModel('file')->processImgURL($data, $this->config->feedback->editor->close['id'], $this->post->uid);
            $this->dao->update(TABLE_FEEDBACK)->data($data)
                ->autoCheck()->checkFlow()
                ->checkIF(isset($data->closedReason) and $data->closedReason == 'repeat', 'repeatFeedback', 'notempty')
                ->where('id')->eq($feedbackID)->exec();
            if(dao::isError()) return false;

            $changes = common::createChanges($feedback, $data);
            if($changes)
            {
                if($data->closedReason == 'repeat') $this->post->closedReason .= ":$data->repeatFeedback";
                $actionID = $this->loadModel('action')->create('feedback', $feedbackID, 'closed', $this->post->comment, $this->post->closedReason);
                $this->action->logHistory($actionID, $changes);
            }
        }
    }

    /**
     * Print cell data.
     *
     * @param  object $value
     * @param  object $feedback
     * @param  array  $users
     * @param  array  $allProducts
     * @param  array  $depts
     * @param  array  $modulePairs
     * @param  string $viewMethod
     * @param  string $browseType
     * @param  array  $stories
     * @param  array  $bugs
     * @param  array  $todos
     * @param  array  $tasks
     * @param  array  $tickets
     * @access public
     * @return string
     */
    public function printCell($value, $feedback, $users, $allProducts, $depts, $modulePairs, $viewMethod = 'view', $browseType = '', $stories = array(), $bugs = array(), $todos = array(), $tasks = array(), $tickets = array(), $demands = array())
    {
        $canBatchEdit     = common::hasPriv('feedback', 'batchEdit');
        $canBatchClose    = common::hasPriv('feedback', 'batchClose');
        $canBatchAssignTo = common::hasPriv('feedback', 'batchAssignTo');
        $canExport        = common::hasPriv('feedback', 'export');
        $canBatchAction   = ($canBatchEdit or $canBatchClose or $canBatchAssignTo or $canExport);

        $feedbackLink = common::hasPriv('feedback', $viewMethod) ? helper::createLink('feedback', $viewMethod, "feedbackID=$feedback->id&browseType=$browseType") : '';
        $adminMethod  = $this->app->getMethodName() == 'admin';

        $id      = $value->id;
        $title   = '';
        $style   = '';
        $class   = "c-$id";
        if($id == 'product')      $title  = "title='" . zget($allProducts, $feedback->product) . "'";
        if($id == 'title')        $title  = "title='{$feedback->title}'";
        if($id == 'solution')     $title  = "title='" . zget($this->lang->feedback->solutionList, $feedback->solution, '') . " #{$feedback->result}'";
        if($id == 'closedReason') $title  = "title='" . zget($this->lang->feedback->closedReasonList, $feedback->closedReason) . "'";
        if($id == 'openedBy')     $title  = "title='" . zget($users, $feedback->openedBy) . "'";
        if($id == 'actions')      $class .= " text-left";
        if($id == 'status')
        {
            $class .= " status-{$feedback->status}";
            $title  = "title='" . $this->processStatus('feedback', $feedback) . "'";
        }

        echo "<td class='" . $class . "' $title $style>";
        switch($id)
        {
        case 'id':
            if($canBatchAction)
            {
                echo html::checkbox('feedbackIDList', array($feedback->id => '')) . ($feedbackLink ? html::a($feedbackLink, sprintf('%03d', $feedback->id)) : sprintf('%03d', $feedback->id));
            }
            else
            {
                printf('%03d', $feedback->id);
            }
            break;
        case 'pri':
            echo "<span class='label-pri label-pri-" . $feedback->pri . "' title='" . zget($this->lang->feedback->priList, $feedback->pri, $feedback->pri) . "'>";
            echo zget($this->lang->feedback->priList, $feedback->pri, $feedback->pri);
            echo "</span>";
            break;
        case 'product':
            echo zget($allProducts, $feedback->product);
            break;
        case 'title':
            if($feedback->module and isset($modulePairs[$feedback->module]) and $this->app->getMethodName() != 'feedback') echo "<span class='label label-gray label-badge'>{$modulePairs[$feedback->module]}</span> ";
            echo $feedbackLink ? html::a($feedbackLink, $feedback->title) : $feedback->title;
            break;
        case 'status':
            echo $this->processStatus('feedback', $feedback);
            break;
        case 'type':
            echo zget($this->lang->feedback->typeList, $feedback->type, '');
            break;
        case 'solution':
            echo zget($this->lang->feedback->solutionList, $feedback->solution, '');
            if($feedback->solution)
            {
                if(in_array($feedback->solution, array('tostory', 'touserstory', 'toepic')) and isset($stories[$feedback->result])) echo " #{$feedback->result} <span class='label label-info'>" . zget($this->lang->story->stageList, $stories[$feedback->result]->stage) . "</span>";
                if($feedback->solution == 'tobug'    and isset($bugs[$feedback->result]))    echo " #{$feedback->result} <span class='label label-info'>" . $this->processStatus('bug', $bugs[$feedback->result]) . "</span>";
                if($feedback->solution == 'totodo'   and isset($todos[$feedback->result]))   echo " #{$feedback->result} <span class='label label-info'>" . zget($this->lang->todo->statusList, $todos[$feedback->result]->status) . "</span>";
                if($feedback->solution == 'totask'   and isset($tasks[$feedback->result]))   echo " #{$feedback->result} <span class='label label-info'>" . $this->processStatus('task', $tasks[$feedback->result]) . "</span>";
                if($feedback->solution == 'toticket' and isset($tickets[$feedback->result]))   echo " #{$feedback->result} <span class='label label-info'>" . $this->processStatus('ticket', $tickets[$feedback->result]) . "</span>";
                if($feedback->solution == 'todemand' and isset($demands[$feedback->result]))   echo " #{$feedback->result} <span class='label label-info'>" . $this->processStatus('demand', $demands[$feedback->result]) . "</span>";
            }
            break;
        case 'dept':
            echo zget($depts, $feedback->dept, '/');
            break;
        case 'company':
            echo $feedback->source;
            break;
        case 'openedBy':
            echo zget($users, $feedback->openedBy);
            break;
        case 'openedDate':
            echo substr($feedback->openedDate, 5, 11);
            break;
        case 'assignedTo':
            $this->printAssignedHtml($feedback, $users);
            break;
        case 'processedBy':
            echo zget($users, $feedback->processedBy);
            break;
        case 'processedDate':
            echo helper::isZeroDate($feedback->processedDate) ? '' : substr($feedback->processedDate, 5, 11);
            break;
        case 'activatedBy':
            echo zget($users, $feedback->activatedBy);
            break;
        case 'activatedDate':
            echo helper::isZeroDate($feedback->activatedDate) ? '' : substr($feedback->activatedDate, 5, 11);
            break;
        case 'feedbackBy':
            echo $feedback->feedbackBy;
            break;
        case 'notifyEmail':
            echo $feedback->notifyEmail;
            break;
        case 'closedDate':
            echo helper::isZeroDate($feedback->closedDate) ? '' : substr($feedback->closedDate, 5, 11);
            break;
        case 'closedReason':
            echo zget($this->lang->feedback->closedReasonList, $feedback->closedReason);
            break;
        case 'editedDate':
            echo helper::isZeroDate($feedback->editedDate) ? '' : substr($feedback->editedDate, 5, 11);
            break;
        case 'actions':
            $feedback->browseType = $browseType;
            echo $this->buildOperateMenu($feedback, 'browse');
            break;
        default: $this->loadModel('flow')->printFlowCell('feedback', $feedback, $id);
        }
        echo '</td>';
    }

    /**
     * Product module feedback page add assignment function.
     *
     * @param  object $feedback
     * @param  array $users
     * @access public
     * @return string
     */
    public function printAssignedHtml($feedback, $users)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $hasPriv        = common::hasPriv('feedback', 'assignTo');
        $assignedToText = !empty($feedback->assignedTo) ? zget($users, $feedback->assignedTo) : $this->lang->feedback->noAssigned;
        if(empty($feedback->assignedTo)) $btnClass = $btnTextClass = 'assigned-none';
        if($feedback->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($feedback->assignedTo) and $feedback->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $feedback->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = helper::createLink('feedback', 'assignTo', "feedbackID=$feedback->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='$feedback->assignedTo'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !$hasPriv ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    /**
     * Sendmail.
     *
     * @param  int    $feedbackID
     * @param  int    $actionID
     * @param  string $to
     * @access public
     * @return void
     */
    public function sendmail($feedbackID, $actionID, $to = '')
    {
        $feedback = $this->getByID($feedbackID);
        if(empty($feedback) or !$feedback->notify) return false;
        $users = $this->loadModel('user')->getPairs('noletter');

        $this->loadModel('mail');
        $productName = $this->loadModel('product')->getById($feedback->product)->name;

        /* Get action info. */
        $action = $this->loadModel('action')->getById($actionID);

        $history            = $this->action->getHistory($actionID);
        $action->history    = isset($history[$actionID]) ? $history[$actionID] : array();
        $action->appendLink = '';
        if(strpos($action->extra, ':') !== false)
        {
            list($extra, $id) = explode(':', $action->extra);
            $action->extra    = $extra;

            $table = '';
            if($action->objectType == 'bug')   $table = TABLE_BUG;
            if($action->objectType == 'story') $table = TABLE_STORY;
            if($id and $table)
            {
                $name  = $this->dao->select('title')->from($table)->where('id')->eq($id)->fetch('title');
                if($name) $action->appendLink = html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink($action->objectType, 'view', "id=$id", 'html'), "#$id " . $name);
            }
        }

        /* Get mail content. */
        $modulePath = $this->app->getModulePath($appName = '', 'feedback');
        $oldcwd     = getcwd();
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        /* Send mail to notifyEmail. */
        if(!empty($feedback->notify) && !empty($feedback->notifyEmail))
        {
            $notifyAccount = new stdclass();
            $notifyAccount->email    = $feedback->notifyEmail;
            $notifyAccount->account  = 'notifyEmail';
            $notifyAccount->realname = $feedback->feedbackBy;

            $emails = array();
            $emails[$feedback->notifyEmail] = $notifyAccount;

            $this->mail->send($feedback->notifyEmail, $this->lang->feedback->common . ' #'. $feedback->id . ' ' . $feedback->title . ' - ' . $productName, $mailContent, '', false, $emails, true, false);
        }

        /* Set toList and ccList. */
        $sendUsers = $this->getToAndCcList($feedback, $action->action);
        if(!$sendUsers) return;
        list($toList, $ccList) = $sendUsers;

        if($to != 'asked')  $ccList .= ',' . $feedback->openedBy;

        /* Send it. */
        $this->mail->send($toList, $this->lang->feedback->common . ' #'. $feedback->id . ' ' . $feedback->title . ' - ' . $productName, $mailContent, $ccList);

        if($this->mail->isError()) error_log(join("\n", $this->mail->getError()));
    }

    /**
     * Get to and ccList.
     *
     * @param  object $feedback
     * @access public
     * @return array
     */
    public function getToAndCcList($feedback, $action = '')
    {
        /* Set toList and ccList. */
        $toList = !empty($feedback->assignedTo) && $feedback->assignedTo != 'closed' ? array($feedback->assignedTo) : array();
        if($action == 'closed')
        {
            $relations = $this->getRelations($feedback->id, $feedback);
            foreach($relations as $typeRelations)
            {
                foreach($typeRelations as $relation)
                {
                    if(!empty($relation->assignedTo) && $relation->status != 'closed') $toList[] = $relation->assignedTo;
                }
            }
        }
        $toList = array_unique($toList);

        $ccList = array();
        if(!$feedback->notify) $ccList[] = $feedback->openedBy . ',';
        if($feedback->mailto)  $ccList[] = $feedback->mailto . ',';
        if($feedback->product)
        {
            $product = $this->loadModel('product')->getByID($feedback->product);
            if($product->feedback) $ccList[] = $product->feedback . ',';
        }
        $ccList = array_unique($ccList);
        $ccList = array_diff($ccList, $toList);
        $toList = implode(',', $toList);
        $ccList = implode(',', $ccList);

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
     * Force not review.
     *
     * @access public
     * @return bool
     */
    public function forceNotReview()
    {
        if(empty($this->config->feedback->needReview))
        {
            if(!isset($this->config->feedback->forceReview)) return true;
            if(strpos(",{$this->config->feedback->forceReview},", ",{$this->app->user->account},") === false) return true;
        }
        if($this->config->feedback->needReview && strpos(",{$this->config->feedback->forceNotReview},", ",{$this->app->user->account},") !== false) return true;
        if($this->config->feedback->needReview && $this->config->feedback->reviewer == 'feedbackmanager')
        {
            $feedbackManager = $this->dao->select('feedback')->from(TABLE_PRODUCT)->where('id')->eq($this->post->product)->fetch('feedback');
            if(empty($feedbackManager)) return true;
        }

        return false;
    }

    /**
     * Adjust the action clickable.
     *
     * @param  object $feedback
     * @param  string $action
     * @param  string $module
     * @access public
     * @return bool
     */
    public static function isClickable($feedback, $action, $module = 'feedback')
    {
        global $app, $config;

        if(!common::hasPriv($module, $action)) return false;
        $editOthers = common::hasPriv($module, 'editOthers');

        $action = strtolower($action);

        if($module == 'feedback' && $action == 'edit'        && (empty($feedback->status) || strpos('wait|noreview|clarify', $feedback->status) !== false) && ($app->user->account == $feedback->openedBy || $app->user->admin || $editOthers)) return true;
        if($module == 'feedback' && $action == 'activate'    && in_array($feedback->status, array('replied', 'closed'))) return true;
        if($module == 'feedback' && $action == 'assignto'    && empty($app->user->feedback) && $feedback->status && strpos('closed', $feedback->status) === false ) return true;
        if($module == 'feedback' && $action == 'review'      && empty($app->user->feedback) && $feedback->status == 'noreview') return true;
        if($module == 'feedback' && $action == 'comment'     && empty($app->user->feedback) && $feedback->status && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'reply'       && empty($app->user->feedback) && $feedback->status && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'ask'         && empty($app->user->feedback) && $feedback->status && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'close'       && $feedback->status != 'closed' && ($app->user->account == $feedback->openedBy || empty($app->user->feedback))) return true;
        if($module == 'feedback' && $action == 'totodo'      && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'toticket'    && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'todemand'    && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'totask'      && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'tostory'     && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'tobug'       && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'touserstory' && !empty($config->URAndSR) && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'toepic'      && !empty($config->enableER) && strpos('closed|clarify|noreview', $feedback->status) === false) return true;
        if($module == 'feedback' && $action == 'ajaxlike' && $feedback->public) return true;
        if($module == 'feedback' && in_array($action, array('create', 'delete'))) return true;

        return false;
    }

    /**
     * Get status for different method.
     *
     * @param  string $methodName
     * @param  bool   $needReview
     * @param  string $status
     * @access public
     * @return string
     */
    public function getStatus($methodName, $needReview, $status = '')
    {
        if($methodName == 'create') $status = $needReview ? 'noreview' : 'wait';
        if($methodName == 'update')
        {
            if($status == 'clarify') $status = $needReview ? 'noreview' : 'wait';
        }
        if($methodName == 'review')
        {
            if($this->post->result == 'pass')    $status = 'wait';
            if($this->post->result == 'clarify') $status = 'clarify';
        }
        return $status;
    }

    /**
     * Build feedback menu.
     *
     * @param  object $feedback
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($feedback, $type = 'view')
    {
        if($feedback->deleted) return '';

        $this->app->loadLang('story');
        $this->app->loadLang('bug');
        $this->app->loadLang('task');
        $this->app->loadLang('todo');

        $function = 'buildOperate' . ucfirst($type) . 'Menu';
        return $this->$function($feedback);
    }

    /**
     * Build feedback view menu.
     *
     * @param  object $feedback
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($feedback)
    {
        $menu        = '';
        $params      = "feedbackID=$feedback->id";
        $adminMethod = $this->app->getMethodName() == 'admin';

        if($feedback->status != 'closed' && !isonlybody()) $menu .= $this->loadModel('effort')->createAppendLink('feedback', $feedback->id);

        if($this->app->user->account == $feedback->openedBy && common::hasPriv('feedback', 'comment') && $feedback->status != 'noreview')
        {
            $menu .= $this->buildMenu('feedback', 'ask', $params, $feedback, 'view', 'chat-line', '', "iframe", true, '', $this->lang->feedback->ask);
        }

        $menu .= $this->buildMenu('feedback', 'assignTo', $params, $feedback, 'view', '', '', "iframe", true);
        $menu .= $this->buildMenu('feedback', 'review', $params, $feedback, 'view', 'glasses', '', "showinonlybody iframe", true, '', $this->lang->feedback->review);

        if($this->config->vision != 'lite' && strpos('closed|clarify|noreview', $feedback->status) === false)
        {
            $menu .= $this->buildMenu('feedback', 'reply', $params, $feedback, 'view', 'restart', '', "iframe", true, '', $this->lang->feedback->reply);

            $hasProductPrivilege = in_array($feedback->product, explode(',', $this->app->user->view->products));
            if($hasProductPrivilege && (self::isClickable($feedback, 'toStory') || self::isClickable($feedback, 'toUserStory') || self::isClickable($feedback, 'toepic') || self::isClickable($feedback, 'toTask') || self::isClickable($feedback, 'toBug') || self::isClickable($feedback, 'toTodo') || self::isClickable($feedback, 'toTicket') || self::isClickable($feedback, 'toDemand')))
            {
                $menu .= "<div class='btn-group dropup'>";
                $menu .= "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><i class='icon icon-arrow-right'></i> " . $this->lang->feedback->convert . " <span class='caret'></span></button>";
                $menu .= "<ul class='dropdown-menu' id='createCaseActionMenu'>";

                if(self::isClickable($feedback, 'toDemand') and $this->config->vision == 'or')
                {
                    $link = helper::createLink('feedback', 'toDemand', "extra=fromType=feedback,fromID=$feedback->id");
                    $menu .= "<li>" . html::a($link, $this->lang->feedback->toDemand, '', "data-app='feedback'") . "</li>";
                }
                if(self::isClickable($feedback, 'toTicket') and $this->config->vision != 'or')
                {
                    $link = helper::createLink('feedback', 'toTicket', "extras=fromType=feedback,fromID={$feedback->id}");
                    $menu .= "<li>" . html::a($link, $this->lang->ticket->common, '', "data-app='feedback'") . "</li>";
                }
                if(self::isClickable($feedback, 'toStory') and $this->config->vision != 'or')
                {
                    $link = helper::createLink('feedback', 'toStory', "product=$feedback->product&extra=fromType=feedback,fromID=$feedback->id");
                    $menu .= "<li>" . html::a($link, $this->lang->SRCommon, '', "data-app='feedback'") . "</li>";
                }

                if(self::isClickable($feedback, 'toUserStory'))
                {
                    $link = helper::createLink('feedback', 'toUserStory', "product=$feedback->product&extra=fromType=feedback,fromID=$feedback->id");
                    $menu .= "<li>" . html::a($link, $this->lang->URCommon, '', "data-app='feedback'") . "</li>";
                }

                if(self::isClickable($feedback, 'toepic'))
                {
                    $link = helper::createLink('feedback', 'toepic', "product=$feedback->product&extra=fromType=feedback,fromID=$feedback->id");
                    $menu .= "<li>" . html::a($link, $this->lang->ERCommon, '', "data-app='feedback'") . "</li>";
                }

                if(self::isClickable($feedback, 'toTask') and $this->config->vision != 'or')
                {
                    $menu .= "<li>" . html::a('#toTask', $this->lang->task->common, '', "data-toggle='modal' data-id='$feedback->id' data-product={$feedback->product} onclick='getFeedbackID(this)'") . "</li>";
                }

                if(self::isClickable($feedback, 'toBug') and $this->config->vision != 'or')
                {
                    $link = helper::createLink('bug', 'create', "product={$feedback->product}&branch=0&extras=projectID=0,fromType=feedback,fromID=$feedback->id");
                    $menu .= "<li>" . html::a($link, $this->lang->bug->common, '', "data-id='$feedback->id' data-app='feedback'") . "</li>";
                }

                if(self::isClickable($feedback, 'toTodo'))
                {
                    $link = helper::createLink('feedback', 'toTodo', "feedbackID={$feedback->id}");
                    $menu .= "<li>" . html::a($link, $this->lang->todo->common, '', "data-app='feedback'") . "</li>";
                }

                $menu .= "</ul>";
                $menu .= "</div>";
            }
        }

        if(strpos('clarify|noreview', $feedback->status) !== false and self::isClickable($feedback, 'toTodo')) $menu .= $this->buildMenu('feedback', 'toTodo', "feedbackID={$feedback->id}", $feedback, 'view', 'check', '', '', '', '', $this->lang->feedback->toTodo);

        $menu .= $this->buildMenu('feedback', 'close', $params, $feedback, 'view', '', '', "iframe", true, '', $this->lang->feedback->close);

        if($feedback->public)
        {
            $likeByTitle = '';
            $users       = $feedback->userList;
            if($feedback->public && $feedback->likes)
            {
                foreach(explode(',', $feedback->likes) as $likeBy) $likeByTitle .= zget($users, $likeBy, $likeBy) . ',';
                $likeByTitle .= $this->lang->feedback->feelLike;
            }

            $menu .= $this->buildMenu('feedback', 'comment', "$params&type=commented", $feedback, 'view', 'confirm', '', "iframe", true, '', $this->lang->feedback->comment);
            $menu .= "<span class='likesBox'>";
            $menu .= html::a("javascript:like($feedback->id)", "<i class='icon icon-{$feedback->likeIcon}'></i> ({$feedback->likesCount})", '', "class='btn' title='$likeByTitle'");
            $menu .= "</span>";
        }

        if($feedback->status != 'closed') $menu .= "<div class='divider'></div>";
        $menu .= $this->buildFlowMenu('feedback', $feedback, 'view', 'direct');
        if($feedback->status != 'closed') $menu .= "<div class='divider'></div>";

        $menu .= $this->buildMenu('feedback', 'activate', $params, $feedback, 'view', 'magic', '', "iframe", true, '', $this->lang->feedback->activate);
        $menu .= $this->buildMenu('feedback', 'edit', "$params&browseType={$feedback->browseType}", $feedback, 'view');
        $menu .= $this->buildMenu('feedback', 'delete', $params, $feedback, 'view', 'trash', 'hiddenwin');

        return $menu;
    }

    /**
     * Build feedback browse menu.
     *
     * @param  object $feedback
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenu($feedback)
    {
        $menu        = '';
        $iframe      = '';
        $params      = "feedbackID=$feedback->id";
        $adminMethod = $this->app->getMethodName() == 'admin';
        if($this->app->getMethodName() == 'feedback') $iframe = true;

        $menu .= $this->buildMenu('feedback', 'edit', "id=$feedback->id&browseType={$feedback->browseType}", $feedback, 'browse', '', '', $iframe ? 'iframe' : '', $iframe);

        if($adminMethod or ($this->app->getModuleName() == 'my' and $this->config->vision != 'lite'))
        {
            if($feedback->status == 'noreview')
            {
                $menu .= $this->buildMenu('feedback', 'review', $params, $feedback, 'browse', 'glasses', '', "showinonlybody iframe", true, '', $this->lang->feedback->review);
                $menu .= $this->buildMenu('feedback', 'toTodo', "feedbackID=$feedback->id", '', 'browse', $this->lang->icons['todo'], '', ($iframe ? 'iframe' : ''), $iframe, '', $this->lang->feedback->toTodo);
            }
            else
            {
                $disabled = strpos('closed|clarify', $feedback->status) === false ? '' : ' disabled';
                $menu .= $this->buildMenu('feedback', 'reply', $params, $feedback, 'browse', 'restart', '', "iframe {$disabled}", true, $disabled, $this->lang->feedback->reply);

                if(self::isClickable($feedback, 'toStory') || self::isClickable($feedback, 'toUserStory') || self::isClickable($feedback, 'toepic') || self::isClickable($feedback, 'toTask') || self::isClickable($feedback, 'toBug') || self::isClickable($feedback, 'toTodo') || self::isClickable($feedback, 'toTicket') || self::isClickable($feedback, 'toDemand'))
                {
                    $hasProductPrivilege = $this->app->user->admin || in_array($feedback->product, explode(',', $this->app->user->view->products));
                    $dropdownDisabled    = $hasProductPrivilege ? '' : 'disabled';
                    $dropdownhidden      = $hasProductPrivilege ? '' : 'hidden';
                    $menu .= "<div class='btn-group' style='padding: 0 6px 0 0;'>";
                    $menu .= "<button type='button' class='btn icon-caret-down dropdown-toggle $dropdownDisabled' data-toggle='context-dropdown' title='{$this->lang->more}' style='width: 16px; padding-left: 0px;'></button>";
                    $menu .= "<ul class='dropdown-menu pull-right c-actions text-center $dropdownhidden' role='menu' style='position: unset; min-width: auto; padding: 5px 0; white-space: nowrap;'>";
                    if($hasProductPrivilege)
                    {
                        if(self::isClickable($feedback, 'toTicket') and $this->config->vision != 'or')
                        {
                            $link = helper::createLink('feedback', 'toTicket', "extras=fromType=feedback,fromID={$feedback->id}");
                            $class = $disabled ? "class='btn disabled' disabled" : "data-id='$feedback->id' data-product={$feedback->product} onclick='getFeedbackID(this)' class='btn' title='{$this->lang->feedback->toTicket}'";
                            $menu .= html::a($link, "<i class='icon icon-" . $this->lang->icons['ticket'] . "'></i> ", '', $class);
                        }
                        $menu .= $this->buildMenu('feedback', 'suspend', $params, $feedback, 'pause', '', 'iframe btn-action btn', true, '');
                        if($this->config->vision == 'or') $menu .= $this->buildMenu('feedback', 'toDemand', "poolID=0&demandID=0&extra=fromType=feedback,fromID=$feedback->id", '', 'browse', $this->lang->icons['story'], '', ($iframe ? 'iframe' : '') . $disabled, $iframe, "data-width=95% {$disabled}", $this->lang->feedback->toDemand);
                        if($this->config->vision != 'or') $menu .= $this->buildMenu('feedback', 'toStory', "product=$feedback->product&extra=fromType=feedback,fromID=$feedback->id", '', 'browse', $this->lang->icons['story'], '', ($iframe ? 'iframe' : '') . $disabled, $iframe, "data-width=95% {$disabled}", $this->lang->feedback->toStory);
                        if(!empty($this->config->URAndSR)) $menu .= $this->buildMenu('feedback', 'toUserStory', "product=$feedback->product&extra=fromType=feedback,fromID=$feedback->id", '', 'browse', 'customer', '', ($iframe ? 'iframe' : '') . $disabled, $iframe, "data-width=95% {$disabled}", $this->lang->feedback->toUserStory);
                        if(!empty($this->config->enableER)) $menu .= $this->buildMenu('feedback', 'toepic', "product=$feedback->product&extra=fromType=feedback,fromID=$feedback->id", '', 'browse', 'customer', '', ($iframe ? 'iframe' : '') . $disabled, $iframe, "data-width=95% {$disabled}", $this->lang->feedback->toepic);
                        if(self::isClickable($feedback, 'toTask') and $this->config->vision != 'or')
                        {
                            $link  = $disabled ? '###' : '#toTask';
                            $class = $disabled ? "class='btn disabled' disabled" : "data-toggle='modal' data-id='$feedback->id' data-product={$feedback->product} onclick='getFeedbackID(this)' class='btn' title='{$this->lang->feedback->toTask}'";
                            $menu .= html::a($link, "<i class='icon icon-" . $this->lang->icons['task'] . "'></i> ", '', $class);
                        }

                        if(self::isClickable($feedback, 'toBug') and $this->config->vision != 'or')
                        {
                            $link  = $disabled ? '###' : helper::createLink('bug', 'create', "product={$feedback->product}&branch=0&extras=projectID=0,fromType=feedback,fromID=$feedback->id");
                            $class = $disabled ? "class='btn disabled' disabled" : " data-id='$feedback->id' data-product={$feedback->product} data-app='feedback' class='btn' title='{$this->lang->feedback->toBug}'";
                            $menu .= html::a($link, "<i class='icon icon-" . $this->lang->icons['bug'] . "'></i> ", '', $class);
                        }

                        $todoBtnStatus = $feedback->status == 'clarify' ? '' : $disabled;
                        $menu .= $this->buildMenu('feedback', 'toTodo', "feedbackID=$feedback->id", '', 'browse', $this->lang->icons['todo'], '', ($iframe ? 'iframe' : '') . $todoBtnStatus, $iframe, $todoBtnStatus, $this->lang->feedback->toTodo);
                    }

                    $menu .= "</ul>";
                    $menu .= "</div>";
                }
            }
        }

        $menu .= $this->buildMenu('feedback', 'close', $params, $feedback, 'browse', 'off', '', "iframe", '', '', $this->lang->feedback->close);
        $menu .= $this->buildMenu('feedback', 'delete', $params, $feedback, 'browse', 'trash', 'hiddenwin', '', '', '', $this->lang->feedback->delete);

        return $menu;
    }

    /**
     * Process feedback status.
     *
     * @param  string  $type
     * @param  int     $feedbackID
     * @param  string  $status
     * @param  string  $oldStatus
     * @param  int     $objectID
     * @access public
     * @return bool
     */
    public function updateStatus($type, $feedbackID, $status, $oldStatus = '', $objectID = 0)
    {
        if(isset($this->config->feedback->undoneStatusList[$type]) && in_array($status, $this->config->feedback->undoneStatusList[$type])) return $this->activateByObject($type, $feedbackID, $status, $objectID);

        $statusList = $this->config->feedback->relationStatusList;
        if(in_array($oldStatus, $statusList[$type]) || !in_array($status, $statusList[$type])) return false;

        $feedback = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->eq($feedbackID)->fetch();
        if(!$feedback || $feedback->status == 'replied' || $feedback->status == 'closed') return false;

        $relations = $this->getRelations($feedbackID, $feedback);
        if(!$relations) return false;

        $isReplied = true;
        foreach($relations as $objectType => $relationList)
        {
            foreach($relationList as $relation)
            {
                if(!in_array($relation->status, $statusList[$objectType]))
                {
                    $isReplied = false;
                    break;
                }
            }

            if(!$isReplied) break;
        }

        if($isReplied)
        {
            $this->dao->update(TABLE_FEEDBACK)->set('status')->eq('replied')->where('id')->eq($feedbackID)->exec();

            if($type == 'story')
            {
                $storyType = $this->dao->select('type')->from(TABLE_STORY)->where('id')->eq($objectID)->fetch('type');
                $type      = $storyType == 'requirement' ? 'userstory' : $storyType;
            }
            $this->loadModel('action')->create('feedback', $feedbackID, 'processedby' . $type, '', $objectID);
        }

        if($oldStatus != $status)
        {
            $status = $isReplied ? 'replied' : $status;
            $this->updateSubStatus($feedbackID, $status);
        }

        return !dao::isError();
    }

    /**
     * Create feedback from import.
     *
     * @access public
     * @return bool
     */
    public function createFromImport()
    {
        $this->loadModel('action');
        $now  = helper::now();
        $data = fixer::input('post')->get();

        if(!$this->session->insert) $oldFeedbacks = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->in($_POST['id'])->fetchAll('id', false);

        $feedbacks         = array();
        $line              = 1;
        $extendFields      = $this->getFlowExtendFields();
        $notEmptyRule      = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

        foreach($extendFields as $extendField)
        {
            if(strpos(",$extendField->rules,", ",$notEmptyRule->id,") !== false)
            {
                $this->config->feedback->create->requiredFields .= ',' . $extendField->field;
            }
        }

        foreach($data->title as $key => $value)
        {
            $feedbackData = new stdclass();

            if(empty($value)) continue;
            $feedbackData->module      = $data->module[$key] ? $data->module[$key] : 0;
            $feedbackData->product     = $data->product[$key];
            $feedbackData->type        = $data->type[$key];
            $feedbackData->title       = $data->title[$key];
            $feedbackData->desc        = $data->desc[$key];
            $feedbackData->source      = $data->source[$key];
            $feedbackData->pri         = $data->pri[$key] ? $data->pri[$key] : 3;
            $feedbackData->feedbackBy  = $data->feedbackBy[$key];
            $feedbackData->notifyEmail = $data->notifyEmail[$key];
            $feedbackData->public      = (empty($data->public[$key]) and $data->public[$key] === '') ? '1' : $data->public[$key];
            $feedbackData->notify      = (empty($data->notify[$key]) and $data->notify[$key] === '') ? '1' : $data->notify[$key];

            foreach($extendFields as $extendField)
            {
                $dataArray = $_POST[$extendField->field];
                $feedbackData->{$extendField->field} = $dataArray[$key];
                if(is_array($feedbackData->{$extendField->field})) $feedbackData->{$extendField->field} = join(',', $feedbackData->{$extendField->field});

                $feedbackData->{$extendField->field} = htmlSpecialString($feedbackData->{$extendField->field});
            }

            if(isset($this->config->feedback->create->requiredFields))
            {
                $requiredFields = explode(',', $this->config->feedback->create->requiredFields);
                foreach($requiredFields as $requiredField)
                {
                    $requiredField = trim($requiredField);
                    if(empty($feedbackData->$requiredField)) dao::$errors[] = sprintf($this->lang->feedback->noRequire, $line, $this->lang->feedback->$requiredField);
                }
            }

            $feedbacks[$key] = $feedbackData;
            $line++;
        }

        if(dao::isError()) return false;

        $message = $this->lang->saveSuccess;
        foreach($feedbacks as $key => $feedbackData)
        {
            $feedbackID = 0;
            if(!empty($_POST['id'][$key]) and empty($_POST['insert']))
            {
                $feedbackID = $data->id[$key];
                if(!isset($oldFeedbacks[$feedbackID])) $feedbackID = 0;
            }

            if($feedbackID)
            {
                $oldFeedback = (array)$oldFeedbacks[$feedbackID];
                $newFeedback = (array)$feedbackData;

                $changes = common::createChanges((object)$oldFeedback, (object)$newFeedback);
                if(empty($changes)) continue;

                $feedbackData->editedBy   = $this->app->user->account;
                $feedbackData->editedDate = $now;
                $this->dao->update(TABLE_FEEDBACK)->data($feedbackData)->where('id')->eq($feedbackID)->autoCheck()->checkFlow()->exec();

                if(!dao::isError())
                {
                    $actionID = $this->action->create('feedback', $feedbackID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                    $message = $this->executeHooks($feedbackID);
                }
            }
            else
            {
                $needReview = !$this->forceNotReview();
                if($needReview)
                {
                    $assignedTo = $this->config->feedback->reviewer;
                    if(empty($assignedTo)) $assignedTo = $this->loadModel('dept')->getManager($this->app->user->dept);
                    if($assignedTo == 'feedbackmanager') $assignedTo = $this->dao->select('feedback')->from(TABLE_PRODUCT)->where('id')->eq($this->post->product)->fetch('feedback');
                }
                else
                {
                    $assignedTo = $this->dao->findByID($feedbackData->product)->from(TABLE_PRODUCT)->fetch('feedback');
                }

                if(!empty($assignedTo))
                {
                    $feedbackData->assignedTo   = $assignedTo;
                    $feedbackData->assignedDate = $now;
                }

                $feedbackData->openedBy   = $this->app->user->account;
                $feedbackData->openedDate = $now;
                $feedbackData->editedBy   = $this->app->user->account;
                $feedbackData->editedDate = $now;
                $feedbackData->status     = $this->getStatus('create', $needReview);

                $this->dao->insert(TABLE_FEEDBACK)->data($feedbackData)->autoCheck()->checkFlow()->exec();

                if(!dao::isError())
                {
                    $feedbackID = $this->dao->lastInsertID();
                    $this->action->create('feedback', $feedbackID, 'Opened');
                    $message = $this->executeHooks($feedbackID);
                }
            }
        }

        if($this->post->isEndPage)
        {
            if(is_file($this->session->fileImport)) unlink($this->session->fileImport);
            unset($_SESSION['fileImport']);
        }

        return $message;
    }

    /**
     * Get Module List group by product
     *
     * @access public
     * @return array
     */
    public function getModuleListGroupByProduct()
    {
        $productIDList = $this->getFeedbackProducts();

        $moduleList = array();
        if(empty($productIDList)) return $moduleList;

        foreach($productIDList as $id => $product)
        {
            $modules = $this->loadModel('tree')->getOptionMenu($id, 'feedback', 0, 'all');
            foreach($modules as $moduleID => $moduleName)
            {
                $moduleList[$id][] = array('text' => $moduleName, 'value' => $moduleID);
            }
        }

        return $moduleList;
    }

    /**
     * Get module by product
     *
     * @access public
     * @return array
     */
    public function getProductModule($productID)
    {
        $module = $this->dao->select('id, name')->from(TABLE_MODULE)
            ->where('type')->eq('feedback')
            ->andWhere('root')->eq($productID)
            ->andWhere('deleted')->eq('0')
            ->fetchAll();
        $returnData = array(0 => '/');
        foreach($module as $item) $returnData[$item->id] = $item->name;

        return $returnData;
    }

    /**
     * Set Menu for feedback.
     *
     * @param  int    $productID
     * @param  string $module
     * @param  string $extra
     * @access public
     * @return void
     */
    public function setMenu($productID = 0, $module = 'feedback', $extra = '')
    {
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;

        if($productID !== 'all' and !$productID)
        {
            $products  = $this->getGrantProducts();
            if($products) $productID = key($products);
        }

        $this->session->set($module . 'Product', $productID, 'feedback');
        $this->lang->switcherMenu = $this->getSwitcher($productID, $extra);

        common::setMenuVars('feedback', (int)$productID);
    }

    /**
     * Get Switcher for feedback.
     *
     * @param  int    $productID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function getSwitcher($productID = 0, $extra = '')
    {
        $currentModule = $this->app->moduleName;
        $currentMethod = $this->app->methodName;

        $this->loadModel('product');
        $currentProductName = $this->lang->product->allProduct;
        $currentProduct     = $this->product->getById((int)$productID);

        if($productID != 'all' and $currentProduct)
        {
            $currentProductName = $currentProduct->name;
            $this->session->set('currentProductType', $currentProduct->type);
        }

        $dropMenuLink  = helper::createLink('feedback', 'ajaxGetDropMenu', "objectID=$productID&module=$currentModule&method=$currentMethod&extra=$extra");

        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProductName}'><span class='text'>{$currentProductName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";
        return $output;
    }

    /*
     * Build feedback search form
     *
     * @param  string     $actionURL
     * @param  int        $queryID
     * @param  string|int $productID
     * @access public
     * @return void
     */
    public function buildSearchForm($actionURL, $queryID = 0, $productID = 'all')
    {
        $this->config->feedback->search['actionURL'] = $actionURL;
        $this->config->feedback->search['queryID']   = $queryID;
        $this->config->feedback->search['params']['source']['values']      = $this->getSource();
        $this->config->feedback->search['params']['product']['values']     = arrayUnion(array('' => ''), $this->getGrantProducts());
        $this->config->feedback->search['params']['module']['values']      = $productID == 'all' ? $this->getModuleList('feedback', true) : array_merge(array('' => ''), $this->loadModel('tree')->getOptionMenu(intVal($productID), 'feedback', 0, 'all'));
        $this->config->feedback->search['params']['processedBy']['values'] = arrayUnion(array('' => ''), $this->getFeedbackPairs('admin'));
        $this->loadModel('search')->setSearchParams($this->config->feedback->search);
    }

    /*
     * Get feedback search config
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function buildSearchConfig($productID)
    {
        unset($this->config->feedback->search['fields']['product']);
        $this->config->feedback->search['params']['source']['values']      = $this->getSource();
        $this->config->feedback->search['params']['module']['values']      = $this->loadModel('tree')->getOptionMenu(intVal($productID), 'feedback', 0, 'all');
        $this->config->feedback->search['params']['processedBy']['values'] = arrayUnion(array('' => ''), $this->getFeedbackPairs('admin'));

        $_SESSION['searchParams']['module'] = 'feedback';
        $searchConfig = $this->config->feedback->search;
        $searchConfig['params'] = $this->loadModel('search')->setDefaultParams($searchConfig['fields'], $searchConfig['params']);

        return $searchConfig;
    }

    /**
     * Get feedback company source.
     *
     * return array
     */
    public function getSource()
    {
        return  $this->dao->select('source')->from(TABLE_FEEDBACK)->fetchPairs('source');
    }

    /**
     * Get feedback pairs.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * return array
     */
    public function getPairs($productID = 0, $orderBy = 'id_desc')
    {
        return $this->dao->select('id, title')
            ->from(TABLE_FEEDBACK)
            ->where('deleted')->eq(0)
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * Product setting.
     *
     * return bool
     */
    public function productSetting()
    {
        $data = fixer::input('post')->get();

        foreach($data->products as $key => $productID)
        {
            $feedback = !empty($data->feedbacks[$key]) ? $data->feedbacks[$key] : '';
            $ticket   = !empty($data->tickets[$key]) ? $data->tickets[$key] : '';
            $this->dao->update(TABLE_PRODUCT)->set('feedback')->eq($feedback)->set('ticket')->eq($ticket)->where('id')->eq($productID)->exec();
        }

        return true;
    }

    /**
     * 更新子状态。
     * Update sub status.
     *
     * @param  int    $feedbackID
     * @param  string $status
     * @access public
     * @return void
     */
    public function updateSubStatus($feedbackID, $status = '')
    {
        if(!$status) return false;

        $field = $this->dao->select('options')->from(TABLE_WORKFLOWFIELD)->where('`module`')->eq('feedback')->andWhere('`field`')->eq('subStatus')->fetch();
        if(!empty($field->options)) $field->options = json_decode($field->options, true);

        if(empty($field->options[$status]['default'])) return false;

        $flow    = $this->dao->select('`table`')->from(TABLE_WORKFLOW)->where('`module`')->eq('feedback')->fetch();
        $default = $field->options[$status]['default'];
        $this->dao->update($flow->table)->set('subStatus')->eq($default)->where('id')->eq($feedbackID)->exec();

        return dao::isError();
    }

    /**
     * 通过反馈关联对象激活反馈。
     * Activate feedback by object.
     *
     * @param  string $type
     * @param  int    $feedbackID
     * @param  string $status
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function activateByObject($type, $feedbackID, $status, $objectID)
    {
        if(!isset($this->config->feedback->undoneStatusList[$type]) || !in_array($status, $this->config->feedback->undoneStatusList[$type])) return false;

        $oldFeedback = $this->fetchByID($feedbackID);
        if(!in_array($oldFeedback->status, array('replied', 'closed'))) return false;

        $data = new stdClass();
        $data->status         = 'commenting';
        $data->assignedTo     = $this->getAssignedTo($oldFeedback);
        $data->prevAssignedTo = '';
        $data->assignedDate   = helper::now();
        $data->processedBy    = '';
        $data->processedDate  = null;
        $data->closedBy       = '';
        $data->closedDate     = null;
        $data->closedReason   = '';
        $data->activatedBy    = $this->app->user->account;
        $data->activatedDate  = helper::now();
        $this->dao->update(TABLE_FEEDBACK)->data($data)->where('id')->eq($feedbackID)->exec();
        if(dao::isError()) return false;

        $this->updateSubStatus($feedbackID, 'commenting');

        if($type == 'story')
        {
            $storyType = $this->dao->select('type')->from(TABLE_STORY)->where('id')->eq($objectID)->fetch('type');
            $type      = $storyType == 'requirement' ? 'userstory' : $storyType;
        }

        $feedback = $this->fetchByID($feedbackID);
        $actionID = $this->loadModel('action')->create('feedback', $feedbackID, 'activatedby' . $type, '', $objectID);
        $changes  = common::createChanges($oldFeedback, $feedback);
        $this->action->logHistory($actionID, $changes);
        return dao::isError();
    }

    /**
     * 获取反馈的指派人。
     * Get assigned to.
     *
     * @param  object $feedback
     * @access public
     * @return string
     */
    public function getAssignedTo($feedback)
    {
        $this->loadModel('user');
        if(!empty($feedback->prevAssignedTo))
        {
            $user = $this->user->getById($feedback->prevAssignedTo);
            if(!$user->deleted) return $feedback->prevAssignedTo;
        }

        $creator = $this->user->getById($feedback->openedBy);
        if(!empty($creator) && !$creator->deleted) return $feedback->openedBy;

        $product      = $this->loadModel('product')->fetchByID($feedback->product);
        $productOwner = $this->user->getById($product->PO);
        return empty($productOwner) || $productOwner->deleted ? '' : $product->PO;
    }
}
