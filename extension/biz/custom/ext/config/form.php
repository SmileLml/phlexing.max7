<?php
if(!isset($config->custom->form)) $config->custom->form = new stdclass();
$config->custom->form->setCharterInfo['key']   = array('type' => 'string');
$config->custom->form->setCharterInfo['level'] = array('type' => 'string', 'base' => true);
$config->custom->form->setCharterInfo['index'] = array('type' => 'array');
$config->custom->form->setCharterInfo['name']  = array('type' => 'array');
