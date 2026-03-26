<?php
$config->researchreport = new stdclass();
$config->researchreport->create = new stdclass();
$config->researchreport->edit   = new stdclass();
$config->researchreport->create->requiredFields = 'title,relatedPlan';
$config->researchreport->edit->requiredFields   = $config->researchreport->create->requiredFields;

$config->researchreport->editor = new stdclass();
$config->researchreport->editor->create = array('id' => 'content', 'tools' => 'simpleTools');
$config->researchreport->editor->edit   = array('id' => 'content', 'tools' => 'simpleTools');

 /* Search. */
global $lang;
$config->researchreport->search['module'] = 'researchreport';
$config->researchreport->search['fields']['id']              = $lang->researchreport->id;
$config->researchreport->search['fields']['title']           = $lang->researchreport->title;
$config->researchreport->search['fields']['relatedPlan']     = $lang->researchreport->relatedPlan;
$config->researchreport->search['fields']['author']          = $lang->researchreport->author;
$config->researchreport->search['fields']['content']         = $lang->researchreport->content;
$config->researchreport->search['fields']['customer']        = $lang->researchreport->customer;
$config->researchreport->search['fields']['researchObjects'] = $lang->researchreport->researchObjects;
$config->researchreport->search['fields']['begin']           = $lang->researchreport->begin;
$config->researchreport->search['fields']['end']             = $lang->researchreport->end;
$config->researchreport->search['fields']['location']        = $lang->researchreport->location;
$config->researchreport->search['fields']['method']          = $lang->researchreport->method;
$config->researchreport->search['fields']['createdBy']       = $lang->researchreport->createdBy;
$config->researchreport->search['fields']['createdDate']     = $lang->researchreport->createdDate;
$config->researchreport->search['fields']['editedBy']        = $lang->researchreport->editedBy;
$config->researchreport->search['fields']['editedDate']      = $lang->researchreport->editedDate;

$config->researchreport->search['params']['id']              = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->researchreport->search['params']['title']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchreport->search['params']['relatedPlan']     = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->researchreport->search['params']['author']          = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->researchreport->search['params']['content']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchreport->search['params']['customer']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchreport->search['params']['researchObjects'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchreport->search['params']['begin']           = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->researchreport->search['params']['end']             = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->researchreport->search['params']['location']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->researchreport->search['params']['method']          = array('operator' => '=',       'control' => 'select', 'values' => $this->lang->researchreport->methodList);
$config->researchreport->search['params']['createdBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->researchreport->search['params']['createdDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->researchreport->search['params']['editedBy']        = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->researchreport->search['params']['editedDate']      = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
