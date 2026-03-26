<?php
global $config;
$lang->workflow->common         = 'Workflow Flow';
$lang->workflow->browseFlow     = 'View Flow';
$lang->workflow->browseDB       = 'View DB';
$lang->workflow->create         = 'Create Flow';
$lang->workflow->copy           = 'Copy Flow';
$lang->workflow->edit           = 'Edit Flow';
$lang->workflow->view           = 'View Flow';
$lang->workflow->delete         = 'Delete Flow';
$lang->workflow->fullTextSearch = 'Full-Text Retrieval';
$lang->workflow->buildIndex     = 'Build Index';
$lang->workflow->custom         = 'Custom';
$lang->workflow->setApproval    = 'Set Approval';
$lang->workflow->setJS          = 'JS';
$lang->workflow->setCSS         = 'CSS';
$lang->workflow->backup         = 'Backup Flow';
$lang->workflow->upgrade        = 'Upgrade Flow';
$lang->workflow->upgradeAction  = 'Upgrade Flow';
$lang->workflow->preview        = 'Preview';
$lang->workflow->design         = 'Design';
$lang->workflow->release        = 'Release';
$lang->workflow->syncRelease    = 'Synchronize Release';
$lang->workflow->deactivate     = 'Disable';
$lang->workflow->activate       = 'Enable';
$lang->workflow->createApp      = 'New';
$lang->workflow->cover          = 'Cover';
$lang->workflow->approval       = 'Approval';
$lang->workflow->delimiter      = ',';
$lang->workflow->belong         = 'Specific';

$lang->workflow->setFulltextSearch = 'Full-Text Retrieval';

$lang->workflow->id            = 'ID';
$lang->workflow->parent        = 'Prev';
$lang->workflow->type          = 'Type';
$lang->workflow->app           = 'App';
$lang->workflow->position      = 'Location';
$lang->workflow->module        = 'Module';
$lang->workflow->table         = 'Table';
$lang->workflow->name          = 'Name';
$lang->workflow->icon          = 'Icon';
$lang->workflow->titleField    = 'Title Field';
$lang->workflow->contentField  = 'Content Fields';
$lang->workflow->ui            = 'UI';
$lang->workflow->js            = 'JS';
$lang->workflow->css           = 'CSS';
$lang->workflow->order         = 'Order';
$lang->workflow->buildin       = 'Built-in';
$lang->workflow->administrator = 'White List';
$lang->workflow->desc          = 'Description';
$lang->workflow->version       = 'Version';
$lang->workflow->status        = 'Status';
$lang->workflow->createdBy     = 'Created By';
$lang->workflow->createdDate   = 'Created';
$lang->workflow->editedBy      = 'Edited By';
$lang->workflow->editedDate    = 'Edited';
$lang->workflow->currentTime   = 'Current Time';

$lang->workflow->actionFlowWidth = 210;

$lang->workflow->copyFlow         = 'Copy';
$lang->workflow->source           = 'Source Flow';
$lang->workflow->field            = 'Field';
$lang->workflow->action           = 'Action';
$lang->workflow->label            = 'Label';
$lang->workflow->mainTable        = 'Main Table';
$lang->workflow->subTable         = 'Sub Table';
$lang->workflow->relation         = 'Relation';
$lang->workflow->report           = 'Report';
$lang->workflow->export           = 'Export';
$lang->workflow->subTableSettings = 'Settings';
$lang->workflow->flowchart        = 'Flowchart';
$lang->workflow->quoteDB          = 'Use other template sub tables';

$lang->workflow->statusList['wait']   = 'Wait';
$lang->workflow->statusList['normal'] = 'Normal';
$lang->workflow->statusList['pause']  = 'Pause';

$lang->workflow->syncReleaseList['self']    = 'Only Release';
$lang->workflow->syncReleaseList['default'] = 'Release and Synchronize in Default Template';
$lang->workflow->syncReleaseList['all']     = 'Release and Synchronize in All Templates';

$lang->workflow->activateList['all']    = 'All Enable';
$lang->workflow->activateList['single'] = 'Single Enable';

$lang->workflow->releaseList['all']    = 'All Release';
$lang->workflow->releaseList['single'] = 'Single Release';

$lang->workflow->positionList['before'] = 'Before';
$lang->workflow->positionList['after']  = 'After';

