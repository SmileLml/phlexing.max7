<?php
$lang->reportList          = new stdclass();
$lang->reportList->program = new stdclass();
$lang->reportList->program->lists[5]  = $lang->projectCommon . '汇总表|report|projectsummary';
$lang->reportList->program->lists[10] = $lang->projectCommon . '工作量统计表|report|projectworkload';

$lang->report->projectSummary   = $lang->projectCommon . '汇总表';
$lang->report->projectWorkload  = $lang->projectCommon . '工作量统计表';
$lang->report->customeredReport = '查看复合模板';
$lang->report->viewReport       = '预览复合模板';

$lang->report->instanceNew = '生成新报告';
$lang->report->setName     = '设置报表名称';
$lang->report->name        = '报表名称';
$lang->report->history     = '查看历史报表';

$lang->report->titleList           = array();
$lang->report->titleList['staff']  = '参与人数';
$lang->report->titleList['pv']     = '计划工作量[人时]';
$lang->report->titleList['ev']     = $lang->projectCommon . '组实际工作量[人时]';
$lang->report->titleList['dv']     = '工作量估算偏差';
$lang->report->titleList['dvrate'] = '工作量估算偏差率';
