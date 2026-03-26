<?php
$lang->approvalflow->browse        = '流程列表';
$lang->approvalflow->create        = '创建审批流';
$lang->approvalflow->edit          = '编辑审批流';
$lang->approvalflow->view          = '审批流详情';
$lang->approvalflow->delete        = '删除审批流';
$lang->approvalflow->design        = '设计审批流';
$lang->approvalflow->roleList      = '审批角色';
$lang->approvalflow->createRole    = '创建角色';
$lang->approvalflow->editRole      = '编辑角色';
$lang->approvalflow->deleteRole    = '删除角色';

$lang->approvalflow->common        = '审批流';
$lang->approvalflow->id            = 'ID';
$lang->approvalflow->name          = '名称';
$lang->approvalflow->createdBy     = '创建人';
$lang->approvalflow->createdDate   = '创建日期';
$lang->approvalflow->noFlow        = '当前没有审批流';
$lang->approvalflow->title         = '标题';
$lang->approvalflow->reviewer      = '审批人';
$lang->approvalflow->workflow      = '绑定工作流';
$lang->approvalflow->ccer          = '抄送人';
$lang->approvalflow->condition     = '条件分支';
$lang->approvalflow->parallel      = '并行分支';
$lang->approvalflow->priv          = '操作权限';
$lang->approvalflow->approval      = '审批流程';
$lang->approvalflow->desc          = '描述';
$lang->approvalflow->basicInfo     = '基本信息';
$lang->approvalflow->confirmDelete = '您确认要删除吗？';
$lang->approvalflow->setNode       = '节点设置';
$lang->approvalflow->select        = '选择';
$lang->approvalflow->needAll       = '需所有人完成评审';
$lang->approvalflow->percent       = '百分比';

$lang->approvalflow->nameList = array();
$lang->approvalflow->nameList['stage']  = '阶段审批';

$lang->approvalflow->nodeTypeList = array();
$lang->approvalflow->nodeTypeList['branch']    = '分支';
$lang->approvalflow->nodeTypeList['condition'] = '条件';
$lang->approvalflow->nodeTypeList['default']   = '默认';
$lang->approvalflow->nodeTypeList['other']     = '其他';
$lang->approvalflow->nodeTypeList['approval']  = '审批';
$lang->approvalflow->nodeTypeList['cc']        = '抄送';
$lang->approvalflow->nodeTypeList['start']     = '发起';
$lang->approvalflow->nodeTypeList['end']       = '结束';

$lang->approvalflow->userTypeList = array();
$lang->approvalflow->userTypeList['cc']        = '抄送人';
$lang->approvalflow->userTypeList['submitter'] = '发起人';
$lang->approvalflow->userTypeList['reviewer']  = '审批人';

