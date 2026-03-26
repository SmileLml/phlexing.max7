#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/flow.unit.class.php';

/**

title=测试 flowModel::getCategories();
timeout=0
cid=1

*/

zenData('module')->loadYaml('module')->gen(5)->fixPath();
zenData('workflow')->loadYaml('workflow')->gen(5);
zenData('workflowfield')->loadYaml('workflowfield')->gen(5);

$flowList = array('flowa', 'flowb', 'flowtest');
$modeList = array('browse', 'single', 'batch');

$flowTester = new flowTest();
r($flowTester->getCategoriesTest($flowList[0], $modeList[0])) && p('flowa_typea:type,name') && e('flowa_typea,类型1'); // 测试获取工作流a，列表页的分类
r($flowTester->getCategoriesTest($flowList[0], $modeList[1])) && p('flowa_typea:type,name') && e('flowa_typea,类型1'); // 测试获取工作流a，创建/编辑页的分类
r($flowTester->getCategoriesTest($flowList[0], $modeList[2])) && p('flowa_typea:type,name') && e('flowa_typea,类型1'); // 测试获取工作流a，批量页的分类
r($flowTester->getCategoriesTest($flowList[1], $modeList[0])) && p('flowb_typeb:type,name') && e('flowb_typeb,类型2'); // 测试获取工作流b，列表页的分类
r($flowTester->getCategoriesTest($flowList[1], $modeList[1])) && p('flowb_typeb:type,name') && e('flowb_typeb,类型2'); // 测试获取工作流b，创建/编辑页的分类
r($flowTester->getCategoriesTest($flowList[1], $modeList[2])) && p('flowb_typeb:type,name') && e('flowb_typeb,类型2'); // 测试获取工作流b，列表页的分类
r($flowTester->getCategoriesTest($flowList[2], $modeList[0])) && p()                        && e('0');                 // 测试获取不存在的工作流，列表页的分类
r($flowTester->getCategoriesTest($flowList[2], $modeList[1])) && p()                        && e('0');                 // 测试获取不存在的工作流，创建/编辑页的分类
r($flowTester->getCategoriesTest($flowList[2], $modeList[2])) && p()                        && e('0');                 // 测试获取不存在的工作流，列表页的分类