$lang->workflow->belongList['program']   = 'Program';
$lang->workflow->belongList['product']   = $lang->productCommon;
$lang->workflow->belongList['project']   = $lang->projectCommon;
$lang->workflow->belongList['execution'] = $lang->executionCommon;
if($config->vision == 'lite') unset($lang->workflow->belongList['project']);
if($config->systemMode == 'light') unset($lang->workflow->belongList['program']);
if($config->vision == 'or')
{
    $lang->workflow->belongList = array();
    $lang->workflow->belongList['product'] = $lang->productCommon;
}

$lang->workflow->buildinList['0'] = 'No';
$lang->workflow->buildinList['1'] = 'Yes';

$lang->workflow->fullTextSearch = new stdclass();
$lang->workflow->fullTextSearch->common       = 'Full-Text Retrieval';
$lang->workflow->fullTextSearch->titleField   = 'Title Field';
$lang->workflow->fullTextSearch->contentField = 'Content Fields';

$lang->workflow->charterApprovalAction                      = 'Approval Action';
$lang->workflow->charterApproval['projectApproval']         = 'Project Approval';
$lang->workflow->charterApproval['completionApproval']      = 'Completion Approval';
$lang->workflow->charterApproval['cancelProjectApproval']   = 'Cancel Project Approval';
$lang->workflow->charterApproval['activateProjectApproval'] = 'Activate Project Approval';

$lang->workflow->upgrade = new stdclass();
$lang->workflow->upgrade->common         = 'Upgrade';
$lang->workflow->upgrade->backup         = 'Backup';
$lang->workflow->upgrade->backupSuccess  = 'Upgraded';
$lang->workflow->upgrade->newVersion     = 'Get a new version';
$lang->workflow->upgrade->clickme        = 'Upgrade';
$lang->workflow->upgrade->start          = 'Start';
$lang->workflow->upgrade->currentVersion = 'Current Version';
$lang->workflow->upgrade->selectVersion  = 'New Version';
$lang->workflow->upgrade->confirm        = 'Confirm Upgrade SQL';
$lang->workflow->upgrade->upgrade        = 'Upgrade Current Module';
$lang->workflow->upgrade->upgradeFail    = 'Failed!';
$lang->workflow->upgrade->upgradeSuccess = 'Upgraded!';
$lang->workflow->upgrade->install        = 'Install New Module';
$lang->workflow->upgrade->installFail    = 'Failed!';
$lang->workflow->upgrade->installSuccess = 'Installed!';

