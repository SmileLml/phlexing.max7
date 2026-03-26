<?php
global $app, $lang;
$app->loadLang('workflow');

$config->workflow->dtable = new stdclass();
$config->workflow->dtable->fieldList['id']['title']    = $lang->idAB;
$config->workflow->dtable->fieldList['id']['type']     = 'id';
$config->workflow->dtable->fieldList['id']['sortType'] = true;

$config->workflow->dtable->fieldList['name']['title']       = $lang->workflow->name;
$config->workflow->dtable->fieldList['name']['link']        = ['module' => 'workflow', 'method' => 'view', 'params' => 'id={id}'];
$config->workflow->dtable->fieldList['name']['data-toggle'] = 'modal';
$config->workflow->dtable->fieldList['name']['width']       = '200px';
$config->workflow->dtable->fieldList['name']['sortType']    = true;

$config->workflow->dtable->fieldList['module']['title']    = $lang->workflow->module;
$config->workflow->dtable->fieldList['module']['width']    = '200px';
$config->workflow->dtable->fieldList['module']['sortType'] = true;

$config->workflow->dtable->fieldList['navigator']['title']    = $lang->workflow->navigator;
$config->workflow->dtable->fieldList['navigator']['map']      = $lang->workflow->navigators;
$config->workflow->dtable->fieldList['navigator']['align']    = 'center';
$config->workflow->dtable->fieldList['navigator']['width']    = '100px';
$config->workflow->dtable->fieldList['navigator']['sortType'] = true;

$config->workflow->dtable->fieldList['app']['title']    = $lang->workflow->app;
$config->workflow->dtable->fieldList['app']['align']    = 'center';
$config->workflow->dtable->fieldList['app']['width']    = '100px';
$config->workflow->dtable->fieldList['app']['sortType'] = true;

$config->workflow->dtable->fieldList['buildin']['title']    = $lang->workflow->buildin;
$config->workflow->dtable->fieldList['buildin']['align']    = 'center';
$config->workflow->dtable->fieldList['buildin']['width']    = '80px';
$config->workflow->dtable->fieldList['buildin']['sortType'] = true;

$config->workflow->dtable->fieldList['status']['title']     = $lang->workflow->status;
$config->workflow->dtable->fieldList['status']['type']      = 'status';
$config->workflow->dtable->fieldList['status']['statusMap'] = $lang->workflow->statusList;

$config->workflow->dtable->fieldList['desc']['title'] = $lang->workflow->desc;
$config->workflow->dtable->fieldList['desc']['type']  = 'desc';

$config->workflow->dtable->fieldList['actions']['title'] = $lang->actions;
$config->workflow->dtable->fieldList['actions']['type']  = 'actions';
$config->workflow->dtable->fieldList['actions']['menu']  = ['edit', 'design|field', 'release|deactivate|activate', 'copy', 'delete'];
$config->workflow->dtable->fieldList['actions']['list']  = $config->workflow->actionList;
