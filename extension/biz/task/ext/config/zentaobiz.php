<?php
$config->task->form->create['docs'] = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');

$config->task->form->edit['docs']        = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->task->form->edit['oldDocs']     = array('type' => 'array', 'required' => false, 'default' => array());
$config->task->form->edit['docVersions'] = array('type' => 'array', 'required' => false, 'default' => array());
