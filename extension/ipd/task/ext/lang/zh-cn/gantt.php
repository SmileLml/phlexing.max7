<?php
$lang->task->gantt                = new stdclass();
$lang->task->gantt->notice        = new stdclass();
$lang->task->gantt->notice->notSS = "任务：“%s”开始之后，该任务才能开始！";
$lang->task->gantt->notice->notFS = "任务：“%s”结束之后，该任务才能开始！";
$lang->task->gantt->notice->notSF = "任务：“%s”开始之后，该任务才能结束！";
$lang->task->gantt->notice->notFF = "任务：“%s”结束之后，该任务才能结束！";

$lang->task->unlinkRelationTip = new stdclass();
$lang->task->unlinkRelationTip->cancel = "建立任务关系的任务不支持取消，需解除任务关系后，才能保存。";
$lang->task->unlinkRelationTip->parent = "父级任务不支持建立任务关系，解除父级任务关系后，才能保存当前任务。";
$lang->task->unlinkRelationTip->split  = "父级任务不支持建立任务关系，解除父级任务关系后，才能保存子级任务。";

$lang->task->unlink = '解除';
