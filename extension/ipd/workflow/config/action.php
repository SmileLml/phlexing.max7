<?php
global $app, $lang;
$app->loadLang('workflow');

$config->workflow->actionList = array();
$config->workflow->actionList['edit']['icon']        = 'edit';
$config->workflow->actionList['edit']['text']        = $lang->workflow->edit;
$config->workflow->actionList['edit']['hint']        = $lang->workflow->edit;
$config->workflow->actionList['edit']['url']         = array('module' => 'workflow', 'method' => 'edit', 'params' => 'id={id}');
$config->workflow->actionList['edit']['data-toggle'] = 'modal';
$config->workflow->actionList['edit']['data-size']   = 'sm';

$config->workflow->actionList['design']['icon'] = 'design';
$config->workflow->actionList['design']['text'] = $lang->workflow->design;
$config->workflow->actionList['design']['hint'] = $lang->workflow->design;
$config->workflow->actionList['design']['url']  = array('module' => 'workflow', 'method' => 'ui', 'params' => 'module={module}');

$config->workflow->actionList['field']['icon'] = 'design';
$config->workflow->actionList['field']['text'] = $lang->workflow->design;
$config->workflow->actionList['field']['hint'] = $lang->workflow->design;
$config->workflow->actionList['field']['url']  = array('module' => 'workflowfield', 'method' => 'browse', 'params' => 'module={module}');

$config->workflow->actionList['release']['icon']        = 'publish';
$config->workflow->actionList['release']['text']        = $lang->workflow->release;
$config->workflow->actionList['release']['hint']        = $lang->workflow->release;
$config->workflow->actionList['release']['url']         = array('module' => 'workflow', 'method' => 'release', 'params' => 'id={id}');
$config->workflow->actionList['release']['data-toggle'] = 'modal';
$config->workflow->actionList['release']['data-size']   = 'sm';

$config->workflow->actionList['deactivate']['icon']         = 'off';
$config->workflow->actionList['deactivate']['text']         = $lang->workflow->deactivate;
$config->workflow->actionList['deactivate']['hint']         = $lang->workflow->deactivate;
$config->workflow->actionList['deactivate']['url']          = array('module' => 'workflow', 'method' => 'deactivate', 'params' => 'id={id}');
$config->workflow->actionList['deactivate']['data-confirm'] = $lang->workflow->tips->syncDeactivate;
$config->workflow->actionList['deactivate']['class']        = 'ajax-submit';

$config->workflow->actionList['activate']['icon']  = 'play';
$config->workflow->actionList['activate']['text']  = $lang->workflow->activate;
$config->workflow->actionList['activate']['hint']  = $lang->workflow->activate;
$config->workflow->actionList['activate']['url']   = 'javascript:activate({id})';

$config->workflow->actionList['copy']['icon']        = 'copy';
$config->workflow->actionList['copy']['text']        = $lang->workflow->copy;
$config->workflow->actionList['copy']['hint']        = $lang->workflow->copy;
$config->workflow->actionList['copy']['url']         = array('module' => 'workflow', 'method' => 'copy', 'params' => 'id={id}');
$config->workflow->actionList['copy']['data-toggle'] = 'modal';
$config->workflow->actionList['copy']['data-size']   = 'sm';

$config->workflow->actionList['delete']['icon']         = 'trash';
$config->workflow->actionList['delete']['text']         = $lang->workflow->delete;
$config->workflow->actionList['delete']['hint']         = $lang->workflow->delete;
$config->workflow->actionList['delete']['url']          = array('module' => 'workflow', 'method' => 'delete', 'params' => 'id={id}');
$config->workflow->actionList['delete']['data-confirm'] = array('message' => array('html' => $lang->workflow->tips->deleteConfirm), 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x', 'size' => 'sm');
$config->workflow->actionList['delete']['class']        = 'ajax-submit';
