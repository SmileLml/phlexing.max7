<?php
global $lang;
$now = helper::now();

$config->feedback->form = new stdclass();

$config->feedback->form->assignTo = array();
$config->feedback->form->assignTo['assignedTo']   = array('type' => 'string', 'default' => '');
$config->feedback->form->assignTo['assignedDate'] = array('type' => 'date',   'default' => $now);
$config->feedback->form->assignTo['editedBy']     = array('type' => 'string', 'default' => '');
$config->feedback->form->assignTo['editedDate']   = array('type' => 'date',   'default' => $now);
$config->feedback->form->assignTo['mailto']       = array('type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->feedback->form->assignTo['comment']      = array('type' => 'string', 'default' => '',        'control' => 'editor');

$config->feedback->form->batchcreate['module']      = array('label' => $lang->feedback->module,      'name' => 'module',      'type' => 'int',    'control' => array('control' => 'picker', 'required' => true), 'required' => false, 'width' => '200px', 'default' => 0, 'ditto' => true);
$config->feedback->form->batchcreate['title']       = array('label' => $lang->feedback->title,       'name' => 'title',       'type' => 'string', 'control' => 'text',      'required' => true, 'width' => '240px', 'default' => '', 'filter'  => 'trim', 'base' => true);
$config->feedback->form->batchcreate['desc']        = array('label' => $lang->feedback->desc,        'name' => 'desc',        'type' => 'string', 'control' => 'textarea',  'required' => false, 'width' => '160px', 'default' => '');
$config->feedback->form->batchcreate['public']      = array('label' => $lang->feedback->public,      'name' => 'public',      'type' => 'int',    'control' => array('control' => 'radioList', 'inline' => true), 'required' => false, 'width' => '100px', 'defalue' => 1, 'value' => '1', 'items' => $lang->feedback->publicListAB);
$config->feedback->form->batchcreate['pri']         = array('label' => $lang->feedback->pri,         'name' => 'pri',         'type' => 'int',    'control' => 'priPicker', 'required' => false, 'width' => '120px', 'default' => 3, 'value' => '3', 'ditto' => true, 'items' => array_filter($lang->feedback->priList));
$config->feedback->form->batchcreate['type']        = array('label' => $lang->feedback->type,        'name' => 'type',        'type' => 'string', 'control' => 'picker',    'required' => false, 'width' => '160px', 'default' => '', 'ditto' => true, 'items' => $lang->feedback->typeList);
$config->feedback->form->batchcreate['feedbackBy']  = array('label' => $lang->feedback->feedbackBy,  'name' => 'feedbackBy',  'type' => 'string', 'control' => 'text',      'required' => false, 'width' => '160px', 'default' => '');
$config->feedback->form->batchcreate['source']      = array('label' => $lang->feedback->source,      'name' => 'source',      'type' => 'string', 'control' => 'text',      'required' => false, 'width' => '160px', 'default' => '');
$config->feedback->form->batchcreate['notifyEmail'] = array('label' => $lang->feedback->notifyEmail, 'name' => 'notifyEmail', 'type' => 'string', 'control' => 'text',      'required' => false, 'width' => '160px', 'default' => '');
$config->feedback->form->batchcreate['keywords']    = array('label' => $lang->feedback->keywords,    'name' => 'keywords',    'type' => 'string', 'control' => 'text',      'required' => false, 'width' => '160px', 'default' => '');
$config->feedback->form->batchcreate['mailto']      = array('label' => $lang->feedback->mailto,      'name' => 'mailto',      'type' => 'array',  'control' => 'picker',    'required' => false, 'width' => '200px', 'default' => array(''), 'multiple' => true, 'filter' => 'join');
