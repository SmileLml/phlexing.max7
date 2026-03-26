<?php
$lang->milestone->common      =  $lang->projectCommon . '里程碑報告';
$lang->milestone->index       = '報告首頁';
$lang->milestone->title       = '里程碑報告';
$lang->milestone->name        = '里程碑名稱';
$lang->milestone->stage       = '里程碑階段';
$lang->milestone->save        = '保存';

$lang->milestone->indexAction = '里程碑報告';

$lang->milestone->startedWeeks  = '開始周數';
$lang->milestone->finishedWeeks = '結束周數';
$lang->milestone->offset        = '里程碑工期偏差';

$lang->milestone->processCommon =  $lang->projectCommon . '當前進展狀況';
$lang->milestone->process       =  $lang->projectCommon . '進度';
$lang->milestone->executionCost =  $lang->projectCommon . '成本';
$lang->milestone->toNow         = '到目前為止';
$lang->milestone->targetRange   = '目標控制範圍';
$lang->milestone->ge            = '大於等於';
$lang->milestone->le            = '小於等於';
$lang->milestone->analysis      = '分析結果';
$lang->milestone->PV            = '計劃完成的工作(PV)';
$lang->milestone->EV            = '實際完成的工作(EV)';
$lang->milestone->AC            = '實際花費的成本(AC)';
$lang->milestone->SPI           =  $lang->projectCommon . '進度績效(SPI)';
$lang->milestone->CPI           =  $lang->projectCommon . '成本績效(CPI)';
$lang->milestone->SV            = '進度偏差率(SV%)';
$lang->milestone->CV            = '成本偏差率(CV%)';

$lang->milestone->workHours   = '工作量（人時）';
$lang->milestone->allStage    = '工程階段';
$lang->milestone->devHours    = '研發工作量';
$lang->milestone->toHours     = '返工工作量';
$lang->milestone->reviewHours = '評審工作量';
$lang->milestone->qaHours     = '測試工作量';
$lang->milestone->rowSummary  = '工作量小計';
$lang->milestone->rowPercent  = '分佈百分比(%)';
$lang->milestone->colPercent  = '占工作量百分比(%)';
$lang->milestone->colSummary  = '總和';
$lang->milestone->qatoDev     = '測試研發比';

$lang->milestone->executionRisk   = '5.' . $lang->projectCommon . '風險(優先最高的前五項風險)';
$lang->milestone->riskCountermove = '風險對策';
$lang->milestone->riskDescriptio  = '風險描述';
$lang->milestone->riskPossibility = '可能性';
$lang->milestone->riskSeriousness = '嚴重性';
$lang->milestone->riskFactor      = '風險係數';
$lang->milestone->riskMeasures    = '風險對策';
$lang->milestone->riskAccumulate  = '累積的高風險';

$lang->milestone->otherIssue       = '其它問題';
$lang->milestone->issueSolutions   = '問題及解決建議';
$lang->milestone->issueDescription = '問題描述';
$lang->milestone->issuePropose     = '解決建議';

$lang->milestone->demandStatus     = '4.用戶需求狀況分析';
$lang->milestone->storyUnit        = '單位:Item';
$lang->milestone->engineeringStage = '工程階段';
$lang->milestone->rateChange       = '需求變化率';
$lang->milestone->originalStory    = '原始需求數量';
$lang->milestone->modifyNumber     = '變更後需求總數';
$lang->milestone->changeStory      = '變更的需求數';

$lang->milestone->paogressForecast   =  $lang->projectCommon . '進展預測';
$lang->milestone->duration           = '工期(天)';
$lang->milestone->cost               = '成本(人時)';
$lang->milestone->forecastResults    = '預測結果分析';
$lang->milestone->plannedValue       = '計劃值';
$lang->milestone->predictedValue     = '預測值';
$lang->milestone->predictedValueDesc = '計算公式：如果' . $lang->projectCommon . '進度績效(SPI)=0，那麼預測值=0，否則，預測值=計劃值 除以 ' . $lang->projectCommon . '進度績效(SPI)';
$lang->milestone->periodDeviation    = '工期偏差';
$lang->milestone->costDeviation      = '成本偏差';
$lang->milestone->nextStage          = '下一里程碑階段';
$lang->milestone->overallProject     =  $lang->projectCommon . '總體';
$lang->milestone->corrective         = '糾偏措施';
$lang->milestone->timeOverrun        = '總工期將超出:%s天。';
$lang->milestone->costOverrun        = '總成本將超出:%s個單位。';
$lang->milestone->saveOtherProblem   = '保存其他問題';

$lang->milestone->chart = new stdclass();
$lang->milestone->chart->title    = '到目前為止' . $lang->projectCommon . '進展趨勢圖';
$lang->milestone->chart->time     = '第';
$lang->milestone->chart->week     = '周';
$lang->milestone->chart->month    = '月';
$lang->milestone->chart->workhour = '研發工作量分析圖';

$lang->milestone->otherproblem      = '6.其它問題';
$lang->milestone->problemandsuggest = '問題及解決建議';
$lang->milestone->suggest           = '解決建議';
$lang->milestone->needhelp          = '是否需要高層支持？';
$lang->milestone->prodescr          = '問題描述';

$lang->milestone->reviewHoursTip  = '評審工作量 = 評審時長 * 評審人數';
$lang->milestone->toHoursTip      = '返工工作量統計的是研發任務被激活之後，重新消耗的工時';
$lang->milestone->startedWeekTip  = '統計項目開始時間到當前里程碑開始時間的周數';
$lang->milestone->finishedWeekTip = '統計項目開始時間到當前里程碑結束時間的周數';

