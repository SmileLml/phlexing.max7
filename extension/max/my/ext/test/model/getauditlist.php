#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unit.class.php';

/**

title=myModel->getAuditList();
timeout=0
cid=1

*/

zenData('user')->gen(5);
zenData('action')->loadYaml('action')->gen(50);
zenData('review')->loadYaml('review')->gen(50);
zenData('object')->loadYaml('object')->gen(50);
zenData('approval')->loadYaml('approval')->gen(50);

$browseTypeList = array('auditByMe', 'createdByMe');
$orderByList    = array('id_desc', 'id_asc');
$recPerPageList = array(5, 10);

$myTester = new myTest();
r($myTester->getAuditListTest($browseTypeList[0], $orderByList[0], $recPerPageList[0])) && p()                     && e('0');            // 获取由admin审批的按照ID倒叙排列的5条数据
r($myTester->getAuditListTest($browseTypeList[0], $orderByList[1], $recPerPageList[0])) && p()                     && e('0');            // 获取由admin审批的按照ID正叙排列的5条数据
r($myTester->getAuditListTest($browseTypeList[1], $orderByList[0], $recPerPageList[0])) && p('25:title,createdBy') && e('评审25,admin'); // 获取由admin发起的按照ID倒叙排列的5条数据
r($myTester->getAuditListTest($browseTypeList[1], $orderByList[1], $recPerPageList[0])) && p('1:title,createdBy')  && e('评审1,admin');  // 获取由admin发起的按照ID正叙排列的5条数据
r($myTester->getAuditListTest($browseTypeList[0], $orderByList[0], $recPerPageList[1])) && p()                     && e('0');            // 获取由admin审批的按照ID倒叙排列的10条数据
r($myTester->getAuditListTest($browseTypeList[0], $orderByList[1], $recPerPageList[1])) && p()                     && e('0');            // 获取由admin审批的按照ID正叙排列的10条数据
r($myTester->getAuditListTest($browseTypeList[1], $orderByList[0], $recPerPageList[1])) && p('25:title,createdBy') && e('评审25,admin'); // 获取由admin发起的按照ID倒叙排列的10条数据
r($myTester->getAuditListTest($browseTypeList[1], $orderByList[1], $recPerPageList[1])) && p('1:title,createdBy')  && e('评审1,admin');  // 获取由admin发起的按照ID正叙排列的10条数据
