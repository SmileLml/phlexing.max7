<?php
$lang->workflowrule->common = 'Workflow Rule';
$lang->workflowrule->browse = 'Rules';
$lang->workflowrule->create = 'Create Rule';
$lang->workflowrule->edit   = 'Edit Rule';
$lang->workflowrule->view   = 'View Rule';
$lang->workflowrule->delete = 'Delete Rule';

$lang->workflowrule->id          = 'ID';
$lang->workflowrule->type        = 'Type';
$lang->workflowrule->name        = 'Name';
$lang->workflowrule->rule        = 'Rule';
$lang->workflowrule->createdBy   = 'Created By';
$lang->workflowrule->createdDate = 'Created';
$lang->workflowrule->editedBy    = 'Edited By';
$lang->workflowrule->editedDate  = 'Edited';

$lang->workflowrule->typeList['system'] = 'System Function';
$lang->workflowrule->typeList['regex']  = 'Regular Expression';
$lang->workflowrule->typeList['func']   = 'Function';

$lang->workflowrule->default = new stdclass();
$lang->workflowrule->default->rules['notempty'] = 'Required';
$lang->workflowrule->default->rules['date']     = 'Date';
$lang->workflowrule->default->rules['email']    = 'Email';
$lang->workflowrule->default->rules['float']    = 'Number';
$lang->workflowrule->default->rules['phone']    = 'Phone';
$lang->workflowrule->default->rules['ip']       = 'IP';

$lang->workflowrule->error = new stdclass();
$lang->workflowrule->error->wrongRegex = 'The regular expression is wrong! Error: ';

$lang->workflowrule->tip = new stdclass();
$lang->workflowrule->tip->delimiter = 'Regular expressions need to contain delimiters.';
