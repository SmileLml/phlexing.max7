<?php
$lang->reportList          = new stdclass();
$lang->reportList->program = new stdclass();
$lang->reportList->program->lists[5]  = $lang->projectCommon . '彙總表|report|projectsummary';
$lang->reportList->program->lists[10] = $lang->projectCommon . '工作量統計表|report|projectworkload';

$lang->report->projectSummary   = $lang->projectCommon . '彙總表';
$lang->report->projectWorkload  = $lang->projectCommon . '工作量統計表';
$lang->report->customeredReport = '查看複合模板';
$lang->report->viewReport       = '預覽複合模板';

$lang->report->instanceNew = '生成新報告';
$lang->report->setName     = '設置報表名稱';
$lang->report->name        = '報表名稱';
$lang->report->history     = '查看歷史報表';

$lang->report->titleList           = array();
$lang->report->titleList['staff']  = '參與人數';
$lang->report->titleList['pv']     = '計劃工作量[人時]';
$lang->report->titleList['ev']     = $lang->projectCommon . '組實際工作量[人時]';
$lang->report->titleList['dv']     = '工作量估算偏差';
$lang->report->titleList['dvrate'] = '工作量估算偏差率';
