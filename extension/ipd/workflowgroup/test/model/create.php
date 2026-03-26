#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/workflowgroup.unittest.class.php';
su('admin');

zenData('workflowgroup')->gen(0);

/**

title=workflowgroupModel->create();
timeout=0
cid=1

- 产品流程模板名称为空返回错误信息。第name条的0属性 @『名称』不能为空。
- 项目流程模板名称为空返回错误信息。第name条的0属性 @『名称』不能为空。
- 项目流程模板项目模型为空返回错误信息。第projectModel条的0属性 @『适用的项目模型』不能为空。
- 项目流程模板项目类型为空返回错误信息。第projectType条的0属性 @『适用的项目类型』不能为空。
- 产品流程模板创建成功。
 - 属性id @1
 - 属性type @product
 - 属性name @group3
 - 属性status @wait
 - 属性vision @rnd
- 项目流程模板创建成功。
 - 属性id @2
 - 属性type @project
 - 属性name @group4
 - 属性status @wait
 - 属性vision @rnd
 - 属性projectModel @scrum
 - 属性projectType @1
- 产品流程模板名称重复返回错误信息。第name条的0属性 @『名称』已经有『group3』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 项目流程模板名称重复返回错误信息。第name条的0属性 @『名称』已经有『group4』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/

$group1 = (object)['type' => 'product', 'name' => '',       'desc' => ''];
$group2 = (object)['type' => 'project', 'name' => '',       'desc' => '', 'projectModel' => '',      'projectType' => ''];
$group3 = (object)['type' => 'product', 'name' => 'group3', 'desc' => ''];
$group4 = (object)['type' => 'project', 'name' => 'group4', 'desc' => '', 'projectModel' => 'scrum', 'projectType' => 'product'];

$group = new workflowgroupTest();

r($group->createTest($group1)) && p('name:0')         && e('『名称』不能为空。');           // 产品流程模板名称为空返回错误信息。
r($group->createTest($group2)) && p('name:0')         && e('『名称』不能为空。');           // 项目流程模板名称为空返回错误信息。
r($group->createTest($group2)) && p('projectModel:0') && e('『适用的项目模型』不能为空。'); // 项目流程模板项目模型为空返回错误信息。
r($group->createTest($group2)) && p('projectType:0')  && e('『适用的项目类型』不能为空。'); // 项目流程模板项目类型为空返回错误信息。

r($group->createTest($group3)) && p('id,type,name,status,vision') && e('1,product,group3,wait,rnd'); // 产品流程模板创建成功。

r($group->createTest($group4)) && p('id,type,name,status,vision,projectModel,projectType') && e('2,project,group4,wait,rnd,scrum,product'); // 项目流程模板创建成功。

r($group->createTest($group3)) && p('name:0') && e('『名称』已经有『group3』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 产品流程模板名称重复返回错误信息。
r($group->createTest($group4)) && p('name:0') && e('『名称』已经有『group4』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 项目流程模板名称重复返回错误信息。
