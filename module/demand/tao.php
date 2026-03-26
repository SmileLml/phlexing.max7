<?php
/**
 * The model file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      tanghucheng<tanghucheng@cnezsoft.com>
 * @package     demand
 * @link        https://www.zentao.net
 */
class demandTao extends demandModel
{
    /**
     * 创建需求描述和验收标准。
     * Do create story spec.
     *
     * @param  int       $demandID
     * @param  object    $demand
     * @param  array     $files
     * @access protected
     * @return void
     */
    protected function doCreateSpec($demandID, $demand, $files = array())
    {
        if(empty($demandID)) return;

        $data          = new stdclass();
        $data->demand  = $demandID;
        $data->version = 1;
        $data->title   = $demand->title;
        $data->spec    = $demand->spec;
        $data->verify  = $demand->verify;
        $data->files   = is_string($files) ? $files : implode(',', array_keys($files));
        $this->dao->insert(TABLE_DEMANDSPEC)->data($data)->exec();
    }

    /**
     * 创建需求的时候，关联创建评审人列表。
     * Do create reviewer when create demand.
     *
     * @param  int       $demandID
     * @param  array     $reviewers
     * @access protected
     * @return void
     */
    protected function doCreateReviewer($demandID, $reviewers)
    {
        if(empty($demandID) or empty($reviewers)) return;

        foreach($reviewers as $reviewer)
        {
            if(empty($reviewer)) continue;

            $reviewData = new stdclass();
            $reviewData->demand     = $demandID;
            $reviewData->version    = 1;
            $reviewData->reviewer   = $reviewer;
            $reviewData->reviewDate = helper::now();
            $this->dao->insert(TABLE_DEMANDREVIEW)->data($reviewData)->exec();
        }
    }

    /**
     * 如果是反馈转需求池需求，记录反馈和需求的关联关系。
     * Do create feedback when create demand.
     *
     * @param  int       $demandID
     * @param  array     $reviewers
     * @access protected
     * @return void
     * @param int $feedbackID
     */
    protected function updateFeedback($demandID, $feedbackID = 0)
    {
        if(empty($feedbackID)) return;
        $fileIDPairs = $this->loadModel('file')->copyObjectFiles('demand');

        /* If demand is from feedback, record action for feedback and add files to demand from feedback. */
        $oldFeedback = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->eq($feedbackID)->fetch();

        $feedback = new stdclass();
        $feedback->status        = 'commenting';
        $feedback->result        = $demandID;
        $feedback->solution      = 'todemand';
        $feedback->processedBy   = $this->app->user->account;
        $feedback->processedDate = helper::now();

        $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($feedbackID)->exec();

        $this->loadModel('action')->create('feedback', $feedbackID, 'ToDemand', '', $demandID);
        if($oldFeedback->status != 'commenting') $this->action->create('feedback', $feedbackID, 'syncDoingByDemand', '', $demandID);

        $relation = new stdClass();
        $relation->AType    = 'feedback';
        $relation->AID      = $feedbackID;
        $relation->relation = 'transferredto';
        $relation->BType    = 'demand';
        $relation->BID      = $demandID;
        $relation->product  = 0;
        $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

        /* 将反馈附件复制到需求池需求中。*/
        /* Copy feedback files to demand. */
        if($fileIDPairs) $this->dao->update(TABLE_FILE)->set('objectID')->eq($demandID)->where('id')->in($fileIDPairs)->exec();
    }

    /**
     * Compute parent demand stage.
     *
     * @param  array      storyList
     * @param  bool       updateParent
     * @access protected
     * @return void
     */
    protected function computeStage($storyList, $updateParent = false)
    {
        /* Set stage weight. */
        $stageWeightList   = array('wait' => 1, 'distributed' => 1, 'inroadmap' => 2, 'incharter' => 3, 'planned' => 3, 'projected' => 3, 'developing' => 4);
        $preDeliveringList = array(1 => 'distributed', 2 => 'inroadmap', 3 => 'incharter', 4 => 'developing');
        if($updateParent)
        {
            $stageWeightList   = array('wait' => 1, 'distributed' => 2, 'inroadmap' => 3, 'incharter' => 4, 'developing' => 5);
            $preDeliveringList = array(1 => 'wait', 2 => 'distributed', 3 => 'inroadmap', 4 => 'incharter', 5 => 'developing');
        }

        $computedStage    = '';
        $allClosed        = true;
        $allReasonNotDone = true;
        $allDone          = true;
        $hasDelivering    = false;
        $hasDelivered     = false;
        $stageWeight      = 0;
        foreach($storyList as $story)
        {
            /* 1. 分发的业用需或子OR需求都是非已完成已关闭阶段时，OR需求为待分发。*/
            if($story->stage != 'closed')      $allClosed        = false;
            if($story->closedReason == 'done') $allReasonNotDone = false;

            /* 已关闭阶段且关闭原因不是已完成的需求，只参与待分发阶段计算。*/
            if($story->stage == 'closed' && $story->closedReason != 'done') continue;

            /* 2. 分发的业用需或子OR需求都是已完成关闭阶段时，或阶段都为已交付，OR需求为已交付。*/
            if(!in_array($story->stage, array('closed', 'delivered'))) $allDone = false;

            /**
             * 3. OR需求为交付中的判断逻辑：
             *  1) 分发的业用需或子OR需求中至少有一个是已交付且有一个是交付中或交付前，OR需求的交付阶段为交付中。
             *  2) 分发的业用需或子OR需求没有已交付且至少有一个是交付中，OR需求的交付阶段为交付中。
             */
            if($story->stage == 'delivering') $hasDelivering = true;
            if($story->stage == 'delivered' || ($story->stage == 'closed' && $story->closedReason == 'done')) $hasDelivered = true;

            /* 4. 分发的业用需或子OR需求都是交付前的阶段，OR需求的交付阶段取业用需中进度最快的研发阶段。*/
            /* 业用需或子OR需求交付前的研发阶段包括未开始、已设路标、Charter立项、已计划、研发立项、研发中。*/
            if(in_array($story->stage, array_keys($stageWeightList)) && $stageWeight < $stageWeightList[$story->stage]) $stageWeight = $stageWeightList[$story->stage];
        }
        if($allClosed && $allReasonNotDone)                $computedStage = 'wait';
        if($computedStage != 'wait' && $allDone)           $computedStage = 'delivered';
        if($hasDelivering || (!$allDone && $hasDelivered)) $computedStage = 'delivering';
        if($computedStage != 'delivering' && $stageWeight) $computedStage = $preDeliveringList[$stageWeight];

        return $computedStage;
    }
}
