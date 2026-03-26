<?php
$config->testcase->menu = array(array('confirmDemandRetract|confirmDemandUnlink|confirmStoryChange'), array('review', 'runCase|ztfRun', 'runResult', 'edit', 'createBug', 'create', 'showScript'));

$config->testcase->actionList['confirmDemandRetract']['icon']        = 'search';
$config->testcase->actionList['confirmDemandRetract']['text']        = $lang->testcase->confirmDemandRetract;
$config->testcase->actionList['confirmDemandRetract']['hint']        = $lang->testcase->confirmDemandRetract;
$config->testcase->actionList['confirmDemandRetract']['url']         = array('module' => 'testcase', 'method' => 'confirmDemandRetract', 'params'=> 'objectID={caseID}&object=case&extra={confirmeObjectID}');
$config->testcase->actionList['confirmDemandRetract']['data-toggle'] = 'modal';

$config->testcase->actionList['confirmDemandUnlink']['icon']        = 'search';
$config->testcase->actionList['confirmDemandUnlink']['text']        = $lang->testcase->confirmDemandUnlink;
$config->testcase->actionList['confirmDemandUnlink']['hint']        = $lang->testcase->confirmDemandUnlink;
$config->testcase->actionList['confirmDemandUnlink']['url']         = array('module' => 'testcase', 'method' => 'confirmDemandUnlink', 'params' => 'objectID={caseID}&object=case&extra={confirmeObjectID}');
$config->testcase->actionList['confirmDemandUnlink']['data-toggle'] = 'modal';

$config->testcase->dtable->fieldList['actions']['list'] = $config->testcase->actionList;
