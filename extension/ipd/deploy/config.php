<?php
global $lang;
$config->deploy->create   = new stdclass();
$config->deploy->edit     = new stdclass();
$config->deploy->editstep = new stdclass();
$config->deploy->finish   = new stdclass();
$config->deploy->create->requiredFields   = 'name,owner,begin,end';
$config->deploy->edit->requiredFields     = 'name,owner,begin,end';
$config->deploy->editstep->requiredFields = 'title,begin,end';
$config->deploy->finish->requiredFields   = 'result';

$config->deploy->view = new stdclass();
$config->deploy->view->navs = array('steps', 'cases', 'view');

$config->deploy->editor = new stdclass();
$config->deploy->editor->create       = array('id' => 'desc', 'tools' => 'simpleTools');
$config->deploy->editor->edit         = array('id' => 'desc', 'tools' => 'simpleTools');
$config->deploy->editor->view         = array('id' => 'comment', 'tools' => 'simpleTools');
$config->deploy->editor->activate     = array('id' => 'comment', 'tools' => 'simpleTools');
$config->deploy->editor->activatestep = array('id' => 'comment', 'tools' => 'simpleTools');
$config->deploy->editor->finishstep   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->deploy->editor->assignto     = array('id' => 'comment', 'tools' => 'simpleTools');
$config->deploy->editor->finish       = array('id' => 'comment', 'tools' => 'simpleTools');

$config->deploy->actionList = array();
$config->deploy->actionList['edit'] = array();
$config->deploy->actionList['edit']['icon']        = 'edit';
$config->deploy->actionList['edit']['hint']        = $lang->deploy->edit;
$config->deploy->actionList['edit']['url']         = helper::createLink('deploy', 'edit', "deployID={id}");;
$config->deploy->actionList['edit']['data-toggle'] = 'modal';
$config->deploy->actionList['edit']['data-size']   = 'lg';

$config->deploy->actionList['publish'] = array();
$config->deploy->actionList['publish']['icon']         = 'publish';
$config->deploy->actionList['publish']['hint']         = $lang->deploy->publish;
$config->deploy->actionList['publish']['url']          = helper::createLink('deploy', 'publish', "deployID={id}");;
$config->deploy->actionList['publish']['data-confirm'] = $lang->deploy->confirmPublish;
$config->deploy->actionList['publish']['className']    = 'ajax-submit';

$config->deploy->actionList['delete'] = array();
$config->deploy->actionList['delete']['icon']         = 'trash';
$config->deploy->actionList['delete']['hint']         = $lang->deploy->delete;
$config->deploy->actionList['delete']['url']          = helper::createLink('deploy', 'delete', "deployID={id}");;
$config->deploy->actionList['delete']['data-confirm'] = $lang->deploy->confirmDelete;
$config->deploy->actionList['delete']['className']    = 'ajax-submit';

$config->deploy->actionList['finish'] = array();
$config->deploy->actionList['finish']['icon']        = 'checked';
$config->deploy->actionList['finish']['hint']        = $lang->deploy->finish;
$config->deploy->actionList['finish']['url']         = helper::createLink('deploy', 'finish', "deployID={id}");;
$config->deploy->actionList['finish']['data-toggle'] = 'modal';

$config->deploy->actionList['activate'] = array();
$config->deploy->actionList['activate']['icon']        = 'cancel';
$config->deploy->actionList['activate']['hint']        = $lang->deploy->activate;
$config->deploy->actionList['activate']['url']         = helper::createLink('deploy', 'activate', "deployID={id}");;
$config->deploy->actionList['activate']['data-toggle'] = 'modal';

$config->deploy->actionList['editStep'] = array();
$config->deploy->actionList['editStep']['icon']        = 'edit';
$config->deploy->actionList['editStep']['hint']        = $lang->deploy->edit;
$config->deploy->actionList['editStep']['url']         = helper::createLink('deploy', 'editStep', "stepID={id}");;
$config->deploy->actionList['editStep']['data-toggle'] = 'modal';

$config->deploy->actionList['deleteStep'] = array();
$config->deploy->actionList['deleteStep']['icon']         = 'trash';
$config->deploy->actionList['deleteStep']['hint']         = $lang->deploy->delete;
$config->deploy->actionList['deleteStep']['url']          = helper::createLink('deploy', 'deleteStep', "stepID={id}");;
$config->deploy->actionList['deleteStep']['data-confirm'] = $lang->deploy->confirmDeleteStep;

$config->deploy->actionList['finishStep'] = array();
$config->deploy->actionList['finishStep']['icon']        = 'checked';
$config->deploy->actionList['finishStep']['hint']        = $lang->deploy->finish;
$config->deploy->actionList['finishStep']['url']         = helper::createLink('deploy', 'finishStep', "stepID={id}");;
$config->deploy->actionList['finishStep']['data-toggle'] = 'modal';

$config->deploy->search['module'] = 'deploy';
$config->deploy->search['fields']['name']        = $lang->deploy->name;
$config->deploy->search['fields']['product']     = $lang->deploy->product;
$config->deploy->search['fields']['system']      = $lang->deploy->system;
$config->deploy->search['fields']['status']      = $lang->deploy->status;
$config->deploy->search['fields']['owner']       = $lang->deploy->owner;
$config->deploy->search['fields']['members']     = $lang->deploy->members;
$config->deploy->search['fields']['estimate']    = $lang->deploy->estimate;
$config->deploy->search['fields']['desc']        = $lang->deploy->desc;
$config->deploy->search['fields']['createdBy']   = $lang->deploy->createdBy;
$config->deploy->search['fields']['createdDate'] = $lang->deploy->createdDate;

$config->deploy->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->deploy->search['params']['product']     = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->deploy->search['params']['system']      = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->deploy->search['params']['status']      = array('operator' => '=',       'control' => 'select', 'values' => $lang->deploy->statusList);
$config->deploy->search['params']['owner']       = array('operator' => '=',       'control' => 'select', 'values' => array());
$config->deploy->search['params']['members']     = array('operator' => 'include', 'control' => 'select', 'values' => array());
$config->deploy->search['params']['estimate']    = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->deploy->search['params']['desc']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->deploy->search['params']['createdBy']   = array('operator' => '=',       'control' => 'select', 'values' => array());
$config->deploy->search['params']['createdDate'] = array('operator' => '=',       'control' => 'date',   'values' => '');

$config->deploy->actions = new stdclass();
$config->deploy->actions->view['mainActions']   = array('publish', 'finish', 'activate');
$config->deploy->actions->view['suffixActions'] = array('edit', 'delete');
