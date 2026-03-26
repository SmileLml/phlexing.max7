<?php
$config->meeting->editor = new stdclass();
$config->meeting->editor->minutes = array('id' => 'minutes', 'tools' => 'simpleTools');

global $lang;
$config->meeting->create = new stdclass();
$config->meeting->edit   = new stdclass();
$config->meeting->create->requiredFields = 'name,date,begin,end,mode,host,participant';
$config->meeting->edit->requiredFields   = 'name,date,begin,end,mode,host,participant';

$config->meeting->custom = new stdclass();
$config->meeting->custom->createFields = 'files,project,execution,dept';

$config->meeting->objectTypeList['']            = '';
$config->meeting->objectTypeList['story']       = $lang->SRCommon;
$config->meeting->objectTypeList['task']        = $lang->task->common;
$config->meeting->objectTypeList['bug']         = $lang->bug->common;
$config->meeting->objectTypeList['issue']       = $lang->issue->common;
$config->meeting->objectTypeList['risk']        = $lang->risk->common;
$config->meeting->objectTypeList['opportunity'] = $lang->opportunity->common;

$config->meeting->objectCreatedField['story']       = 'openedBy';
$config->meeting->objectCreatedField['task']        = 'openedBy';
$config->meeting->objectCreatedField['bug']         = 'openedBy';
$config->meeting->objectCreatedField['issue']       = 'createdBy';
$config->meeting->objectCreatedField['risk']        = 'createdBy';
$config->meeting->objectCreatedField['opportunity'] = 'createdBy';

$config->meeting->search['module']                = 'meeting';
$config->meeting->search['fields']['id']          = $lang->meeting->id;
$config->meeting->search['fields']['project']     = $lang->meeting->project;
$config->meeting->search['fields']['name']        = $lang->meeting->name;
$config->meeting->search['fields']['begin']       = $lang->meeting->begin;
$config->meeting->search['fields']['end']         = $lang->meeting->end;
$config->meeting->search['fields']['mode']        = $lang->meeting->mode;
$config->meeting->search['fields']['host']        = $lang->meeting->host;
$config->meeting->search['fields']['date']        = $lang->meeting->date;
$config->meeting->search['fields']['room']        = $lang->meeting->room;
$config->meeting->search['fields']['dept']        = $lang->meeting->dept;
$config->meeting->search['fields']['minutedBy']   = $lang->meeting->minutedBy;
$config->meeting->search['fields']['minutedDate'] = $lang->meeting->minutedDate;
$config->meeting->search['fields']['createdBy']   = $lang->meeting->createdBy;
$config->meeting->search['fields']['createdDate'] = $lang->meeting->createdDate;
$config->meeting->search['fields']['editedBy']    = $lang->meeting->editedBy;
$config->meeting->search['fields']['editedDate']  = $lang->meeting->editedDate;
$config->meeting->search['fields']['execution']   = $lang->meeting->execution;

$config->meeting->search['params']['name']        = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->meeting->search['params']['project']     = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->meeting->search['params']['type']        = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->meeting->search['params']['begin']       = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->meeting->search['params']['end']         = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->meeting->search['params']['mode']        = array('operator' => '=', 'control' => 'select', 'values' => $lang->meeting->modeList);
$config->meeting->search['params']['host']        = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->meeting->search['params']['date']        = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->meeting->search['params']['room']        = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->meeting->search['params']['dept']        = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->meeting->search['params']['minutedBy']   = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->meeting->search['params']['minutedDate'] = array('operator' => 'include', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->meeting->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->meeting->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->meeting->search['params']['editedBy']    = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->meeting->search['params']['editedDate']  = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->meeting->search['params']['execution']   = array('operator' => '=', 'control' => 'select', 'values' => '');

global $app;
$config->meeting->actionList = array();
$config->meeting->actionList['edit']['icon']     = 'edit';
$config->meeting->actionList['edit']['text']     = $lang->edit;
$config->meeting->actionList['edit']['hint']     = $lang->edit;
$config->meeting->actionList['edit']['url']      = array('module' => 'meeting', 'method' => 'edit', 'params' => 'meetingID={id}&from={from}');
$config->meeting->actionList['edit']['data-app'] = $app->tab;

$config->meeting->actionList['minutes']['icon']        = 'summary';
$config->meeting->actionList['minutes']['text']        = $lang->meeting->minutes;
$config->meeting->actionList['minutes']['hint']        = $lang->meeting->minutes;
$config->meeting->actionList['minutes']['url']         = array('module' => 'meeting', 'method' => 'minutes', 'params' => 'meetingID={id}');
$config->meeting->actionList['minutes']['data-toggle'] = 'modal';
$config->meeting->actionList['minutes']['data-size']   = 'lg';

$config->meeting->actionList['delete']['icon']         = 'trash';
$config->meeting->actionList['delete']['text']         = $lang->delete;
$config->meeting->actionList['delete']['hint']         = $lang->delete;
$config->meeting->actionList['delete']['url']          = array('module' => 'meeting', 'method' => 'delete', 'params' => 'meetingID={id}&from={from}');
$config->meeting->actionList['delete']['className']    = 'ajax-submit';
$config->meeting->actionList['delete']['data-confirm'] = array('message' => $this->lang->meeting->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->meeting->actionList['delete']['notInModal']   = true;

$config->meeting->actions = new stdClass();
$config->meeting->actions->view = array();
$config->meeting->actions->view['mainActions']   = array('minutes');
$config->meeting->actions->view['suffixActions'] = array('edit', 'delete');
