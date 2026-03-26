<?php
$lang->report->crystalExport = 'Export Single Template';
$lang->report->null          = 'NULL';
$lang->report->name          = 'Report';
$lang->report->code          = 'Code';

$lang->crystal = new stdclass();
$lang->crystal->common    = 'Crystal Report';
$lang->crystal->setVar    = 'Add Variable';
$lang->crystal->browse    = 'Reports';
$lang->crystal->use       = 'Design';
$lang->crystal->addVar    = 'Add Variable';
$lang->crystal->addLang   = 'Set Field';
$lang->crystal->custom    = 'Add Single Template';
$lang->crystal->saveAs    = 'Save as';

$lang->crystal->sql          = 'SQL Query';
$lang->crystal->explain      = 'Explain';
$lang->crystal->query        = 'Search';
$lang->crystal->condition    = 'Design';
$lang->crystal->params       = 'Params';
$lang->crystal->result       = 'Query Result';
$lang->crystal->group        = 'GroupBy Field';
$lang->crystal->statistics   = 'Report Field';
$lang->crystal->group1       = 'Field 1';
$lang->crystal->group2       = 'Field 2';
$lang->crystal->reportField  = 'Field';
$lang->crystal->reportType   = 'Method';
$lang->crystal->reportTotal  = 'Sum';
$lang->crystal->percent      = 'Percentage';
$lang->crystal->contrast     = 'Compare';
$lang->crystal->showAlone    = 'in a separated column';
$lang->crystal->percentAB    = '%';
$lang->crystal->isUser       = 'User Realname';
$lang->crystal->total        = 'Total';
$lang->crystal->requestType  = 'Input';
$lang->crystal->varName      = 'Variable Name';
$lang->crystal->showName     = 'Display Name';
$lang->crystal->name         = 'Report';
$lang->crystal->module       = 'Module';
$lang->crystal->id           = 'ID';
$lang->crystal->code         = 'Code';
$lang->crystal->all          = 'All';
$lang->crystal->fieldName    = 'Field Name';
$lang->crystal->fieldValue   = 'Display Name';
$lang->crystal->lang         = 'Languages';
$lang->crystal->desc         = 'Description';
$lang->crystal->default      = 'Default Value';
$lang->crystal->project      = $lang->projectCommon;
$lang->crystal->execution    = 'Execution';
$lang->crystal->allProject   = 'All' . $lang->projectCommon;
$lang->crystal->allExecution = 'All Execution';

$lang->crystal->reportTypeList['count'] = 'Count';
$lang->crystal->reportTypeList['sum']   = 'Sum';

$lang->crystal->requestTypeList['input']    = 'Text';
$lang->crystal->requestTypeList['date']     = 'Date';
$lang->crystal->requestTypeList['datetime'] = 'Time';
$lang->crystal->requestTypeList['select']   = 'Dropdown';

$lang->crystal->selectList['user']           = 'Users';
$lang->crystal->selectList['product']        = $lang->productCommon . 's';
$lang->crystal->selectList['project']        = $lang->projectCommon . 'List';
$lang->crystal->selectList['execution']      = $lang->executionCommon . 's';
$lang->crystal->selectList['dept']           = 'Departments';
$lang->crystal->selectList['project.status'] = $lang->projectCommon . 'Status List';

$lang->crystal->moduleList['']        = '';
$lang->crystal->moduleList['product'] = $lang->productCommon;
$lang->crystal->moduleList['project'] = $lang->projectCommon;
$lang->crystal->moduleList['test']    = 'QA';
$lang->crystal->moduleList['staff']   = 'Company';

$lang->crystal->sqlPlaceholder    = 'A SQL query can only do query, and other SQL is forbidden.';
$lang->crystal->sumPlaceholder    = 'Select fields to sum up';
$lang->crystal->codePlaceholder   = 'Unique code for the report';
$lang->crystal->noticeSelect      = 'SQL query only';
$lang->crystal->noticeBlack       = 'SQL contains non query keywords %s';
$lang->crystal->notResult         = 'No query data.';
$lang->crystal->noticeResult      = 'Total %s. Show %s';
$lang->crystal->noticeVarName     = 'The name of the variable is not set';
$lang->crystal->noticeRequestType = 'The input of %s is not set.';
$lang->crystal->noticeShowName    = 'The name of variable %s is not set.';
$lang->crystal->errorSave         = 'SQL variables cannot be empty!';
$lang->crystal->errorNoReport     = 'The report does not exist!';
$lang->crystal->confirmDelete     = 'Do you want to delete this report?';
$lang->crystal->errorSql          = 'The SQL is wrong! Error:';
$lang->crystal->noSumAppend       = 'No fields have been selected to sum up in %s.';
$lang->crystal->noStep            = 'Please search the data, then save the report!';
$lang->crystal->emptyName         = 'The report name should not be empty.';

$lang->report->custom           = $lang->crystal->custom;
$lang->report->useReport        = $lang->crystal->use;
$lang->report->useReportAction  = 'Design Single Template';
$lang->report->browseReport     = 'Browse Single Template';
$lang->report->deleteReport     = 'Delete Single Template';
$lang->report->editReport       = 'Edit';
$lang->report->editReportAction = 'Edit Single Template';
$lang->report->saveReport       = 'Save Single Template';
$lang->report->show             = 'Show Single Template';

$lang->report->statisticLangList['project']   = $lang->projectCommon . 'List';
$lang->report->statisticLangList['product']   = $lang->productCommon;
$lang->report->statisticLangList['dept']      = 'Department';
$lang->report->statisticLangList['execution'] = 'Execution List';
$lang->report->statisticLangList['status']    = 'Status';
$lang->report->statisticLangList['beginDate'] = 'Begin';
$lang->report->statisticLangList['startDate'] = 'Begin';
$lang->report->statisticLangList['endDate']   = 'End';

$lang->datepicker->dpText->TEXT_WEEK_MONDAY = 'Monday';
$lang->datepicker->dpText->TEXT_WEEK_SUNDAY = 'Sunday';
$lang->datepicker->dpText->TEXT_MONTH_BEGIN = 'Begin Month';
$lang->datepicker->dpText->TEXT_MONTH_END   = 'End Month';

$lang->report->projectStatusList['done']   = 'Done';
$lang->report->projectStatusList['cancel'] = 'Cancel';
$lang->report->projectStatusList['pause']  = 'Pause';
