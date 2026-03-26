<?php
global $app, $lang;
$config->deploy->dtable = new stdclass();
$config->deploy->dtable->fieldList['name']['title']    = $lang->deploy->name;
$config->deploy->dtable->fieldList['name']['type']     = 'title';
$config->deploy->dtable->fieldList['name']['required'] = true;
$config->deploy->dtable->fieldList['name']['link']     = array('module' => 'deploy', 'method' => 'steps', 'params' => 'deployID={id}');
$config->deploy->dtable->fieldList['name']['show']     = true;
$config->deploy->dtable->fieldList['name']['order']    = 5;

$config->deploy->dtable->fieldList['product']['title'] = $lang->deploy->product;
$config->deploy->dtable->fieldList['product']['type']  = 'type';
$config->deploy->dtable->fieldList['product']['show']  = true;
$config->deploy->dtable->fieldList['product']['width'] = 150;
$config->deploy->dtable->fieldList['product']['hint']  = true;
$config->deploy->dtable->fieldList['product']['order'] = 15;

$config->deploy->dtable->fieldList['system']['title'] = $lang->deploy->system;
$config->deploy->dtable->fieldList['system']['type']  = 'type';
$config->deploy->dtable->fieldList['system']['show']  = true;
$config->deploy->dtable->fieldList['system']['width'] = 150;
$config->deploy->dtable->fieldList['system']['hint']  = true;
$config->deploy->dtable->fieldList['system']['order'] = 20;

$config->deploy->dtable->fieldList['status']['title']     = $lang->deploy->status;
$config->deploy->dtable->fieldList['status']['type']      = 'status';
$config->deploy->dtable->fieldList['status']['show']      = true;
$config->deploy->dtable->fieldList['status']['statusMap'] = $lang->deploy->statusList;
$config->deploy->dtable->fieldList['status']['order']     = 25;

$config->deploy->dtable->fieldList['owner']['title'] = $lang->deploy->owner;
$config->deploy->dtable->fieldList['owner']['show']  = true;
$config->deploy->dtable->fieldList['owner']['type']  = 'user';
$config->deploy->dtable->fieldList['owner']['order'] = 30;

$config->deploy->dtable->fieldList['members']['title']     = $lang->deploy->members;
$config->deploy->dtable->fieldList['members']['type']      = 'desc';
$config->deploy->dtable->fieldList['members']['delimiter'] = ',';
$config->deploy->dtable->fieldList['members']['hint']      = true;
$config->deploy->dtable->fieldList['members']['order']     = 35;

$config->deploy->dtable->fieldList['estimate']['title'] = $lang->deploy->estimate;
$config->deploy->dtable->fieldList['estimate']['type']  = 'date';
$config->deploy->dtable->fieldList['estimate']['show']  = true;
$config->deploy->dtable->fieldList['estimate']['width'] = 150;
$config->deploy->dtable->fieldList['estimate']['order'] = 40;

$config->deploy->dtable->fieldList['begin']['title'] = $lang->deploy->begin;
$config->deploy->dtable->fieldList['begin']['type']  = 'date';
$config->deploy->dtable->fieldList['begin']['show']  = false;
$config->deploy->dtable->fieldList['begin']['width'] = 150;
$config->deploy->dtable->fieldList['begin']['order'] = 40;

$config->deploy->dtable->fieldList['end']['title'] = $lang->deploy->end;
$config->deploy->dtable->fieldList['end']['type']  = 'date';
$config->deploy->dtable->fieldList['end']['show']  = false;
$config->deploy->dtable->fieldList['end']['width'] = 150;
$config->deploy->dtable->fieldList['end']['order'] = 40;

$config->deploy->dtable->fieldList['desc']['title'] = $lang->deploy->desc;
$config->deploy->dtable->fieldList['desc']['type']  = 'html';
$config->deploy->dtable->fieldList['desc']['order'] = 50;

