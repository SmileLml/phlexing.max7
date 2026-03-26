<?php
$config->market = new stdclass();
$config->market->create = new stdclass();
$config->market->edit   = new stdclass();

$config->market->create->requiredFields = 'name';
$config->market->edit->requiredFields   = $config->market->create->requiredFields;

$config->market->editor = new stdclass();
$config->market->editor->create    = array('id' => 'desc', 'tools' => 'simpleTools');
$config->market->editor->edit      = array('id' => 'desc', 'tools' => 'simpleTools');
$config->market->editor->view      = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
