#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unit.class.php';

/**

title=myModel->getBaselineList();
timeout=0
cid=1

*/

zenData('user')->gen(5);
zenData('object')->loadYaml('object')->gen(50);

$browseTypeList = array('all', 'createdByMe');
$orderByList    = array('id_desc', 'id_asc');
$recPerPageList = array(5, 10);

$myTester = new myTest();
r($myTester->getBaselineListTest($browseTypeList[0], $orderByList[0], $recPerPageList[0])) && p('4:id,title') && e('46,基线46'); // 获取按id倒序排列的5条基线数据
r($myTester->getBaselineListTest($browseTypeList[0], $orderByList[1], $recPerPageList[0])) && p('4:id,title') && e('5,基线5');   // 获取按id正序排列的5条基线数据
r($myTester->getBaselineListTest($browseTypeList[0], $orderByList[0], $recPerPageList[1])) && p('9:id,title') && e('41,基线41'); // 获取按id倒序排列的10条基线数据
r($myTester->getBaselineListTest($browseTypeList[0], $orderByList[1], $recPerPageList[1])) && p('9:id,title') && e('10,基线10'); // 获取按id正序排列的10条基线数据
r($myTester->getBaselineListTest($browseTypeList[1], $orderByList[0], $recPerPageList[0])) && p('0:id,title') && e('50,基线50'); // 获取按id倒序排列由admin创建的5条基线数据
r($myTester->getBaselineListTest($browseTypeList[1], $orderByList[1], $recPerPageList[0])) && p('0:id,title') && e('1,基线1');   // 获取按id正序排列由admin创建的5条基线数据
r($myTester->getBaselineListTest($browseTypeList[1], $orderByList[0], $recPerPageList[1])) && p('0:id,title') && e('50,基线50'); // 获取按id倒序排列由admin创建的10条基线数据
r($myTester->getBaselineListTest($browseTypeList[1], $orderByList[1], $recPerPageList[1])) && p('0:id,title') && e('1,基线1');   // 获取按id正序排列由admin创建的10条基线数据
