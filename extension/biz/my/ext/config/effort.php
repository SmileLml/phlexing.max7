<?php
global $app;
$app->loadLang('effort');
$app->loadConfig('effort');
$config->my->effort = new stdclass();
$config->my->effort->dtable = new stdclass();
$config->my->effort->dtable->fieldList['id']['name']  = 'id';
$config->my->effort->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->effort->dtable->fieldList['id']['type']  = 'checkID';

$config->my->effort->dtable->fieldList['work']['name']        = 'work';
$config->my->effort->dtable->fieldList['work']['title']       = $lang->effort->work;
$config->my->effort->dtable->fieldList['work']['type']        = 'title';
$config->my->effort->dtable->fieldList['work']['link']        = array('module' => 'effort', 'method' => 'view', 'params' => 'id={id}&from=my');
$config->my->effort->dtable->fieldList['work']['data-toggle'] = 'modal';
$config->my->effort->dtable->fieldList['work']['data-size']   = 'lg';

$config->my->effort->dtable->fieldList['date']['name']     = 'date';
$config->my->effort->dtable->fieldList['date']['title']    = $lang->effort->date;
$config->my->effort->dtable->fieldList['date']['type']     = 'date';
$config->my->effort->dtable->fieldList['date']['show']     = true;
$config->my->effort->dtable->fieldList['date']['required'] = true;

$config->my->effort->dtable->fieldList['account']['name']  = 'account';
$config->my->effort->dtable->fieldList['account']['title'] = $lang->effort->account;
$config->my->effort->dtable->fieldList['account']['type']  = 'user';
$config->my->effort->dtable->fieldList['account']['show']  = true;

$config->my->effort->dtable->fieldList['consumed']['name']  = 'consumed';
$config->my->effort->dtable->fieldList['consumed']['title'] = $lang->effort->consumed;
$config->my->effort->dtable->fieldList['consumed']['type']  = 'number';
$config->my->effort->dtable->fieldList['consumed']['show']  = true;

$config->my->effort->dtable->fieldList['left']['name']  = 'left';
$config->my->effort->dtable->fieldList['left']['title'] = $lang->effort->left;
$config->my->effort->dtable->fieldList['left']['type']  = 'number';
$config->my->effort->dtable->fieldList['left']['show']  = true;

$config->my->effort->dtable->fieldList['objectTitle']['name']  = 'objectTitle';
$config->my->effort->dtable->fieldList['objectTitle']['title'] = $lang->effort->objectType;
$config->my->effort->dtable->fieldList['objectTitle']['type']  = 'text';
$config->my->effort->dtable->fieldList['objectTitle']['link']  = helper::createLink('{objectType}', 'view', 'id={objectID}');
$config->my->effort->dtable->fieldList['objectTitle']['show']  = true;

$config->my->effort->dtable->fieldList['product']['name']    = 'product';
$config->my->effort->dtable->fieldList['product']['title']   = $lang->effort->product;
$config->my->effort->dtable->fieldList['product']['type']    = 'category';
$config->my->effort->dtable->fieldList['product']['control'] = 'multiple';
$config->my->effort->dtable->fieldList['product']['align']   = 'left';
$config->my->effort->dtable->fieldList['product']['show']    = true;

$config->my->effort->dtable->fieldList['project']['name']  = 'project';
$config->my->effort->dtable->fieldList['project']['title'] = $lang->effort->project;
$config->my->effort->dtable->fieldList['project']['type']  = 'category';
$config->my->effort->dtable->fieldList['project']['align'] = 'left';
$config->my->effort->dtable->fieldList['project']['show']  = true;

$config->my->effort->dtable->fieldList['execution']['name']  = 'execution';
$config->my->effort->dtable->fieldList['execution']['title'] = $lang->effort->execution;
$config->my->effort->dtable->fieldList['execution']['type']  = 'category';
$config->my->effort->dtable->fieldList['execution']['align'] = 'left';
$config->my->effort->dtable->fieldList['execution']['show']  = true;

$config->my->effort->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->effort->dtable->fieldList['actions']['type']     = 'actions';
$config->my->effort->dtable->fieldList['actions']['width']    = '80px';
$config->my->effort->dtable->fieldList['actions']['sortType'] = false;
$config->my->effort->dtable->fieldList['actions']['list']     = $config->effort->actionList;
$config->my->effort->dtable->fieldList['actions']['menu']     = array('edit', 'delete');
