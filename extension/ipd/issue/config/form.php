<?php
global $lang;
$config->issue->form = new stdclass();
$config->issue->form->batchcreate = array();
$config->issue->form->batchcreate['execution']  = array('name' => 'execution',  'label' => $lang->issue->execution,  'control' => 'picker',         'required' => false, 'width' => '150px', 'type' => 'int',    'default' => 0);
$config->issue->form->batchcreate['title']      = array('name' => 'title',      'label' => $lang->issue->title,      'control' => 'input',          'required' => true,                      'type' => 'string', 'default' => '', 'base' => true);
$config->issue->form->batchcreate['type']       = array('name' => 'type',       'label' => $lang->issue->type,       'control' => 'picker',         'required' => true,  'width' => '120px', 'type' => 'string', 'default' => '', 'items' => $lang->issue->typeList);
$config->issue->form->batchcreate['severity']   = array('name' => 'severity',   'label' => $lang->issue->severity,   'control' => 'severityPicker', 'required' => true,  'width' => '120px', 'type' => 'int',    'default' => 0, 'items' => $lang->issue->severityList);
$config->issue->form->batchcreate['desc']       = array('name' => 'desc',       'label' => $lang->issue->desc,       'control' => 'textarea',       'required' => false, 'width' => '150px', 'type' => 'string', 'default' => '');
$config->issue->form->batchcreate['pri']        = array('name' => 'pri',        'label' => $lang->issue->pri,        'control' => 'priPicker',      'required' => false, 'width' => '100px', 'type' => 'int',    'default' => 0, 'items' => $lang->issue->priList);
$config->issue->form->batchcreate['deadline']   = array('name' => 'deadline',   'label' => $lang->issue->deadline,   'control' => 'datePicker',     'required' => false, 'width' => '120px', 'type' => 'date',   'default' => null);
$config->issue->form->batchcreate['assignedTo'] = array('name' => 'assignedTo', 'label' => $lang->issue->assignedTo, 'control' => 'picker',         'required' => false, 'width' => '120px', 'type' => 'string', 'default' => '');
$config->issue->form->batchcreate['owner']      = array('name' => 'owner',      'label' => $lang->issue->owner,      'control' => 'picker',         'required' => false, 'width' => '120px', 'type' => 'string', 'default' => '');
