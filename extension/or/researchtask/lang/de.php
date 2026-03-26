<?php
global $app;
$app->loadLang('task');
$lang->researchtask = clone $lang->task;
$lang->researchtask->common    = 'Research Task';
$lang->researchtask->name      = 'Research Task Name';
$lang->researchtask->execution = 'Stage';

$lang->researchtask->afterChoices = array();
$lang->researchtask->afterChoices['continueAdding'] = 'Continue Adding Tasks';
$lang->researchtask->afterChoices['toTaskList']     = 'Go to Task List';
