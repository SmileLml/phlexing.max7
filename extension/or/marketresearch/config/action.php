<?php
global $app, $lang;
$app->loadLang('marketresearch');
$app->loadLang('researchtask');

$config->marketresearch->actionList['start']['icon']        = 'play';
$config->marketresearch->actionList['start']['hint']        = $lang->marketresearch->start;
$config->marketresearch->actionList['start']['url']         = helper::createLink('marketresearch', 'start', 'researchID={id}');
$config->marketresearch->actionList['start']['data-toggle'] = 'modal';

$config->marketresearch->actionList['close']['icon']        = 'off';
$config->marketresearch->actionList['close']['hint']        = $lang->marketresearch->close;
$config->marketresearch->actionList['close']['url']         = helper::createLink('marketresearch', 'close', 'researchID={id}');
$config->marketresearch->actionList['close']['data-toggle'] = 'modal';

$config->marketresearch->actionList['activate']['icon']        = 'magic';
$config->marketresearch->actionList['activate']['hint']        = $lang->marketresearch->activate;
$config->marketresearch->actionList['activate']['url']         = helper::createLink('marketresearch', 'activate', 'researchID={id}');
$config->marketresearch->actionList['activate']['data-toggle'] = 'modal';

$config->marketresearch->actionList['edit']['icon'] = 'edit';
$config->marketresearch->actionList['edit']['hint'] = $lang->marketresearch->edit;
$config->marketresearch->actionList['edit']['url']  = array('module' => 'marketresearch', 'method' => 'edit', 'params' => 'researchID={id}');

$config->marketresearch->actionList['group']['icon'] = 'group';
$config->marketresearch->actionList['group']['hint'] = $lang->marketresearch->team;
$config->marketresearch->actionList['group']['url']  = array('module' => 'marketresearch', 'method' => 'team', 'params' => 'researchID={id}');

$config->marketresearch->actionList['reports']['icon'] = 'list-alt';
$config->marketresearch->actionList['reports']['hint'] = $lang->marketresearch->reports;
$config->marketresearch->actionList['reports']['url']  = array('module' => 'marketresearch', 'method' => 'reports', 'params' => 'researchID={id}');

$config->marketresearch->actionList['delete']['icon'] = 'trash';
$config->marketresearch->actionList['delete']['hint'] = $lang->marketresearch->delete;
$config->marketresearch->actionList['delete']['url']  = 'javascript:confirmDelete("{id}", "{name}")';

$config->marketresearch->actionList['startStage']['icon']        = 'play';
$config->marketresearch->actionList['startStage']['hint']        = $lang->marketresearch->startStage;
$config->marketresearch->actionList['startStage']['url']         = helper::createLink('marketresearch', 'startStage', 'stageID={stageID}');
$config->marketresearch->actionList['startStage']['data-toggle'] = 'modal';

$config->marketresearch->actionList['createTask']['icon']     = 'plus';
$config->marketresearch->actionList['createTask']['hint']     = $lang->marketresearch->createTask;
$config->marketresearch->actionList['createTask']['url']      = array('module' => 'researchtask', 'method' => 'create', 'params' => 'researchID={project}&stageID={stageID}');
$config->marketresearch->actionList['createTask']['data-app'] = $app->tab;

$config->marketresearch->actionList['createStage']['icon'] = 'split';
$config->marketresearch->actionList['createStage']['hint'] = $lang->marketresearch->createStage;
$config->marketresearch->actionList['createStage']['url']  = array('module' => 'marketresearch', 'method' => 'createStage', 'params' => 'researchID={project}&stageID={stageID}');

$config->marketresearch->actionList['editStage']['icon']        = 'edit';
$config->marketresearch->actionList['editStage']['hint']        = $lang->marketresearch->editStage;
$config->marketresearch->actionList['editStage']['url']         = array('module' => 'marketresearch', 'method' => 'editStage', 'params' => 'stageID={stageID}&projectID={project}');
$config->marketresearch->actionList['editStage']['data-toggle'] = 'modal';

$config->marketresearch->actionList['closeStage']['icon']        = 'off';
$config->marketresearch->actionList['closeStage']['hint']        = $lang->marketresearch->closeStage;
$config->marketresearch->actionList['closeStage']['url']         = array('module' => 'marketresearch', 'method' => 'closeStage', 'params' => 'stageID={stageID}');
$config->marketresearch->actionList['closeStage']['data-toggle'] = 'modal';

