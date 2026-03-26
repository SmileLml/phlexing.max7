<?php
$lang->doc->templateType         = '模板類型';
$lang->doc->template             = '模板';
$lang->doc->selectTemplate       = '選擇模板';
$lang->doc->createByTemplate     = '由模板創建';
$lang->doc->assignedTo           = '指派給';
$lang->doc->approver             = '審批人';
$lang->doc->importToPracticeLib  = '導入最佳實踐庫';
$lang->doc->importToComponentLib = '導入組件庫';
$lang->doc->practiceLib          = '最佳實踐庫';
$lang->doc->componentLib         = '組件庫';
$lang->doc->isExistPracticeLib   = '最佳實踐庫中已有此條記錄，請勿重複提交！';
$lang->doc->isExistComponentLib  = '組件庫中已有此條記錄，請勿重複提交！';
$lang->doc->replaceContentTip    = '您確定要使用該模板嗎，使用模板後，正文內容將被清空。';
$lang->doc->selectExecution      = '選擇執行';
$lang->doc->selectProduct        = '選擇產品';
$lang->doc->selectProject        = '選擇項目';

$lang->docTemplate->filter     = '篩選器';
$lang->docTemplate->param      = '配置參數';
$lang->docTemplate->searchTab  = '檢索標籤';
$lang->docTemplate->zentaoData = '禪道列表數據配置';
$lang->docTemplate->filterTip  = '使用檢索標籤快捷配置相應對象篩選器。';
$lang->docTemplate->paramTip   = '此區塊會根據配置的參數以及所選模板中對象篩選器的配置，展示相應的列表數據。';
$lang->docTemplate->configTip  = '使用該模板時，此區塊會根據篩選器的配置展示相應的%s數據。';
$lang->docTemplate->next       = '下一步';
$lang->docTemplate->noPriv     = '您沒有%s的權限。';

$lang->doc->docLang->createByTemplate = $lang->doc->createByTemplate;

$lang->docTemplate->zentaoList = array();
$lang->docTemplate->zentaoList['story']          = $lang->SRCommon;
$lang->docTemplate->zentaoList['productStory']   = $lang->productCommon . $lang->SRCommon;
$lang->docTemplate->zentaoList['projectStory']   = $lang->projectCommon . $lang->SRCommon;
$lang->docTemplate->zentaoList['executionStory'] = $lang->execution->common . $lang->SRCommon;

$lang->docTemplate->zentaoList['design'] = $lang->design->common;
$lang->docTemplate->zentaoList['HLDS']   = $lang->design->HLDS;
$lang->docTemplate->zentaoList['DDS']    = $lang->design->DDS;
$lang->docTemplate->zentaoList['DBDS']   = $lang->design->DBDS;
$lang->docTemplate->zentaoList['ADS']    = $lang->design->ADS;

$lang->docTemplate->zentaoList['task']        = $lang->task->common;
$lang->docTemplate->zentaoList['case']        = $lang->testcase->common;
$lang->docTemplate->zentaoList['productCase'] = $lang->productCommon . $lang->testcase->common;
$lang->docTemplate->zentaoList['projectCase'] = $lang->projectCommon . $lang->testcase->common;
$lang->docTemplate->zentaoList['bug']         = $lang->bug->common;
$lang->docTemplate->zentaoList['gantt']       = '甘特圖';

$lang->docTemplate->searchTabList = array();
$lang->docTemplate->searchTabList['productStory'] = array();
$lang->docTemplate->searchTabList['productStory']['allstory']       = '全部';
$lang->docTemplate->searchTabList['productStory']['unclosed']       = '未關閉';
$lang->docTemplate->searchTabList['productStory']['draftstory']     = '草稿';
$lang->docTemplate->searchTabList['productStory']['activestory']    = '激活';
$lang->docTemplate->searchTabList['productStory']['changingstory']  = '變更中';
$lang->docTemplate->searchTabList['productStory']['reviewingstory'] = '評審中';
$lang->docTemplate->searchTabList['productStory']['willclose']      = '待關閉';
$lang->docTemplate->searchTabList['productStory']['closedstory']    = '已關閉';
$lang->docTemplate->searchTabList['productStory']['feedback']       = '來自反饋';

