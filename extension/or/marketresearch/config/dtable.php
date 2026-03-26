<?php
global $lang, $app;
$app->loadLang('researchtask');
$app->loadLang('execution');

$config->marketresearch->dtable = new stdclass();
$config->marketresearch->dtable->fieldList = array();
$config->marketresearch->dtable->fieldList['id']['title']    = 'idAB';
$config->marketresearch->dtable->fieldList['id']['type']     = 'id';
$config->marketresearch->dtable->fieldList['id']['fixed']    = 'left';
$config->marketresearch->dtable->fieldList['id']['required'] = true;

$config->marketresearch->dtable->fieldList['name']['type']     = 'title';
$config->marketresearch->dtable->fieldList['name']['fixed']    = 'left';
$config->marketresearch->dtable->fieldList['name']['link']     = array('module' => 'marketresearch', 'method' => 'task', 'params' => "researchID={id}");
$config->marketresearch->dtable->fieldList['name']['required'] = true;

$config->marketresearch->dtable->fieldList['status']['type']      = 'status';
$config->marketresearch->dtable->fieldList['status']['statusMap'] = $lang->marketresearch->statusList;
$config->marketresearch->dtable->fieldList['status']['show']      = true;

$config->marketresearch->dtable->fieldList['market']['type'] = 'category';
$config->marketresearch->dtable->fieldList['market']['show'] = true;

$config->marketresearch->dtable->fieldList['PM']['type'] = 'user';
$config->marketresearch->dtable->fieldList['PM']['show'] = true;

$config->marketresearch->dtable->fieldList['begin']['type'] = 'date';
$config->marketresearch->dtable->fieldList['begin']['show'] = true;

$config->marketresearch->dtable->fieldList['end']['type'] = 'date';
$config->marketresearch->dtable->fieldList['end']['show'] = true;

$config->marketresearch->dtable->fieldList['realBegan']['type'] = 'date';
$config->marketresearch->dtable->fieldList['realEnd']['type']   = 'date';

$config->marketresearch->dtable->fieldList['progress']['type'] = 'progress';
$config->marketresearch->dtable->fieldList['progress']['show'] = true;

$config->marketresearch->dtable->fieldList['openedBy']['type'] = 'user';

$config->marketresearch->dtable->fieldList['actions']['title']    = $lang->actions;
$config->marketresearch->dtable->fieldList['actions']['type']     = 'actions';
$config->marketresearch->dtable->fieldList['actions']['width']    = '80';
$config->marketresearch->dtable->fieldList['actions']['fixed']    = 'right';
$config->marketresearch->dtable->fieldList['actions']['list']     = $config->marketresearch->actionList;
$config->marketresearch->dtable->fieldList['actions']['menu']     = array('start', 'close|activate', 'edit', 'group', 'reports', 'delete');
$config->marketresearch->dtable->fieldList['actions']['required'] = true;

$config->marketresearch->task = new stdclass();
$config->marketresearch->task->dtable = new stdclass();

$config->marketresearch->task->dtable->fieldList['name']['title']        = $lang->researchtask->name;
$config->marketresearch->task->dtable->fieldList['name']['type']         = 'title';
$config->marketresearch->task->dtable->fieldList['name']['fixed']        = 'left';
$config->marketresearch->task->dtable->fieldList['name']['sortType']     = true;
$config->marketresearch->task->dtable->fieldList['name']['group']        = 1;
$config->marketresearch->task->dtable->fieldList['name']['nestedToggle'] = true;
$config->marketresearch->task->dtable->fieldList['name']['link']         = array('module' => 'researchtask', 'method' => 'view', 'params' => 'taskID={id}');
$config->marketresearch->task->dtable->fieldList['name']['required']     = true;
$config->marketresearch->task->dtable->fieldList['name']['styleMap']     = array('--color-link' => 'color');
$config->marketresearch->task->dtable->fieldList['name']['data-app']     = $app->tab;

$config->marketresearch->task->dtable->fieldList['status']['title']     = $lang->researchtask->status;
$config->marketresearch->task->dtable->fieldList['status']['type']      = 'status';
$config->marketresearch->task->dtable->fieldList['status']['statusMap'] = $lang->researchtask->statusList;
$config->marketresearch->task->dtable->fieldList['status']['group']     = 2;
$config->marketresearch->task->dtable->fieldList['status']['sortType']  = true;
$config->marketresearch->task->dtable->fieldList['status']['required']  = true;

$config->marketresearch->task->dtable->fieldList['PM']['title']       = $lang->execution->owner;
$config->marketresearch->task->dtable->fieldList['PM']['type']        = 'assign';
$config->marketresearch->task->dtable->fieldList['PM']['currentUser'] = $app->user->account;
$config->marketresearch->task->dtable->fieldList['PM']['assignLink']  = array('module' => 'researchtask', 'method' => 'assignTo', 'params' => 'stageID={execution}&taskID={id}');
$config->marketresearch->task->dtable->fieldList['PM']['sortType']    = false;
$config->marketresearch->task->dtable->fieldList['PM']['required']    = true;

$config->marketresearch->task->dtable->fieldList['estStarted']['title']    = $lang->researchtask->estStarted;
$config->marketresearch->task->dtable->fieldList['estStarted']['type']     = 'date';
$config->marketresearch->task->dtable->fieldList['estStarted']['group']    = 2;
$config->marketresearch->task->dtable->fieldList['estStarted']['sortType'] = true;
$config->marketresearch->task->dtable->fieldList['estStarted']['required'] = true;

