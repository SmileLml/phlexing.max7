<?php
global $config;

$lang->custom->common               = '自定義';
$lang->custom->id                   = '編號';
$lang->custom->set                  = '自定義配置';
$lang->custom->restore              = '恢復預設';
$lang->custom->key                  = '鍵';
$lang->custom->value                = '值';
$lang->custom->working              = '工作方式';
$lang->custom->hours                = '工時';
$lang->custom->select               = '請選擇流程：';
$lang->custom->branch               = '多分支';
$lang->custom->owner                = '所有者';
$lang->custom->module               = '模組';
$lang->custom->section              = '附加部分';
$lang->custom->lang                 = '所屬語言';
$lang->custom->setPublic            = '設為公共';
$lang->custom->required             = '必填項';
$lang->custom->score                = '積分';
$lang->custom->timezone             = '時區';
$lang->custom->scoreReset           = '重置積分';
$lang->custom->scoreTitle           = '積分功能';
$lang->custom->productName          = $lang->productCommon;
$lang->custom->convertFactor        = '換算係數';
$lang->custom->region               = '區間';
$lang->custom->tips                 = '提示語';
$lang->custom->setTips              = '設置提示語';
$lang->custom->isRange              = '是否目標控制範圍';
$lang->custom->concept              = "{$lang->projectCommon}概念";
$lang->custom->URStory              = "用戶需求";
$lang->custom->SRStory              = "軟件需求";
$lang->custom->epic                 = "史詩";
$lang->custom->default              = "預設";
$lang->custom->scrumStory           = "故事";
$lang->custom->waterfallCommon      = "瀑布";
$lang->custom->buildin              = "系統內置";
$lang->custom->editStoryConcept     = "編輯需求概念";
$lang->custom->setStoryConcept      = "設置需求概念";
$lang->custom->setDefaultConcept    = "設置預設概念";
$lang->custom->browseStoryConcept   = "需求概念列表";
$lang->custom->deleteStoryConcept   = "刪除需求概念";
$lang->custom->ERConcept            = "業需概念";
$lang->custom->URConcept            = "用需概念";
$lang->custom->SRConcept            = "軟需概念";
$lang->custom->reviewRule           = "評審規則";
$lang->custom->switch               = "切換";
$lang->custom->oneUnit              = "一個{$lang->hourCommon}";
$lang->custom->convertRelationTitle = "請先設置{$lang->hourCommon}轉換為%s的換算係數";
$lang->custom->superReviewers       = "超級評審人";
$lang->custom->kanban               = "看板";
$lang->custom->allUsers             = '所有人員';
$lang->custom->account              = '人員';
$lang->custom->role                 = '職位';
$lang->custom->dept                 = '部門';
$lang->custom->code                 = $lang->code;
$lang->custom->setCode              = '是否啟用代號';
$lang->custom->projectCommon        = $lang->projectCommon . '設置';
$lang->custom->executionCommon      = '執行';
$lang->custom->selectDefaultProgram = '請選擇一個預設項目集';
$lang->custom->defaultProgram       = '預設項目集';
$lang->custom->modeManagement       = '模式管理';
$lang->custom->percent              = $lang->stage->percent;
$lang->custom->setPercent           = "是否啟用{$lang->stage->percent}";
$lang->custom->beginAndEndDate      = '起止日期';
$lang->custom->beginAndEndDateRange = '起止日期範圍';
$lang->custom->limitTaskDateAction  = '設置起止日期必填';
$lang->custom->closeSetting         = '關閉設置';
$lang->custom->gradeRule            = '是否允許跨層級細分';

$lang->custom->gradeRuleList['cross']    = '是';
$lang->custom->gradeRuleList['stepwise'] = '否';

$lang->custom->unitList['efficiency'] = '工時/';
$lang->custom->unitList['manhour']    = '人時/';
$lang->custom->unitList['cost']       = '元/小時';
$lang->custom->unitList['hours']      = '小時';
$lang->custom->unitList['days']       = '天';
$lang->custom->unitList['loc']        = 'KLOC';

$lang->custom->tipProgressList['SPI'] = "{$lang->projectCommon}進度績效(SPI)";
$lang->custom->tipProgressList['SV']  = '進度偏差率(SV%)';

$lang->custom->tipCostList['CPI'] = "{$lang->projectCommon}成本績效(CPI)";
$lang->custom->tipCostList['CV']  = '成本偏差率(CV%)';

$lang->custom->tipRangeList[0]  = '否';
$lang->custom->tipRangeList[1]  = '是';

