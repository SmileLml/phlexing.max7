<?php
$lang->workflowgroup->common         = 'Workflow Template';
$lang->workflowgroup->product        = 'Product Workflow';
$lang->workflowgroup->project        = 'Project Workflow';
$lang->workflowgroup->create         = 'Create Workflow Template';
$lang->workflowgroup->createProduct  = 'Create Product Flow';
$lang->workflowgroup->createProject  = 'Create Project Flow';
$lang->workflowgroup->edit           = 'Edit Workflow Template';
$lang->workflowgroup->delete         = 'Delete Workflow Template';
$lang->workflowgroup->view           = 'View Workflow Template';
$lang->workflowgroup->design         = 'Design Workflow Template';
$lang->workflowgroup->release        = 'Release Workflow Template';
$lang->workflowgroup->deactivate     = 'Deactivate Workflow Template';
$lang->workflowgroup->activate       = 'Activate Workflow Template';
$lang->workflowgroup->setExclusive   = 'Custom';
$lang->workflowgroup->activateFlow   = 'Activate Flow';
$lang->workflowgroup->deactivateFlow = 'Deactivate Flow';

$lang->workflowgroup->id           = 'ID';
$lang->workflowgroup->type         = 'Type';
$lang->workflowgroup->projectModel = 'Project Model';
$lang->workflowgroup->projectType  = 'Project Type';
$lang->workflowgroup->name         = 'Name';
$lang->workflowgroup->status       = 'Status';
$lang->workflowgroup->vision       = 'Vision';
$lang->workflowgroup->desc         = 'Description';
$lang->workflowgroup->createdBy    = 'Created By';
$lang->workflowgroup->createdDate  = 'Created Date';
$lang->workflowgroup->editedBy     = 'Edited By';
$lang->workflowgroup->editedDate   = 'Edited Date';
$lang->workflowgroup->deleted      = 'Deleted';
$lang->workflowgroup->template     = 'Template';
$lang->workflowgroup->flow         = 'Flow';
$lang->workflowgroup->rule         = 'Rule';

$lang->workflowgroup->notice = new stdclass();
$lang->workflowgroup->notice->confirmDeactivate = 'Are you sure you want to deactivate this template?';
$lang->workflowgroup->notice->confirmDelete     = 'Are you sure you want to delete this template? Deleting it will not affect the %s that are already using this template.';
$lang->workflowgroup->notice->confirmExclusive  = 'After being set as custom, the workflow can be personalized under this process template. It will only take effect on this process template, will not affect other processes, and the operation is irreversible.';

$lang->workflowgroup->typeList['product'] = 'Product Flow Template';
$lang->workflowgroup->typeList['project'] = 'Project Flow Template';

$lang->workflowgroup->projectModelList['scrum']     = 'Scrum';
$lang->workflowgroup->projectModelList['waterfall'] = 'Waterfall';

$lang->workflowgroup->projectTypeList['product'] = 'Product';
$lang->workflowgroup->projectTypeList['project'] = 'Project';

$lang->workflowgroup->statusList['wait']   = 'Wait';
$lang->workflowgroup->statusList['normal'] = 'Normal';
$lang->workflowgroup->statusList['pause']  = 'Pause';

$lang->workflowgroup->abbr = new stdclass();
$lang->workflowgroup->abbr->design     = 'Design';
$lang->workflowgroup->abbr->activate   = 'Activate';
$lang->workflowgroup->abbr->deactivate = 'Deactivate';

$lang->workflowgroup->workflow = new stdclass();
$lang->workflowgroup->workflow->name      = 'Name';
$lang->workflowgroup->workflow->module    = 'Code';
$lang->workflowgroup->workflow->app       = 'App';
$lang->workflowgroup->workflow->exclusive = 'General/Custom';
$lang->workflowgroup->workflow->buildin   = 'built-in';
$lang->workflowgroup->workflow->desc      = 'Description';

$lang->workflowgroup->workflow->exclusiveList[0] = 'General';
$lang->workflowgroup->workflow->exclusiveList[1] = 'Custom';

global $config;
if($config->systemMode == 'light') unset($lang->workflowgroup->projectModelList['waterfall']);
