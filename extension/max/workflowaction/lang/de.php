<?php
$lang->workflowaction->common            = 'Workflow Action';
$lang->workflowaction->browse            = 'Actions';
$lang->workflowaction->create            = 'Create Action';
$lang->workflowaction->edit              = 'Edit Action';
$lang->workflowaction->view              = 'Action Details';
$lang->workflowaction->delete            = 'Delete Action';
$lang->workflowaction->sort              = 'Sort Action';
$lang->workflowaction->setVerification   = 'Verification';
$lang->workflowaction->resetVerification = 'Reset';
$lang->workflowaction->setNotice         = 'Notification';
$lang->workflowaction->setJS             = 'JS';
$lang->workflowaction->setCSS            = 'CSS';
$lang->workflowaction->settings          = 'Action and attribute settings';

$lang->workflowaction->id            = 'ID';
$lang->workflowaction->module        = 'Flow';
$lang->workflowaction->action        = 'Action';
$lang->workflowaction->name          = 'Name';
$lang->workflowaction->type          = 'Type';
$lang->workflowaction->batchMode     = 'Batch Mode';
$lang->workflowaction->extensionType = 'Extension Type';
$lang->workflowaction->open          = 'Open with';
$lang->workflowaction->position      = 'Position';
$lang->workflowaction->show          = 'Display';
$lang->workflowaction->order         = 'Order';
$lang->workflowaction->buildin       = 'Built-in';
$lang->workflowaction->conditions    = 'Condition';
$lang->workflowaction->verifications = 'Verification';
$lang->workflowaction->hooks         = 'Hook';
$lang->workflowaction->toList        = 'Mailto';
$lang->workflowaction->desc          = 'Description';
$lang->workflowaction->status        = 'Status';
$lang->workflowaction->createdBy     = 'Created By';
$lang->workflowaction->createdDate   = 'Created';
$lang->workflowaction->editdeBy      = 'Edited By';
$lang->workflowaction->editdeDate    = 'Edited';
$lang->workflowaction->actionBy      = '%s By';
$lang->workflowaction->actionDate    = '%s Time';

$lang->workflowaction->actionWidth = 180;

$lang->workflowaction->layout      = 'Layout';
$lang->workflowaction->setLayout   = 'Go to set';
$lang->workflowaction->condition   = 'Condition';
$lang->workflowaction->linkage     = 'Linkage';
$lang->workflowaction->hook        = 'Extended Action';
$lang->workflowaction->previewData = "<div class='example-text-holder'></div>";

$lang->workflowaction->statusList['enable']  = 'Enable';
$lang->workflowaction->statusList['disable'] = 'Disable';

$lang->workflowaction->typeList['single'] = 'Single Data';
$lang->workflowaction->typeList['batch']  = 'Multiple Data';

$lang->workflowaction->batchModeList['same']      = 'Same action';
$lang->workflowaction->batchModeList['different'] = 'Different action';

$lang->workflowaction->extensionTypeList['none']     = 'None';
$lang->workflowaction->extensionTypeList['extend']   = 'Extend';

$lang->workflowaction->openList['normal'] = 'Normal';
$lang->workflowaction->openList['modal']  = 'Modal';
$lang->workflowaction->openList['none']   = 'None';

$lang->workflowaction->positionList['browse']        = 'List Page';
$lang->workflowaction->positionList['view']          = 'Detail Page';
$lang->workflowaction->positionList['browseandview'] = 'List and Detail';

$lang->workflowaction->showList['dropdownlist'] = 'in dropdown';
$lang->workflowaction->showList['direct']       = 'on page';

$lang->workflowaction->buildinList['0'] = 'No';
$lang->workflowaction->buildinList['1'] = 'Yes';