/* Tips */
$lang->workflow->tips = new stdclass();
$lang->workflow->tips->noCSSTag              = 'No &lt;style&gt;&lt;/style&gt; tag';
$lang->workflow->tips->noJSTag               = 'No &lt;script&gt;&lt;/script&gt;tag';
$lang->workflow->tips->flowCSS               = ', loaded in all pages.';
$lang->workflow->tips->flowJS                = ', loaded in all pages.';
$lang->workflow->tips->actionCSS             = ', loaded in the page of current action.';
$lang->workflow->tips->actionJS              = ', loaded in the page of current action.';
$lang->workflow->tips->firstRelease          = 'The flow belongs to %s. After the flow is released, it will be automatically added to the corresponding flow template. Do you want to synchronize the flow in the template?';
$lang->workflow->tips->release               = 'After the flow is released, the common flow in the flow template will be synchronized.';
$lang->workflow->tips->activate              = 'Are you sure to enable the flow? Choose "All Enable" to enable the flow in all workflow groups, or "Single Enable" to enable the flow in the default workflow group.';
$lang->workflow->tips->deactivate            = 'Are you sure to disable the flow?';
$lang->workflow->tips->syncDeactivate        = 'Are you sure to disable the flow? The flow will be hidden in all flow templates.';
$lang->workflow->tips->belongDisabled        = 'The flow has set an exclusive process in the template, and the dependent object cannot be modified.';
$lang->workflow->tips->create                = 'Nice One! You have successfully created a workflow, Would you like to design your workflow now? ';
$lang->workflow->tips->subTable              = 'If the detailed information is required to fill in the form, use a sub-table to do it. For example, the specifi information is required for requesting the reimbursement. You can add a sub-table "reimbursement details" to the reimbursement request.';
$lang->workflow->tips->buildinFlow           = 'The built-in flows can not use quick editor.';
$lang->workflow->tips->fullTextSearch        = 'To use the full-text retrieval function, you need to set which fields can be retrieved. The title field has less weight in full-text retrieval, while the content field has more weight. The higher the weight, the higher the search results. <br/>After setting the field, you need to rebuild the index to take effect. The speed of index reconstruction is directly proportional to the amount of content. Please wait patiently for the index reconstruction to complete.';
$lang->workflow->tips->buildIndex            = 'It may take some time to rebuild the index. Are you sure you want to perform the operation?';
$lang->workflow->tips->deleteConfirm         = "<p class='text-lg font-bold'>Are you sure you want to delete this process?</p><p>After deleting the process, all associated data will be deleted, such as history records, approval records, etc.</p><p>Including the exclusive process configured by the workflow and the data generated by the exclusive process.</p><p class='text-danger'><b>This operation is irreversible and the deleted content cannot be restored!</b></p>";
$lang->workflow->tips->belong                = 'This workflow will isolate data by dependent objectss, and selecting the dependent object will automatically add the flow to the corresponding flow group.';
$lang->workflow->tips->belongError           = 'The flow has set an exclusive flow in the %s template, and the dependent app cannot be modified.';
$lang->workflow->tips->noQuoteTables         = 'Other templates do not have sub-tables that can be used.';
$lang->workflow->tips->subTableSync          = 'This sub table has been referenced in %s and will be updated synchronously after modification.';
$lang->workflow->tips->notEditTable          = 'The referenced sub table cannot be edited.';
$lang->workflow->tips->confirmDeleteHasQuote = 'After deletion, all data referencing this sub table in other templates will be synchronously deleted, and the operation is irreversible. Are you sure you want to delete it?';
$lang->workflow->tips->confirmDeleteInQuote  = 'After removal, all configurations using sub table fields in this workflow will be synchronously deleted, and the operation is irreversible. Are you sure you want to remove them?';

$lang->workflow->notNow   = 'No,not now';
$lang->workflow->toDesign = 'Yes!Enter Workflow Editor';

/* Title */
$lang->workflow->title = new stdclass();
$lang->workflow->title->subTable   = 'Sub tables are used to record details of %s.';
$lang->workflow->title->noCopy     = 'The build-in flow cannot be copy.';
$lang->workflow->title->noLabel    = 'The build-in flow cannot set labels.';
$lang->workflow->title->noSubTable = 'The build-in flow cannot set sub tables.';
$lang->workflow->title->noRelation = 'The build-in flow cannot set relations.';
$lang->workflow->title->noJS       = 'The build-in flow cannot js.';
$lang->workflow->title->noCSS      = 'The build-in flow cannot css.';
$lang->workflow->title->remove     = 'Remove';

/* Placeholder */
$lang->workflow->placeholder = new stdclass();
$lang->workflow->placeholder->module       = 'Letters only. It cannot be changed once it is saved.';
$lang->workflow->placeholder->titleField   = 'There can only be one title field, which has less weight in full-text retrieval.';
$lang->workflow->placeholder->contentField = 'The content field can have more than one, so it has more weight in full-text retrieval.';

/* Error */
$lang->workflow->error = new stdclass();
$lang->workflow->error->createTableFail = 'Failed to create a table.';
$lang->workflow->error->buildInModule   = 'The flow code should not be same as the built-in module in Zdoo Pro.';
$lang->workflow->error->wrongCode       = '『%s』 should be letters.';
$lang->workflow->error->conflict        = '『%s』 conflicts with system language.';
$lang->workflow->error->notFound        = 'The flow 『%s』 not found.';
$lang->workflow->error->flowLimit       = 'You can create %s flows.';
$lang->workflow->error->buildIndexFail  = 'Failed to rebuild index.';
$lang->workflow->error->unique          = '『%s』 has been used by another template. To use this field, please select from 『Use other template sub tables』.';

$lang->workflow->notice = new stdclass();
$lang->workflow->notice->autoAddBelong = 'The system will automatically add a "%s" field for you on the "Create" page.';

$lang->workflowtable = new stdclass();
$lang->workflowtable->common = 'Sub Table';
$lang->workflowtable->browse = 'View Table';
$lang->workflowtable->create = 'Create Table';
$lang->workflowtable->edit   = 'Edit Table';
$lang->workflowtable->view   = 'View Table';
$lang->workflowtable->delete = 'Delete Table';
$lang->workflowtable->module = 'Code';
$lang->workflowtable->name   = 'Name';
$lang->workflowtable->use    = 'Use Table';

