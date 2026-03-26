<?php
$lang->chart->create    = '創建圖表';
$lang->chart->edit      = '編輯圖表';
$lang->chart->browse    = '瀏覽圖表';
$lang->chart->delete    = '刪除圖表';
$lang->chart->design    = '設計圖表';
$lang->chart->order     = '排序';
$lang->chart->save      = '保存';
$lang->chart->cancel    = '取消';
$lang->chart->publish   = '發佈';
$lang->chart->draft     = '存為草稿';
$lang->chart->run       = '執行查詢';
$lang->chart->add       = '添加';
$lang->chart->toDesign  = '進入設計';
$lang->chart->toPreview = '退出設計';

$lang->chart->browseAction = '進入圖表設計';

$lang->chart->id          = 'ID';
$lang->chart->name        = '名稱';
$lang->chart->dataset     = '數據集';
$lang->chart->dataview    = '數據';
$lang->chart->type        = '類型';
$lang->chart->config      = '參數配置';
$lang->chart->desc        = '描述';
$lang->chart->xaxis       = 'X軸';
$lang->chart->yaxis       = 'Y軸';
$lang->chart->rotateXaxis = 'x軸';
$lang->chart->rotateYaxis = 'y軸';
$lang->chart->metric      = '指標';
$lang->chart->column      = '列';
$lang->chart->columnField = '欄位';
$lang->chart->operator    = '條件';
$lang->chart->orderby     = '排序';
$lang->chart->valOrAgg    = '值/彙總';
$lang->chart->value       = '值';
$lang->chart->display     = '顯示名稱';
$lang->chart->filterValue = '篩選值';
$lang->chart->must        = '請選擇';
$lang->chart->split       = '分列顯示';
$lang->chart->draftIcon   = '草稿';
$lang->chart->molecule    = '分子';
$lang->chart->denominator = '分母';
$lang->chart->goal        = '對';
$lang->chart->operate     = '進行';
$lang->chart->condition   = '條件';
$lang->chart->eq          = '=';
$lang->chart->and         = '且';

$lang->chart->chooseField     = '選擇欄位';
$lang->chart->chooseCalc      = '選擇計算方式';
$lang->chart->chooseCondition = '選擇條件';

$lang->chart->denominatorTip['tip']   = '水球圖展示的占比是由分子/分母x100%得出的，可以在分子配置需要比較的部分內容，分母會根據配置自動生成';
$lang->chart->denominatorTip['empty'] = '根據分子配置的內容自動生成';
$lang->chart->denominatorTip['full']  = '%s欄位的%s';

$lang->chart->rotateLabel    = '旋轉';
$lang->chart->rotateLabelTip = '勾選後，該軸的刻度標籤會旋轉角度，防止標籤之間重疊';

$lang->chart->calcList['']      = '';
$lang->chart->calcList['sum']   = '求和';
$lang->chart->calcList['count'] = '計數';

$lang->chart->errorList = new stdclass();
$lang->chart->errorList->cantequal = '%s 取值不能與 %s 相同, 請重新設計';

$lang->chart->pie = new stdclass();
$lang->chart->pie->group  = '標籤';
$lang->chart->pie->metric = '數據';
$lang->chart->pie->stat   = '統計方式';

$lang->chart->cluBarX = new stdclass();
$lang->chart->cluBarX->xaxis = 'X軸';
$lang->chart->cluBarX->yaxis = 'Y軸';
$lang->chart->cluBarX->stat  = '統計方式';

/* Cluster bar X graphs and cluster bar Y graphs are really just a switch between the x and y axes, so use a little ingenuity in the cluster bar Y language terms so that the methods can be reused.*/
/* 簇狀柱形圖和簇狀條形圖其實只是x軸和y軸換了換，所以在簇狀條形圖語言項上使用了點小聰明，這樣方法就可以復用了。*/
$lang->chart->cluBarY = new stdclass();
$lang->chart->cluBarY->yaxis = 'X軸';
$lang->chart->cluBarY->xaxis = 'Y軸';
$lang->chart->cluBarY->stat  = '統計方式';

$lang->chart->radar = new stdclass();
$lang->chart->radar->xaxis = '維度';
$lang->chart->radar->yaxis = '極坐標軸';

$lang->chart->line = $lang->chart->cluBarX;

$lang->chart->stackedBar  = $lang->chart->cluBarX;
$lang->chart->stackedBarY = $lang->chart->cluBarY;

$lang->chart->browseGroup = '維護分組';
$lang->chart->allGroup    = '所有分組';
$lang->chart->noGroup     = '暫時沒有分組';
$lang->chart->groupName   = '分組名稱';
$lang->chart->manageGroup = '維護分組';
$lang->chart->dragAndSort = '拖放排序';
$lang->chart->editGroup   = '編輯分組';
$lang->chart->deleteGroup = '刪除分組';
$lang->chart->childTitle  = '子分組';
$lang->chart->export      = '導出圖表';
$lang->chart->nextStep    = '下一步';
$lang->chart->add         = '添加';

$lang->chart->filter          = '篩選器';
$lang->chart->resultFilter    = '結果篩選器';
$lang->chart->noName          = '未命名';
$lang->chart->filterName      = '名稱';
$lang->chart->showAs          = '顯示為';
$lang->chart->default         = '預設值';
$lang->chart->legendBasicInfo = '基礎信息';
$lang->chart->legendConfig    = '全局設置';
$lang->chart->baseSetting     = '基礎設置';

