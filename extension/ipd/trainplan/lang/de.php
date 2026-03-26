<?php
/* Fields. */
$lang->trainplan->common          = 'Train Plan';
$lang->trainplan->id              = 'ID';
$lang->trainplan->name            = 'Name';
$lang->trainplan->place           = 'Place';
$lang->trainplan->datePlan        = 'Plan';
$lang->trainplan->begin           = 'Begin';
$lang->trainplan->end             = 'End';
$lang->trainplan->trainee         = 'Trainee';
$lang->trainplan->lecturer        = 'Lecturer';
$lang->trainplan->type            = 'Type';
$lang->trainplan->status          = 'Status';
$lang->trainplan->summary         = 'Summary';
$lang->trainplan->createdBy       = 'Created By';
$lang->trainplan->createdDate     = 'Created Date';
$lang->trainplan->editedBy        = 'Edited By';
$lang->trainplan->editedDate      = 'Edited Date';
$lang->trainplan->legendBasicInfo = 'Basis Infos';
$lang->trainplan->legendLifeTime  = "Train Plan Life";

/* Actions. */
$lang->trainplan->browse        = 'Train Plan List';
$lang->trainplan->create        = 'Create Train Plan';
$lang->trainplan->edit          = 'Edit Train Plan';
$lang->trainplan->batchCreate   = 'Batch Create';
$lang->trainplan->batchEdit     = 'Batch Edit';
$lang->trainplan->view          = 'Train Plan Detail';
$lang->trainplan->finish        = 'Finish';
$lang->trainplan->batchFinish   = 'Batch Finish';
$lang->trainplan->byQuery       = 'Search';
$lang->trainplan->delete        = 'Delete Train Plan';
$lang->trainplan->deleted       = 'Deleted';
$lang->trainplan->finishAction  = 'Finish Train Plan';
$lang->trainplan->summaryAction = 'Commit Summary';

$lang->trainplan->typeList = array();
$lang->trainplan->typeList['inside']  = 'Inside';
$lang->trainplan->typeList['outside'] = 'Outside';

$lang->trainplan->statusList = array();
$lang->trainplan->statusList['wait'] = 'Waiting';
$lang->trainplan->statusList['done'] = 'Done';

$lang->trainplan->featureBar['browse']['all']  = 'All';
$lang->trainplan->featureBar['browse']['wait'] = 'Waiting';
$lang->trainplan->featureBar['browse']['done'] = 'Done';

$lang->trainplan->action = new stdclass();
$lang->trainplan->action->commitsummary = '$date, <strong>$actor</strong> committed a training summary.' . "\n";
$lang->trainplan->action->updatetrainee = '$date, <strong>$actor</strong> updated the trainee of train plan.' . "\n";

$lang->trainplan->confirmDelete  = 'Do you want to delelte this train plan?';
$lang->trainplan->notAllowedEdit = 'The training plan you select is Done. You cannot edit it.';
$lang->trainplan->endSmall       = '"End" must be greater than "Begin"';