$lang->custom->regionMustNumber    = '區間必須是數字';
$lang->custom->tipNotEmpty         = '提示語不能為空';
$lang->custom->currencyNotEmpty    = '至少選擇一種貨幣';
$lang->custom->defaultNotEmpty     = '預設貨幣不能為空';
$lang->custom->convertRelationTips = "{$lang->hourCommon}轉換為%s後，歷史數據將被統一轉換為%s";
$lang->custom->saveTips            = '點擊保存後，則以當前%s為預設估算單位';

$lang->custom->numberError = '區間必須大於零';
$lang->custom->hoursError  = '可用工時必須在0到24之間！';

$lang->custom->closedProject   = '已關閉' . $lang->projectCommon;
$lang->custom->closedExecution = '已關閉' . $lang->custom->executionCommon;
$lang->custom->closedKanban    = '已關閉' . $lang->custom->kanban;
$lang->custom->closedProduct   = '已關閉' . $lang->productCommon;

$lang->custom->gradeStatusList['enable']  = '正常';
$lang->custom->gradeStatusList['disable'] = '停用';

$lang->custom->block = new stdclass();
$lang->custom->block->fields['closed'] = '關閉的區塊';

$lang->custom->project = new stdClass();
$lang->custom->project->currencySetting    = '貨幣設置';
$lang->custom->project->defaultCurrency    = '預設貨幣';
$lang->custom->project->fields['required'] = $lang->custom->required;
$lang->custom->project->fields['project']  = '關閉設置';
$lang->custom->project->fields['unitList'] = '預算單位';

$lang->custom->execution = new stdClass();
$lang->custom->execution->fields['required']  = $lang->custom->required;
$lang->custom->execution->fields['execution'] = '關閉設置';

$lang->custom->product = new stdClass();
$lang->custom->product->fields['required']           = $lang->custom->required;
$lang->custom->product->fields['browsestoryconcept'] = '需求概念';
$lang->custom->product->fields['product']            = '關閉設置';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['required']         = $lang->custom->required;
$lang->custom->story->fields['categoryList']     = '類型';
$lang->custom->story->fields['priList']          = '優先順序';
$lang->custom->story->fields['sourceList']       = '來源';
$lang->custom->story->fields['reasonList']       = '關閉原因';
$lang->custom->story->fields['stageList']        = '研發階段';
$lang->custom->story->fields['statusList']       = '狀態';
$lang->custom->story->fields['reviewRules']      = '評審規則';
$lang->custom->story->fields['reviewResultList'] = '評審結果';
$lang->custom->story->fields['review']           = '評審流程';

$lang->custom->epic        = clone $lang->custom->story;
$lang->custom->requirement = clone $lang->custom->story;

$lang->custom->task = new stdClass();
$lang->custom->task->fields['required']      = $lang->custom->required;
$lang->custom->task->fields['priList']       = '優先順序';
$lang->custom->task->fields['typeList']      = '類型';
$lang->custom->task->fields['reasonList']    = '關閉原因';
$lang->custom->task->fields['statusList']    = '狀態';
$lang->custom->task->fields['limitTaskDate'] = '起止日期';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['required']       = $lang->custom->required;
$lang->custom->bug->fields['priList']        = '優先順序';
$lang->custom->bug->fields['severityList']   = '嚴重程度';
$lang->custom->bug->fields['osList']         = '操作系統';
$lang->custom->bug->fields['browserList']    = '瀏覽器';
$lang->custom->bug->fields['typeList']       = '類型';
$lang->custom->bug->fields['resolutionList'] = '解決方案';
$lang->custom->bug->fields['statusList']     = '狀態';
$lang->custom->bug->fields['longlife']       = '久未處理天數';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['required']   = $lang->custom->required;
$lang->custom->testcase->fields['priList']    = '優先順序';
$lang->custom->testcase->fields['typeList']   = '類型';
$lang->custom->testcase->fields['stageList']  = '階段';
$lang->custom->testcase->fields['resultList'] = '執行結果';
$lang->custom->testcase->fields['statusList'] = '狀態';
$lang->custom->testcase->fields['review']     = '評審流程';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['required']   = $lang->custom->required;
$lang->custom->testtask->fields['statusList'] = '狀態';
$lang->custom->testtask->fields['typeList']   = '測試類型';
$lang->custom->testtask->fields['priList']    = '優先順序';

$lang->custom->testreport = new stdClass();
$lang->custom->testreport->fields['required'] = $lang->custom->required;

