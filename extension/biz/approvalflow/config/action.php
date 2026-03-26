<?php
global $app, $lang;
$app->loadLang('testcase');

$config->approvalflow->actionList = array();
$config->approvalflow->actionList['editrole']['icon']        = 'edit';
$config->approvalflow->actionList['editrole']['text']        = $lang->approvalflow->editRole;
$config->approvalflow->actionList['editrole']['hint']        = $lang->approvalflow->editRole;
$config->approvalflow->actionList['editrole']['url']         = array('module' => 'approvalflow', 'method' => 'editrole', 'params' => 'id={id}');
$config->approvalflow->actionList['editrole']['data-toggle'] = 'modal';

$config->approvalflow->actionList['deleterole']['icon']         = 'trash';
$config->approvalflow->actionList['deleterole']['text']         = $lang->approvalflow->deleteRole;
$config->approvalflow->actionList['deleterole']['hint']         = $lang->approvalflow->deleteRole;
$config->approvalflow->actionList['deleterole']['url']          = array('module' => 'approvalflow', 'method' => 'deleterole', 'params' => 'id={id}');
$config->approvalflow->actionList['deleterole']['data-confirm'] = $lang->approvalflow->confirmDelete;
$config->approvalflow->actionList['deleterole']['data-submit']  = 'ajax';

$config->approvalflow->actionList['edit']['icon']        = 'edit';
$config->approvalflow->actionList['edit']['text']        = $lang->approvalflow->edit;
$config->approvalflow->actionList['edit']['hint']        = $lang->approvalflow->edit;
$config->approvalflow->actionList['edit']['url']         = array('module' => 'approvalflow', 'method' => 'edit', 'params' => 'id={id}');
$config->approvalflow->actionList['edit']['data-toggle'] = 'modal';

$config->approvalflow->actionList['design']['icon']        = 'treemap';
$config->approvalflow->actionList['design']['text']        = $lang->approvalflow->design;
$config->approvalflow->actionList['design']['hint']        = $lang->approvalflow->design;
$config->approvalflow->actionList['design']['url']         = array('module' => 'approvalflow', 'method' => 'design', 'params' => 'id={id}');

$config->approvalflow->actionList['delete']['icon']         = 'trash';
$config->approvalflow->actionList['delete']['text']         = $lang->approvalflow->delete;
$config->approvalflow->actionList['delete']['hint']         = $lang->approvalflow->delete;
$config->approvalflow->actionList['delete']['url']          = array('module' => 'approvalflow', 'method' => 'delete', 'params' => 'id={id}');
$config->approvalflow->actionList['delete']['data-confirm'] = $lang->approvalflow->confirmDelete;
$config->approvalflow->actionList['delete']['data-submit']  = 'ajax';