$lang->milestone->quality = new stdclass();
$lang->milestone->quality->total         = '合計';
$lang->milestone->quality->identify      = '缺陷識別階段';
$lang->milestone->quality->injection     = '缺陷注入階段';
$lang->milestone->quality->scale         = '規模';
$lang->milestone->quality->identifyRate  = '缺陷識別率';
$lang->milestone->quality->injectionRate = '缺陷注入率';

$lang->milestone->options  = '操作';

$lang->milestone->milestoneHelpNotice = <<<EOD
<h2>PV 計劃完成的工作</h2>
計算方式：
<br />1）任務所屬階段是當前里程碑，累加預計工時
<br />2）當前里程碑階段之前的所有階段下的任務，累加預計工時
<p>統計範圍：</p>
1）為避免重複計算，只包含子任務，不包括父任務
<br />2）不包括已刪除的任務
<br />3）不包括已取消的任務
<br />4）不包括已刪除執行中的任務
<h2>EV實際完成的工作</h2>
計算方式：
<br />1）任務所屬階段是當前里程碑：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任務狀態為已完成，累加預計工時
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任務狀態為已關閉且關閉原因為已完成，累加預計工時
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任務狀態為進行中、已暫停，累加 預計工時×完成進度
<br />2）當前里程碑階段之前的所有階段下的任務：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任務狀態為已完成，累加預計工時
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任務狀態為已關閉且關閉原因為已完成，累加預計工時
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任務狀態為進行中、已暫停，累加 預計工時×完成進度
<p>統計範圍：</p>
1）為避免重複計算，只包含子任務，不包括父任務
<br />2）不包括已刪除的任務
<br />3）不包括已取消的任務
<br />4）不包括已刪除執行中的任務
<br />5）消耗工時不為0的任務
<br />6）完成進度=已消耗工時÷(已消耗工時+剩餘工時)
<h2>AC 實際花費（消耗）的成本</h2>
計算方式：
<br />1）當前里程碑中所有消耗的工時
<br />2）當前里程碑階段之前的所有階段下消耗的工時
<p>統計範圍：</p>
1）消耗的工時包括任務、需求、Bug、用例、版本、測試單、問題、風險、文檔、評審的耗時
<br />2）為避免重複計算，只包含子任務，不包括父任務
<br />3）包括已刪除的任務、需求、Bug、用例、版本、測試單、問題、風險、文檔、評審的耗時
<br />4）包括已刪除執行中任務、需求、Bug、用例、版本、測試單、文檔的耗時
<br />5）包括取消的任務、問題、風險的耗時
<h2>SV(%)進度偏差率</h2>
計算方式：SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) 成本偏差率</h2>
計算方式：CV(%) = -1 * (1 - (EV / AC))%
<h2>SPI 進度性能指標</h2>
計算方式：SPI = EV / PV
<h2>CPI成本性能指標</h2>
計算方式：CPI = EV / AC
EOD;
$lang->milestone->soFarHelpNotice = <<<EOD
<h2>PV計劃完成的工作</h2>
計算方式：
<br />1）任務預計截止日期在今天結束時間之前，累加預計工時
<br />2）任務預計開始日期小於今天結束時間，截止日期大於今天結束時間，累加（任務的預計工時 ÷ 任務工期天數）×  任務預計開始到本週結束日期的天數
<p>統計範圍：</p>
1）為避免重複計算，只包含子任務，不包括父任務
<br />2）不包括刪除的任務
<br />3）不包括取消的任務
<br />4）不包括已刪除執行中的任務
<br />5）今天結束時間是今天的23:59:59
<br />6）任務未填寫預計開始日期，預計開始日期預設取任務所屬階段的計劃開始日期
<br />7）任務未填寫預計截止日期，預計截止日期預設取任務所屬階段的計劃完成日期
<br />8）計算公式只計算工作日
<h2>EV實際完成的工作</h2>
計算方式：
<br />1）任務狀態為已完成，累加預計工時
<br />2）任務狀態為已關閉且關閉原因為已完成，累加預計工時
<br />3）任務狀態為進行中、已暫停，累加 預計工時×完成進度
<p>統計範圍：</p>
1）今天結束時間之前，今天結束時間是今天的23:59:59
<br />2）為避免重複計算，只包含子任務，不包括父任務
<br />3）不包括已刪除的任務
<br />4）不包括已取消的任務
<br />5）不包括已刪除執行中的任務
<br />6）消耗工時不為0的任務
<br />7）完成進度=已消耗工時÷(已消耗工時+剩餘工時)
<h2>AC 實際花費（消耗）的成本</h2>
計算方式：
<br />1）累加今天結束時間之前所有消耗的工時
<p>統計範圍：</p>
1）消耗的工時包括任務、需求、Bug、用例、版本、測試單、問題、風險、文檔、評審的耗時
<br />2）為避免重複計算，只包含子任務，不包括父任務
<br />3）包括已刪除的任務、需求、Bug、用例、版本、測試單、問題、風險、文檔、評審的耗時
<br />4）包括已刪除執行中任務、需求、Bug、用例、版本、測試單、文檔的耗時
<br />5）包括取消的任務、問題、風險的耗時
<br />6）今天結束時間是今天的23:59:59
<h2>SV(%)進度偏差率</h2>
計算方式：SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) 成本偏差率</h2>
計算方式：CV(%) = -1 * (1 - (EV / AC))%
<h2>SPI 進度性能指標</h2>
計算方式：SPI = EV / PV
<h2>CPI成本性能指標</h2>
計算方式：CPI = EV / AC
EOD;
