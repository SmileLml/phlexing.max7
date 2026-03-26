<?php
/* Fields */
$lang->opportunity->source            = 'Source';
$lang->opportunity->id                = 'ID';
$lang->opportunity->name              = 'Name';
$lang->opportunity->type              = 'Type';
$lang->opportunity->strategy          = 'Strategy';
$lang->opportunity->status            = 'Status';
$lang->opportunity->impact            = 'Impact';
$lang->opportunity->chance            = 'Probability';
$lang->opportunity->ratio             = 'Opportunity Index';
$lang->opportunity->execution         = $lang->execution->common;
$lang->opportunity->pri               = 'Priority';
$lang->opportunity->desc              = 'Description';
$lang->opportunity->prevention        = 'Solution';
$lang->opportunity->identifiedDate    = 'Identified';
$lang->opportunity->plannedClosedDate = 'Deadline';
$lang->opportunity->assignedTo        = 'AssignedTo';
$lang->opportunity->assignedDate      = 'AssignedDate';
$lang->opportunity->createdBy         = 'Added By';
$lang->opportunity->createdDate       = 'Added Date';
$lang->opportunity->noAssigned        = 'Unassigned';
$lang->opportunity->canceledBy        = 'CanceledBy';
$lang->opportunity->canceledDate      = 'CanceledDate';
$lang->opportunity->cancelReason      = 'Cancel Reason';
$lang->opportunity->resolvedBy        = 'ResolvedBy';
$lang->opportunity->resolvedDate      = 'ResolvedDate';
$lang->opportunity->closedBy          = 'ClosedBy';
$lang->opportunity->closedDate        = 'ClosedDate';
$lang->opportunity->actualClosedDate  = 'Closed Date';
$lang->opportunity->resolution        = 'Response';
$lang->opportunity->hangupedBy        = 'SuspendedBy';
$lang->opportunity->hangupedDate      = 'SuspendedDate';
$lang->opportunity->activatedBy       = 'ActivatedBy';
$lang->opportunity->activatedDate     = 'ActivatedDate';
$lang->opportunity->isChange          = 'Any change?';
$lang->opportunity->lastCheckedBy     = 'Tracked By';
$lang->opportunity->lastCheckedDate   = 'Tracked Date';
$lang->opportunity->editedBy          = 'EditedBy';
$lang->opportunity->editedDate        = 'EditedDate';
$lang->opportunity->legendBasicInfo   = 'Basic Info.';
$lang->opportunity->legendLifeTime    = 'About Opportunity';
$lang->opportunity->confirmDelete     = 'Do you want to delelte this opportunity?';
$lang->opportunity->deleted           = 'Deleted';
$lang->opportunity->allLib            = 'All Opportunity Library';
$lang->opportunity->lib               = 'Opportunity Library';
$lang->opportunity->approver          = 'Approver';

/* Actions */
$lang->opportunity->create            = 'Create Opportunity';
$lang->opportunity->edit              = 'Edit Opportunity';
$lang->opportunity->browse            = 'Opportunities';
$lang->opportunity->view              = 'Details';
$lang->opportunity->activate          = 'Active';
$lang->opportunity->hangup            = 'Suspend';
$lang->opportunity->close             = 'Close';
$lang->opportunity->cancel            = 'Cancle';
$lang->opportunity->batchCreate       = 'Batch Create';
$lang->opportunity->batchEdit         = 'Batch Edit';
$lang->opportunity->batchAssignTo     = 'Batch AssignTo';
$lang->opportunity->batchClose        = 'Batch Close';
$lang->opportunity->batchCancel       = 'Batch Cancel';
$lang->opportunity->batchHangup       = 'Batch Hangup';
$lang->opportunity->batchActivate     = 'Batch Activate';
$lang->opportunity->track             = 'Track';
$lang->opportunity->assignTo          = 'Assign';
$lang->opportunity->delete            = 'Delete';
$lang->opportunity->byQuery           = 'Search';
$lang->opportunity->trackAction       = 'Track Opportunity';
$lang->opportunity->assignToAction    = 'Assign Opportunity';
$lang->opportunity->cancelAction      = 'Cancel Opportunity';
$lang->opportunity->closeAction       = 'Close Opportunity';
$lang->opportunity->hangupAction      = 'Hangup Opportunity';
$lang->opportunity->activateAction    = 'Activate Opportunity';
$lang->opportunity->deleteAction      = 'Delete Opportunity';
$lang->opportunity->importToLib      = 'Import To Lib';
$lang->opportunity->batchImportToLib = 'Batch Import To Lib';
$lang->opportunity->isExist          = 'This opportunity is already added in the Issue Lib. Please do not add it again.';