$config->marketresearch->actionList['activateStage']['icon']        = 'magic';
$config->marketresearch->actionList['activateStage']['hint']        = $lang->marketresearch->activateStage;
$config->marketresearch->actionList['activateStage']['url']         = array('module' => 'marketresearch', 'method' => 'activateStage', 'params' => 'stageID={stageID}');
$config->marketresearch->actionList['activateStage']['data-toggle'] = 'modal';

$config->marketresearch->actionList['deleteStage']['icon']         = 'trash';
$config->marketresearch->actionList['deleteStage']['hint']         = $lang->marketresearch->deleteStage;
$config->marketresearch->actionList['deleteStage']['url']          = array('module' => 'marketresearch', 'method' => 'deleteStage', 'params' => 'stageID={stageID}');
$config->marketresearch->actionList['deleteStage']['data-confirm'] = array('message' => $lang->marketresearch->stageConfirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->marketresearch->actionList['deleteStage']['class']        = 'ajax-submit';
$config->marketresearch->actionList['deleteStage']['notInModal']   = true;

$config->marketresearch->actionList['startTask']['icon']        = 'play';
$config->marketresearch->actionList['startTask']['hint']        = $lang->researchtask->start;
$config->marketresearch->actionList['startTask']['url']         = array('module' => 'researchtask', 'method' => 'start', 'params' => 'taskID={id}');
$config->marketresearch->actionList['startTask']['data-toggle'] = 'modal';
$config->marketresearch->actionList['startTask']['class']       = 'researchtask-start-btn';

$config->marketresearch->actionList['finishTask']['icon']        = 'checked';
$config->marketresearch->actionList['finishTask']['hint']        = $lang->researchtask->finish;
$config->marketresearch->actionList['finishTask']['url']         = array('module' => 'researchtask', 'method' => 'finish', 'params' => 'taskID={id}');
$config->marketresearch->actionList['finishTask']['data-toggle'] = 'modal';
$config->marketresearch->actionList['finishTask']['class']       = 'researchtask-finish-btn';

$config->marketresearch->actionList['closeTask']['icon']        = 'off';
$config->marketresearch->actionList['closeTask']['hint']        = $lang->researchtask->close;
$config->marketresearch->actionList['closeTask']['url']         = array('module' => 'researchtask', 'method' => 'close', 'params' => 'taskID={id}');
$config->marketresearch->actionList['closeTask']['data-toggle'] = 'modal';
$config->marketresearch->actionList['closeTask']['class']       = 'researchtask-close-btn';

$config->marketresearch->actionList['recordWorkhour']['icon']        = 'time';
$config->marketresearch->actionList['recordWorkhour']['hint']        = $lang->researchtask->recordEstimate;
$config->marketresearch->actionList['recordWorkhour']['url']         = array('module' => 'researchtask', 'method' => 'recordWorkhour', 'params' => 'taskID={id}');
$config->marketresearch->actionList['recordWorkhour']['data-toggle'] = 'modal';
$config->marketresearch->actionList['recordWorkhour']['class']       = 'researchtask-recordWorkhour-btn';

$config->marketresearch->actionList['editTask']['icon'] = 'edit';
$config->marketresearch->actionList['editTask']['hint'] = $lang->researchtask->edit;
$config->marketresearch->actionList['editTask']['url']  = array('module' => 'researchtask', 'method' => 'edit', 'params' => 'taskID={id}');

$config->marketresearch->actionList['batchCreateTask']['icon']     = 'split';
$config->marketresearch->actionList['batchCreateTask']['hint']     = $lang->researchtask->batchCreate;
$config->marketresearch->actionList['batchCreateTask']['url']      = array('module' => 'researchtask', 'method' => 'batchCreate', 'params' => 'executionID={execution}&taskID={id}');
$config->marketresearch->actionList['batchCreateTask']['data-app'] = $app->tab;

$config->marketresearch->team = new stdclass();
$config->marketresearch->team->actionList['unlink']['icon'] = 'unlink';
$config->marketresearch->team->actionList['unlink']['hint'] = $lang->marketresearch->unlinkMember;
$config->marketresearch->team->actionList['unlink']['url']  = 'javascript:deleteMember("{root}", "{userID}")';

$config->marketresearch->actionMenu = new stdclass();
$config->marketresearch->actionMenu->task  = array('startTask', 'finishTask', 'closeTask', 'recordWorkhour', 'editTask', 'batchCreateTask');
$config->marketresearch->actionMenu->stage = array('startStage', 'createTask', 'createStage', 'editStage', 'closeStage|activateStage', 'deleteStage');
