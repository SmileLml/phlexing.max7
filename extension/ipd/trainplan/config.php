<?php
$config->trainplan->editor = new stdclass();
$config->trainplan->editor->finish  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->trainplan->editor->summary = array('id' => 'summary', 'tools' => 'simpleTools');
$config->trainplan->editor->view    = array('id' => 'lastComment', 'tools' => 'simpleTools');

$config->trainplan->create = new stdclass();
$config->trainplan->create->requiredFields = 'name';

global $lang;
$config->trainplan->search['module']                = 'trainplan';
$config->trainplan->search['fields']['id']          = $lang->trainplan->id;
$config->trainplan->search['fields']['name']        = $lang->trainplan->name;
$config->trainplan->search['fields']['begin']       = $lang->trainplan->begin;
$config->trainplan->search['fields']['end']         = $lang->trainplan->end;
$config->trainplan->search['fields']['place']       = $lang->trainplan->place;
$config->trainplan->search['fields']['trainee']     = $lang->trainplan->trainee;
$config->trainplan->search['fields']['lecturer']    = $lang->trainplan->lecturer;
$config->trainplan->search['fields']['type']        = $lang->trainplan->type;
$config->trainplan->search['fields']['status']      = $lang->trainplan->status;
$config->trainplan->search['fields']['summary']     = $lang->trainplan->summary;
$config->trainplan->search['fields']['createdBy']   = $lang->trainplan->createdBy;
$config->trainplan->search['fields']['createdDate'] = $lang->trainplan->createdDate;
$config->trainplan->search['fields']['editedBy']    = $lang->trainplan->editedBy;
$config->trainplan->search['fields']['editedDate']  = $lang->trainplan->editedDate;

$config->trainplan->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->trainplan->search['params']['begin']       = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->trainplan->search['params']['end']         = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->trainplan->search['params']['place']       = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->trainplan->search['params']['trainee']     = array('operator' => 'include', 'control' => 'select',  'values' => '');
$config->trainplan->search['params']['lecturer']    = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->trainplan->search['params']['type']        = array('operator' => '=', 'control' => 'select',  'values' => arrayUnion(array(''), $lang->trainplan->typeList));
$config->trainplan->search['params']['status']      = array('operator' => '=', 'control' => 'select',  'values' => arrayUnion(array(''), $lang->trainplan->statusList));
$config->trainplan->search['params']['summary']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->trainplan->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->trainplan->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->trainplan->search['params']['editedBy']    = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->trainplan->search['params']['editedDate']  = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
