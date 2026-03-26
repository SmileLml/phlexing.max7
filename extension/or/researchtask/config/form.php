<?php
global $app;

$config->researchtask->form = new stdclass();
$config->researchtask->form->batchcreate = array();
$config->researchtask->form->batchcreate['parent']        = array('type' => 'int',      'default' => 0);
$config->researchtask->form->batchcreate['execution']     = array('type' => 'int',      'default' => 0);
$config->researchtask->form->batchcreate['project']       = array('type' => 'int',      'default' => 0);
$config->researchtask->form->batchcreate['lane']          = array('type' => 'int',      'default' => 0);
$config->researchtask->form->batchcreate['column']        = array('type' => 'int',      'default' => 0);
$config->researchtask->form->batchcreate['name']          = array('type' => 'string',   'default' => '', 'base' => true);
$config->researchtask->form->batchcreate['color']         = array('type' => 'string',   'default' => '');
$config->researchtask->form->batchcreate['type']          = array('type' => 'string',   'default' => '');
$config->researchtask->form->batchcreate['version']       = array('type' => 'int',      'default' => 1);
$config->researchtask->form->batchcreate['assignedTo']    = array('type' => 'string',   'default' => '');
$config->researchtask->form->batchcreate['estimate']      = array('type' => 'float',    'default' => 0);
$config->researchtask->form->batchcreate['estStarted']    = array('type' => 'date',     'default' => null);
$config->researchtask->form->batchcreate['deadline']      = array('type' => 'date',     'default' => null);
$config->researchtask->form->batchcreate['desc']          = array('type' => 'string',   'default' => '');
$config->researchtask->form->batchcreate['pri']           = array('type' => 'int',      'default' => 3);
$config->researchtask->form->batchcreate['openedBy']      = array('type' => 'string',   'default' => $app->user->account);
$config->researchtask->form->batchcreate['openedDate']    = array('type' => 'datetime', 'default' => helper::now());
$config->researchtask->form->batchcreate['vision']        = array('type' => 'string',   'default' => 'or');
$config->researchtask->form->batchcreate['level']         = array('type' => 'string',   'required' => false, 'default' => 0);