$lang->workflowaction->default = new stdclass();
$lang->workflowaction->default->actions['browse']         = 'Lists';
$lang->workflowaction->default->actions['create']         = 'Create';
$lang->workflowaction->default->actions['batchcreate']    = 'Batch Create';
$lang->workflowaction->default->actions['batchedit']      = 'Batch Edit';
$lang->workflowaction->default->actions['batchassign']    = 'Batch Assign';
$lang->workflowaction->default->actions['edit']           = 'Edit';
$lang->workflowaction->default->actions['assign']         = 'Assign';
$lang->workflowaction->default->actions['view']           = 'Details';
$lang->workflowaction->default->actions['delete']         = 'Delete';
$lang->workflowaction->default->actions['link']           = 'Link Data';
$lang->workflowaction->default->actions['unlink']         = 'Unlink Data';
$lang->workflowaction->default->actions['export']         = 'Export Data';
$lang->workflowaction->default->actions['exporttemplate'] = 'Download Template';
$lang->workflowaction->default->actions['import']         = 'Import Data';
$lang->workflowaction->default->actions['showimport']     = 'Show Import';
$lang->workflowaction->default->actions['report']         = 'Report';
 
$lang->workflowaction->approval = new stdclass();
$lang->workflowaction->approval->actions['approvalsubmit'] = 'Submit';
$lang->workflowaction->approval->actions['approvalcancel'] = 'Cancel';
$lang->workflowaction->approval->actions['approvalreview'] = 'Review';

$lang->workflowaction->tips = new stdclass();
$lang->workflowaction->tips->buildin     = 'The built-in action does not support preview.';
$lang->workflowaction->tips->emptyLayout = 'The current action has no layout and cannot be previewed.';
$lang->workflowaction->tips->noLayout    = 'The current action does not support preview.';
$lang->workflowaction->tips->position    = 'The action will dock on right of each record in the table, if it displays a list page. Dock on the bottom of the detail page, if it displays the view page.';
$lang->workflowaction->tips->show        = 'If there are lots of actions, you can put the unusual used action into dropdown list. Otheswise display them in the list page.';

$lang->workflowaction->placeholder = new stdclass();
$lang->workflowaction->placeholder->code = 'letters only';

$lang->workflowaction->error = new stdclass();
$lang->workflowaction->error->emptyName   = 'Double click the control to set action name.';
$lang->workflowaction->error->emptyCode   = 'Double click the control to set action code.';
$lang->workflowaction->error->existCode   = 'The action code %s has existed.';
$lang->workflowaction->error->wrongCode   = '<strong> %s </strong> should be letters.';
$lang->workflowaction->error->builtinCode = "Couldn't use the built-in action code %s.";
$lang->workflowaction->error->conflict    = '<strong> %s </strong> conflicts with system language.';

/* Verification */
$lang->workflowverification = new stdclass();
$lang->workflowverification->common   = 'Verification';
$lang->workflowverification->type     = 'Type';
$lang->workflowverification->result   = 'Result';
$lang->workflowverification->field    = 'Field';
$lang->workflowverification->sql      = 'SQL';
$lang->workflowverification->varName  = 'Var Name';
$lang->workflowverification->varValue = 'Var Value';
$lang->workflowverification->message  = 'Message';

$lang->workflowverification->typeList['no']   = 'No verification';
$lang->workflowverification->typeList['data'] = 'Data';
$lang->workflowverification->typeList['sql']  = 'SQL';

$lang->workflowverification->resultList['empty']    = 'Pass validation when result is empty or zero.';
$lang->workflowverification->resultList['notempty'] = 'Pass validation when result is not empty and zero.';

$lang->workflowverification->logicalOperatorList['and'] = 'And';
$lang->workflowverification->logicalOperatorList['or']  = 'Or';

$lang->workflowverification->placeholder = new stdclass();
$lang->workflowverification->placeholder->sql     = 'Use a SQL query. Only the query is allowed. Other SQL operations are prohibited.';
$lang->workflowverification->placeholder->message = 'The message pops out when verify failed.';
