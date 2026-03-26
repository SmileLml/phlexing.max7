<?php
$config->marketresearch = new stdclass();
$config->marketresearch->create = new stdclass();
$config->marketresearch->edit   = new stdclass();
$config->marketresearch->close  = new stdclass();

$config->marketresearch->create->requiredFields = 'name,market,PM,begin,end';
$config->marketresearch->edit->requiredFields   = 'name,market,PM,begin,end';
$config->marketresearch->close->requiredFields  = 'realEnd,closedReason';

$config->marketresearch->editor = new stdclass();
$config->marketresearch->editor->create     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->marketresearch->editor->edit       = array('id' => 'desc', 'tools' => 'simpleTools');
$config->marketresearch->editor->close      = array('id' => 'comment', 'tools' => 'simpleTools');
$config->marketresearch->editor->start      = array('id' => 'comment', 'tools' => 'simpleTools');
$config->marketresearch->editor->activate   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->marketresearch->editor->view       = array('id' => 'lastComment', 'tools' => 'simpleTools');
$config->marketresearch->editor->createtask = array('id' => 'desc', 'tools' => 'simpleTools');
$config->marketresearch->editor->edittask   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->marketresearch->editor->closestage = array('id' => 'comment', 'tools' => 'simpleTools');

$config->marketresearch->custom = new stdclass();
$config->marketresearch->custom->createFields = 'PM,acl,realBegan,realEnd';
$config->marketresearch->customCreateFields   = $config->marketresearch->custom->createFields;

$config->marketresearch->actions = new stdclass();
$config->marketresearch->actions->view = array();
$config->marketresearch->actions->view['mainActions']   = array('start', 'activate', 'close');
$config->marketresearch->actions->view['suffixActions'] = array('edit', 'delete');
