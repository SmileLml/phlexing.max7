<?php
/* Fields */
$lang->meeting->common          = '会议';
$lang->meeting->id              = 'ID';
$lang->meeting->project         = '所属' . $lang->projectCommon;
$lang->meeting->name            = '会议名称';
$lang->meeting->type            = '会议类型';
$lang->meeting->datePlan        = '会议时间';
$lang->meeting->begin           = '开始时间';
$lang->meeting->end             = '结束时间';
$lang->meeting->dept            = '所属部门';
$lang->meeting->mode            = '会议模式';
$lang->meeting->objectType      = '关联类型';
$lang->meeting->execution       = '所属' . $lang->execution->common;
$lang->meeting->objectID        = '关联对象';
$lang->meeting->host            = '主持人';
$lang->meeting->participant     = '参会人员';
$lang->meeting->date            = '会议日期';
$lang->meeting->files           = '附件';
$lang->meeting->room            = '会议室';
$lang->meeting->minutes         = '会议记录';
$lang->meeting->minutedBy       = '由谁记录';
$lang->meeting->minutedDate     = '记录日期';
$lang->meeting->createdBy       = '由谁创建';
$lang->meeting->createdDate     = '创建日期';
$lang->meeting->editedBy        = '由谁编辑';
$lang->meeting->editedDate      = '编辑日期';
$lang->meeting->legendBasicInfo = '基本信息';
$lang->meeting->legendLifeTime  = '会议的一生';
$lang->meeting->deleted         = '已删除';
$lang->meeting->linked          = '相关';
$lang->meeting->confirmDelete   = '您确认删除该会议吗？';
$lang->meeting->notOpenTime     = '您选择的会议室在当前日期不开放。';
$lang->meeting->booked          = '您选择的会议室在当前时间段已被预约。';
$lang->meeting->errorBegin      = '开始时间必须小于结束时间。';
$lang->meeting->errorTime       = '『%s』应当为合法的时间';

/* Actions */
$lang->meeting->batchCreate  = '批量添加';
$lang->meeting->create       = '添加会议';
$lang->meeting->edit         = '编辑会议';
$lang->meeting->browse       = '会议列表';
$lang->meeting->view         = '会议详情';
$lang->meeting->delete       = '删除';
$lang->meeting->byQuery      = '搜索';
$lang->meeting->deleteAction = '删除会议';

$lang->meeting->statusList[''] = '';
$lang->meeting->statusList['wait']  = '未开始';
$lang->meeting->statusList['doing'] = '进行中';
$lang->meeting->statusList['done']  = '已结束';

$lang->meeting->modeList[''] = '';
$lang->meeting->modeList['online']  = '线上';
$lang->meeting->modeList['outline'] = '线下';
$lang->meeting->modeList['both']    = '线上+线下';

$lang->meeting->featureBar['browse']['all']         = '全部';
$lang->meeting->featureBar['browse']['booked']      = '我预约的';
$lang->meeting->featureBar['browse']['participate'] = '我参加的';
