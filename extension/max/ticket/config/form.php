<?php
global $lang,$config;

$config->ticket->form = new stdclass();

$config->ticket->form->edit = array();
$config->ticket->form->edit['product']     = array('name' => 'product',     'label' => $lang->ticket->product,     'width' => '200px', 'control' => array('control' => 'picker', 'required' => true),     'required' => false, 'type' => 'int',    'default' => 0);
$config->ticket->form->edit['module']      = array('name' => 'module',      'label' => $lang->ticket->module,      'width' => '200px', 'control' => array('control' => 'picker', 'required' => true),     'required' => false, 'type' => 'int',    'default' => 0);
$config->ticket->form->edit['title']       = array('name' => 'title',       'label' => $lang->ticket->title,       'width' => '200px', 'control' => 'input',                                              'required' => true,  'type' => 'string', 'default' => '');
$config->ticket->form->edit['type']        = array('name' => 'type',        'label' => $lang->ticket->type,        'width' => '200px', 'control' => array('control' => 'picker', 'required' => true),     'required' => false, 'type' => 'string', 'default' => '', 'items' => $lang->ticket->typeList);
$config->ticket->form->edit['assignedTo']  = array('name' => 'assignedTo',  'label' => $lang->ticket->assignedTo,  'width' => '150px', 'control' => 'picker',                                             'required' => false, 'type' => 'string', 'default' => '');
$config->ticket->form->edit['pri']         = array('name' => 'pri',         'label' => $lang->ticket->pri,         'width' => '100px', 'control' => array('control' => 'priPicker', 'required' => true),  'required' => false, 'type' => 'int',    'default' => 3,  'value' => 3, 'items' => $lang->ticket->priList);
$config->ticket->form->edit['estimate']    = array('name' => 'estimate',    'label' => $lang->ticket->estimate,    'width' => '100px', 'control' => 'input',                                              'required' => false, 'type' => 'float',  'default' => 0);
$config->ticket->form->edit['desc']        = array('name' => 'desc',        'label' => $lang->ticket->desc,        'width' => '200px', 'control' => 'editor',                                             'required' => false, 'type' => 'string', 'default' => '');
$config->ticket->form->edit['openedBuild'] = array('name' => 'openedBuild', 'label' => $lang->ticket->openedBuild, 'width' => '150px', 'control' => 'picker',                                             'required' => false, 'type' => 'array',  'default' => array(''), 'multiple' => true, 'filter' => 'join');
$config->ticket->form->edit['deadline']    = array('name' => 'deadline',    'label' => $lang->ticket->deadline,    'width' => '120px', 'control' => 'datePicker',                                         'required' => false, 'type' => 'date',   'default' => null);
$config->ticket->form->edit['keywords']    = array('name' => 'keywords',    'label' => $lang->ticket->keywords,    'width' => '160px', 'control' => 'text',                                               'required' => false, 'type' => 'string', 'default' => '');
$config->ticket->form->edit['mailto']      = array('name' => 'mailto',      'label' => $lang->ticket->mailto,      'width' => '200px', 'control' => 'picker',                                             'required' => false, 'type' => 'array',  'default' => array(''), 'multiple' => true, 'filter' => 'join');

