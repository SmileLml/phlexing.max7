<?php
$lang->execution->template        = $lang->projectCommon . '模版';
$lang->execution->finish          = '完成';
$lang->execution->program         = '所屬' . $lang->projectCommon;
$lang->execution->taskCount       = '任務數量';
$lang->execution->deliverable     = '維護交付物';
$lang->execution->deliverableAbbr = '交付物';
$lang->execution->whenClosedTips  = '（執行未關閉時，不會對關閉時的交付物進行嚴格校驗）';

$lang->execution->enter = '進入';
$lang->execution->draft = '草稿';

$lang->execution->cannotCloseByDeliverable = "部分執行已關閉，進行中的執行因未提交交付物無法批量關閉。\n 以下執行無法關閉：\n %s";
$lang->execution->closeExecutionError      = "無法關閉未提交交付物的執行。";
$lang->execution->notClose                 = "無法關閉該執行";
$lang->execution->cannotAutoCloseParent    = "檢測到父執行有未上傳的交付物，無法自動關閉，是否手動關閉父執行？";

$lang->execution->action->managedeliverable = '$date, 由 <strong>$actor</strong> 維護交付物。' . "\n";
