<?php
$config->researchplan = new stdclass();
$config->researchplan->create = new stdclass();
$config->researchplan->edit   = new stdclass();
$config->researchplan->create->requiredFields = 'name';
$config->researchplan->edit->requiredFields   = $config->researchplan->create->requiredFields;

$config->researchplan->editor = new stdclass();
$config->researchplan->editor->create = array('id' => 'outline,schedule', 'tools' => 'simpleTools');
$config->researchplan->editor->edit   = array('id' => 'outline,schedule', 'tools' => 'simpleTools');

 /* Search. */
global $lang;
$config->researchplan->search['module'] = 'researchplan';
$config->researchplan->search['fields']['id']          = $lang->researchplan->id;
$config->researchplan->search['fields']['name']        = $lang->researchplan->name;
$config->researchplan->search['fields']['customer']    = $lang->researchplan->customer;
$config->researchplan->search['fields']['stakeholder'] = $lang->researchplan->stakeholder;
$config->researchplan->search['fields']['objective']   = $lang->researchplan->objective;
$config->researchplan->search['fields']['begin']       = $lang->researchplan->begin;
$config->researchplan->search['fields']['end']         = $lang->researchplan->end;
$config->researchplan->search['fields']['location']    = $lang->researchplan->location;
$config->researchplan->search['fields']['team']        = $lang->researchplan->team;
$config->researchplan->search['fields']['method']      = $lang->researchplan->method;
$config->researchplan->search['fields']['outline']     = $lang->researchplan->outline;
$config->researchplan->search['fields']['schedule']    = $lang->researchplan->schedule;
$config->researchplan->search['fields']['createdBy']   = $lang->researchplan->createdBy;
$config->researchplan->search['fields']['createdDate'] = $lang->researchplan->createdDate;
$config->researchplan->search['fields']['editedBy']    = $lang->researchplan->editedBy;
$config->researchplan->search['fields']['editedDate']  = $lang->researchplan->editedDate;

$config->researchplan->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->researchplan->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchplan->search['params']['customer']    = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchplan->search['params']['stakeholder'] = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->researchplan->search['params']['objective']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchplan->search['params']['begin']       = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->researchplan->search['params']['end']         = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->researchplan->search['params']['location']    = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchplan->search['params']['team']        = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->researchplan->search['params']['method']      = array('operator' => '=',       'control' => 'select', 'values' => $lang->researchplan->methodList);
$config->researchplan->search['params']['outline']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchplan->search['params']['schedule']    = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchplan->search['params']['createdBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->researchplan->search['params']['createdDate'] = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->researchplan->search['params']['editedBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->researchplan->search['params']['editedDate']  = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
