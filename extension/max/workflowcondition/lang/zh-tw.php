<?php
$lang->workflowcondition->common = '工作流觸發條件';
$lang->workflowcondition->browse = '瀏覽觸發條件';
$lang->workflowcondition->create = '添加觸發條件';
$lang->workflowcondition->edit   = '編輯觸發條件';
$lang->workflowcondition->delete = '刪除觸發條件';

$lang->workflowcondition->condition = '觸發條件';
$lang->workflowcondition->type      = '條件類型';
$lang->workflowcondition->result    = '條件結果';
$lang->workflowcondition->field     = '欄位';
$lang->workflowcondition->sql       = 'SQL';
$lang->workflowcondition->varName   = '變數名';
$lang->workflowcondition->varValue  = '變數值';

$lang->workflowcondition->know = '知道了';
$lang->workflowcondition->tips = '設置顯示當前動作的條件，例如：當採購金額＞1000時，顯示“審核”動作。';

$lang->workflowcondition->typeList['data'] = '以數據作為校驗方式';
$lang->workflowcondition->typeList['sql']  = '以SQL語句作為校驗方式';

$lang->workflowcondition->resultList['empty']    = '查詢結果為空或0時通過校驗';
$lang->workflowcondition->resultList['notempty'] = '查詢結果不為空和0時通過校驗';

$lang->workflowcondition->logicalOperatorList['and'] = '並且';
$lang->workflowcondition->logicalOperatorList['or']  = '或者';

$lang->workflowcondition->options['currentUser'] = '當前用戶';
$lang->workflowcondition->options['currentDept'] = '當前部門';
$lang->workflowcondition->options['deptManager'] = '部門經理';
$lang->workflowcondition->options['actor']       = '操作人';
$lang->workflowcondition->options['today']       = '操作日期';
$lang->workflowcondition->options['now']         = '操作時間';

$lang->workflowcondition->operatorList['equal']      = '=';
$lang->workflowcondition->operatorList['notequal']   = '!=';
$lang->workflowcondition->operatorList['gt']         = '>';
$lang->workflowcondition->operatorList['ge']         = '>=';
$lang->workflowcondition->operatorList['lt']         = '<';
$lang->workflowcondition->operatorList['le']         = '<=';
$lang->workflowcondition->operatorList['include']    = '包含';
$lang->workflowcondition->operatorList['notinclude'] = '不包含';

$lang->workflowcondition->placeholder = new stdclass();
$lang->workflowcondition->placeholder->sql = '直接寫入一條SQL查詢語句，只能進行查詢操作。';
