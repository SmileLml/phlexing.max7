<?php
$config->roadmap = new stdclass();
$config->roadmap->editor = new stdclass();
$config->roadmap->editor->create   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->roadmap->editor->edit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->roadmap->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->roadmap->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->roadmap->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->roadmap->editor->unlinkur = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

$config->roadmap->create   = new stdclass();
$config->roadmap->edit     = new stdclass();
$config->roadmap->close    = new stdclass();
$config->roadmap->unlinkur = new stdclass();

$config->roadmap->create->requiredFields   = 'name,begin,end';
$config->roadmap->edit->requiredFields     = 'name,begin,end';
$config->roadmap->close->requiredFields    = 'closedReason';
$config->roadmap->unlinkur->requiredFields = 'unlinkReason';

$config->roadmap->future = '2030-01-01';

$config->roadmap->defaultFields = array();
$config->roadmap->defaultFields['story']  = array('id', 'title', 'pri', 'branch', 'module', 'status', 'openedBy', 'estimate', 'stage', 'assignedTo', 'actions');
$config->roadmap->defaultFields['linkUR'] = array('id', 'pri', 'module', 'title', 'openedBy', 'assignedTo', 'estimate', 'status', 'stage');

global $lang;
$config->roadmap->actionList = array();
$config->roadmap->actionList['unlinkUR']['icon']        = 'unlink';
$config->roadmap->actionList['unlinkUR']['hint']        = $lang->roadmap->unlinkUR;
$config->roadmap->actionList['unlinkUR']['url']         = array('module' => 'roadmap', 'method' => 'unlinkUR', 'params' => "story={story}&roadmap={roadmap}");
$config->roadmap->actionList['unlinkUR']['data-toggle'] = 'modal';

$config->roadmap->actionList['edit']['icon'] = 'edit';
$config->roadmap->actionList['edit']['hint'] = $lang->edit;
$config->roadmap->actionList['edit']['text'] = $lang->edit;
$config->roadmap->actionList['edit']['url']  = array('module' => 'roadmap', 'method' => 'edit', 'params' => 'roadmapID={roadmapID}');

$config->roadmap->actionList['close']['icon']        = 'off';
$config->roadmap->actionList['close']['hint']        = $lang->close;
$config->roadmap->actionList['close']['text']        = $lang->close;
$config->roadmap->actionList['close']['url']         = array('module' => 'roadmap', 'method' => 'close', 'params' => 'roadmapID={roadmapID}', 'onlybody' => true);
$config->roadmap->actionList['close']['data-toggle'] = 'modal';
$config->roadmap->actionList['close']['data-type']   = 'iframe';

$config->roadmap->actionList['activate']['icon']         = 'magic';
$config->roadmap->actionList['activate']['hint']         = $lang->activate;
$config->roadmap->actionList['activate']['text']         = $lang->activate;
$config->roadmap->actionList['activate']['url']          = array('module' => 'roadmap', 'method' => 'activate', 'params' => 'roadmapID={roadmapID}');
$config->roadmap->actionList['activate']['data-confirm'] = $lang->roadmap->confirmActivate;
$config->roadmap->actionList['activate']['className']    = 'ajax-submit';

$config->roadmap->actionList['delete']['icon']         = 'trash';
$config->roadmap->actionList['delete']['hint']         = $lang->delete;
$config->roadmap->actionList['delete']['text']         = $lang->delete;
$config->roadmap->actionList['delete']['url']          = array('module' => 'roadmap', 'method' => 'delete', 'params' => 'roadmapID={roadmapID}');
$config->roadmap->actionList['delete']['data-confirm'] = $lang->roadmap->confirmDelete;
$config->roadmap->actionList['delete']['className']    = 'ajax-submit';

$config->roadmap->actions = new stdclass();
$config->roadmap->actions->view = array();
$config->roadmap->actions->view['mainActions']   = array('close', 'activate');
$config->roadmap->actions->view['suffixActions'] = array('edit', 'delete');
