<?php
$lang->task->gantt                = new stdclass();
$lang->task->gantt->notice        = new stdclass();
$lang->task->gantt->notice->notSS = "Only when \"%s\" is started, this task can be started!";
$lang->task->gantt->notice->notFS = "Only when \"%s\" is finished, this task can be started!";
$lang->task->gantt->notice->notSF = "Only when \"%s\" is started, this task can be finished!";
$lang->task->gantt->notice->notFF = "Only when \"%s\" is finished, this task can be finished!";

$lang->task->unlinkRelationTip = new stdclass();
$lang->task->unlinkRelationTip->cancel = "Tasks with established relationships cannot be canceled; the relationship must be removed before saving.";
$lang->task->unlinkRelationTip->parent = "The parent task does not support creating task relationships. You must remove the parent task relationship before saving the current task.";
$lang->task->unlinkRelationTip->split  = "The parent task does not support creating task relationships. You must remove the parent task relationship before saving the child task.";

$lang->task->unlink = 'Unlink';
