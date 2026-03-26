<?php
/**
 * The report module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: zh-tw.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->report->index     = '統計首頁';
$lang->report->list      = '透視表';
$lang->report->item      = '條目';
$lang->report->value     = '值';
$lang->report->percent   = '百分比';
$lang->report->undefined = '未設定';
$lang->report->project   = $lang->projectCommon;
$lang->report->PO        = 'PO';

$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'F6BD0F';
$lang->report->colors[] = '8BBA00';
$lang->report->colors[] = 'FF8E46';
$lang->report->colors[] = '008E8E';
$lang->report->colors[] = 'D64646';
$lang->report->colors[] = '8E468E';
$lang->report->colors[] = '588526';
$lang->report->colors[] = 'B3AA00';
$lang->report->colors[] = '008ED6';
$lang->report->colors[] = '9D080D';
$lang->report->colors[] = 'A186BE';

$lang->report->assign['noassign'] = '未指派';
$lang->report->assign['assign']   = '已指派';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = "{$lang->execution->common}偏差報表";
$lang->report->productSummary   = $lang->productCommon . '彙總表';
$lang->report->bugCreate        = 'Bug創建表';
$lang->report->bugAssign        = '未解決Bug指派表';
$lang->report->workload         = '員工負載表';
$lang->report->workloadAB       = '工作負載';
$lang->report->bugOpenedDate    = 'Bug創建時間';
$lang->report->beginAndEnd      = '起止時間';
$lang->report->begin            = '起始日期';
$lang->report->end              = '結束日期';
$lang->report->dept             = '部門';
$lang->report->deviationChart   = "{$lang->execution->common}偏差曲綫";

$lang->report->id            = '編號';
$lang->report->execution     = $lang->execution->common;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = '姓名';
$lang->report->bugTotal      = 'Bug';
$lang->report->task          = '任務數';
$lang->report->estimate      = '總預計';
$lang->report->consumed      = '總消耗';
$lang->report->remain        = '剩餘工時';
$lang->report->deviation     = '偏差';
$lang->report->deviationRate = '偏差率';
$lang->report->total         = '總計';
$lang->report->to            = '至';
$lang->report->taskTotal     = "總任務數";
$lang->report->manhourTotal  = "總工時";
$lang->report->validRate     = "有效率";
$lang->report->validRateTips = "方案為已解決或延期/狀態為已解決或已關閉";
$lang->report->unplanned     = "未計劃";
$lang->report->workday       = '每天工時';
$lang->report->diffDays      = '工作日天數';

$lang->report->typeList['default'] = '預設';
$lang->report->typeList['pie']     = '餅圖';
$lang->report->typeList['bar']     = '柱狀圖';
$lang->report->typeList['line']    = '折線圖';

$lang->report->conditions    = '篩選條件：';
$lang->report->closedProduct = '關閉' . $lang->productCommon;
$lang->report->overduePlan   = "過期計劃";

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Bug標題';
$lang->report->taskName     = '任務名稱';
$lang->report->todoName     = '待辦名稱';
$lang->report->testTaskName = '版本名稱';
$lang->report->deadline     = '截止日期';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = '提醒：您有';
$lang->report->mailTitle->bug      = " Bug(%s),";
$lang->report->mailTitle->task     = " 任務(%s),";
$lang->report->mailTitle->todo     = " 待辦(%s),";
$lang->report->mailTitle->testTask = " 測試版本(%s),";

$lang->report->annualData = new stdclass();
$lang->report->annualData->title            = "%s %s年工作彙總";
$lang->report->annualData->exportByZentao   = "由禪道系統導出";
$lang->report->annualData->scope            = "統計範圍";
$lang->report->annualData->allUser          = "所有用戶";
$lang->report->annualData->allDept          = "全公司";
$lang->report->annualData->soFar            = "（%s年）";
$lang->report->annualData->baseInfo         = "基本數據";
$lang->report->annualData->actionData       = "操作數據";
$lang->report->annualData->contributionData = "貢獻數據";
$lang->report->annualData->radar            = "能力雷達圖";
$lang->report->annualData->executions       = "{$lang->executionCommon}數據";
$lang->report->annualData->products         = "{$lang->productCommon}數據";
$lang->report->annualData->stories          = "需求數據";
$lang->report->annualData->tasks            = "任務數據";
$lang->report->annualData->bugs             = "Bug數據";
$lang->report->annualData->cases            = "用例數據";
$lang->report->annualData->statusStat       = "{$lang->SRCommon}/任務/Bug狀態分佈（截止今日）";

$lang->report->annualData->companyUsers     = "公司總人數";
$lang->report->annualData->deptUsers        = "部門人數";
$lang->report->annualData->logins           = "登錄次數";
$lang->report->annualData->actions          = "操作次數";
$lang->report->annualData->contributions    = "貢獻數";
$lang->report->annualData->consumed         = "消耗工時";
$lang->report->annualData->todos            = "待辦數";

$lang->report->annualData->storyStatusStat = "需求狀態分佈";
$lang->report->annualData->taskStatusStat  = "任務狀態分佈";
$lang->report->annualData->bugStatusStat   = "Bug狀態分佈";
$lang->report->annualData->caseResultStat  = "用例結果分佈";
$lang->report->annualData->allStory        = "總需求";
$lang->report->annualData->allTask         = "總任務";
$lang->report->annualData->allBug          = "總Bug";
$lang->report->annualData->undone          = "未完成";
$lang->report->annualData->unresolve       = "未解決";

$lang->report->annualData->storyMonthActions = "每月需求操作情況";
$lang->report->annualData->taskMonthActions  = "每月任務操作情況";
$lang->report->annualData->bugMonthActions   = "每月Bug操作情況";
$lang->report->annualData->caseMonthActions  = "每月用例操作情況";

$lang->report->annualData->executionFields['name']  = "{$lang->executionCommon}名稱";
$lang->report->annualData->executionFields['story'] = "驗收通過的{$lang->SRCommon}數";
$lang->report->annualData->executionFields['task']  = "完成任務數";
$lang->report->annualData->executionFields['bug']   = "修復Bug數";

$lang->report->annualData->productFields['name'] = "{$lang->productCommon}名稱";
$lang->report->annualData->productFields['plan'] = "創建計劃數";
$lang->report->annualData->productFields['epic'] = "創建{$lang->ERCommon}數";
global $config;
if(!empty($config->URAndSR))
{
    $lang->report->annualData->productFields['requirement'] = "創建{$lang->URCommon}數";
}
$lang->report->annualData->productFields['story']  = "創建{$lang->SRCommon}數";
$lang->report->annualData->productFields['closed'] = "關閉{$lang->SRCommon}數";

$lang->report->annualData->objectTypeList['product']     = $lang->productCommon;
$lang->report->annualData->objectTypeList['story']       = "需求";
$lang->report->annualData->objectTypeList['productplan'] = "計劃";
$lang->report->annualData->objectTypeList['release']     = "發佈";
$lang->report->annualData->objectTypeList['project']     = $lang->projectCommon;
$lang->report->annualData->objectTypeList['execution']   = $lang->executionCommon;
$lang->report->annualData->objectTypeList['task']        = '任務';
$lang->report->annualData->objectTypeList['repo']        = '代碼';
$lang->report->annualData->objectTypeList['bug']         = 'Bug';
$lang->report->annualData->objectTypeList['build']       = '構建';
$lang->report->annualData->objectTypeList['testtask']    = '測試單';
$lang->report->annualData->objectTypeList['case']        = '用例';
$lang->report->annualData->objectTypeList['doc']         = '文檔';

$lang->report->annualData->actionList['create']    = '創建';
$lang->report->annualData->actionList['edit']      = '編輯';
$lang->report->annualData->actionList['close']     = '關閉';
$lang->report->annualData->actionList['review']    = '評審';
$lang->report->annualData->actionList['gitCommit'] = 'GIT提交';
$lang->report->annualData->actionList['svnCommit'] = 'SVN提交';
$lang->report->annualData->actionList['start']     = '開始';
$lang->report->annualData->actionList['finish']    = '完成';
$lang->report->annualData->actionList['assign']    = '指派';
$lang->report->annualData->actionList['activate']  = '激活';
$lang->report->annualData->actionList['resolve']   = '解決';
$lang->report->annualData->actionList['run']       = '執行';
$lang->report->annualData->actionList['stop']      = '停止維護';
$lang->report->annualData->actionList['putoff']    = '延期';
$lang->report->annualData->actionList['suspend']   = '掛起';
$lang->report->annualData->actionList['change']    = '變更';
$lang->report->annualData->actionList['pause']     = '暫停';
$lang->report->annualData->actionList['cancel']    = '取消';
$lang->report->annualData->actionList['confirm']   = '確認';
$lang->report->annualData->actionList['createBug'] = '轉Bug';
$lang->report->annualData->actionList['delete']    = '刪除';
$lang->report->annualData->actionList['toAudit']   = '發起審計';
$lang->report->annualData->actionList['audit']     = '審計';

$lang->report->annualData->todoStatus['all']    = '所有待辦';
$lang->report->annualData->todoStatus['undone'] = '未完成';
$lang->report->annualData->todoStatus['done']   = '已完成';

$lang->report->annualData->radarItems['product']   = "{$lang->productCommon}管理";
$lang->report->annualData->radarItems['execution'] = "{$lang->projectCommon}管理";
$lang->report->annualData->radarItems['devel']     = "研發";
$lang->report->annualData->radarItems['qa']        = "測試";
$lang->report->annualData->radarItems['other']     = "其他";

$lang->report->companyRadar        = "公司能力雷達圖";
$lang->report->outputData          = "產出數據";
$lang->report->outputTotal         = "產出總數";
$lang->report->storyOutput         = "需求產出";
$lang->report->planOutput          = "計劃產出";
$lang->report->releaseOutput       = "發佈產出";
$lang->report->executionOutput     = "執行產出";
$lang->report->taskOutput          = "任務產出";
$lang->report->bugOutput           = "Bug產出";
$lang->report->caseOutput          = "用例產出";
$lang->report->bugProgress         = "Bug進展";
$lang->report->productProgress     = "{$lang->productCommon}進展";
$lang->report->executionProgress   = "執行進展";
$lang->report->projectProgress     = "{$lang->projectCommon}進展";
$lang->report->yearProjectOverview = "年度{$lang->projectCommon}總覽";
$lang->report->projectOverview     = "截止目前{$lang->projectCommon}總覽";

$lang->report->contributionCountObject = array();
$lang->report->contributionCountObject['task']        = "任務：創建、完成、關閉、取消、指派";
$lang->report->contributionCountObject['story']       = "研發需求：創建、評審、關閉、指派";
$lang->report->contributionCountObject['requirement'] = "用戶需求：創建、評審、關閉、指派";
$lang->report->contributionCountObject['epic']        = "業務需求：創建、評審、關閉、指派";
$lang->report->contributionCountObject['bug']         = "Bug：創建、解決、關閉、指派";
$lang->report->contributionCountObject['testcase']    = "用例：創建";
$lang->report->contributionCountObject['testtask']    = "測試單：關閉";
$lang->report->contributionCountObject['audit']       = "審計：發起、審計";
$lang->report->contributionCountObject['doc']         = "文檔：創建、編輯";
$lang->report->contributionCountObject['issue']       = "問題：創建、關閉、指派";
$lang->report->contributionCountObject['risk']        = "風險：創建、關閉、指派";
$lang->report->contributionCountObject['qa']          = "QA：創建、解決、關閉、指派";
$lang->report->contributionCountObject['feedback']    = "反饋：創建、評審、指派、關閉";
$lang->report->contributionCountObject['ticket']      = "工單：創建、解決、指派、關閉";

$lang->report->tips = new stdclass();
$lang->report->tips->basic = array();
$lang->report->tips->basic['company'] = '
1.公司總人數：系統所有用戶個數求和，過濾已刪除的用戶。<br>
2.操作次數：系統某年的操作次數求和。<br>
3.消耗工時：系統某年的工時消耗求和。<br>
4.待辦數：系統所有用戶的待辦數據求和。<br>
5.貢獻數：系統所有用戶的貢獻數求和。';
$lang->report->tips->basic['dept'] = '
1.部門人數：某部門所有用戶個數求和，過濾已刪除的用戶。<br>
2.操作次數：某部門用戶在某年的操作次數求和。<br>
3.消耗工時：某部門用戶在某年的工時消耗求和。<br>
4.待辦數：某部門用戶的待辦數據求和。<br>
5.貢獻數：某部門用戶的貢獻數據求和。';
$lang->report->tips->basic['user'] = '
1.登錄次數：某用戶某年的登錄次數求和。<br>
2.操作次數：某用戶在某年的操作次數求和。<br>
3.消耗工時：某用戶在某年的工時消耗求和。<br>
4.待辦數：某用戶的待辦數據求和。<br>
5.貢獻數：某用戶的貢獻數據求和。';

$lang->report->tips->contributionCount['company'] = "全公司在已選年份的貢獻數據，包含：";
$lang->report->tips->contributionCount['dept']    = "已選部門的用戶在已選年份的貢獻數據，包含：";
$lang->report->tips->contributionCount['user']    = "已選用戶在已選年份的貢獻數據，包含：";

$lang->report->tips->contribute['company'] = '不同系統對象在某年的操作次數求和。';
$lang->report->tips->contribute['dept']    = '不同系統對象在某年的操作次數求和，要求是操作用戶屬於選中的部門。';
$lang->report->tips->contribute['user']    = '不同系統對象在某年的操作次數求和，要求是操作用戶屬於選中的用戶。';

$lang->report->tips->radar = '
1.產品管理包含：產品、計劃、需求、發佈相關的操作數據。<br>
2.項目管理包含：項目、迭代、構建、任務相關的操作數據。<br>
3.研發包含：任務、代碼、Bug的解決相關的操作數據。<br>
4.測試包含：Bug的創建、Bug的激活、Bug的關閉、用例、測試單相關的操作數據。<br>
5.其他包含：其他零散的動態數據。';

$lang->report->tips->execution['company'] = '
驗收通過的研發需求數：某年創建的執行中滿足以下條件的研發需求個數求和，要求所處階段為已驗收、已發佈或關閉原因為已完成的研發需求，過濾已刪除的研發需求。<br>
完成任務數：某年創建的執行中任務個數求和，狀態為已完成，過濾已刪除的任務。<br>
修復Bug數：某年創建的執行中狀態為已關閉且解決方案為已解決的Bug數。';
$lang->report->tips->execution['dept'] = '
驗收通過的研發需求數：某年創建的執行中滿足以下條件的研發需求個數求和，要求所處階段為已驗收、已發佈或關閉原因為已完成的研發需求，過濾已刪除的研發需求，創建人為已選中的部門用戶。<br>
完成任務數：某年創建的執行中任務個數求和，狀態為已完成，過濾已刪除的任務，創建人為已選中的部門用戶。<br>
修復Bug數：某年創建的執行中狀態為已關閉且解決方案為已解決的Bug數，創建人為已選中的部門用戶。';
$lang->report->tips->execution['user'] = '
驗收通過的研發需求數：某年創建的執行中滿足以下條件的研發需求個數求和，要求所處階段為已驗收、已發佈或關閉原因為已完成的研發需求，過濾已刪除的研發需求，創建人為已選中的用戶。<br>
完成任務數：某年創建的執行中任務個數求和，狀態為已完成，過濾已刪除的任務，創建人為已選中的用戶。<br>
修復Bug數：某年創建的執行中狀態為已關閉且解決方案為已解決的Bug數，創建人為已選中的用戶。';

$lang->report->tips->product['company'] = '
計劃數：產品中創建時間在某年的計劃數。<br>
創建業務需求數：產品中創建時間在某年的業務需求數。<br>
創建用戶需求數：產品中創建時間在某年的用戶需求數。<br>
創建研發需求數：產品中創建時間在某年的研發需求數。<br>
關閉研發需求數：產品中關閉時間在某年的研發需求數。';
$lang->report->tips->product['dept'] = '
計劃數：產品中創建時間在某年的計劃數，創建人為所選部門中的用戶。<br>
創建業務需求數：產品中創建時間在某年的業務需求數，創建人為所選部門中的用戶。<br>
創建用戶需求數：產品中創建時間在某年的用戶需求數，創建人為所選部門中的用戶。<br>
創建研發需求數：產品中創建時間在某年的研發需求數，創建人為所選部門中的用戶。<br>
關閉研發需求數：產品中關閉時間在某年的研發需求數，關閉人為所選部門中的用戶。';
$lang->report->tips->product['user'] = '
計劃數：產品中創建時間在某年的計劃數，創建人為選中的用戶。<br>
創建業務需求數：產品中創建時間在某年的業務需求數，創建人為選中的用戶。<br>
創建用戶需求數：產品中創建時間在某年的用戶需求數，創建人為選中的用戶。<br>
創建研發需求數：產品中創建時間在某年的研發需求數，創建人為選中的用戶。<br>
關閉研發需求數：產品中關閉時間在某年的研發需求數，關閉人為選中的用戶。';

$lang->report->tips->story['company'] = '
需求結果分佈：不同狀態的需求數據分佈，要求是創建時間為某年。<br>
每月需求操作情況：需求的操作次數求和，要求是操作時間為某年。';
$lang->report->tips->story['dept'] = '
需求狀態分佈：不同狀態的需求數據分佈，要求是創建時間為某年，創建用戶為選中的部門用戶。<br>
每月需求操作情況：需求的操作次數求和，要求是操作時間為某年，操作用戶為選中的部門用戶。';
$lang->report->tips->story['user'] = '
需求狀態分佈：不同狀態的需求數據分佈，要求是創建時間為某年，創建用戶為選中用戶。<br>
每月需求操作情況：需求的操作次數求和，要求是操作時間為某年，操作用戶為選中用戶。';

$lang->report->tips->bug['company'] = '
Bug狀態分佈：不同狀態的Bug數據分佈，要求是創建時間為某年。<br>
每月Bug操作情況：Bug的操作次數求和，要求是操作時間為某年。';
$lang->report->tips->bug['dept'] = '
Bug狀態分佈：不同狀態的Bug數據分佈，要求是創建時間為某年，創建用戶為選中的部門用戶。<br>
每月Bug操作情況：Bug的操作次數求和，要求是操作時間為某年，操作用戶為選中的部門用戶。';
$lang->report->tips->bug['user'] = '
Bug狀態分佈：不同狀態的Bug數據分佈，要求是創建時間為某年，創建用戶為選中用戶。<br>
每月Bug操作情況：Bug的操作次數求和，要求是操作時間為某年，操作用戶為選中用戶。';

$lang->report->tips->case['company'] = '
用例結果分佈：不同執行結果的用例數據分佈，要求是創建時間為某年。<br>
每月用例操作情況：用例的操作次數求和，要求是操作時間為某年。';
$lang->report->tips->case['dept'] = '
用例狀態分佈：不同執行結果的用例數據分佈，要求是創建時間為某年，執行用戶為選中的部門用戶。<br>

每月用例操作情況：用例的操作次數求和，要求是操作時間為某年，操作用戶為選中的部門用戶。';
$lang->report->tips->case['user'] = '
用例狀態分佈：不同執行結果的用例數據分佈，要求是創建時間為某年，執行用戶為選中用戶。<br>
每月用例操作情況：用例的操作次數求和，要求是操作時間為某年，操作用戶為選中用戶。';

$lang->report->tips->task['company'] = '
任務狀態分佈：不同狀態的任務數據分佈，要求是創建時間為某年。<br>
每月任務操作情況：任務的操作次數求和，要求是操作時間為某年。';
$lang->report->tips->task['dept'] = '
任務狀態分佈：不同狀態的任務數據分佈，要求是創建時間為某年，創建用戶為選中的部門用戶。<br>
每月任務操作情況：任務的操作次數求和，要求是操作時間為某年，操作用戶為選中的部門用戶。';
$lang->report->tips->task['user'] = '
任務狀態分佈：不同狀態的任務數據分佈，要求是創建時間為某年，創建用戶為選中用戶。<br>
每月任務操作情況：任務的操作次數求和，要求是操作時間為某年，操作用戶為選中用戶。';
