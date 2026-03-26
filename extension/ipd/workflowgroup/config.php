<?php
global $lang;
$config->workflowgroup = new stdclass();

$config->workflowgroup->actionList['design']['icon'] = 'design';
$config->workflowgroup->actionList['design']['hint'] = $lang->workflowgroup->design;
$config->workflowgroup->actionList['design']['text'] = $lang->workflowgroup->design;
$config->workflowgroup->actionList['design']['url']  = array('module' => 'workflowgroup', 'method' => 'design', 'params' => 'id={id}');

$config->workflowgroup->actionList['release']['icon'] = 'publish';
$config->workflowgroup->actionList['release']['hint'] = $lang->workflowgroup->release;
$config->workflowgroup->actionList['release']['text'] = $lang->workflowgroup->release;
$config->workflowgroup->actionList['release']['url']  = array('module' => 'workflowgroup', 'method' => 'release', 'params' => 'id={id}');

$config->workflowgroup->actionList['deactivate']['icon']         = 'pause';
$config->workflowgroup->actionList['deactivate']['hint']         = $lang->workflowgroup->deactivate;
$config->workflowgroup->actionList['deactivate']['text']         = $lang->workflowgroup->deactivate;
$config->workflowgroup->actionList['deactivate']['url']          = array('module' => 'workflowgroup', 'method' => 'deactivate', 'params' => 'id={id}');
$config->workflowgroup->actionList['deactivate']['data-confirm'] = $lang->workflowgroup->notice->confirmDeactivate;

$config->workflowgroup->actionList['edit']['icon']        = 'edit';
$config->workflowgroup->actionList['edit']['hint']        = $lang->workflowgroup->edit;
$config->workflowgroup->actionList['edit']['text']        = $lang->workflowgroup->edit;
$config->workflowgroup->actionList['edit']['url']         = array('module' => 'workflowgroup', 'method' => 'edit', 'params' => 'id={id}');
$config->workflowgroup->actionList['edit']['data-toggle'] = 'modal';

$config->workflowgroup->actionList['delete']['icon'] = 'trash';
$config->workflowgroup->actionList['delete']['hint'] = $lang->workflowgroup->delete;
$config->workflowgroup->actionList['delete']['text'] = $lang->workflowgroup->delete;
$config->workflowgroup->actionList['delete']['url']  = array('module' => 'workflowgroup', 'method' => 'delete', 'params' => 'id={id}');

$config->workflowgroup->actionList['setExclusive']['hint']         = $lang->workflowgroup->setExclusive;
$config->workflowgroup->actionList['setExclusive']['text']         = $lang->workflowgroup->setExclusive;
$config->workflowgroup->actionList['setExclusive']['url']          = array('module' => 'workflowgroup', 'method' => 'setExclusive', 'params' => 'flowID={id}&groupID=%s');
$config->workflowgroup->actionList['setExclusive']['data-confirm'] = $lang->workflowgroup->notice->confirmExclusive;

$config->workflowgroup->actionList['designBuildin']['icon'] = 'design';
$config->workflowgroup->actionList['designBuildin']['hint'] = $lang->workflowgroup->abbr->design;
$config->workflowgroup->actionList['designBuildin']['text'] = $lang->workflowgroup->abbr->design;
$config->workflowgroup->actionList['designBuildin']['url']  = array('module' => 'workflowfield', 'method' => 'browse', 'params' => 'module={module}&orderBy=order&groupID=%s');

$config->workflowgroup->actionList['designCustom']['icon'] = 'design';
$config->workflowgroup->actionList['designCustom']['hint'] = $lang->workflowgroup->abbr->design;
$config->workflowgroup->actionList['designCustom']['text'] = $lang->workflowgroup->abbr->design;
$config->workflowgroup->actionList['designCustom']['url']  = array('module' => 'workflow', 'method' => 'ui', 'params' => 'module={module}&method=create&groupID=%s');

$config->workflowgroup->actionList['activateFlow']['icon']      = 'start';
$config->workflowgroup->actionList['activateFlow']['hint']      = $lang->workflowgroup->activateFlow;
$config->workflowgroup->actionList['activateFlow']['text']      = $lang->workflowgroup->abbr->activate;
$config->workflowgroup->actionList['activateFlow']['url']       = array('module' => 'workflowgroup', 'method' => 'activateFlow', 'params' => 'module={module}&groupID=%s');
$config->workflowgroup->actionList['activateFlow']['className'] = 'ajax-submit';

$config->workflowgroup->actionList['deactivateFlow']['icon']      = 'pause';
$config->workflowgroup->actionList['deactivateFlow']['hint']      = $lang->workflowgroup->deactivateFlow;
$config->workflowgroup->actionList['deactivateFlow']['text']      = $lang->workflowgroup->abbr->deactivate;
$config->workflowgroup->actionList['deactivateFlow']['url']       = array('module' => 'workflowgroup', 'method' => 'deactivateFlow', 'params' => 'module={module}&groupID=%s');
$config->workflowgroup->actionList['deactivateFlow']['className'] = 'ajax-submit';

$config->workflowgroup->modules['product'] = array('product', 'epic', 'requirement', 'story', 'productplan', 'release', 'bug', 'testcase', 'testtask', 'feedback', 'ticket');
$config->workflowgroup->modules['project']['product'] = array('project', 'execution', 'task', 'build');
$config->workflowgroup->modules['project']['project'] = array('project', 'execution', 'task', 'epic', 'requirement', 'story', 'productplan', 'build', 'release', 'bug', 'testcase', 'testtask', 'feedback', 'ticket');
