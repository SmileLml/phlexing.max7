<?php
$config->ticket->editor = new stdclass();
$config->ticket->editor->create    = array('id' => 'desc', 'tools' => 'simpleTools');
$config->ticket->editor->edit      = array('id' => 'desc,resolution', 'tools' => 'simpleTools');
$config->ticket->editor->view      = array('id' => 'comment', 'tools' => 'simpleTools');
$config->ticket->editor->start     = array('id' => 'comment', 'tools' => 'simpleTools');
$config->ticket->editor->assignto  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->ticket->editor->close     = array('id' => 'resolution,comment', 'tools' => 'simpleTools');
$config->ticket->editor->finish    = array('id' => 'resolution,comment', 'tools' => 'simpleTools');
$config->ticket->editor->activate  = array('id' => 'comment', 'tools' => 'simpleTools');

$config->ticket->start         = new stdclass();
$config->ticket->finish        = new stdclass();
$config->ticket->create        = new stdclass();
$config->ticket->edit          = new stdclass();
$config->ticket->close         = new stdclass();
$config->ticket->batchActivate = new stdclass();
$config->ticket->start->requiredFields         = 'assignedTo';
$config->ticket->finish->requiredFields        = 'resolvedDate,resolution';
$config->ticket->create->requiredFields        = 'product,title,module';
$config->ticket->edit->requiredFields          = 'product,title,module';
$config->ticket->close->requiredFields         = 'closedReason';
$config->ticket->batchActivate->requiredFields = 'title';

$config->ticket->list = new stdclass();
$config->ticket->list->customBatchCreateFields = 'module,type,pri,estimate,desc,openedBuild,deadline,customer,contact,notifyEmail,keywords,mailto';

$config->ticket->custom = new stdclass();
$config->ticket->custom->batchCreateFields = 'module,type,pri,estimate,desc,openedBuild,deadline';

global $lang;
$config->ticket->search['module'] = 'ticket';
$config->ticket->search['fields']['title']          = $lang->ticket->title;
$config->ticket->search['fields']['status']         = $lang->ticket->status;
$config->ticket->search['fields']['type']           = $lang->ticket->type;
$config->ticket->search['fields']['product']        = $lang->ticket->product;
$config->ticket->search['fields']['pri']            = $lang->ticket->pri;
$config->ticket->search['fields']['module']         = $lang->ticket->module;
$config->ticket->search['fields']['id']             = $lang->ticket->idAB;
$config->ticket->search['fields']['desc']           = $lang->ticket->desc;
$config->ticket->search['fields']['openedBuild']    = $lang->ticket->openedBuild;
$config->ticket->search['fields']['customer']       = $lang->ticket->customer;
$config->ticket->search['fields']['contact']        = $lang->ticket->contact;
$config->ticket->search['fields']['notifyEmail']    = $lang->ticket->notifyEmail;
$config->ticket->search['fields']['deadline']       = $lang->ticket->deadline;
$config->ticket->search['fields']['assignedTo']     = $lang->ticket->assignedTo;
$config->ticket->search['fields']['mailto']         = $lang->ticket->mailto;
$config->ticket->search['fields']['keywords']       = $lang->ticket->keywords;
$config->ticket->search['fields']['openedBy']       = $lang->ticket->createdBy;
$config->ticket->search['fields']['openedDate']     = $lang->ticket->createdDate;
$config->ticket->search['fields']['feedback']       = $lang->ticket->source;
$config->ticket->search['fields']['startedBy']      = $lang->ticket->startedBy;
$config->ticket->search['fields']['startedDate']    = $lang->ticket->startedDate;
$config->ticket->search['fields']['activatedBy']    = $lang->ticket->activatedBy;
$config->ticket->search['fields']['activatedDate']  = $lang->ticket->activatedDate;
$config->ticket->search['fields']['activatedCount'] = $lang->ticket->activatedCount;
$config->ticket->search['fields']['resolvedBy']     = $lang->ticket->resolvedBy;
$config->ticket->search['fields']['resolution']     = $lang->ticket->resolution;
$config->ticket->search['fields']['resolvedDate']   = $lang->ticket->resolvedDate;
$config->ticket->search['fields']['closedBy']       = $lang->ticket->closedBy;
$config->ticket->search['fields']['closedDate']     = $lang->ticket->closedDate;
$config->ticket->search['fields']['closedReason']   = $lang->ticket->closedReason;
$config->ticket->search['fields']['editedBy']       = $lang->ticket->lastEditedBy;
$config->ticket->search['fields']['editedDate']     = $lang->ticket->lastEditedDate;

