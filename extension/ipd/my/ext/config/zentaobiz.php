<?php
global $lang,$config, $app;
$app->loadLang('ticket');
$config->my->openedDateField['charter'] = 'createdDate';

$config->my->ticket = new stdclass();
$config->my->ticket->actionList = array();
$config->my->ticket->actionList['start']['icon']        = 'play';
$config->my->ticket->actionList['start']['text']        = $lang->ticket->start;
$config->my->ticket->actionList['start']['hint']        = $lang->ticket->start;
$config->my->ticket->actionList['start']['url']         = array('module' => 'ticket', 'method' => 'start', 'params' => 'ticketID={id}', 'onlybody' => true);
$config->my->ticket->actionList['start']['data-toggle'] = 'modal';
$config->my->ticket->actionList['start']['data-size']   = 'lg';
$config->my->ticket->actionList['start']['data-type']   = 'iframe';

$config->my->ticket->actionList['finish']['icon']        = 'checked';
$config->my->ticket->actionList['finish']['text']        = $lang->ticket->finish;
$config->my->ticket->actionList['finish']['hint']        = $lang->ticket->finish;
$config->my->ticket->actionList['finish']['url']         = array('module' => 'ticket', 'method' => 'finish', 'params' => 'ticketID={id}', 'onlybody' => true);
$config->my->ticket->actionList['finish']['data-toggle'] = 'modal';
$config->my->ticket->actionList['finish']['data-size']   = 'lg';
$config->my->ticket->actionList['finish']['data-type']   = 'iframe';

$config->my->ticket->actionList['close']['icon']        = 'off';
$config->my->ticket->actionList['close']['text']        = $lang->ticket->close;
$config->my->ticket->actionList['close']['hint']        = $lang->ticket->close;
$config->my->ticket->actionList['close']['url']         = array('module' => 'ticket', 'method' => 'close', 'params' => 'ticketID={id}');
$config->my->ticket->actionList['close']['data-toggle'] = 'modal';

$config->my->ticket->actionList['edit']['icon'] = 'edit';
$config->my->ticket->actionList['edit']['text'] = $lang->ticket->edit;
$config->my->ticket->actionList['edit']['hint'] = $lang->ticket->edit;
$config->my->ticket->actionList['edit']['url']  = array('module' => 'ticket', 'method' => 'edit', 'params' => 'ticketID={id}');

$config->my->ticket->actionList['createBug']['icon'] = 'bug';
$config->my->ticket->actionList['createBug']['text'] = $lang->ticket->toBug;
$config->my->ticket->actionList['createBug']['hint'] = $lang->ticket->toBug;
$config->my->ticket->actionList['createBug']['url']  = array('module' => 'ticket', 'method' => 'createBug', 'params' => 'product={product}&extra=projectID=0,fromType=ticket,fromID={id}');

$config->my->ticket->actionList['createStory']['icon'] = 'lightbulb';
$config->my->ticket->actionList['createStory']['text'] = $lang->ticket->toStory;
$config->my->ticket->actionList['createStory']['hint'] = $lang->ticket->toStory;
$config->my->ticket->actionList['createStory']['url']  = array('module' => 'ticket', 'method' => 'createStory', 'params' => 'product={product}&extra=fromType=ticket,fromID={id}');

$config->my->ticket->actionList['recordHour']['icon']        = 'time';
$config->my->ticket->actionList['recordHour']['text']        = $lang->ticket->effort;
$config->my->ticket->actionList['recordHour']['hint']        = $lang->ticket->effort;
$config->my->ticket->actionList['recordHour']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'objectType=ticket&objectID={id}');
$config->my->ticket->actionList['recordHour']['data-toggle'] = 'modal';

$config->my->ticket->dtable = new stdclass();
$config->my->ticket->dtable->fieldList = array();
$config->my->ticket->dtable->fieldList['id']['name']  = 'id';
$config->my->ticket->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->ticket->dtable->fieldList['id']['type']  = 'id';
$config->my->ticket->dtable->fieldList['id']['show']  = true;

