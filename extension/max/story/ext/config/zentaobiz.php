<?php
$config->story->form->create['docs'] = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');

$config->story->form->edit['docs']        = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->story->form->edit['oldDocs']     = array('type' => 'array', 'required' => false, 'default' => array());
$config->story->form->edit['docVersions'] = array('type' => 'array', 'required' => false, 'default' => array());

$config->story->form->change['docs']        = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->story->form->change['oldDocs']     = array('type' => 'array', 'required' => false, 'default' => array());
$config->story->form->change['docVersions'] = array('type' => 'array', 'required' => false, 'default' => array());