$config->ticket->search['params']['title']          = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->ticket->search['params']['product']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->ticket->search['params']['status']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->ticket->statusList);
$config->ticket->search['params']['pri']            = array('operator' => '=',       'control' => 'select', 'values' => $lang->ticket->priList);
$config->ticket->search['params']['type']           = array('operator' => '=',       'control' => 'select', 'values' => $lang->ticket->typeList);
$config->ticket->search['params']['module']         = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->ticket->search['params']['id']             = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->ticket->search['params']['desc']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->ticket->search['params']['openedBuild']    = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->ticket->search['params']['customer']       = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->ticket->search['params']['contact']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->ticket->search['params']['notifyEmail']    = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->ticket->search['params']['deadline']       = array('operator' => '=',       'control' => 'input',  'values' => 'date');
$config->ticket->search['params']['assignedTo']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->ticket->search['params']['mailto']         = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->ticket->search['params']['openedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->ticket->search['params']['openedDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->ticket->search['params']['feedback']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->ticket->search['params']['startedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->ticket->search['params']['startedDate']    = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->ticket->search['params']['activatedBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->ticket->search['params']['activatedDate']  = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->ticket->search['params']['activatedCount'] = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->ticket->search['params']['resolvedBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->ticket->search['params']['resolution']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->ticket->search['params']['resolvedDate']   = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->ticket->search['params']['closedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->ticket->search['params']['closedDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->ticket->search['params']['closedReason']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->ticket->search['params']['editedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->ticket->search['params']['editedDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');

$config->ticket->datatable = new stdclass();
$config->ticket->datatable->defaultField = array('id', 'title', 'pri', 'feedback', 'status', 'type', 'assignedTo', 'estimate', 'openedBy', 'openedDate', 'deadline', 'editedDate', 'actions');
$config->ticket->datatable->fieldList['id']['title']    = 'idAB';
$config->ticket->datatable->fieldList['id']['fixed']    = 'left';
$config->ticket->datatable->fieldList['id']['width']    = '50';
$config->ticket->datatable->fieldList['id']['required'] = 'yes';

$config->ticket->datatable->fieldList['title']['title']    = 'title';
$config->ticket->datatable->fieldList['title']['fixed']    = 'left';
$config->ticket->datatable->fieldList['title']['width']    = 'auto';
$config->ticket->datatable->fieldList['title']['required'] = 'yes';

$config->ticket->datatable->fieldList['product']['title']      = 'product';
$config->ticket->datatable->fieldList['product']['fixed']      = 'left';
$config->ticket->datatable->fieldList['product']['width']      = '120';
$config->ticket->datatable->fieldList['product']['required']   = 'no';
$config->ticket->datatable->fieldList['product']['control']    = 'select';
$config->ticket->datatable->fieldList['product']['dataSource'] = array('module' => 'feedback', 'method' => 'getGrantProducts', 'params' => 'true&true&all');

$config->ticket->datatable->fieldList['module']['title']       = 'module';
$config->ticket->datatable->fieldList['module']['display']     = false;
$config->ticket->datatable->fieldList['module']['dataSource']  = array('module' => 'feedback', 'method' => 'getModuleList', 'params' => "ticket&true&no");

$config->ticket->datatable->fieldList['pri']['title']    = 'P';
$config->ticket->datatable->fieldList['pri']['fixed']    = 'left';
$config->ticket->datatable->fieldList['pri']['width']    = '50';
$config->ticket->datatable->fieldList['pri']['required'] = 'no';
$config->ticket->datatable->fieldList['pri']['name']     = $lang->ticket->pri;

$config->ticket->datatable->fieldList['feedback']['title']      = 'from';
$config->ticket->datatable->fieldList['feedback']['fixed']      = 'no';
$config->ticket->datatable->fieldList['feedback']['width']      = '120';
$config->ticket->datatable->fieldList['feedback']['required']   = 'no';
$config->ticket->datatable->fieldList['feedback']['dataSource'] = array('module' => 'feedback', 'method' => 'getPairs');

$config->ticket->datatable->fieldList['status']['title']    = 'status';
$config->ticket->datatable->fieldList['status']['fixed']    = 'no';
$config->ticket->datatable->fieldList['status']['width']    = '80';
$config->ticket->datatable->fieldList['status']['required'] = 'no';

$config->ticket->datatable->fieldList['type']['title']    = 'type';
$config->ticket->datatable->fieldList['type']['fixed']    = 'no';
$config->ticket->datatable->fieldList['type']['width']    = '80';
$config->ticket->datatable->fieldList['type']['required'] = 'no';

$config->ticket->datatable->fieldList['assignedTo']['title']      = 'assignedTo';
$config->ticket->datatable->fieldList['assignedTo']['fixed']      = 'no';
$config->ticket->datatable->fieldList['assignedTo']['width']      = '120';
$config->ticket->datatable->fieldList['assignedTo']['required']   = 'no';
$config->ticket->datatable->fieldList['assignedTo']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->ticket->datatable->fieldList['estimate']['title']    = 'estimate';
$config->ticket->datatable->fieldList['estimate']['fixed']    = 'no';
$config->ticket->datatable->fieldList['estimate']['width']    = '80';
$config->ticket->datatable->fieldList['estimate']['required'] = 'no';

$config->ticket->datatable->fieldList['openedBy']['title']    = 'createdBy';
$config->ticket->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->ticket->datatable->fieldList['openedBy']['width']    = '100';
$config->ticket->datatable->fieldList['openedBy']['required'] = 'no';

$config->ticket->datatable->fieldList['openedDate']['title']    = 'openedDate';
$config->ticket->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->ticket->datatable->fieldList['openedDate']['width']    = '100';
$config->ticket->datatable->fieldList['openedDate']['required'] = 'no';

$config->ticket->datatable->fieldList['deadline']['title']    = 'deadline';
$config->ticket->datatable->fieldList['deadline']['fixed']    = 'no';
$config->ticket->datatable->fieldList['deadline']['width']    = '80';
$config->ticket->datatable->fieldList['deadline']['required'] = 'no';
$config->ticket->datatable->fieldList['deadline']['control']  = 'date';

$config->ticket->datatable->fieldList['consumed']['title']    = 'consumed';
$config->ticket->datatable->fieldList['consumed']['fixed']    = 'no';
$config->ticket->datatable->fieldList['consumed']['width']    = '90';
$config->ticket->datatable->fieldList['consumed']['required'] = 'no';

$config->ticket->datatable->fieldList['openedBuild']['title']      = 'openedBuild';
$config->ticket->datatable->fieldList['openedBuild']['fixed']      = 'no';
$config->ticket->datatable->fieldList['openedBuild']['width']      = '120';
$config->ticket->datatable->fieldList['openedBuild']['required']   = 'no';
$config->ticket->datatable->fieldList['openedBuild']['dataSource'] = array('module' => 'build', 'method' =>'getBuildPairs', 'params' => array(0));

$config->ticket->datatable->fieldList['keywords']['title']    = 'keywords';
$config->ticket->datatable->fieldList['keywords']['fixed']    = 'no';
$config->ticket->datatable->fieldList['keywords']['width']    = '90';
$config->ticket->datatable->fieldList['keywords']['required'] = 'no';

$config->ticket->datatable->fieldList['mailto']['title']      = 'mailto';
$config->ticket->datatable->fieldList['mailto']['fixed']      = 'no';
$config->ticket->datatable->fieldList['mailto']['width']      = '90';
$config->ticket->datatable->fieldList['mailto']['required']   = 'no';
$config->ticket->datatable->fieldList['mailto']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->ticket->datatable->fieldList['startedBy']['title']      = 'startedBy';
$config->ticket->datatable->fieldList['startedBy']['fixed']      = 'no';
$config->ticket->datatable->fieldList['startedBy']['width']      = '100';
$config->ticket->datatable->fieldList['startedBy']['required']   = 'no';
$config->ticket->datatable->fieldList['startedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->ticket->datatable->fieldList['startedDate']['title']    = 'startedDate';
$config->ticket->datatable->fieldList['startedDate']['fixed']    = 'no';
$config->ticket->datatable->fieldList['startedDate']['width']    = '100';
$config->ticket->datatable->fieldList['startedDate']['required'] = 'no';

$config->ticket->datatable->fieldList['finishedBy']['title']    = 'finishedByAB';
$config->ticket->datatable->fieldList['finishedBy']['fixed']    = 'no';
$config->ticket->datatable->fieldList['finishedBy']['width']    = '100';
$config->ticket->datatable->fieldList['finishedBy']['required'] = 'no';

$config->ticket->datatable->fieldList['finishedDate']['title']    = 'finishedDate';
$config->ticket->datatable->fieldList['finishedDate']['fixed']    = 'no';
$config->ticket->datatable->fieldList['finishedDate']['width']    = '110';
$config->ticket->datatable->fieldList['finishedDate']['required'] = 'no';

$config->ticket->datatable->fieldList['closedBy']['title']    = 'closedByAB';
$config->ticket->datatable->fieldList['closedBy']['fixed']    = 'no';
$config->ticket->datatable->fieldList['closedBy']['width']    = '100';
$config->ticket->datatable->fieldList['closedBy']['required'] = 'no';

$config->ticket->datatable->fieldList['closedDate']['title']    = 'closedDate';
$config->ticket->datatable->fieldList['closedDate']['fixed']    = 'no';
$config->ticket->datatable->fieldList['closedDate']['width']    = '100';
$config->ticket->datatable->fieldList['closedDate']['required'] = 'no';

$config->ticket->datatable->fieldList['closedReason']['title']    = 'closedReason';
$config->ticket->datatable->fieldList['closedReason']['fixed']    = 'no';
$config->ticket->datatable->fieldList['closedReason']['width']    = '110';
$config->ticket->datatable->fieldList['closedReason']['required'] = 'no';

$config->ticket->datatable->fieldList['activatedBy']['title']      = 'activatedBy';
$config->ticket->datatable->fieldList['activatedBy']['fixed']      = 'no';
$config->ticket->datatable->fieldList['activatedBy']['width']      = '100';
$config->ticket->datatable->fieldList['activatedBy']['required']   = 'no';
$config->ticket->datatable->fieldList['activatedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->ticket->datatable->fieldList['activatedDate']['title']    = 'activatedDate';
$config->ticket->datatable->fieldList['activatedDate']['fixed']    = 'no';
$config->ticket->datatable->fieldList['activatedDate']['width']    = '110';
$config->ticket->datatable->fieldList['activatedDate']['required'] = 'no';

$config->ticket->datatable->fieldList['activatedCount']['title']    = 'activatedCount';
$config->ticket->datatable->fieldList['activatedCount']['fixed']    = 'no';
$config->ticket->datatable->fieldList['activatedCount']['width']    = '120';
$config->ticket->datatable->fieldList['activatedCount']['required'] = 'no';

$config->ticket->datatable->fieldList['editedBy']['title']      = 'editedBy';
$config->ticket->datatable->fieldList['editedBy']['fixed']      = 'no';
$config->ticket->datatable->fieldList['editedBy']['width']      = '100';
$config->ticket->datatable->fieldList['editedBy']['required']   = 'no';
$config->ticket->datatable->fieldList['editedBy']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

$config->ticket->datatable->fieldList['editedDate']['title']    = 'lastEditedDate';
$config->ticket->datatable->fieldList['editedDate']['fixed']    = 'no';
$config->ticket->datatable->fieldList['editedDate']['width']    = '130';
$config->ticket->datatable->fieldList['editedDate']['required'] = 'no';

$config->ticket->datatable->fieldList['legendMisc']['title']    = 'legendMisc';
$config->ticket->datatable->fieldList['legendMisc']['fixed']    = 'no';
$config->ticket->datatable->fieldList['legendMisc']['width']    = '150';
$config->ticket->datatable->fieldList['legendMisc']['required'] = 'no';
$config->ticket->datatable->fieldList['legendMisc']['sort']     = 'no';

$config->ticket->datatable->fieldList['actions']['title']    = 'actions';
$config->ticket->datatable->fieldList['actions']['fixed']    = 'right';
$config->ticket->datatable->fieldList['actions']['width']    = '120';
$config->ticket->datatable->fieldList['actions']['required'] = 'yes';

$config->ticket->actions = new stdclass();
$config->ticket->actions->view = array();
$config->ticket->actions->view['mainActions']   = array('recordHour', 'assignTo', 'start', 'finish', 'activate', 'close', 'createStory', 'createBug');
$config->ticket->actions->view['suffixActions'] = array('edit', 'delete');
