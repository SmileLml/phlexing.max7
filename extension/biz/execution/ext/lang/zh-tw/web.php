<?php
$lang->execution->webMenu = new stdclass();
$lang->execution->webMenu->task     = array('link' => "任務|execution|task|executionID=%s", 'subModule' => 'task', 'alias' => 'importtask,importbug,tree');
$lang->execution->webMenu->story    = "{$lang->SRCommon}|execution|story|executionID=%s";
$lang->execution->webMenu->bug      = "Bug|execution|bug|executionID=%s";
$lang->execution->webMenu->build    = array('link' => "版本|execution|build|executionID=%s", 'subModule' => 'build');
$lang->execution->webMenu->testtask = "測試單|execution|testtask|executionID=%s";
$lang->execution->webMenu->team     = "團隊|execution|team|executionID=%s";
$lang->execution->webMenu->action   = "動態|execution|dynamic|executionID=%s";
$lang->execution->webMenu->view     = "概況|execution|view|executionID=%s";
$lang->execution->webMenu->all      = "所有{$lang->executionCommon}|execution|all|";

$lang->execution->webMenuOrder[5]  = "task";
$lang->execution->webMenuOrder[10] = "story";
$lang->execution->webMenuOrder[15] = "bug";
$lang->execution->webMenuOrder[20] = "build";
$lang->execution->webMenuOrder[25] = "testtask";
$lang->execution->webMenuOrder[30] = "team";
$lang->execution->webMenuOrder[35] = "action";
$lang->execution->webMenuOrder[40] = "view";
$lang->execution->webMenuOrder[45] = "all";
