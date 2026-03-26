<?php
$config->repo->editor->view                     = array('id' => 'commentText', 'tools' => 'simpleTools');
$config->repo->editor->diff                     = array('id' => 'commentText', 'tools' => 'simpleTools');
$config->repo->editor->ajaxgeteditorcontent     = array('id' => 'commentText', 'tools' => 'simpleTools');
$config->repo->editor->ajaxgetdiffeditorcontent = array('id' => 'commentText', 'tools' => 'simpleTools');

$now = helper::now();
$config->repo->form->addBug = array();
$config->repo->form->addBug['file']          = array('required' => true,  'type' => 'string');
$config->repo->form->addBug['revision']      = array('required' => true,  'type' => 'string');
$config->repo->form->addBug['product']       = array('required' => true,  'type' => 'int');
$config->repo->form->addBug['title']         = array('required' => true,  'type' => 'string');
$config->repo->form->addBug['begin']         = array('required' => true,  'type' => 'int');
$config->repo->form->addBug['end']           = array('required' => true,  'type' => 'int');
$config->repo->form->addBug['branch']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->repo->form->addBug['execution']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->repo->form->addBug['pri']           = array('required' => false, 'type' => 'int',    'default' => 3);
$config->repo->form->addBug['severity']      = array('required' => false, 'type' => 'int',    'default' => 3);
$config->repo->form->addBug['module']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->repo->form->addBug['repoType']      = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->addBug['assignedTo']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->addBug['steps']         = array('required' => false, 'type' => 'string', 'default' => '', 'control' => 'editor');
$config->repo->form->addBug['fromReversion'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->addBug['severity']      = array('required' => false, 'type' => 'int',    'default' => 3);
$config->repo->form->addBug['openedDate']    = array('required' => false, 'type' => 'string', 'default' => $now);
$config->repo->form->addBug['assignedDate']  = array('required' => false, 'type' => 'string', 'default' => $now);
$config->repo->form->addBug['openedBuild']   = array('required' => false, 'type' => 'string', 'default' => 'trunk');
$config->repo->form->addBug['type']          = array('required' => false, 'type' => 'string', 'default' => 'codeimprovement');
