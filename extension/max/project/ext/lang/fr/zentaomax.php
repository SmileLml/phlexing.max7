<?php
$lang->project->approval           = 'Approval';
$lang->project->previous           = 'Previous';
$lang->project->deliverable        = 'Lieferung';
$lang->project->deliverableAbbr    = 'Lieferung';
$lang->project->template           = 'Template';
$lang->project->templateList       = 'Template Browse';
$lang->project->templateName       = 'Template Name';
$lang->project->createTemplate     = 'Create Template';
$lang->project->createTemplateAbbr = 'Create Template';
$lang->project->copyProjectID      = 'Select Project';
$lang->project->model              = 'Project Model';
$lang->project->newProject         = 'New Project';
$lang->project->deleteTemplate     = 'Delete Template';
$lang->project->inUse              = 'In Use';
$lang->project->noDesc             = 'No Description';

$lang->project->approvalflow = new stdclass();
$lang->project->approvalflow->flow   = 'Approval Flow';
$lang->project->approvalflow->object = 'Apporval Object';

$lang->project->approvalflow->objectList[''] = '';
$lang->project->approvalflow->objectList['stage'] = 'Stage';
$lang->project->approvalflow->objectList['task']  = 'Task';

$lang->project->deliverableList['create'] = 'Create Deliverables';
$lang->project->deliverableList['close']  = 'Close Deliverables';

$lang->project->copyProjectConfirm    = "Complete {$lang->projectCommon} Information";
$lang->project->executionInfoConfirm  = 'Complete Execution Information';
$lang->project->stageInfoConfirm      = 'Complete Stage Information';
$lang->project->confirmDeleteTemplate = 'Are you sure to delete the project template?';

$lang->project->executionInfoTips     = "To avoid repetition, modify the {$lang->executionCommon} name and {$lang->executionCommon} code, and set the planned start time and planned finish time.";
$lang->project->executionInfoTipsAbbr = "To avoid repetition, modify the {$lang->executionCommon} name and {$lang->executionCommon} code.";
$lang->project->deliverableTips       = 'Deliverable submission ratio = number of submitted deliverables / total number of required and submitted deliverables';
$lang->project->whenClosedTips        = '(Deliverables are not strictly validated when the project is closed)';

$lang->project->chosenProductStage = 'Please select the stage of' . $lang->productCommon . '"%s" to copy %s';
$lang->project->notCopyStage       = 'Not Copy';
$lang->project->completeCopy       = 'Complete Copy';
$lang->project->noTemplateData     = 'No Data';

$lang->project->copyProject->code               = '『' . $lang->projectCommon . '』Cannot be repeated.';
$lang->project->copyProject->executionCode      = '『' . $lang->executionCommon . '』Cannot be repeated.';
$lang->project->copyProject->select             = 'Select';
$lang->project->copyProject->confirmData        = 'Confirm';
$lang->project->copyProject->improveData        = 'Improve';
$lang->project->copyProject->completeData       = 'Complete';
$lang->project->copyProject->selectPlz          = 'Please select the' . $lang->projectCommon;
$lang->project->copyProject->cancel             = 'Cancel';
$lang->project->copyProject->all                = 'All data';
$lang->project->copyProject->basic              = 'Basic data';
$lang->project->copyProject->allList            = array($lang->projectCommon . 'data', 'The %s', 'Project and %s documentation lib', 'The %s tasks', 'QA', 'Process', 'Team member permissions');
$lang->project->copyProject->noSprintList       = array($lang->projectCommon . ' data', 'Tasks', $lang->projectCommon . ' documentation lib', 'Team member and permissions');
$lang->project->copyProject->ipdAllList         = array($lang->projectCommon . 'data', 'The %s', 'Project and %s documentation lib', 'The %s tasks', 'Team member permissions');
$lang->project->copyProject->toComplete         = 'To complete';
$lang->project->copyProject->selectProjectPlz   = 'Please select the' . $lang->projectCommon;
$lang->project->copyProject->confirmCopyDataTip = 'Make sure you want to copy the data:';
$lang->project->copyProject->basicInfo          = $lang->projectCommon . 'data (program, project name, project code,' . $lang->productCommon . ')';
$lang->project->copyProject->selectProgram      = 'Please select the program';
$lang->project->copyProject->sprint             = $lang->executionCommon;

$lang->project->action->managedeliverable = '$date, managed deliverable by <strong>$actor</strong>.';

$lang->project->featureBar['template']['all'] = 'All';

global $config;
if($config->systemMode == 'light') $lang->project->copyProject->basicInfo = $lang->projectCommon . 'data (project name, project code,' . $lang->productCommon . ')';
