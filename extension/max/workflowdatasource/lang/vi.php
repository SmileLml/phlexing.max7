<?php
$lang->workflowdatasource->common   = 'Workflow Datasource';
$lang->workflowdatasource->browse   = 'Datasource';
$lang->workflowdatasource->create   = 'Create Datasource';
$lang->workflowdatasource->edit     = 'Edit Datasource';
$lang->workflowdatasource->view     = 'Datasource Details';
$lang->workflowdatasource->delete   = 'Delete Datasource';
$lang->workflowdatasource->category = 'Manage';

$lang->workflowdatasource->id          = 'ID';
$lang->workflowdatasource->type        = 'Type';
$lang->workflowdatasource->name        = 'Name';
$lang->workflowdatasource->code        = 'Code';
$lang->workflowdatasource->datasource  = 'Datasource';
$lang->workflowdatasource->buildin     = 'Built-in';
$lang->workflowdatasource->createdBy   = 'Created By';
$lang->workflowdatasource->createdDate = 'Created';
$lang->workflowdatasource->editedBy    = 'Edited By';
$lang->workflowdatasource->editedDate  = 'Edited';

$lang->workflowdatasource->key         = 'Key';
$lang->workflowdatasource->value       = 'Value';
$lang->workflowdatasource->app         = 'App';
$lang->workflowdatasource->module      = 'Module';
$lang->workflowdatasource->method      = 'Method';
$lang->workflowdatasource->desc        = 'Description';
$lang->workflowdatasource->param       = 'Parameter';
$lang->workflowdatasource->paramType   = 'Type';
$lang->workflowdatasource->paramValue  = 'Value';
$lang->workflowdatasource->sql         = 'SQL';

$lang->workflowdatasource->apps['sys'] = 'Common';

$lang->workflowdatasource->options['user']        = 'System User';
$lang->workflowdatasource->options['dept']        = 'Department';
$lang->workflowdatasource->options['deptManager'] = 'Dept Manager';
$lang->workflowdatasource->options['actor']       = 'Operated User';
$lang->workflowdatasource->options['today']       = 'Operated Date';
$lang->workflowdatasource->options['now']         = 'Operated Time';
$lang->workflowdatasource->options['form']        = 'Form Data';
$lang->workflowdatasource->options['record']      = 'Record Data';
$lang->workflowdatasource->options['custom']      = 'Custom';

$lang->workflowdatasource->typeList['system']   = 'System Function';
$lang->workflowdatasource->typeList['sql']      = 'SQL';
//$lang->workflowdatasource->typeList['func']     = 'Function';
$lang->workflowdatasource->typeList['option']   = 'Option';
$lang->workflowdatasource->typeList['lang']     = 'Language';
$lang->workflowdatasource->typeList['category'] = 'Category';

$lang->workflowdatasource->langList['orderStatus']      = 'Order Status';
$lang->workflowdatasource->langList['orderReason']      = 'Order Closed Reason';
$lang->workflowdatasource->langList['contractType']     = 'Contract Type';
$lang->workflowdatasource->langList['contractDelivery'] = 'Sale Contract Delivery Status';
$lang->workflowdatasource->langList['contractReturn']   = 'Sale Contract Return Status';
$lang->workflowdatasource->langList['contractPay']      = 'Purchase Contract Pay Status';
$lang->workflowdatasource->langList['contractStatus']   = 'Contract Status';
$lang->workflowdatasource->langList['customerType']     = 'Customer Type';
$lang->workflowdatasource->langList['customerRelation'] = 'Customer Relation';
$lang->workflowdatasource->langList['customerSource']   = 'Customer Source';
$lang->workflowdatasource->langList['customerStatus']   = 'Customer Status';
$lang->workflowdatasource->langList['providerStatus']   = 'Provider Status';
$lang->workflowdatasource->langList['contactStatus']    = 'Contact Status';
$lang->workflowdatasource->langList['leadsStatus']      = 'Leads Status';
$lang->workflowdatasource->langList['productType']      = 'Product Type';
$lang->workflowdatasource->langList['productModel']     = 'Product Model';
$lang->workflowdatasource->langList['productUnit']      = 'Product Unit';
$lang->workflowdatasource->langList['productStatus']    = 'Product Status';
$lang->workflowdatasource->langList['invoiceKind']      = 'Invoice Kind';
$lang->workflowdatasource->langList['invoiceType']      = 'Invoice Type';
$lang->workflowdatasource->langList['invoiceSaleType']  = 'Invoice SaleType';
$lang->workflowdatasource->langList['invoiceSendway']   = 'Invoice Sendway';
$lang->workflowdatasource->langList['invoiceStatus']    = 'Invoice Status';
$lang->workflowdatasource->langList['feedbackPri']      = 'Feedback Pri';
$lang->workflowdatasource->langList['feedbackType']     = 'Feedback Type';
$lang->workflowdatasource->langList['feedbackReason']   = 'Feedback Closed Reason';
$lang->workflowdatasource->langList['feedbackStatus']   = 'Feedback Status';
$lang->workflowdatasource->langList['public']           = 'Public';
$lang->workflowdatasource->langList['deleted']          = 'Deleted';
$lang->workflowdatasource->langList['gender']           = 'Gender';
$lang->workflowdatasource->langList['role']             = 'Role';
$lang->workflowdatasource->langList['express']          = 'Express';
$lang->workflowdatasource->langList['bank']             = 'Bank';

$lang->workflowdatasource->placeholder = new stdclass();
$lang->workflowdatasource->placeholder->optionCode = 'It should be letters or numbers.';
$lang->workflowdatasource->placeholder->sql        = 'Use a SQL query. Only the query is allowed. Other SQL operations are prohibited. The query result is key-value pairs. The 1st field of query will be the key of result and the 2nd one be the value, other fields will be ignored. If there is only one field it will be the key and the value.';

$lang->workflowdatasource->error = new stdclass();;
$lang->workflowdatasource->error->emptyOptions = 'Empty Key and Value.';
