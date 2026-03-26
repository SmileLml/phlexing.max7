#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
su('admin');

zenData('task')->loadYaml('task', true)->gen(9);
zenData('taskspec')->loadYaml('taskspec', true)->gen(7);

/**

title=taskModel->getTaskSpec();
timeout=0
cid=1


*/

$taskIdList  = range(1, 6);
$versionList = range(1, 3);

$taskModel = $tester->loadModel('task');
r($taskModel->getTaskSpec($taskIdList[0], $versionList[0])) && p('name') && e('任务1');   // 测试获取taskID=1,版本号为1的任务信息
r($taskModel->getTaskSpec($taskIdList[0], $versionList[1])) && p('name') && e('任务1A');  // 测试获取taskID=1,版本号为2的任务信息
r($taskModel->getTaskSpec($taskIdList[2], $versionList[1])) && p('name') && e('任务3B');  // 测试获取taskID=3,版本号为2的任务信息
r($taskModel->getTaskSpec($taskIdList[3], $versionList[2])) && p('name') && e('0');       // 测试获取taskID=4,版本号为3（不存在）的任务信息
r($taskModel->getTaskSpec($taskIdList[5], $versionList[0])) && p('name') && e('0');       // 测试获取taskID=6（不存在）,版本号为1的任务信息
