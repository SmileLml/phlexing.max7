<?php
/* Fields. */
$lang->trainplan->common          = '培訓計劃';
$lang->trainplan->id              = 'ID';
$lang->trainplan->name            = '培訓名稱';
$lang->trainplan->place           = '地點';
$lang->trainplan->datePlan        = '日程規劃';
$lang->trainplan->begin           = '開始時間';
$lang->trainplan->end             = '結束時間';
$lang->trainplan->trainee         = '培訓人員';
$lang->trainplan->lecturer        = '講師';
$lang->trainplan->type            = '培訓類型';
$lang->trainplan->status          = '狀態';
$lang->trainplan->summary         = '培訓總結';
$lang->trainplan->createdBy       = '由誰創建';
$lang->trainplan->createdDate     = '創建日期';
$lang->trainplan->editedBy        = '由誰編輯';
$lang->trainplan->editedDate      = '最後編輯';
$lang->trainplan->legendBasicInfo = '基本信息';
$lang->trainplan->legendLifeTime  = '培訓計劃的一生';

/* Actions. */
$lang->trainplan->browse        = '培訓計劃列表';
$lang->trainplan->create        = '添加培訓計劃';
$lang->trainplan->edit          = '編輯培訓計劃';
$lang->trainplan->batchCreate   = '批量創建';
$lang->trainplan->batchEdit     = '批量編輯';
$lang->trainplan->view          = '培訓計劃詳情';
$lang->trainplan->finish        = '完成';
$lang->trainplan->batchFinish   = '批量完成';
$lang->trainplan->byQuery       = '搜索';
$lang->trainplan->delete        = '刪除培訓計劃';
$lang->trainplan->deleted       = '已刪除';
$lang->trainplan->finishAction  = '完成培訓計劃';
$lang->trainplan->summaryAction = '提交培訓總結';

$lang->trainplan->typeList = array();
$lang->trainplan->typeList['inside']  = '內部培訓';
$lang->trainplan->typeList['outside'] = '外部培訓';

$lang->trainplan->statusList = array();
$lang->trainplan->statusList['wait'] = '未開始';
$lang->trainplan->statusList['done'] = '已完成';

$lang->trainplan->featureBar['browse']['all']  = '全部';
$lang->trainplan->featureBar['browse']['wait'] = '未開始';
$lang->trainplan->featureBar['browse']['done'] = '已完成';

$lang->trainplan->action = new stdclass();
$lang->trainplan->action->commitsummary = '$date, 由 <strong>$actor</strong> 提交培訓總結。' . "\n";
$lang->trainplan->action->updatetrainee = '$date, 由 <strong>$actor</strong> 更新培訓人員。' . "\n";

$lang->trainplan->confirmDelete  = '您確認刪除該培訓計劃嗎？';
$lang->trainplan->notAllowedEdit = '您選中的培訓計劃都已完成，不能再進行編輯操作。';
$lang->trainplan->endSmall       = '"結束日期"不小於"開始日期"';
