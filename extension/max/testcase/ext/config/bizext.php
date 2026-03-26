<?php
global $app;
$app->loadLang('effort');
$config->testcase->actionList['createeffort']['icon']        = 'time';
$config->testcase->actionList['createeffort']['text']        = $lang->effort->common;
$config->testcase->actionList['createeffort']['hint']        = $lang->effort->common;
$config->testcase->actionList['createeffort']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'type=testcase&caseID={id}');
$config->testcase->actionList['createeffort']['data-toggle'] = 'modal';

array_unshift($config->testcase->actions->view['mainActions'], 'createeffort');
