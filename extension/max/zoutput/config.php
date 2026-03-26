<?php
$config->zoutput->create = new stdclass();
$config->zoutput->edit   = new stdclass();

$config->zoutput->create->requiredFields = 'name,activity';
$config->zoutput->edit->requiredFields   = 'name,activity';

$config->zoutput->editor          = new stdclass();
$config->zoutput->editor->view    = array('id' => 'content,lastComment', 'tools' => 'simpleTools');
$config->zoutput->editor->create  = array('id' => 'content', 'tools' => 'simpleTools');
$config->zoutput->editor->edit    = array('id' => 'content', 'tools' => 'simpleTools');

global $lang;
$config->zoutput->search['module'] = 'zoutput';

$config->zoutput->search['fields']['name']        = $lang->zoutput->name;
$config->zoutput->search['fields']['optional']    = $lang->zoutput->optional;
$config->zoutput->search['fields']['createdBy']   = $lang->zoutput->createdBy;
$config->zoutput->search['fields']['activity']    = $lang->zoutput->activity;
$config->zoutput->search['fields']['createdDate'] = $lang->zoutput->createdDate;
$config->zoutput->search['fields']['editedBy']    = $lang->zoutput->editedBy;
$config->zoutput->search['fields']['editedDate']  = $lang->zoutput->editedDate;

$config->zoutput->search['params']['name']        = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->zoutput->search['params']['optional']    = array('operator' => '=', 'control' => 'select', 'values' => arrayUnion(array('' => ''), $lang->zoutput->optionalList));
$config->zoutput->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->zoutput->search['params']['activity']    = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->zoutput->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->zoutput->search['params']['editedBy']    = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->zoutput->search['params']['editedDate']  = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
