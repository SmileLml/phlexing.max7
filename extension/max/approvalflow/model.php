<?php
/**
 * The model file of approvalflow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     approvalflow
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class approvalflowModel extends model
{
    /**
     * My info
     *
     * @var array
     * @access private
     */
    private $submitter = array();

    /**
     * Get flow by id.
     *
     * @param  int $flowID
     * @param  int $version
     * @access public
     * @return void
     */
    public function getByID($flowID, $version = 0)
    {
        $flow = $this->dao->select('*')->from(TABLE_APPROVALFLOW)->where('id')->eq($flowID)->fetch();
        if(!$flow) return false;

        $spec = $this->dao->select('*')->from(TABLE_APPROVALFLOWSPEC)
            ->where('flow')->eq($flow->id)
            ->andWhere('version')->eq($version == 0 ? $flow->version : $version)
            ->fetch();
        $flow->version = $spec->version;
        $flow->nodes   = $spec->nodes;

        return $flow;
    }

    /**
     * Get flows.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($type = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $flows = $this->dao->select("*, date_format(createdDate, '%Y-%m-%d %H:%i:%s') as createdDate")->from(TABLE_APPROVALFLOW)
            ->where('deleted')->eq(0)
            ->beginIF($this->config->edition == 'biz')->andWhere('code')->ne('simple')->fi()
            ->beginIF($type == 'noworkflow')->andWhere('workflow')->eq('')->fi()
            ->beginIF($type != 'all' && $type != 'noworkflow')
            ->andWhere('(workflow')->eq('')
            ->orWhere('workflow')->eq($type)
            ->markRight(true)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);

        return $flows;
    }

    /**
     * Get flow pairs.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function getPairs($type = 'all')
    {
        $flows = $this->getList($type);

        if(empty($flows)) return array();

        $pairs = array();
        foreach($flows as $flow) $pairs[$flow->id] = $flow->name;

        return $pairs;
    }

    /**
     * Get approval flow id by object.
     *
     * @param  int    $rootID
     * @param  string $objectType
     * @param  string $extra
     * @access public
     * @return void
     */
    public function getFlowIDByObject($rootID = 0, $objectType = '', $extra = '')
    {
        if(!$objectType) return 0;

        $flowID = $this->dao->select('flow')->from(TABLE_APPROVALFLOWOBJECT)
            ->where('root')->eq($rootID)
            ->andWhere('objectType')->eq($objectType)
            ->beginIF(!empty($extra))->andWhere('extra')->eq($extra)->fi()
            ->fetch('flow');

        if(!$flowID) $flowID = $this->dao->select('flow')->from(TABLE_APPROVALFLOWOBJECT)
            ->where('root')->eq(0)
            ->andWhere('objectType')->eq($objectType)
            ->beginIF(!empty($extra))->andWhere('extra')->eq($extra)->fi()
            ->fetch('flow');

        if($this->config->edition != 'biz')
        {
            /* Baseline object list default simple flow. */
            $this->app->loadLang('baseline');
            if($this->config->systemMode == 'PLM') $this->lang->baseline->objectList = array_merge($this->lang->baseline->objectList, $this->lang->baseline->ipd->pointList);
            $baselineObjects = $this->lang->baseline->objectList;

            if(in_array($objectType, array_keys($baselineObjects)) and !$flowID) $flowID = $this->dao->select('id')->from(TABLE_APPROVALFLOW)->where('code')->eq('simple')->fetch('id');
        }

        return $flowID ? $flowID : 0;
    }

    /**
     * Get approvalflow object.
     *
     * @param  int    $rootID
     * @param  string $objectType
     * @param  string $extra
     * @access public
     * @return object
     */
    public function getFlowObject($rootID = 0, $objectType = '', $extra = '')
    {
        $flow = $this->dao->select('*')->from(TABLE_APPROVALFLOWOBJECT)
            ->where('root')->eq($rootID)
            ->andWhere('objectType')->eq($objectType)
            ->beginIF(!empty($extra))->andWhere('extra')->eq($extra)->fi()
            ->fetch();

        if(!$flow) $flow = $this->dao->select('*')->from(TABLE_APPROVALFLOWOBJECT)
            ->where('root')->eq(0)
            ->andWhere('objectType')->eq($objectType)
            ->beginIF(!empty($extra))->andWhere('extra')->eq($extra)->fi()
            ->fetch();

        return $flow;
    }

    /**
     * Gen nodes for approval flow.
     *
     * @param  object $nodes
     * @param  array  $users
     * @param  array  $toNodeList
     * @param  object $object
     * @access public
     * @return void
     */
    public function genNodes(&$nodes, $users = array(), $toNodeList = array(), $object = null)
    {
        if($users)
        {
            $reviewers    = $users['reviewers'];
            $upLevel      = $users['upLevel'];
            $superior     = $users['superior'];
            $positions    = $users['userGroupByRole'];
            $deletedUsers = $users['deletedUsers'];
        }

        foreach($nodes as $node)
        {
            if($node->type == 'branch')
            {
                $isContinue = true; // Need execute other branch ?
                foreach($node->branches as $index => $branch)
                {
                    if($this->checkCondition($branch->conditions, $object) and $isContinue)
                    {
                        $this->genNodes($branch->nodes, $users, array(), $object);
                        $node->branches[$index] = $branch;
                        if($node->branchType != 'parallel')
                        {
                            $isContinue = false;
                        }
                    }
                    else
                    {
                        unset($node->branches[$index]);
                    }
                }

                if($isContinue)
                {
                    $this->genNodes($node->default->nodes, $users, array(), $object);
                    $node->branches[] = $node->default;
                }
                unset($node->default);
            }
            else
            {
                $node->id       = in_array($node->type, array('start', 'end')) ? $node->type : $node->id;
                $node->title    = isset($node->title) ? $node->title : $this->lang->approvalflow->nodeTypeList[$node->type];
                $node->multiple = isset($node->multiple) ? $node->multiple : 'and';
                if(isset($toNodeList[$node->id])) $node->toNodeID = $toNodeList[$node->id];
                if($node->type == 'approval' && isset($node->reviewType) && $node->reviewType != 'manual')
                {
                    $reviewer = new stdclass();
                    $reviewer->users = array('');
                    $node->reviewers = array($reviewer);
                }
                else if(isset($node->reviewers) && !empty($node->reviewers))
                {
                    foreach($node->reviewers as $index => $reviewer)
                    {
                        if(!isset($node->reviewers[$index]->users)) $node->reviewers[$index]->users = array();
                        if($reviewer->type == 'select')
                        {
                            if(!isset($reviewers[$node->id]) || !isset($reviewers[$node->id]['reviewers']))
                            {
                                dao::$errors[] = $this->lang->approvalflow->errorList['needReivewer'];
                                return false;
                            }

                            $node->reviewers[$index]->users = isset($reviewers) ? $reviewers[$node->id]['reviewers'] : array();
                        }
                        else if($reviewer->type == 'self')
                        {
                            $node->reviewers[$index]->users = array($this->app->user->account);
                        }
                        else if($reviewer->type == 'upLevel')
                        {
                            $node->reviewers[$index]->users = $upLevel ? array($upLevel) : array();
                        }
                        else if($reviewer->type == 'superior')
                        {
                            $node->reviewers[$index]->users = $superior ? array($superior) : array();
                        }
                        else if($reviewer->type == 'superiorList')
                        {
                            $node->reviewers[$index]->users = $this->getSuperiorList($this->app->user->account, $node->reviewers[$index]->superiorList);
                        }
                        else if($reviewer->type == 'setByPrev')
                        {
                            $node->reviewers[$index]->users = array('pending-'); //由上级节点设置审批人，此时审批人为特殊字符串'pending-'。
                        }
                        else if($reviewer->type == 'position')
                        {
                            $node->reviewers[$index]->users = array();
                            foreach($reviewer->positions as $position) $node->reviewers[$index]->users = array_merge($node->reviewers[$index]->users, array_keys(zget($positions, $position, array())));
                            $node->reviewers[$index]->users = array_filter($node->reviewers[$index]->users);
                        }
                        else
                        {
                            foreach(array('roles', 'projectRoles', 'productRoles', 'executionRoles') as $roleType)
                            {
                                if(!empty($reviewer->{$roleType}) && !empty($users[$roleType]))
                                {
                                    $node->reviewers[$index]->users = array();
                                    foreach($reviewer->{$roleType} as $role) $node->reviewers[$index]->users = array_merge($node->reviewers[$index]->users, $users[$roleType][$role]);
                                    $node->reviewers[$index]->users = array_filter($node->reviewers[$index]->users);
                                }
                            }
                        }

                        $node->reviewers[$index]->users = $this->checkReviewerRule($node->reviewers[$index]->users, $node, $this->app->user->account, $deletedUsers);
                    }
                }

                if(isset($node->ccs) and !empty($node->ccs))
                {
                    foreach($node->ccs as $index => $cc)
                    {
                        if($cc->type == 'select')
                        {
                            if(!isset($reviewers[$node->id]) or !isset($reviewers[$node->id]['ccs']))
                            {
                                dao::$errors[] = $this->lang->approvalflow->errorList['needCcer'];
                                return false;
                            }
                            $node->ccs[$index]->users = isset($reviewers) ? $reviewers[$node->id]['ccs'] : array();
                        }
                        else if($cc->type == 'self')
                        {
                            $node->ccs[$index]->users = array($this->app->user->account);
                        }
                        else if($cc->type == 'upLevel')
                        {
                            $node->ccs[$index]->users = $upLevel ? array($upLevel) : array();
                        }
                        else if($cc->type == 'superior')
                        {
                            $node->ccs[$index]->users = $superior ? array($superior) : array();
                        }
                        else if($cc->type == 'position')
                        {
                            $node->ccs[$index]->users = array();
                            foreach($cc->positions as $position) $node->ccs[$index]->users = array_merge($node->ccs[$index]->users, array_keys(zget($positions, $position, array())));
                            $node->ccs[$index]->users = array_filter($node->ccs[$index]->users);
                        }
                        else
                        {
                            foreach(array('roles', 'projectRoles', 'productRoles', 'executionRoles') as $roleType)
                            {
                                if(isset($cc->{$roleType}) && !empty($cc->{$roleType}))
                                {
                                    $node->ccs[$index]->users = array();
                                    foreach($cc->{$roleType} as $role) $node->ccs[$index]->users = array_merge($node->ccs[$index]->users, $users[$roleType][$role]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Get role list.
     *
     * @access public
     * @return array
     */
    public function getRoleList()
    {
        return $this->dao->select('*')->from(TABLE_APPROVALROLE)->where('deleted')->eq('0')->fetchAll('id', false);
    }

    /**
     * Get role pairs.
     *
     * @access public
     * @return array
     */
    public function getRolePairs()
    {
        return $this->dao->select('id,name')->from(TABLE_APPROVALROLE)->where('deleted')->eq('0')->fetchPairs();
    }

    /**
     * 获取某个用户的上级用户列表。
     * Get superior list.
     *
     * @param  string $account
     * @param  int    $grade
     * @access public
     * @return object
     */
    public function getSuperiorList($account = '', $grade = 0)
    {
        $superiorList     = array();
        $currentAccount   = $account;
        $visitedAccounts = array();

        $level = 0;
        while(true)
        {
            $superior = $this->dao->select('superior')->from(TABLE_USER)->where('account')->eq($currentAccount)->fetch('superior');

            if(empty($superior) || ($grade > 0 && $level >= $grade) || in_array($superior, $visitedAccounts)) break;

            $superiorList[]    = $superior;
            $visitedAccounts[] = $currentAccount;
            $currentAccount    = $superior;
            $level++;
        }

        return $superiorList;
    }

    /**
     * Create flow.
     *
     * @param  string $workflow
     * @access public
     * @return void
     */
    public function create($workflow = '')
    {
        $data = new stdclass();
        $data->name        = $this->post->name;
        $data->workflow    = $this->post->workflow;
        $data->desc        = $this->post->desc;
        $data->createdBy   = $this->app->user->account;
        $data->createdDate = helper::now();

        $this->dao->insert(TABLE_APPROVALFLOW)->data($data)
            ->autoCheck()
            ->batchCheck($this->config->approvalflow->create->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $flowID = $this->dao->lastInsertID();

            $spec = new stdclass();
            $spec->flow        = $flowID;
            $spec->version     = 1;
            $spec->nodes       = '[{"type":"start","ccs":[]},{"id":"3ewcj92p55e","type":"approval","reviewType":"manual","reviewers":[{"type":"select"}]},{"type":"end","ccs":[]}]';
            $spec->createdBy   = $this->app->user->account;
            $spec->createdDate = helper::now();

            $this->dao->insert(TABLE_APPROVALFLOWSPEC)->data($spec)->exec();

            if($workflow && $data->workflow == $workflow)
            {
                $approvalflowObject = new stdclass();
                $approvalflowObject->objectType = $workflow;
                $approvalflowObject->flow       = $flowID;
                $this->dao->delete()->from(TABLE_APPROVALFLOWOBJECT)->where('objectType')->eq($workflow)->exec();
                $this->dao->insert(TABLE_APPROVALFLOWOBJECT)->data($approvalflowObject)->exec();
            }

            return $flowID;
        }

        return false;
    }

    /**
     * Update flow.
     *
     * @param  int    $flowID
     * @access public
     * @return array
     */
    public function update($flowID)
    {
        $oldFlow = $this->getByID($flowID);
        $flow    = fixer::input('post')->get();

        $this->dao->update(TABLE_APPROVALFLOW)->data($flow)
            ->where('id')->eq($flowID)
            ->batchCheck($this->config->approvalflow->edit->requiredFields, 'notempty')
            ->exec();

        return common::createChanges($oldFlow, $flow);
    }

    /**
     * Update nodes
     *
     * @param object $flow
     * @access public
     * @return void
     */
    public function updateNodes($flow)
    {
        $version = $flow->version + 1;

        $data = new stdclass();
        $data->flow        = $flow->id;
        $data->version     = $version;
        $data->nodes       = $this->post->nodes;
        $data->createdBy   = $this->app->user->account;
        $data->createdDate = helper::now();

        $this->dao->insert(TABLE_APPROVALFLOWSPEC)->data($data)->exec();

        $this->dao->update(TABLE_APPROVALFLOW)
            ->set('version')->eq($version)
            ->where('id')->eq($flow->id)
            ->exec();
    }

    /**
     * Create a approval role.
     *
     * @access public
     * @return void
     */
    public function createRole()
    {
        $data = fixer::input('post')
            ->join('users', ',')
            ->get();
        if($data->users) $data->users = ',' . trim($data->users, ',') . ',';

        $this->lang->approvalrole = new stdclass();
        $this->lang->approvalrole->name = $this->lang->approvalflow->name;
        $this->dao->insert(TABLE_APPROVALROLE)->data($data)->batchCheck('name', 'notempty')->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * Edit a approval role.
     *
     * @access public
     * @return void
     */
    public function editRole($roleID)
    {
        $data = fixer::input('post')
            ->join('users', ',')
            ->get();
        if($data->users) $data->users = ',' . trim($data->users, ',') . ',';

        $this->lang->approvalrole = new stdclass();
        $this->lang->approvalrole->name = $this->lang->approvalflow->name;
        $this->dao->update(TABLE_APPROVALROLE)
            ->data($data)
            ->batchCheck('name', 'notempty')
            ->where('id')->eq($roleID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Check condition
     *
     * @param  array  $conditions
     * @param  object $object
     * @access public
     * @return bool
     */
    public function checkCondition($conditions, $object = null)
    {
        if(empty($conditions)) return true;

        if(empty($this->submitter))
        {
            $user = $this->loadModel('user')->fetchByID($this->app->user->id);
            /* Depts. */
            $path = '';
            if($user->dept) $path = $this->dao->select('path')->from(TABLE_DEPT)->where('id')->eq($user->dept)->fetch('path');
            $depts = explode(',', trim($path, ','));

            /* Roles. */
            $roles = $this->dao->select('id')->from(TABLE_APPROVALROLE)->where('users')->like('%,' . $this->app->user->account . ',%')->fetchAll('id');
            $roles = array_keys($roles);

            $this->submitter['account']  = $this->app->user->account;
            $this->submitter['depts']    = $depts;
            $this->submitter['roles']    = $roles;
            $this->submitter['position'] = $user->role;
        }

        $enabled = false;
        $index   = 1;
        foreach($conditions as $condition)
        {
            $conditionField    = $condition->conditionField;
            $conditionValue    = $condition->conditionValue;
            $conditionOperator = $condition->conditionOperator;

            $var = '';
            if($conditionField == 'submitUsers')
            {
                $var = $this->submitter['account']; // 发起人用户名
            }
            elseif($conditionField == 'submitDepts')
            {
                $var = in_array($conditionOperator, array('include', 'notinclude')) ? $this->submitter['depts'] : $this->app->user->dept; // 发起人部门
            }
            elseif($conditionField == 'submitRoles')
            {
                $var = $this->submitter['roles']; // 发起人角色
                if(in_array($conditionOperator, array('equal', 'notequal'))) $conditionOperator = $conditionOperator == 'equal' ? 'include' : 'notinclude';
            }
            elseif($conditionField == 'submitPositions')
            {
                $var = $this->submitter['position']; // 发起人职位
            }
            else
            {
                $var = isset($object->{$conditionField}) ? $object->{$conditionField} : ''; // 工作流字段验证
            }

            if(!$var) $result = false;

            $checkFunc = 'check' . $condition->conditionOperator;

            if($conditionOperator == 'include' || $conditionOperator == 'notinclude')
            {
                if(is_array($var))
                {
                    $result = in_array($conditionValue, $var);
                }
                else
                {
                    $result = strpos($var, $conditionValue) !== false;
                }

                if($conditionOperator == 'notinclude') $result = !$result;
            }
            else
            {
                $result = validater::$checkFunc($var, $conditionValue);
            }

            if($index == 1) $enabled = $result;
            if($index > 1)
            {
                $logicalOperator = zget($condition, 'conditionLogical', 'or');

                if($logicalOperator == 'and') $enabled = $enabled && $result;
                if($logicalOperator == 'or')  $enabled = $enabled || $result;
            }

            $index ++;
        }

        return $enabled;
    }

    /**
     * Search nodes to confirm.
     *
     * @param  array  $nodes
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $executionID
     * @param  object $object
     * @access public
     * @return array
     */
    public function searchNodesToConfirm($nodes, $projectID = 0, $productID = 0, $executionID = 0, $object = null)
    {
        static $projectStakeholders, $project, $product, $execution;

        $user    = $this->loadModel('user')->fetchByID($this->app->user->id);
        $upLevel = $this->dao->select('manager')->from(TABLE_DEPT)->where('id')->eq($user->dept)->fetch('manager');

        /* Get users of all roles. */
        $roles = $this->dao->select('id,users')->from(TABLE_APPROVALROLE)->where('deleted')->eq(0)->fetchPairs();
        foreach($roles as $id => $users) $roles[$id] = explode(',', trim($users, ','));

        $userGroupByDept = $this->dao->select('account,dept')->from(TABLE_USER)->where('deleted')->eq(0)->fetchGroup('dept', 'account');
        $userGroupByRole = $this->dao->select('account,role')->from(TABLE_USER)->where('deleted')->eq(0)->fetchGroup('role', 'account');

        $results = array();
        foreach($nodes as $node)
        {
            if($node->type == 'branch')
            {
                $exeDefault = true; // Need execute default branch ?
                foreach($node->branches as $branch)
                {
                    if(!$this->checkCondition($branch->conditions, $object)) continue;

                    $results = array_merge($results, $this->searchNodesToConfirm($branch->nodes, $projectID, $productID, $executionID, $object));
                    if($node->branchType != 'parallel')
                    {
                        $exeDefault = false;
                        break;
                    }
                }
                if($exeDefault) $results = array_merge($results, $this->searchNodesToConfirm($node->default->nodes, $projectID, $productID, $executionID, $object));
            }
            else
            {
                $result = array('types' => array());
                if(in_array($node->type, array('start', 'end')))
                {
                    $result['id']    = $node->type;
                    $result['title'] = $this->lang->approvalflow->nodeTypeList[$node->type];
                }
                else
                {
                    $result['id']    = $node->id;
                    $result['title'] = isset($node->title) ? $node->title : $this->lang->approvalflow->nodeTypeList[$node->type];
                }

                if(isset($node->reviewers) && !empty($node->reviewers))
                {
                    foreach($node->reviewers as $reviewer)
                    {
                        if(!isset($reviewer->type)) continue;
                        if($reviewer->type == 'select')
                        {
                            $result['types'][] = 'reviewer';
                            if(!isset($reviewer->userRange)) continue;

                            if($reviewer->userRange != 'all')  $result['range']['reviewer'] = array();
                            if($reviewer->userRange == 'user') $result['range']['reviewer'] = $reviewer->users;
                            if($reviewer->userRange == 'role')
                            {
                                if(empty($reviewer->roles)) continue;
                                foreach($reviewer->roles as $role)
                                {
                                    $rangeUsers = zget($roles, $role, array());
                                    $result['range']['reviewer'] = array_merge($result['range']['reviewer'], $rangeUsers);
                                }
                            }
                            if($reviewer->userRange == 'dept')
                            {
                                if(empty($reviewer->depts)) continue;
                                foreach($reviewer->depts as $dept)
                                {
                                    $rangeUsers = zget($userGroupByDept, $dept, array());
                                    $result['range']['reviewer'] = array_merge($result['range']['reviewer'], array_keys($rangeUsers));
                                }
                            }
                            if($reviewer->userRange == 'position')
                            {
                                if(empty($reviewer->positions)) continue;
                                foreach($reviewer->positions as $position)
                                {
                                    $rangeUsers = zget($userGroupByRole, $position, array());
                                    $result['range']['reviewer'] = array_merge($result['range']['reviewer'], array_keys($rangeUsers));
                                }
                            }
                        }
                        if($reviewer->type == 'appointee')
                        {
                            if(!isset($result['appointees']['reviewer'])) $result['appointees']['reviewer'] = array();
                            $result['appointees']['reviewer'] = array_merge($result['appointees']['reviewer'], $reviewer->users);
                        }

                        if($reviewer->type == 'self')         $result['self']['reviewer'][]       = $user->account;
                        if($reviewer->type == 'upLevel')      $result['upLevel']['reviewer'][]    = $upLevel ? $upLevel : '';
                        if($reviewer->type == 'superior')     $result['superior']['reviewer'][]   = $user->superior;
                        if($reviewer->type == 'superiorList') $result['superiorList']['reviewer'] = $this->getSuperiorList($this->app->user->account, $reviewer->superiorList);

                        if($reviewer->type == 'role')
                        {
                            if(!isset($result['role']['reviewer'])) $result['role']['reviewer'] = array();
                            foreach($reviewer->roles as $role) $result['role']['reviewer'] = array_merge($result['role']['reviewer'], zget($roles, $role, array()));
                        }
                        if($reviewer->type == 'position')
                        {
                            if(!isset($result['position']['reviewer'])) $result['position']['reviewer'] = array();
                            foreach($reviewer->positions as $position) $result['position']['reviewer'] = array_merge($result['position']['reviewer'], array_keys(zget($userGroupByRole, $position, array())));
                        }
                        if($reviewer->type == 'projectRole')
                        {
                            if(!isset($result['projectRole']['reviewer'])) $result['projectRole']['reviewer'] = array();
                            if($projectID && !$project) $project = $this->loadModel('project')->fetchByID($projectID);
                            if($project)
                            {
                                foreach($reviewer->projectRoles as $projectRole)
                                {
                                    if($projectRole == 'PM')
                                    {
                                        if(!empty($project->{$projectRole})) $result['projectRole']['reviewer'][] = $project->{$projectRole};
                                    }
                                    elseif($projectRole == 'stakeholder')
                                    {
                                        if(!$projectStakeholders) $projectStakeholders = $this->dao->select('user')->from(TABLE_STAKEHOLDER)->where('objectType')->eq('project')->andWhere('objectID')->eq($projectID)->andWhere('deleted')->eq('0')->fetchPairs();
                                        $result['projectRole']['reviewer'] = array_merge($result['projectRole']['reviewer'], array_values($projectStakeholders));
                                    }
                                }
                            }
                        }
                        if($reviewer->type == 'productRole')
                        {
                            if(!isset($result['productRole']['reviewer'])) $result['productRole']['reviewer'] = array();
                            if($productID && !$product) $product = $this->loadModel('product')->fetchByID($productID);
                            if($product)
                            {
                                foreach($reviewer->productRoles as $productRole)
                                {
                                    if($productRole == 'reviewer')
                                    {
                                        $result['productRole']['reviewer'] = array_merge($result['productRole']['reviewer'], array_filter(explode(',', $product->reviewer)));
                                    }
                                    else
                                    {
                                        if(!empty($product->{$productRole})) $result['productRole']['reviewer'][] = $product->{$productRole};
                                    }
                                }
                            }
                        }
                        if($reviewer->type == 'executionRole')
                        {
                            if(!isset($result['executionRole']['reviewer'])) $result['executionRole']['reviewer'] = array();
                            if($executionID && !$execution) $execution = $this->loadModel('execution')->fetchByID($executionID);
                            if($execution)
                            {
                                foreach($reviewer->executionRoles as $executionRole)
                                {
                                    if(!empty($execution->{$executionRole})) $result['executionRole']['reviewer'][] = $execution->{$executionRole};
                                }
                            }

                        }
                    }
                }
                if(isset($node->ccs) && !empty($node->ccs))
                {
                    foreach($node->ccs as $cc)
                    {
                        if(!isset($cc->type)) continue;
                        if($cc->type == 'select')
                        {
                            $result['types'][] = 'ccer';
                            if($cc->userRange != 'all')  $result['range']['ccer'] = array();
                            if($cc->userRange == 'user') $result['range']['ccer'] = $cc->users;
                            if($cc->userRange == 'role')
                            {
                                if(empty($cc->roles)) continue;
                                foreach($cc->roles as $role)
                                {
                                    $rangeUsers = zget($roles, $role, array());
                                    $result['range']['ccer'] = array_merge($result['range']['ccer'], $rangeUsers);
                                }
                            }
                            if(!empty($cc->userRange) && $cc->userRange == 'dept')
                            {
                                if(empty($cc->depts)) continue;
                                foreach($cc->depts as $dept)
                                {
                                    $rangeUsers = zget($userGroupByDept, $dept, array());
                                    $result['range']['ccer'] = array_merge($result['range']['ccer'], array_keys($rangeUsers));
                                }
                            }
                            if($cc->userRange == 'position')
                            {
                                if(empty($cc->positions)) continue;
                                foreach($cc->positions as $position)
                                {
                                    $rangeUsers = zget($userGroupByRole, $position, array());
                                    $result['range']['ccer'] = array_merge($result['range']['ccer'], array_keys($rangeUsers));
                                }
                            }
                        }
                        if($cc->type == 'appointee')
                        {
                            if(!isset($result['appointees']['ccer'])) $result['appointees']['ccer'] = array();
                            $result['appointees']['ccer'] = array_merge($result['appointees']['ccer'], $cc->users);
                        }

                        if($cc->type == 'upLevel')   $result['upLevel']['ccer'][]  = $upLevel ? $upLevel : '';
                        if($cc->type == 'superior')  $result['superior']['ccer'][] = $user->superior;

                        if($cc->type == 'role')
                        {
                            if(!isset($result['role']['ccer'])) $result['role']['ccer'] = array();
                            foreach($cc->roles as $role) $result['role']['ccer'] = array_merge($result['role']['ccer'], zget($roles, $role, array()));
                        }
                        if($cc->type == 'position')
                        {
                            if(!isset($result['position']['ccer'])) $result['position']['ccer'] = array();
                            foreach($cc->positions as $position) $result['position']['ccer'] = array_merge($result['position']['ccer'], array_keys(zget($userGroupByRole, $position, array())));
                        }
                    }
                }

                $hasResult = false;
                if(isset($result['appointees']))    $hasResult = true;
                if(isset($result['self']))          $hasResult = true;
                if(isset($result['upLevel']))       $hasResult = true;
                if(isset($result['superior']))      $hasResult = true;
                if(isset($result['role']))          $hasResult = true;
                if(isset($result['position']))      $hasResult = true;
                if(isset($result['projectRole']))   $hasResult = true;
                if(isset($result['productRole']))   $hasResult = true;
                if(isset($result['executionRole'])) $hasResult = true;
                if(isset($result['superiorList']))  $hasResult = true;

                if(count($result['types']) >= 1 or $hasResult) $results[] = $result;
            }
        }

        return $results;
    }

    /**
     * 验证审批人字段规则。
     * Check reviewer rule.
     *
     * @param  array  $reviewers
     * @param  object $node
     * @param  string $createdBy
     * @param  array  $deletedUsers
     * @access public
     * @return array
     */
    public function checkReviewerRule($reviewers, $node, $createdBy, $deletedUsers)
    {
        $reviewers = array_filter($reviewers);

        /* If reviewers is initiator, use agent. */
        if(!empty($reviewers) && in_array($createdBy, $reviewers) && isset($node->selfType))
        {
            $user = $this->loadModel('user')->getById($createdBy, 'account');
            $key  = array_search($createdBy, $reviewers);
            if($key !== false) unset($reviewers[$key]);
            switch($node->selfType)
            {
                case 'selfNext':
                    $reviewers[] = !empty($user->superior) ? $user->superior : '';
                    break;
                case 'selfManager':
                    $upLevel     = $this->dao->select('manager')->from(TABLE_DEPT)->where('id')->eq($user->dept)->fetch('manager');
                    $reviewers[] = $upLevel ? $upLevel : '';
                    break;
                default:
                    $reviewers[] = $createdBy;
                    break;
            }
        }

        if(!empty($reviewers) && isset($node->deletedType))
        {
            foreach($reviewers as $key => $reviewer)
            {
                if(in_array($reviewer, $deletedUsers))
                {
                    $user = $this->loadModel('user')->getById($reviewer, 'account');
                    switch($node->deletedType)
                    {
                        case 'setUser':
                            $reviewer = $node->setUser;
                            break;
                        case 'setSuperior':
                            $reviewer = !empty($user->superior) ? $user->superior : '';
                            break;
                        case 'setManager':
                            $upLevel  = $this->dao->select('manager')->from(TABLE_DEPT)->where('id')->eq($user->dept)->fetch('manager');
                            $reviewer = $upLevel && $reviewer != $upLevel ? $upLevel : '';
                            break;
                        case 'setAdmin':
                            $admins = explode(',', trim($this->app->company->admins, ','));
                            $reviewer = $admins[0];
                            break;
                        default:
                            break;
                    }
                    $reviewers[$key] = $reviewer;
                }
            }
        }

        /* If reviewers is empty, use agent. */
        if(empty($reviewers) && isset($node->agentType))
        {
            switch($node->agentType)
            {
                case 'appointee':
                    $reviewers[] = $node->agentUser;
                    break;
                case 'admin':
                    $admins = explode(',', trim($this->app->company->admins, ','));
                    $reviewers[] = $admins[0];
                    break;
                default:
                    $reviewers[] = '';
                    break;
            }
        }

        return $reviewers;
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  object  $flow
     * @param  string  $action
     *
     * @access public
     * @return bool
     */
    public static function isClickable($issue, $action)
    {
        global $config;
        $action = strtolower($action);

        if($action == 'delete') return !$issue->code && in_array($config->edition, array('max', 'ipd'));
        if($action == 'edit')   return !$issue->code && in_array($config->edition, array('max', 'ipd'));

        return true;
    }
}
