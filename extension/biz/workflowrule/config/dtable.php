<?php
global $app, $lang;
$app->loadLang('workflowrule');

$config->workflowrule->dtable = new stdclass();
$config->workflowrule->dtable->fieldList['id']['title']    = $lang->idAB;
$config->workflowrule->dtable->fieldList['id']['type']     = 'id';
$config->workflowrule->dtable->fieldList['id']['sortType'] = true;

$config->workflowrule->dtable->fieldList['name']['type']     = 'title';
$config->workflowrule->dtable->fieldList['name']['sortType'] = true;

$config->workflowrule->dtable->fieldList['rule']['flex'] = 1;
$config->workflowrule->dtable->fieldList['rule']['hint'] = true;

$config->workflowrule->dtable->fieldList['type']['map']      = $lang->workflowrule->typeList;
$config->workflowrule->dtable->fieldList['type']['width']    = 120;
$config->workflowrule->dtable->fieldList['type']['sortType'] = true;

$config->workflowrule->dtable->fieldList['createdBy']['type']     = 'user';
$config->workflowrule->dtable->fieldList['createdBy']['width']    = 120;
$config->workflowrule->dtable->fieldList['createdBy']['sortType'] = true;

$config->workflowrule->dtable->fieldList['createdDate']['type']     = 'date';
$config->workflowrule->dtable->fieldList['createdDate']['width']    = 120;
$config->workflowrule->dtable->fieldList['createdDate']['sortType'] = true;

$config->workflowrule->dtable->fieldList['actions']['type']  = 'actions';
$config->workflowrule->dtable->fieldList['actions']['width'] = 80;
$config->workflowrule->dtable->fieldList['actions']['list']  = $config->workflowrule->actionList;
$config->workflowrule->dtable->fieldList['actions']['menu']  = array('edit', 'delete');
