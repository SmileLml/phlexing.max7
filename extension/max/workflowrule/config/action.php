<?php
global $app, $lang;
$app->loadLang('workflowrule');

$config->workflowrule->actionList = array();
$config->workflowrule->actionList['edit']['icon']        = 'edit';
$config->workflowrule->actionList['edit']['text']        = $lang->workflowrule->edit;
$config->workflowrule->actionList['edit']['hint']        = $lang->workflowrule->edit;
$config->workflowrule->actionList['edit']['url']         = array('module' => 'workflowrule', 'method' => 'edit', 'params' => 'id={id}');
$config->workflowrule->actionList['edit']['data-toggle'] = 'modal';

$config->workflowrule->actionList['delete']['icon']         = 'trash';
$config->workflowrule->actionList['delete']['text']         = $lang->workflowrule->delete;
$config->workflowrule->actionList['delete']['hint']         = $lang->workflowrule->delete;
$config->workflowrule->actionList['delete']['url']          = array('module' => 'workflowrule', 'method' => 'delete', 'params' => 'id={id}');
$config->workflowrule->actionList['delete']['data-confirm'] = array('message' => $lang->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->workflowrule->actionList['delete']['class']        = 'ajax-submit';
