<?php
global $app, $lang;
$app->loadLang('workflowdatasource');

$config->workflowdatasource->actionList = array();
$config->workflowdatasource->actionList['manage']['icon']     = 'split';
$config->workflowdatasource->actionList['manage']['text']     = $lang->workflowdatasource->category;
$config->workflowdatasource->actionList['manage']['hint']     = $lang->workflowdatasource->category;
$config->workflowdatasource->actionList['manage']['url']      = helper::createLink('tree', 'browse', 'rootID=0&viewType=datasource_{id}&currentModuleID=0&branch=&from=workflow');
$config->workflowdatasource->actionList['manage']['data-app'] = 'workflow';

$config->workflowdatasource->actionList['edit']['icon']        = 'edit';
$config->workflowdatasource->actionList['edit']['text']        = $lang->workflowdatasource->edit;
$config->workflowdatasource->actionList['edit']['hint']        = $lang->workflowdatasource->edit;
$config->workflowdatasource->actionList['edit']['url']         = array('module' => 'workflowdatasource', 'method' => 'edit', 'params' => 'id={id}');
$config->workflowdatasource->actionList['edit']['data-toggle'] = 'modal';

$config->workflowdatasource->actionList['delete']['icon']         = 'trash';
$config->workflowdatasource->actionList['delete']['text']         = $lang->workflowdatasource->delete;
$config->workflowdatasource->actionList['delete']['hint']         = $lang->workflowdatasource->delete;
$config->workflowdatasource->actionList['delete']['url']          = array('module' => 'workflowdatasource', 'method' => 'delete', 'params' => 'id={id}');
$config->workflowdatasource->actionList['delete']['data-confirm'] = array('message' => $lang->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->workflowdatasource->actionList['delete']['class']        = 'ajax-submit';
