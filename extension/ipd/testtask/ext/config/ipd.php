<?php
$config->testtask->cases->actionList['confirmDemandRetract']['icon']        = 'search';
$config->testtask->cases->actionList['confirmDemandRetract']['text']        = $lang->testcase->confirmDemandRetract;
$config->testtask->cases->actionList['confirmDemandRetract']['hint']        = $lang->testcase->confirmDemandRetract;
$config->testtask->cases->actionList['confirmDemandRetract']['url']         = array('module' => 'testcase', 'method' => 'confirmDemandRetract', 'params'=> 'objectID={case}&object=case&extra={confirmeObjectID}');
$config->testtask->cases->actionList['confirmDemandRetract']['data-toggle'] = 'modal';

$config->testtask->cases->actionList['confirmDemandUnlink']['icon']        = 'search';
$config->testtask->cases->actionList['confirmDemandUnlink']['text']        = $lang->testcase->confirmDemandUnlink;
$config->testtask->cases->actionList['confirmDemandUnlink']['hint']        = $lang->testcase->confirmDemandUnlink;
$config->testtask->cases->actionList['confirmDemandUnlink']['url']         = array('module' => 'testcase', 'method' => 'confirmDemandUnlink', 'params' => 'objectID={case}&object=case&extra={confirmeObjectID}');
$config->testtask->cases->actionList['confirmDemandUnlink']['data-toggle'] = 'modal';

$config->testtask->cases->dtable->fieldList['actions']['list'] = $config->testtask->cases->actionList;
$config->testtask->cases->dtable->fieldList['actions']['menu']  = array(array('confirmDemandRetract|confirmDemandUnlink|confirmChange'), array('createBug', 'runCase', 'results', 'unlinkCase'));
