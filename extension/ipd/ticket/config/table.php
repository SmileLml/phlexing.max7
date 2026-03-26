<?php
global $lang,$config;

$config->ticket->dtable = new stdclass();
$config->ticket->dtable->fieldList = array();
$config->ticket->dtable->fieldList['id']['name']  = 'id';
$config->ticket->dtable->fieldList['id']['title'] = $lang->idAB;
$config->ticket->dtable->fieldList['id']['type']  = 'checkID';
$config->ticket->dtable->fieldList['id']['show']  = true;

$config->ticket->dtable->fieldList['title']['name']  = 'title';
$config->ticket->dtable->fieldList['title']['title'] = $lang->ticket->title;
$config->ticket->dtable->fieldList['title']['type']  = 'title';
$config->ticket->dtable->fieldList['title']['link']  = array('module' => 'ticket', 'method' => 'view', 'params' => 'id={id}');
$config->ticket->dtable->fieldList['title']['fixed'] = 'left';
$config->ticket->dtable->fieldList['title']['show']  = true;

$config->ticket->dtable->fieldList['product']['name']     = 'product';
$config->ticket->dtable->fieldList['product']['title']    = $lang->ticket->product;
$config->ticket->dtable->fieldList['product']['type']     = 'category';
$config->ticket->dtable->fieldList['product']['sortType'] = true;

$config->ticket->dtable->fieldList['pri']['name']    = 'pri';
$config->ticket->dtable->fieldList['pri']['title']   = $lang->priAB;
$config->ticket->dtable->fieldList['pri']['type']    = 'pri';
$config->ticket->dtable->fieldList['pri']['priList'] = $lang->ticket->priList;
$config->ticket->dtable->fieldList['pri']['show']    = true;

$config->ticket->dtable->fieldList['feedback']['name']       = 'feedbackTip';
$config->ticket->dtable->fieldList['feedback']['title']      = $lang->ticket->from;
$config->ticket->dtable->fieldList['feedback']['width']      = '120';
$config->ticket->dtable->fieldList['feedback']['type']       = 'category';
$config->ticket->dtable->fieldList['feedback']['link']       = array('module' => 'feedback', 'method' => 'adminView', 'params' => 'feedbackID={feedback}');
$config->ticket->dtable->fieldList['feedback']['dataSource'] = array('module' => 'feedback', 'method' => 'getPairs');
$config->ticket->dtable->fieldList['feedback']['show']       = true;

$config->ticket->dtable->fieldList['status']['name']      = 'status';
$config->ticket->dtable->fieldList['status']['title']     = $lang->ticket->status;
$config->ticket->dtable->fieldList['status']['type']      = 'status';
$config->ticket->dtable->fieldList['status']['statusMap'] = $lang->ticket->statusList;
$config->ticket->dtable->fieldList['status']['show']      = true;

$config->ticket->dtable->fieldList['type']['name']  = 'type';
$config->ticket->dtable->fieldList['type']['title'] = $lang->ticket->type;
$config->ticket->dtable->fieldList['type']['type']  = 'category';
$config->ticket->dtable->fieldList['type']['map']   = $lang->ticket->typeList;
$config->ticket->dtable->fieldList['type']['show']  = true;

$config->ticket->dtable->fieldList['assignedTo']['name']       = 'assignedTo';
$config->ticket->dtable->fieldList['assignedTo']['title']      = $lang->ticket->assignedTo;
$config->ticket->dtable->fieldList['assignedTo']['type']       = 'assign';
$config->ticket->dtable->fieldList['assignedTo']['assignLink'] = array('module' => 'ticket', 'method' => 'assignTo', 'params' => 'ticketID={id}', 'onlybody' => true);
$config->ticket->dtable->fieldList['assignedTo']['data-type']  = 'iframe';
$config->ticket->dtable->fieldList['assignedTo']['show']       = true;

$config->ticket->dtable->fieldList['estimate']['name']  = 'estimate';
$config->ticket->dtable->fieldList['estimate']['title'] = $lang->ticket->estimate;
$config->ticket->dtable->fieldList['estimate']['width'] = '80';
$config->ticket->dtable->fieldList['estimate']['type']  = 'category';
$config->ticket->dtable->fieldList['estimate']['show']  = true;

