<?php
$lang->message->common     = '系統通知';
$lang->message->index      = '首頁';
$lang->message->setting    = '設置';
$lang->message->browser    = '系統通知';
$lang->message->blockUser  = '不通知人員';
$lang->message->markUnread = '標為未讀';

$lang->message->typeList['mail']     = '郵件';
$lang->message->typeList['message']  = '系統通知';
$lang->message->typeList['webhook']  = 'Webhook';

$lang->message->browserSetting = new stdclass();
$lang->message->browserSetting->turnon   = '是否打開';
$lang->message->browserSetting->pollTime = '輪詢時間';

$lang->message->browserSetting->pollTimeTip         = '輪詢時間不能小於30秒。';
$lang->message->browserSetting->pollTimePlaceholder = '通知的時間間隔，以秒為單位。';

$lang->message->browserSetting->turnonList[1] = '打開';
$lang->message->browserSetting->turnonList[0] = '關閉';

$lang->message->browserSetting->more    = '更多設置';
$lang->message->browserSetting->show    = '瀏覽器通知';
$lang->message->browserSetting->count   = '計數提醒';
$lang->message->browserSetting->maxDays = '保留天數';

$lang->message->unread = '未讀消息(%s)';
$lang->message->all    = '全部消息';

$lang->message->timeLabel['minute'] = '%s分鐘前';
$lang->message->timeLabel['hour']   = '1小時前';

$lang->message->notice = new stdclass();
$lang->message->notice->allMarkRead = '一鍵已讀';
$lang->message->notice->clearRead   = '清空已讀';

$lang->message->error = new stdclass();
$lang->message->error->maxDaysFormat  = '保留天數只能填寫正整數';
$lang->message->error->maxDaysValue   = '保留天數不能小於0。';

$lang->message->label = new stdclass();
$lang->message->label->created      = '創建';
$lang->message->label->opened       = '創建';
$lang->message->label->changed      = '變更';
$lang->message->label->releaseddoc  = '發佈';
$lang->message->label->edited       = '編輯';
$lang->message->label->assigned     = '指派';
$lang->message->label->closed       = '關閉';
$lang->message->label->deleted      = '刪除';
$lang->message->label->undeleted    = '還原';
$lang->message->label->commented    = '備註';
$lang->message->label->activated    = '激活';
$lang->message->label->resolved     = '解決';
$lang->message->label->submitreview = '提交評審';
$lang->message->label->reviewed     = '評審';
$lang->message->label->confirmed    = "確認{$lang->SRCommon}";
$lang->message->label->frombug      = "轉{$lang->SRCommon}";
$lang->message->label->started      = '開始';
$lang->message->label->delayed      = '延期';
$lang->message->label->suspended    = '掛起';
$lang->message->label->finished     = '完成';
$lang->message->label->paused       = '暫停';
$lang->message->label->canceled     = '取消';
$lang->message->label->restarted    = '繼續';
$lang->message->label->blocked      = '阻塞';
$lang->message->label->bugconfirmed = '確認';
$lang->message->label->compilepass  = '構建通過';
$lang->message->label->compilefail  = '構建失敗';
$lang->message->label->archived     = '歸檔';
$lang->message->label->restore      = '還原';
$lang->message->label->moved        = '移動';
$lang->message->label->published    = '發佈';
$lang->message->label->changestatus = '修改發佈狀態';
