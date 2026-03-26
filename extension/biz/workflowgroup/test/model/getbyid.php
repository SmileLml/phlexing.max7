#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/workflowgroup.unittest.class.php';
su('admin');

zenData('workflowgroup')->loadYaml('workflowgroup')->gen(4);

/**

title=workflowgroupModel->getByID();
timeout=0
cid=1

- 流程模板 ID 不存在返回 false。 @0
- 产品流程模板 ID 存在返回名称和描述。
 - 属性name @产品流程模板1
 - 属性desc @产品流程模板1
- 产品流程模板 ID 存在返回名称和描述。
 - 属性name @产品流程模板2
 - 属性desc @产品流程模板2
- 项目流程模板 ID 存在返回名称和描述。
 - 属性name @敏捷项目流程模板
 - 属性desc @敏捷项目流程模板
- 项目流程模板 ID 存在返回名称和描述。
 - 属性name @瀑布项目流程模板
 - 属性desc @瀑布项目流程模板

*/

$group = new workflowgroupTest();

r($group->getByIdTest(0)) && p() && e('0'); // 流程模板 ID 不存在返回 false。

r($group->getByIdTest(1)) && p('name,desc') && e('产品流程模板1,产品流程模板1');       // 产品流程模板 ID 存在返回名称和描述。
r($group->getByIdTest(2)) && p('name,desc') && e('产品流程模板2,产品流程模板2');       // 产品流程模板 ID 存在返回名称和描述。
r($group->getByIdTest(3)) && p('name,desc') && e('敏捷项目流程模板,敏捷项目流程模板'); // 项目流程模板 ID 存在返回名称和描述。
r($group->getByIdTest(4)) && p('name,desc') && e('瀑布项目流程模板,瀑布项目流程模板'); // 项目流程模板 ID 存在返回名称和描述。