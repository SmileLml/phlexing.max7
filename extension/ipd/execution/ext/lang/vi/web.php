<?php
$lang->execution->webMenu = new stdclass();
$lang->execution->webMenu->task  = array('link' => "Nhiệm vụ|execution|task|executionID=%s", 'subModule' => 'task', 'alias' => 'importtask,importbug,tree');
$lang->execution->webMenu->story = "Câu chuyện|execution|story|executionID=%s";
$lang->execution->webMenu->bug   = "Bug|execution|bug|executionID=%s";
$lang->execution->webMenu->build = array('link' => "Bản dựng|execution|build|executionID=%s", 'subModule' => 'build');
$lang->execution->webMenu->testtask = "Test nhiệm vụ|execution|testtask|executionID=%s";
$lang->execution->webMenu->team  = "Đội nhóm|execution|team|executionID=%s";
$lang->execution->webMenu->action   = "Lịch sử|execution|dynamic|executionID=%s";
$lang->execution->webMenu->view  = "Tổng quan|execution|view|executionID=%s";
$lang->execution->webMenu->all   = "Tất cả {$lang->executionCommon}|execution|all|";

$lang->execution->webMenuOrder[5]  = "task";
$lang->execution->webMenuOrder[10] = "story";
$lang->execution->webMenuOrder[15] = "bug";
$lang->execution->webMenuOrder[20] = "build";
$lang->execution->webMenuOrder[25] = "testtask";
$lang->execution->webMenuOrder[30] = "team";
$lang->execution->webMenuOrder[35] = "action";
$lang->execution->webMenuOrder[40] = "view";
$lang->execution->webMenuOrder[45] = "all";