$config->deploy->dtable->fieldList['createdBy']['title'] = $lang->deploy->createdBy;
$config->deploy->dtable->fieldList['createdBy']['type']  = 'user';
$config->deploy->dtable->fieldList['createdBy']['order'] = 55;

$config->deploy->dtable->fieldList['createdDate']['title'] = $lang->deploy->createdDate;
$config->deploy->dtable->fieldList['createdDate']['type']  = 'datetime';
$config->deploy->dtable->fieldList['createdDate']['order'] = 60;

$config->deploy->dtable->fieldList['actions']['type']  = 'actions';
$config->deploy->dtable->fieldList['actions']['title'] = $lang->actions;
$config->deploy->dtable->fieldList['actions']['fixed'] = 'right';
$config->deploy->dtable->fieldList['actions']['width'] = 100;
$config->deploy->dtable->fieldList['actions']['list']  = $config->deploy->actionList;
$config->deploy->dtable->fieldList['actions']['menu']  = array('publish', 'finish|activate', 'edit', 'delete');

$app->loadLang('testcase');
$app->loadLang('testtask');
$config->deploy->dtable->cases = new stdclass();
$config->deploy->dtable->cases->fieldList['ID']['title'] = $lang->idAB;
$config->deploy->dtable->cases->fieldList['ID']['type']  = 'checkID';
$config->deploy->dtable->cases->fieldList['ID']['name']  = 'id';
$config->deploy->dtable->cases->fieldList['ID']['sortType'] = false;

$config->deploy->dtable->cases->fieldList['pri']['title']    = $lang->pri;
$config->deploy->dtable->cases->fieldList['pri']['type']     = 'pri';
$config->deploy->dtable->cases->fieldList['pri']['name']     = 'pri';
$config->deploy->dtable->cases->fieldList['pri']['sortType'] = false;

$config->deploy->dtable->cases->fieldList['title']['title']       = $lang->testcase->title;
$config->deploy->dtable->cases->fieldList['title']['type']        = 'text';
$config->deploy->dtable->cases->fieldList['title']['name']        = 'title';
$config->deploy->dtable->cases->fieldList['title']['link']        = array('module' => 'testcase', 'method' => 'view', 'params' => "id={id}");
$config->deploy->dtable->cases->fieldList['title']['hint']        = true;
$config->deploy->dtable->cases->fieldList['title']['data-toggle'] = 'modal';
$config->deploy->dtable->cases->fieldList['title']['data-size']   = 'lg';

$config->deploy->dtable->cases->fieldList['type']['title']    = $lang->testcase->type;
$config->deploy->dtable->cases->fieldList['type']['type']     = 'type';
$config->deploy->dtable->cases->fieldList['type']['name']     = 'type';
$config->deploy->dtable->cases->fieldList['type']['sortType'] = false;
$config->deploy->dtable->cases->fieldList['type']['map']      = $lang->testcase->typeList;

$config->deploy->dtable->cases->fieldList['lastResult']['title']     = $lang->testcase->lastRunResult;
$config->deploy->dtable->cases->fieldList['lastResult']['type']      = 'status';
$config->deploy->dtable->cases->fieldList['lastResult']['statusMap'] = $lang->testcase->resultList;
$config->deploy->dtable->cases->fieldList['lastResult']['sortType']  = false;

$config->deploy->dtable->cases->actionList = array();
$config->deploy->dtable->cases->actionList['unlinkCase']['icon']         = 'unlink';
$config->deploy->dtable->cases->actionList['unlinkCase']['text']         = $lang->unlink;
$config->deploy->dtable->cases->actionList['unlinkCase']['hint']         = $lang->unlink;
$config->deploy->dtable->cases->actionList['unlinkCase']['url']          = array('module' => 'deploy', 'method' => 'unlinkCase', 'params' => "deployID={deployID}&caseID={id}");
$config->deploy->dtable->cases->actionList['unlinkCase']['ajaxSubmit']   = true;
$config->deploy->dtable->cases->actionList['unlinkCase']['data-confirm'] = $lang->testtask->confirmUnlinkCase;