$config->my->ticket->dtable->fieldList['title']['name']  = 'title';
$config->my->ticket->dtable->fieldList['title']['title'] = $lang->ticket->title;
$config->my->ticket->dtable->fieldList['title']['type']  = 'title';
$config->my->ticket->dtable->fieldList['title']['link']  = array('module' => 'ticket', 'method' => 'view', 'params' => 'id={id}');
$config->my->ticket->dtable->fieldList['title']['fixed'] = 'left';
$config->my->ticket->dtable->fieldList['title']['show']  = true;

$config->my->ticket->dtable->fieldList['product']['name']     = 'product';
$config->my->ticket->dtable->fieldList['product']['title']    = $lang->ticket->product;
$config->my->ticket->dtable->fieldList['product']['type']     = 'category';
$config->my->ticket->dtable->fieldList['product']['sortType'] = true;

$config->my->ticket->dtable->fieldList['pri']['name']    = 'pri';
$config->my->ticket->dtable->fieldList['pri']['title']   = $lang->priAB;
$config->my->ticket->dtable->fieldList['pri']['type']    = 'pri';
$config->my->ticket->dtable->fieldList['pri']['priList'] = $lang->ticket->priList;
$config->my->ticket->dtable->fieldList['pri']['show']    = true;

$config->my->ticket->dtable->fieldList['feedback']['name']       = 'feedbackTip';
$config->my->ticket->dtable->fieldList['feedback']['title']      = $lang->ticket->from;
$config->my->ticket->dtable->fieldList['feedback']['width']      = '120';
$config->my->ticket->dtable->fieldList['feedback']['type']       = 'category';
$config->my->ticket->dtable->fieldList['feedback']['link']       = array('module' => 'feedback', 'method' => 'adminView', 'params' => 'feedbackID={feedback}');
$config->my->ticket->dtable->fieldList['feedback']['dataSource'] = array('module' => 'feedback', 'method' => 'getPairs');
$config->my->ticket->dtable->fieldList['feedback']['show']       = true;

$config->my->ticket->dtable->fieldList['status']['name']      = 'status';
$config->my->ticket->dtable->fieldList['status']['title']     = $lang->ticket->status;
$config->my->ticket->dtable->fieldList['status']['type']      = 'status';
$config->my->ticket->dtable->fieldList['status']['statusMap'] = $lang->ticket->statusList;
$config->my->ticket->dtable->fieldList['status']['show']      = true;

$config->my->ticket->dtable->fieldList['type']['name']  = 'type';
$config->my->ticket->dtable->fieldList['type']['title'] = $lang->ticket->type;
$config->my->ticket->dtable->fieldList['type']['type']  = 'category';
$config->my->ticket->dtable->fieldList['type']['map']   = $lang->ticket->typeList;
$config->my->ticket->dtable->fieldList['type']['show']  = true;

$config->my->ticket->dtable->fieldList['assignedTo']['name']  = 'assignedTo';
$config->my->ticket->dtable->fieldList['assignedTo']['title'] = $lang->ticket->assignedTo;
$config->my->ticket->dtable->fieldList['assignedTo']['type']  = 'user';
$config->my->ticket->dtable->fieldList['assignedTo']['show']  = true;

$config->my->ticket->dtable->fieldList['estimate']['name']  = 'estimate';
$config->my->ticket->dtable->fieldList['estimate']['title'] = $lang->ticket->estimate;
$config->my->ticket->dtable->fieldList['estimate']['width'] = '80';
$config->my->ticket->dtable->fieldList['estimate']['type']  = 'category';
$config->my->ticket->dtable->fieldList['estimate']['show']  = true;

