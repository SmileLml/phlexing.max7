<?php
$lang->reportList          = new stdclass();
$lang->reportList->program = new stdclass();
$lang->reportList->program->lists[5]  = $lang->projectCommon . 'Summary|report|projectsummary';
$lang->reportList->program->lists[10] = $lang->projectCommon . 'Project Workload|report|projectworkload';

$lang->report->projectSummary   = $lang->projectCommon . 'Summary';
$lang->report->projectWorkload  = $lang->projectCommon . 'Project Workload';
$lang->report->customeredReport = 'View Composite Template';
$lang->report->viewReport       = 'Preview Composite Template';

$lang->report->instanceNew = 'Create Report';
$lang->report->setName     = 'Set Report Name';
$lang->report->name        = 'Report Name';
$lang->report->history     = 'Show History';

$lang->report->titleList           = array();
$lang->report->titleList['staff']  = 'Number of participants';
$lang->report->titleList['pv']     = 'Planned workload[Man Hour]';
$lang->report->titleList['ev']     = 'Workload of' . $lang->projectCommon . 'team[Man Hour]';
$lang->report->titleList['dv']     = 'Deviation of workload estimation';
$lang->report->titleList['dvrate'] = 'Deviation rate of workload estimation';