$config->deploy->dtable->cases->actionList['runCase']['icon']         = 'play';
$config->deploy->dtable->cases->actionList['runCase']['text']         = $lang->testtask->runCase;
$config->deploy->dtable->cases->actionList['runCase']['hint']         = $lang->testtask->runCase;
$config->deploy->dtable->cases->actionList['runCase']['url']          = array('module' => 'testtask', 'method' => 'runCase', 'params' => "runID=0&caseID={id}");
$config->deploy->dtable->cases->actionList['runCase']['data-toggle']  = 'modal';
$config->deploy->dtable->cases->actionList['runCase']['data-size']    = 'lg';
$config->deploy->dtable->cases->actionList['runCase']['notLoadModel'] = true;

$config->deploy->dtable->cases->fieldList['actions']['name']  = 'actions';
$config->deploy->dtable->cases->fieldList['actions']['type']  = 'actions';
$config->deploy->dtable->cases->fieldList['actions']['title'] = $lang->actions;
$config->deploy->dtable->cases->fieldList['actions']['fixed'] = 'right';
$config->deploy->dtable->cases->fieldList['actions']['menu']  = array('runCase', 'unlinkCase');
$config->deploy->dtable->cases->fieldList['actions']['list']  = $config->deploy->dtable->cases->actionList;

$config->deploy->dtable->steps = new stdclass();
$config->deploy->dtable->steps->fieldList['title']['title']        = $lang->deploy->steps;
$config->deploy->dtable->steps->fieldList['title']['type']         = 'nestedTitle';
$config->deploy->dtable->steps->fieldList['title']['name']         = 'title';
$config->deploy->dtable->steps->fieldList['title']['link']         = array('module' => 'deploy', 'method' => 'viewStep', 'params' => "stepID={id}");
$config->deploy->dtable->steps->fieldList['title']['hint']         = true;
$config->deploy->dtable->steps->fieldList['title']['nestedToggle'] = true;
$config->deploy->dtable->steps->fieldList['title']['sortType']     = false;
$config->deploy->dtable->steps->fieldList['title']['data-toggle']  = 'modal';
$config->deploy->dtable->steps->fieldList['title']['data-size']    = 'lg';

$config->deploy->dtable->steps->fieldList['content']['title']    = $lang->deploy->desc;
$config->deploy->dtable->steps->fieldList['content']['type']     = 'text';
$config->deploy->dtable->steps->fieldList['content']['name']     = 'content';
$config->deploy->dtable->steps->fieldList['content']['hint']     = true;
$config->deploy->dtable->steps->fieldList['content']['sortType'] = false;

$config->deploy->dtable->steps->fieldList['assignedTo']['title']      = $lang->deploy->assignedTo;
$config->deploy->dtable->steps->fieldList['assignedTo']['type']       = 'assign';
$config->deploy->dtable->steps->fieldList['assignedTo']['assignLink'] = array('module' => 'deploy', 'method' => 'assignTo', 'params' => 'stepID={id}');
$config->deploy->dtable->steps->fieldList['assignedTo']['sortType']   = false;

$config->deploy->dtable->steps->fieldList['status']['title']     = $lang->deploy->status;
$config->deploy->dtable->steps->fieldList['status']['type']      = 'status';
$config->deploy->dtable->steps->fieldList['status']['statusMap'] = $lang->deploy->stepStatusList;
$config->deploy->dtable->steps->fieldList['status']['sortType']  = false;

$config->deploy->dtable->steps->fieldList['actions']['title']    = $lang->actions;
$config->deploy->dtable->steps->fieldList['actions']['type']     = 'actions';
$config->deploy->dtable->steps->fieldList['actions']['width']    = '60';
$config->deploy->dtable->steps->fieldList['actions']['list']     = $config->deploy->actionList;
$config->deploy->dtable->steps->fieldList['actions']['menu']     = array('finishStep');
