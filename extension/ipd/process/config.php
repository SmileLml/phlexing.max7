<?php
$config->process->create = new stdclass();
$config->process->edit   = new stdclass();

$config->process->create->requiredFields = 'name';
$config->process->edit->requiredFields   = 'name';

$config->process->editor          = new stdclass();
$config->process->editor->create    = array('id' => 'desc', 'tools' => 'simpleTools');
$config->process->editor->edit      = array('id' => 'desc', 'tools' => 'simpleTools');

global $lang;
$config->process->search['module'] = 'process';

$config->process->search['fields']['id']           = $lang->process->id;
$config->process->search['fields']['name']         = $lang->process->name;
$config->process->search['fields']['type']         = $lang->process->type;
$config->process->search['fields']['abbr']         = $lang->process->abbr;
$config->process->search['fields']['createdBy']    = $lang->process->createdBy;
$config->process->search['fields']['createdDate']  = $lang->process->createdDate;
$config->process->search['fields']['editedBy']     = $lang->process->editedBy;
$config->process->search['fields']['editedDate']   = $lang->process->editedDate;
$config->process->search['fields']['assignedTo']   = $lang->process->assignedTo;
$config->process->search['fields']['assignedBy']   = $lang->process->assignedBy;
$config->process->search['fields']['assignedDate'] = $lang->process->assignedDate;

$config->process->search['params']['id']           = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->process->search['params']['name']         = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->process->search['params']['type']         = array('operator' => '=', 'control' => 'select', 'values' => $lang->process->classify);
$config->process->search['params']['abbr']         = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->process->search['params']['createdBy']    = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->process->search['params']['createdDate']  = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->process->search['params']['editedBy']     = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->process->search['params']['editedDate']   = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->process->search['params']['assignedTo']   = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->process->search['params']['assignedBy']   = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->process->search['params']['assignedDate'] = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
