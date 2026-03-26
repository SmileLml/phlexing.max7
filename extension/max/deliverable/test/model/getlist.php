#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/init.php';
su('admin');

zenData('deliverable')->loadYaml('deliverable', true)->gen(5);

/**

title=taskModel->getByID();
timeout=0
cid=1

- 获取所有交付物的数量 @5
- 获取项目交付物的数量 @3
- 获取执行交付物的数量 @2
- 获取第一个交付物的名称第1条的name属性 @交付物1
- 获取第六个交付物的名称属性6 @~~

*/

global $tester;

$allDeliverables       = $tester->loadModel('deliverable')->getList('all');
$projectDeliverables   = $tester->loadModel('deliverable')->getList('project');
$executionDeliverables = $tester->loadModel('deliverable')->getList('execution');

r(count($allDeliverables))       && p() && e(5); // 获取所有交付物的数量
r(count($projectDeliverables))   && p() && e(3); // 获取项目交付物的数量
r(count($executionDeliverables)) && p() && e(2); // 获取执行交付物的数量
r($allDeliverables)              && p('1:name') && e('交付物1'); // 获取第一个交付物的名称
r($allDeliverables)              && p('6') && e('~~'); // 获取第六个交付物的名称