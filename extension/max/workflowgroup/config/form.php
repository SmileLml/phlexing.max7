<?php
$config->workflowgroup->form = new stdClass();
$config->workflowgroup->form->create = [];
$config->workflowgroup->form->create['name']         = ['type' => 'string', 'default' => ''];
$config->workflowgroup->form->create['projectModel'] = ['type' => 'string', 'default' => ''];
$config->workflowgroup->form->create['projectType']  = ['type' => 'string', 'default' => ''];
$config->workflowgroup->form->create['desc']         = ['type' => 'string', 'default' => ''];

$config->workflowgroup->form->edit = [];
$config->workflowgroup->form->edit['name'] = ['type' => 'string', 'default' => ''];
$config->workflowgroup->form->edit['desc'] = ['type' => 'string', 'default' => ''];
