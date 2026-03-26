<?php
$config->workflowlayout->buildin = new stdclass();
$config->workflowlayout->buildin->layouts = new stdclass();
$config->workflowlayout->buildin->layouts->product = new stdclass();
$config->workflowlayout->buildin->layouts->product->browse['id']      = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->product->browse['name']    = array('width' => 200,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->product->browse['line']    = array('width' => 110,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->product->browse['type']    = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->product->browse['status']  = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->product->browse['desc']    = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->product->browse['PO']      = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->product->browse['QD']      = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->product->browse['RD']      = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->product->browse['actions'] = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->story = new stdclass();
$config->workflowlayout->buildin->layouts->story->browse['id']         = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->story->browse['pri']        = array('width' => 50,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->story->browse['title']      = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->story->browse['plan']       = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->story->browse['openedBy']   = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->story->browse['assignedTo'] = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->story->browse['estimate']   = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->story->browse['status']     = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->story->browse['stage']      = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->story->browse['actions']    = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->requirement = $config->workflowlayout->buildin->layouts->story;
$config->workflowlayout->buildin->layouts->epic        = $config->workflowlayout->buildin->layouts->story;

$config->workflowlayout->buildin->layouts->productplan = new stdclass();
$config->workflowlayout->buildin->layouts->productplan->browse['id']      = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->productplan->browse['product'] = array('width' => 160,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->productplan->browse['branch']  = array('width' => 160,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->productplan->browse['title']   = array('width' => 160,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->productplan->browse['begin']   = array('width' => 120,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->productplan->browse['end']     = array('width' => 120,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->productplan->browse['desc']    = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->productplan->browse['actions'] = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->release = new stdclass();
$config->workflowlayout->buildin->layouts->release->browse['id']      = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->release->browse['name']    = array('width' => 200,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->release->browse['build']   = array('width' => 200,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->release->browse['date']    = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->release->browse['status']  = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->release->browse['desc']    = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->release->browse['actions'] = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->project = new stdclass();
$config->workflowlayout->buildin->layouts->project->browse['id']      = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->project->browse['name']    = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->project->browse['code']    = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->project->browse['PM']      = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->project->browse['end']     = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->project->browse['status']  = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->project->browse['actions'] = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->task = new stdclass();
$config->workflowlayout->buildin->layouts->task->browse['id']         = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['pri']        = array('width' => 50,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['name']       = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['status']     = array('width' => 120,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['assignedTo'] = array('width' => 120,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['finishedBy'] = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['estimate']   = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['consumed']   = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['left']       = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['deadline']   = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->task->browse['actions']    = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->build = new stdclass();
$config->workflowlayout->buildin->layouts->build->browse['product']  = array('width' => 200,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->build->browse['id']       = array('width' => 70,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->build->browse['name']     = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->build->browse['scmPath']  = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->build->browse['filePath'] = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->build->browse['date']     = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->build->browse['builder']  = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->build->browse['actions']  = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->bug = new stdclass();
$config->workflowlayout->buildin->layouts->bug->browse['id']         = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->bug->browse['severity']   = array('width' => 60,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->bug->browse['pri']        = array('width' => 50,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->bug->browse['title']      = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->bug->browse['status']     = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->bug->browse['openedBy']   = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->bug->browse['openedDate'] = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->bug->browse['assignedTo'] = array('width' => 120,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->bug->browse['resolution'] = array('width' => 70,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->bug->browse['actions']    = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->testcase = new stdclass();
$config->workflowlayout->buildin->layouts->testcase->browse['id']            = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testcase->browse['pri']           = array('width' => 40,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testcase->browse['title']         = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testcase->browse['type']          = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testcase->browse['openedBy']      = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testcase->browse['lastRunner']    = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testcase->browse['lastRunDate']   = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testcase->browse['lastRunResult'] = array('width' => 70,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testcase->browse['status']        = array('width' => 70,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testcase->browse['actions']       = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->testtask = new stdclass();
$config->workflowlayout->buildin->layouts->testtask->browse['id']      = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testtask->browse['name']    = array('width' => 200,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testtask->browse['product'] = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testtask->browse['project'] = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testtask->browse['build']   = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testtask->browse['owner']   = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testtask->browse['begin']   = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testtask->browse['end']     = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testtask->browse['status']  = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testtask->browse['actions'] = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->testsuite = new stdclass();
$config->workflowlayout->buildin->layouts->testsuite->browse['id']        = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testsuite->browse['name']      = array('width' => 200,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testsuite->browse['desc']      = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testsuite->browse['addedBy']   = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testsuite->browse['addedDate'] = array('width' => 150,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->testsuite->browse['actions']   = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->caselib = new stdclass();
$config->workflowlayout->buildin->layouts->caselib->browse['id']        = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->caselib->browse['name']      = array('width' => 200,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->caselib->browse['desc']      = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->caselib->browse['addedBy']   = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->caselib->browse['addedDate'] = array('width' => 150,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->caselib->browse['actions']   = array('width' => 130,    'mobileShow' => 0);

$config->workflowlayout->buildin->layouts->feedback = new stdclass();
$config->workflowlayout->buildin->layouts->feedback->browse['id']         = array('width' => 90,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->feedback->browse['product']    = array('width' => 120,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->feedback->browse['title']      = array('width' => 'auto', 'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->feedback->browse['status']     = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->feedback->browse['openedBy']   = array('width' => 80,     'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->feedback->browse['openedDate'] = array('width' => 100,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->feedback->browse['assignedTo'] = array('width' => 120,    'mobileShow' => 0);
$config->workflowlayout->buildin->layouts->feedback->browse['actions']    = array('width' => 130,    'mobileShow' => 0);
