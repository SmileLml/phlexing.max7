<?php
$config->project->form->create['charter']       = array('type' => 'int',    'required' => false, 'default' => 0);
$config->project->form->create['workflowGroup'] = array('type' => 'int',    'control' => 'select', 'required' => false, 'default' => '0', 'options' => array());
$config->project->form->create['linkType']      = array('type' => 'string', 'control' => 'hidden', 'default' => 'plan');

$config->project->form->edit['charter']       = array('type' => 'int',    'required' => false, 'default' => 0);
$config->project->form->edit['workflowGroup'] = array('type' => 'int',    'control' => 'select', 'required' => false, 'default' => '0', 'options' => array());
$config->project->form->edit['linkType']      = array('type' => 'string', 'control' => 'hidden', 'default' => 'plan');
