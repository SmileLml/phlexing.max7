#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 7) . '/module/task/test/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('gantt_project')->gen(6);
zenData('task')->loadYaml('gantt_task')->gen(12);
zenData('relationoftasks')->loadYaml('gantt_relationoftasks')->gen(4);

/**

title=taskModel->finish();
timeout=0
cid=1

- 没有前置任务的任务。
 - 第0条的field属性 @left
 - 第0条的old属性 @1
 - 第0条的new属性 @0
- FS关系，前置任务是进行中的任务。第message条的0属性 @任务：“2::子任务Aa”结束之后，该任务才能结束！
- SS关系，前置任务是未开始的任务。第message条的0属性 @任务：“3::子任务Ab”开始之后，该任务才能结束！
- SF关系，前置任务是未开始的任务。第message条的0属性 @任务：“5::子任务Ba”开始之后，该任务才能结束！
- FF关系，前置任务是进行中的任务。第message条的0属性 @任务：“11::任务G”结束之后，该任务才能结束！

*/

$taskIDList = range(1, 12);
$waitTask   = array('assignedTo' => 'admin', 'consumed' => 10);
$doingTask  = array('assignedTo' => 'user1', 'consumed' => 10);
$taskTester = new taskTest();

r($taskTester->finishTest($taskIDList[1],  $waitTask))  && p('0:field,old,new') && e('left,1,0');                                        // 没有前置任务的任务。
r($taskTester->finishTest($taskIDList[2],  $doingTask)) && p('message:0')       && e('任务：“2::子任务Aa”结束之后，该任务才能结束！'); // FS关系，前置任务是进行中的任务。
r($taskTester->finishTest($taskIDList[4],  $doingTask)) && p('message:0')       && e('任务：“3::子任务Ab”开始之后，该任务才能结束！'); // SS关系，前置任务是未开始的任务。
r($taskTester->finishTest($taskIDList[10], $waitTask))  && p('message:0')       && e('任务：“5::子任务Ba”开始之后，该任务才能结束！'); // SF关系，前置任务是未开始的任务。
r($taskTester->finishTest($taskIDList[11], $waitTask))  && p('message:0')       && e('任务：“11::任务G”结束之后，该任务才能结束！');   // FF关系，前置任务是进行中的任务。
