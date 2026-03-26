<?php
/* Fields. */
$lang->trainplan->common          = '培训计划';
$lang->trainplan->id              = 'ID';
$lang->trainplan->name            = '培训名称';
$lang->trainplan->place           = '地点';
$lang->trainplan->datePlan        = '日程规划';
$lang->trainplan->begin           = '开始时间';
$lang->trainplan->end             = '结束时间';
$lang->trainplan->trainee         = '培训人员';
$lang->trainplan->lecturer        = '讲师';
$lang->trainplan->type            = '培训类型';
$lang->trainplan->status          = '状态';
$lang->trainplan->summary         = '培训总结';
$lang->trainplan->createdBy       = '由谁创建';
$lang->trainplan->createdDate     = '创建日期';
$lang->trainplan->editedBy        = '由谁编辑';
$lang->trainplan->editedDate      = '最后编辑';
$lang->trainplan->legendBasicInfo = '基本信息';
$lang->trainplan->legendLifeTime  = '培训计划的一生';

/* Actions. */
$lang->trainplan->browse        = '培训计划列表';
$lang->trainplan->create        = '添加培训计划';
$lang->trainplan->edit          = '编辑培训计划';
$lang->trainplan->batchCreate   = '批量创建';
$lang->trainplan->batchEdit     = '批量编辑';
$lang->trainplan->view          = '培训计划详情';
$lang->trainplan->finish        = '完成';
$lang->trainplan->batchFinish   = '批量完成';
$lang->trainplan->byQuery       = '搜索';
$lang->trainplan->delete        = '删除培训计划';
$lang->trainplan->deleted       = '已删除';
$lang->trainplan->finishAction  = '完成培训计划';
$lang->trainplan->summaryAction = '提交培训总结';

$lang->trainplan->typeList = array();
$lang->trainplan->typeList['inside']  = '内部培训';
$lang->trainplan->typeList['outside'] = '外部培训';

$lang->trainplan->statusList = array();
$lang->trainplan->statusList['wait'] = '未开始';
$lang->trainplan->statusList['done'] = '已完成';

$lang->trainplan->featureBar['browse']['all']  = '全部';
$lang->trainplan->featureBar['browse']['wait'] = '未开始';
$lang->trainplan->featureBar['browse']['done'] = '已完成';

$lang->trainplan->action = new stdclass();
$lang->trainplan->action->commitsummary = '$date, 由 <strong>$actor</strong> 提交培训总结。' . "\n";
$lang->trainplan->action->updatetrainee = '$date, 由 <strong>$actor</strong> 更新培训人员。' . "\n";

$lang->trainplan->confirmDelete  = '您确认删除该培训计划吗？';
$lang->trainplan->notAllowedEdit = '您选中的培训计划都已完成，不能再进行编辑操作。';
$lang->trainplan->endSmall       = '"结束日期"不小于"开始日期"';
