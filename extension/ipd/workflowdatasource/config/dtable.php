<?php
global $app, $lang;
$app->loadLang('workflowdatasource');

$config->workflowdatasource->dtable = new stdclass();
$config->workflowdatasource->dtable->fieldList['id']['title']    = $lang->idAB;
$config->workflowdatasource->dtable->fieldList['id']['type']     = 'id';
$config->workflowdatasource->dtable->fieldList['id']['sortType'] = true;

$config->workflowdatasource->dtable->fieldList['name']['type']     = 'title';
$config->workflowdatasource->dtable->fieldList['name']['sortType'] = true;

$config->workflowdatasource->dtable->fieldList['code']['type']     = 'title';
$config->workflowdatasource->dtable->fieldList['code']['sortType'] = true;

$config->workflowdatasource->dtable->fieldList['datasource']['map']  = $lang->workflowdatasource->langList;
$config->workflowdatasource->dtable->fieldList['datasource']['flex'] = 1;
$config->workflowdatasource->dtable->fieldList['datasource']['hint'] = true;

$config->workflowdatasource->dtable->fieldList['type']['map']      = $lang->workflowdatasource->typeList;
$config->workflowdatasource->dtable->fieldList['type']['width']    = 100;
$config->workflowdatasource->dtable->fieldList['type']['sortType'] = true;

$config->workflowdatasource->dtable->fieldList['buildin']['width']    = 80;
$config->workflowdatasource->dtable->fieldList['buildin']['align']    = 'center';
$config->workflowdatasource->dtable->fieldList['buildin']['sortType'] = true;

$config->workflowdatasource->dtable->fieldList['createdBy']['type']     = 'user';
$config->workflowdatasource->dtable->fieldList['createdBy']['width']    = 100;
$config->workflowdatasource->dtable->fieldList['createdBy']['sortType'] = true;

$config->workflowdatasource->dtable->fieldList['createdDate']['type']     = 'date';
$config->workflowdatasource->dtable->fieldList['createdDate']['width']    = 120;
$config->workflowdatasource->dtable->fieldList['createdDate']['sortType'] = true;

$config->workflowdatasource->dtable->fieldList['actions']['type']  = 'actions';
$config->workflowdatasource->dtable->fieldList['actions']['width'] = 80;
$config->workflowdatasource->dtable->fieldList['actions']['list']  = $config->workflowdatasource->actionList;
$config->workflowdatasource->dtable->fieldList['actions']['menu']  = array('manage', 'edit', 'delete');
