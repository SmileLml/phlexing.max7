#!/usr/bin/env php
<?php

/**

title=创建透视表
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/createpivot.ui.class.php';


$tester = new createPivotTester();
$tester->login();

$pivot = array(
    'normal' => array(
        'group' => array('产品'),
        'name'  => '透视表' . time(),
    ),
);

r($tester->create($pivot['normal'])) && p('message') && e('创建透视表成功');
