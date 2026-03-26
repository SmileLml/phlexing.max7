<?php

/**
title=创建透视表
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/editpivot.ui.class.php';

$tester = new editPivotTester();
$tester->login();

$pivot = array(
    'normal' => array(
        'group' => array('组织'),
        'name'  => '编辑透视表' . time(),
    ),
);

r($tester->edit($pivot['normal'])) && p('message') && e('编辑透视表成功');
