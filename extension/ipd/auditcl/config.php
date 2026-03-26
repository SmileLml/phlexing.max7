<?php 
$config->auditcl = new stdClass();
$config->auditcl->requiredFields = 'title,practiceArea,type';

global $lang;
$config->auditcl->search['module']                 = 'auditcl';
$config->auditcl->search['fields']['id']           = $lang->auditcl->id;
$config->auditcl->search['fields']['practiceArea'] = $lang->auditcl->practiceArea;
$config->auditcl->search['fields']['type']         = $lang->auditcl->objectType;
$config->auditcl->search['fields']['title']        = $lang->auditcl->title;
$config->auditcl->search['fields']['objectType']   = $lang->auditcl->type ;
$config->auditcl->search['fields']['createdBy']    = $lang->auditcl->createdBy;
$config->auditcl->search['fields']['createdDate']  = $lang->auditcl->createdDate;
$config->auditcl->search['fields']['editedBy']     = $lang->auditcl->editedBy;
$config->auditcl->search['fields']['editedDate']   = $lang->auditcl->editedDate;

$config->auditcl->search['params']['objectType']   = array('operator' => '=', 'control' => 'select', 'values' => $lang->auditcl->objectTypeList);
$config->auditcl->search['params']['practiceArea'] = array('operator' => '=', 'control' => 'select', 'values' => $lang->auditcl->practiceAreaList);
$config->auditcl->search['params']['type']         = array('operator' => '=', 'control' => 'select', 'values' => $lang->auditcl->typeList);
$config->auditcl->search['params']['title']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->auditcl->search['params']['createdBy']    = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->auditcl->search['params']['createdDate']  = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->auditcl->search['params']['editedBy']     = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->auditcl->search['params']['editedDate']   = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
