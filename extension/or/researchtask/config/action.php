<?php
global $app, $lang;
$app->loadLang('researchtask');

$config->researchtask->actionList['batchCreate']['icon']     = 'split';
$config->researchtask->actionList['batchCreate']['hint']     = $lang->researchtask->children;
$config->researchtask->actionList['batchCreate']['text']     = $lang->researchtask->children;
$config->researchtask->actionList['batchCreate']['url']      = array('module' => 'researchtask', 'method' => 'batchCreate', 'params' => 'executionID={execution}&taskID={id}');
$config->researchtask->actionList['batchCreate']['data-app'] = $app->tab;

$config->researchtask->actionList['assignTo']['icon']        = 'hand-right';
$config->researchtask->actionList['assignTo']['hint']        = $lang->researchtask->assignTo;
$config->researchtask->actionList['assignTo']['text']        = $lang->researchtask->assignTo;
$config->researchtask->actionList['assignTo']['url']         = array('module' => 'researchtask', 'method' => 'assignTo', 'params' => 'executionID={execution}&taskID={id}');
$config->researchtask->actionList['assignTo']['data-toggle'] = 'modal';

$config->researchtask->actionList['start']['icon']        = 'play';
$config->researchtask->actionList['start']['hint']        = $lang->researchtask->start;
$config->researchtask->actionList['start']['text']        = $lang->researchtask->start;
$config->researchtask->actionList['start']['url']         = array('module' => 'researchtask', 'method' => 'start', 'params' => 'taskID={id}');
$config->researchtask->actionList['start']['data-toggle'] = 'modal';

$config->researchtask->actionList['finish']['icon']        = 'checked';
$config->researchtask->actionList['finish']['hint']        = $lang->researchtask->finish;
$config->researchtask->actionList['finish']['text']        = $lang->researchtask->finish;
$config->researchtask->actionList['finish']['url']         = array('module' => 'researchtask', 'method' => 'finish', 'params' => 'taskID={id}');
$config->researchtask->actionList['finish']['data-toggle'] = 'modal';

$config->researchtask->actionList['close']['icon']        = 'off';
$config->researchtask->actionList['close']['hint']        = $lang->researchtask->close;
$config->researchtask->actionList['close']['text']        = $lang->researchtask->close;
$config->researchtask->actionList['close']['url']         = array('module' => 'researchtask', 'method' => 'close', 'params' => 'taskID={id}');
$config->researchtask->actionList['close']['data-toggle'] = 'modal';

$config->researchtask->actionList['recordWorkhour']['icon']        = 'time';
$config->researchtask->actionList['recordWorkhour']['hint']        = $lang->researchtask->recordEstimate;
$config->researchtask->actionList['recordWorkhour']['text']        = $lang->researchtask->recordEstimate;
$config->researchtask->actionList['recordWorkhour']['url']         = array('module' => 'researchtask', 'method' => 'recordWorkhour', 'params' => 'taskID={id}');
$config->researchtask->actionList['recordWorkhour']['data-toggle'] = 'modal';

$config->researchtask->actionList['edit']['icon']     = 'edit';
$config->researchtask->actionList['edit']['hint']     = $lang->researchtask->edit;
$config->researchtask->actionList['edit']['text']     = $lang->researchtask->edit;
$config->researchtask->actionList['edit']['url']      = array('module' => 'researchtask', 'method' => 'edit', 'params' => 'taskID={id}');
$config->researchtask->actionList['edit']['data-app'] = $app->tab;

$config->researchtask->actionList['activate']['icon']        = 'magic';
$config->researchtask->actionList['activate']['hint']        = $lang->researchtask->activate;
$config->researchtask->actionList['activate']['text']        = $lang->researchtask->activate;
$config->researchtask->actionList['activate']['url']         = array('module' => 'researchtask', 'method' => 'activate', 'params' => 'taskID={id}');
$config->researchtask->actionList['activate']['data-toggle'] = 'modal';

$config->researchtask->actionList['cancel']['icon']        = 'ban-circle';
$config->researchtask->actionList['cancel']['hint']        = $lang->researchtask->cancel;
$config->researchtask->actionList['cancel']['text']        = $lang->researchtask->cancel;
$config->researchtask->actionList['cancel']['url']         = array('module' => 'researchtask', 'method' => 'cancel', 'params' => 'taskID={id}');
$config->researchtask->actionList['cancel']['data-toggle'] = 'modal';

$config->researchtask->actionList['create']['icon']     = 'copy';
$config->researchtask->actionList['create']['hint']     = $lang->researchtask->copy;
$config->researchtask->actionList['create']['text']     = $lang->researchtask->copy;
$config->researchtask->actionList['create']['url']      = array('module' => 'researchtask', 'method' => 'create', 'params' => 'projectID={project}&executionID={execution}&taskID={id}');
$config->researchtask->actionList['create']['data-app'] = $app->tab;

$config->researchtask->actionList['delete']['icon']         = 'trash';
$config->researchtask->actionList['delete']['hint']         = $lang->researchtask->delete;
$config->researchtask->actionList['delete']['text']         = $lang->researchtask->delete;
$config->researchtask->actionList['delete']['url']          = array('module' => 'researchtask', 'method' => 'delete', 'params' => 'executionID={execution}&taskID={id}');
$config->researchtask->actionList['delete']['data-confirm'] = array('message' => $lang->researchtask->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->researchtask->actionList['delete']['class']        = 'ajax-submit';

$config->researchtask->actions = new stdclass();
$config->researchtask->actions->view = array();
$config->researchtask->actions->view['mainActions']   = array('batchCreate', 'assignTo', 'start', 'finish', 'recordWorkhour', 'activate', 'close', 'cancel');
$config->researchtask->actions->view['suffixActions'] = array('edit', 'create', 'delete');
