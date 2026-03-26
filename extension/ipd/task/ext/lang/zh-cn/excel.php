<?php
$lang->task->importCase     = '导入任务';
$lang->task->import         = '导入Excel';
$lang->task->exportTemplate = '导出模板';
$lang->task->showImport     = '显示导入内容';
$lang->task->importNotice   = '请先导出模板，按照模板格式填写数据后再导入。';
$lang->task->noRequire      = '%s行的“%s”是必填字段，不能为空。';
$lang->task->noExcelData    = '文件数据为空，请重新导入！';

$lang->task->new   = '新增';
$lang->task->level = '层级';

$lang->task->num = '任务记录数：';

$lang->task->filed = new stdclass();
$lang->task->field['id']         = 'ID编号';
$lang->task->field['title']      = '名称';
$lang->task->field['startTime']  = '开始时间';
$lang->task->field['pri']        = '优先级';
$lang->task->field['assignedTo'] = '指派给';

$lang->task->excelmodule   = '模块#';
$lang->task->excelproduct  = $lang->productCommon . '#';
$lang->task->exceltaskName = '任务#';
$lang->task->excelstory    = "{$lang->SRCommon}#";

$lang->task->error->assignedToError = '系统检测到存在多人任务，指派给需在多人任务的团队中！';
