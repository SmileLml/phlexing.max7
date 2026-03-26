#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';

su('admin');

$result = zenData('deliverable')->loadYaml('deliverable')->gen(100);
$result = zenData('workflowgroup')->loadYaml('workflowgroup')->gen(4);

/**

title=upgradeModel->getProjectDeliverable();
cid=1
pid=1

- 获取项目型敏捷项目流程模板的交付物。
 - 第0条的id属性 @4
 - 第0条的category属性 @瀑布项目创建动作的交付物名称4
 - 第0条的required属性 @1
- 获取项目型瀑布项目流程模板的交付物。
 - 第0条的id属性 @2
 - 第0条的category属性 @瀑布项目创建动作的交付物名称2
 - 第0条的required属性 @1
- 获取项目型敏捷项目流程模板的交付物。
 - 第0条的id属性 @other_1
 - 第0条的category属性 @其他
 - 第0条的required属性 @``
- 获取项目型瀑布项目流程模板的交付物。
 - 第0条的id属性 @other_1
 - 第0条的category属性 @其他
 - 第0条的required属性 @``
- 获取产品型敏捷项目流程模板的交付物。
 - 第0条的id属性 @3
 - 第0条的category属性 @瀑布产品创建动作的交付物名称3
 - 第0条的required属性 @``
- 获取产品型瀑布项目流程模板的交付物。
 - 第0条的id属性 @1
 - 第0条的category属性 @瀑布产品创建动作的交付物名称1
 - 第0条的required属性 @``
- 获取产品型敏捷项目流程模板的交付物。
 - 第0条的id属性 @other_1
 - 第0条的category属性 @其他
 - 第0条的required属性 @``
- 获取产品型瀑布项目流程模板的交付物。
 - 第0条的id属性 @other_1
 - 第0条的category属性 @其他
 - 第0条的required属性 @``

*/

global $tester;
$project = $tester->loadModel('project');
r($project->getProjectDeliverable(0, 3, 'project', 'scrum',     'whenCreated')) && p('0:id,category,required') && e('4,瀑布项目创建动作的交付物名称4,1');  // 获取项目型敏捷项目流程模板的交付物。
r($project->getProjectDeliverable(0, 1, 'project', 'waterfall', 'whenCreated')) && p('0:id,category,required') && e('2,瀑布项目创建动作的交付物名称2,1');  // 获取项目型瀑布项目流程模板的交付物。
r($project->getProjectDeliverable(0, 3, 'project', 'scrum',     'whenClosed'))  && p('0:id,category,required') && e('other_1,其他,``');                    // 获取项目型敏捷项目流程模板的交付物。
r($project->getProjectDeliverable(0, 1, 'project', 'waterfall', 'whenClosed'))  && p('0:id,category,required') && e('other_1,其他,``');                    // 获取项目型瀑布项目流程模板的交付物。
r($project->getProjectDeliverable(0, 4, 'product', 'scrum',     'whenCreated')) && p('0:id,category,required') && e('3,瀑布产品创建动作的交付物名称3,``'); // 获取产品型敏捷项目流程模板的交付物。
r($project->getProjectDeliverable(0, 2, 'product', 'waterfall', 'whenCreated')) && p('0:id,category,required') && e('1,瀑布产品创建动作的交付物名称1,``'); // 获取产品型瀑布项目流程模板的交付物。
r($project->getProjectDeliverable(0, 4, 'product', 'scrum',     'whenClosed'))  && p('0:id,category,required') && e('other_1,其他,``');                    // 获取产品型敏捷项目流程模板的交付物。
r($project->getProjectDeliverable(0, 2, 'product', 'waterfall', 'whenClosed'))  && p('0:id,category,required') && e('other_1,其他,``');                    // 获取产品型瀑布项目流程模板的交付物。