$lang->approvalflow->noticeTypeList = array();
$lang->approvalflow->noticeTypeList['setReviewer']     = '设置审批人';
$lang->approvalflow->noticeTypeList['setCondition']    = '设置条件';
$lang->approvalflow->noticeTypeList['addCondition']    = '添加条件分支';
$lang->approvalflow->noticeTypeList['addParallel']     = '添加并行分支';
$lang->approvalflow->noticeTypeList['addCond']         = '添加条件';
$lang->approvalflow->noticeTypeList['addReviewer']     = '添加审批人';
$lang->approvalflow->noticeTypeList['addCC']           = '添加抄送人';
$lang->approvalflow->noticeTypeList['setCC']           = '设置抄送人';
$lang->approvalflow->noticeTypeList['setNode']         = '设置节点';
$lang->approvalflow->noticeTypeList['defaultBranch']   = '所有条件都会执行此流程';
$lang->approvalflow->noticeTypeList['otherBranch']     = '其他条件进入此流程';
$lang->approvalflow->noticeTypeList['conditionOr']     = '不设置条件或者满足其中一个条件即可执行';
$lang->approvalflow->noticeTypeList['when']            = '当';
$lang->approvalflow->noticeTypeList['type']            = '类型';
$lang->approvalflow->noticeTypeList['confirm']         = '确定';
$lang->approvalflow->noticeTypeList['reviewType']      = '审批设置';
$lang->approvalflow->noticeTypeList['ccType']          = '抄送设置';
$lang->approvalflow->noticeTypeList['reviewRange']     = '审批范围';
$lang->approvalflow->noticeTypeList['ccRange']         = '抄送范围';
$lang->approvalflow->noticeTypeList['range']           = '范围';
$lang->approvalflow->noticeTypeList['value']           = '值';
$lang->approvalflow->noticeTypeList['set']             = '设置';
$lang->approvalflow->noticeTypeList['node']            = '节点';
$lang->approvalflow->noticeTypeList['approvalTitle']   = '审批标题';
$lang->approvalflow->noticeTypeList['ccTitle']         = '抄送标题';
$lang->approvalflow->noticeTypeList['multipleType']    = '多人审批时采用的审批方式';
$lang->approvalflow->noticeTypeList['multipleAnd']     = '会签(所有人通过则审批通过)';
$lang->approvalflow->noticeTypeList['multiplePercent'] = '会签(百分比通过则审批通过)';
$lang->approvalflow->noticeTypeList['multipleOr']      = '或签(仅一人通过则审批通过)';
$lang->approvalflow->noticeTypeList['multipleSolicit'] = '征询意见(结果为通过)';
$lang->approvalflow->noticeTypeList['commentType']     = '审批通过时审批意见';
$lang->approvalflow->noticeTypeList['required']        = '必填';
$lang->approvalflow->noticeTypeList['noRequired']      = '不必填';
$lang->approvalflow->noticeTypeList['agentType']       = '当审批人为空时';
$lang->approvalflow->noticeTypeList['agentPass']       = '自动通过';
$lang->approvalflow->noticeTypeList['agentReject']     = '自动拒绝';
$lang->approvalflow->noticeTypeList['agentUser']       = '指定人员';
$lang->approvalflow->noticeTypeList['agentAdmin']      = '管理员';
$lang->approvalflow->noticeTypeList['selfType']        = '当审批人与发起人为同一人时';
$lang->approvalflow->noticeTypeList['selfReview']      = '发起人评审';
$lang->approvalflow->noticeTypeList['selfPass']        = '自动通过';
$lang->approvalflow->noticeTypeList['selfNext']        = '转交直属上级';
$lang->approvalflow->noticeTypeList['selfManager']     = '转交部门主管';
$lang->approvalflow->noticeTypeList['deletedType']     = '当审批人已被删除时';
$lang->approvalflow->noticeTypeList['autoPass']        = '自动通过';
$lang->approvalflow->noticeTypeList['autoReject']      = '自动拒绝';
$lang->approvalflow->noticeTypeList['setUser']         = '指定人员';
$lang->approvalflow->noticeTypeList['setSuperior']     = '转交直属上级';
$lang->approvalflow->noticeTypeList['setManager']      = '转交部门主管';
$lang->approvalflow->noticeTypeList['setAdmin']        = '转交管理员';

$lang->approvalflow->warningList = array();
$lang->approvalflow->warningList['needReview']     = '请保留最少一个审批节点';
$lang->approvalflow->warningList['save']           = '您的修改内容还没有保存，您确定离开吗？';
$lang->approvalflow->warningList['selectUser']     = '请选择人员';
$lang->approvalflow->warningList['selectDept']     = '请选择部门';
$lang->approvalflow->warningList['selectRole']     = '请选择角色';
$lang->approvalflow->warningList['selectPosition'] = '请选择职位';
$lang->approvalflow->warningList['needReviewer']   = '审批人不能为空';
$lang->approvalflow->warningList['needValue']      = '值不能为空';
$lang->approvalflow->warningList['oneSelect']      = '"发起人自选"和"由上一节点审批人指定"只能存在一个';
$lang->approvalflow->warningList['percent']        = '百分比必须在1-100之间，且为整数。';
$lang->approvalflow->warningList['workflow']       = '绑定工作流后，您可以使用其字段配置审批流条件，且仅限于绑定的工作流下使用。';

$lang->approvalflow->userRangeList = array();
$lang->approvalflow->userRangeList['all']      = '不限';
$lang->approvalflow->userRangeList['role']     = '角色';
$lang->approvalflow->userRangeList['dept']     = '部门';
$lang->approvalflow->userRangeList['position'] = '职位';

$lang->approvalflow->reviewTypeList = array();
$lang->approvalflow->reviewTypeList['manual'] = '人工审批';
$lang->approvalflow->reviewTypeList['pass']   = '自动同意';
$lang->approvalflow->reviewTypeList['reject'] = '自动拒绝';

$lang->approvalflow->errorList = array();
$lang->approvalflow->errorList['needReivewer'] = '请填写全部审批人';
$lang->approvalflow->errorList['needCcer']     = '请填写全部抄送人';
$lang->approvalflow->errorList['hasWorkflow']  = '该审批流已绑定工作流，请到工作流-设置审批界面解绑，否则无法删除';

