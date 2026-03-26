<?php
global $lang;

$config->demand->dtable = new stdclass();
$config->demand->dtable->fieldList = array();
$config->demand->dtable->fieldList['id']['title'] = $lang->idAB;
$config->demand->dtable->fieldList['id']['type']  = 'checkID';

$config->demand->dtable->fieldList['title']['title']        = $lang->demand->title;
$config->demand->dtable->fieldList['title']['fixed']        = 'left';
$config->demand->dtable->fieldList['title']['nestedToggle'] = true;
$config->demand->dtable->fieldList['title']['type']         = 'title';
$config->demand->dtable->fieldList['title']['link']         = array('module' => 'demand', 'method' => 'view', 'params' => "demandID={id}");

$config->demand->dtable->fieldList['pri']['title'] = $lang->demand->priAB;
$config->demand->dtable->fieldList['pri']['type']  = 'pri';
$config->demand->dtable->fieldList['pri']['show']  = true;

$config->demand->dtable->fieldList['status']['title']     = $lang->demand->status;
$config->demand->dtable->fieldList['status']['type']      = 'status';
$config->demand->dtable->fieldList['status']['statusMap'] = $lang->demand->statusList;
$config->demand->dtable->fieldList['status']['show']      = true;

$config->demand->dtable->fieldList['stage']['title']     = $lang->demand->stage;
$config->demand->dtable->fieldList['stage']['type']      = 'status';
$config->demand->dtable->fieldList['stage']['statusMap'] = $lang->demand->stageList;
$config->demand->dtable->fieldList['stage']['show']      = true;
$config->demand->dtable->fieldList['stage']['width']     = '90';
$config->demand->dtable->fieldList['stage']['sortType']  = true;

$config->demand->dtable->fieldList['assignedTo']['title']      = $lang->demand->assignedTo;
$config->demand->dtable->fieldList['assignedTo']['type']       = 'assign';
$config->demand->dtable->fieldList['assignedTo']['assignLink'] = array('module' => 'demand', 'method' => 'assignTo', 'params' => 'demandID={id}');
$config->demand->dtable->fieldList['assignedTo']['show']       = true;
$config->demand->dtable->fieldList['assignedTo']['dataSource'] = array('module' => 'demandpool', 'method' => 'getAssignedTo', 'params' => '$poolID');

$config->demand->dtable->fieldList['category']['title'] = $lang->demand->category;
$config->demand->dtable->fieldList['category']['type']  = 'category';
$config->demand->dtable->fieldList['category']['map']   = $lang->demand->categoryList;
$config->demand->dtable->fieldList['category']['show']  = true;

$config->demand->dtable->fieldList['duration']['title']      = $lang->demand->duration;
$config->demand->dtable->fieldList['duration']['type']       = 'category';
$config->demand->dtable->fieldList['duration']['map']        = $lang->demand->durationList;
$config->demand->dtable->fieldList['duration']['show']       = true;
$config->demand->dtable->fieldList['duration']['dataSource'] = array('lang' => 'durationList');

$config->demand->dtable->fieldList['BSA']['title']      = $lang->demand->BSA;
$config->demand->dtable->fieldList['BSA']['type']       = 'category';
$config->demand->dtable->fieldList['BSA']['map']        = $lang->demand->bsaList;
$config->demand->dtable->fieldList['BSA']['dataSource'] = array('lang' => 'bsaList');

$config->demand->dtable->fieldList['product']['title']      = $lang->demand->product;
$config->demand->dtable->fieldList['product']['type']       = 'text';
$config->demand->dtable->fieldList['product']['show']       = true;
$config->demand->dtable->fieldList['product']['control']    = 'multiple';
$config->demand->dtable->fieldList['product']['dataSource'] = array('module' => 'product', 'method' => 'getProductByPool', 'params' => '$poolID');

$config->demand->dtable->fieldList['source']['title'] = $lang->demand->source;
$config->demand->dtable->fieldList['source']['type']  = 'category';
$config->demand->dtable->fieldList['source']['map']   = $lang->demand->sourceList;

$config->demand->dtable->fieldList['sourceNote']['title'] = $lang->demand->sourceNote;
$config->demand->dtable->fieldList['sourceNote']['type']  = 'text';

$config->demand->dtable->fieldList['feedbackedBy']['title'] = $lang->demand->feedbackedBy;
$config->demand->dtable->fieldList['feedbackedBy']['type']  = 'user';

$config->demand->dtable->fieldList['email']['title'] = $lang->demand->email;
$config->demand->dtable->fieldList['email']['type']  = 'text';

