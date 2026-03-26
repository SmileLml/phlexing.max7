<?php
$lang->task->gantt                = new stdclass();
$lang->task->gantt->notice        = new stdclass();
$lang->task->gantt->notice->notSS = "任務：“%s”開始之後，該任務才能開始！";
$lang->task->gantt->notice->notFS = "任務：“%s”結束之後，該任務才能開始！";
$lang->task->gantt->notice->notSF = "任務：“%s”開始之後，該任務才能結束！";
$lang->task->gantt->notice->notFF = "任務：“%s”結束之後，該任務才能結束！";

$lang->task->unlinkRelationTip = new stdclass();
$lang->task->unlinkRelationTip->cancel = "建立任務關係的任務不支持取消，需解除任務關係後，才能保存。";
$lang->task->unlinkRelationTip->parent = "父級任務不支持建立任務關係，解除父級任務關係後，才能保存當前任務。";
$lang->task->unlinkRelationTip->split  = "父級任務不支持建立任務關係，解除父級任務關係後，才能保存子級任務。";

$lang->task->unlink = '解除';
