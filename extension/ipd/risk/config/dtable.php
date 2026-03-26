<?php
global $app;
$config->risk->dtable = new stdclass();

$config->risk->dtable->fieldList['id']['title']    = $lang->idAB;
$config->risk->dtable->fieldList['id']['type']     = 'checkID';
$config->risk->dtable->fieldList['id']['sortType'] = true;
$config->risk->dtable->fieldList['id']['checkbox'] = true;
$config->risk->dtable->fieldList['id']['required'] = true;

$config->risk->dtable->fieldList['name']['title']    = $lang->risk->name;
$config->risk->dtable->fieldList['name']['fixed']    = 'left';
$config->risk->dtable->fieldList['name']['flex']     = 1;
$config->risk->dtable->fieldList['name']['type']     = 'nestedTitle';
$config->risk->dtable->fieldList['name']['sortType'] = true;
$config->risk->dtable->fieldList['name']['link']     = array('url' => array('module' => 'risk', 'method' => 'view', 'params' => 'riskID={id}&from={from}'));
$config->risk->dtable->fieldList['name']['required'] = true;
$config->risk->dtable->fieldList['name']['styleMap'] = array('--color-link' => 'color');
$config->risk->dtable->fieldList['name']['data-app'] = $app->tab;

$config->risk->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->risk->dtable->fieldList['pri']['type']     = 'pri';
$config->risk->dtable->fieldList['pri']['priList']  = $lang->risk->priList;
$config->risk->dtable->fieldList['pri']['sortType'] = true;
$config->risk->dtable->fieldList['pri']['show']     = true;
$config->risk->dtable->fieldList['pri']['group']    = 1;

$config->risk->dtable->fieldList['rate']['title']    = $lang->risk->rate;
$config->risk->dtable->fieldList['rate']['sortType'] = true;
$config->risk->dtable->fieldList['rate']['show']     = true;
$config->risk->dtable->fieldList['rate']['group']    = 1;

$config->risk->dtable->fieldList['status']['title']     = $lang->risk->status;
$config->risk->dtable->fieldList['status']['statusMap'] = $lang->risk->statusList;
$config->risk->dtable->fieldList['status']['type']      = 'status';
$config->risk->dtable->fieldList['status']['sortType']  = true;
$config->risk->dtable->fieldList['status']['show']      = true;
$config->risk->dtable->fieldList['status']['group']     = 1;

$config->risk->dtable->fieldList['category']['title']    = $lang->risk->category;
$config->risk->dtable->fieldList['category']['type']     = 'text';
$config->risk->dtable->fieldList['category']['map']      = $lang->risk->categoryList;
$config->risk->dtable->fieldList['category']['sortType'] = true;
$config->risk->dtable->fieldList['category']['show']     = true;
$config->risk->dtable->fieldList['category']['group']    = 2;

$config->risk->dtable->fieldList['identifiedDate']['type']     = 'date';
$config->risk->dtable->fieldList['identifiedDate']['sortType'] = true;
$config->risk->dtable->fieldList['identifiedDate']['group']    = 3;

$config->risk->dtable->fieldList['assignedTo']['title']       = $lang->risk->assignedTo;
$config->risk->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->risk->dtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->risk->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'risk', 'method' => 'assignTo', 'params' => 'riskID={id}');
$config->risk->dtable->fieldList['assignedTo']['sortType']    = true;
$config->risk->dtable->fieldList['assignedTo']['show']        = true;
$config->risk->dtable->fieldList['assignedTo']['group']       = 4;

$config->risk->dtable->fieldList['strategy']['title']    = $lang->risk->strategy;
$config->risk->dtable->fieldList['strategy']['type']     = 'text';
$config->risk->dtable->fieldList['strategy']['map']      = $lang->risk->strategyList;
$config->risk->dtable->fieldList['strategy']['sortType'] = true;
$config->risk->dtable->fieldList['strategy']['show']     = true;
$config->risk->dtable->fieldList['strategy']['group']    = 5;

$config->risk->dtable->fieldList['relatedObject']['name']        = 'relatedObject';
$config->risk->dtable->fieldList['relatedObject']['title']       = $lang->custom->relateObject;
$config->risk->dtable->fieldList['relatedObject']['sortType']    = false;
$config->risk->dtable->fieldList['relatedObject']['width']       = '70';
$config->risk->dtable->fieldList['relatedObject']['type']        = 'text';
$config->risk->dtable->fieldList['relatedObject']['link']        = common::hasPriv('custom', 'showRelationGraph') ? "RAWJS<function(info){ if(info.row.data.relatedObject == 0) return 0; else return '" . helper::createLink('custom', 'showRelationGraph', 'objectID={id}&objectType=risk') . "'; }>RAWJS" : null;
$config->risk->dtable->fieldList['relatedObject']['data-toggle'] = 'modal';
$config->risk->dtable->fieldList['relatedObject']['data-size']   = 'lg';
$config->risk->dtable->fieldList['relatedObject']['show']        = true;
$config->risk->dtable->fieldList['relatedObject']['group']       = 6;
$config->risk->dtable->fieldList['relatedObject']['flex']        = false;
$config->risk->dtable->fieldList['relatedObject']['align']       = 'center';

$config->risk->dtable->fieldList['actions']['type']     = 'actions';
$config->risk->dtable->fieldList['actions']['menu']     = array('track', 'close', 'cancel', 'hangup', 'divider', 'activate', 'createForObject', 'edit');
$config->risk->dtable->fieldList['actions']['list']     = $config->risk->actionList;

$config->risk->dtable->importRisk = new stdClass();
$config->risk->dtable->importRisk->fieldList['id']       = $config->risk->dtable->fieldList['id'];
$config->risk->dtable->importRisk->fieldList['name']     = $config->risk->dtable->fieldList['name'];
$config->risk->dtable->importRisk->fieldList['strategy'] = $config->risk->dtable->fieldList['strategy'];
$config->risk->dtable->importRisk->fieldList['rate']     = $config->risk->dtable->fieldList['rate'];
$config->risk->dtable->importRisk->fieldList['pri']      = $config->risk->dtable->fieldList['pri'];
$config->risk->dtable->importRisk->fieldList['category'] = $config->risk->dtable->fieldList['category'];

$config->risk->dtable->importRisk->fieldList['name']['data-toggle'] = 'modal';
$config->risk->dtable->importRisk->fieldList['name']['data-size']   = 'lg';
$config->risk->dtable->importRisk->fieldList['name']['data-type']   = 'iframe';
$config->risk->dtable->importRisk->fieldList['name']['link']        = array('module' => 'assetlib', 'method' => 'riskView', 'params' => 'riskID={id}', 'onlybody' => true);
