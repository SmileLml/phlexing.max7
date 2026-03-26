<?php
$lang->execution->template        = 'Template';
$lang->execution->finish          = 'Terminé';
$lang->execution->program         = $lang->projectCommon;
$lang->execution->taskCount       = 'Nombre de tâches';
$lang->execution->deliverable     = 'Livrable';
$lang->execution->deliverableAbbr = 'Livrable';
$lang->execution->whenClosedTips  = '(Deliverables are not strictly validated when the execution is closed)';

$lang->execution->enter = 'Entry';
$lang->execution->draft = 'Draft';

$lang->execution->cannotCloseByDeliverable = "Certaines exécutions ont été fermées, les exécutions en cours ne peuvent pas être fermées en raison de l'absence de livrables soumis. \n Les exécutions suivantes ne peuvent pas être fermées : \n %s";
$lang->execution->closeExecutionError      = "Cannot close the execution of undelivered deliverables.";
$lang->execution->notClose                 = "Cannot close the execution";
$lang->execution->cannotAutoCloseParent    = "Un livrable non soumis a été détecté dans le parent, l'exécution ne peut pas être fermée automatiquement, voulez-vous fermer manuellement le parent ?";

$lang->execution->action->managedeliverable = '$date, mise à jour par <strong>$actor</strong> du livrable.' . "\n";
