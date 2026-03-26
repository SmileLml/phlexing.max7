<?php
$config->activity = new stdclass();
$config->activity->editor = new stdclass();
$config->activity->editor->create   = array('id' => 'content', 'tools' => 'simpleTools');
$config->activity->editor->edit     = array('id' => 'content', 'tools' => 'simpleTools');
$config->activity->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');

$config->activity->create = new stdclass();
$config->activity->edit   = new stdclass();
$config->activity->create->requiredFields = 'process,name';
$config->activity->edit->requiredFields   = 'process,name';

global $lang;
$config->activity->search['module']                = 'activity';
$config->activity->search['fields']['id']          = $lang->activity->id;
$config->activity->search['fields']['name']        = $lang->activity->name;
$config->activity->search['fields']['process']     = $lang->activity->process;
$config->activity->search['fields']['optional']    = $lang->activity->optional;
$config->activity->search['fields']['assignedTo']  = $lang->activity->assignedTo;
$config->activity->search['fields']['createdBy']   = $lang->activity->createdBy;
$config->activity->search['fields']['createdDate'] = $lang->activity->createdDate;

$config->activity->search['params']['id']          = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->activity->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->activity->search['params']['process']     = array('operator' => '=', 'control' => 'select',  'values' => '');
$config->activity->search['params']['optional']    = array('operator' => '=', 'control' => 'select', 'values' => $lang->activity->optionalOptions);
$config->activity->search['params']['assignedTo']  = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->activity->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->activity->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
