<?php
$config->issue->create = new stdclass();
$config->issue->edit   = new stdclass();

$config->issue->create->requiredFields = 'title,type,severity';
$config->issue->edit->requiredFields   = 'title,type,severity';

$config->issue->actions = new stdclass();
$config->issue->actions->view = array();
$config->issue->actions->view['mainActions']   = array('confirm', 'resolve', 'assignTo', 'createForObject', 'cancel', 'close', 'activate', 'importToLib');
$config->issue->actions->view['suffixActions'] = array('edit', 'delete');

$config->issue->editor           = new stdclass();
$config->issue->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->issue->editor->create   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->issue->editor->edit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->issue->editor->confirm  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->issue->editor->cancel   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->issue->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->issue->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');
$config->issue->editor->resolve  = array('id' => 'spec,verify,steps,desc,resolutionComment', 'tools' => 'simpleTools');

$config->issue->list = new stdclass();
$config->issue->list->exportFields = 'id,type,title,severity,execution,pri,desc,status,
    owner,assignedTo,createdBy,createdDate,deadline,
    resolvedDate,resolvedBy,resolution,resolutionComment,
    activateBy,activateDate,closedBy,closedDate,files';

global $lang, $app;

$config->issue->search['module'] = 'issue';

$config->issue->search['fields']['title']        = $lang->issue->title;
$config->issue->search['fields']['id']           = $lang->issue->id;
if($app->rawModule == 'my') $config->issue->search['fields']['project'] = $lang->my->projects;
$config->issue->search['fields']['pri']          = $lang->issue->pri;
$config->issue->search['fields']['status']       = $lang->issue->status;
$config->issue->search['fields']['severity']     = $lang->issue->severity;
$config->issue->search['fields']['execution']    = $lang->issue->execution;
$config->issue->search['fields']['type']         = $lang->issue->type;
$config->issue->search['fields']['createdBy']    = $lang->issue->createdBy;
$config->issue->search['fields']['createdDate']  = $lang->issue->createdDate;
$config->issue->search['fields']['closedBy']     = $lang->issue->closedBy;
$config->issue->search['fields']['closedDate']   = $lang->issue->closedDate;
$config->issue->search['fields']['assignedTo']   = $lang->issue->assignedTo;
$config->issue->search['fields']['assignedDate'] = $lang->issue->assignedDate;
$config->issue->search['fields']['editedBy']     = $lang->issue->editedBy;
$config->issue->search['fields']['editedDate']   = $lang->issue->editedDate;

$config->issue->search['params']['title']        = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->issue->search['params']['id']           = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->issue->search['params']['pri']          = array('operator' => '=', 'control' => 'select', 'values' => $lang->issue->priList);
$config->issue->search['params']['status']       = array('operator' => '=', 'control' => 'select', 'values' => arrayUnion(array('' => ''), $lang->issue->statusList));
$config->issue->search['params']['severity']     = array('operator' => '=', 'control' => 'select', 'values' => $lang->issue->severityList);
$config->issue->search['params']['execution']    = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->issue->search['params']['type']         = array('operator' => '=', 'control' => 'select', 'values' => $lang->issue->typeList);
$config->issue->search['params']['createdBy']    = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->issue->search['params']['createdDate']  = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->issue->search['params']['closedBy']     = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->issue->search['params']['closedDate']   = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->issue->search['params']['assignedTo']   = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->issue->search['params']['assignedDate'] = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->issue->search['params']['editedBy']     = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->issue->search['params']['editedDate']   = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');

$config->issue->actionList['confirm']['icon']        = 'ok';
$config->issue->actionList['confirm']['hint']        = $lang->issue->confirm;
$config->issue->actionList['confirm']['text']        = $lang->issue->confirm;
$config->issue->actionList['confirm']['url']         = helper::createLink('issue', 'confirm', 'issueID={id}');
$config->issue->actionList['confirm']['data-toggle'] = 'modal';
$config->issue->actionList['confirm']['class']       = 'issue-confirm-btn';

