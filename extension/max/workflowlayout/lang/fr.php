<?php
$lang->workflowlayout->common  = 'Workflow Layout';
$lang->workflowlayout->admin   = 'Manage Layout';

$lang->workflowlayout->id           = 'ID';
$lang->workflowlayout->module       = 'Module';
$lang->workflowlayout->action       = 'Action';
$lang->workflowlayout->field        = 'Field';
$lang->workflowlayout->order        = 'Order';
$lang->workflowlayout->width        = 'Width';
$lang->workflowlayout->position     = 'Position';
$lang->workflowlayout->alignment    = 'Alignment';
$lang->workflowlayout->readonly     = 'Readonly';
$lang->workflowlayout->mobileShow   = 'Show on Mobile';
$lang->workflowlayout->summary      = 'Summary';
$lang->workflowlayout->defaultValue = 'Default Value';
$lang->workflowlayout->layoutRules  = 'Rules';
$lang->workflowlayout->blockName    = 'Block Name';
$lang->workflowlayout->tabName      = 'Tab Name';

$lang->workflowlayout->show     = 'Show';
$lang->workflowlayout->hide     = 'Hide';
$lang->workflowlayout->require  = 'Required';
$lang->workflowlayout->custom   = 'Custom';
$lang->workflowlayout->block    = 'Set Block';
$lang->workflowlayout->showName = 'Show';
$lang->workflowlayout->addBlock = 'Add Block';
$lang->workflowlayout->addTab   = 'Add Tab';
$lang->workflowlayout->addUI    = 'Add UI';
$lang->workflowlayout->editUI   = 'Edit UI';
$lang->workflowlayout->deleteUI = 'Delete UI';
$lang->workflowlayout->design   = 'Design';

$lang->workflowlayout->ui = new stdclass();
$lang->workflowlayout->ui->name      = 'UI Name';
$lang->workflowlayout->ui->other     = 'Other UI Condition';
$lang->workflowlayout->ui->condition = 'Condition';

$lang->workflowlayout->positionList['browse']['left']   = 'align-left';
$lang->workflowlayout->positionList['browse']['center'] = 'align-center';
$lang->workflowlayout->positionList['browse']['right']  = 'align-right';

$lang->workflowlayout->positionList['view']['basic'] = 'Basic Info';
$lang->workflowlayout->positionList['view']['info']  = 'Detail';

$lang->workflowlayout->positionList['edit']['basic'] = 'align-right';
$lang->workflowlayout->positionList['edit']['info']  = 'align-left';

$lang->workflowlayout->mobileList[1] = 'Display';
$lang->workflowlayout->mobileList[0] = 'Hide';

$lang->workflowlayout->summaryList['sum']     = 'Total';
$lang->workflowlayout->summaryList['average'] = 'Average';
$lang->workflowlayout->summaryList['max']     = 'Max';
$lang->workflowlayout->summaryList['min']     = 'Min';

$lang->workflowlayout->default = new stdclass();
$lang->workflowlayout->default->user['currentUser'] = 'Current User';
$lang->workflowlayout->default->user['deptManager'] = 'Department Manager';
$lang->workflowlayout->default->dept['currentDept'] = 'Department';
$lang->workflowlayout->default->time['currentTime'] = 'Time';

$lang->workflowlayout->tips = new stdclass();
$lang->workflowlayout->tips->position      = 'Basic information is displayed on the right of the page and details left.';
$lang->workflowlayout->tips->confirmDelete = 'Are you sure you want to delete this UI? It cannot be restored.';
$lang->workflowlayout->tips->duplicateUI   = 'the [%s] field of the [%s] interface';

$lang->workflowlayout->error = new stdclass();
$lang->workflowlayout->error->mobileShow          = 'Up to 5 fields on list page on mobile device.';
$lang->workflowlayout->error->emptyCustomFields   = "Go to [Workflow] => [%s] => [Field] to add fields.";
$lang->workflowlayout->error->emptyLayout         = "You have not set the layout for <strong>%s</strong>. <br> If the action doesn't need set layout, switch to the <strong>Advanced Editor</strong>, change the <strong>Open</strong> attribute to 'None', or change the <strong>Status</strong> attribute to 'Disable'.";
$lang->workflowlayout->error->emptyConditions     = "The new interface cannot be without triggering conditions.";
$lang->workflowlayout->error->duplicateConditions = "There is a possibility of duplication between the current triggering condition and %s.";
$lang->workflowlayout->error->contradiction       = "There is a contradiction in the current triggering condition settings, please recheck.";
