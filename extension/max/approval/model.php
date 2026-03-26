<?php
/**
 * The model file of approval module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     approval
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class approvalModel extends model
{
    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('approvalflow');
    }

    /**
     * Get approval by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_APPROVAL)->where('id')->eq($id)->fetch();
    }

    /**
     * Get pending review list.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getPendingReviews($objectType, $objectID = 0)
    {
        $pendingList = $this->dao->select('t2.objectID')->from(TABLE_APPROVALNODE)->alias('t1')
            ->leftJoin(TABLE_APPROVALOBJECT)->alias('t2')
            ->on('t2.approval = t1.approval')
            ->where('t2.objectType')->eq($objectType)
            ->andWhere('t1.account')->eq($this->app->user->account)
            ->andWhere('t1.status')->eq('doing')
            ->andWhere('t1.type')->eq('review')
            ->beginIF($objectID)->andWhere('t2.objectID')->ne($objectID)->fi()
            ->fetchPairs('objectID');

        unset($pendingList['']);

        return $pendingList;
    }

    /**
     * 检查当前用户是否有审批权限。
     * Can approval.
     *
     * @param  object $object
     * @access public
     * @return bool
     */
    public function canApproval($object)
    {
        if(!$object) return false;

        $doingNodeID = $this->dao->select('id')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($object->approval)
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('status')->eq('doing')
            ->andWhere('type')->eq('review')
            ->fetch('id');

        return !empty($doingNodeID);
    }

    /**
     * 检查当前用户是否可以撤回审批。
     * Can approval.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function canCancel($object)
    {
        if(!$object) return false;

        $approval = $this->dao->select('*')->from(TABLE_APPROVAL)->where('id')->eq($object->approval)->fetch();

        if(!$approval) return false;
        if($approval->createdBy != $this->app->user->account) return false;

        /* 如果还没有人审批过，可以撤回。 */
        $hasReviewed = $this->dao->select('id')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($object->approval)
            ->andWhere('status')->eq('done')
            ->andWhere('type')->eq('review')
            ->fetch('id');

        if(!$hasReviewed) return true;

        /* 如果有人审批过，但勾选了评审中撤回，则可以撤回。 */
        $doingNode   = $this->dao->select('node,COUNT(1) as count')->from(TABLE_APPROVALNODE)->where('approval')->eq($approval->id)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->groupBy('node')->fetch();
        $nodeGroups  = $this->getNodeOptions(json_decode($approval->nodes));
        $currentNode = isset($doingNode->node) ? zget($nodeGroups, $doingNode->node, '') : '';

        return ($currentNode && !empty($currentNode->priv)) ? in_array('withdrawn', $currentNode->priv) : false;
    }

    /**
     * Get nodes to confirm.
     *
     * @param  int    $flowID
     * @param  int    $version
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $executionID
     * @param  object $object
     * @access public
     * @return void
     */
    public function getNodesToConfirm($flowID, $version = 0, $projectID = 0, $productID = 0, $executionID = 0, $object = null)
    {
        $flow  = $this->approvalflow->getByID($flowID, $version);
        $nodes = !empty($flow->nodes) ? json_decode($flow->nodes) : array();

        $nodesToConfirm = $this->approvalflow->searchNodesToConfirm($nodes, $projectID, $productID, $executionID, $object);
        return $nodesToConfirm;
    }

    /**
     * Get front node list.
     *
     * @param  string $objectType
     * @param  int    $object
     * @access public
     * @return void
     */
    public function getFrontNodeList($objectType, $object)
    {
        $approvalObject = $this->dao->select('*')->from(TABLE_APPROVALOBJECT)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($object->id)
            ->orderBy('id_desc')
            ->fetch();

        $nodes = $this->dao->select('*')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalObject->approval)
            ->orderBy('id_asc')
            ->fetchGroup('node', 'id');

        /* Group by code|type. */
        foreach($nodes as $code => $nodeGroup)
        {
            foreach($nodeGroup as $id => $node)
            {
                unset($nodes[$code][$node->id]);
                $nodes[$code][$node->type][] = $node;

                if($node->type != 'review') continue;

                $nodes[$code][$node->type]['users'][$node->account]['result'] = $node->result;
                $nodes[$code][$node->type]['users'][$node->account]['status'] = $node->status;
            }
        }

        /* Compute status and result. */
        foreach($nodes as $code => $nodeGroup)
        {
            foreach($nodeGroup as $type => $node)
            {
                if(isset($node['users']))
                {
                    foreach($node['users'] as $account => $resultAndStatus)
                    {
                        if($resultAndStatus['status'] == 'doing')
                        {
                            $nodes[$code][$type]['status'] = 'doing';
                            $nodes[$code][$type]['result'] = '';
                            break;
                        }
                        else
                        {
                            if($resultAndStatus['status'] == 'wait')
                            {
                                $nodes[$code][$type]['status'] = 'wait';
                                $nodes[$code][$type]['result'] = '';
                                break;
                            }
                            elseif($resultAndStatus['result'] == 'fail')
                            {
                                $nodes[$code][$type]['status'] = 'done';
                                $nodes[$code][$type]['result'] = 'fail';
                                break;
                            }
                            else
                            {
                                $nodes[$code][$type]['status'] = 'done';
                                $nodes[$code][$type]['result'] = 'pass';
                            }
                        }
                    }
                }
            }
        }

        return $nodes;
    }

    /**
     * Get approval object by id.
     *
     * @param  int    $approvalID
     * @access public
     * @return void
     */
    public function getApprovalObjectByID($approvalID)
    {
        return $this->dao->select('*')->from(TABLE_APPROVALOBJECT)->where('approval')->eq($approvalID)->fetch();
    }

    /**
     * Get approval id by object id.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getApprovalIDByObjectID($objectID, $objectType = 'review')
    {
        return $this->dao->select('approval')->from(TABLE_APPROVALOBJECT)->alias('t1')
            ->leftJoin(TABLE_APPROVAL)->alias('t2')->on('t1.approval=t2.id')
            ->where('t1.objectType')->eq($objectType)
            ->andWhere('t1.objectID')->eq($objectID)
            ->andWhere('t2.status')->eq('done')
            ->orderBy('t1.id asc')
            ->fetchPairs();
    }

    /**
     * Get last reviewDate.
     *
     * @param  int    $approvalID
     * @access public
     * @return string
     */
    public function getLastReviewDate($approvalID)
    {
        return $this->dao->select('reviewedDate')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('reviewedDate')->notZeroDatetime()
            ->orderBy('reviewedDate_desc')
            ->limit(1)
            ->fetch('reviewedDate');
    }

    /**
     * Get node by id.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function getNodeByID($nodeID)
    {
        return $this->dao->select('*')->from(TABLE_APPROVALNODE)->where('id')->eq($nodeID)->fetch();
    }

    /**
     * Get approval by object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function getByObject($objectType, $objectID)
    {
        return $this->dao->select('*')->from(TABLE_APPROVAL)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->orderBy('id_desc')
            ->fetch();
    }

    /**
     * Get list by object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getListByObject($objectType, $objectID, $orderBy = 'id_desc')
    {
        return $this->dao->select('*')->from(TABLE_APPROVAL)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->orderBy($orderBy)
            ->fetchAll('id', false);
    }

    /**
     * Get node reviewers.
     *
     * @param  int    $approvalID
     * @access public
     * @return void
     */
    public function getNodeReviewers($approvalID)
    {
        $nodeGroups = $this->dao->select('*')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->orderBy('reviewedDate_asc')->fetchGroup('node', 'id');
        $nodeIdList = $this->dao->select('*')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->orderBy('id')->fetchPairs('id', 'id');
        $nodeMap    = array();
        $this->loadModel('file');

        /* Get node files by nodeID .*/
        $nodeFileGroups = array();
        $nodeFiles      = $this->file->getByObject('approvalNode', $nodeIdList);
        foreach($nodeFiles as $nodeFile) $nodeFileGroups[$nodeFile->objectID][$nodeFile->id] = $nodeFile;

        foreach($nodeGroups as $nodeID => $nodes)
        {
            $nodeMap[$nodeID] = array('reviewers' => array(), 'ccs' => array(), 'doing' => array(), 'status' => 'wait', 'result' => '');

            $nodeStatus = 'wait';
            $nodeResult = '';
            $isIgnore   = true;
            $hasPass    = false;
            $passCount  = 0;
            $failCount  = 0;
            $total      = count($nodes);
            foreach($nodes as $node)
            {
                if($node->opinion) $node = $this->file->replaceImgURL($node, 'opinion');
                if($node->result == 'ignore' and $node->status == 'doing') $node->status = 'done';
                if($node->type == 'review') $nodeMap[$nodeID]['reviewers'][$node->id] = array('account' => $node->account, 'status' => $node->status, 'result' => $node->result, 'forwardBy' => $node->forwardBy, 'revertTo' => $node->revertTo, 'reviewedDate' => helper::isZeroDate($node->reviewedDate) ? '' : $node->reviewedDate, 'opinion' => $node->opinion, 'files' => isset($nodeFileGroups[$node->id]) ? $nodeFileGroups[$node->id] : array());
                if($node->type == 'cc')     $nodeMap[$nodeID]['ccs'][] = $node->account;

                if($node->status == 'doing')
                {
                    $isIgnore   = false;
                    $nodeStatus = 'doing';
                    $nodeResult = '';
                    $nodeMap[$node->node]['doing'][] = $node->account;
                }
                elseif($node->status == 'done' and $nodeStatus != 'doing')
                {
                    if($node->result != 'ignore') $isIgnore = false;
                    if($node->result == 'pass')   $hasPass  = true;

                    $nodeStatus = 'done';
                    if(!$isIgnore and $nodeResult != 'fail')     $nodeResult = 'success';
                    if(!$isIgnore and $node->result == 'fail')   $nodeResult = 'fail';
                    if($isIgnore  and $node->result == 'ignore') $nodeResult = 'ignore';
                }
                elseif($node->status == 'reverted')
                {
                    $nodeStatus = 'reverted';
                    $nodeResult = 'reverted';
                }

                if($node->percent > 0 && $node->result == 'pass') $passCount ++;
                if($node->percent > 0 && $node->result == 'fail') $failCount ++;
            }

            /* 或签、需要所有人审批，有一个通过就算通过。 */
            if($node->multipleType == 'or' && $node->needAll == '1' && $hasPass) $nodeResult = 'pass';

            if(($passCount || $failCount) && $total)
            {
                if($passCount / $total * 100 >= $node->percent)
                {
                    $nodeResult = 'pass';
                    $nodeStatus = 'done';
                    $nodeMap[$nodeID]['desc'] = sprintf($this->lang->approvalflow->passOverPercent, $node->percent);
                }

                if(($failCount / $total * 100) + $node->percent > 100)
                {
                    $nodeResult = 'fail';
                    $nodeStatus = 'done';
                    $nodeMap[$nodeID]['desc'] = sprintf($this->lang->approvalflow->failOverPercent, $node->percent);
                }

                if($node->needAll == '1' && $failCount + $passCount != $total)
                {
                    $nodeResult = '';
                    $nodeStatus = 'doing';
                    unset($nodeMap[$nodeID]['desc']);
                }
            }

            $nodeMap[$nodeID]['status'] = $nodeStatus;
            $nodeMap[$nodeID]['result'] = $nodeResult;
        }

        /* Sort reviewers. Set status of done before doing. Set mine to head in the doing list. */
        foreach($nodeMap as $nodeID => $maps)
        {
            if(empty($maps['doing'])) continue;

            $reviewers = $maps['reviewers'];

            $ordered   = array();
            $mine      = array();
            $doings    = array();
            foreach($reviewers as $reviewer)
            {
                if($reviewer['status'] != 'doing')
                {
                    $ordered[] = $reviewer;
                    continue;
                }

                if(empty($mine) and $reviewer['account'] == $this->app->user->account)
                {
                    $mine = $reviewer;
                    continue;
                }

                $doings[] = $reviewer;
            }

            if($mine) $ordered[] = $mine;
            if($doings)
            {
                foreach($doings as $reviewer) $ordered[] = $reviewer;
            }

            $nodeMap[$nodeID]['reviewers'] = $ordered;
        }

        return $nodeMap;
    }

    /**
     * Get current reviewers.
     *
     * @param  int    $approvalID
     * @access public
     * @return array
     */
    public function getCurrentReviewers($approvalID)
    {
        return $this->dao->select('account')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->fetchPairs();
    }

    /**
     * Create approval.
     *
     * @param int      $flowID
     * @param array    $reviewers
     * @param int      $version
     * @param string   $objectType
     * @param int      $objectID
     * @param string   $extra
     * @access private
     * @return void
     */
    private function create($flowID, $reviewers, $version = 0, $objectType = '', $objectID = 0, $extra = '')
    {
        /* If submit a new approval, set old approval status as done. */
        $oldApproval = $this->getByObject($objectType, $objectID);
        $toNodeList  = array();
        if($oldApproval)
        {
            $oldNodes = json_decode($oldApproval->nodes);
            foreach($oldNodes as $oldNode)
            {
                if(isset($oldNode->toNodeID)) $toNodeList[$oldNode->id] = $oldNode->toNodeID;
            }
            $this->dao->update(TABLE_APPROVAL)->set('status')->eq('done')->where('id')->eq($oldApproval->id)->exec();
        }

        $flow  = $this->approvalflow->getByID($flowID, $version);
        $nodes = json_decode($flow->nodes);

        $user    = $this->loadModel('user')->fetchByID($this->app->user->id);
        $upLevel = $this->dao->select('manager')->from(TABLE_DEPT)->where('id')->eq($user->dept)->fetch('manager');

        /* Get users of all roles. */
        $roles = $this->dao->select('id,users')->from(TABLE_APPROVALROLE)->where('deleted')->eq(0)->fetchPairs();
        foreach($roles as $id => $users) $roles[$id] = explode(',', trim($users, ','));

        $userGroupByRole = $this->dao->select('account,role')->from(TABLE_USER)->where('deleted')->eq(0)->fetchGroup('role', 'account');
        $deletedUsers    = $this->dao->select('account')->from(TABLE_USER)->where('deleted')->eq(1)->fetchAll();

        /* Get project/product/execution related roles. */
        $projectRoles = $productRoles = $executionRoles = array();
        $table        = zget($this->config->objectTables, $objectType, '');
        if($table)
        {
            $object = $this->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
            if($objectType == 'review') $object = $this->dao->select('*')->from(TABLE_OBJECT)->where('id')->eq($object->object)->fetch(); // 项目评审的产品存在OBJECT表中
            if($objectType == 'execution') $object->execution = $object->id;
            if($objectType == 'product')   $object->product   = $object->id;
            if($objectType == 'project')   $object->project   = $object->id;
            if(!empty($object->project))
            {
                $project = $this->loadModel('project')->fetchByID($object->project);
                if($project)
                {
                    $stakeholders = $this->dao->select('user')->from(TABLE_STAKEHOLDER)->where('objectType')->eq('project')->andWhere('objectID')->eq($object->project)->andWhere('deleted')->eq('0')->fetchPairs();
                    $projectRoles['PM']          = array($project->PM);
                    $projectRoles['stakeholder'] = array_values($stakeholders);
                }
            }

            if(!empty($object->product))
            {
                if($objectType == 'charter')
                {
                    $products = $this->dao->select('id,PO,QD,RD,feedback,ticket,reviewer')->from(TABLE_PRODUCT)->where('id')->in(trim($object->product, ','))->fetchAll('id');
                    foreach($products as $product)
                    {
                        foreach($this->lang->approvalflow->productRoleList as $role => $roleName)
                        {
                            if(!isset($productRoles[$role])) $productRoles[$role] = array();
                            if($role == 'reviewer')
                            {
                                $productRoles['reviewer'] = array_merge($productRoles['reviewer'], array_filter(explode(',', $product->reviewer)));
                            }
                            else
                            {
                                $productRoles[$role] = array_merge($productRoles[$role], array($product->{$role}));
                            }

                            $productRoles[$role] = array_unique($productRoles[$role]);
                        }
                    }
                }
                else
                {
                    $product = $this->loadModel('product')->fetchByID($object->product);
                    if($product)
                    {
                        foreach($this->lang->approvalflow->productRoleList as $role => $roleName)
                        {
                            if($role == 'reviewer')
                            {
                                $productRoles['reviewer'] = array_filter(explode(',', $product->reviewer));
                            }
                            else
                            {
                                $productRoles[$role] = array($product->{$role});
                            }
                        }
                    }
                }
            }

            if(!empty($object->execution))
            {
                $execution = $this->loadModel('execution')->fetchByID($object->execution);
                if($execution)
                {
                    foreach($this->lang->approvalflow->executionRoleList as $role => $roleName)
                    {
                        $executionRoles[$role] = array($execution->{$role});
                    }
                }
            }
        }
        else
        {
            $table = $this->dao->select('table')->from(TABLE_WORKFLOW)->where('module')->eq($objectType)->fetch('table');
            if($table) $object = $this->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
        }

        /* Generate nodes. */
        $this->approvalflow->genNodes($nodes, array('reviewers' => $reviewers, 'upLevel' => $upLevel, 'superior' => $user->superior, 'roles' => $roles, 'projectRoles' => $projectRoles, 'productRoles' => $productRoles, 'executionRoles' => $executionRoles, 'userGroupByRole' => $userGroupByRole, 'deletedUsers' => $deletedUsers), $toNodeList, $object);

        if(dao::isError()) return false;

        /* Insert nodes. */
        $approval = new stdclass();
        $approval->flow        = $flowID;
        $approval->objectType  = $objectType;
        $approval->objectID    = $objectID;
        $approval->version     = $flow->version;
        $approval->createdBy   = $this->app->user->account;
        $approval->createdDate = helper::now();
        $approval->nodes       = json_encode($nodes);
        $approval->extra       = $extra;

        $this->dao->insert(TABLE_APPROVAL)->data($approval)->exec();
        $approvalID = $this->dao->lastInsertID();

        $this->insertNodes($approvalID, $nodes, array(), $extra);

        return $approvalID;
    }

    /**
     * Create approval object for a object.
     *
     * @param  int    $root
     * @param  int    $objectID
     * @param  string $objectType
     * @param  array  $reviewers
     * @param  array  $ccers
     * @param  array  $nodeIdList
     * @param  string $type
     * @access public
     * @return string If finished, return pass|fail.
     */
    public function createApprovalObject($root = 0, $objectID = 0, $objectType = 0, $reviewers = array(), $ccers = array(), $nodeIdList = array(), $type = '')
    {
        /* Create approval. */
        $extra = '';
        if($objectType == 'review')
        {
            $approvalflowObject = $this->loadModel('approvalflow')->getFlowObject($root, $type);
            $flowID = $approvalflowObject ? $approvalflowObject->flow : $this->dao->select('*')->from(TABLE_APPROVALFLOW)->where('code')->eq('simple')->fetch()->id;
        }
        elseif($objectType == 'charter')
        {
            $charter = $this->loadModel('charter')->getByID($objectID);
            $extra   = $this->charter->getApprovalFlowExtra($charter);

            $approvalflowObject = $this->loadModel('approvalflow')->getFlowObject($root, $objectType, $extra);
            if(!$approvalflowObject) return false;
            $flowID = $approvalflowObject->flow;
        }
        else
        {
            $approvalflowObject = $this->loadModel('approvalflow')->getFlowObject($root, $objectType);
            if(!$approvalflowObject) return false;
            $flowID = $approvalflowObject->flow;
        }

        $approvalUsers = array();
        foreach($nodeIdList as $id)
        {
            $approvalUsers[$id] = array('reviewers' => array(), 'ccs' => array());

            if(isset($reviewers[$id]))
            {
                foreach($reviewers[$id] as $reviewer)
                {
                    if($reviewer) $approvalUsers[$id]['reviewers'][] = $reviewer;
                }
            }

            if(isset($ccers[$id]))
            {
                foreach($ccers[$id] as $ccer)
                {
                    if($ccer) $approvalUsers[$id]['ccs'][] = $ccer;
                }
            }
        }

        $approvalID = $this->create($flowID, $approvalUsers, 0, $objectType, $objectID, $extra);
        if(dao::isError()) return false;

        $data = new stdclass();
        $data->approval   = $approvalID;
        $data->objectType = $objectType;
        $data->objectID   = $objectID;
        $this->dao->insert(TABLE_APPROVALOBJECT)->data($data)->exec();

        /* Run approval. */
        $this->next($approvalID, '', 'submit');

        /* If the flow is finished, change status of approval. */
        $doing = $this->dao->select('id')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('status')->eq('doing')
            ->fetchAll();

        if(empty($doing))
        {
            $reject = $this->dao->select('id')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('result')->eq('fail')->fetchAll();

            $result = empty($reject) ? 'pass' : 'fail';
            $this->finish($approvalID, $result, 'submit');

            return array('result' => $result, 'approvalID' => $approvalID);
        }

        return array('result' => '', 'approvalID' => $approvalID);
    }

    /**
     * Insert node.
     *
     * @param int      $approvalID
     * @param object   $node
     * @param array    $prevs
     * @param string   $extra
     * @access private
     * @return void
     */
    private function insertNode($approvalID, $node, $prevs, $extra = '')
    {
        $newPrev = '';
        if(isset($node->reviewers) and !empty($node->reviewers))
        {
            foreach($node->reviewers as $reviewer)
            {
                foreach($reviewer->users as $user)
                {

                    $data = new stdclass();
                    $data->approval     = $approvalID;
                    $data->node         = $node->id;
                    $data->title        = $node->title;
                    $data->type         = 'review';
                    $data->prev         = implode(',', $prevs);
                    $data->account      = $user;
                    $data->multipleType = in_array($node->multiple, array('percent', 'solicit')) ? 'and' : $node->multiple;
                    $data->percent      = $node->multiple == 'percent' ? $node->percent : 0;
                    $data->needAll      = !empty($node->needAll) ? '1' : '0';
                    $data->reviewType   = isset($node->reviewType) ? $node->reviewType : 'manual';
                    $data->agentType    = isset($node->agentType)  ? $node->agentType  : 'pass';
                    $data->solicit      = $node->multiple == 'solicit' ? '1' : '0';
                    $data->extra        = $extra;

                    /* 回退后重新发起的情况, 直达回退节点。 */
                    if(isset($node->toNodeID))
                    {
                        if($node->toNodeID != $data->node)
                        {
                            $data->status = 'done';
                            $data->result = 'ignore';
                        }
                        else
                        {
                            $data->status = 'doing';
                        }
                    }

                    /* 连续上级审批一定是会签的。 */
                    if(isset($reviewer->type) && $reviewer->type == 'superiorList')
                    {
                        $data->multipleType = 'and';
                        $data->percent      = '0';
                    }

                    $this->dao->insert(TABLE_APPROVALNODE)->data($data)->exec();
                    $newPrev = $node->id;
                }
            }
        }

        if(isset($node->ccs) and !empty($node->ccs))
        {
            foreach($node->ccs as $cc)
            {
                foreach($cc->users as $user)
                {
                    $data = new stdclass();
                    $data->approval     = $approvalID;
                    $data->node         = $node->id;
                    $data->title        = $node->title;
                    $data->type         = 'cc';
                    $data->prev         = implode(',', $prevs);
                    $data->account      = $user;
                    $data->multipleType = $node->multiple;
                    $data->reviewType   = isset($node->reviewType) ? $node->reviewType : 'manual';
                    $data->extra        = $extra;

                    /* 回退后重新发起的情况, 直达回退节点。 */
                    if(isset($node->toNodeID))
                    {
                        if($node->toNodeID != $data->node)
                        {
                            $data->status = 'done';
                            $data->result = 'ignore';
                        }
                        else
                        {
                            $data->status = 'wait';
                        }
                    }

                    $this->dao->insert(TABLE_APPROVALNODE)->data($data)->exec();
                    $newPrev = $node->id;
                }
            }
        }

        return $newPrev;
    }

    /**
     * Insert nodes.
     *
     * @param  int    $approvalID
     * @param  array  $nodes
     * @param  array  $prevs
     * @param  string $extra
     * @access public
     * @return array
     */
    public function insertNodes($approvalID, $nodes, $prevs, $extra = '')
    {
        foreach($nodes as $node)
        {
            if($node->type == 'branch')
            {
                $nextPrevs = array();
                foreach($node->branches as $branch)
                {
                    $newPrevs = $this->insertNodes($approvalID, $branch->nodes, $prevs, $extra);
                    foreach($newPrevs as $newPrev)
                    {
                        if($newPrev) $nextPrevs[] = $newPrev;
                    }
                }
                if(!empty($nextPrevs)) $prevs = $nextPrevs;
            }
            else
            {
                $newPrev = $this->insertNode($approvalID, $node, $prevs, $extra);
                if($newPrev) $prevs = array($newPrev);
            }
        }

        return $prevs;
    }

    /**
     * Next viewer node.
     *
     * @param int    $approvalID
     * @param string $prev
     * @access private
     * @return void
     */
    private function next($approvalID, $prev, $action = 'submit')
    {
        /* Get next nodes. */
        if(!$prev)
        {
            $nodes = $this->dao->select('prev,node,account,type,reviewType,agentType,status,result')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('prev')->eq('')->orderBy('id_asc')->fetchAll();
        }
        else
        {
            $nodes = $this->dao->select('prev,node,account,type,reviewType,agentType,status,result')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('prev')->like("%$prev%")->orderBy('id_asc')->fetchAll();
        }
        if(empty($nodes)) return;

        $nodeMap = array();
        foreach($nodes as $index => $node)
        {
            if($prev)
            {
                $undone = $this->dao->select('id')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('node')->in($node->prev)->andWhere('status')->notin('done,reverted,forward')->fetchAll();
                if(!empty($undone)) continue;   // Only all prevs are done, the node can flow.
            }

            if(!isset($nodeMap[$node->node])) $nodeMap[$node->node] = array('review' => array(), 'cc' => array());
            if($node->account) $nodeMap[$node->node][$node->type][] = $node->account; //如果节点的审批人为空，则直接跳过该节点。
            $nodeMap[$node->node]['reviewType'] = $node->reviewType;
            $nodeMap[$node->node]['agentType']  = $node->agentType;
            $nodeMap[$node->node]['status']     = $node->status;
            $nodeMap[$node->node]['result']     = $node->result;
        }

        foreach($nodeMap as $nodeID => $users)
        {
            if(empty($users['review']))
            {
                /* 审批人为空自动拒绝。 */
                if($users['agentType'] == 'reject')
                {
                    $this->dao->update(TABLE_APPROVALNODE)
                         ->set('status')->eq('done')
                         ->set('result')->eq('fail')
                         ->where('approval')->eq($approvalID)
                         ->andWhere('node')->eq($nodeID)
                         ->andWhere('account')->eq('')
                         ->exec();

                    $approval = $this->getByID($approvalID);
                    $table    = zget($this->config->objectTables, $approval->objectType, '');
                    if($table)
                    {
                        $object = $this->dao->select('*')->from($table)->where('id')->eq($approval->objectID)->fetch();
                        return $this->reject($approval->objectType, $object);
                    }
                }

                $this->cc($approvalID, $nodeID, $users['cc'], $action);
                $this->next($approvalID, $nodeID, $action);
            }
            /* 下一节点审批结果是ignore，跳到下下节点。 */
            else if($users['status'] == 'done' && $users['result'] == 'ignore')
            {
                $this->cc($approvalID, $nodeID, $users['cc'], $action);
                $this->next($approvalID, $nodeID, $action);
                break;
            }
            else if($users['reviewType'] == 'pass') // Auto pass
            {
                $this->dao->update(TABLE_APPROVALNODE)
                    ->set('status')->eq('done')
                    ->set('result')->eq('pass')
                    ->where('approval')->eq($approvalID)
                    ->andWhere('node')->eq($nodeID)
                    ->exec();
                $this->cc($approvalID, $nodeID, $users['cc'], $action);
                $this->next($approvalID, $nodeID, 'review');
            }
            else if($users['reviewType'] == 'reject') // Auto reject
            {
                $this->dao->update(TABLE_APPROVALNODE)
                    ->set('status')->eq('done')
                    ->set('result')->eq('fail')
                    ->where('approval')->eq($approvalID)
                    ->andWhere('node')->eq($nodeID)
                    ->andWhere('type')->eq('review')
                    ->exec();
                $this->dao->update(TABLE_APPROVALNODE)
                    ->set('status')->eq('done')
                    ->set('result')->eq('ignore')
                    ->where('approval')->eq($approvalID)
                    ->andWhere('status')->eq('wait')
                    ->exec();

                $approval = $this->getByID($approvalID);
                $this->sendMessage(array($approval->createdBy), $approvalID, $nodeID, 'review');
            }
            else
            {
                $this->review($approvalID, $nodeID, $users['review'], $action);
                if(!empty($users['cc'])) $this->sendMessage($users['cc'], $approvalID, $nodeID, $action, true);
            }
        }
    }

    /**
     * review
     *
     * @param int      $approvalID
     * @param string   $nodeID
     * @param array    $reviewers
     * @param string   $action
     * @access private
     * @return void
     */
    private function review($approvalID, $nodeID, $reviewers, $action)
    {
        $this->dao->update(TABLE_APPROVALNODE)
             ->set('status')->eq('doing')
             ->where('approval')->eq($approvalID)
             ->andWhere('node')->eq($nodeID)
             ->andWhere('result')->eq('')
             ->exec();

        $reviewers = $this->setReviewerByRule($approvalID, $nodeID, $reviewers, $action);
        $this->sendMessage($reviewers, $approvalID, $nodeID, $action);
    }

    /**
     * cc
     *
     * @param int    $approvalID
     * @param string $nodeID
     * @param array  $ccs
     * @access private
     * @return void
     */
    private function cc($approvalID, $nodeID, $ccs, $action)
    {
        $this->dao->update(TABLE_APPROVALNODE)->set('status')->eq('done')->where('approval')->eq($approvalID)->andWhere('node')->eq($nodeID)->exec();
        $this->sendMessage($ccs, $approvalID, $nodeID, $action, true);
    }

    /**
     * 回退到某个审批节点。
     * Revert to a node.
     *
     * @param  int    $approvalID
     * @param  string $nodeID
     * @param  string $toNodeID
     * @param  string $revertType
     * @access public
     * @return array
     */
    public function revert($approvalID, $nodeID, $toNodeID, $revertType = 'order')
    {
        // 获取当前审批流程信息
        $approval = $this->getByID($approvalID);

        // 如果回退到开始节点，新建相同流程
        if($toNodeID == 'start')
        {
            if($revertType == 'revert')
            {
                $nodes = json_decode($approval->nodes);
                $nodes = $this->getRevertStartNodes($nodes, $nodeID);
                $nodes = json_encode($nodes);
                $this->dao->update(TABLE_APPROVAL)->set('nodes')->eq($nodes)->where('id')->eq($approvalID)->exec();
            }
            $this->sendMessage(array($approval->createdBy), $approvalID, $nodeID, 'revert');
        }
        elseif($toNodeID != 'start')
        {
            /* 回退到其他节点的情况。 */
            $oldNodeGroup = $this->dao->select('*')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->fetchGroup('node', 'id');

            $approval->version += 1;
            $approval->status   = 'doing';
            $this->dao->insert(TABLE_APPROVAL)->data($approval, 'id')->exec();
            $newApprovalID = $this->dao->lastInsertID();

            /* 插入approvalobject表。*/
            $approvalObject = $this->dao->select('*')->from(TABLE_APPROVALOBJECT)->where('approval')->eq($approvalID)->fetch();
            $approvalObject->approval    = $newApprovalID;
            $approvalObject->appliedBy   = '';
            $approvalObject->appliedDate = null;
            $this->dao->insert(TABLE_APPROVALOBJECT)->data($approvalObject, 'id')->exec();

            $fromNode = $this->dao->select('*')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('node')->eq($nodeID)->fetch();
            $toNode   = $this->dao->select('*')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('node')->eq($toNodeID)->fetch();

            $reviewers = array();
            if($revertType == 'revert')
            {
                foreach($oldNodeGroup as $id => $nodes)
                {
                    foreach($nodes as $node)
                    {
                        if($node->prev == $nodeID) break;

                        /* 如果回退到并行分支，则所有并行的分支都要激活。 */
                        if($id == $toNodeID || $node->prev == $toNode->prev)
                        {
                            $node->status = 'doing';
                            $node->result = '';
                            $reviewers[]  = $node->account;
                        }
                        elseif($id == $nodeID || $node->prev == $fromNode->prev)
                        {
                            $node->status = 'wait';
                            $node->result = '';
                        }
                        else
                        {
                            $node->status = 'done';
                            $node->result = 'ignore';
                        }
                    }
                }
            }
            else
            {
                foreach($oldNodeGroup as $id => $nodes)
                {
                    foreach($nodes as $node)
                    {
                        if($node->prev == $nodeID) break;

                        /* 如果回退到并行分支，则所有并行的分支都要激活。 */
                        if($id == $toNodeID || $node->prev == $toNode->prev)
                        {
                            $node->status = 'doing';
                            $node->result = '';
                            $reviewers[]  = $node->account;
                        }
                        else
                        {
                            $node->status = 'wait';
                            $node->result = '';
                        }
                    }
                }
            }

            foreach($oldNodeGroup as $nodeID => $nodes)
            {
                foreach($nodes as $node)
                {
                    if(!$node->date)         $node->date         = null;
                    if(!$node->reviewedDate) $node->reviewedDate = null;
                    $node->approval = $newApprovalID;
                    $this->dao->insert(TABLE_APPROVALNODE)->data($node, 'id')->exec();
                }
            }

            $this->sendMessage($reviewers, $approvalID, $nodeID, 'revert');
        }

        /* 把百分比通过置空，否则会展示百分比结果。*/
        $this->dao->update(TABLE_APPROVALNODE)
            ->set('percent')->eq('0')
            ->where('approval')->eq($approvalID)
            ->exec();

        // 更新旧的审批流程状态为 'revert'
        $this->dao->update(TABLE_APPROVALNODE)
             ->set('status')->eq('reverted')
             ->set('result')->eq('reverted')
             ->set('reviewedDate')->eq(helper::now())
             ->set('revertTo')->eq($toNodeID)
             ->where('approval')->eq($approvalID)
             ->andWhere('account')->eq($this->app->user->account)
             ->andWhere('status')->eq('doing')
             ->exec();

        // 废弃当前流程
        $this->dao->update(TABLE_APPROVALNODE)
            ->set('status')->eq('done')
            ->set('result')->eq('ignore')
            ->where('approval')->eq($approvalID)
            ->andWhere('status')->notin('done,reverted')
            ->exec();

    }

    /**
     * 递归处理回退到开始节点的情况。
     * Get revert start nodes.
     *
     * @param  array  $nodes
     * @param  string $nodeID
     * @access public
     * @return array
     */
    public function getRevertStartNodes($nodes, $nodeID)
    {
        foreach($nodes as $node)
        {
            if($node->type == 'branch' and isset($node->branches))
            {
                foreach($node->branches as $childNode)
                {
                    $this->getRevertStartNodes($childNode->nodes, $nodeID);
                }
            }

            $node->toNodeID = $nodeID;
            if($node->id == $nodeID) return $nodes;
        }

        return $nodes;
    }

    /**
     * Add node to approval.
     *
     * @param  object $approval
     * @param  string $currentNodeID
     * @param  object $data
     * @access public
     * @return void
     */
    public function addNode($approval, $currentNodeID, $data)
    {
        $currentNode = $this->dao->select('*')->from(TABLE_APPROVALNODE)
            ->where('node')->eq($currentNodeID)
            ->andWhere('approval')->eq($approval->id)
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('status')->eq('doing')
            ->fetch();

        $randomString = bin2hex(random_bytes(ceil(5)));
        $newNodeID    = substr($randomString, 0, 10);

        $users = $this->loadModel('user')->getPairs('noclosed|noletter');
        $nodes = json_decode($approval->nodes, true);
        $nodes = $this->addNodeToApproval($nodes, $data, $currentNode, $newNodeID, $users);
        $nodes = json_encode($nodes);
        $this->dao->update(TABLE_APPROVAL)->set('nodes')->eq($nodes)->where('id')->eq($approval->id)->exec();

        if($data->addNodeMethod != 'current')
        {
            if($data->addNodeMethod == 'next')
            {
                $currentNode->prev = $currentNode->node;
                $this->dao->update(TABLE_APPROVALNODE)
                    ->set('prev')->eq($newNodeID)
                    ->where('approval')->eq($approval->id)
                    ->andWhere('prev')->eq($currentNode->node)
                    ->exec();

                $currentNode->status = 'wait';
            }
            elseif($data->addNodeMethod == 'prev')
            {
                $this->dao->update(TABLE_APPROVALNODE)
                    ->set('prev')->eq($newNodeID)
                    ->where('approval')->eq($approval->id)
                    ->andWhere('node')->eq($currentNode->node)
                    ->exec();

                $this->dao->update(TABLE_APPROVALNODE)
                    ->set('status')->eq('wait')
                    ->where('approval')->eq($approval->id)
                    ->andWhere('node')->eq($currentNode->node)
                    ->andWhere('status')->eq('doing')
                    ->exec();
            }

            $currentNode->node         = $newNodeID;
            $currentNode->title        = $data->addNodeTitle;
            $currentNode->multipleType = in_array($data->multiple, array('percent', 'solicit')) ? 'and' : $data->multiple;
            $currentNode->percent      = $data->multiple == 'percent' ? $data->percent : 0;
            $currentNode->needAll      = !empty($data->needAll) ? '1' : '0';
            $currentNode->solicit      = $data->multiple == 'solicit' ? '1' : '0';
            $currentNode->revertTo     = '';
            $currentNode->forwardBy    = '';
            $currentNode->extra        = '';
            $currentNode->reviewedDate = null;
        }

        foreach($data->reviewer as $account)
        {
            $currentNode->account      = $account;
            $currentNode->extra        = $data->addNodeOpinion;
            $currentNode->date         = helper::now();
            $currentNode->reviewedDate = $currentNode->reviewedDate ? $currentNode->reviewedDate : null;
            $this->dao->insert(TABLE_APPROVALNODE)->data($currentNode, 'id')->exec();
        }
    }

    /**
     * 递归处理添加节点的情况。
     * Add node to approval.
     *
     * @param  array  $nodes
     * @param  object $data
     * @param  object $currentNode
     * @param  string $newNodeID
     * @access public
     * @return array
     * @param mixed[] $users
     */
    public function addNodeToApproval($nodes, $data, $currentNode, $newNodeID, $users)
    {
        foreach($nodes as $index => $node)
        {
            if($node['type'] == 'branch' and isset($node['branches']))
            {
                foreach($node['branches'] as $childNode)
                {
                    $this->addNodeToApproval($childNode->nodes, $data, $currentNode, $newNodeID, $users);
                }
            }

            if($node['id'] == $currentNode->node)
            {
                $newNode = $node;
                $newNode['id']          = $newNodeID;
                $newNode['title']       = $data->addNodeTitle;
                $newNode['multiple']    = $data->multiple;
                $newNode['percent']     = $data->percent;
                $newNode['addNodeDesc'] = array();

                $reviewer = '';
                foreach($data->reviewer as $account) $reviewer .= '<strong>' . zget($users, $account) . '</strong>,';
                $reviewer = trim($reviewer, ',');

                if($data->addNodeMethod == 'next')
                {
                    $nodes[$index]['addNodeDesc'][] = str_replace(array('$actor', '$reviewer', '$date'), array(zget($users, $this->app->user->account), $reviewer, helper::now()), $this->lang->approval->reviewDesc->addNext) . "<div class='opinion'>{$data->addNodeOpinion}</div>";
                    array_splice($nodes, $index + 1, 0, array($newNode));
                }
                elseif($data->addNodeMethod == 'prev')
                {
                    $nodes[$index]['addNodeDesc'][] = str_replace(array('$actor', '$reviewer', '$date'), array(zget($users, $this->app->user->account), $reviewer, helper::now()), $this->lang->approval->reviewDesc->addPrev) . "<div class='opinion'>{$data->addNodeOpinion}</div>";
                    array_splice($nodes, $index, 0, array($newNode));
                }
                elseif($data->addNodeMethod == 'current')
                {
                    $nodes[$index]['addNodeDesc'][] = str_replace(array('$actor', '$reviewer', '$date'), array(zget($users, $this->app->user->account), $reviewer, helper::now()), $this->lang->approval->reviewDesc->addCurrent) . "<div class='opinion'>{$data->addNodeOpinion}</div>";
                }

                return $nodes;
            }
        }

        return $nodes;
    }

    /**
     * 获取一个审批流程中可以回退到的节点。
     * Get can revert nodes.
     *
     * @param  int    $approvalID
     * @param  object $currentNode
     * @access public
     * @return array
     */
    public function getCanRevertNodes($approvalID, $currentNode)
    {
        $currentNodeID = $this->dao->select('id')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('node')->eq($currentNode->id)
            ->orderBy('id_asc')
            ->fetch('id');

        $nodes = $this->dao->select('node, title')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('id')->lt($currentNodeID)
            ->andWhere('prev')->ne($currentNode->id)
            ->fetchPairs();

        return arrayUnion(array('start' => $this->lang->approval->startNode), $nodes);
    }

    /**
     * 转交审批。
     * Forward approval node.
     *
     * @param  int    $approvalID
     * @param  string $nodeID
     * @param  string $forwardTo
     * @param  string $forwardOpinion
     * @access public
     * @return void
     */
    public function forward($approvalID, $nodeID, $forwardTo, $forwardOpinion)
    {
        $currentNode = $this->dao->select('*')->from(TABLE_APPROVALNODE)
            ->where('node')->eq($nodeID)
            ->andWhere('approval')->eq($approvalID)
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('status')->eq('doing')
            ->fetch();

        $currentNode->account      = $forwardTo;
        $currentNode->forwardBy    = $this->app->user->account;
        $currentNode->extra        = $forwardOpinion;
        $currentNode->date         = helper::now();
        $currentNode->reviewedDate = null;
        $this->dao->insert(TABLE_APPROVALNODE)->data($currentNode, 'id')->exec();

        $this->dao->update(TABLE_APPROVALNODE)
             ->set('status')->eq('forward')
             ->set('result')->eq('forward')
             ->set('reviewedDate')->eq(helper::now())
             ->where('id')->eq($currentNode->id)
             ->exec();

        $this->sendMessage(array($forwardTo), $currentNode->approval, $currentNode->node, 'forward');
    }

    /**
     * Send message.
     *
     * @param  array  $sendList
     * @param  int    $approvalID
     * @param  int    $nodeID
     * @param  string $method
     * @param  string $isCC
     * @access private
     * @return void
     */
    private function sendMessage($sendList, $approvalID, $nodeID, $method, $isCC = false)
    {
        $approval = $this->dao->select('objectType, result, objectID')->from(TABLE_APPROVAL)->where('id')->eq($approvalID)->fetch();
        $node     = $this->dao->select('*')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('node')->eq($nodeID)->fetch();

        $objectType = $approval->objectType;
        $objectID   = $approval->objectID;

        if(empty($objectID) || empty($objectType) || $objectType == 'deploy') return;

        $this->loadModel('mail');
        $this->loadModel('message');
        $this->loadModel('action');

        /* Message config. */
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);

        $suffix = '';
        $flow   = $this->dao->select('name, `table`')->from(TABLE_WORKFLOW)->where('module')->eq($objectType)->fetch();
        if($flow)
        {
            $object = $this->dao->select('*')->from("`$flow->table`")->where('id')->eq($objectID)->fetch();

            $objectNameFields = $this->dao->select('field')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($objectType)->andWhere('field')->in('name,title')->fetchPairs('field', 'field');
            if(isset($objectNameFields['title'])) $this->config->action->objectNameFields[$objectType] = 'title';
            if(isset($objectNameFields['name']))  $this->config->action->objectNameFields[$objectType] = 'name';
            if(!isset($this->config->action->objectNameFields[$objectType])) $this->config->action->objectNameFields[$objectType] = 'id';
        }
        else
        {
            $model  = zget($this->config->approval->objectModels, $objectType);
            $object = $this->loadModel($model)->getByID($objectID);
            if($objectType == 'review') $suffix = empty($object->project) ? '' : ' - ' . $this->loadModel('project')->getById($object->project)->name;
        }

        if(isset($messageSetting['message']['setting']))
        {
            $actions     = zget($messageSetting['message'], 'setting', array());
            $isFlow      = $this->app->moduleName == 'flow' and isset($actions[$objectType]) and in_array($method, $actions[$objectType]);
            $isWaterfall = $this->app->moduleName != 'flow' and isset($actions['waterfall']) and in_array($method, $actions['waterfall']);
            if($isFlow || $isWaterfall)
            {
                if(!$node) $node = new stdclass();
                if(!isset($node->node)) $node->node = '';
                $this->saveNotice($sendList, $objectType, $objectID, $object, $method == 'cancel' ? 'cancel' : $node->node, $isCC);
            }
        }

        if(isset($messageSetting['webhook']['setting']))
        {
            $actions     = zget($messageSetting['webhook'], 'setting', array());
            $isFlow      = $this->app->moduleName == 'flow' && isset($actions[$objectType]) && in_array($method, $actions[$objectType]);
            $isWaterfall = $this->app->moduleName != 'flow' && isset($actions['waterfall']) && in_array($method, $actions['waterfall']);
            if($isFlow || $isWaterfall)
            {
                $this->sendWebHook($sendList, $objectType, $objectID, $object, $method);
            }
        }

        if(isset($messageSetting['sms']['setting']))
        {
            $actions     = zget($messageSetting['sms'], 'setting', array());
            $isFlow      = $this->app->moduleName == 'flow' && isset($actions[$objectType]) && in_array($method, $actions[$objectType]);
            $isWaterfall = $this->app->moduleName != 'flow' && isset($actions['waterfall']) && in_array($method, $actions['waterfall']);
            if($isFlow || $isWaterfall)
            {
                $this->sendSMS($sendList, $objectType, $objectID, $object);
            }
        }

        if(isset($messageSetting['xuanxuan']['setting']))
        {
            $actions     = zget($messageSetting['xuanxuan'], 'setting', array());
            $isFlow      = $this->app->moduleName == 'flow' && isset($actions[$objectType]) && in_array($method, $actions[$objectType]);
            $isWaterfall = $this->app->moduleName != 'flow' && isset($actions['waterfall']) && in_array($method, $actions['waterfall']);
            if($isFlow || $isWaterfall)
            {
                $this->sendXuanxuan($sendList, $objectType, $objectID, $object, $method);
            }
        }

        if(isset($messageSetting['mail']['setting']))
        {
            $actions = zget($messageSetting['mail'], 'setting', array());
            if($this->app->moduleName == 'flow' and !isset($actions[$objectType])) return false;
            if($this->app->moduleName != 'flow' and !isset($actions['waterfall'])) return false;

            if($this->app->moduleName == 'flow' && !in_array($method, $actions[$objectType])) return false;
            if($this->app->moduleName != 'flow' && !in_array($method, $actions['waterfall'])) return false;
        }

        /* Load module and get vars. */
        $this->loadModel('action');
        $users      = $this->loadModel('user')->getPairs('noletter');
        $actions    = $this->action->getList($objectType, $objectID);
        $nameFields = $this->config->action->objectNameFields[$objectType];
        $title      = zget($object, $nameFields, '');
        $subject    = strtoupper($objectType) . ' #' . $object->id . ($approval->objectType == 'workflow' ? ' - ' . $flow->name : ' ' . $title . $suffix);
        $domain     = zget($this->config->mail, 'domain', common::getSysURL());

        foreach($actions as $action)
        {
            $action->appendLink = '';
            if(strpos($action->extra, ':') !== false)
            {
                list($extra, $id) = explode(':', $action->extra);
                $action->extra    = $extra;
                if($title) $action->appendLink = html::a($domain . helper::createLink($action->objectType, 'view', "id=$id", 'html'), "#$id " . $title);
            }
        }

        if(is_array($sendList)) $sendList = implode(',', $sendList);

        /* Get mail content. */
        $modulePath = $this->app->getModulePath($appName = '', 'approval');
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

        $this->mail->send($sendList, $subject, $mailContent);

        if($this->mail->isError()) error_log(join("\n", $this->mail->getError()));
    }

    /**
     * Send notice.
     *
     * @param  array  $sendList
     * @param  string $objectType
     * @param  int    $objectID
     * @param  object $object
     * @param  string $node
     * @param  bool   $isCC
     * @access public
     * @return void
     */
    public function saveNotice($sendList, $objectType, $objectID, $object, $node, $isCC)
    {
        $this->loadModel('action');
        $actor      = $this->app->user->account;
        $user       = $this->loadModel('user')->getById($actor);
        $nameFields = $this->config->action->objectNameFields[$objectType];
        $title      = zget($object, $nameFields, '');
        $url        = helper::createLink($objectType, 'view', "id=$objectID");

        if($node == 'start')
        {
            $data = $user->realname . $this->lang->approval->start . ':' . html::a($url, "[#{$objectID}::{$title}]");
        }
        else if($node == 'end')
        {
            $data = $this->lang->approval->end . ':' . html::a($url, "[#{$objectID}::{$title}]");
        }
        else if($node == 'cancel')
        {
            $data = $user->realname . $this->lang->approval->cancel . ':' . html::a($url, "[#{$objectID}::{$title}]");
        }
        else
        {
            $data = $this->lang->action->objectTypes[$objectType] . $this->lang->approval->common . ':' . html::a($url, "[#{$objectID}::{$title}]");
        }

        if($isCC) $data = "[{$this->lang->approval->cc}]" . $data;

        $notify = new stdclass();
        $notify->objectType  = 'message';
        $notify->action      = '0';
        $notify->data        = $data;
        $notify->status      = 'wait';
        $notify->createdBy   = $this->app->user->account;
        $notify->createdDate = helper::now();
        foreach($sendList as $account)
        {
            if($account == $actor) continue;

            $notify->toList = ",{$account},";
            $this->dao->insert(TABLE_NOTIFY)->data($notify)->exec();
        }
    }

    /**
     * Send sms.
     *
     * @param  array  $sendList
     * @param  string $objectType
     * @param  int    $objectID
     * @param  object $object
     * @access public
     * @return void
     */
    public function sendSMS($sendList, $objectType, $objectID, $object)
    {
        $accounts  = $this->dao->select('mobile')->from(TABLE_USER)->where('account')->in($sendList)->andWhere('deleted')->eq(0)->fetchAll();
        $mobiles   = array();
        $delimiter = isset($this->app->config->sms->delimiter) ? $this->app->config->sms->delimiter : ',';
        foreach($accounts as $account)
        {
            if($account->mobile) $mobiles[$account->mobile] = $account->mobile;
        }

        $nameFields = $this->config->action->objectNameFields[$objectType];
        $mobiles    = join($delimiter, $mobiles);
        $content    = zget($object, $nameFields, '');
        $this->loadModel('sms')->sendContent($mobiles, $content);
    }

    /**
     * Send webhook.
     *
     * @param  array  $sendList
     * @param  string $objectType
     * @param  int    $objectID
     * @param  object $object
     * @param  string $method
     * @access public
     * @return void
     */
    public function sendWebHook($sendList, $objectType, $objectID, $object, $method = '')
    {
        static $webhooks = array();
        $this->loadModel('webhook');
        if(!$webhooks) $webhooks = $this->webhook->getList();
        if(!$webhooks) return true;

        static $users = array();
        if(empty($users)) $users = $this->loadModel('user')->getList();

        $nameFields = $this->config->action->objectNameFields[$objectType];
        $title      = zget($object, $nameFields, '');
        $host       = empty($webhook->domain) ? common::getSysURL() : $webhook->domain;
        $viewLink   = helper::createLink($objectType, 'view', "id=$objectID", 'html');
        $text       = "[#{$objectID}::{$title}](" . $host . $viewLink . ")";

        $method = 'approval' . strtolower($method);
        if($method and isset($this->lang->action->label->$method))
        {
            $objectTypeName = $objectType == 'requirement' ? $this->lang->action->objectTypes['requirement'] : $this->lang->action->objectTypes[$objectType];
            $text           = $this->app->user->realname . $this->lang->action->label->$method . $objectTypeName . ' ' . $text;
        }

        foreach($users as $user)
        {
            if(in_array($user->account, $sendList))
            {
                $mobile = $user->mobile;
                $email  = $user->email;
                foreach($webhooks as $id => $webhook)
                {
                    if($webhook->type == 'dinggroup' or $webhook->type == 'dinguser')
                    {
                        $data = $this->webhook->getDingdingData($title, $text, $webhook->type == 'dinguser' ? '' : $mobile);
                    }
                    elseif($webhook->type == 'bearychat')
                    {
                        $data = $this->webhook->getBearychatData($text, $mobile, $email, $objectType, $objectID);
                    }
                    elseif($webhook->type == 'wechatgroup' or $webhook->type == 'wechatuser')
                    {
                        $data = $this->webhook->getWeixinData($text, $mobile);
                    }
                    elseif($webhook->type == 'feishuuser' or $webhook->type == 'feishugroup')
                    {
                        $data = $this->webhook->getFeishuData($title, $text);
                    }
                    else
                    {
                        $data = new stdclass();
                        $data->text = $text;
                    }

                    $postData = json_encode($data);
                    if(!$postData) continue;

                    if($webhook->sendType == 'async')
                    {
                        $this->webhook->saveData($id, '0', $postData);
                        continue;
                    }

                    $result = $this->webhook->fetchHook($webhook, $postData, 0, $user->account);
                    if(!empty($result)) $this->webhook->saveLog($webhook, '0', $postData, $result);
                }
            }
        }
    }

    /**
     * Send xuanxuan.
     *
     * @param  array  $sendList
     * @param  string $objectType
     * @param  int    $objectID
     * @param  object $object
     * @access public
     * @return void
     */
    public function sendXuanxuan($sendList, $objectType, $objectID, $object, $actionType)
    {
        $nameFields = $this->config->action->objectNameFields[$objectType];
        $title      = zget($object, $nameFields, '');

        $target = $this->dao->select('id')->from(TABLE_USER)
            ->where('account')->in($sendList)
            ->andWhere('account')->ne($this->app->user->account)->fi()
            ->fetchAll('id');

        $target = array_keys($target);
        $server = $this->loadModel('im')->getServer('zentao');
        $url    = $server . helper::createLink($objectType, 'view', "id=$objectID", 'html');

        $subcontent = new stdclass();
        $subcontent->action     = $actionType;
        $subcontent->object     = $objectID;
        $subcontent->objectName = $title;
        $subcontent->objectType = $objectType;
        $subcontent->actor      = $this->app->user->id;
        $subcontent->actorName  = $this->app->user->realname;
        $subcontent->name       = $title;
        $subcontent->id         = sprintf('%03d', $object->id);
        $subcontent->count      = 1;
        $subcontent->parentType = $objectType;

        $contentData = new stdclass();
        $contentData->title       = $title;
        $contentData->subtitle    = '';
        $contentData->contentType = "zentao-$objectType-$actionType";
        $contentData->parentType  = $subcontent->parentType;
        $contentData->content     = json_encode($subcontent);
        $contentData->actions     = array();
        $contentData->url         = "xxc:openInApp/zentao-integrated/" . urlencode($url);
        $contentData->extra       = '';

        $content   = json_encode($contentData);
        $avatarUrl = $server . $this->app->getWebRoot() . 'favicon.ico';
        $this->im->messageCreateNotify($target, $title, $subtitle = '', $content, $contentType = 'object', $url, $actions = array(), $sender = array('id' => 'zentao', 'realname' => $this->lang->message->sender, 'name' => $this->lang->message->sender, 'avatar' => $avatarUrl));
    }

    /**
     * Pass approval.
     *
     * @param  string $objectType
     * @param  object $object
     * @param  string $extra
     * @param  string $account
     * @access public
     * @return bool
     */
    public function pass($objectType, $object, $extra = '', $account = '')
    {
        if(!$account) $account = $this->app->user->account;

        $approvalID = $this->dao->select('approval')->from(TABLE_APPROVALOBJECT)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($object->id)
            ->orderBy('id_desc')
            ->fetch('approval');

        $node = $this->dao->select('*')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('status')->eq('doing')
            ->andWhere('account')->eq($account)
            ->fetch();

        $data = new stdclass();
        $data->status       = 'done';
        $data->result       = 'pass';
        $data->date         = !empty($object->createdDate) ? $object->createdDate : helper::now();
        $data->opinion      = !empty($object->opinion)     ? $object->opinion     : '';
        $data->extra        = $extra;
        $data->reviewedBy   = $account;
        $data->reviewedDate = helper::now();

        /* 更新当前用户的评审记录，并且将没有设置评审人的节点更新成一样的结果。 */
        $this->dao->update(TABLE_APPROVALNODE)->data($data)
            ->where('approval')->eq($approvalID)
            ->andWhere('status')->eq('doing')
            ->andWhere('account')->eq($account)
            ->orWhere('(node')->eq($node->node)
            ->andWhere('approval')->eq($approvalID)
            ->andWhere('account')->eq('')
            ->andWhere('result')->eq('')
            ->markRight(1)
            ->exec();

        if(!empty($object->setReviewer)) $this->draftPendingReviewer($approvalID, $node->node, $object->setReviewer);

        $undone = $this->dao->select('*')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('node')->eq($node->node)
            ->andWhere('type')->eq('review')
            ->andWhere('status')->notin('done,forward,reverted')
            ->fetch();

        /* 如果需要所有人审批，只要还有未审批的人员就继续往下走。 */
        if($node->needAll == '1' && !empty($undone)) return;

        if($node->multipleType == 'or')
        {
            $this->dao->update(TABLE_APPROVALNODE)->set('status')->eq('done')->set('result')->eq('ignore')
                ->where('approval')->eq($approvalID)
                ->andWhere('node')->eq($node->node)
                ->andWhere('status')->eq('doing')
                ->exec();
        }
        elseif($node->multipleType == 'and')
        {
            /* 百分比评审的逻辑。 */
            if($node->percent > 0)
            {
                $summary = $this->dao->select('count(1) as total, sum(case when result = "pass" then 1 else 0 end) as pass')
                    ->from(TABLE_APPROVALNODE)
                    ->where('approval')->eq($approvalID)
                    ->andWhere('node')->eq($node->node)
                    ->andWhere('type')->eq('review')
                    ->fetch();

                $percent = $summary->total == 0 ? 0 : round($summary->pass / $summary->total * 100);
                if($percent >= $node->percent)
                {
                    $this->dao->update(TABLE_APPROVALNODE)->set('status')->eq('done')->set('result')->eq('ignore')
                        ->where('approval')->eq($approvalID)
                        ->andWhere('node')->eq($node->node)
                        ->andWhere('type')->eq('review')
                        ->andWhere('result')->eq('')
                        ->exec();

                    $this->next($approvalID, $node->node, 'review');
                    $doing = $this->dao->select('id')->from(TABLE_APPROVALNODE)
                        ->where('approval')->eq($approvalID)
                        ->andWhere('status')->eq('doing')
                        ->fetchAll();
                    if(empty($doing)) return $this->finish($approvalID, 'pass', 'review');
                }
                else
                {
                    $this->rejectAfterPass($approvalID, $node);
                }
            }
            elseif(!empty($undone))
            {
                return;
            }
            elseif(empty($undone) && $node->needAll == '1')
            {
                $this->rejectAfterPass($approvalID, $node);
            }
        }

        $ccList = $this->dao->select('account')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('node')->eq($node->node)
            ->andWhere('type')->eq('cc')
            ->fetchAll('account');

        /* All reviewer is passed, run cc. */
        if($ccList) $this->cc($approvalID, $node->node, array_keys($ccList), 'review');

        $this->next($approvalID, $node->node, 'review');

        /* If the flow is finished, change status of approval. */
        $doing = $this->dao->select('id')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('status')->eq('doing')
            ->fetchAll();

        if(empty($doing))
        {
            $reject = $this->dao->select('id')->from(TABLE_APPROVALNODE)
                ->where('approval')->eq($approvalID)
                ->andWhere('result')->eq('fail')
                ->fetchAll();

            return $this->finish($approvalID, empty($reject) ? 'pass' : 'fail', 'review');
        }
    }

    /**
     * 所有人评审、会签，通过也可能拒绝的情况。
     * If need all pass, but one reject, then reject the approval.
     *
     * @param  int    $approvalID
     * @param  string $node
     * @param  string $setReviewer
     * @access public
     * @return void
     */
    public function rejectAfterPass($approvalID, $node)
    {
        $reject = $this->dao->select('id')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('node')->eq($node->node)
            ->andWhere('type')->eq('review')
            ->andWhere('result')->eq('fail')
            ->fetchAll();

        if($reject)
        {
            $this->dao->update(TABLE_APPROVALNODE)
                 ->set('result')->eq('ignore')
                 ->set('status')->eq('done')
                 ->where('approval')->eq($approvalID)
                 ->andWhere('result')->eq('')
                 ->andWhere('status')->ne('done')
                 ->exec();

            $this->finish($approvalID, 'fail', 'review');
        }
    }

    /**
     * Reject approval.
     *
     * @param  string $objectType
     * @param  object $object
     * @param  string $extra
     * @param  string $account
     * @access public
     * @return array
     */
    public function reject($objectType = '', $object = null, $extra = '', $account = '')
    {
        if(!$account) $account = $this->app->user->account;

        $approvalID = $this->dao->select('approval')->from(TABLE_APPROVALOBJECT)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($object->id)
            ->orderBy('id_desc')
            ->fetch('approval');

        $node = $this->dao->select('*')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('status')->eq('doing')
            ->andWhere('account')->eq($account)
            ->fetch();

        $data = new stdclass();
        $data->status       = 'done';
        $data->result       = $node->solicit == '1' ? 'ignore' : 'fail';
        $data->date         = !empty($object->createdDate) ? $object->createdDate : helper::now();
        $data->opinion      = !empty($object->opinion)     ? $object->opinion     : '';
        $data->extra        = $extra;
        $data->reviewedBy   = $account;
        $data->reviewedDate = helper::now();

        $this->dao->update(TABLE_APPROVALNODE)->data($data)
            ->where('approval')->eq($approvalID)
            ->andWhere('status')->eq('doing')
            ->andWhere('account')->eq($account)
            ->orWhere('(node')->eq($node->node)
            ->andWhere('approval')->eq($approvalID)
            ->andWhere('account')->eq('')
            ->andWhere('result')->eq('')
            ->markRight(1)
            ->exec();

        /* 如果需要所有人审批或为征求意见节点，即使拒绝了也依然要往下走。 */
        if($node->needAll == '1' || $node->solicit == '1')
        {
            $nodeUndone = $this->dao->select('*')->from(TABLE_APPROVALNODE)
                  ->where('approval')->eq($approvalID)
                  ->andWhere('node')->eq($node->node)
                  ->andWhere('type')->eq('review')
                  ->andWhere('status')->notin('done,reverted,forward')
                  ->fetch();

            if($nodeUndone) return array('finished' => false, 'result' => '');

            if($node->multipleType == 'or')
            {
                $hasPass = $this->dao->select('*')->from(TABLE_APPROVALNODE)
                    ->where('approval')->eq($approvalID)
                    ->andWhere('node')->eq($node->node)
                    ->andWhere('type')->eq('review')
                    ->andWhere('result')->eq('pass')
                    ->fetch();

                $result = empty($hasPass) ? 'fail' : 'pass';
                if($result == 'pass') $this->next($approvalID, $node->node, 'review');
                return array('finished' => true, 'result' => $result);
            }
        }

        /* 如果是征求意见节点并且后续还有节点未完成，则直接返回。 */
        if($node->solicit == '1')
        {
            $approvalUndone = $this->dao->select('*')->from(TABLE_APPROVALNODE)
                  ->where('approval')->eq($approvalID)
                  ->andWhere('type')->eq('review')
                  ->andWhere('status')->notin('done,reverted,forward')
                  ->fetch();

            $this->next($approvalID, $node->node, 'review');
            if($approvalUndone)
            {
                return array('finished' => false, 'result' => '');
            }
            else
            {
                $this->finish($approvalID, 'pass', 'review');
                return array('finished' => true, 'result' => 'pass');
            }
        }

        /* 如果是百分比通过，并且失败人数先达到上限要立马拒绝。 */
        $finished = true;
        if($node->percent > 0)
        {
            $summary = $this->dao->select('count(1) as total, sum(case when result = "fail" then 1 else 0 end) as fail')
                ->from(TABLE_APPROVALNODE)
                ->where('approval')->eq($approvalID)
                ->andWhere('node')->eq($node->node)
                ->andWhere('type')->eq('review')
                ->fetch();

            $failPercent = $summary->total == 0 ? 0 : round($summary->fail / $summary->total * 100);
            $passPercent = 100 - $failPercent;
            if($failPercent + $node->percent <= 100) $finished = false;
            if($passPercent >= $node->percent)
            {
                $this->next($approvalID, $node->node, 'review');
                $doing = $this->dao->select('id')->from(TABLE_APPROVALNODE)
                    ->where('approval')->eq($approvalID)
                    ->andWhere('status')->eq('doing')
                    ->fetchAll();
                if(empty($doing))
                {
                    $this->finish($approvalID, 'pass', 'review');
                    return array('finished' => true, 'result' => 'pass');
                }
            }
        }

        if($finished)
        {
            $endNodes = $this->dao->select('id,account,node')->from(TABLE_APPROVALNODE)
                ->where('approval')->eq($approvalID)
                ->andWhere('node')->eq('end')
                ->fetchAll();

            if($endNodes)
            {
                $ccList = array();
                foreach($endNodes as $endNode) $ccList[] = $endNode->account;
                $this->cc($approvalID, $endNode->node, $ccList, 'review');
            }

            $this->dao->update(TABLE_APPROVALNODE)
                ->set('status')->eq('done')
                ->set('result')->eq('ignore')
                ->where('approval')->eq($approvalID)
                ->andWhere('status')->notin('done,forward,reverted')
                ->exec();

            $this->finish($approvalID, 'fail', 'review');
            return array('finished' => $finished, 'result' => 'fail');
        }

        return array('finished' => $finished, 'result' => '');
    }

    /**
     * Finish approval.
     *
     * @param int    $approvalID
     * @param string $result
     * @param string $method
     * @access public
     * @return bool
     */
    public function finish($approvalID, $result, $method)
    {
        $this->dao->update(TABLE_APPROVAL)
            ->set('status')->eq('done')
            ->set('result')->eq($result)
            ->where('id')->eq($approvalID)
            ->exec();

        $approval = $this->getByID($approvalID);
        $lastNode = $this->dao->select('node')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->orderBy('id_desc')->fetch('node');
        $this->sendMessage(array($approval->createdBy), $approvalID, $lastNode, $method);

        $startNodes = $this->dao->select('account')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approvalID)
            ->andWhere('node')->eq('start')
            ->fetchPairs('account');

        if($startNodes) $this->sendMessage($startNodes, $approvalID, $lastNode, $method, true);

        if($lastNode == 'end')
        {
            $ccList = $this->dao->select('account')->from(TABLE_APPROVALNODE)
                ->where('approval')->eq($approvalID)
                ->andWhere('node')->eq('end')
                ->andWhere('type')->eq('cc')
                ->fetchPairs('account');

            $this->sendMessage($ccList, $approvalID, $lastNode, $method, true);
        }

        return true;
    }

    /**
     * Restart a approval flow.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function restart($objectType, $objectID)
    {
        $approval = $this->dao->select('*')->from(TABLE_APPROVAL)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->orderBy('id_desc')
            ->fetch();

        $approval->status = 'doing';
        $oldApprovalID    = $approval->id;
        unset($approval->id);

        $this->dao->insert(TABLE_APPROVAL)->data($approval)->exec();
        $approvalID = $this->dao->lastInsertID();

        $approvalObject = new stdclass();
        $approvalObject->approval   = $approvalID;
        $approvalObject->objectType = $objectType;
        $approvalObject->objectID   = $objectID;

        $this->dao->insert(TABLE_APPROVALOBJECT)->data($approvalObject)->exec();

        $nodes = $this->dao->select('*')->from(TABLE_APPROVALNODE)->where('approval')->eq($oldApprovalID)->fetchAll('id', false);
        foreach($nodes as $node)
        {
            unset($node->id);

            $node->status       = 'wait';
            $node->result       = '';
            $node->date         = null;
            $node->opinion      = '';
            $node->reviewedBy   = '';
            $node->reviewedDate = null;
            $node->approval     = $approvalID;

            $this->dao->insert(TABLE_APPROVALNODE)->data($node)->exec();
        }

        $this->next($approvalID, '', 'submit');
    }

    /**
     * Cancel all approval nodes of an object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function cancel($objectType, $objectID)
    {
        $this->app->loadLang('approvalflow');

        $approvalID = $this->dao->select('approval')->from(TABLE_APPROVALOBJECT)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->orderBy('id_desc')
            ->limit(1)
            ->fetch('approval');

        if($approvalID)
        {
            $reviewers = $this->dao->select('account, node')->from(TABLE_APPROVALNODE)
                ->where('approval')->eq($approvalID)
                ->andWhere('status')->eq('doing')
                ->andWhere('type')->eq('review')
                ->fetchAll('account');

            $ccers = $this->dao->select('account, node')->from(TABLE_APPROVALNODE)
                ->where('approval')->eq($approvalID)
                ->andWhere('status')->eq('doing')
                ->andWhere('type')->eq('cc')
                ->fetchAll('account');

            $this->dao->update(TABLE_APPROVALNODE)
                ->set('status')->eq('done')
                ->set('result')->eq('ignore')
                ->where('approval')->eq($approvalID)
                ->andWhere('status')->notin('done,forward,reverted')
                ->exec();
        }

        $approval = $this->getByID($approvalID);
        if($reviewers) $this->sendMessage(array_keys($reviewers), $approvalID, zget(reset($reviewers), 'node'), 'cancel');
        if($ccers)     $this->sendMessage(array_keys($ccers),     $approvalID, zget(reset($ccers), 'node'),     'cancel', true);
        return !dao::isError();
    }

    /**
     * Build review desc.
     *
     * @param  object $node
     * @param  array  $extra
     * @param  array  $nodePairs
     * @access public
     * @return string
     */
    public function buildReviewDesc($node, $extra = array(), $nodePairs = array())
    {
        if(empty($node->id)) return '';

        $approval     = zget($extra, 'approval', '');
        $users        = zget($extra, 'users', array());
        $reviewers    = zget($extra, 'reviewers', array());
        $allReviewers = zget($extra, 'allReviewers', array());
        $reviewDesc   = '';

        /* 审批节点的account为空或为pending-， 都作为没有审批人来处理。*/
        if(!empty($reviewers['reviewers']))
        {
            foreach($reviewers['reviewers'] as $key => $reviewer)
            {
                if(empty($reviewer['account'])) unset($reviewers['reviewers'][$key]);
            }
        }

        if($node->type == 'branch' and isset($node->branches))
        {
            foreach($node->branches as $childNode)
            {
                foreach($childNode->nodes as $branchChildNode) $reviewDesc .= $this->buildReviewDesc($branchChildNode, array('users' => $users, 'allReviewers' => $allReviewers, 'reviewers' => zget($allReviewers, $branchChildNode->id, array())), $nodePairs);
            }
            return $reviewDesc;
        }

        if($node->type == 'start')
        {
            $this->app->loadLang('charter');
            $reviewDesc  = "<li class='start' data-id='{$node->id}'><div><span class='timeline-text'>";
            $reviewDesc .= str_replace(array('$actor', '$date'), array(zget($users, $approval->createdBy), helper::isZeroDate($approval->createdDate) ? '' : substr($approval->createdDate, 0, 16)), sprintf($this->lang->approval->reviewDesc->start, zget($this->lang->charter, $node->extra, $this->lang->approval->start)));
            $reviewDesc .= "</span></div></li>";
            return $reviewDesc;
        }

        if($node->type == 'end') return;

        $hasTitle    = !empty($node->title);
        $reviewClass = $hasTitle ? 'reviewer' : 'node';
        $reviewDesc  = '';

        $nodeStatus = 'wait';
        if(empty($reviewers) and isset($node->agentType))
        {
            if($node->agentType == 'pass')   $nodeStatus = 'pass';
            if($node->agentType == 'reject') $nodeStatus = 'fail';
        }
        if(!empty($reviewers)) $nodeStatus = empty($reviewers['result']) ? $reviewers['status'] : $reviewers['result'];
        if($nodeStatus == 'success') $nodeStatus = 'pass';
        if($nodeStatus == 'ignore' && $node->multiple == 'solicit') $nodeStatus = 'pass';
        if($nodeStatus == 'ignore') return;

        if($hasTitle)
        {
            $reviewDesc .= "<li class='node title text-muted $nodeStatus' data-id='{$node->id}' data-status='{$nodeStatus}'><div><span class='timeline-text'>";
            $reviewDesc .= $node->title;
            if($node->multiple == 'or')    $reviewDesc .= $this->lang->approval->notice->orSign;
            if(!empty($reviewers['desc'])) $reviewDesc .= ' (' . $reviewers['desc'] . ')';
            if(!empty($node->addNodeDesc))
            {
                foreach($node->addNodeDesc as $addNodeDesc) $reviewDesc .= "<div class='addNodeDesc'>{$addNodeDesc}</div>";
            }
            $reviewDesc .= "</span></div></li>";
        }

        if((empty($reviewers) || empty($reviewers['reviewers'])) and !empty($node->reviewers))
        {
            $reviewDesc .= "<li class='$reviewClass $nodeStatus' data-id='{$node->id}' data-status='{$nodeStatus}'><div><span class='timeline-text'>";
            if(isset($node->agentType) && $node->agentType == 'pass')   $reviewDesc .= $this->lang->approval->reviewDesc->pass4NoReviewer;
            if(isset($node->agentType) && $node->agentType == 'reject') $reviewDesc .= $this->lang->approval->reviewDesc->reject4NoReviewer;
            $reviewDesc .= "</span></div></li>";
        }
        elseif(empty($reviewers['reviewers']) and !empty($reviewers['ccs']))
        {
            $ccList = '';
            foreach($reviewers['ccs'] as $cc) $ccList .= zget($users, $cc) . ' ';
            $reviewDesc .= "<li class='$reviewClass $nodeStatus' data-id='{$node->id}' data-status='{$nodeStatus}'><div><span class='timeline-text'>";
            $reviewDesc .= str_replace('$actor', trim($ccList), $this->lang->approval->reviewDesc->cc);
            $reviewDesc .= "</span></div></li>";
        }
        elseif(!empty($reviewers['reviewers']))
        {
            foreach($reviewers['reviewers'] as $reviewInfo)
            {
                $reviewStatus = !empty($reviewInfo['result']) ? $reviewInfo['result'] : $reviewInfo['status'];

                /* 征询意见节点的评审结果始终为通过。 */
                if($reviewStatus == 'ignore' && $node->multiple == 'solicit') $reviewStatus = 'pass';

                if(!isset($this->lang->approval->reviewDesc->$reviewStatus)) continue;

                if(!empty($reviewInfo['result']) and isset($node->multiple) and $node->multiple == 'or')
                {
                    /* Only show reviewed account in or node. */
                    if($reviewInfo['result'] != 'ignore')
                    {
                        $reviewDesc .= "<li class='$reviewClass $reviewStatus' data-id='{$node->id}' data-status='{$reviewStatus}'><div><span class='timeline-text'>";
                        $reviewDesc .= str_replace(array('$actor', '$date', '$node'), array(zget($users, $reviewInfo['account']), helper::isZeroDate($reviewInfo['reviewedDate']) ? '' : substr($reviewInfo['reviewedDate'], 0, 16), zget($nodePairs, $reviewInfo['revertTo'], '')), $this->lang->approval->reviewDesc->{$reviewStatus});
                        if(!empty($reviewInfo['forwardBy'])) $reviewDesc .= str_replace('$actor', zget($users, $reviewInfo['forwardBy']), $this->lang->approval->reviewDesc->forwardBy);
                        $reviewDesc .= "</span>";
                        if(!empty($reviewInfo['opinion'])) $reviewDesc .= "<div class='opinion'>{$reviewInfo['opinion']}</div>";
                        $reviewDesc .= "</div></li>";
                        if(isset($node->needAll) && $node->needAll == '0') break;
                    }
                }
                else
                {
                    if($reviewStatus == 'ignore') continue;

                    $account     = $reviewInfo['account'];
                    $reviewDesc .= "<li class='$reviewClass $reviewStatus' data-id='{$node->id}' data-status='{$reviewStatus}'><div><span class='timeline-text'>";
                    if(empty($account))
                    {
                        $autoReviewDesc = '';
                        if($node->reviewType == 'reject') $autoReviewDesc = $this->lang->approval->reviewDesc->autoReject;
                        if($node->reviewType == 'pass')   $autoReviewDesc = $this->lang->approval->reviewDesc->autoPass;
                        if(!empty($reviewInfo['result']) and $node->reviewType == 'reject') $autoReviewDesc = $this->lang->approval->reviewDesc->autoRejected;
                        if(!empty($reviewInfo['result']) and $node->reviewType == 'pass')   $autoReviewDesc = $this->lang->approval->reviewDesc->autoPassed;
                        $reviewDesc .= $autoReviewDesc;
                    }
                    else
                    {
                        /* 由于是上级节点设置审批人，提示暂未确定审批人。 */
                        if(strpos($reviewInfo['account'], 'pending-') !== false)
                        {
                            $reviewDesc .= $this->lang->approval->reviewDesc->setReviewer;
                        }
                        else
                        {
                            $reviewDesc .= str_replace(array('$actor', '$date', '$node'), array(zget($users, $reviewInfo['account']), helper::isZeroDate($reviewInfo['reviewedDate']) ? '' : substr($reviewInfo['reviewedDate'], 0, 16), zget($nodePairs, $reviewInfo['revertTo'], '')), $this->lang->approval->reviewDesc->{$reviewStatus});
                        }
                    }

                    if(!empty($reviewInfo['forwardBy'])) $reviewDesc .= str_replace('$actor', zget($users, $reviewInfo['forwardBy']), $this->lang->approval->reviewDesc->forwardBy);
                    $reviewDesc .= "</span>";
                    if(!empty($reviewInfo['opinion'])) $reviewDesc .= "<div class='opinion'>{$reviewInfo['opinion']}</div>";
                    $reviewDesc .= "</div></li>";
                }

                $reviewDesc .= $this->printReviewerFiles($reviewInfo['files']);
            }
        }
        return $reviewDesc;
    }

    /**
     * Print reviewer files.
     *
     * @param mixed $files
     * @access private
     * @return void
     */
    private function printReviewerFiles($files)
    {
        if(!$files) return '';
        $showDelete = false;
        $showEdit   = true;
        $reviewDesc = include '../view/printfiles.html.php';

        return $reviewDesc;
    }

    /**
     * Order branch nodes. order rule is done, doing, wait.
     *
     * @param  array  $nodes
     * @param  array  $reviewers
     * @param  string $extra
     * @access public
     * @return array
     */
    public function orderBranchNodes($nodes, $reviewers, $extra = '')
    {
        foreach($nodes as $i => $node)
        {
            $node->extra = $extra;

            if($node->type != 'branch') continue;
            if(count((array)$node->branches) <= 1) continue;

            $doneNodes  = array();
            $doingNodes = array();
            $waitNodes  = array();
            foreach($node->branches as $branckKey => $branchNodes)
            {
                $nodeStatus = 'wait';
                foreach($branchNodes->nodes as $branchNode)
                {
                    if($nodeStatus == 'doing') break;
                    if(!isset($reviewers[$branchNode->id]))
                    {
                        if(isset($branchNode->agentType) and $branchNode->agentType == 'pass') $nodeStatus = 'done';
                        continue;
                    }

                    if($reviewers[$branchNode->id]['status'] == 'doing') $nodeStatus = 'doing';
                    if($reviewers[$branchNode->id]['status'] == 'done')  $nodeStatus = 'done';
                    if($reviewers[$branchNode->id]['status'] == 'wait' and $nodeStatus == 'done') $nodeStatus = 'doing';
                }

                if($nodeStatus == 'done')  $doneNodes[]  = $branchNodes;
                if($nodeStatus == 'doing') $doingNodes[] = $branchNodes;
                if($nodeStatus == 'wait')  $waitNodes[]  = $branchNodes;
            }

            $sortedNodes = array();
            foreach($doneNodes as $branchNodes)  $sortedNodes[] = $branchNodes;
            foreach($doingNodes as $branchNodes) $sortedNodes[] = $branchNodes;
            foreach($waitNodes as $branchNodes)  $sortedNodes[] = $branchNodes;

            $node->branches = $sortedNodes;
        }

        return $nodes;
    }

    /*
     * Get approval node by approval id.
     *
     * @param  int    $approvalID
     * @param  string $type
     * @access public
     */
    public function getApprovalNodeByApprovalID($approvalID, $type = 'review')
    {
        return $this->dao->select('t1.id,t1.approval,t1.type,t1.result,t1.date,t1.opinion,t1.extra,t1.reviewedBy,t1.reviewedDate')->from(TABLE_APPROVALNODE)->alias('t1')
            ->where('type')->eq($type)
            ->andWhere('approval')->eq($approvalID)
            ->andWhere('result')->in('fail,pass')
            ->orderBy('reviewedDate asc')
            ->fetchAll();
    }

    /**
     * 给下一节点设置待定状态的审批人。
     * Draft pending reviewer.
     *
     * @param  int    $approvalID
     * @param  int    $node
     * @param  string $reviewer
     * @access public
     * @return void
     */
    public function draftPendingReviewer($approvalID, $node, $reviewer)
    {
        $this->dao->update(TABLE_APPROVALNODE)
            ->set('account')->eq('pending-' . $reviewer)
            ->where('approval')->eq($approvalID)
            ->andWhere('prev')->like("%{$node}%")
            ->andWhere('account')->like('pending-%')
            ->exec();
    }

    /**
     * 根据审批规则设置审批人。
     * Set reviewer by approval rule.
     *
     * @param  int    $approvalID
     * @param  int    $node
     * @param  array  $reviewers
     * @param  string $action
     * @access public
     * @return array
     */
    public function setReviewerByRule($approvalID, $node, $reviewers, $action = 'submit')
    {
        /* 如果审批人被删除的情况 */
        $approval     = $this->getByID($approvalID);
        $nodeGroups   = $this->getNodeOptions(json_decode($approval->nodes));
        $nodeOption   = zget($nodeGroups, $node);
        $deletedUsers = $this->dao->select('account')->from(TABLE_USER)->where('deleted')->eq(1)->fetchPairs();

        $object = new stdclass();
        $object->id = $approval->objectID;
        foreach($reviewers as $key => $reviewer)
        {
            $oldReviewer = $reviewer;
            if(strpos($reviewer, 'pending-') !== false) $reviewer = str_replace('pending-', '', $reviewer);
            $reviewer = $this->approvalflow->checkReviewerRule(array($reviewer), $nodeOption, $approval->createdBy, $deletedUsers);
            $reviewer = current($reviewer);

            $reviewers[$key] = $reviewer;

            $this->dao->update(TABLE_APPROVALNODE)
                ->set('account')->eq($reviewer)
                ->where('approval')->eq($approvalID)
                ->andWhere('node')->eq($node)
                ->andWhere('type')->eq('review')
                ->andWhere('account')->eq($oldReviewer)
                ->exec();

            /* 设置了如果发起人本人评审则自动通过。 */
            if(!empty($nodeOption->selfType) && $nodeOption->selfType == 'selfPass' && $reviewer == $approval->createdBy)
            {
                $this->pass($approval->objectType, $object, '', $reviewer);
                unset($reviewers[$key]);
                continue;
            }

            /* 设置了评审人被删除后流转。 */
            if(!empty($nodeOption->deletedType) && ($nodeOption->deletedType == 'autoPass' || $nodeOption->deletedType == 'autoReject') && in_array($reviewer, $deletedUsers))
            {
                if($nodeOption->deletedType == 'autoPass')   $this->pass($approval->objectType,   $object, '', $reviewer);
                if($nodeOption->deletedType == 'autoReject') $this->reject($approval->objectType, $object, '', $reviewer);
                unset($reviewers[$key]);
                continue;
            }
        }

        if(empty($reviewers))
        {
            /* 审批人为空自动拒绝。 */
            if($nodeOption->agentType == 'reject')
            {
                $this->dao->update(TABLE_APPROVALNODE)
                    ->set('status')->eq('done')
                    ->set('result')->eq('fail')
                    ->where('approval')->eq($approvalID)
                    ->andWhere('node')->eq($node)
                    ->andWhere('account')->eq('')
                    ->exec();

                $table = zget($this->config->objectTables, $approval->objectType, '');
                if($table)
                {
                    $object = $this->dao->select('*')->from($table)->where('id')->eq($approval->objectID)->fetch();
                    return $this->reject($approval->objectType, $object);
                }
            }

            $doing = $this->dao->select('id')->from(TABLE_APPROVALNODE)
                ->where('approval')->eq($approvalID)
                ->andWhere('status')->eq('doing')
                ->fetchAll();
            if(empty($doing))
            {
                $reject = $this->dao->select('id')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('result')->eq('fail')->fetchAll();
                $this->finish($approvalID, empty($reject) ? 'pass' : 'fail', 'review');
            }
            else
            {
                $this->next($approvalID, $node, $action);
            }
        }

        return $reviewers;
    }

    /**
     * 将节点按照代号分组展示。
     * Get options group by node.
     *
     * @param  array  $nodes
     * @access public
     * @return array
     */
    public function getNodeOptions($nodes)
    {
        $nodeGroup = array();
        foreach($nodes as $node)
        {
            if($node->type == 'branch')
            {
                foreach($node->branches as $index => $branch)
                {
                    $nodeGroup = array_merge($nodeGroup, $this->getNodeOptions($branch->nodes));
                }
            }
            else
            {
                if(isset($node->id)) $nodeGroup[$node->id] = $node;
            }
        }
        return $nodeGroup;
    }

    /**
     * 给工作流添加审批流额外字段。
     * Set approval fields.
     *
     * @param  array  $fields
     * @param  string $objectType
     * @param  string $objectID
     * @access public
     * @return array
     */
    public function setApprovalFields($fields, $objectType, $objectID)
    {
        $approval   = $this->loadModel('approval')->getByObject($objectType, $objectID);
        $doingNode  = $this->dao->select('node,COUNT(1) as count')->from(TABLE_APPROVALNODE)->where('approval')->eq($approval->id)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->groupBy('node')->fetch();
        $nodeGroups = $this->getNodeOptions(json_decode($approval->nodes));
        $nodeOption = zget($nodeGroups, $doingNode->node);

        $setReviewer = $this->getNextPendingReviewer($objectType, $objectID);

        $users = $this->loadModel('user')->getDeptPairs('nodeleted|noclosed');

        $newFields = array();
        foreach($fields as $key => $field)
        {
            if($field->field == 'reviewResult' && $setReviewer)
            {
                $data = new stdclass();
                $data->show     = 'true';
                $data->module   = 'module';
                $data->field    = 'setReviewer';
                $data->required = ($nodeOption->multiple == 'or' && empty($nodeOption->needAll)) || $doingNode->count <= 1;
                $data->name     = $this->lang->approval->setReviewer;
                $data->control  = 'select';
                $data->options  = $users;
                $data->default  = strpos($setReviewer, 'pending-') !== false ? substr($setReviewer, 8) : $setReviewer;
                $newFields['setReviewer'] = $data;
            }
            if($field->field == 'reviewOpinion')
            {
                $field->required = isset($nodeOption->commentType) && $nodeOption->commentType == 'required';
            }
            $newFields[$key] = $field;
        }

        return $newFields;
    }

    /**
     * Get next pending reviewer.
     *
     * @param  string       $objectType
     * @param  int          $objectID
     * @access public
     * @return string|false
     */
    public function getNextPendingReviewer($objectType, $objectID)
    {
        $approval = $this->loadModel('approval')->getByObject($objectType, $objectID);
        $prevNode = $this->dao->select('node')->from(TABLE_APPROVALNODE)->where('approval')->eq($approval->id)->andWhere('status')->eq('doing')->fetch('node');

        return $this->dao->select('account')->from(TABLE_APPROVALNODE)
            ->where('approval')->eq($approval->id)
            ->andWhere('status')->eq('wait')
            ->andWhere('prev')->eq($prevNode)
            ->andWhere('account')->like('pending-%')
            ->fetch('account');
    }

    /**
     * 审批人被删除了。
     * Delete approval user.
     *
     * @access public
     * @return void
     */
    public function deleteApprovalUser($account)
    {
         $nodes = $this->dao->select('node,approval,account')->from(TABLE_APPROVALNODE)->where('account')->eq($account)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->fetchAll('node');
         foreach($nodes as $node)
         {
            $this->setReviewerByRule($node->approval, $node->node, array($node->account));
         }
    }

    /**
     * 审批人必填校验。
     * Check approval reviewer required.
     *
     * @param  int    $root
     * @param  string $type
     * @access public
     * @return void
     */
    public function checkReviewer($root, $type, $reviewers = array())
    {
        $flowID    = $this->loadModel('approvalflow')->getFlowIDByObject($root, $type);
        $flow      = $this->approvalflow->getByID($flowID, 0);
        $nodes     = !empty($flow->nodes) ? json_decode($flow->nodes) : array();
        $nodeGroup = $this->getNodeOptions($nodes);
        $reviewers = $reviewers ? $reviewers : $this->post->reviewer;

        foreach($reviewers as $nodeID => $reviewer)
        {
            if(!empty(array_filter($reviewer))) continue;
            if(!isset($nodeGroup[$nodeID])) continue;

            foreach($nodeGroup[$nodeID]->reviewers as $reviewOption)
            {
                if($reviewOption->type == 'select' && (empty($reviewOption->required) || $reviewOption->required == 'yes')) dao::$errors["reviewer{$nodeID}"] = sprintf($this->lang->error->notempty, $this->lang->approval->reviewer);
            }
        }

        if(dao::isError() && !$root) dao::$errors['message'] = sprintf($this->lang->error->notempty, $this->lang->approval->reviewer);

        return true;
    }

    /**
     * 检查是否已经评审了。
     * Check if a object is reviewed.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function isReviewed($objectType, $objectID)
    {
        $pendingReview = $this->dao->select('t2.objectID')->from(TABLE_APPROVALNODE)->alias('t1')
            ->leftJoin(TABLE_APPROVALOBJECT)->alias('t2')
            ->on('t2.approval = t1.approval')
            ->where('t2.objectType')->eq($objectType)
            ->andWhere('t1.account')->eq($this->app->user->account)
            ->andWhere('t1.status')->eq('doing')
            ->andWhere('t1.type')->eq('review')
            ->andWhere('t2.objectID')->eq($objectID)
            ->fetchPairs('objectID');

        return !empty($pendingReview) ? true : false;
    }

    /**
     * 获取审批人
     * Get reviewer account by approval id.
     *
     * @param  int    $approvalID
     * @access public
     * @return array
     */
    public function getReviewerByApprovalID($approvalID)
    {
        return $this->dao->select('account')->from(TABLE_APPROVALNODE)->where('approval')->eq($approvalID)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->fetchPairs();
    }
}