$config->issue->actionList['resolve']['icon']        = 'checked';
$config->issue->actionList['resolve']['hint']        = $lang->issue->resolve;
$config->issue->actionList['resolve']['text']        = $lang->issue->resolve;
$config->issue->actionList['resolve']['url']         = helper::createLink('issue', 'resolve', 'issueID={id}');
$config->issue->actionList['resolve']['data-toggle'] = 'modal';
$config->issue->actionList['resolve']['class']       = 'issue-resolve-btn';

$config->issue->actionList['close']['icon']        = 'off';
$config->issue->actionList['close']['hint']        = $lang->issue->close;
$config->issue->actionList['close']['text']        = $lang->issue->close;
$config->issue->actionList['close']['url']         = helper::createLink('issue', 'close', 'issueID={id}');
$config->issue->actionList['close']['data-toggle'] = 'modal';
$config->issue->actionList['close']['class']       = 'issue-close-btn';

$config->issue->actionList['cancel']['icon']        = 'cancel';
$config->issue->actionList['cancel']['hint']        = $lang->issue->cancel;
$config->issue->actionList['cancel']['text']        = $lang->issue->cancel;
$config->issue->actionList['cancel']['url']         = helper::createLink('issue', 'cancel', 'issueID={id}');
$config->issue->actionList['cancel']['data-toggle'] = 'modal';

$config->issue->actionList['activate']['icon']        = 'magic';
$config->issue->actionList['activate']['hint']        = $lang->issue->activate;
$config->issue->actionList['activate']['text']        = $lang->issue->activate;
$config->issue->actionList['activate']['url']         = helper::createLink('issue', 'activate', 'issueID={id}');
$config->issue->actionList['activate']['data-toggle'] = 'modal';

$config->issue->actionList['createForObject']['icon']        = 'time';
$config->issue->actionList['createForObject']['hint']        = $lang->issue->effort;
$config->issue->actionList['createForObject']['text']        = $lang->issue->effort;
$config->issue->actionList['createForObject']['url']         = helper::createLink('effort', 'createForObject', 'objectType=issue&objectID={id}');
$config->issue->actionList['createForObject']['data-toggle'] = 'modal';

$config->issue->actionList['edit']['icon'] = 'edit';
$config->issue->actionList['edit']['hint'] = $lang->issue->edit;
$config->issue->actionList['edit']['text'] = $lang->issue->edit;
$config->issue->actionList['edit']['url']  = helper::createLink('issue', 'edit', 'issueID={id}&from={from}');

$config->issue->actionList['assignTo']['icon']        = 'hand-right';
$config->issue->actionList['assignTo']['hint']        = $lang->issue->assignTo;
$config->issue->actionList['assignTo']['text']        = $lang->issue->assignTo;
$config->issue->actionList['assignTo']['url']         = helper::createLink('issue', 'assignTo', 'issueID={id}');
$config->issue->actionList['assignTo']['data-toggle'] = 'modal';

$config->issue->actionList['createForObject']['icon']        = 'time';
$config->issue->actionList['createForObject']['text']        = $lang->issue->effort;
$config->issue->actionList['createForObject']['hint']        = $lang->issue->effort;
$config->issue->actionList['createForObject']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'objectType=issue&objectID={id}');
$config->issue->actionList['createForObject']['data-toggle'] = 'modal';

$config->issue->actionList['delete']['icon']         = 'trash';
$config->issue->actionList['delete']['hint']         = $lang->issue->delete;
$config->issue->actionList['delete']['text']         = $lang->issue->delete;
$config->issue->actionList['delete']['url']          = helper::createLink('issue', 'delete', 'issueID={id}');
$config->issue->actionList['delete']['data-confirm'] = array('message' => $lang->issue->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->issue->actionList['delete']['class']        = 'ajax-submit';

$config->issue->actionList['importToLib']['icon']        = 'assets';
$config->issue->actionList['importToLib']['hint']        = $lang->issue->importToLib;
$config->issue->actionList['importToLib']['text']        = $lang->issue->importToLib;
$config->issue->actionList['importToLib']['data-target'] = '#importToLib';
$config->issue->actionList['importToLib']['data-toggle'] = 'modal';
$config->issue->actionList['importToLib']['data-size']   = 'sm';
