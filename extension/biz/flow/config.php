<?php
$config->flow->batchCreateRow               = 10;
$config->flow->showBatchActionsInLinkedPage = false;

$config->flow->variables = array('today', 'now', 'actor', 'currentUser', 'currentDept', 'currentTime', 'deptManager');

$config->flow->defaultFields = new stdclass();
$config->flow->defaultFields->createdBy   = array();
$config->flow->defaultFields->createdDate = array();
$config->flow->defaultFields->editedBy    = array('demand' => 'lastEditedBy');
$config->flow->defaultFields->editedDate  = array('demand' => 'lastEditedDate');

$config->flow->linkPairs = array();

$config->flow->report = new stdclass();
$config->flow->report->width  = 350;
$config->flow->report->height = 70;

$config->flow->realModule = array();
