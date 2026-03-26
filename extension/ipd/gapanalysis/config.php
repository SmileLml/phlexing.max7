<?php
$config->gapanalysis->editor = new stdclass();
$config->gapanalysis->editor->create = array('id' => 'analysis', 'tools' => 'simpleTools');
$config->gapanalysis->editor->edit   = array('id' => 'analysis', 'tools' => 'simpleTools');
$config->gapanalysis->editor->view   = array('id' => 'lastComment', 'tools' => 'simpleTools');

$config->gapanalysis->create = new stdclass();
$config->gapanalysis->create->requiredFields = 'account';


global $lang;
$config->gapanalysis->search['module']                = 'gapanalysis';
$config->gapanalysis->search['fields']['id']          = $lang->gapanalysis->id;
$config->gapanalysis->search['fields']['account']     = $lang->gapanalysis->account;
$config->gapanalysis->search['fields']['role']        = $lang->gapanalysis->role;
$config->gapanalysis->search['fields']['analysis']    = $lang->gapanalysis->analysis;
$config->gapanalysis->search['fields']['needTrain']   = $lang->gapanalysis->needTrain;
$config->gapanalysis->search['fields']['createdBy']   = $lang->gapanalysis->createdBy;
$config->gapanalysis->search['fields']['createdDate'] = $lang->gapanalysis->createdDate;
$config->gapanalysis->search['fields']['editedBy']    = $lang->gapanalysis->editedBy;
$config->gapanalysis->search['fields']['editedDate']  = $lang->gapanalysis->editedDate;

$config->gapanalysis->search['params']['account']     = array('operator' => '=', 'control' => 'select',  'values' => '');
$config->gapanalysis->search['params']['role']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->gapanalysis->search['params']['analysis']    = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->gapanalysis->search['params']['needTrain']   = array('operator' => '=', 'control' => 'select',  'values' => arrayUnion(array('' => ''), $lang->gapanalysis->needTrainList));
$config->gapanalysis->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->gapanalysis->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->gapanalysis->search['params']['editedBy']    = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->gapanalysis->search['params']['editedDate']  = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
