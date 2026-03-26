<?php
global $app;
$config->opportunity->dtable = new stdclass();

$config->opportunity->dtable->fieldList['id']['title']    = $lang->idAB;
$config->opportunity->dtable->fieldList['id']['type']     = 'checkID';
$config->opportunity->dtable->fieldList['id']['checkbox'] = true;

$config->opportunity->dtable->fieldList['name']['title']    = $lang->opportunity->name;
$config->opportunity->dtable->fieldList['name']['type']     = 'title';
$config->opportunity->dtable->fieldList['name']['link']     = array('url' => array('module' => 'opportunity', 'method' => 'view', 'params' => 'opportunityID={id}&from={from}'));
$config->opportunity->dtable->fieldList['name']['data-app'] = $app->tab;

$config->opportunity->dtable->fieldList['pri']['title']   = $lang->priAB;
$config->opportunity->dtable->fieldList['pri']['type']    = 'pri';
$config->opportunity->dtable->fieldList['pri']['priList'] = $lang->opportunity->priList;

$config->opportunity->dtable->fieldList['ratio']['title'] = $lang->opportunity->ratio;
$config->opportunity->dtable->fieldList['ratio']['type']  = 'number';
$config->opportunity->dtable->fieldList['ratio']['width'] = '80px';

$config->opportunity->dtable->fieldList['status']['title']     = $lang->opportunity->status;
$config->opportunity->dtable->fieldList['status']['statusMap'] = $lang->opportunity->statusList;
$config->opportunity->dtable->fieldList['status']['type']      = 'status';

$config->opportunity->dtable->fieldList['type']['title']    = $lang->opportunity->type;
$config->opportunity->dtable->fieldList['type']['type']     = 'category';
$config->opportunity->dtable->fieldList['type']['map']      = $lang->opportunity->typeList;
$config->opportunity->dtable->fieldList['type']['sortType'] = true;

$config->opportunity->dtable->fieldList['identifiedDate']['type']     = 'date';
$config->opportunity->dtable->fieldList['identifiedDate']['sortType'] = true;

$config->opportunity->dtable->fieldList['assignedTo']['title']       = $lang->opportunity->assignedTo;
$config->opportunity->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->opportunity->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'opportunity', 'method' => 'assignTo', 'params' => 'opportunityID={id}');

$config->opportunity->dtable->fieldList['strategy']['title']    = $lang->opportunity->strategy;
$config->opportunity->dtable->fieldList['strategy']['type']     = 'text';
$config->opportunity->dtable->fieldList['strategy']['map']      = $lang->opportunity->strategyList;
$config->opportunity->dtable->fieldList['strategy']['sortType'] = true;

$config->opportunity->dtable->fieldList['actions']['type'] = 'actions';
$config->opportunity->dtable->fieldList['actions']['menu'] = array('track', 'close', 'cancel', 'hangup', 'activate', 'edit');
$config->opportunity->dtable->fieldList['actions']['list'] = $config->opportunity->actionList;

$config->opportunity->dtable->importOpportunity = new stdClass();
$config->opportunity->dtable->importOpportunity->fieldList['id']       = $config->opportunity->dtable->fieldList['id'];
$config->opportunity->dtable->importOpportunity->fieldList['name']     = $config->opportunity->dtable->fieldList['name'];
$config->opportunity->dtable->importOpportunity->fieldList['strategy'] = $config->opportunity->dtable->fieldList['strategy'];
$config->opportunity->dtable->importOpportunity->fieldList['ratio']    = $config->opportunity->dtable->fieldList['ratio'];
$config->opportunity->dtable->importOpportunity->fieldList['pri']      = $config->opportunity->dtable->fieldList['pri'];
$config->opportunity->dtable->importOpportunity->fieldList['type']     = $config->opportunity->dtable->fieldList['type'];

$config->opportunity->dtable->importOpportunity->fieldList['name']['data-toggle'] = 'modal';
$config->opportunity->dtable->importOpportunity->fieldList['name']['data-size']   = 'lg';
$config->opportunity->dtable->importOpportunity->fieldList['name']['data-type']   = 'iframe';
$config->opportunity->dtable->importOpportunity->fieldList['name']['link']        = array('module' => 'assetlib', 'method' => 'opportunityView', 'params' => 'opportunityID={id}', 'onlybody' => true);