$lang->custom->caselib = new stdClass();
$lang->custom->caselib->fields['required'] = $lang->custom->required;

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = '優先順序';
$lang->custom->todo->fields['typeList']   = '類型';
$lang->custom->todo->fields['statusList'] = '狀態';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['required']     = $lang->custom->required;
$lang->custom->user->fields['roleList']     = '職位';
$lang->custom->user->fields['statusList']   = '狀態';
$lang->custom->user->fields['contactField'] = '可用聯繫方式';
$lang->custom->user->fields['deleted']      = '列出已刪除用戶';

$lang->custom->currentLang = '適用當前語言';
$lang->custom->allLang     = '適用所有語言';

$lang->custom->confirmRestore = '是否要恢復預設配置？';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice     = '控制以上欄位在用戶相關頁面是否顯示，留空則全部顯示';
$lang->custom->notice->canNotAdd           = '該項參與運算，不提供自定義添加功能';
$lang->custom->notice->forceReview         = "指定人提交的%s必須評審。";
$lang->custom->notice->forceNotReview      = "指定人提交的%s不需要評審。";
$lang->custom->notice->longlife            = 'Bug列表頁面的久未處理標籤中，列出設置天數之前未處理的Bug。';
$lang->custom->notice->invalidNumberKey    = '鍵值應為不大於255的數字';
$lang->custom->notice->invalidStringKey    = '鍵值應當為大小寫英文字母、數字或下劃線的組合';
$lang->custom->notice->cannotSetTimezone   = 'date_default_timezone_set方法不存在或禁用，不能設置時區。';
$lang->custom->notice->noClosedBlock       = '沒有永久關閉的區塊';
$lang->custom->notice->required            = '頁面提交時，選中的欄位必填';
$lang->custom->notice->conceptResult       = '我們已經根據您的選擇為您設置了<b> %s-%s </b>模式，使用<b>%s</b> + <b> %s</b>。';
$lang->custom->notice->conceptPath         = '您可以在：後台 -> 自定義 -> 流程頁面修改。';
$lang->custom->notice->readOnlyOfProduct   = '禁止修改後，已關閉' . $lang->productCommon . '下的' . $lang->SRCommon . '、Bug、用例、日誌、發佈、計劃、構建都禁止修改。';
$lang->custom->notice->readOnlyOfProject   = "禁止修改後，已關閉{$lang->projectCommon}下的數據將禁止修改：<br/>
1. 產品型{$lang->projectCommon}啟用{$lang->custom->executionCommon}：已關閉項目下的{$lang->custom->executionCommon}、需求、設計、評審、評審問題、基線、文檔、構建、發佈、日誌、測試單、測試報告、過程裁剪、調研、估算、問題、風險、機會、會議、質量保證計劃、不符合項等禁止修改。<br/>
2. 產品型{$lang->projectCommon}不啟用{$lang->custom->executionCommon}：已關閉項目下的任務、需求、構建、發佈、日誌、測試單、測試報告、文檔等禁止修改。<br/>
3. 項目型{$lang->projectCommon}啟用{$lang->custom->executionCommon}：已關閉項目下的執行、需求、設計、評審、評審問題、基線、Bug、用例、測試單、測試報告、文檔、構建、發佈、日誌、過程裁剪、調研、估算、問題、風險、機會、會議、質量保證計劃、不符合項等禁止修改。<br/>
4. 項目型{$lang->projectCommon}不啟用{$lang->custom->executionCommon}：已關閉項目下的任務、需求、Bug、用例、測試單、測試報告、文檔、構建、發佈、日誌等禁止修改。";
if(in_array($config->edition, array('open', 'biz')))
{
    $lang->custom->notice->readOnlyOfExecution = "禁止修改後，已關閉{$lang->custom->executionCommon}下的任務、日誌、構建、測試單、測試報告、文檔以及關聯需求等都禁止修改。";
}
else
{
    $lang->custom->notice->readOnlyOfExecution = "禁止修改後，已關閉{$lang->custom->executionCommon}下的任務、日誌、構建、測試單、測試報告、文檔、問題、風險、QA、會議以及關聯需求等都禁止修改。";
}
$lang->custom->notice->readOnlyOfKanban    = "禁止修改後，已關閉{$lang->custom->kanban}下的卡片以及相關設置都禁止修改。";
$lang->custom->notice->URSREmpty           = '自定義需求名稱不能為空！';
$lang->custom->notice->valueEmpty          = '值不能為空！';
$lang->custom->notice->confirmDelete       = '您確定要刪除嗎？';
$lang->custom->notice->confirmReviewCase   = '是否將待評審的用例修改為正常狀態？';
$lang->custom->notice->storyReviewTip      = '按人員、職位、部門勾選後，取所有人員的並集。';
$lang->custom->notice->selectAllTip        = '勾選所有人員後，會清空並置灰評審人員，同時隱藏職位、部門。';
$lang->custom->notice->repeatKey           = '%s鍵重複';
$lang->custom->notice->readOnlyOfCode      = "代號是一種管理話術，主要便于保密或作為別名存在。啟用代號管理後，系統中的{$lang->productCommon}、{$lang->projectCommon}、執行在創建、編輯、詳情、列表等頁面均會展示代號信息。";
$lang->custom->notice->readOnlyOfPercent   = "工作量占比用於劃分{$lang->projectCommon}中存在多個階段時的工作量的占比，同一級階段的百分比之和最高為100%。啟用工作量占比後，系統中的瀑布{$lang->projectCommon}和融合瀑布{$lang->projectCommon}模型中設置階段時需要維護階段的工作量占比。";
$lang->custom->notice->gradeRule           = '跨層細分：可從任意需求層級創建需求，並支持跨層級關聯父子關係。例如：您可以在第一層需求下直接創建第三層需求。';