$lang->workfloweditor = new stdclass();
$lang->workfloweditor->nextStep              = 'Next';
$lang->workfloweditor->prevStep              = 'Prev';
$lang->workfloweditor->quickEditor           = 'Quick Editor';
$lang->workfloweditor->advanceEditor         = 'Advanced Editor';
$lang->workfloweditor->switchTo              = '%s';
$lang->workfloweditor->switchConfirmMessage  = 'It will switch to the advanced workflow editor. <br> You can set extensions, design labels and sub-table in advanced editor. <br> Are you sure to switch?';
$lang->workfloweditor->cancelSwitch          = 'Not now';
$lang->workfloweditor->confirmSwitch         = 'Confirm switch';
$lang->workfloweditor->elementCode           = 'Code';
$lang->workfloweditor->elementType           = 'Type';
$lang->workfloweditor->elementName           = 'Name';
$lang->workfloweditor->nameAndCodeRequired   = 'Name and code must be required';
$lang->workfloweditor->uiDesign              = 'UI Design';
$lang->workfloweditor->selectField           = 'Select Field';
$lang->workfloweditor->uiPreview             = 'UI Preview';
$lang->workfloweditor->fieldProperties       = 'Field Properties';
$lang->workfloweditor->uiControls            = 'Controls';
$lang->workfloweditor->showedFields          = 'Exists Fields';
$lang->workfloweditor->selectFieldToEditTip  = 'Select form field to edit here';
$lang->workfloweditor->addFieldOption        = 'Add Option';
$lang->workfloweditor->confirmReleaseMessage = 'You can set extension or labels by the Advanced Editor. Sure to release?';
$lang->workfloweditor->switchMessage         = 'Switch Editor Here';
$lang->workfloweditor->continueRelease       = 'Release';
$lang->workfloweditor->enterToAdvance        = 'Advanced Editor';
$lang->workfloweditor->labelAll              = 'All';
$lang->workfloweditor->confirmToDelete       = 'Are you sure to delete this %s?';
$lang->workfloweditor->leavePageTip          = 'The current page has unsaved changes. Are you sure you want to leave the page?';
$lang->workfloweditor->addFile               = 'Add File';
$lang->workfloweditor->fieldWidth            = 'Column Width';
$lang->workfloweditor->fieldPosition         = 'Text Align';
$lang->workfloweditor->dragDropTip           = 'Drag and drop here';
$lang->workfloweditor->moreSettingsLabel     = 'More Settings';

$lang->workfloweditor->quickSteps = array();
$lang->workfloweditor->quickSteps['ui'] = 'UI Design|workflow|ui';

$lang->workfloweditor->advanceSteps = array();
$lang->workfloweditor->advanceSteps['mainTable'] = 'Main Table|workflowfield|browse';
$lang->workfloweditor->advanceSteps['subTable']  = 'Sub Table|workflow|browsedb';
$lang->workfloweditor->advanceSteps['action']    = 'Actions|workflowaction|browse';
$lang->workfloweditor->advanceSteps['label']     = 'Lists|workflowlabel|browse';
$lang->workfloweditor->advanceSteps['setting']   = array('link' => 'More Settings|workflow|more', 'subMenu' => array('workflowrelation' => 'admin', 'workflowfield' => 'setValue,setExport,setSearch', 'workflow' => 'setJS,setCSS,setFulltextSearch', 'workflowreport' => 'browse'));

$lang->workfloweditor->moreSettings = array();
$lang->workfloweditor->moreSettings['approval']  = "Approval|workflow|setapproval|module=%s";
$lang->workfloweditor->moreSettings['relation']  = "Relations|workflowrelation|admin|prev=%s";
$lang->workfloweditor->moreSettings['setReport'] = "Report Settings|workflowreport|browse|module=%s";
$lang->workfloweditor->moreSettings['setValue']  = "Display Values|workflowfield|setValue|module=%s";
$lang->workfloweditor->moreSettings['setExport'] = "Export Settings|workflowfield|setExport|module=%s";
$lang->workfloweditor->moreSettings['setSearch'] = "Search Settings|workflowfield|setSearch|module=%s";
$lang->workfloweditor->moreSettings['fulltext']  = "Full-Text Retrieval|workflow|setFulltextSearch|id=%s";
$lang->workfloweditor->moreSettings['setJS']     = "JS|workflow|setJS|id=%s";
$lang->workfloweditor->moreSettings['setCSS']    = "CSS|workflow|setCSS|id=%s";