$config->ticket->dtable->fieldList['openedBy']['name']       = 'openedBy';
$config->ticket->dtable->fieldList['openedBy']['title']      = $lang->ticket->openedBy;
$config->ticket->dtable->fieldList['openedBy']['type']       = 'user';
$config->ticket->dtable->fieldList['openedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');
$config->ticket->dtable->fieldList['openedBy']['show']       = true;

$config->ticket->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->ticket->dtable->fieldList['openedDate']['title'] = $lang->ticket->openedDate;
$config->ticket->dtable->fieldList['openedDate']['type']  = 'date';
$config->ticket->dtable->fieldList['openedDate']['show']  = true;

$config->ticket->dtable->fieldList['deadline']['name']  = 'deadline';
$config->ticket->dtable->fieldList['deadline']['title'] = $lang->ticket->deadline;
$config->ticket->dtable->fieldList['deadline']['width'] = '80';
$config->ticket->dtable->fieldList['deadline']['type']  = 'date';
$config->ticket->dtable->fieldList['deadline']['show']  = true;

$config->ticket->dtable->fieldList['consumed']['name']  = 'consumed';
$config->ticket->dtable->fieldList['consumed']['title'] = $lang->ticket->consumed;
$config->ticket->dtable->fieldList['consumed']['width'] = '90';
$config->ticket->dtable->fieldList['consumed']['type']  = 'int';

$config->ticket->dtable->fieldList['openedBuild']['name']       = 'openedBuild';
$config->ticket->dtable->fieldList['openedBuild']['title']      = $lang->ticket->openedBuild;
$config->ticket->dtable->fieldList['openedBuild']['type']       = 'category';
$config->ticket->dtable->fieldList['openedBuild']['width']      = '120';
$config->ticket->dtable->fieldList['openedBuild']['dataSource'] = array('module' => 'build', 'method' =>'getBuildPairs', 'params' => array('productIdList' => array()));

$config->ticket->dtable->fieldList['keywords']['name']  = 'keywords';
$config->ticket->dtable->fieldList['keywords']['title'] = $lang->ticket->keywords;
$config->ticket->dtable->fieldList['keywords']['width'] = '90';

