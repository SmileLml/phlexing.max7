<?php
global $app;
$app->loadLang('task');
$lang->researchtask = clone $lang->task;
$lang->researchtask->common    = '調研任務';
$lang->researchtask->name      = '調研任務名稱';
$lang->researchtask->execution = '所屬階段';

$lang->researchtask->afterChoices = array();
$lang->researchtask->afterChoices['continueAdding'] = "繼續添加任務";
$lang->researchtask->afterChoices['toTaskList']     = '返回任務列表';