$config->ticket->form->batchCreate = array();
$config->ticket->form->batchCreate['id']          = array('name' => 'id',          'label' => $lang->idAB,                'width' => '50px',  'control' => 'index',                                              'required' => false, 'type' => 'int');
$config->ticket->form->batchCreate['module']      = array('name' => 'module',      'label' => $lang->ticket->module,      'width' => '200px', 'control' => array('control' => 'picker', 'required' => true),     'required' => false, 'type' => 'int',    'ditto' => true,  'default' => 0);
$config->ticket->form->batchCreate['title']       = array('name' => 'title',       'label' => $lang->ticket->title,       'width' => '200px', 'control' => 'input',                                              'required' => true,  'type' => 'string', 'ditto' => false, 'default' => '', 'base' => true);
$config->ticket->form->batchCreate['type']        = array('name' => 'type',        'label' => $lang->ticket->type,        'width' => '200px', 'control' => array('control' => 'picker', 'required' => true),     'required' => false, 'type' => 'string', 'ditto' => true,  'default' => '', 'items' => $lang->ticket->typeList);
$config->ticket->form->batchCreate['assignedTo']  = array('name' => 'assignedTo',  'label' => $lang->ticket->assignedTo,  'width' => '150px', 'control' => 'picker',                                             'required' => false, 'type' => 'string', 'ditto' => true,  'default' => '');
$config->ticket->form->batchCreate['pri']         = array('name' => 'pri',         'label' => $lang->ticket->pri,         'width' => '100px', 'control' => array('control' => 'priPicker', 'required' => true),  'required' => false, 'type' => 'int',    'ditto' => true,  'default' => 3,  'value' => 3, 'items' => $lang->ticket->priList);
$config->ticket->form->batchCreate['estimate']    = array('name' => 'estimate',    'label' => $lang->ticket->estimate,    'width' => '100px', 'control' => 'input',                                              'required' => false, 'type' => 'float',  'ditto' => false, 'default' => 0);
$config->ticket->form->batchCreate['desc']        = array('name' => 'desc',        'label' => $lang->ticket->desc,        'width' => '200px', 'control' => 'textarea',                                           'required' => false, 'type' => 'string', 'ditto' => false, 'default' => '');
$config->ticket->form->batchCreate['openedBuild'] = array('name' => 'openedBuild', 'label' => $lang->ticket->openedBuild, 'width' => '150px', 'control' => 'picker',                                             'required' => false, 'type' => 'array',  'ditto' => true,  'default' => array(''), 'multiple' => true, 'filter' => 'join');
$config->ticket->form->batchCreate['deadline']    = array('name' => 'deadline',    'label' => $lang->ticket->deadline,    'width' => '120px', 'control' => 'datePicker',                                         'required' => false, 'type' => 'date',   'ditto' => true,  'default' => null);
$config->ticket->form->batchCreate['customer']    = array('name' => 'customer',    'label' => $lang->ticket->customer,    'width' => '160px', 'control' => 'text',                                               'required' => false, 'type' => 'string', 'ditto' => false, 'default' => '');
$config->ticket->form->batchCreate['contact']     = array('name' => 'contact',     'label' => $lang->ticket->contact,     'width' => '160px', 'control' => 'text',                                               'required' => false, 'type' => 'string', 'ditto' => false, 'default' => '');
$config->ticket->form->batchCreate['notifyEmail'] = array('name' => 'notifyEmail', 'label' => $lang->ticket->notifyEmail, 'width' => '160px', 'control' => 'text',                                               'required' => false, 'type' => 'string', 'ditto' => false, 'default' => '');
$config->ticket->form->batchCreate['keywords']    = array('name' => 'keywords',    'label' => $lang->ticket->keywords,    'width' => '160px', 'control' => 'text',                                               'required' => false, 'type' => 'string', 'ditto' => false, 'default' => '');
$config->ticket->form->batchCreate['mailto']      = array('name' => 'mailto',      'label' => $lang->ticket->mailto,      'width' => '200px', 'control' => 'picker',                                             'required' => false, 'type' => 'array',  'ditto' => false, 'default' => array(''), 'multiple' => true, 'filter' => 'join');

$config->ticket->form->batchEdit = array();
$config->ticket->form->batchEdit['idIndex']    = array('name' => 'id',         'label' => $lang->idAB,               'width' => '50px',  'control' => 'index',     'required' => false, 'type' => 'int');
$config->ticket->form->batchEdit['id']         = array('name' => 'id',         'label' => $lang->idAB,               'width' => '50px',  'control' => 'hidden',    'required' => false, 'type' => 'int',    'className' => 'hidden', 'base' => true);
$config->ticket->form->batchEdit['title']      = array('name' => 'title',      'label' => $lang->ticket->title,      'width' => '200px', 'control' => 'input',     'required' => true,  'type' => 'string', 'default' => '');
$config->ticket->form->batchEdit['product']    = array('name' => 'product',    'label' => $lang->ticket->product,    'width' => '200px', 'control' => 'picker',    'required' => false, 'type' => 'int',    'default' => 0);
$config->ticket->form->batchEdit['module']     = array('name' => 'module',     'label' => $lang->ticket->module,     'width' => '200px', 'control' => 'picker',    'required' => false, 'type' => 'int',    'default' => 0);
$config->ticket->form->batchEdit['pri']        = array('name' => 'pri',        'label' => $lang->ticket->pri,        'width' => '100px', 'control' => 'priPicker', 'required' => false, 'type' => 'int',    'default' => 0);
$config->ticket->form->batchEdit['type']       = array('name' => 'type',       'label' => $lang->ticket->type,       'width' => '200px', 'control' => 'picker',    'required' => false, 'type' => 'string', 'default' => '');
$config->ticket->form->batchEdit['assignedTo'] = array('name' => 'assignedTo', 'label' => $lang->ticket->assignedTo, 'width' => '150px', 'control' => 'picker',    'required' => false, 'type' => 'string', 'default' => '');