$config->my->ticket->dtable->fieldList['openedBy']['name']       = 'openedBy';
$config->my->ticket->dtable->fieldList['openedBy']['title']      = $lang->ticket->openedBy;
$config->my->ticket->dtable->fieldList['openedBy']['type']       = 'user';
$config->my->ticket->dtable->fieldList['openedBy']['dataSource'] = array('module' => 'build', 'method' =>'getBuildPairs', 'params' => array(0));
$config->my->ticket->dtable->fieldList['openedBy']['show']       = true;

$config->my->ticket->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->my->ticket->dtable->fieldList['openedDate']['title'] = $lang->ticket->openedDate;
$config->my->ticket->dtable->fieldList['openedDate']['type']  = 'date';
$config->my->ticket->dtable->fieldList['openedDate']['show']  = true;

$config->my->ticket->dtable->fieldList['deadline']['name']  = 'deadline';
$config->my->ticket->dtable->fieldList['deadline']['title'] = $lang->ticket->deadline;
$config->my->ticket->dtable->fieldList['deadline']['width'] = '80';
$config->my->ticket->dtable->fieldList['deadline']['type']  = 'date';
$config->my->ticket->dtable->fieldList['deadline']['show']  = true;

$config->my->ticket->dtable->fieldList['consumed']['name']  = 'consumed';
$config->my->ticket->dtable->fieldList['consumed']['title'] = $lang->ticket->consumed;
$config->my->ticket->dtable->fieldList['consumed']['width'] = '90';
$config->my->ticket->dtable->fieldList['consumed']['type']  = 'int';

$config->my->ticket->dtable->fieldList['openedBuild']['name']       = 'openedBuild';
$config->my->ticket->dtable->fieldList['openedBuild']['title']      = $lang->ticket->openedBuild;
$config->my->ticket->dtable->fieldList['openedBuild']['type']       = 'category';
$config->my->ticket->dtable->fieldList['openedBuild']['width']      = '120';
$config->my->ticket->dtable->fieldList['openedBuild']['dataSource'] = array('module' => 'build', 'method' =>'getBuildPairs', 'params' => array(0));

$config->my->ticket->dtable->fieldList['keywords']['name']  = 'keywords';
$config->my->ticket->dtable->fieldList['keywords']['title'] = $lang->ticket->keywords;
$config->my->ticket->dtable->fieldList['keywords']['width'] = '90';

