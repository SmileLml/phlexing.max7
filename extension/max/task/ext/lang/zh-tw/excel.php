<?php
$lang->task->importCase     = '導入任務';
$lang->task->import         = '導入Excel';
$lang->task->exportTemplate = '導出模板';
$lang->task->showImport     = '顯示導入內容';
$lang->task->importNotice   = '請先導出模板，按照模板格式填寫數據後再導入。';
$lang->task->noRequire      = '%s行的“%s”是必填欄位，不能為空。';
$lang->task->noExcelData    = '檔案數據為空，請重新導入！';

$lang->task->new   = '新增';
$lang->task->level = '層級';

$lang->task->num = '任務記錄數：';

$lang->task->filed = new stdclass();
$lang->task->field['id']         = 'ID編號';
$lang->task->field['title']      = '名稱';
$lang->task->field['startTime']  = '開始時間';
$lang->task->field['pri']        = '優先順序';
$lang->task->field['assignedTo'] = '指派給';

$lang->task->excelmodule   = '模組#';
$lang->task->excelproduct  = $lang->productCommon . '#';
$lang->task->exceltaskName = '任務#';
$lang->task->excelstory    = "{$lang->SRCommon}#";

$lang->task->error->assignedToError = '系統檢測到存在多人任務，指派給需在多人任務的團隊中！';