$config->ticket->dtable->fieldList['mailto']['name']       = 'mailto';
$config->ticket->dtable->fieldList['mailto']['title']      = $lang->ticket->mailto;
$config->ticket->dtable->fieldList['mailto']['width']      = '90';
$config->ticket->dtable->fieldList['mailto']['type']       = 'user';
$config->ticket->dtable->fieldList['mailto']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->ticket->dtable->fieldList['startedBy']['name']       = 'startedBy';
$config->ticket->dtable->fieldList['startedBy']['title']      = $lang->ticket->startedBy;
$config->ticket->dtable->fieldList['startedBy']['width']      = '100';
$config->ticket->dtable->fieldList['startedBy']['type']       = 'user';
$config->ticket->dtable->fieldList['startedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->ticket->dtable->fieldList['startedDate']['name']  = 'startedDate';
$config->ticket->dtable->fieldList['startedDate']['title'] = $lang->ticket->startedDate;
$config->ticket->dtable->fieldList['startedDate']['width'] = '100';
$config->ticket->dtable->fieldList['startedDate']['type']  = 'date';

$config->ticket->dtable->fieldList['finishedBy']['name']  = 'finishedByAB';
$config->ticket->dtable->fieldList['finishedBy']['title'] = $lang->ticket->finishedByAB;
$config->ticket->dtable->fieldList['finishedBy']['width'] = '100';
$config->ticket->dtable->fieldList['finishedBy']['type']  = 'user';

$config->ticket->dtable->fieldList['finishedDate']['name']  = 'finishedDate';
$config->ticket->dtable->fieldList['finishedDate']['title'] = $lang->ticket->finishedDate;
$config->ticket->dtable->fieldList['finishedDate']['width'] = '110';
$config->ticket->dtable->fieldList['finishedDate']['type']  = 'date';

$config->ticket->dtable->fieldList['closedBy']['name']  = 'closedByAB';
$config->ticket->dtable->fieldList['closedBy']['title'] = $lang->ticket->closedByAB;
$config->ticket->dtable->fieldList['closedBy']['width'] = '100';
$config->ticket->dtable->fieldList['closedBy']['type']  = 'user';

$config->ticket->dtable->fieldList['closedDate']['name']  = 'closedDate';
$config->ticket->dtable->fieldList['closedDate']['title'] = $lang->ticket->closedDate;
$config->ticket->dtable->fieldList['closedDate']['width'] = '100';
$config->ticket->dtable->fieldList['closedDate']['type']  = 'date';

$config->ticket->dtable->fieldList['closedReason']['name']  = 'closedReason';
$config->ticket->dtable->fieldList['closedReason']['title'] = $lang->ticket->closedReason;
$config->ticket->dtable->fieldList['closedReason']['width'] = '110';

$config->ticket->dtable->fieldList['activatedBy']['name']       = 'activatedBy';
$config->ticket->dtable->fieldList['activatedBy']['title']      = $lang->ticket->activatedBy;
$config->ticket->dtable->fieldList['activatedBy']['width']      = '100';
$config->ticket->dtable->fieldList['activatedBy']['type']       = 'user';
$config->ticket->dtable->fieldList['activatedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->ticket->dtable->fieldList['activatedDate']['name']  = 'activatedDate';
$config->ticket->dtable->fieldList['activatedDate']['title'] = $lang->ticket->activatedDate;
$config->ticket->dtable->fieldList['activatedDate']['width'] = '110';
$config->ticket->dtable->fieldList['activatedDate']['type']  = 'date';

$config->ticket->dtable->fieldList['activatedCount']['name']  = 'activatedCount';
$config->ticket->dtable->fieldList['activatedCount']['title'] = $lang->ticket->activatedCount;
$config->ticket->dtable->fieldList['activatedCount']['width'] = '120';

$config->ticket->dtable->fieldList['editedBy']['name']       = 'editedBy';
$config->ticket->dtable->fieldList['editedBy']['title']      = $lang->ticket->editedBy;
$config->ticket->dtable->fieldList['editedBy']['width']      = '100';
$config->ticket->dtable->fieldList['editedBy']['type']       = 'user';
$config->ticket->dtable->fieldList['editedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->ticket->dtable->fieldList['editedDate']['name']  = 'lastEditedDate';
$config->ticket->dtable->fieldList['editedDate']['title'] = $lang->ticket->lastEditedDate;
$config->ticket->dtable->fieldList['editedDate']['width'] = '130';
$config->ticket->dtable->fieldList['editedDate']['type']  = 'date';

$config->ticket->dtable->fieldList['legendMisc']['name']  = 'legendMisc';
$config->ticket->dtable->fieldList['legendMisc']['title'] = $lang->ticket->legendMisc;
$config->ticket->dtable->fieldList['legendMisc']['width'] = '150';
$config->ticket->dtable->fieldList['legendMisc']['sort']  = false;

$config->ticket->dtable->fieldList['relatedObject']['name']        = 'relatedObject';
$config->ticket->dtable->fieldList['relatedObject']['title']       = $lang->custom->relateObject;
$config->ticket->dtable->fieldList['relatedObject']['sortType']    = false;
$config->ticket->dtable->fieldList['relatedObject']['width']       = '70';
$config->ticket->dtable->fieldList['relatedObject']['type']        = 'text';
$config->ticket->dtable->fieldList['relatedObject']['link']        = common::hasPriv('custom', 'showRelationGraph') ? "RAWJS<function(info){ if(info.row.data.relatedObject == 0) return 0; else return '" . helper::createLink('custom', 'showRelationGraph', 'objectID={id}&objectType=ticket') . "'; }>RAWJS" : null;
$config->ticket->dtable->fieldList['relatedObject']['data-toggle'] = 'modal';
$config->ticket->dtable->fieldList['relatedObject']['data-size']   = 'lg';
$config->ticket->dtable->fieldList['relatedObject']['show']        = true;
$config->ticket->dtable->fieldList['relatedObject']['group']       = 8;
$config->ticket->dtable->fieldList['relatedObject']['flex']        = false;
$config->ticket->dtable->fieldList['relatedObject']['align']       = 'center';

$config->ticket->dtable->fieldList['actions']['name']     = 'actions';
$config->ticket->dtable->fieldList['actions']['title']    = $lang->actions;
$config->ticket->dtable->fieldList['actions']['type']     = 'actions';
$config->ticket->dtable->fieldList['actions']['width']    = '140';
$config->ticket->dtable->fieldList['actions']['sortType'] = false;
$config->ticket->dtable->fieldList['actions']['list']     = $config->ticket->actionList;
$config->ticket->dtable->fieldList['actions']['menu']     = array('start', 'finish|activate', 'close', 'recordHour', 'createStory', 'createBug', 'edit');
