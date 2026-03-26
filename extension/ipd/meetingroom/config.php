<?php
$config->meetingroom->create = new stdclass();
$config->meetingroom->edit   = new stdclass();
$config->meetingroom->create->requiredFields = 'name,position,seats,equipment,openTime';
$config->meetingroom->edit->requiredFields   = 'name,position,seats,equipment,openTime';

global $lang;
$config->meetingroom->search['module']                = 'meetingroom';
$config->meetingroom->search['fields']['id']          = $lang->meetingroom->id;
$config->meetingroom->search['fields']['name']        = $lang->meetingroom->name;
$config->meetingroom->search['fields']['position']    = $lang->meetingroom->position;
$config->meetingroom->search['fields']['seats']       = $lang->meetingroom->seats;
$config->meetingroom->search['fields']['equipment']   = $lang->meetingroom->equipment;
$config->meetingroom->search['fields']['openTime']    = $lang->meetingroom->openTime;
$config->meetingroom->search['fields']['createdBy']   = $lang->meetingroom->createdBy;
$config->meetingroom->search['fields']['createdDate'] = $lang->meetingroom->createdDate;
$config->meetingroom->search['fields']['editedBy']    = $lang->meetingroom->editedBy;
$config->meetingroom->search['fields']['editedDate']  = $lang->meetingroom->editedDate;

$config->meetingroom->search['params']['name']        = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->meetingroom->search['params']['position']    = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->meetingroom->search['params']['seats']       = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->meetingroom->search['params']['equipment']   = array('operator' => 'include', 'control' => 'select', 'values' => arrayUnion(array('' => ''), $this->lang->meetingroom->equipmentList));
$config->meetingroom->search['params']['openTime']    = array('operator' => 'include', 'control' => 'select', 'values' => arrayUnion(array('' => ''), $this->lang->meetingroom->openTimeList));
$config->meetingroom->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->meetingroom->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->meetingroom->search['params']['editedBy']    = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->meetingroom->search['params']['editedDate']  = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
