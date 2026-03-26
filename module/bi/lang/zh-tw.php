<?php
$lang->bi->binNotExists        = 'DuckDB 二進制檔案不存在。';
$lang->bi->tmpPermissionDenied = 'DuckDB tmp 目錄沒有權限, 需要修改目錄 "%s" 的權限。<br />命令為：<br />chmod 755 -R %s。';

$lang->bi->acl = '訪問控制';

$lang->bi->driver = '資料庫類型';
$lang->bi->driverList = array();
$lang->bi->driverList['mysql'] = 'MySQL';

$lang->bi->query      = '查詢';
$lang->bi->sqlQuery   = 'SQL語句查詢';
$lang->bi->sqlBuilder = 'SQL構建器';
$lang->bi->dictionary = '數據字典';

$lang->bi->toggleSqlText    = '手寫SQL語句';
$lang->bi->toggleSqlBuilder = 'SQL構建器';

$lang->bi->builderStepList = array();
$lang->bi->builderStepList['table'] = '查詢數據表';
$lang->bi->builderStepList['field'] = '選擇查詢欄位';
$lang->bi->builderStepList['func']  = '新增日期函數欄位';
$lang->bi->builderStepList['where'] = '確定性查詢條件';
$lang->bi->builderStepList['query'] = '動態查詢篩選器';
$lang->bi->builderStepList['group'] = '設置分組並聚合';

$lang->bi->stepTableTitle = '選擇要查詢的數據表';
$lang->bi->stepTableTip   = '請選擇要查詢的數據表，用於指定您想要從哪張表或哪些表中檢索數據。';
$lang->bi->changeModeTip  = '此次切換將清空當前構建器的配置、並將構建的SQL語句回顯到手寫SQL語句中；且不可再切換回SQL構建器模式，是否繼續？';
$lang->bi->modeDisableTip = '手寫SQL語句靈活性較高，暫不支持切回SQL構建器模式';

$lang->bi->fromTable     = '主表';
$lang->bi->leftTable     = '左連接';
$lang->bi->joinCondition = '連接條件為';
$lang->bi->joinTable     = '%s的';
$lang->bi->of            = '的';
$lang->bi->do            = '對';
$lang->bi->set           = '進行';
$lang->bi->funcAs        = '運算，對結果重命名為';
$lang->bi->enable        = '啟用';
$lang->bi->previewSql    = '預覽構建的sql語句';
$lang->bi->addFunc       = '新增日期函數欄位';
$lang->bi->emptyFuncs    = '暫未新增函數欄位。';
$lang->bi->addWhere      = '添加組';
$lang->bi->emptyWheres   = '暫未添加確定性查詢條件。';
$lang->bi->checkAll      = '全選';
$lang->bi->cancelAll     = '取消全選';
$lang->bi->groupField    = '分組欄位';
$lang->bi->aggField      = '聚合欄位';
$lang->bi->allFields     = '已選/新增欄位';
$lang->bi->addQuery      = '添加動態查詢篩選器';
$lang->bi->emptyQuerys   = '暫未添加動態查詢篩選器。';
$lang->bi->emptySelect   = '請至少選擇一個欄位。';
$lang->bi->length        = '長度';

$lang->bi->allFieldsTip  = '已選的查詢欄位和新增的函數欄位。';
$lang->bi->groupFieldTip = '使用分組欄位對查詢結果進行分組。';
$lang->bi->aggFieldTip   = '對聚合欄位配置聚合函數運算，從而得到不同分組下的彙總數據。';

$lang->bi->aggTipA = '對 %s 進行';
$lang->bi->aggTipB = '運算，對結果重命名為 %s';

$lang->bi->fieldTypeList = array();
$lang->bi->fieldTypeList['string'] = '字元串';
$lang->bi->fieldTypeList['number'] = '數值';
$lang->bi->fieldTypeList['date']   = '日期';
$lang->bi->fieldTypeList['option'] = '選項';
$lang->bi->fieldTypeList['object'] = '對象';

$lang->bi->aggList = array();
$lang->bi->aggList['count']         = '計數';
$lang->bi->aggList['countdistinct'] = '去重後計數';
$lang->bi->aggList['avg']           = '平均值';
$lang->bi->aggList['sum']           = '求和';
$lang->bi->aggList['max']           = '最大值';
$lang->bi->aggList['min']           = '最小值';

