<?php
/* Fields */
$lang->meeting->common          = '會議';
$lang->meeting->id              = 'ID';
$lang->meeting->project         = '所屬' . $lang->projectCommon;
$lang->meeting->name            = '會議名稱';
$lang->meeting->type            = '會議類型';
$lang->meeting->datePlan        = '會議時間';
$lang->meeting->begin           = '開始時間';
$lang->meeting->end             = '結束時間';
$lang->meeting->dept            = '所屬部門';
$lang->meeting->mode            = '會議模式';
$lang->meeting->objectType      = '關聯類型';
$lang->meeting->execution       = '所屬' . $lang->execution->common;
$lang->meeting->objectID        = '關聯對象';
$lang->meeting->host            = '主持人';
$lang->meeting->participant     = '參會人員';
$lang->meeting->date            = '會議日期';
$lang->meeting->files           = '附件';
$lang->meeting->room            = '會議室';
$lang->meeting->minutes         = '會議記錄';
$lang->meeting->minutedBy       = '由誰記錄';
$lang->meeting->minutedDate     = '記錄日期';
$lang->meeting->createdBy       = '由誰創建';
$lang->meeting->createdDate     = '創建日期';
$lang->meeting->editedBy        = '由誰編輯';
$lang->meeting->editedDate      = '編輯日期';
$lang->meeting->legendBasicInfo = '基本信息';
$lang->meeting->legendLifeTime  = '會議的一生';
$lang->meeting->deleted         = '已刪除';
$lang->meeting->linked          = '相關';
$lang->meeting->confirmDelete   = '您確認刪除該會議嗎？';
$lang->meeting->notOpenTime     = '您選擇的會議室在當前日期不開放。';
$lang->meeting->booked          = '您選擇的會議室在當前時間段已被預約。';
$lang->meeting->errorBegin      = '開始時間必須小於結束時間。';
$lang->meeting->errorTime       = '『%s』應當為合法的時間';

/* Actions */
$lang->meeting->batchCreate  = '批量添加';
$lang->meeting->create       = '添加會議';
$lang->meeting->edit         = '編輯會議';
$lang->meeting->browse       = '會議列表';
$lang->meeting->view         = '會議詳情';
$lang->meeting->delete       = '刪除';
$lang->meeting->byQuery      = '搜索';
$lang->meeting->deleteAction = '刪除會議';

$lang->meeting->statusList[''] = '';
$lang->meeting->statusList['wait']  = '未開始';
$lang->meeting->statusList['doing'] = '進行中';
$lang->meeting->statusList['done']  = '已結束';

$lang->meeting->modeList[''] = '';
$lang->meeting->modeList['online']  = '線上';
$lang->meeting->modeList['outline'] = '綫下';
$lang->meeting->modeList['both']    = '線上+綫下';

$lang->meeting->featureBar['browse']['all']         = '全部';
$lang->meeting->featureBar['browse']['booked']      = '我預約的';
$lang->meeting->featureBar['browse']['participate'] = '我參加的';