$lang->chart->dateGroup = array();
$lang->chart->dateGroup['year']  = '年';
$lang->chart->dateGroup['month'] = '月';
$lang->chart->dateGroup['week']  = '周';
$lang->chart->dateGroup['day']   = '日';

$lang->chart->count       = '個數';
$lang->chart->project     = $lang->projectCommon . '名稱';
$lang->chart->customer    = '客戶名稱';
$lang->chart->build       = '軟件版本';
$lang->chart->cusBuild    = '客戶版本';
$lang->chart->period      = '測試周期';
$lang->chart->purpose     = '測試目的';
$lang->chart->stage       = '階段';
$lang->chart->users       = '測試人數';
$lang->chart->testtasks   = '提交測試單';
$lang->chart->comment     = '備註';
$lang->chart->major       = '軟件主測';
$lang->chart->conclusion  = '結論';
$lang->chart->result      = '基本測試結果';
$lang->chart->caseCount   = '用例總數';
$lang->chart->runCount    = '完成數';
$lang->chart->runRate     = '完成率';
$lang->chart->manpower    = '投入人數';
$lang->chart->bugs        = '提交Bug數';
$lang->chart->day         = '天';
$lang->chart->reportDate  = '報告日期';
$lang->chart->hours       = '耗時(小時)';
$lang->chart->pastDays    = '距今天數';

$lang->chart->saveAsDataview = '存為中間表';

$lang->chart->confirmDelete = '您確認要刪除嗎?';
$lang->chart->nameEmpty     = '『名稱』不能為空';
$lang->chart->fieldEmpty    = '欄位不能為空';

$lang->chart->noChartTip    = '配置後，即可顯示圖表';
$lang->chart->noFilterTip   = '暫時沒有篩選器。';
$lang->chart->dataError     = '"%s" 填寫的不是合法的值';
$lang->chart->beginGtEnd    = '開始時間不得大於結束時間。';
$lang->chart->resetSettings = '查詢數據的配置已修改，是否清空圖表和篩選器配置，重新設計。';
$lang->chart->clearSettings = '查詢數據的配置已修改，是否清空圖表和篩選器配置並保存。';
$lang->chart->draftSave     = '已發佈的內容被編輯，將覆蓋，是否繼續?';

$lang->chart->confirm = new stdclass();
$lang->chart->confirm->design  = '該圖表被已發佈的大屏引用，是否繼續？';
$lang->chart->confirm->publish = '該圖表被已發佈的大屏引用，發佈後將在大屏上顯示為修改後的圖表，是否繼續？';
$lang->chart->confirm->draft   = '該圖表被已發佈的大屏引用，存為草稿後將在大屏上顯示為提示信息，是否繼續？';
$lang->chart->confirm->delete  = '該圖表被已發佈的大屏引用，刪除後將在大屏上顯示為提示信息，是否繼續？';

$lang->chart->orderList = array();
$lang->chart->orderList['asc']  = '正序';
$lang->chart->orderList['desc'] = '倒序';

$lang->chart->operatorList = array();
$lang->chart->operatorList['=']       = '=';
$lang->chart->operatorList['!=']      = '!=';
$lang->chart->operatorList['<']       = '<';
$lang->chart->operatorList['>']       = '>';
$lang->chart->operatorList['<=']      = '<=';
$lang->chart->operatorList['>=']      = '>=';
$lang->chart->operatorList['in']      = 'IN';
$lang->chart->operatorList['notin']   = 'NOT IN';
$lang->chart->operatorList['notnull'] = 'IS NOT NULL';
$lang->chart->operatorList['null']    = 'IS NULL';

$lang->chart->dateList = array();
$lang->chart->dateList['day']   = '天';
$lang->chart->dateList['week']  = '周';
$lang->chart->dateList['month'] = '月';
$lang->chart->dateList['year']  = '年';

$lang->chart->designStepNav = array();
$lang->chart->designStepNav['1'] = '查詢數據';
$lang->chart->designStepNav['2'] = '設計圖表';
$lang->chart->designStepNav['3'] = '配置篩選器';
$lang->chart->designStepNav['4'] = '準備發佈';

$lang->chart->nextButton = array();
$lang->chart->nextButton['1'] = '去設計';
$lang->chart->nextButton['2'] = '去配置';
$lang->chart->nextButton['3'] = '去準備';
$lang->chart->nextButton['4'] = '發佈';

$lang->chart->displayList = array();
$lang->chart->displayList['value']   = '顯示值';
$lang->chart->displayList['percent'] = '百分比';

$lang->chart->typeOptions = array();
$lang->chart->typeOptions['user']      = '用戶';
$lang->chart->typeOptions['product']   = '產品';
$lang->chart->typeOptions['project']   = '項目';
$lang->chart->typeOptions['execution'] = '執行';
$lang->chart->typeOptions['dept']      = '部門';

$lang->chart->browseTypeList = $lang->chart->typeList;
$lang->chart->browseTypeList['card']      = '指標卡片';
$lang->chart->browseTypeList['bar']       = '簇狀條形圖';
$lang->chart->browseTypeList['piecircle'] = '環形餅圖';
$lang->chart->browseTypeList['waterpolo'] = '水球圖';
