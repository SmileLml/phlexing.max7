<?php
$lang->demandpool->create   = '创建需求池';
$lang->demandpool->browse   = '需求池列表';
$lang->demandpool->edit     = '编辑';
$lang->demandpool->view     = '需求池概况';
$lang->demandpool->delete   = '删除';
$lang->demandpool->close    = '关闭';
$lang->demandpool->activate = '激活';
$lang->demandpool->comment  = '备注';
$lang->demandpool->track    = '需求池矩阵';
$lang->demandpool->demand   = '需求';

$lang->demandpool->id           = '编号';
$lang->demandpool->idAB         = 'ID';
$lang->demandpool->code         = '序号';
$lang->demandpool->category     = '需求分类';
$lang->demandpool->owner        = '负责人';
$lang->demandpool->pri          = '优先级';
$lang->demandpool->dept         = '所属部门';
$lang->demandpool->status       = '状态';
$lang->demandpool->name         = '需求池名称';
$lang->demandpool->createdBy    = '由谁创建';
$lang->demandpool->createdDate  = '创建时间';
$lang->demandpool->desc         = '描述';
$lang->demandpool->num          = '需求数量';
$lang->demandpool->assignedTo   = '指派给';
$lang->demandpool->reviewer     = '评审人';
$lang->demandpool->myDemand     = '我负责';
$lang->demandpool->other        = '其他';
$lang->demandpool->doneDemands  = '已关闭';
$lang->demandpool->info         = '信息';
$lang->demandpool->basicInfo    = '基本信息';
$lang->demandpool->activateAB   = '激活';
$lang->demandpool->closeAB      = '关闭';
$lang->demandpool->deleted      = '已删除';
$lang->demandpool->products     = '绑定产品';
$lang->demandpool->all          = '全部需求池';

$lang->demandpool->noData           = '暂无数据，';
$lang->demandpool->needDistribute   = '待分发';
$lang->demandpool->closedDemandpool = '已关闭的需求池';
$lang->demandpool->hasReview        = '当前人员有待评审的需求，无法删除。';

$lang->demandpool->accessDenied = "您无权访问该需求池！";

$lang->demandpool->statusList[''] = '';
$lang->demandpool->statusList['normal'] = '正常';
$lang->demandpool->statusList['closed'] = '已关闭';

$lang->demandpool->labelList = array();
$lang->demandpool->labelList['all']    = '全部';
$lang->demandpool->labelList['mine']   = '我负责';
$lang->demandpool->labelList['normal'] = '正常';
$lang->demandpool->labelList['closed'] = '已关闭';

$lang->demandpool->colList = array();
$lang->demandpool->colList['draft']       = '草稿';
$lang->demandpool->colList['reviewing']   = '评审中';
$lang->demandpool->colList['wait']        = '待分发';
$lang->demandpool->colList['willCharter'] = '待立项';
$lang->demandpool->colList['inCharter']   = 'Charter立项';

$lang->demandpool->acl = '访问控制';

$lang->demandpool->aclList['open']    = '公开（有需求池视图权限，即可访问）';
$lang->demandpool->aclList['private'] = '私有（需求池负责人、评审人可以访问）';

$lang->demandpool->confirmDelete    = '您确认删除该需求池吗？';
$lang->demandpool->hasDemand        = '需求池中已有需求，不能删除，请先转移或删除需求后再操作。';
$lang->demandpool->bindProductsTips = '绑定产品后，需求只能分发到绑定的产品中';
