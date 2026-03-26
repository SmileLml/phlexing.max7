<?php

use function zin\wg;

/**
 * The control file of approval module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     approval
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class approval extends control
{
    /**
     * Ajax generate nodes.
     *
     * @param int $flowID
     * @param int $version
     * @access public
     * @return void
     */
    public function ajaxGenNodes($flowID, $version = 0)
    {
    }

    /**
     * Display progress.
     *
     * @param int   $approvalID
     * @access public
     * @return void
     */
    public function progress($approvalID)
    {
        $this->app->loadLang('approvalflow');
        $approval = $this->approval->getByID($approvalID);
        if(!$approval) return;

        /* Get approval times and approval time. */
        $approvals     = $this->approval->getListByObject($approval->objectType, $approval->objectID, 'id_asc');
        $approvalCount = count($approvals);
        $firstApproval = current($approvals);
        $approvalTime  = 0;
        if(!helper::isZeroDate($firstApproval->createdDate))
        {
            $endTime = time();
            if($approval->status == 'done')
            {
                $lastReviewDate = $this->approval->getLastReviewDate($approvalID);
                $endTime        = empty($lastReviewDate) ? strtotime($lastReviewDate) : strtotime($firstApproval->createdDate);
            }

            $approvalTime = $endTime - strtotime($firstApproval->createdDate);
            $approvalTime = round($approvalTime / 3600, 2);
            if($approvalTime < 1) $approvalTime = 0;
        }

        $reviewerGroup = array();
        $nodeGroup     = array();
        $nodes         = array();
        foreach($approvals as $approvalID => $approval)
        {
            $nodes     = json_decode($approval->nodes);
            $reviewers = $this->approval->getNodeReviewers($approvalID);

            $nodeGroup[$approvalID]     = $this->approval->orderBranchNodes($nodes, $reviewers, $approval->extra);
            $reviewerGroup[$approvalID] = $reviewers;
        }

        $nodePairs   = array();
        $nodeOptions = $this->approval->getNodeOptions($nodes);
        foreach($nodeOptions as $node) $nodePairs[$node->id] = $node->title;

        $noticeLang = $this->lang->approval->notice;

        $approvalNotice = '';
        if($approvalCount > 1) $approvalNotice .= sprintf($noticeLang->times, $approvalCount);

        if($approvalTime)
        {
            $days =($approvalTime > 24) ? floor($approvalTime / 24) : 0;
            $hour = (int)$approvalTime % 24;

            $processedApprovalTime = $hour . $noticeLang->hour;
            if($days > 0) $processedApprovalTime = $days . $noticeLang->day . $processedApprovalTime;

            $processedTime = sprintf($noticeLang->approvalTime, $processedApprovalTime);

            if($approvalNotice) $approvalNotice .= $this->lang->comma;
            $approvalNotice .= $processedTime;
        }

        if($approvalNotice) $approvalNotice = "<span class='approvalNotice'>({$approvalNotice})</span>";

        $this->view->title          = $this->lang->approval->progress;
        $this->view->approvalNotice = $approvalNotice;
        $this->view->approvals      = $approvals;
        $this->view->approvalCount  = $approvalCount;
        $this->view->approvalTime   = $approvalTime;
        $this->view->nodeGroup      = $nodeGroup;
        $this->view->reviewerGroup  = $reviewerGroup;
        $this->view->nodePairs      = $nodePairs;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter|all');
        $this->display();
    }

    /**
     * 回退节点。
     * Revert a node.
     *
     * @param  string  $objectType
     * @param  int     $objectID
     * @access public
     * @return void
     */
    public function revert($objectType, $objectID)
    {
        $approval = $this->approval->getByObject($objectType, $objectID);
        if($_POST)
        {
            $reviewerAccount = $this->approval->getReviewerByApprovalID($approval->id);
            if(strpos(',' . implode(',', $reviewerAccount) . ',', ',' . $this->app->user->account . ',') === false) return $this->send(array('result' => 'fail', 'message' => $this->lang->approval->cannotOperate));

            $data = fixer::input('post')
                ->stripTags('revertOpinion', $this->config->allowedTags)
                ->get();

            if(!$data->revertOpinion) $this->send(array('result' => 'fail', 'message' => $this->lang->approval->revertOpinionRequired));

            $this->approval->revert($approval->id, $data->currentNodeID, $data->toNodeID, $data->revertType);
            $this->loadModel('action')->create($objectType, $objectID, 'Reverted', $data->revertOpinion, $data->currentNodeID);

            if($objectType == 'review')
            {
                if($data->toNodeID == 'start') $this->dao->update(TABLE_REVIEW)->set('status')->eq('reverting')->where('id')->eq($objectID)->exec();
                $this->send(array('result' => 'success', 'message'=> $this->lang->saveSuccess, 'locate' => $this->createLink('review', 'view', "reviewID=$objectID")));
            }
            else
            {
                $table = zget($this->config->objectTables, $objectType, '');
                if(!$table) $table = $this->dao->select('table')->from(TABLE_WORKFLOW)->where('module')->eq($objectType)->fetch('table');
                if($data->toNodeID == 'start')
                {
                    if($table)
                    {
                        if($objectType == 'charter')
                        {
                            $beforeStatus = $this->dao->select('status')->from(TABLE_APPROVALOBJECT)->where('objectType')->eq('charter')->andWhere('objectID')->eq($objectID)->andWhere('approval')->ne($approval->id)->andWhere('result')->ne('')->orderBy('approval_desc')->limit(1)->fetch('status');
                            $beforeStatus = $beforeStatus ? $beforeStatus : 'wait';
                            $this->dao->update($table)->set('reviewStatus')->eq($beforeStatus)->where('id')->eq($objectID)->exec();
                        }
                        else
                        {
                            $this->dao->update($table)->set('reviewStatus')->eq('reverting')->where('id')->eq($objectID)->exec();
                        }
                    }
                }
                elseif($table)
                {
                    /* 回退到其他节点，需要更新工作流绑定的审批流。 */
                    $approval = $this->approval->getByObject($objectType, $objectID);
                    $this->dao->update($table)->set('approval')->eq($approval->id)->where('id')->eq($objectID)->exec();
                }

                $this->send(array('result' => 'success', 'message'=> $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
            }

        }

        $doingNode      = $this->dao->select('node,COUNT(1) as count')->from(TABLE_APPROVALNODE)->where('approval')->eq($approval->id)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->groupBy('node')->fetch();
        $nodeGroups     = $this->approval->getNodeOptions(json_decode($approval->nodes));
        $currentNode    = zget($nodeGroups, $doingNode->node);
        $canRevertNodes = $this->loadModel('approval')->getCanRevertNodes($approval->id, $currentNode);

        $this->view->currentNode    = $currentNode;
        $this->view->canRevertNodes = $canRevertNodes;
        $this->display();
    }

    /**
     * 加签。
     * Add node.
     *
     * @param  string  $objectType
     * @param  int     $objectID
     * @access public
     * @return void
     */
    public function addNode($objectType, $objectID)
    {
        $this->loadModel('approvalflow');
        $approval = $this->approval->getByObject($objectType, $objectID);
        if($_POST)
        {
            $reviewerAccount = $this->approval->getReviewerByApprovalID($approval->id);
            if(strpos(',' . implode(',', $reviewerAccount) . ',', ',' . $this->app->user->account . ',') === false) return $this->send(array('result' => 'fail', 'message' => $this->lang->approval->cannotOperate));

            $data = fixer::input('post')
                ->stripTags('addNodeOpinion', $this->config->allowedTags)
                ->get();

            if($data->addNodeMethod != 'current' && !$data->addNodeTitle)
            {
                $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->error->notempty, $this->lang->approval->nodeName)));
            }

            if(!$data->addNodeOpinion)         $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->error->notempty, $this->lang->approval->addNodeOpinion)));
            if(!array_filter($data->reviewer)) $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->error->notempty, $this->lang->approval->reviewer)));

            $approval = $this->approval->getByObject($objectType, $objectID);
            $this->approval->addNode($approval, $data->currentNodeID, $data);
            $this->loadModel('action')->create($objectType, $objectID, 'addnode', $data->addNodeOpinion);

            if($objectType == 'review')
            {
                if($data->addNodeMethod == 'current') $this->sendSuccess(array('message'=> $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
                $this->send(array('result' => 'success', 'message'=> $this->lang->saveSuccess, 'locate' => $this->createLink('review', 'view', "reviewID=$objectID")));
            }
            else
            {
                /* 更新工作流的审批人字段。 */
                $flow = $this->loadModel('workflow')->getByModule($objectType);
                if($data->addNodeMethod == 'prev')
                {
                    $reviewers = implode(',', $data->reviewer);
                    $this->dao->update($flow->table)->set('reviewers')->eq($reviewers)->where('id')->eq($objectID)->exec();
                }
                elseif($data->addNodeMethod == 'current')
                {
                    $reviewers = $this->dao->select('reviewers')->from($flow->table)->where('id')->eq($objectID)->fetch('reviewers');
                    $reviewers = trim($reviewers, ',') . ',' . implode(',', $data->reviewer);
                    $this->dao->update($flow->table)->set('reviewers')->eq($reviewers)->where('id')->eq($objectID)->exec();
                }

                $this->send(array('result' => 'success', 'message'=> $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
            }
        }

        $doingNode   = $this->dao->select('node,COUNT(1) as count')->from(TABLE_APPROVALNODE)->where('approval')->eq($approval->id)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->groupBy('node')->fetch();
        $nodeGroups  = $this->approval->getNodeOptions(json_decode($approval->nodes));
        $currentNode = zget($nodeGroups, $doingNode->node);

        $this->view->currentReviewers = $this->dao->select('account')->from(TABLE_APPROVALNODE)
             ->where('approval')->eq($approval->id)
             ->andWhere('node')->eq($currentNode->id)
             ->andWhere('type')->eq('review')
             ->fetchPairs();

        $this->view->currentNode = $currentNode;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->display();
    }

    /**
     * 转交节点。
     * Forward a node.
     *
     * @param  string  $objectType
     * @param  int     $objectID
     * @access public
     * @return void
     */
    public function forward($objectType, $objectID)
    {
        $approval = $this->approval->getByObject($objectType, $objectID);
        if($_POST)
        {
            $reviewerAccount = $this->approval->getReviewerByApprovalID($approval->id);
            if(strpos(',' . implode(',', $reviewerAccount) . ',', ',' . $this->app->user->account . ',') === false) return $this->send(array('result' => 'fail', 'message' => $this->lang->approval->cannotOperate));

            $data = fixer::input('post')
                ->stripTags('forwardOpinion', $this->config->allowedTags)
                ->get();

            if(!$data->forwardOpinion) $this->send(array('result' => 'fail', 'message' => $this->lang->approval->forwardOpinionRequired));

            $this->approval->forward($approval->id, $data->currentNodeID, $data->forwardTo, $data->forwardOpinion);
            $this->loadModel('action')->create($objectType, $objectID, 'forwarded', $data->forwardOpinion);
            if($objectType == 'review')
            {
                $this->send(array('result' => 'success', 'message'=> $this->lang->saveSuccess, 'locate' => $this->createLink('review', 'view', "reviewID=$objectID")));
            }
            else
            {
                /* 更新工作流的审批人字段。 */
                if($objectType != 'charter')
                {
                    $flow         = $this->loadModel('workflow')->getByModule($objectType);
                    $oldReviewers = $this->dao->select('reviewers')->from($flow->table)->where('id')->eq($objectID)->fetch('reviewers');
                    $reviewers    = str_replace(",{$this->app->user->account},", ",{$data->forwardTo},", ",$oldReviewers,");
                    $reviewers    = trim($reviewers, ',');
                    $this->dao->update($flow->table)->set('reviewers')->eq($reviewers)->where('id')->eq($objectID)->exec();
                }

                $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
            }
        }

        $doingNode      = $this->dao->select('node,COUNT(1) as count')->from(TABLE_APPROVALNODE)->where('approval')->eq($approval->id)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->groupBy('node')->fetch();
        $nodeGroups     = $this->approval->getNodeOptions(json_decode($approval->nodes));
        $currentNode    = zget($nodeGroups, $doingNode->node);
        $canRevertNodes = $this->loadModel('approval')->getCanRevertNodes($approval->id, $currentNode);

        $users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        unset($users[$this->app->user->account]);

        $this->view->users          = $users;
        $this->view->currentNode    = $currentNode;
        $this->view->canRevertNodes = $canRevertNodes;
        $this->display();
    }
}