$lang->approvalflow->reviewerTypeList = array();
$lang->approvalflow->reviewerTypeList['select']        = array('name' => '发起人自选',           'options' => 'userRange',      'tips' => '选择范围');
$lang->approvalflow->reviewerTypeList['self']          = array('name' => '发起人本人',           'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['appointee']     = array('name' => '指定人员',             'options' => 'users',          'tips' => '选择人员');
$lang->approvalflow->reviewerTypeList['role']          = array('name' => '角色',                 'options' => 'roles',          'tips' => '选择角色');
$lang->approvalflow->reviewerTypeList['position']      = array('name' => '职位',                 'options' => 'positions',      'tips' => '选择职位');
$lang->approvalflow->reviewerTypeList['upLevel']       = array('name' => '部门负责人',           'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['superior']      = array('name' => '直属上级',             'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['superiorList']  = array('name' => '连续多级上级',         'options' => 'superiorList',   'tips' => '审批终点');
$lang->approvalflow->reviewerTypeList['setByPrev']     = array('name' => '由上一节点审批人指定', 'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['productRole']   = array('name' => '产品角色',             'options' => 'productRoles',   'tips' => '选择角色');
$lang->approvalflow->reviewerTypeList['projectRole']   = array('name' => '项目角色',             'options' => 'projectRoles',   'tips' => '选择角色');
$lang->approvalflow->reviewerTypeList['executionRole'] = array('name' => '执行角色',             'options' => 'executionRoles', 'tips' => '选择角色');

$lang->approvalflow->conditionTypeList = array();
$lang->approvalflow->conditionTypeList['submitUsers']     = '发起人姓名';
$lang->approvalflow->conditionTypeList['submitDepts']     = '发起人从属部门';
$lang->approvalflow->conditionTypeList['submitRoles']     = '发起人所属角色';
$lang->approvalflow->conditionTypeList['submitPositions'] = '发起人所属职位';

$lang->approvalflow->superiorList[0] = '最高上级';
$lang->approvalflow->superiorList[2] = '2级上级';
$lang->approvalflow->superiorList[3] = '3级上级';
$lang->approvalflow->superiorList[4] = '4级上级';
$lang->approvalflow->superiorList[5] = '5级上级';

$lang->approvalflow->productRoleList['PO']       = '产品负责人';
$lang->approvalflow->productRoleList['QD']       = '测试负责人';
$lang->approvalflow->productRoleList['RD']       = '发布负责人';
$lang->approvalflow->productRoleList['feedback'] = '反馈负责人';
$lang->approvalflow->productRoleList['ticket']   = '工单负责人';
$lang->approvalflow->productRoleList['reviewer'] = '需求评审人';

$lang->approvalflow->projectRoleList['PM']          = '项目负责人';
$lang->approvalflow->projectRoleList['stakeholder'] = '项目干系人';

$lang->approvalflow->executionRoleList['PO'] = '产品负责人';
$lang->approvalflow->executionRoleList['PM'] = '执行负责人';
$lang->approvalflow->executionRoleList['QD'] = '测试负责人';
$lang->approvalflow->executionRoleList['RD'] = '发布负责人';

$lang->approvalflow->privList['forward']   = '转交';
$lang->approvalflow->privList['revert']    = '回退';
$lang->approvalflow->privList['addnode']   = '加签';
$lang->approvalflow->privList['withdrawn'] = '评审时发起人撤回';

$lang->approvalflow->required['yes'] = '审批人必填';
$lang->approvalflow->required['no']  = '审批人不必填';

$lang->approvalflow->emptyName       = '名称不能为空！';
$lang->approvalflow->passOverPercent = '系统判断通过人数占比达到%d%%，审批结果为通过';
$lang->approvalflow->failOverPercent = '系统判断通过人数占比未达到%d%%，审批结果为不通过';

$lang->approvalflow->role = new stdclass();
$lang->approvalflow->role->create = '创建角色';
$lang->approvalflow->role->browse = '角色列表';
$lang->approvalflow->role->edit   = '编辑角色';
$lang->approvalflow->role->member = '角色成员';
$lang->approvalflow->role->delete = '删除角色';

$lang->approvalflow->role->name   = '角色名称';
$lang->approvalflow->role->code   = '角色代号';
$lang->approvalflow->role->desc   = '角色描述';