$lang->docTemplate->searchTabList['projectStory'] = array();
$lang->docTemplate->searchTabList['projectStory']['allstory']          = '全部';
$lang->docTemplate->searchTabList['projectStory']['unclosed']          = '未關閉';
$lang->docTemplate->searchTabList['projectStory']['draft']             = '草稿';
$lang->docTemplate->searchTabList['projectStory']['reviewing']         = '評審中';
$lang->docTemplate->searchTabList['projectStory']['changing']          = '變更中';
$lang->docTemplate->searchTabList['projectStory']['closed']            = '已關閉';
$lang->docTemplate->searchTabList['projectStory']['linkedexecution']   = '已關聯' . $lang->execution->common;
$lang->docTemplate->searchTabList['projectStory']['unlinkedexecution'] = '未關聯' . $lang->execution->common;

$lang->docTemplate->searchTabList['executionStory'] = array();
$lang->docTemplate->searchTabList['executionStory']['all']       = '全部';
$lang->docTemplate->searchTabList['executionStory']['unclosed']  = '未關閉';
$lang->docTemplate->searchTabList['executionStory']['draft']     = '草稿';
$lang->docTemplate->searchTabList['executionStory']['reviewing'] = '評審中';

$lang->docTemplate->searchTabList['task'] = array();
$lang->docTemplate->searchTabList['task']['all']         = '全部';
$lang->docTemplate->searchTabList['task']['unclosed']    = '未關閉';
$lang->docTemplate->searchTabList['task']['needconfirm'] = "{$lang->SRCommon}變更";
$lang->docTemplate->searchTabList['task']['wait']        = '未開始';
$lang->docTemplate->searchTabList['task']['doing']       = '進行中';
$lang->docTemplate->searchTabList['task']['undone']      = '未完成';
$lang->docTemplate->searchTabList['task']['done']        = '已完成';
$lang->docTemplate->searchTabList['task']['closed']      = '已關閉';
$lang->docTemplate->searchTabList['task']['cancel']      = '已取消';
$lang->docTemplate->searchTabList['task']['delayed']     = '已延期';

$lang->docTemplate->searchTabList['bug']['all']           = '全部';
$lang->docTemplate->searchTabList['bug']['unclosed']      = '未關閉';
$lang->docTemplate->searchTabList['bug']['unresolved']    = '未解決';
$lang->docTemplate->searchTabList['bug']['unconfirmed']   = '未確認';
$lang->docTemplate->searchTabList['bug']['assigntonull']  = '未指派';
$lang->docTemplate->searchTabList['bug']['longlifebugs']  = '久未處理';
$lang->docTemplate->searchTabList['bug']['toclosed']      = '待關閉';
$lang->docTemplate->searchTabList['bug']['postponedbugs'] = '被延期';
$lang->docTemplate->searchTabList['bug']['overduebugs']   = '過期Bug';
$lang->docTemplate->searchTabList['bug']['needconfirm']   = "{$lang->SRCommon}變動";
$lang->docTemplate->searchTabList['bug']['feedback']      = '來自反饋';

$lang->docTemplate->searchTabList['productCase'] = array();
$lang->docTemplate->searchTabList['productCase']['all']         = '全部';
$lang->docTemplate->searchTabList['productCase']['wait']        = '待評審';
$lang->docTemplate->searchTabList['productCase']['needconfirm'] = "{$lang->common->story}變動";

$lang->docTemplate->searchTabList['projectCase'] = array();
$lang->docTemplate->searchTabList['projectCase']['all']         = '全部';
$lang->docTemplate->searchTabList['projectCase']['wait']        = '待評審';
$lang->docTemplate->searchTabList['projectCase']['needconfirm'] = "{$lang->common->story}變動";

$lang->docTemplate->searchTabList['HLDS'] = array();
$lang->docTemplate->searchTabList['HLDS']['all'] = '全部';

$lang->docTemplate->searchTabList['DDS'] = array();
$lang->docTemplate->searchTabList['DDS']['all'] = '全部';

$lang->docTemplate->searchTabList['DBDS'] = array();
$lang->docTemplate->searchTabList['DBDS']['all'] = '全部';

$lang->docTemplate->searchTabList['ADS'] = array();
$lang->docTemplate->searchTabList['ADS']['all'] = '全部';

$lang->doc->featureBar['selecttemplate'] = array();
$lang->doc->featureBar['selecttemplate']['all'] = '全部';
