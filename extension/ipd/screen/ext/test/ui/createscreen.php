#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/createscreen.ui.class.php';

$tester = new createScreenTester();
$tester->login();

$screen = new stdClass();
$screen->name = '';
r($tester->createScreen($screen)) && p('message,status') && e('大屏名称必填项校验正确,SUCCESS');// 大屏名称必填项校验

$screen->name = '自动化大屏';
$screen->desc = '大屏描述';
r($tester->createScreen($screen)) && p('message,status') && e('创建大屏成功,SUCCESS'); // 创建大屏

$tester->closeBrowser();