$lang->opportunity->importFromLib    = 'Import From Library';
$lang->opportunity->import           = 'Import';
$lang->opportunity->export           = 'Export Data';
$lang->opportunity->exportAction     = 'Export Opportunity';

$lang->opportunity->action = new stdclass();
$lang->opportunity->action->hangup                   = '$date, suspended by <strong>$actor</strong>.' . "\n";
$lang->opportunity->action->tracked                  = '$date, tracked by <strong>$actor</strong>.' . "\n";
$lang->opportunity->action->importfromopportunitylib = array('main' => '$date, imported by <strong>$actor</strong> from <strong>$extra</strong>.');;

$lang->opportunity->sourceList[''] = '';
$lang->opportunity->sourceList['business'] = 'Business';
$lang->opportunity->sourceList['team']     = $lang->projectCommon . 'Team';
$lang->opportunity->sourceList['logistic'] = $lang->projectCommon . 'Logistics';
$lang->opportunity->sourceList['manage']   = 'Management';
$lang->opportunity->sourceList['customer'] = 'Customer';
$lang->opportunity->sourceList['others']   = 'Other';

$lang->opportunity->typeList[''] = '';
$lang->opportunity->typeList['technical']   = 'Technology';
$lang->opportunity->typeList['manage']      = 'Management';
$lang->opportunity->typeList['business']    = 'Business';
$lang->opportunity->typeList['requirement'] = 'Requirement';
$lang->opportunity->typeList['resource']    = 'Resource';
$lang->opportunity->typeList['others']      = 'Other';

$lang->opportunity->impactList[1]  = 1;
$lang->opportunity->impactList[2]  = 2;
$lang->opportunity->impactList[3]  = 3;
$lang->opportunity->impactList[4]  = 4;
$lang->opportunity->impactList[5]  = 5;

$lang->opportunity->chanceList[1]  = 1;
$lang->opportunity->chanceList[2]  = 2;
$lang->opportunity->chanceList[3]  = 3;
$lang->opportunity->chanceList[4]  = 4;
$lang->opportunity->chanceList[5]  = 5;

$lang->opportunity->priList['high']   = 'High';
$lang->opportunity->priList['middle'] = 'Medium';
$lang->opportunity->priList['low']    = 'Low';

$lang->opportunity->statusList[''] = '';
$lang->opportunity->statusList['active']   = 'Active';
$lang->opportunity->statusList['closed']   = 'Closed';
$lang->opportunity->statusList['hangup']   = 'Suspended';
$lang->opportunity->statusList['canceled'] = 'Canceled';

$lang->opportunity->strategyList[''] = '';
$lang->opportunity->strategyList['monitor'] = 'Monitor';
$lang->opportunity->strategyList['create']  = 'Create';
$lang->opportunity->strategyList['utilize'] = 'Utilize';
$lang->opportunity->strategyList['enhance'] = 'Enhance';
$lang->opportunity->strategyList['accept']  = 'Accept';

$lang->opportunity->isChangeList[0] = 'No';
$lang->opportunity->isChangeList[1] = 'Yes';

$lang->opportunity->cancelReasonList[''] = '';
$lang->opportunity->cancelReasonList['disappeared'] = 'Dispear';
$lang->opportunity->cancelReasonList['mistake']     = 'Mistake';

$lang->opportunity->featureBar['browse']['all']      = 'All';
$lang->opportunity->featureBar['browse']['active']   = 'Active';
$lang->opportunity->featureBar['browse']['assignTo'] = 'AssignToMe';
$lang->opportunity->featureBar['browse']['closed']   = 'Closed';
$lang->opportunity->featureBar['browse']['hangup']   = 'Suspended';
$lang->opportunity->featureBar['browse']['canceled'] = 'Canceled';
