#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/workflowgroup.unittest.class.php';
su('admin');

zenData('workflowgroup')->loadYaml('workflowgroup')->gen(4);

/**

title=workflowgroupModel->update();
timeout=0
cid=1

- 产品流程模板名称为空返回错误信息。第name条的0属性 @『名称』不能为空。
- 项目流程模板名称为空返回错误信息。第name条的0属性 @『名称』不能为空。
- 产品流程模板更新成功，查看历史记录。
 - 第0条的field属性 @name
 - 第0条的old属性 @产品流程模板1
 - 第0条的new属性 @group3
 - 第1条的field属性 @desc
 - 第1条的old属性 @产品流程模板1
 - 第1条的new属性 @group3
- 项目流程模板更新成功，查看历史记录。
 - 第0条的field属性 @name
 - 第0条的old属性 @敏捷项目流程模板
 - 第0条的new属性 @group4
 - 第1条的field属性 @desc
 - 第1条的old属性 @敏捷项目流程模板
 - 第1条的new属性 @group4
- 产品流程模板更新成功，查看更新后的名称和描述。
 - 属性name @group3
 - 属性desc @group3
- 项目流程模板更新成功，查看更新后的名称和描述。
 - 属性name @group4
 - 属性desc @group4
- 产品流程模板名称重复返回错误信息。第name条的0属性 @『名称』已经有『group3』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 项目流程模板名称重复返回错误信息。第name条的0属性 @『名称』已经有『group4』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/

$group1 = (object)['type' => 'product', 'name' => '',       'desc' => 'group1'];
$group2 = (object)['type' => 'project', 'name' => '',       'desc' => 'group2'];
$group3 = (object)['type' => 'product', 'name' => 'group3', 'desc' => 'group3'];
$group4 = (object)['type' => 'project', 'name' => 'group4', 'desc' => 'group4'];

$group = new workflowgroupTest();

$oldGroup1 = $group->getByIdTest(1);
$oldGroup2 = $group->getByIdTest(2);
$oldGroup3 = $group->getByIdTest(3);
$oldGroup4 = $group->getByIdTest(4);

r($group->updateTest($group1, $oldGroup1)) && p('name:0') && e('『名称』不能为空。'); // 产品流程模板名称为空返回错误信息。
r($group->updateTest($group2, $oldGroup3)) && p('name:0') && e('『名称』不能为空。'); // 项目流程模板名称为空返回错误信息。

r($group->updateTest($group3, $oldGroup1)) && p('0:field,old,new;1:field,old,new') && e('name,产品流程模板1,group3,desc,产品流程模板1,group3');       // 产品流程模板更新成功，查看历史记录。
r($group->updateTest($group4, $oldGroup3)) && p('0:field,old,new;1:field,old,new') && e('name,敏捷项目流程模板,group4,desc,敏捷项目流程模板,group4'); // 项目流程模板更新成功，查看历史记录。

r($group->getByIdTest(1)) && p('name,desc') && e('group3,group3'); // 产品流程模板更新成功，查看更新后的名称和描述。
r($group->getByIdTest(3)) && p('name,desc') && e('group4,group4'); // 项目流程模板更新成功，查看更新后的名称和描述。

r($group->updateTest($group3, $oldGroup2)) && p('name:0') && e('『名称』已经有『group3』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 产品流程模板名称重复返回错误信息。
r($group->updateTest($group4, $oldGroup4)) && p('name:0') && e('『名称』已经有『group4』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 项目流程模板名称重复返回错误信息。