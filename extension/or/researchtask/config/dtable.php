<?php
global $app;
$app->loadConfig('task');

/* 复用任务config。*/
/* Reuse task config. */
$config->researchtask->dtable = $config->task->dtable;

/* 修改名称链接。*/
/* Modify name link. */
$config->researchtask->dtable->fieldList['name']['link'] = array('url' => array('module' => 'researchtask', 'method' => 'view', 'params' => 'taskID={id}'));
$config->researchtask->dtable->children->fieldList['name']['link'] = array('url' => array('module' => 'researchtask', 'method' => 'view', 'params' => 'taskID={id}'));

/* 修改指派给链接。*/
/* Modify assign to link. */
$config->researchtask->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'researchtask', 'method' => 'assignTo', 'params' => 'executionID={execution}&taskID={id}');
$config->researchtask->dtable->children->fieldList['assignedTo']['assignLink']  = array('module' => 'researchtask', 'method' => 'assignTo', 'params' => 'executionID={execution}&taskID={id}');

/* 修改调研任务及子任务操作按钮。*/
/* Modify action buttons. */
$config->researchtask->dtable->fieldList['actions']['list'] = $config->researchtask->actionList;
$config->researchtask->dtable->fieldList['actions']['menu'] = array('restart|start', 'finish', 'close', 'recordWorkhour', 'edit', 'batchCreate');

$config->researchtask->dtable->children->fieldList['actions'] = $config->researchtask->dtable->fieldList['actions'];
$config->researchtask->dtable->children->fieldList['actions']['title'] = $lang->actions;
$config->researchtask->dtable->children->fieldList['actions']['name']  = 'actions';