$config->my->ticket->dtable->fieldList['mailto']['name']       = 'mailto';
$config->my->ticket->dtable->fieldList['mailto']['title']      = $lang->ticket->mailto;
$config->my->ticket->dtable->fieldList['mailto']['width']      = '90';
$config->my->ticket->dtable->fieldList['mailto']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->my->ticket->dtable->fieldList['startedBy']['name']       = 'startedBy';
$config->my->ticket->dtable->fieldList['startedBy']['title']      = $lang->ticket->startedBy;
$config->my->ticket->dtable->fieldList['startedBy']['width']      = '100';
$config->my->ticket->dtable->fieldList['startedBy']['type']       = 'user';
$config->my->ticket->dtable->fieldList['startedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->my->ticket->dtable->fieldList['startedDate']['name']  = 'startedDate';
$config->my->ticket->dtable->fieldList['startedDate']['title'] = $lang->ticket->startedDate;
$config->my->ticket->dtable->fieldList['startedDate']['width'] = '100';
$config->my->ticket->dtable->fieldList['startedDate']['type']  = 'date';

$config->my->ticket->dtable->fieldList['finishedBy']['name']  = 'finishedByAB';
$config->my->ticket->dtable->fieldList['finishedBy']['title'] = $lang->ticket->finishedByAB;
$config->my->ticket->dtable->fieldList['finishedBy']['width'] = '100';
$config->my->ticket->dtable->fieldList['finishedBy']['type']  = 'user';

$config->my->ticket->dtable->fieldList['finishedDate']['name']  = 'finishedDate';
$config->my->ticket->dtable->fieldList['finishedDate']['title'] = $lang->ticket->finishedDate;
$config->my->ticket->dtable->fieldList['finishedDate']['width'] = '110';
$config->my->ticket->dtable->fieldList['finishedDate']['type']  = 'date';

$config->my->ticket->dtable->fieldList['closedBy']['name']  = 'closedByAB';
$config->my->ticket->dtable->fieldList['closedBy']['title'] = $lang->ticket->closedByAB;
$config->my->ticket->dtable->fieldList['closedBy']['width'] = '100';
$config->my->ticket->dtable->fieldList['closedBy']['type']  = 'user';

$config->my->ticket->dtable->fieldList['closedDate']['name']  = 'closedDate';
$config->my->ticket->dtable->fieldList['closedDate']['title'] = $lang->ticket->closedDate;
$config->my->ticket->dtable->fieldList['closedDate']['width'] = '100';
$config->my->ticket->dtable->fieldList['closedDate']['type']  = 'date';

$config->my->ticket->dtable->fieldList['closedReason']['name']  = 'closedReason';
$config->my->ticket->dtable->fieldList['closedReason']['title'] = $lang->ticket->closedReason;
$config->my->ticket->dtable->fieldList['closedReason']['width'] = '110';

$config->my->ticket->dtable->fieldList['activatedBy']['name']       = 'activatedBy';
$config->my->ticket->dtable->fieldList['activatedBy']['title']      = $lang->ticket->activatedBy;
$config->my->ticket->dtable->fieldList['activatedBy']['width']      = '100';
$config->my->ticket->dtable->fieldList['activatedBy']['type']       = 'user';
$config->my->ticket->dtable->fieldList['activatedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->my->ticket->dtable->fieldList['activatedDate']['name']  = 'activatedDate';
$config->my->ticket->dtable->fieldList['activatedDate']['title'] = $lang->ticket->activatedDate;
$config->my->ticket->dtable->fieldList['activatedDate']['width'] = '110';
$config->my->ticket->dtable->fieldList['activatedDate']['type']  = 'date';

$config->my->ticket->dtable->fieldList['activatedCount']['name']  = 'activatedCount';
$config->my->ticket->dtable->fieldList['activatedCount']['title'] = $lang->ticket->activatedCount;
$config->my->ticket->dtable->fieldList['activatedCount']['width'] = '120';

$config->my->ticket->dtable->fieldList['editedBy']['name']       = 'editedBy';
$config->my->ticket->dtable->fieldList['editedBy']['title']      = $lang->ticket->editedBy;
$config->my->ticket->dtable->fieldList['editedBy']['width']      = '100';
$config->my->ticket->dtable->fieldList['editedBy']['type']       = 'user';
$config->my->ticket->dtable->fieldList['editedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->my->ticket->dtable->fieldList['editedDate']['name']  = 'lastEditedDate';
$config->my->ticket->dtable->fieldList['editedDate']['title'] = $lang->ticket->lastEditedDate;
$config->my->ticket->dtable->fieldList['editedDate']['width'] = '130';
$config->my->ticket->dtable->fieldList['editedDate']['type']  = 'date';

$config->my->ticket->dtable->fieldList['legendMisc']['name']  = 'legendMisc';
$config->my->ticket->dtable->fieldList['legendMisc']['title'] = $lang->ticket->legendMisc;
$config->my->ticket->dtable->fieldList['legendMisc']['width'] = '150';
$config->my->ticket->dtable->fieldList['legendMisc']['sort']  = false;

$config->my->ticket->dtable->fieldList['actions']['name']     = 'actions';
$config->my->ticket->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->ticket->dtable->fieldList['actions']['type']     = 'actions';
$config->my->ticket->dtable->fieldList['actions']['width']    = '140';
$config->my->ticket->dtable->fieldList['actions']['sortType'] = false;
$config->my->ticket->dtable->fieldList['actions']['list']     = $config->my->ticket->actionList;
$config->my->ticket->dtable->fieldList['actions']['menu']     = array('start', 'finish', 'close', 'recordHour', 'createStory', 'createBug', 'edit');
