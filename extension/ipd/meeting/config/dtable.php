<?php
global $lang, $app;

$config->meeting->dtable = new stdclass();
$config->meeting->dtable->fieldList = array();
$config->meeting->dtable->fieldList['id']['title']    = $lang->idAB;
$config->meeting->dtable->fieldList['id']['type']     = 'id';
$config->meeting->dtable->fieldList['id']['required'] = true;

$config->meeting->dtable->fieldList['name']['title']    = $lang->meeting->name;
$config->meeting->dtable->fieldList['name']['fixed']    = 'left';
$config->meeting->dtable->fieldList['name']['type']     = 'title';
$config->meeting->dtable->fieldList['name']['link']     = array('module' => 'meeting', 'method' => 'view', 'params' => "meetingID={id}&from={$app->tab}");
$config->meeting->dtable->fieldList['name']['data-app'] = $app->tab;

$config->meeting->dtable->fieldList['mode']['title']    = $lang->meeting->mode;
$config->meeting->dtable->fieldList['mode']['type']     = 'text';
$config->meeting->dtable->fieldList['mode']['map']      = $lang->meeting->modeList;
$config->meeting->dtable->fieldList['mode']['sortType'] = true;
$config->meeting->dtable->fieldList['mode']['show']     = true;

$config->meeting->dtable->fieldList['dept']['title']    = $lang->meeting->dept;
$config->meeting->dtable->fieldList['dept']['type']     = 'text';
$config->meeting->dtable->fieldList['dept']['sortType'] = true;
$config->meeting->dtable->fieldList['dept']['show']     = true;

$config->meeting->dtable->fieldList['project']['title']    = $lang->meeting->project;
$config->meeting->dtable->fieldList['project']['type']     = 'text';
$config->meeting->dtable->fieldList['project']['sortType'] = true;
$config->meeting->dtable->fieldList['project']['show']     = true;

$config->meeting->dtable->fieldList['execution']['title']    = $lang->meeting->execution;
$config->meeting->dtable->fieldList['execution']['type']     = 'text';
$config->meeting->dtable->fieldList['execution']['sortType'] = true;
$config->meeting->dtable->fieldList['execution']['show']     = true;

$config->meeting->dtable->fieldList['date']['title']    = $lang->meeting->date;
$config->meeting->dtable->fieldList['date']['type']     = 'date';
$config->meeting->dtable->fieldList['date']['sortType'] = true;
$config->meeting->dtable->fieldList['date']['show']     = true;

$config->meeting->dtable->fieldList['room']['title']    = $lang->meeting->room;
$config->meeting->dtable->fieldList['room']['type']     = 'text';
$config->meeting->dtable->fieldList['room']['sortType'] = true;
$config->meeting->dtable->fieldList['room']['show']     = true;

$config->meeting->dtable->fieldList['host']['title']    = $lang->meeting->host;
$config->meeting->dtable->fieldList['host']['type']     = 'user';
$config->meeting->dtable->fieldList['host']['sortType'] = true;
$config->meeting->dtable->fieldList['host']['show']     = true;

$config->meeting->dtable->fieldList['minutedBy']['title']    = $lang->meeting->minutedBy;
$config->meeting->dtable->fieldList['minutedBy']['type']     = 'user';
$config->meeting->dtable->fieldList['minutedBy']['sortType'] = true;
$config->meeting->dtable->fieldList['minutedBy']['show']     = true;

$config->meeting->dtable->fieldList['actions']['name']     = 'actions';
$config->meeting->dtable->fieldList['actions']['title']    = $lang->actions;
$config->meeting->dtable->fieldList['actions']['type']     = 'actions';
$config->meeting->dtable->fieldList['actions']['sortType'] = false;
$config->meeting->dtable->fieldList['actions']['width']    = '80px';
$config->meeting->dtable->fieldList['actions']['list']     = $config->meeting->actionList;
$config->meeting->dtable->fieldList['actions']['menu']     = array('edit', 'minutes');
