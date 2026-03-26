<?php
$config->reviewissue->resolve = new stdClass();
$config->reviewissue->resolve->requiredFields = 'resolution';

$config->reviewissue->create = new stdClass();
$config->reviewissue->create->requiredFields = 'listID';

global $lang;
$config->reviewissue->search['onMenuBar']             = 'yes';
$config->reviewissue->search['module']                = 'reviewissue';
$config->reviewissue->search['fields']['title']       = $lang->reviewissue->title;
$config->reviewissue->search['fields']['id']          = $lang->reviewissue->id;
$config->reviewissue->search['fields']['review']      = $lang->reviewissue->review;
$config->reviewissue->search['fields']['opinion']     = $lang->reviewissue->opinion;
$config->reviewissue->search['fields']['status']      = $lang->reviewissue->status;
$config->reviewissue->search['fields']['type']        = $lang->reviewissue->type;
$config->reviewissue->search['fields']['createdBy']   = $lang->reviewissue->createdBy;
$config->reviewissue->search['fields']['createdDate'] = $lang->reviewissue->createdDate;

$config->reviewissue->search['params']['title']       = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->reviewissue->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->reviewissue->search['params']['review']      = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->reviewissue->search['params']['opinion']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->reviewissue->search['params']['type']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->reviewissue->search['params']['status']      = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->reviewissue->search['params']['createdBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->reviewissue->search['params']['createdDate'] = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
