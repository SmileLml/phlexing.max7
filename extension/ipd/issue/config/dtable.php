<?php
global $lang;
$config->issue->dtable = new stdclass();

$config->issue->dtable->fieldList['id']['title']    = $lang->idAB;
$config->issue->dtable->fieldList['id']['type']     = 'checkID';
$config->issue->dtable->fieldList['id']['fixed']    = 'left';
$config->issue->dtable->fieldList['id']['sortType'] = true;
$config->issue->dtable->fieldList['id']['required'] = true;
$config->issue->dtable->fieldList['id']['group']    = 1;

$config->issue->dtable->fieldList['title']['title']    = $lang->issue->title;
$config->issue->dtable->fieldList['title']['type']     = 'title';
$config->issue->dtable->fieldList['title']['fixed']    = 'left';
$config->issue->dtable->fieldList['title']['link']     = array('module' => 'issue', 'method' => 'view', 'params' => "issueID={id}&from={from}");
$config->issue->dtable->fieldList['title']['required'] = true;
$config->issue->dtable->fieldList['title']['sortType'] = true;
$config->issue->dtable->fieldList['title']['group']    = 1;

$config->issue->dtable->fieldList['pri']['title']    = $lang->issue->pri;
$config->issue->dtable->fieldList['pri']['type']     = 'pri';
$config->issue->dtable->fieldList['pri']['show']     = true;
$config->issue->dtable->fieldList['pri']['sortType'] = true;
$config->issue->dtable->fieldList['pri']['group']    = 2;

$config->issue->dtable->fieldList['severity']['title']    = $lang->issue->severity;
$config->issue->dtable->fieldList['severity']['type']     = 'severity';
$config->issue->dtable->fieldList['severity']['show']     = true;
$config->issue->dtable->fieldList['severity']['sortType'] = true;
$config->issue->dtable->fieldList['severity']['group']    = 2;

$config->issue->dtable->fieldList['type']['title']    = $lang->issue->type;
$config->issue->dtable->fieldList['type']['type']     = 'category';
$config->issue->dtable->fieldList['type']['map']      = $lang->issue->typeList;
$config->issue->dtable->fieldList['type']['flex']     = false;
$config->issue->dtable->fieldList['type']['sortType'] = true;
$config->issue->dtable->fieldList['type']['group']    = 3;

$config->issue->dtable->fieldList['owner']['title']    = $lang->issue->owner;
$config->issue->dtable->fieldList['owner']['type']     = 'user';
$config->issue->dtable->fieldList['owner']['show']     = true;
$config->issue->dtable->fieldList['owner']['sortType'] = true;
$config->issue->dtable->fieldList['owner']['group']    = 4;

$config->issue->dtable->fieldList['createdDate']['title']    = $lang->issue->createdDate;
$config->issue->dtable->fieldList['createdDate']['type']     = 'date';
$config->issue->dtable->fieldList['createdDate']['show']     = true;
$config->issue->dtable->fieldList['createdDate']['sortType'] = 'date';
$config->issue->dtable->fieldList['createdDate']['group']    = 4;

$config->issue->dtable->fieldList['assignedTo']['title']      = $lang->issue->assignedTo;
$config->issue->dtable->fieldList['assignedTo']['type']       = 'assign';
$config->issue->dtable->fieldList['assignedTo']['assignLink'] = array('module' => 'issue', 'method' => 'assignTo', 'params' => 'issueID={id}');
$config->issue->dtable->fieldList['assignedTo']['show']       = true;
$config->issue->dtable->fieldList['assignedTo']['sortType']   = true;
$config->issue->dtable->fieldList['assignedTo']['group']    = 5;

$config->issue->dtable->fieldList['assignedBy']['title']    = $lang->issue->assignedBy;
$config->issue->dtable->fieldList['assignedBy']['type']     = 'user';
$config->issue->dtable->fieldList['assignedBy']['show']     = true;
$config->issue->dtable->fieldList['assignedBy']['sortType'] = true;
$config->issue->dtable->fieldList['assignedBy']['group']    = 5;

