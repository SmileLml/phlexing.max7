<?php
$lang->pivot->testcase          = '用例統計表';
$lang->pivot->casesrun          = '用例執行統計表';
$lang->pivot->build             = '版本統計表';
$lang->pivot->workSummary       = '任務完成彙總表';
$lang->pivot->bugSummary        = 'Bug解決彙總表';
$lang->pivot->roadmap           = $lang->productCommon . '路線圖表';
$lang->pivot->storyLinkedBug    = $lang->SRCommon . '關聯Bug彙總表';
$lang->pivot->workAssignSummary = '任務指派彙總表';
$lang->pivot->bugAssignSummary  = 'Bug指派彙總表';
$lang->pivot->productInvest     = $lang->productCommon . '投入表';

$lang->pivot->taskFinishedDate  = '任務完成時間';
$lang->pivot->taskConsumed      = '任務總消耗';
$lang->pivot->projectConsumed   = $lang->projectCommon . '總消耗';
$lang->pivot->executionConsumed = '執行總消耗';
$lang->pivot->userConsumed      = '用戶總消耗';
$lang->pivot->bugResolvedDate   = 'Bug解決日期';
$lang->pivot->bugAssignedDate   = 'Bug指派日期';
$lang->pivot->taskAssignedDate  = '任務指派時間';
$lang->pivot->projects          = $lang->projectCommon . '數';
$lang->pivot->storyConsumed     = $lang->SRCommon . '消耗工時';
$lang->pivot->taskConsumed      = '任務消耗工時';
$lang->pivot->bugConsumed       = 'Bug消耗工時';
$lang->pivot->caseConsumed      = '用例消耗工時';
$lang->pivot->totalConsumed     = '總消耗工時';

if(helper::hasFeature('product_roadmap')) $lang->pivotList->product->lists[20] = $lang->productCommon . '路線圖表|pivot|roadmap';
$lang->pivotList->product->lists[25] = $lang->productCommon . '投入表|pivot|productInvest';
$lang->pivotList->test->lists[20]    = '用例統計表|pivot|testcase';
$lang->pivotList->test->lists[25]    = '用例執行統計表|pivot|casesrun';
$lang->pivotList->test->lists[30]    = '版本統計表|pivot|build';
$lang->pivotList->test->lists[35]    = $lang->SRCommon . '關聯Bug彙總表|pivot|storylinkedbug';
$lang->pivotList->staff->lists[20]   = '任務完成彙總表|pivot|worksummary';
$lang->pivotList->staff->lists[25]   = '任務指派彙總表|pivot|workAssignSummary';
$lang->pivotList->staff->lists[30]   = 'Bug解決彙總表|pivot|bugsummary';
$lang->pivotList->staff->lists[35]   = 'Bug指派彙總表|pivot|bugAssignSummary';

$lang->pivot->product    = $lang->productCommon . '名稱';
$lang->pivot->moduleName = '模組名稱';
$lang->pivot->buildTitle = '測試版本';
$lang->pivot->severity   = '嚴重程度';
$lang->pivot->bugType    = 'Bug類型';
$lang->pivot->bugStatus  = 'Bug狀態';
$lang->pivot->delay      = '延期';
$lang->pivot->day        = '天';
$lang->pivot->plan       = '計劃';
$lang->pivot->future     = '待定';

$lang->pivot->case           = new stdclass();
$lang->pivot->case->total    = '總用例數';
$lang->pivot->case->run      = '總執行數';
$lang->pivot->case->passRate = '用例通過率';
$lang->pivot->case->name     = '名稱';

$lang->pivot->bugTypeList['codeerror']   = '代碼';
$lang->pivot->bugTypeList['interface']   = '界面';
$lang->pivot->bugTypeList['config']      = '配置';
$lang->pivot->bugTypeList['install']     = '安裝';
$lang->pivot->bugTypeList['security']    = '安全';
$lang->pivot->bugTypeList['performance'] = '性能';
$lang->pivot->bugTypeList['standard']    = '標準';
$lang->pivot->bugTypeList['automation']  = '腳本';
$lang->pivot->bugTypeList['others']      = '其他';

$lang->pivot->bug         = new stdclass();
$lang->pivot->bug->total  = '總計';
$lang->pivot->bug->title  = 'Bug標題';
$lang->pivot->bug->story  = $lang->SRCommon;
$lang->pivot->bug->status = '狀態';

$lang->pivot->caseRunDesc = '分別按照用例的執行次數和結果進行統計';
