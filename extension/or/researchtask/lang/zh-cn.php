<?php
global $app;
$app->loadLang('task');
$lang->researchtask = clone $lang->task;
$lang->researchtask->common    = '调研任务';
$lang->researchtask->name      = '调研任务名称';
$lang->researchtask->execution = '所属阶段';

$lang->researchtask->afterChoices = array();
$lang->researchtask->afterChoices['continueAdding'] = "继续添加任务";
$lang->researchtask->afterChoices['toTaskList']     = '返回任务列表';