$lang->custom->notice->indexPage['product'] = "從8.2版本起增加了產品主頁視圖，是否預設進入產品主頁？";
$lang->custom->notice->indexPage['project'] = "從8.2版本起增加了{$lang->projectCommon}主頁視圖，是否預設進入{$lang->projectCommon}主頁？";
$lang->custom->notice->indexPage['qa']      = "從8.2版本起增加了測試主頁視圖，是否預設進入測試主頁？";

$lang->custom->notice->invalidStrlen['ten']        = '鍵的長度必須小於10個字元！';
$lang->custom->notice->invalidStrlen['fifteen']    = '鍵的長度必須小於15個字元！';
$lang->custom->notice->invalidStrlen['twenty']     = '鍵的長度必須小於20個字元！';
$lang->custom->notice->invalidStrlen['thirty']     = '鍵的長度必須小於30個字元！';
$lang->custom->notice->invalidStrlen['twoHundred'] = '鍵的長度必須小於225個字元！';

$lang->custom->storyReview    = '評審流程';
$lang->custom->forceReview    = '強制評審';
$lang->custom->forceNotReview = '不需要評審';
$lang->custom->reviewList[1]  = '開啟';
$lang->custom->reviewList[0]  = '關閉';

$lang->custom->deletedList[1] = '列出';
$lang->custom->deletedList[0] = '不列出';

$lang->custom->setHours       = '工時設置';
$lang->custom->setWeekend     = '休息日設置';
$lang->custom->setHoliday     = '節假日設置';
$lang->custom->workingHours   = '每天可用工時';
$lang->custom->weekendRole    = '規則設置';
$lang->custom->weekendList[1] = '單休';
$lang->custom->weekendList[2] = '雙休';
$lang->custom->restDayList[6] = '周六休息';
$lang->custom->restDayList[0] = '周天休息';

global $config;
$lang->custom->sprintConceptList[0] = "項目 產品 迭代";
$lang->custom->sprintConceptList[1] = "項目 產品 衝刺";

$lang->custom->workingList['full'] = '完整研發管理工具';

$lang->custom->menuTip           = '點擊顯示或隱藏導航條目，拖拽來更改顯示順序。';
$lang->custom->saveFail          = '保存失敗！';
$lang->custom->page              = '頁面';
$lang->custom->usage             = '使用場景';
$lang->custom->selectUsage       = '請選擇使用模式';
$lang->custom->useLight          = '使用輕量管理模式';
$lang->custom->useALM            = '使用全生命周期管理模式';
$lang->custom->currentModeTips   = '您當前使用的是%s, 您可以切換到%s';
$lang->custom->changeModeTips    = '您確定要切換到%s嗎？';
$lang->custom->selectProgramTips = "切換到輕量管理模式後，為確保資料結構一致，需要選擇一個項目集作為預設項目集，後續新增的{$lang->productCommon}和{$lang->projectCommon}數據都關聯在這個預設的項目集下。";

$lang->custom->modeList['light'] = '輕量級管理模式';
$lang->custom->modeList['ALM']   = '全生命周期管理模式';
$lang->custom->modeList['PLM']   = 'IPD整合產品開發模式';

$lang->custom->modeIntroductionList['light'] = "提供了{$lang->projectCommon}管理的核心功能，適用於小型研發團隊";
$lang->custom->modeIntroductionList['ALM']   = '概念更加完整、嚴謹，功能更加豐富，適用於中大型研發團隊';

