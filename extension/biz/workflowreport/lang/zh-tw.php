<?php
$lang->workflowreport->common   = '工作流報表';
$lang->workflowreport->report   = '報表';
$lang->workflowreport->browse   = '報表設置';
$lang->workflowreport->preview  = '報表預覽';
$lang->workflowreport->property = '報表及屬性設置';

$lang->workflowreport->brow     = '瀏覽報表';
$lang->workflowreport->create   = '新增報表';
$lang->workflowreport->edit     = '編輯報表';
$lang->workflowreport->delete   = '刪除報表';
$lang->workflowreport->sort     = '報表排序';
$lang->workflowreport->generate = '生成報表';

$lang->workflowreport->select      = '請選擇報表';
$lang->workflowreport->name        = '名稱';
$lang->workflowreport->type        = '類型';
$lang->workflowreport->countType   = '統計類型';
$lang->workflowreport->displayType = '顯示方式';
$lang->workflowreport->dimension   = '維度';
$lang->workflowreport->fields      = '統計數值';

$lang->workflowreport->typeList['pie']  = '餅狀圖';
$lang->workflowreport->typeList['line'] = '折線圖';
$lang->workflowreport->typeList['bar']  = '柱狀圖';
$lang->workflowreport->iconList['pie']  = '餅狀圖  <i class="icon icon-chart-pie"></i>';
$lang->workflowreport->iconList['line'] = '折線圖  <i class="icon icon-chart-line"></i>';
$lang->workflowreport->iconList['bar']  = '柱狀圖  <i class="icon icon-chart-bar"></i>';

$lang->workflowreport->countTypeList['sum']   = '求和';
$lang->workflowreport->countTypeList['count'] = '計數';

$lang->workflowreport->displayTypeList['value']   = '實際值';
$lang->workflowreport->displayTypeList['percent'] = '百分比';

$lang->workflowreport->granularityList['year']      = '按年統計';
$lang->workflowreport->granularityList['quarter']   = '按季度統計';
$lang->workflowreport->granularityList['month']     = '按月統計';
$lang->workflowreport->granularityList['week']      = '按周統計';
$lang->workflowreport->granularityList['day']       = '按天統計';

/* Tips */
$lang->workflowreport->tips = new stdclass();
$lang->workflowreport->tips->noReport = '當前沒有報表，';
$lang->workflowreport->tips->toCreate = '去新增';
$lang->workflowreport->tips->source   = '註：統計報表的數據，來源於列表頁面的檢索結果，生成統計報表前請先在列表頁面進行檢索。';

/* Demo chart data. */
$lang->workflowreport->demo = new stdclass();
$lang->workflowreport->demo->pie1    = '藍色';
$lang->workflowreport->demo->pie2    = '紅色';
$lang->workflowreport->demo->pie3    = '綠色';
$lang->workflowreport->demo->pie4    = '黃色';
$lang->workflowreport->demo->dataset = '數據';
$lang->workflowreport->demo->month1  = '一月';
$lang->workflowreport->demo->month2  = '二月';
$lang->workflowreport->demo->month3  = '三月';
$lang->workflowreport->demo->month4  = '四月';
$lang->workflowreport->demo->month5  = '五月';
$lang->workflowreport->demo->month6  = '六月';

/* Error */
$lang->workflowreport->error = new stdclass();
$lang->workflowreport->error->notOneTable = '<strong> ' . $lang->workflowreport->dimension . ' </strong>和<strong> ' . $lang->workflowreport->fields . ' </strong>必須是同一個表中的欄位。';
