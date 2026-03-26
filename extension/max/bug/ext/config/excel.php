<?php
$config->bug->listFields     = "module,project,execution,plan,story,severity,pri,type,os,browser,openedBuild";
$config->bug->sysListFields  = "module,execution,story";
$config->bug->templateFields = "product,branch,module,project,execution,story,title,keywords,severity,pri,type,os,browser,steps,deadline,openedBuild,feedbackBy,notifyEmail";

$config->bug->form->import = array();
$config->bug->form->import['id']          = array('required' => false, 'type' => 'int',    'base' => true);
$config->bug->form->import['product']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->import['module']      = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->import['project']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->import['execution']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->import['story']       = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->import['title']       = array('required' => true,  'type' => 'string', 'filter'  => 'trim');
$config->bug->form->import['keywords']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->import['severity']    = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->import['pri']         = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->import['type']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->import['os']          = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->import['browser']     = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->import['steps']       = array('required' => false, 'type' => 'string', 'default' => $lang->bug->tplStep . $lang->bug->tplResult . $lang->bug->tplExpect, 'control' => 'importor');
$config->bug->form->import['deadline']    = array('required' => false, 'type' => 'date',   'default' => null);
$config->bug->form->import['openedBuild'] = array('required' => true,  'type' => 'array',  'filter'  => 'join');
$config->bug->form->import['feedbackBy']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->import['notifyEmail'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->import['injection']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->import['identify']    = array('required' => false, 'type' => 'int',    'default' => 0);
