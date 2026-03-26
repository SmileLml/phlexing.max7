<?php
global $lang, $app;
$config->review->dtable = new stdclass();
$config->review->dtable->defaultField = array('id', 'title', 'product', 'category', 'version', 'status', 'reviewedBy', 'reviewer', 'createdBy', 'createdDate', 'deadline', 'lastReviewedDate', 'result', 'lastAuditedDate', 'auditResult', 'actions');

$config->review->dtable->fieldList['id']['title']    = $lang->idAB;
$config->review->dtable->fieldList['id']['type']     = 'id';
$config->review->dtable->fieldList['id']['fixed']    = 'left';
$config->review->dtable->fieldList['id']['sortType'] = true;
$config->review->dtable->fieldList['id']['required'] = true;
$config->review->dtable->fieldList['id']['group']    = 1;

$config->review->dtable->fieldList['title']['title']    = $lang->review->title;
$config->review->dtable->fieldList['title']['type']     = 'title';
$config->review->dtable->fieldList['title']['fixed']    = 'left';
$config->review->dtable->fieldList['title']['link']     = array('module' => 'review', 'method' => 'view', 'params' => "reviewID={id}");
$config->review->dtable->fieldList['title']['required'] = true;
$config->review->dtable->fieldList['title']['group']    = 1;
$config->review->dtable->fieldList['title']['data-app'] = $app->tab;
$config->review->dtable->fieldList['title']['sortType'] = true;

$config->review->dtable->fieldList['product']['name']    = 'product';
$config->review->dtable->fieldList['product']['title']   = $lang->review->product;
$config->review->dtable->fieldList['product']['type']    = 'text';
$config->review->dtable->fieldList['product']['show']    = true;
$config->review->dtable->fieldList['product']['sortType'] = true;

$config->review->dtable->fieldList['category']['name']    = 'category';
$config->review->dtable->fieldList['category']['title']   = $lang->review->object;
$config->review->dtable->fieldList['category']['type']    = 'text';
$config->review->dtable->fieldList['category']['show']    = true;
$config->review->dtable->fieldList['category']['sortType'] = true;

$config->review->dtable->fieldList['version']['name']     = 'version';
$config->review->dtable->fieldList['version']['title']    = $lang->review->version;
$config->review->dtable->fieldList['version']['width']    = '180';
$config->review->dtable->fieldList['version']['show']     = true;
$config->review->dtable->fieldList['version']['required'] = false;
$config->review->dtable->fieldList['version']['sortType'] = true;

$config->review->dtable->fieldList['status']['name']      = 'status';
$config->review->dtable->fieldList['status']['title']     = $lang->review->status;
$config->review->dtable->fieldList['status']['width']     = '100';
$config->review->dtable->fieldList['status']['show']      = true;
$config->review->dtable->fieldList['status']['required']  = false;
$config->review->dtable->fieldList['status']['type']      = 'status';
$config->review->dtable->fieldList['status']['statusMap'] = $lang->review->statusList;
$config->review->dtable->fieldList['status']['sortType']  = true;

$config->review->dtable->fieldList['reviewedBy']['name']     = 'reviewedBy';
$config->review->dtable->fieldList['reviewedBy']['title']    = $lang->review->reviewedBy;
$config->review->dtable->fieldList['reviewedBy']['width']    = '150';
$config->review->dtable->fieldList['reviewedBy']['show']     = true;
$config->review->dtable->fieldList['reviewedBy']['type']     = 'text';
$config->review->dtable->fieldList['reviewedBy']['required'] = false;
$config->review->dtable->fieldList['reviewedBy']['sortType'] = true;

$config->review->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->review->dtable->fieldList['createdBy']['title']    = $lang->review->createdBy;
$config->review->dtable->fieldList['createdBy']['width']    = '120';
$config->review->dtable->fieldList['createdBy']['show']     = true;
$config->review->dtable->fieldList['createdBy']['type']     = 'user';
$config->review->dtable->fieldList['createdBy']['required'] = false;

$config->review->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->review->dtable->fieldList['createdDate']['title']    = $lang->review->createdDate;
$config->review->dtable->fieldList['createdDate']['width']    = '120';
$config->review->dtable->fieldList['createdDate']['show']     = true;
$config->review->dtable->fieldList['createdDate']['type']     = 'date';
$config->review->dtable->fieldList['createdDate']['required'] = false;

$config->review->dtable->fieldList['deadline']['name']     = 'deadline';
$config->review->dtable->fieldList['deadline']['title']    = $lang->review->deadline;
$config->review->dtable->fieldList['deadline']['width']    = '120';
$config->review->dtable->fieldList['deadline']['show']     = true;
$config->review->dtable->fieldList['deadline']['type']     = 'date';
$config->review->dtable->fieldList['deadline']['required'] = false;

$config->review->dtable->fieldList['lastReviewedDate']['name']     = 'lastReviewedDate';
$config->review->dtable->fieldList['lastReviewedDate']['title']    = $lang->review->lastReviewedDate;
$config->review->dtable->fieldList['lastReviewedDate']['width']    = '120';
$config->review->dtable->fieldList['lastReviewedDate']['show']     = true;
$config->review->dtable->fieldList['lastReviewedDate']['type']     = 'date';
$config->review->dtable->fieldList['lastReviewedDate']['required'] = false;

$config->review->dtable->fieldList['result']['name']      = 'result';
$config->review->dtable->fieldList['result']['title']     = $lang->review->result;
$config->review->dtable->fieldList['result']['type']      = 'status';
$config->review->dtable->fieldList['result']['statusMap'] = $lang->review->resultList;
$config->review->dtable->fieldList['result']['width']     = '120';
$config->review->dtable->fieldList['result']['show']      = true;
$config->review->dtable->fieldList['result']['required']  = false;

$config->review->dtable->fieldList['lastAuditedDate']['name']     = 'lastAuditedDate';
$config->review->dtable->fieldList['lastAuditedDate']['title']    = $lang->review->lastAuditedDate;
$config->review->dtable->fieldList['lastAuditedDate']['width']    = '120';
$config->review->dtable->fieldList['lastAuditedDate']['show']     = true;
$config->review->dtable->fieldList['lastAuditedDate']['type']     = 'date';
$config->review->dtable->fieldList['lastAuditedDate']['required'] = false;

$config->review->dtable->fieldList['auditResult']['name']     = 'auditResult';
$config->review->dtable->fieldList['auditResult']['title']    = $lang->review->auditResult;
$config->review->dtable->fieldList['auditResult']['width']    = '120';
$config->review->dtable->fieldList['auditResult']['map']      = $lang->review->auditResultList;
$config->review->dtable->fieldList['auditResult']['show']     = true;
$config->review->dtable->fieldList['auditResult']['required'] = false;

$config->review->dtable->fieldList['actions']['title']    = $lang->actions;
$config->review->dtable->fieldList['actions']['type']     = 'actions';
$config->review->dtable->fieldList['actions']['width']    = '140';
$config->review->dtable->fieldList['actions']['sortType'] = false;
$config->review->dtable->fieldList['actions']['fixed']    = 'right';
$config->review->dtable->fieldList['actions']['list']     = $config->review->actionList;
$config->review->dtable->fieldList['actions']['menu']     = array('submit', 'recall', 'assess', 'progress', 'report', 'toAudit', 'audit', 'createBaseline', 'edit', 'delete');
