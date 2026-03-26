<?php
$lang->execution->template        = $lang->projectCommon . '模版';
$lang->execution->finish          = '完成';
$lang->execution->program         = '所属' . $lang->projectCommon;
$lang->execution->taskCount       = '任务数量';
$lang->execution->deliverable     = '维护交付物';
$lang->execution->deliverableAbbr = '交付物';
$lang->execution->whenClosedTips  = '（执行未关闭时，不会对关闭时的交付物进行严格校验）';

$lang->execution->enter = '进入';
$lang->execution->draft = '草稿';

$lang->execution->cannotCloseByDeliverable = "部分执行已关闭，进行中的执行因未提交交付物无法批量关闭。\n 以下执行无法关闭：\n %s";
$lang->execution->closeExecutionError      = "无法关闭未提交交付物的执行。";
$lang->execution->notClose                 = "无法关闭该执行";
$lang->execution->cannotAutoCloseParent    = "检测到父执行有未上传的交付物，无法自动关闭，是否手动关闭父执行？";

$lang->execution->action->managedeliverable = '$date, 由 <strong>$actor</strong> 维护交付物。' . "\n";
