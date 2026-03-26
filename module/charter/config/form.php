<?php
$config->charter->form = new stdclass();

$config->charter->form->review = array();
$config->charter->form->review['reviewResult']  = array('type' => 'string', 'default' => 'pass');
$config->charter->form->review['reviewOpinion'] = array('type' => 'string', 'default' => '', 'control' => 'editor');
$config->charter->form->review['setReviewer']   = array('type' => 'string', 'default' => '');

$config->charter->form->projectApproval = array();
$config->charter->form->projectApproval['appliedBy']   = array('type' => 'string', 'default' => '', 'control' => 'select', 'options' => 'users');
$config->charter->form->projectApproval['appliedDate'] = array('type' => 'string', 'default' => '', 'control' => '',       'default' => helper::now());

$config->charter->form->completionApproval = array();
$config->charter->form->completionApproval['desc']        = array('type' => 'string', 'default' => '', 'control' => 'editor');
$config->charter->form->completionApproval['appliedBy']   = array('type' => 'string', 'default' => '', 'control' => 'select', 'options' => 'users');
$config->charter->form->completionApproval['appliedDate'] = array('type' => 'string', 'default' => '', 'control' => '',       'default' => helper::now());

$config->charter->form->cancelProjectApproval = array();
$config->charter->form->cancelProjectApproval['desc']        = array('type' => 'string', 'default' => '', 'control' => 'editor');
$config->charter->form->cancelProjectApproval['appliedBy']   = array('type' => 'string', 'default' => '', 'control' => 'select', 'options' => 'users');
$config->charter->form->cancelProjectApproval['appliedDate'] = array('type' => 'string', 'default' => '', 'control' => '',       'default' => helper::now());

$config->charter->form->activateProjectApproval = array();
$config->charter->form->activateProjectApproval['appliedBy']   = array('type' => 'string', 'default' => '', 'control' => 'select', 'options' => 'users');
$config->charter->form->activateProjectApproval['appliedDate'] = array('type' => 'string', 'default' => '', 'control' => '',       'default' => helper::now());
