<?php
$lang->execution->template        = 'Template';
$lang->execution->finish          = 'Finish';
$lang->execution->program         = $lang->projectCommon;
$lang->execution->taskCount       = 'Task Count';
$lang->execution->deliverable     = 'Deliverable';
$lang->execution->deliverableAbbr = 'Deliverable';
$lang->execution->whenClosedTips  = '(Deliverables are not strictly validated when the execution is closed)';

$lang->execution->enter = 'Entry';
$lang->execution->draft = 'Draft';

$lang->execution->cannotCloseByDeliverable = "Some executions have been closed, the ongoing executions cannot be closed due to the absence of submitted deliverables. \n The following executions cannot be closed: \n %s";
$lang->execution->closeExecutionError      = "Cannot close the execution of undelivered deliverables.";
$lang->execution->notClose                 = "Cannot close the execution";
$lang->execution->cannotAutoCloseParent    = "A non-submitted deliverable has been detected in the parent, the execution cannot be closed automatically, do you want to close the parent manually?";

$lang->execution->action->managedeliverable = '$date, managed by <strong>$actor</strong> of deliverable.' . "\n";