if(empty($config->openedApproval)) unset($lang->workfloweditor->moreSettings['approval']);

$lang->workfloweditor->validateMessages = array();
$lang->workfloweditor->validateMessages['nameRequired']        = 'Field name is required';
$lang->workfloweditor->validateMessages['nameDuplicated']      = 'The field name has been used, please use a different name';
$lang->workfloweditor->validateMessages['fieldRequired']       = 'Field code is required';
$lang->workfloweditor->validateMessages['fieldInvalid']        = 'Field code can only contain letters';
$lang->workfloweditor->validateMessages['fieldDuplicated']     = 'The field code is the same as the existing field "%s", please use a different code';
$lang->workfloweditor->validateMessages['lengthRequired']      = 'Field length is required';
$lang->workfloweditor->validateMessages['failSummary']         = 'There are %s errors in multiple fields, please modify them before saving.';
$lang->workfloweditor->validateMessages['defaultNotInOptions'] = 'Default value “%s” is not in options';
$lang->workfloweditor->validateMessages['defaultNotOptionKey'] = 'Default value must be a option key, dot not use value "%s"';
$lang->workfloweditor->validateMessages['widthInvalid']        = 'Width value must be number or "auto"';

$lang->workfloweditor->error = new stdclass();
$lang->workfloweditor->error->unknown = 'Unknown error, please retry.';

$lang->workflowapproval = new stdclass();
$lang->workflowapproval->enabled         = 'Enable approval';
$lang->workflowapproval->approval        = 'Appoval';
$lang->workflowapproval->approvalFlow    = 'Appoval Flow';
$lang->workflowapproval->noApproval      = 'There is no approval process available,';
$lang->workflowapproval->createTips      = array('You can', 'You can contact the administrator to create an approval process.');
$lang->workflowapproval->createApproval  = 'Create Approval';
$lang->workflowapproval->waiting         = 'Waiting';
$lang->workflowapproval->conflictField   = 'Fields:';
$lang->workflowapproval->conflictAction  = 'Actions:';
$lang->workflowapproval->openLater       = 'You can also turn approval on or off later in the advanced editor.';
$lang->workflowapproval->disableApproval = 'The flow cannot turn on the approval function.';
$lang->workflowapproval->conflict        = array('Enabled Approval', 'Enabling the approval function requires adding new fields and actions. The system detects conflicts between the following fields and actions:', 'You can click Cancel to resolve the conflict by yourself, such as "modify field code, delete field, delete action", and then re enable the approval function.', 'You can also click cover to resolve the conflict. The system will delete the conflicting fields and actions and add new fields and actions.', 'Note: the cover operation is irreversible, and the deleted fields and actions cannot be restored!');

$lang->workflowapproval->approvalList = array('enabled' => 'Enabled', 'disabled' => 'Disabled');

$lang->workflowapproval->tips = new stdclass();
$lang->workflowapproval->tips->processesInProgress = 'There is an approval process in progress. Please complete or withdraw the approval.';

$lang->workflowapproval->buildInFields = array('name' => array(), 'options' => array());
$lang->workflowapproval->buildInFields['name']['reviewers']     = 'Reviewers';
$lang->workflowapproval->buildInFields['name']['reviewStatus']  = 'Review Status';
$lang->workflowapproval->buildInFields['name']['reviewResult']  = 'Review Result';
$lang->workflowapproval->buildInFields['name']['reviewOpinion'] = 'Review Opinion';

$lang->workflowapproval->buildInFields['options']['reviewStatus'] = array('wait' => 'wait', 'doing' => 'doing', 'pass' => 'pass', 'reject' => 'reject', 'reverting' => 'reverting');
$lang->workflowapproval->buildInFields['options']['reviewResult'] = array('pass' => 'pass', 'reject' => 'reject');

$lang->workflowapproval->buildInActions = array('name' => array('submit' => 'submit', 'cancel' => 'cancel', 'review' => 'review'));
