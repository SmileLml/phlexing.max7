<?php
$config->excel = new stdclass();
$config->excel->editor['task']        = array('desc');
$config->excel->editor['story']       = array('spec', 'verify');
$config->excel->editor['epic']        = array('spec', 'verify');
$config->excel->editor['requirement'] = array('spec', 'verify');
$config->excel->editor['bug']         = array('steps');
$config->excel->editor['project']     = array('desc');
$config->excel->editor['todo']        = array('desc');

$config->excel->width          = new stdclass();
$config->excel->width->short   = 16;
$config->excel->width->middle  = 26;
$config->excel->width->long    = 41;

$config->excel->autoWidths   = array('stepDesc', 'stepExpect', 'plan', 'branch', 'source', 'sourceNote', 'pri', 'estimate', 'consumed', 'left', 'status', 'stage', 'taskCountAB', 'bugCountAB', 'caseCountAB', 'openedBy', 'openedDate', 'assignedTo', 'assignedDate', 'mailto', 'reviewedBy', 'reviewedDate', 'closedBy', 'closedDate', 'closedReason', 'lastEditedBy', 'lastEditedDate', 'childStories', 'linkStories', 'duplicateStory', 'deadline','pri');
$config->excel->titleFields  = array('name', 'title', 'product', 'module', 'project', 'execution', 'story', 'plan', 'startTime');
$config->excel->shortFields  = array('keywords');
$config->excel->centerFields = array();
$config->excel->dateFields   = array();

$config->excel->sysDataField = array('module', 'project', 'execution', 'plan', 'build', 'branch', 'assignedTo', 'story', 'task', 'openedBuild', 'reviewedBy', 'needReview', 'product', 'notify', 'public', 'mailto');

$config->excel->dateField  = array('deadline', 'estStarted', 'realStarted');

$config->excel->noAutoFilter    = array('tree');
$config->excel->isShowSystemTab = array('tree');

$config->excel->requiredFieldModule = array('task', 'bug', 'testcase', 'story', 'epic', 'requirement', 'feedback', 'demand');

$filter->testcase->ajaxselectstory = new stdclass();
$filter->testcase->ajaxselectstory->cookie['selectedStories'] = 'array';

$config->logonMethods[] = 'programplan.showimport';
