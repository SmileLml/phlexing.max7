<?php
$lang->story->BSA                = 'BSA';
$lang->story->duration           = '管理周期';
$lang->story->demandInfo         = '需求池需求信息';
$lang->story->batchChangeRoadmap = '批量修改路标';
$lang->story->submitedBy         = '提交评审人';
$lang->story->retractedBy        = '由谁撤销';
$lang->story->retractedDate      = '撤销时间';

$lang->story->confirmDemandRetract = '确认撤回';
$lang->story->confirmDemandUnlink  = '确认移除';
$lang->story->confirmRetractTip    = '该%s相关的用户需求因执行撤回分发操作已经关闭，请知悉。';
$lang->story->confirmUnlinkTip     = '该%s相关的用户需求已取消立项，请知悉。';

$lang->requirement->batchChangeRoadmap = '批量修改路标';
$lang->requirement->title              = "{$lang->URCommon}名称";

$lang->story->URChangeTip = "只有已立项、研发中状态的{$lang->URCommon}，才能进行变更";

$lang->story->action->retractclosed = array('main' => '$date, 由 <strong>$actor</strong> 执行撤回操作关闭，原因为 <strong>$extra</strong>。', 'extra' => 'reasonList');
$lang->story->action->distributed   = array('main' => '$date, 由 <strong>$actor</strong> 从需求池需求分发而来，需求池需求编号为 <strong>$extra</strong>。');
