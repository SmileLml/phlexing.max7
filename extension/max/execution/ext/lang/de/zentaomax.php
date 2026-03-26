<?php
$lang->execution->template        = 'Vorlage';
$lang->execution->finish          = 'Fertig';
$lang->execution->program         = $lang->projectCommon;
$lang->execution->taskCount       = 'Aufgabenanzahl';
$lang->execution->deliverable     = 'Lieferung';
$lang->execution->deliverableAbbr = 'Lieferung';
$lang->execution->whenClosedTips  = '(Deliverables are not strictly validated when the execution is closed)';

$lang->execution->enter = 'Eingang';
$lang->execution->draft = 'Entwurf';

$lang->execution->cannotCloseByDeliverable = "Some executions have been closed, the ongoing executions cannot be closed due to the absence of submitted deliverables. \n The following executions cannot be closed: \n %s";
$lang->execution->closeExecutionError      = "Cannot close the execution of undelivered deliverables.";
$lang->execution->notClose                 = "Cannot close the execution";
$lang->execution->cannotAutoCloseParent    = "A non-submitted deliverable has been detected in the parent, the execution cannot be closed automatically, do you want to close the parent manually?";

$lang->execution->action->managedeliverable = '$date, verwaltet von <strong>$actor</strong> der Lieferung.' . "\n";