$lang->bi->whereGroupTitle  = '第%s組確定性查詢條件';
$lang->bi->addWhereGroup    = '添加組';
$lang->bi->removeWhereGroup = '刪除組';

$lang->bi->selectTableTip = '請選擇數據表';
$lang->bi->selectFieldTip = '請選擇欄位';
$lang->bi->selectFuncTip  = '請選擇函數';
$lang->bi->selectInputTip = '請輸入';

$lang->bi->funcList = array();
$lang->bi->funcList['date']  = '提取日期';
$lang->bi->funcList['month'] = '提取月份';
$lang->bi->funcList['year']  = '提取年份';

$lang->bi->whereOperatorList = array();
$lang->bi->whereOperatorList['and'] = '且';
$lang->bi->whereOperatorList['or']  = '或';

$lang->bi->whereItemOperatorList = array();
$lang->bi->whereItemOperatorList['=']     = '=';
$lang->bi->whereItemOperatorList['!=']    = '!=';
$lang->bi->whereItemOperatorList['>']     = '>';
$lang->bi->whereItemOperatorList['>=']    = '>=';
$lang->bi->whereItemOperatorList['<']     = '<';
$lang->bi->whereItemOperatorList['<=']    = '<=';
$lang->bi->whereItemOperatorList['in']    = '包含';
$lang->bi->whereItemOperatorList['notIn'] = '不包含';
$lang->bi->whereItemOperatorList['like']  = '模糊匹配';

$lang->bi->queryFilterFormHeader = array();
$lang->bi->queryFilterFormHeader['table']   = '選擇表';
$lang->bi->queryFilterFormHeader['field']   = '選擇欄位';
$lang->bi->queryFilterFormHeader['name']    = '篩選器名稱';
$lang->bi->queryFilterFormHeader['type']    = '篩選器類型';
$lang->bi->queryFilterFormHeader['default'] = '預設值';

$lang->bi->emptyError     = '不能為空';
$lang->bi->duplicateError = '存在重複';
$lang->bi->noSql          = '通過SQL構建器配置的SQL查詢語句，將展示在此處';

$lang->bi->stepFieldTitle = '選擇查詢表中的欄位';
$lang->bi->stepFieldTip   = '選擇查詢表中的欄位用於從已選擇的查詢表中獲取所需的數據。';
$lang->bi->leftTableTip   = '在SQL中，左連接（Left join）是一種表與表之間的關聯操作，它返回左表中所有記錄以及與右表中匹配的記錄。左連接根據指定的條件從兩個表中組合數據，其中左表是查詢的主表，而右表是要連接的表。具體請看禪道使用手冊7.14.13.1聯表查詢常用方式：左連接。';

$lang->bi->stepFuncTitle = '新增日期函數欄位';
$lang->bi->stepFuncTip   = '您可以對查詢表中的欄位設置函數，以在查詢結果中新增一列您期望的數據。';

$lang->bi->stepWhereTitle = '添加確定性查詢條件';
$lang->bi->stepWhereTip   = '1.確定性查詢條件用於過濾不滿足要求的數據，您可以按需添加查詢條件，以獲取相應的查詢結果。2.使用=、!=、>、>=、<、<=、和模糊匹配(like)條件符號時，請在符號右側輸入框內輸入相應條件值。3.使用包含(in)條件符號時，請在符號右側輸入框內輸入一個或多個條件值，並用英文逗號隔開，例如：任務類型 包含(in) 開發,測試。';

$lang->bi->stepQueryTitle = '添加動態查詢篩選器';
$lang->bi->stepQueryTip   = '動態查詢篩選器是通過在 SQL 中插入變數實現動態查詢的篩選方式，第三步配置的結果篩選器是對SQL查詢結果進行進一步篩選。';

$lang->bi->stepGroupTitle = '設置分組並聚合';
$lang->bi->stepGroupTip   = '設置分組用於對查詢結果按照指定的列值進行分組，並對分組後的其他數據應用聚合函數來獲取彙總信息。';
$lang->bi->emptyGroups    = '啟用“設置分組並聚合”後，系統會將您選擇的查詢欄位與新增的函數欄位自動展示在此；<br />您可依次設置分組欄位，以及其他需要進行聚合運算的欄位。';
