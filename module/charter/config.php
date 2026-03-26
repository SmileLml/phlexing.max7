<?php
$config->charter->create = new stdclass();
$config->charter->edit   = new stdclass();
$config->charter->close  = new stdclass();

$config->charter->create->requiredFields = 'name';
$config->charter->edit->requiredFields   = 'name';
$config->charter->close->requiredFields  = 'closedReason';

$config->charter->editor = new stdclass();
$config->charter->editor->create = array('id' => 'spec', 'tools' => 'simpleTools');
$config->charter->editor->edit   = array('id' => 'spec', 'tools' => 'simpleTools');
$config->charter->editor->close  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->charter->editor->review = array('id' => 'meetingMinutes', 'tools' => 'simpleTools');
$config->charter->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

/* Search. */
global $lang;
$config->charter->search['module'] = 'charter';
$config->charter->search['fields']['id']                = $lang->charter->id;
$config->charter->search['fields']['name']              = $lang->charter->name;
$config->charter->search['fields']['level']             = $lang->charter->level;
$config->charter->search['fields']['category']          = $lang->charter->category;
$config->charter->search['fields']['market']            = $lang->charter->market;
$config->charter->search['fields']['budget']            = $lang->charter->budget;
$config->charter->search['fields']['status']            = $lang->charter->status;
$config->charter->search['fields']['reviewStatus']      = $lang->charter->reviewStatus;
$config->charter->search['fields']['createdBy']         = $lang->charter->createdBy;
$config->charter->search['fields']['createdDate']       = $lang->charter->createdDate;
$config->charter->search['fields']['appliedBy']         = $lang->charter->appliedBy;
$config->charter->search['fields']['appliedDate']       = $lang->charter->appliedDate;
$config->charter->search['fields']['completedBy']       = $lang->charter->completedBy;
$config->charter->search['fields']['completedDate']     = $lang->charter->completedDate;
$config->charter->search['fields']['canceledBy']        = $lang->charter->canceledBy;
$config->charter->search['fields']['canceledDate']      = $lang->charter->canceledDate;
$config->charter->search['fields']['activatedBy']       = $lang->charter->activatedBy;
$config->charter->search['fields']['activatedDate']     = $lang->charter->activatedDate;
$config->charter->search['fields']['closedBy']          = $lang->charter->closedBy;
$config->charter->search['fields']['closedDate']        = $lang->charter->closedDate;
$config->charter->search['fields']['closedReason']      = $lang->charter->closedReason;

$config->charter->search['params']['id']                = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->charter->search['params']['name']              = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->charter->search['params']['level']             = array('operator' => '=',       'control' => 'select', 'values' => $lang->charter->levelList);
$config->charter->search['params']['category']          = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->charter->categoryList);
$config->charter->search['params']['market']            = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->charter->marketList);
$config->charter->search['params']['budget']            = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->charter->search['params']['status']            = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->charter->statusList);
$config->charter->search['params']['reviewStatus']      = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->charter->reviewStatusList);
$config->charter->search['params']['createdBy']         = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->charter->search['params']['createdDate']       = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->charter->search['params']['appliedBy']         = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->charter->search['params']['appliedDate']       = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->charter->search['params']['appliedReviewer']   = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->charter->search['params']['completedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->charter->search['params']['completedDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->charter->search['params']['completedReviewer'] = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->charter->search['params']['canceledBy']        = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->charter->search['params']['canceledDate']      = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->charter->search['params']['canceledReviewer']  = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->charter->search['params']['activatedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->charter->search['params']['activatedDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->charter->search['params']['activatedReviewer'] = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->charter->search['params']['closedBy']          = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->charter->search['params']['closedDate']        = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->charter->search['params']['closedReason']      = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->charter->closeReasonList);
