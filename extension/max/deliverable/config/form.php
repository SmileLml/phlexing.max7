<?php
$config->deliverable->form = new stdclass();
$config->deliverable->form->create = array();
$config->deliverable->form->create['name']   = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->deliverable->form->create['module'] = array('type' => 'string', 'required' => true);
$config->deliverable->form->create['method'] = array('type' => 'string', 'required' => true);
$config->deliverable->form->create['model']  = array('type' => 'array',  'required' => true, 'filter' => 'join');
$config->deliverable->form->create['desc']   = array('type' => 'string', 'required' => false, 'control' => 'editor');

$config->deliverable->form->edit = array();
$config->deliverable->form->edit['name']        = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->deliverable->form->edit['module']      = array('type' => 'string', 'required' => true);
$config->deliverable->form->edit['method']      = array('type' => 'string', 'required' => true);
$config->deliverable->form->edit['model']       = array('type' => 'array',  'required' => true, 'filter' => 'join');
$config->deliverable->form->edit['desc']        = array('type' => 'string', 'required' => false, 'control' => 'editor');
$config->deliverable->form->edit['deleteFiles'] = array('type' => 'array',   'control' => 'hidden',       'required' => false, 'default' => array());
$config->deliverable->form->edit['renameFiles'] = array('type' => 'array',   'control' => 'hidden',       'required' => false, 'default' => array());