$lang->custom->features['program']              = '項目集';
$lang->custom->features['productRR']            = "{$lang->productCommon}-研發需求";
$lang->custom->features['productUR']            = "{$lang->productCommon}-用戶需求";
$lang->custom->features['productER']            = "{$lang->productCommon}-業務需求";
$lang->custom->features['productLine']          = "{$lang->productCommon}-產品綫";
$lang->custom->features['projectScrum']         = "{$lang->projectCommon}-敏捷模型";
$lang->custom->features['projectWaterfall']     = "{$lang->projectCommon}-瀑布模型";
$lang->custom->features['projectKanban']        = "{$lang->projectCommon}-看板模型";
$lang->custom->features['projectAgileplus']     = "{$lang->projectCommon}-融合敏捷模型";
$lang->custom->features['projectWaterfallplus'] = "{$lang->projectCommon}-融合瀑布模型";
$lang->custom->features['execution']            = '執行';
$lang->custom->features['qa']                   = '測試';
$lang->custom->features['devops']               = 'DevOps';
$lang->custom->features['kanban']               = '看板';
$lang->custom->features['doc']                  = '文檔';
$lang->custom->features['report']               = 'BI';
$lang->custom->features['system']               = '組織';
$lang->custom->features['assetlib']             = '資產庫';
$lang->custom->features['oa']                   = '辦公';
$lang->custom->features['ops']                  = '運維';
$lang->custom->features['feedback']             = '反饋';
$lang->custom->features['traincourse']          = '學堂';
$lang->custom->features['workflow']             = '工作流';
$lang->custom->features['admin']                = '後台';
$lang->custom->features['vision']               = '研發綜合界面、運營管理界面';
$lang->custom->features['ai']                   = 'AI';

$lang->custom->needClosedFunctions['waterfall']     = "瀑布{$lang->projectCommon}";
$lang->custom->needClosedFunctions['waterfallplus'] = "融合瀑布{$lang->projectCommon}";
$lang->custom->needClosedFunctions['URStory']       = '用戶需求';
if($config->edition == 'max') $lang->custom->needClosedFunctions['assetLib'] = '資產庫';

$lang->custom->scoreStatus[1] = '開啟';
$lang->custom->scoreStatus[0] = '關閉';

$lang->custom->CRProduct[1] = '允許修改';
$lang->custom->CRProduct[0] = '禁止修改';

$lang->custom->CRProject[1] = '允許修改';
$lang->custom->CRProject[0] = '禁止修改';

$lang->custom->CRExecution[1] = '允許修改';
$lang->custom->CRExecution[0] = '禁止修改';

$lang->custom->CRKanban[1] = '允許修改';
$lang->custom->CRKanban[0] = '禁止修改';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = '計劃';
$lang->custom->moduleName['execution']   = $lang->custom->executionCommon;

$lang->custom->conceptQuestions['overview']   = "下述哪種組合方式更適合您公司的管理現狀？";
$lang->custom->conceptQuestions['URAndSR']    = "是否啟用{$lang->URCommon}和{$lang->SRCommon}概念？";
$lang->custom->conceptQuestions['storypoint'] = "您公司是在使用以下哪種單位來做規模估算？";

$lang->custom->conceptOptions             = new stdclass;
$lang->custom->conceptOptions->story      = array();
$lang->custom->conceptOptions->story['0'] = '需求';
$lang->custom->conceptOptions->story['1'] = '故事';

$lang->custom->conceptOptions->URAndSR = array();
$lang->custom->conceptOptions->URAndSR['1'] = '是';
$lang->custom->conceptOptions->URAndSR['0'] = '否';

$lang->custom->conceptOptions->hourPoint      = array();
$lang->custom->conceptOptions->hourPoint['0'] = '工時';
$lang->custom->conceptOptions->hourPoint['1'] = '故事點';
$lang->custom->conceptOptions->hourPoint['2'] = '功能點';

$lang->custom->scrum = new stdclass();
$lang->custom->scrum->setConcept = "設置{$lang->projectCommon}概念";

$lang->custom->reviewRules['allpass']  = '全部通過通過';
$lang->custom->reviewRules['halfpass'] = '半數以上通過通過';

$lang->custom->limitTaskDate['0'] = '不限制';
$lang->custom->limitTaskDate['1'] = '限定在所屬執行起止日期範圍內';

$lang->custom->setDate = new stdClass();
$lang->custom->setDate->fields['hours']   = $lang->custom->setHours;
$lang->custom->setDate->fields['weekend'] = $lang->custom->setWeekend;
$lang->custom->setDate->fields['holiday'] = $lang->custom->setHoliday;
