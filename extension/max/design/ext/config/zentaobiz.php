<?php
$config->design->form->create['docs'] = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');

$config->design->form->edit['docs']        = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->design->form->edit['oldDocs']     = array('type' => 'array', 'required' => false, 'default' => array());
$config->design->form->edit['docVersions'] = array('type' => 'array', 'required' => false, 'default' => array());
