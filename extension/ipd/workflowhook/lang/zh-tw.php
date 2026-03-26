<?php
$lang->workflowhook->common = '工作流擴展動作';
$lang->workflowhook->browse = '瀏覽擴展動作';
$lang->workflowhook->create = '添加擴展動作';
$lang->workflowhook->edit   = '編輯擴展動作';
$lang->workflowhook->delete = '刪除擴展動作';

$lang->workflowhook->condition = '觸發條件';
$lang->workflowhook->hook      = '擴展動作';
$lang->workflowhook->type      = '條件類型';
$lang->workflowhook->result    = '條件結果';
$lang->workflowhook->sql       = 'SQL';
$lang->workflowhook->varName   = '變數名';
$lang->workflowhook->varValue  = '變數值';
$lang->workflowhook->action    = '操作';
$lang->workflowhook->table     = '表';
$lang->workflowhook->field     = '欄位';
$lang->workflowhook->value     = '值';
$lang->workflowhook->where     = '條件';
$lang->workflowhook->message   = '提示信息';

$lang->workflowhook->typeList['data'] = '以數據作為校驗方式';
$lang->workflowhook->typeList['sql']  = '以SQL語句作為校驗方式';

$lang->workflowhook->resultList['empty']    = '查詢結果為空或0時通過校驗';
$lang->workflowhook->resultList['notempty'] = '查詢結果不為空和0時通過校驗';

$lang->workflowhook->logicalOperatorList['and'] = '並且';
$lang->workflowhook->logicalOperatorList['or']  = '或者';

$lang->workflowhook->actionList['insert'] = '新增';
$lang->workflowhook->actionList['update'] = '修改';
$lang->workflowhook->actionList['delete'] = '刪除';

$lang->workflowhook->options['currentUser'] = '當前用戶';
$lang->workflowhook->options['currentDept'] = '當前部門';
$lang->workflowhook->options['deptManager'] = '部門經理';
$lang->workflowhook->options['actor']       = '操作人';
$lang->workflowhook->options['today']       = '操作日期';
$lang->workflowhook->options['now']         = '操作時間';
$lang->workflowhook->options['formula']     = '公式';

$lang->workflowhook->placeholder = new stdclass();
$lang->workflowhook->placeholder->sql = '直接寫入一條SQL查詢語句，只能進行查詢操作。';

$lang->workflowhook->error = new stdclass();
$lang->workflowhook->error->wrongSQL = 'SQL語句有錯！錯誤：';

/* Formula */
$lang->workflowhook->formula = new stdclass();
$lang->workflowhook->formula->common    = '公式';
$lang->workflowhook->formula->target    = '計算對象';
$lang->workflowhook->formula->operator  = '計算符號';
$lang->workflowhook->formula->numbers   = '數字';
$lang->workflowhook->formula->clearLast = '刪除';
$lang->workflowhook->formula->clearAll  = '清空';
$lang->workflowhook->formula->set       = '設置公式';

$lang->workflowhook->formula->functions['sum']     = '%s_%s_合計值';
$lang->workflowhook->formula->functions['average'] = '%s_%s_平均值';
$lang->workflowhook->formula->functions['max']     = '%s_%s_最大值';
$lang->workflowhook->formula->functions['min']     = '%s_%s_最小值';
$lang->workflowhook->formula->functions['count']   = '%s_%s_計數';

$lang->workflowhook->formula->error = new stdclass();
$lang->workflowhook->formula->error->empty = '請為公式設置計算方式';
$lang->workflowhook->formula->error->error = '公式存在錯誤，請檢查後再保存';
