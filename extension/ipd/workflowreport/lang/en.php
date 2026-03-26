<?php
$lang->workflowreport->common   = 'Workflow Report';
$lang->workflowreport->report   = 'Report';
$lang->workflowreport->browse   = 'Report Settings';
$lang->workflowreport->preview  = 'Report Preview';
$lang->workflowreport->property = 'Report Attribute Settings';

$lang->workflowreport->brow     = 'Browse Report';
$lang->workflowreport->create   = 'Add Report';
$lang->workflowreport->edit     = 'Edit Report';
$lang->workflowreport->delete   = 'Delete Report';
$lang->workflowreport->sort     = 'Report Sort';
$lang->workflowreport->generate = 'Generate Report';

$lang->workflowreport->select      = 'Please select reports';
$lang->workflowreport->name        = 'Name';
$lang->workflowreport->type        = 'Type';
$lang->workflowreport->countType   = 'Count Type';
$lang->workflowreport->displayType = 'Display Method';
$lang->workflowreport->dimension   = 'Dimension';
$lang->workflowreport->fields      = 'Count Values';

$lang->workflowreport->typeList['pie']  = 'Pie chart';
$lang->workflowreport->typeList['line'] = 'Line chart';
$lang->workflowreport->typeList['bar']  = 'Bar chart';
$lang->workflowreport->iconList['pie']  = 'Pie chart  <i class="icon icon-pie-chart"></i>';
$lang->workflowreport->iconList['line'] = 'Line chart  <i class="icon icon-timeline"></i>';
$lang->workflowreport->iconList['bar']  = 'Bar chart  <i class="icon icon-bar-chart"></i>';

$lang->workflowreport->countTypeList['sum']   = 'Sum';
$lang->workflowreport->countTypeList['count'] = 'Count';

$lang->workflowreport->displayTypeList['value']   = 'Actual Value';
$lang->workflowreport->displayTypeList['percent'] = 'percent';

$lang->workflowreport->granularityList['year']      = 'Statistics by Year';
$lang->workflowreport->granularityList['quarter']   = 'Statistics by Quarter';
$lang->workflowreport->granularityList['month']     = 'Statistics by Month';
$lang->workflowreport->granularityList['week']      = 'Statistics by Week';
$lang->workflowreport->granularityList['day']       = 'Statistics by Day';

/* Tips */
$lang->workflowreport->tips = new stdclass();
$lang->workflowreport->tips->noReport = 'There are no reports currently,';
$lang->workflowreport->tips->toCreate = 'please add it';
$lang->workflowreport->tips->source   = 'Note: The report is generated from search results. Please search on the list page before you generate a report.';

/* Demo chart data. */
$lang->workflowreport->demo = new stdclass();
$lang->workflowreport->demo->pie1    = 'Blue';
$lang->workflowreport->demo->pie2    = 'Red';
$lang->workflowreport->demo->pie3    = 'Green';
$lang->workflowreport->demo->pie4    = 'Yellow';
$lang->workflowreport->demo->dataset = 'Dataset';
$lang->workflowreport->demo->month1  = 'January';
$lang->workflowreport->demo->month2  = 'February';
$lang->workflowreport->demo->month3  = 'March';
$lang->workflowreport->demo->month4  = 'April';
$lang->workflowreport->demo->month5  = 'May';
$lang->workflowreport->demo->month6  = 'June';

/* Error */
$lang->workflowreport->error =new stdclass();
$lang->workflowreport->error->notOneTable = '<strong> ' . $lang->workflowreport->dimension . ' </strong> and <strong> ' . $lang->workflowreport->fields . ' </strong> must be in the same table.';
