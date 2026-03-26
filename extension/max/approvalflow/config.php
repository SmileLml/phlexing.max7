<?php
$config->approvalflow->create = new stdclass();
$config->approvalflow->edit   = new stdclass();

$config->approvalflow->create->requiredFields = 'name';
$config->approvalflow->edit->requiredFields   = 'name';

$config->approvalflow->editor          = new stdclass();
$config->approvalflow->editor->create  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->approvalflow->editor->edit    = array('id' => 'desc', 'tools' => 'simpleTools');

$config->approvalflow->actions = new stdclass();
$config->approvalflow->actions->view = array();
$config->approvalflow->actions->view['mainActions']   = array('design');
$config->approvalflow->actions->view['suffixActions'] = array('edit', 'delete');