$config->marketresearch->task->dtable->fieldList['deadline']['title']    = $lang->researchtask->deadline;
$config->marketresearch->task->dtable->fieldList['deadline']['type']     = 'date';
$config->marketresearch->task->dtable->fieldList['deadline']['group']    = 2;
$config->marketresearch->task->dtable->fieldList['deadline']['sortType'] = true;
$config->marketresearch->task->dtable->fieldList['deadline']['required'] = true;

$config->marketresearch->task->dtable->fieldList['estimate']['title']    = $lang->researchtask->estimate;
$config->marketresearch->task->dtable->fieldList['estimate']['type']     = 'number';
$config->marketresearch->task->dtable->fieldList['estimate']['group']    = 5;
$config->marketresearch->task->dtable->fieldList['estimate']['width']    = '80';
$config->marketresearch->task->dtable->fieldList['estimate']['sortType'] = true;
$config->marketresearch->task->dtable->fieldList['estimate']['required'] = true;

$config->marketresearch->task->dtable->fieldList['consumed']['title']    = $lang->researchtask->consumed;
$config->marketresearch->task->dtable->fieldList['consumed']['type']     = 'number';
$config->marketresearch->task->dtable->fieldList['consumed']['group']    = 5;
$config->marketresearch->task->dtable->fieldList['consumed']['width']    = '80';
$config->marketresearch->task->dtable->fieldList['consumed']['sortType'] = true;
$config->marketresearch->task->dtable->fieldList['consumed']['required'] = true;

$config->marketresearch->task->dtable->fieldList['left']['title']    = $lang->researchtask->left;
$config->marketresearch->task->dtable->fieldList['left']['type']     = 'number';
$config->marketresearch->task->dtable->fieldList['left']['group']    = 5;
$config->marketresearch->task->dtable->fieldList['left']['width']    = '80';
$config->marketresearch->task->dtable->fieldList['left']['sortType'] = true;
$config->marketresearch->task->dtable->fieldList['left']['required'] = true;

$config->marketresearch->task->dtable->fieldList['progress']['title']    = $lang->researchtask->progress;
$config->marketresearch->task->dtable->fieldList['progress']['type']     = 'progress';
$config->marketresearch->task->dtable->fieldList['progress']['group']    = 5;
$config->marketresearch->task->dtable->fieldList['progress']['sortType'] = false;
$config->marketresearch->task->dtable->fieldList['progress']['required'] = true;

$config->marketresearch->task->dtable->fieldList['actions']['title']    = $lang->actions;
$config->marketresearch->task->dtable->fieldList['actions']['type']     = 'actions';
$config->marketresearch->task->dtable->fieldList['actions']['list']     = $config->marketresearch->actionList;
$config->marketresearch->task->dtable->fieldList['actions']['menu']     = array();
$config->marketresearch->task->dtable->fieldList['actions']['width']    = '185';
$config->marketresearch->task->dtable->fieldList['actions']['required'] = true;
$config->marketresearch->task->dtable->fieldList['actions']['group']    = 7;

$config->marketresearch->dtable->team = new stdclass();
$config->marketresearch->dtable->team->fieldList = array();

$config->marketresearch->dtable->team->fieldList['account']['title']    = $lang->team->realname;
$config->marketresearch->dtable->team->fieldList['account']['align']    = 'left';
$config->marketresearch->dtable->team->fieldList['account']['name']     = 'realname';
$config->marketresearch->dtable->team->fieldList['account']['type']     = 'user';
$config->marketresearch->dtable->team->fieldList['account']['sortType'] = false;

$config->marketresearch->dtable->team->fieldList['role']['title']    = $lang->team->role;
$config->marketresearch->dtable->team->fieldList['role']['type']     = 'user';
$config->marketresearch->dtable->team->fieldList['role']['sortType'] = false;

$config->marketresearch->dtable->team->fieldList['join']['title']    = $lang->team->join;
$config->marketresearch->dtable->team->fieldList['join']['type']     = 'date';
$config->marketresearch->dtable->team->fieldList['join']['sortType'] = false;

$config->marketresearch->dtable->team->fieldList['days']['title']    = $lang->team->days;
$config->marketresearch->dtable->team->fieldList['days']['type']     = 'number';
$config->marketresearch->dtable->team->fieldList['days']['sortType'] = false;

$config->marketresearch->dtable->team->fieldList['hours']['title']    = $lang->team->hours;
$config->marketresearch->dtable->team->fieldList['hours']['type']     = 'number';
$config->marketresearch->dtable->team->fieldList['hours']['sortType'] = false;

$config->marketresearch->dtable->team->fieldList['total']['title']    = $lang->team->totalHours;
$config->marketresearch->dtable->team->fieldList['total']['type']     = 'number';
$config->marketresearch->dtable->team->fieldList['total']['sortType'] = false;

$config->marketresearch->dtable->team->fieldList['limited']['title']    = $lang->team->limited;
$config->marketresearch->dtable->team->fieldList['limited']['type']     = 'user';
$config->marketresearch->dtable->team->fieldList['limited']['map']      = $lang->team->limitedList;
$config->marketresearch->dtable->team->fieldList['limited']['sortType'] = false;

$config->marketresearch->dtable->team->fieldList['actions']['title']      = $lang->actions;
$config->marketresearch->dtable->team->fieldList['actions']['type']       = 'actions';
$config->marketresearch->dtable->team->fieldList['actions']['minWidth']   = 60;
$config->marketresearch->dtable->team->fieldList['actions']['actionsMap'] = $config->marketresearch->team->actionList;