$config->demand->dtable->fieldList['reviewedBy']['title'] = $lang->demand->reviewedBy;
$config->demand->dtable->fieldList['reviewedBy']['type']  = 'category';
$config->demand->dtable->fieldList['reviewedBy']['show']  = true;

$config->demand->dtable->fieldList['reviewedDate']['title'] = $lang->demand->reviewedDate;
$config->demand->dtable->fieldList['reviewedDate']['type']  = 'datetime';

$config->demand->dtable->fieldList['createdBy']['title'] = $lang->demand->createdBy;
$config->demand->dtable->fieldList['createdBy']['type']  = 'user';
$config->demand->dtable->fieldList['createdBy']['show']  = true;

$config->demand->dtable->fieldList['createdDate']['title'] = $lang->demand->createdDate;
$config->demand->dtable->fieldList['createdDate']['type']  = 'datetime';

$config->demand->dtable->fieldList['assignedDate']['title'] = $lang->demand->assignedDate;
$config->demand->dtable->fieldList['assignedDate']['type']  = 'datetime';

$config->demand->dtable->fieldList['closedBy']['title'] = $lang->demand->closedBy;
$config->demand->dtable->fieldList['closedBy']['type']  = 'user';

$config->demand->dtable->fieldList['closedDate']['title'] = $lang->demand->closedDate;
$config->demand->dtable->fieldList['closedDate']['type']  = 'datetime';

$config->demand->dtable->fieldList['closedReason']['title'] = $lang->demand->closedReason;
$config->demand->dtable->fieldList['closedReason']['type']  = 'category';
$config->demand->dtable->fieldList['closedReason']['map']   = $lang->demand->reasonList;

$config->demand->dtable->fieldList['lastEditedBy']['title'] = $lang->demand->lastEditedBy;
$config->demand->dtable->fieldList['lastEditedBy']['type']  = 'user';

$config->demand->dtable->fieldList['lastEditedDate']['title'] = $lang->demand->lastEditedDate;
$config->demand->dtable->fieldList['lastEditedDate']['type']  = 'datetime';

$config->demand->dtable->fieldList['activatedDate']['title'] = $lang->demand->activatedDate;
$config->demand->dtable->fieldList['activatedDate']['type']  = 'datetime';

$config->demand->dtable->fieldList['mailto']['title'] = $lang->demand->mailto;
$config->demand->dtable->fieldList['mailto']['type']  = 'category';

$config->demand->dtable->fieldList['keywords']['title'] = $lang->demand->keywords;
$config->demand->dtable->fieldList['keywords']['type']  = 'text';

$config->demand->dtable->fieldList['version']['title'] = $lang->demand->version;
$config->demand->dtable->fieldList['version']['type']  = 'text';

$config->demand->dtable->fieldList['relatedObject']['name']        = 'relatedObject';
$config->demand->dtable->fieldList['relatedObject']['title']       = $lang->custom->relateObject;
$config->demand->dtable->fieldList['relatedObject']['sortType']    = false;
$config->demand->dtable->fieldList['relatedObject']['width']       = '70';
$config->demand->dtable->fieldList['relatedObject']['type']        = 'text';
$config->demand->dtable->fieldList['relatedObject']['link']        = common::hasPriv('custom', 'showRelationGraph') ? "RAWJS<function(info){ if(info.row.data.relatedObject == 0) return 0; else return '" . helper::createLink('custom', 'showRelationGraph', 'objectID={id}&objectType=demand') . "'; }>RAWJS" : null;
$config->demand->dtable->fieldList['relatedObject']['data-toggle'] = 'modal';
$config->demand->dtable->fieldList['relatedObject']['data-size']   = 'lg';
$config->demand->dtable->fieldList['relatedObject']['show']        = true;
$config->demand->dtable->fieldList['relatedObject']['group']       = 8;
$config->demand->dtable->fieldList['relatedObject']['flex']        = false;
$config->demand->dtable->fieldList['relatedObject']['align']       = 'center';

$config->demand->dtable->fieldList['actions']['name']     = 'actions';
$config->demand->dtable->fieldList['actions']['title']    = $lang->actions;
$config->demand->dtable->fieldList['actions']['type']     = 'actions';
$config->demand->dtable->fieldList['actions']['sortType'] = false;
$config->demand->dtable->fieldList['actions']['width']    = '';
$config->demand->dtable->fieldList['actions']['list']     = $config->demand->actionList;
$config->demand->dtable->fieldList['actions']['menu']     = array(array('processDemandChange'), array('change', 'submitReview|review', 'recall', 'distribute', 'edit', 'batchCreate', 'close|activate'));