$config->ticket->form->batchFinish = array();
$config->ticket->form->batchFinish['idIndex']      = array('name' => 'id',           'label' => $lang->idAB,                    'width' => '50px',  'control' => 'index',      'required' => false, 'type' => 'int');
$config->ticket->form->batchFinish['id']           = array('name' => 'id',           'label' => $lang->idAB,                    'width' => '50px',  'control' => 'hidden',     'required' => false, 'type' => 'int',     'className' => 'hidden', 'base' => true);
$config->ticket->form->batchFinish['title']        = array('name' => 'title',        'label' => $lang->ticket->title,           'width' => '200px', 'control' => 'input',      'required' => true,  'type' => 'string',  'default' => '');
$config->ticket->form->batchFinish['consumed']     = array('name' => 'consumed',     'label' => $lang->ticket->currentConsumed, 'width' => '100px', 'control' => 'input',      'required' => false, 'type' => 'number',  'default' => 0);
$config->ticket->form->batchFinish['resolvedDate'] = array('name' => 'resolvedDate', 'label' => $lang->ticket->resolvedDate,    'width' => '120px', 'control' => 'datePicker', 'required' => true,  'type' => 'date',    'default' => null);
$config->ticket->form->batchFinish['resolution']   = array('name' => 'resolution',   'label' => $lang->ticket->resolution,      'width' => '300px', 'control' => 'input',      'required' => true,  'type' => 'text',    'default' => '');
$config->ticket->form->batchFinish['comment']      = array('name' => 'comment',      'label' => $lang->comment,                 'width' => '300px', 'control' => 'input',      'required' => false, 'type' => 'text',    'default' => '');

$config->ticket->form->batchClose = array();
$config->ticket->form->batchClose['id']           = array('name' => 'id',           'required' => true,  'type' => 'int',    'base' => true);
$config->ticket->form->batchClose['closedReason'] = array('name' => 'closedReason', 'required' => false, 'type' => 'string', 'default' => '');
$config->ticket->form->batchClose['repeatTicket'] = array('name' => 'repeatTicket', 'required' => false, 'type' => 'int',    'default' => 0);
$config->ticket->form->batchClose['resolution']   = array('name' => 'resolution',   'required' => false, 'type' => 'string', 'default' => '');

$config->ticket->form->batchActivate = array();
$config->ticket->form->batchActivate['idIndex']    = array('name' => 'id',         'label' => $lang->idAB,               'width' => '50px',  'control' => 'index',  'required' => false, 'type' => 'int');
$config->ticket->form->batchActivate['id']         = array('name' => 'id',         'label' => $lang->idAB,               'width' => '50px',  'control' => 'hidden', 'required' => false, 'type' => 'int',     'className' => 'hidden', 'base' => true);
$config->ticket->form->batchActivate['title']      = array('name' => 'title',      'label' => $lang->ticket->title,      'width' => '200px', 'control' => 'input',  'required' => true,  'type' => 'string',  'default' => '');
$config->ticket->form->batchActivate['estimate']   = array('name' => 'estimate',   'label' => $lang->ticket->estimate,   'width' => '100px', 'control' => 'input',  'required' => false, 'type' => 'number',  'default' => 0);
$config->ticket->form->batchActivate['assignedTo'] = array('name' => 'assignedTo', 'label' => $lang->ticket->assignedTo, 'width' => '150px', 'control' => 'picker', 'required' => false, 'type' => 'string', 'default' => '');
$config->ticket->form->batchActivate['comment']    = array('name' => 'comment',    'label' => $lang->comment,            'width' => '300px', 'control' => 'input',  'required' => false, 'type' => 'text',    'default' => '');

$config->ticket->form->close = array();
$config->ticket->form->close['closedReason'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->ticket->form->close['repeatTicket'] = array('required' => false, 'type' => 'int',    'default' => 0);
$config->ticket->form->close['resolvedBy']   = array('required' => false, 'type' => 'string', 'default' => '');
$config->ticket->form->close['resolvedDate'] = array('required' => false, 'type' => 'date',   'default' => '');
$config->ticket->form->close['resolution']   = array('required' => false, 'type' => 'string', 'default' => '');
