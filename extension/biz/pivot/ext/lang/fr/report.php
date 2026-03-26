<?php
$lang->pivot->testcase          = 'Case Report';
$lang->pivot->casesrun          = 'Case Run Report';
$lang->pivot->build             = 'Build Report';
$lang->pivot->workSummary       = 'Task Finished Summary';
$lang->pivot->bugSummary        = 'Bug Solution Summary';
$lang->pivot->roadmap           = $lang->productCommon . ' Roadmap';
$lang->pivot->storyLinkedBug    = 'Story Linked Bugs Summary';
$lang->pivot->workAssignSummary = 'Task Assignment Summary';
$lang->pivot->bugAssignSummary  = 'Bug Assignment Summary';
$lang->pivot->productInvest     = $lang->productCommon . ' Investment';

$lang->pivot->taskFinishedDate  = 'Task Finished Date';
$lang->pivot->taskConsumed      = 'Task Total Cost';
$lang->pivot->projectConsumed   = $lang->projectCommon . 'Total Cost';
$lang->pivot->executionConsumed = 'Execution Total Cost';
$lang->pivot->userConsumed      = 'User Total Cost';
$lang->pivot->bugResolvedDate   = 'Bug Resolved Date';
$lang->pivot->bugAssignedDate   = 'Bug Assigned Date';
$lang->pivot->taskAssignedDate  = 'Task Assigned Date';
$lang->pivot->projects          = $lang->projectCommon;
$lang->pivot->storyConsumed     = $lang->SRCommon . ' Consumed';
$lang->pivot->taskConsumed      = 'Task Consumed';
$lang->pivot->bugConsumed       = 'Bug Consumed';
$lang->pivot->caseConsumed      = 'Case Consumed';
$lang->pivot->totalConsumed     = 'Total Consumed';

if(helper::hasFeature('product_roadmap')) $lang->pivotList->product->lists[20] = $lang->productCommon . ' Roadmap|pivot|roadmap';
$lang->pivotList->product->lists[25] = $lang->productCommon . ' Investment|pivot|productInvest';
$lang->pivotList->test->lists[20]    = 'Case Report|pivot|testcase';
$lang->pivotList->test->lists[25]    = 'Case Run Report|pivot|casesrun';
$lang->pivotList->test->lists[30]    = 'Build Report|pivot|build';
$lang->pivotList->test->lists[35]    = 'Story Linked Bugs|pivot|storylinkedbug';
$lang->pivotList->staff->lists[20]   = 'Task Finished Summary|pivot|worksummary';
$lang->pivotList->staff->lists[25]   = 'Task Assignment Summary|pivot|workAssignSummary';
$lang->pivotList->staff->lists[30]   = 'Bug Solution Summary|pivot|bugsummary';
$lang->pivotList->staff->lists[35]   = 'Bug Assignment Summary|pivot|bugAssignSummary';

$lang->pivot->product    = $lang->productCommon . ' Name';
$lang->pivot->moduleName = 'Module';
$lang->pivot->buildTitle = 'Build';
$lang->pivot->severity   = 'Severity';
$lang->pivot->bugType    = 'Type';
$lang->pivot->bugStatus  = 'Status';
$lang->pivot->delay      = 'Delay';
$lang->pivot->day        = 'day';
$lang->pivot->plan       = 'Plan';
$lang->pivot->future     = 'TBD';

$lang->pivot->case           = new stdclass();
$lang->pivot->case->total    = 'Total';
$lang->pivot->case->run      = 'Run';
$lang->pivot->case->passRate = 'Percentage';
$lang->pivot->case->name     = 'Name';

$lang->pivot->bugTypeList['codeerror']   = 'code';
$lang->pivot->bugTypeList['interface']   = 'interface';
$lang->pivot->bugTypeList['config']      = 'config';
$lang->pivot->bugTypeList['install']     = 'install';
$lang->pivot->bugTypeList['security']    = 'security';
$lang->pivot->bugTypeList['performance'] = 'performace';
$lang->pivot->bugTypeList['standard']    = 'standard';
$lang->pivot->bugTypeList['automation']  = 'automation';
$lang->pivot->bugTypeList['others']      = 'others';

$lang->pivot->bug         = new stdclass();
$lang->pivot->bug->total  = 'Total';
$lang->pivot->bug->title  = 'Bug Title';
$lang->pivot->bug->story  = 'Story';
$lang->pivot->bug->status = 'Status';

$lang->pivot->caseRunDesc = 'Statistics according to the execution times and results of use cases.';
