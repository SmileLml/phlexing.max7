#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/flow.unit.class.php';

/**

title=测试 flowModel::createCategoryLink();
timeout=0
cid=1

*/

zenData('module')->loadYaml('module')->gen(5)->fixPath();
$moduleIdList = range(1, 5);

$flowTester = new flowTest();
r($flowTester->createCategoryLinkTest($moduleIdList[0])) && p('name,parent') && e('模块1,0'); // 测试构建moduleID为1的模块链接
r($flowTester->createCategoryLinkTest($moduleIdList[1])) && p('name,parent') && e('模块2,0'); // 测试构建moduleID为2的模块链接
r($flowTester->createCategoryLinkTest($moduleIdList[2])) && p('name,parent') && e('模块3,1'); // 测试构建moduleID为3的模块链接
r($flowTester->createCategoryLinkTest($moduleIdList[3])) && p('name,parent') && e('模块4,1'); // 测试构建moduleID为4的模块链接
r($flowTester->createCategoryLinkTest($moduleIdList[4])) && p('name,parent') && e('模块5,0'); // 测试构建moduleID为5的模块链接
