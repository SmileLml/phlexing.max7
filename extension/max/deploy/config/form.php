<?php
$config->deploy->form = new stdclass();

$config->deploy->form->manageScope = array();
$config->deploy->form->manageScope['service'] = array('type' => 'int',   'required' => true,  'default' => '', 'base' => true);
$config->deploy->form->manageScope['hosts']   = array('type' => 'array', 'required' => false, 'default' => array());
$config->deploy->form->manageScope['remove']  = array('type' => 'array', 'required' => false, 'default' => array());
$config->deploy->form->manageScope['add']     = array('type' => 'array', 'required' => false, 'default' => array());

$config->deploy->form->manageStep = array();
$config->deploy->form->manageStep['id']         = array('type' => 'array',  'required' => false, 'default' => array());
$config->deploy->form->manageStep['title']      = array('type' => 'array',  'required' => true,  'default' => array());
$config->deploy->form->manageStep['content']    = array('type' => 'array',  'required' => false, 'default' => array());

$config->deploy->form->editStep = array();
$config->deploy->form->editStep['status']     = array('type' => 'string', 'required' => false, 'default' => '');
$config->deploy->form->editStep['title']      = array('type' => 'string', 'required' => true,  'default' => '');
$config->deploy->form->editStep['content']    = array('type' => 'string', 'required' => false, 'default' => '');
$config->deploy->form->editStep['assignedTo'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->deploy->form->editStep['finishedBy'] = array('type' => 'string', 'required' => false, 'default' => '');

$config->deploy->form->create = array();
$config->deploy->form->create['name']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->deploy->form->create['host']        = array('type' => 'int', 'required' => false,  'default' => 0);
$config->deploy->form->create['desc']        = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim', 'control' => 'editor');
$config->deploy->form->create['owner']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->deploy->form->create['estimate']    = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->deploy->form->create['members']     = array('type' => 'array',  'required' => false, 'default' => array(), 'filter' => 'join');
$config->deploy->form->create['products']    = array('type' => 'array',  'required' => false, 'default' => array());
$config->deploy->form->create['release']     = array('type' => 'array',  'required' => false, 'default' => array());
$config->deploy->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->deploy->form->edit = array();
$config->deploy->form->edit['name']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->deploy->form->edit['host']        = array('type' => 'int', 'required' => false,  'default' => 0);
$config->deploy->form->edit['desc']        = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim', 'control' => 'editor');
$config->deploy->form->edit['owner']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->deploy->form->edit['estimate']    = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->deploy->form->edit['members']     = array('type' => 'array',  'required' => false, 'default' => array(), 'filter' => 'join');
$config->deploy->form->edit['products']    = array('type' => 'array',  'required' => false, 'default' => array());
$config->deploy->form->edit['release']     = array('type' => 'array',  'required' => false, 'default' => array());

$config->deploy->form->activate = array();
$config->deploy->form->activate['begin']   = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->deploy->form->activate['end']     = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');

$config->deploy->form->finish = array();
$config->deploy->form->finish['begin']   = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->deploy->form->finish['end']     = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->deploy->form->finish['members'] = array('type' => 'array',  'required' => false, 'default' => array(), 'filter' => 'join');
$config->deploy->form->finish['result']  = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
