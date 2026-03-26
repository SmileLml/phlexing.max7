<?php
$config->opportunity = new stdclass();
$config->opportunity->batchCreate = 10;

$config->opportunity->editor = new stdclass();
$config->opportunity->editor->create   = array('id' => 'prevention,desc', 'tools' => 'simpleTools');
$config->opportunity->editor->edit     = array('id' => 'prevention,resolution,desc', 'tools' => 'simpleTools');
$config->opportunity->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');
$config->opportunity->editor->cancel   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->opportunity->editor->close    = array('id' => 'resolution', 'tools' => 'simpleTools');
$config->opportunity->editor->track    = array('id' => 'prevention,resolution,comment,desc', 'tools' => 'simpleTools');
$config->opportunity->editor->hangup   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->opportunity->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->opportunity->editor->view     = array('id' => 'lastComment', 'tools' => 'simpleTools');

$config->opportunity->create = new stdclass();
$config->opportunity->create->requiredFields = 'name';

$config->opportunity->edit = new stdclass();
$config->opportunity->edit->requiredFields = 'name';

$config->opportunity->track = new stdclass();
$config->opportunity->track->requiredFields = 'name';

$config->opportunity->cancel = new stdclass();
$config->opportunity->cancel->requiredFields = 'cancelReason';

$config->opportunity->labelDot = array();
$config->opportunity->labelDot['active']   = 'doing';
$config->opportunity->labelDot['closed']   = 'closed';
$config->opportunity->labelDot['hangup']   = 'pause';
$config->opportunity->labelDot['canceled'] = 'cancel';

$config->opportunity->actions = new stdclass();
$config->opportunity->actions->view = array();
$config->opportunity->actions->view['mainActions']   = array('track', 'assignTo', 'cancel', 'close', 'hangup', 'activate', 'importToLib');
$config->opportunity->actions->view['suffixActions'] = array('edit', 'delete');

global $lang;
$config->opportunity->search['module']                      = 'opportunity';
$config->opportunity->search['fields']['id']                = $lang->opportunity->id;
$config->opportunity->search['fields']['name']              = $lang->opportunity->name;
$config->opportunity->search['fields']['desc']              = $lang->opportunity->desc;
$config->opportunity->search['fields']['source']            = $lang->opportunity->source;
$config->opportunity->search['fields']['type']              = $lang->opportunity->type;
$config->opportunity->search['fields']['strategy']          = $lang->opportunity->strategy;
$config->opportunity->search['fields']['status']            = $lang->opportunity->status;
$config->opportunity->search['fields']['impact']            = $lang->opportunity->impact;
$config->opportunity->search['fields']['chance']            = $lang->opportunity->chance;
$config->opportunity->search['fields']['ratio']             = $lang->opportunity->ratio;
$config->opportunity->search['fields']['pri']               = $lang->opportunity->pri;
$config->opportunity->search['fields']['identifiedDate']    = $lang->opportunity->identifiedDate;
$config->opportunity->search['fields']['assignedTo']        = $lang->opportunity->assignedTo;
$config->opportunity->search['fields']['prevention']        = $lang->opportunity->prevention;
$config->opportunity->search['fields']['resolution']        = $lang->opportunity->resolution;
$config->opportunity->search['fields']['plannedClosedDate'] = $lang->opportunity->plannedClosedDate;
$config->opportunity->search['fields']['actualClosedDate']  = $lang->opportunity->actualClosedDate;
$config->opportunity->search['fields']['createdBy']         = $lang->opportunity->createdBy;
$config->opportunity->search['fields']['createdDate']       = $lang->opportunity->createdDate;
$config->opportunity->search['fields']['resolvedBy']        = $lang->opportunity->resolvedBy;
$config->opportunity->search['fields']['activatedBy']       = $lang->opportunity->activatedBy;
$config->opportunity->search['fields']['canceledBy']        = $lang->opportunity->canceledBy;
$config->opportunity->search['fields']['hangupedBy']        = $lang->opportunity->hangupedBy;
$config->opportunity->search['fields']['lastCheckedBy']     = $lang->opportunity->lastCheckedBy;
$config->opportunity->search['fields']['editedBy']          = $lang->opportunity->editedBy;
$config->opportunity->search['fields']['editedDate']        = $lang->opportunity->editedDate;

$config->opportunity->search['params']['name']              = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->opportunity->search['params']['desc']              = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->opportunity->search['params']['source']            = array('operator' => '=', 'control' => 'select',  'values' => $lang->opportunity->sourceList);
$config->opportunity->search['params']['type']              = array('operator' => '=', 'control' => 'select',  'values' => $lang->opportunity->typeList);
$config->opportunity->search['params']['strategy']          = array('operator' => '=', 'control' => 'select',  'values' => $lang->opportunity->strategyList);
$config->opportunity->search['params']['status']            = array('operator' => '=', 'control' => 'select',  'values' => $lang->opportunity->statusList);
$config->opportunity->search['params']['impact']            = array('operator' => '=', 'control' => 'select',  'values' => arrayUnion(array('' => ''), $lang->opportunity->impactList));
$config->opportunity->search['params']['chance']            = array('operator' => '=', 'control' => 'select',  'values' => arrayUnion(array('' => ''), $lang->opportunity->chanceList));
$config->opportunity->search['params']['ratio']             = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->opportunity->search['params']['pri']               = array('operator' => '=', 'control' => 'select',  'values' => arrayUnion(array('' => ''), $lang->opportunity->priList));
$config->opportunity->search['params']['identifiedDate']    = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->opportunity->search['params']['prevention']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->opportunity->search['params']['plannedClosedDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->opportunity->search['params']['actualClosedDate']  = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->opportunity->search['params']['createdBy']         = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->opportunity->search['params']['createdDate']       = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->opportunity->search['params']['resolution']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->opportunity->search['params']['resolvedBy']        = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->opportunity->search['params']['activatedBy']       = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->opportunity->search['params']['assignedTo']        = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->opportunity->search['params']['canceledBy']        = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->opportunity->search['params']['hangupedBy']        = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->opportunity->search['params']['lastCheckedBy']     = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->opportunity->search['params']['editedBy']          = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->opportunity->search['params']['editedDate']        = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');

$config->opportunity->exportFields = 'id,source,name,execution,type,strategy,status,impact,chance,ratio,pri,identifiedDate,assignedTo,assignedDate,desc,prevention,resolution,plannedClosedDate,actualClosedDate,resolvedBy,resolvedDate';
