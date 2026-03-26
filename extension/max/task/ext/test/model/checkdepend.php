#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 7) . '/module/task/test/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('gantt_project')->gen(6);
zenData('task')->loadYaml('gantt_task')->gen(12);
zenData('relationoftasks')->loadYaml('gantt_relationoftasks')->gen(4);

/**

title=taskModel->start();
timeout=0
cid=1

- 没有前置任务的任务。 @0
- 没有前置任务的任务。 @0
- FS关系，前置任务是进行中的任务。 @任务：“2::子任务Aa”结束之后，该任务才能开始！
- FS关系，前置任务是进行中的任务。 @任务：“2::子任务Aa”结束之后，该任务才能结束！
- SS关系，前置任务是未开始的任务。 @任务：“3::子任务Ab”开始之后，该任务才能开始！
- SS关系，前置任务是未开始的任务。 @任务：“3::子任务Ab”开始之后，该任务才能结束！
- SF关系，前置任务是未开始的任务。 @0
- SF关系，前置任务是未开始的任务。 @任务：“5::子任务Ba”开始之后，该任务才能结束！
- FF关系，前置任务是进行中的任务。 @0
- FF关系，前置任务是进行中的任务。 @任务：“11::任务G”结束之后，该任务才能结束！

*/


$taskIDList = range(1, 12);

$tester->loadModel('task');
r($tester->task->checkDepend($taskIDList[1],  'begin'))  && p('') && e('0');                                               // 没有前置任务的任务。
r($tester->task->checkDepend($taskIDList[1],  'end'))    && p('') && e('0');                                               // 没有前置任务的任务。
r($tester->task->checkDepend($taskIDList[2],  'begin'))  && p('') && e('任务：“2::子任务Aa”结束之后，该任务才能开始！'); // FS关系，前置任务是进行中的任务。
r($tester->task->checkDepend($taskIDList[2],  'end'))    && p('') && e('任务：“2::子任务Aa”结束之后，该任务才能结束！'); // FS关系，前置任务是进行中的任务。
r($tester->task->checkDepend($taskIDList[4],  'begin'))  && p('') && e('任务：“3::子任务Ab”开始之后，该任务才能开始！'); // SS关系，前置任务是未开始的任务。
r($tester->task->checkDepend($taskIDList[4],  'end'))    && p('') && e('任务：“3::子任务Ab”开始之后，该任务才能结束！'); // SS关系，前置任务是未开始的任务。
r($tester->task->checkDepend($taskIDList[10], 'begin'))  && p('') && e('0');                                               // SF关系，前置任务是未开始的任务。
r($tester->task->checkDepend($taskIDList[10], 'end'))    && p('') && e('任务：“5::子任务Ba”开始之后，该任务才能结束！'); // SF关系，前置任务是未开始的任务。
r($tester->task->checkDepend($taskIDList[11], 'begin'))  && p('') && e('0');                                               // FF关系，前置任务是进行中的任务。
r($tester->task->checkDepend($taskIDList[11], 'end'))    && p('') && e('任务：“11::任务G”结束之后，该任务才能结束！');   // FF关系，前置任务是进行中的任务。
