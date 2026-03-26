<?php
$config->workflow->virtualParams = ',currentUser,deptManager,actor,today,now,';

$config->workflow->buildin = new stdclass();
$config->workflow->buildin->modules = new stdclass();
$config->workflow->buildin->modules->product = new stdclass();
$config->workflow->buildin->modules->product->product     = array('table' => TABLE_PRODUCT,     'navigator' => 'primary');
$config->workflow->buildin->modules->product->productplan = array('table' => TABLE_PRODUCTPLAN, 'navigator' => 'secondary');
$config->workflow->buildin->modules->product->release     = array('table' => TABLE_RELEASE,     'navigator' => 'secondary');
$config->workflow->buildin->modules->product->story       = array('table' => TABLE_STORY,       'navigator' => 'secondary');
$config->workflow->buildin->modules->product->requirement = array('table' => TABLE_STORY,       'navigator' => 'secondary');
$config->workflow->buildin->modules->product->epic        = array('table' => TABLE_STORY,       'navigator' => 'secondary');

$config->workflow->buildin->modules->program = new stdclass();
$config->workflow->buildin->modules->program->program = array('table' => TABLE_PROJECT, 'navigator' => 'primary');
$config->workflow->buildin->modules->program->charter = array('table' => TABLE_CHARTER, 'navigator' => 'secondary');

$config->workflow->buildin->modules->project = new stdclass();
$config->workflow->buildin->modules->project->project = array('table' => TABLE_PROJECT, 'navigator' => 'primary');

$config->workflow->buildin->modules->execution = new stdclass();
$config->workflow->buildin->modules->execution->execution = array('table' => TABLE_PROJECT, 'navigator' => 'primary');
$config->workflow->buildin->modules->execution->build     = array('table' => TABLE_BUILD,   'navigator' => 'secondary');
$config->workflow->buildin->modules->execution->task      = array('table' => TABLE_TASK,    'navigator' => 'secondary');

$config->workflow->buildin->modules->qa = new stdclass();
$config->workflow->buildin->modules->qa->bug       = array('table' => TABLE_BUG,       'navigator' => 'secondary');
$config->workflow->buildin->modules->qa->testcase  = array('table' => TABLE_CASE,      'navigator' => 'secondary');
$config->workflow->buildin->modules->qa->testtask  = array('table' => TABLE_TESTTASK,  'navigator' => 'secondary');
$config->workflow->buildin->modules->qa->testsuite = array('table' => TABLE_TESTSUITE, 'navigator' => 'secondary');
$config->workflow->buildin->modules->qa->caselib   = array('table' => TABLE_TESTSUITE, 'navigator' => 'secondary');

$config->workflow->buildin->modules->feedback = new stdclass();
$config->workflow->buildin->modules->feedback->feedback = array('table' => TABLE_FEEDBACK, 'navigator' => 'primary');
$config->workflow->buildin->modules->feedback->ticket   = array('table' => TABLE_TICKET, 'navigator' => 'secondary');

$config->workflow->buildin->modules->demandpool = new stdclass();
$config->workflow->buildin->modules->demandpool->demand = array('table' => TABLE_DEMAND, 'navigator' => 'secondary');

$config->workflow->buildin->subStatus = new stdclass();
$config->workflow->buildin->subStatus->modules = array('product', 'release', 'story', 'requirement', 'epic', 'project', 'task', 'bug', 'testcase', 'testtask', 'feedback', 'ticket');

$config->workflow->buildin->noApproval = array('feedback', 'testcase', 'story', 'requirement', 'epic', 'program', 'demand');

$config->workflow->buildin->createdBy = array();
$config->workflow->buildin->createdBy['story']       = 'openedBy';
$config->workflow->buildin->createdBy['requirement'] = 'openedBy';
$config->workflow->buildin->createdBy['epic']        = 'openedBy';
$config->workflow->buildin->createdBy['story']       = 'openedBy';
$config->workflow->buildin->createdBy['project']     = 'openedBy';
$config->workflow->buildin->createdBy['program']     = 'openedBy';
$config->workflow->buildin->createdBy['execution']   = 'openedBy';
$config->workflow->buildin->createdBy['build']       = 'builder';
$config->workflow->buildin->createdBy['task']        = 'openedBy';
$config->workflow->buildin->createdBy['bug']         = 'openedBy';
$config->workflow->buildin->createdBy['testcase']    = 'openedBy';
$config->workflow->buildin->createdBy['testsuite']   = 'addedBy';
$config->workflow->buildin->createdBy['caselib']     = 'addedBy';
$config->workflow->buildin->createdBy['feedback']    = 'openedBy';
$config->workflow->buildin->createdBy['ticket']      = 'openedBy';
$config->workflow->buildin->createdBy['demand']      = 'createdBy';
$config->workflow->buildin->createdBy['charter']     = 'createdBy';

$config->workflow->buildin->visions = array('demand' => 'or');

$config->workflow->buildin->liteModules = array('project', 'task', 'feedback', 'ticket');
