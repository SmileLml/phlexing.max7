<?php
$lang->story->BSA                = 'BSA';
$lang->story->duration           = '管理周期';
$lang->story->demandInfo         = '需求池需求信息';
$lang->story->batchChangeRoadmap = '批量修改路標';
$lang->story->submitedBy         = '提交評審人';
$lang->story->retractedBy        = '由誰撤銷';
$lang->story->retractedDate      = '撤銷時間';

$lang->story->confirmDemandRetract = '確認撤回';
$lang->story->confirmDemandUnlink  = '確認移除';
$lang->story->confirmRetractTip    = '該%s相關的用戶需求因執行撤回分發操作已經關閉，請知悉。';
$lang->story->confirmUnlinkTip     = '該%s相關的用戶需求已取消立項，請知悉。';

$lang->requirement->batchChangeRoadmap = '批量修改路標';
$lang->requirement->title              = "{$lang->URCommon}名稱";

$lang->story->URChangeTip = "只有已立項、研發中狀態的{$lang->URCommon}，才能進行變更";

$lang->story->action->retractclosed = array('main' => '$date, 由 <strong>$actor</strong> 執行撤回操作關閉，原因為 <strong>$extra</strong>。', 'extra' => 'reasonList');
$lang->story->action->distributed   = array('main' => '$date, 由 <strong>$actor</strong> 從需求池需求分發而來，需求池需求編號為 <strong>$extra</strong>。');
