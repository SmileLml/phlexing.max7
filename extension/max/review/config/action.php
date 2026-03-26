<?php
global $lang, $app;
$app->loadLang('approval');
$config->review->actionList = array();
$config->review->actionList['submit']['icon']        = 'play';
$config->review->actionList['submit']['text']        = $lang->review->submit;
$config->review->actionList['submit']['hint']        = $lang->review->submit;
$config->review->actionList['submit']['url']         = array('module' => 'review', 'method' => 'submit', 'params' => 'reviewID={id}');
$config->review->actionList['submit']['data-toggle'] = 'modal';

$config->review->actionList['recall']['icon']        = 'back';
$config->review->actionList['recall']['text']        = $lang->review->recall;
$config->review->actionList['recall']['hint']        = $lang->review->recall;
$config->review->actionList['recall']['url']         = array('module' => 'review', 'method' => 'recall', 'params' => 'reviewID={id}');
$config->review->actionList['recall']['className']   = 'ajax-submit';

$config->review->actionList['assess']['icon'] = 'glasses';
$config->review->actionList['assess']['text'] = $lang->review->assess;
$config->review->actionList['assess']['hint'] = $lang->review->assess;
$config->review->actionList['assess']['url']  = array('module' => 'review', 'method' => 'assess', 'params' => 'reviewID={id}');

$config->review->actionList['progress']['icon']        = 'list-alt';
$config->review->actionList['progress']['text']        = $lang->approval->progress;
$config->review->actionList['progress']['hint']        = $lang->approval->progress;
$config->review->actionList['progress']['url']         = array('module' => 'approval', 'method' => 'progress', 'params' => 'approvalID={approval}');
$config->review->actionList['progress']['data-toggle'] = 'modal';

$config->review->actionList['report']['icon'] = 'bar-chart';
$config->review->actionList['report']['text'] = $lang->review->reviewReport;
$config->review->actionList['report']['hint'] = $lang->review->reviewReport;
$config->review->actionList['report']['url']  = array('module' => 'review', 'method' => 'report', 'params' => 'reviewID={id}');

$config->review->actionList['toAudit']['icon']        = 'hand-right';
$config->review->actionList['toAudit']['text']        = $lang->review->toAudit;
$config->review->actionList['toAudit']['hint']        = $lang->review->toAudit;
$config->review->actionList['toAudit']['url']         = array('module' => 'review', 'method' => 'toAudit', 'params' => 'reviewID={id}');
$config->review->actionList['toAudit']['data-toggle'] = 'modal';
$config->review->actionList['toAudit']['class']       = 'review-toaudit-btn';

$config->review->actionList['audit']['icon'] = 'search';
$config->review->actionList['audit']['text'] = $lang->review->audit;
$config->review->actionList['audit']['hint'] = $lang->review->audit;
$config->review->actionList['audit']['url']  = array('module' => 'review', 'method' => 'audit', 'params' => 'reviewID={id}');

$config->review->actionList['createBaseline']['icon']         = 'flag';
$config->review->actionList['createBaseline']['text']         = $lang->review->createBaseline;
$config->review->actionList['createBaseline']['hint']         = $lang->review->createBaseline;
$config->review->actionList['createBaseline']['url']          = array('module' => 'cm', 'method' => 'create', 'params' => 'project={project}&reviewID={id}');
$config->review->actionList['createBaseline']['notLoadModel'] = true;

$config->review->actionList['edit']['icon'] = 'edit';
$config->review->actionList['edit']['text'] = $lang->review->edit;
$config->review->actionList['edit']['hint'] = $lang->review->edit;
$config->review->actionList['edit']['url']  = array('module' => 'review', 'method' => 'edit', 'params' => 'reviewID={id}');

$config->review->actionList['delete']['icon']         = 'trash';
$config->review->actionList['delete']['text']         = $lang->review->delete;
$config->review->actionList['delete']['hint']         = $lang->review->delete;
$config->review->actionList['delete']['url']          = array('module' => 'review', 'method' => 'delete', 'params' => 'reviewID={id}');
$config->review->actionList['delete']['className']    = 'ajax-submit';
$config->review->actionList['delete']['data-confirm'] = array('message' => $lang->review->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