$config->issue->dtable->fieldList['status']['title']    = $lang->issue->status;
$config->issue->dtable->fieldList['status']['type']     = 'category';
$config->issue->dtable->fieldList['status']['map']      = $lang->issue->statusList;
$config->issue->dtable->fieldList['status']['show']     = true;
$config->issue->dtable->fieldList['status']['sortType'] = true;
$config->issue->dtable->fieldList['status']['group']    = 6;

$config->issue->dtable->fieldList['relatedObject']['name']        = 'relatedObject';
$config->issue->dtable->fieldList['relatedObject']['title']       = $lang->custom->relateObject;
$config->issue->dtable->fieldList['relatedObject']['sortType']    = false;
$config->issue->dtable->fieldList['relatedObject']['width']       = '70';
$config->issue->dtable->fieldList['relatedObject']['type']        = 'text';
$config->issue->dtable->fieldList['relatedObject']['link']        = common::hasPriv('custom', 'showRelationGraph') ? "RAWJS<function(info){ if(info.row.data.relatedObject == 0) return 0; else return '" . helper::createLink('custom', 'showRelationGraph', 'objectID={id}&objectType=issue') . "'; }>RAWJS" : null;
$config->issue->dtable->fieldList['relatedObject']['data-toggle'] = 'modal';
$config->issue->dtable->fieldList['relatedObject']['data-size']   = 'lg';
$config->issue->dtable->fieldList['relatedObject']['show']        = true;
$config->issue->dtable->fieldList['relatedObject']['group']       = 7;
$config->issue->dtable->fieldList['relatedObject']['flex']        = false;
$config->issue->dtable->fieldList['relatedObject']['align']       = 'center';

$config->issue->dtable->fieldList['actions']['type'] = 'actions';
$config->issue->dtable->fieldList['actions']['menu'] = array('confirm', 'resolve', 'close', 'cancel', 'activate', 'createForObject', 'edit');
$config->issue->dtable->fieldList['actions']['list'] = $config->issue->actionList;

$config->issue->dtable->importIssue = new stdclass();
$config->issue->dtable->importIssue->fieldList['id']['title']    = $lang->idAB;
$config->issue->dtable->importIssue->fieldList['id']['type']     = 'checkID';
$config->issue->dtable->importIssue->fieldList['id']['fixed']    = 'left';
$config->issue->dtable->importIssue->fieldList['id']['sortType'] = true;

$config->issue->dtable->importIssue->fieldList['type']['title']    = $lang->issue->type;
$config->issue->dtable->importIssue->fieldList['type']['type']     = 'category';
$config->issue->dtable->importIssue->fieldList['type']['map']      = $lang->issue->typeList;
$config->issue->dtable->importIssue->fieldList['type']['sortType'] = false;

$config->issue->dtable->importIssue->fieldList['title']['title']    = $lang->issue->title;
$config->issue->dtable->importIssue->fieldList['title']['type']     = 'title';
$config->issue->dtable->importIssue->fieldList['title']['link']     = array('module' => 'assetlib', 'method' => 'issueView', 'params' => "issueID={id}");
$config->issue->dtable->importIssue->fieldList['title']['required'] = true;
$config->issue->dtable->importIssue->fieldList['title']['sortType'] = false;

$config->issue->dtable->importIssue->fieldList['severity']['title']    = $lang->issue->severity;
$config->issue->dtable->importIssue->fieldList['severity']['type']     = 'severity';
$config->issue->dtable->importIssue->fieldList['severity']['sortType'] = false;

$config->issue->dtable->importIssue->fieldList['pri']['title']    = $lang->issue->pri;
$config->issue->dtable->importIssue->fieldList['pri']['type']     = 'pri';
$config->issue->dtable->importIssue->fieldList['pri']['sortType'] = false;
