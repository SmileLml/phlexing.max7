<?php

/**
 * The ai module zh-tw lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
$lang->ai->common = 'AI配置';

/* Definitions of table columns, used to sprintf error messages to dao::$errors. */
$lang->prompt = new stdclass();
$lang->prompt->name             = '名稱';
$lang->prompt->desc             = '描述';
$lang->prompt->model            = '語言模型';
$lang->prompt->module           = '所屬分組';
$lang->prompt->source           = '對象數據';
$lang->prompt->targetForm       = '目標表單';
$lang->prompt->purpose          = '操作';
$lang->prompt->elaboration      = '補充要求';
$lang->prompt->role             = '角色';
$lang->prompt->characterization = '角色描述';
$lang->prompt->status           = '階段';
$lang->prompt->createdBy        = '由誰創建';
$lang->prompt->createdDate      = '創建時間';
$lang->prompt->editedBy         = '最後編輯';
$lang->prompt->editedDate       = '編輯時間';
$lang->prompt->deleted          = '是否已刪除';

/* Lang for privs, keys are paired with privlang items. */
$lang->ai->modelBrowse             = '瀏覽語言模型';
$lang->ai->modelView               = '查看語言模型詳情';
$lang->ai->modelCreate             = '創建語言模型';
$lang->ai->modelEdit               = '編輯語言模型';
$lang->ai->modelEnable             = '啟用語言模型';
$lang->ai->modelDisable            = '禁用語言模型';
$lang->ai->modelDelete             = '刪除語言模型';
$lang->ai->modelTestConnection     = '測試連接';
$lang->ai->promptCreate            = '創建提詞';
$lang->ai->promptEdit              = '編輯提詞';
$lang->ai->promptDelete            = '刪除提詞';
$lang->ai->promptAssignRole        = '指定角色';
$lang->ai->promptSelectDataSource  = '選擇對象';
$lang->ai->promptSetPurpose        = '確認操作';
$lang->ai->promptSetTargetForm     = '結果處理';
$lang->ai->promptFinalize          = '準備發佈';
$lang->ai->promptAudit             = '調試提詞';
$lang->ai->promptPublish           = '發佈提詞';
$lang->ai->promptUnpublish         = '取消發佈提詞';
$lang->ai->promptBrowse            = '瀏覽提詞列表';
$lang->ai->promptView              = '查看提詞詳情';
$lang->ai->promptExecute           = '執行提詞';
$lang->ai->promptExecutionReset    = '重置執行';
$lang->ai->roleTemplates           = '管理角色模板';
$lang->ai->chat                    = '聊天';
$lang->ai->createMiniProgram       = '創建AI小程序';
$lang->ai->editMiniProgram         = '編輯AI小程序';
$lang->ai->configuredMiniProgram   = '配置小程序';
$lang->ai->testMiniProgram         = '調試AI小程序';
$lang->ai->miniProgramList         = '瀏覽AI小程序列表';
$lang->ai->miniProgramView         = '查看AI小程序詳情';
$lang->ai->publishMiniProgram      = '發佈AI小程序';
$lang->ai->unpublishMiniProgram    = '下架AI小程序';
$lang->ai->deleteMiniProgram       = '刪除AI小程序';
$lang->ai->exportMiniProgram       = '導出AI小程序';
$lang->ai->importMiniProgram       = '導入AI小程序';
$lang->ai->editMiniProgramCategory = '維護分組';
$lang->ai->assistants              = '瀏覽AI助手';
$lang->ai->assistantView           = '查看AI助手詳情';
$lang->ai->assistantCreate         = '創建AI助手';
$lang->ai->assistantEdit           = '編輯AI助手';
$lang->ai->assistantPublish        = '發佈AI助手';
$lang->ai->assistantWithdraw       = '停用AI助手';
$lang->ai->assistantDelete         = '刪除AI助手';

$lang->ai->store                  = '商店';
$lang->ai->export                 = '導出';
$lang->ai->import                 = '導入';
$lang->ai->saveFail               = '保存失敗';
$lang->ai->installPackage         = '安裝包';
$lang->ai->toPublish              = '安裝後發佈';
$lang->ai->toZentaoStoreAIPage    = '點擊可跳轉至禪道官網應用商店AI小程序頁面。';

$lang->ai->chatPlaceholderMessage = 'Hi，我是 AI 助手阿道，您可以問我任何問題。';
$lang->ai->chatPlaceholderInput   = '問問阿道…';
$lang->ai->chatSystemMessage      = '你叫阿道，是禪道的 AI 助手兼吉祥物，你可以回答用戶的問題和與用戶聊天。你當前所處的環境是禪道項目管理軟件。';
$lang->ai->chatSend               = '發送';
$lang->ai->chatReset              = '清空';
$lang->ai->chatNoResponse         = '會話發生了錯誤，<a id="retry" class="text-blue">點擊這裡重試</a>。';
$lang->ai->noMiniProgram          = '您訪問的小程序不存在';

$lang->ai->nextStep  = '下一步';
$lang->ai->goTesting = '去調試';
$lang->ai->maintenanceGroup = '維護分組';

$lang->ai->maintenanceGroupDuplicated = '分組名不能重複';

$lang->ai->validate = new stdclass();
$lang->ai->validate->noEmpty       = '%s不能為空。';
$lang->ai->validate->dirtyForm     = '%s的參數配置已變動，是否保存並返回？';
$lang->ai->validate->nameNotUnique = '該名稱已使用，請嘗試其他名稱。';

$lang->ai->prompts = new stdclass();
$lang->ai->prompts->common       = '提詞';
$lang->ai->prompts->emptyList    = '暫時沒有提詞。';
$lang->ai->prompts->create       = '創建提詞';
$lang->ai->prompts->edit         = '編輯提詞';
$lang->ai->prompts->id           = 'ID';
$lang->ai->prompts->name         = '名稱';
$lang->ai->prompts->description  = '描述';
$lang->ai->prompts->createdBy    = '創建者';
$lang->ai->prompts->createdDate  = '創建時間';
$lang->ai->prompts->targetForm   = '表單';
$lang->ai->prompts->funcDesc     = '功能描述';
$lang->ai->prompts->deleted      = '已刪除';
$lang->ai->prompts->stage        = '階段';
$lang->ai->prompts->basicInfo    = '基本信息';
$lang->ai->prompts->editInfo     = '創建編輯';
$lang->ai->prompts->createdBy    = '由誰創建';
$lang->ai->prompts->publishedBy  = '由誰發佈';
$lang->ai->prompts->draftedBy    = '由誰下架';
$lang->ai->prompts->lastEditor   = '最後編輯';
$lang->ai->prompts->modelNeutral = '通用';

$lang->ai->prompts->summary = '本頁共 %s 個提詞。';
$lang->ai->prompts->fieldSeparator = '、';

$lang->ai->prompts->action = new stdclass();
$lang->ai->prompts->action->goDesignConfirm = '當前提詞未完成，是否繼續設計？';
$lang->ai->prompts->action->goDesign        = '去設計';
$lang->ai->prompts->action->draftConfirm    = '下架後，提詞將不能繼續使用，您確定要下架嗎？';
$lang->ai->prompts->action->design          = '設計';
$lang->ai->prompts->action->test            = '調試';
$lang->ai->prompts->action->edit            = '編輯';
$lang->ai->prompts->action->publish         = '發佈';
$lang->ai->prompts->action->unpublish       = '下架';
$lang->ai->prompts->action->delete          = '刪除';
$lang->ai->prompts->action->disable         = '禁用';
$lang->ai->prompts->action->deleteConfirm   = '刪除後，提詞將不能繼續使用，您確定要刪除嗎？';
$lang->ai->prompts->action->publishSuccess  = '發佈成功';

/* Steps of prompt creation. */
$lang->ai->prompts->assignRole       = '指定角色';
$lang->ai->prompts->selectDataSource = '選擇對象';
$lang->ai->prompts->setPurpose       = '確認操作';
$lang->ai->prompts->setTargetForm    = '結果處理';
$lang->ai->prompts->finalize         = '準備發佈';

/* Role assigning. */
$lang->ai->prompts->model               = '語言模型';
$lang->ai->prompts->role                = '角色';
$lang->ai->prompts->characterization    = '角色描述';
$lang->ai->prompts->rolePlaceholder     = '“你來扮演 <一個什麼角色>”';
$lang->ai->prompts->charPlaceholder     = '該角色的具體描述信息';
$lang->ai->prompts->roleTemplate        = '角色模版';
$lang->ai->prompts->roleTemplateTip     = '引用模板後，修改角色、角色描述不會對模板造成影響。';
$lang->ai->prompts->addRoleTemplate     = '添加角色模板';
$lang->ai->prompts->editRoleTemplate    = '編輯角色模板';
$lang->ai->prompts->editRoleTemplateTip = '本次編輯不會影響已使用該模版的提詞';
$lang->ai->prompts->roleAddedSuccess    = '角色模版保存成功';
$lang->ai->prompts->roleDelConfirm      = '刪除不會影響已用角色模版的提詞，是否刪除？';
$lang->ai->prompts->roleDelSuccess      = '角色模板已刪除';
$lang->ai->prompts->roleTemplateSave    = '存為角色模板';
$lang->ai->prompts->roleTemplateSaveList = array();
$lang->ai->prompts->roleTemplateSaveList['save']    = '保存';
$lang->ai->prompts->roleTemplateSaveList['discard'] = '不保存';

/* Data source selecting. */
$lang->ai->prompts->selectData       = '選擇欄位';
$lang->ai->prompts->selectDataTip    = '選擇對象後，此處會展示已選對象的欄位。';
$lang->ai->prompts->selectedFormat   = '已選對象為{0}，已選 {1} 條欄位';
$lang->ai->prompts->nonSelected      = '暫無所選欄位。';
$lang->ai->prompts->sortTip          = '可根據重要性給數據欄位排序。';
$lang->ai->prompts->object           = '對象';
$lang->ai->prompts->field            = '欄位';

/* Purpose setting. */
$lang->ai->prompts->purpose        = '操作';
$lang->ai->prompts->purposeTip     = '“我希望<它能完成什麼事情，以便于達到什麼樣的目標>”';
$lang->ai->prompts->elaboration    = '補充要求';
$lang->ai->prompts->elaborationTip = '“我希望<它的回答請注意一些補充要求>”';
$lang->ai->prompts->inputPreview   = '輸入預覽';
$lang->ai->prompts->dataPreview    = '對象數據預覽';
$lang->ai->prompts->rolePreview    = '角色提詞預覽';
$lang->ai->prompts->promptPreview  = '操作提詞預覽';

/* Target form selecting. */
$lang->ai->prompts->selectTargetForm    = '選擇表單';
$lang->ai->prompts->selectTargetFormTip = '選擇後，可以將大語言模型返回的結果直接錄入到禪道對應的表單中。';
$lang->ai->prompts->goingTesting        = '即將跳轉至調試頁面';
$lang->ai->prompts->goingTestingFail    = '無法去調試，找不到合適的對象';

/* Finalize page. */
$lang->ai->moduleDisableTip = '系統根據所選對象自動關聯分組';

/* Data source definition. */
$lang->ai->dataSource = array();

$lang->ai->dataSource['my']['common']          = '地盤';
$lang->ai->dataSource['product']['common']     = '產品';
$lang->ai->dataSource['story']['common']       = '需求';
$lang->ai->dataSource['productplan']['common'] = '計劃';
$lang->ai->dataSource['release']['common']     = '發佈';
$lang->ai->dataSource['project']['common']     = '項目';
$lang->ai->dataSource['execution']['common']   = '執行';
$lang->ai->dataSource['task']['common']        = '任務';
$lang->ai->dataSource['bug']['common']         = 'Bug';
$lang->ai->dataSource['case']['common']        = '用例';
$lang->ai->dataSource['doc']['common']         = '文檔';

$lang->ai->dataSource['my']['efforts']['common']    = '日誌列表';
$lang->ai->dataSource['my']['efforts']['date']      = '日期';
$lang->ai->dataSource['my']['efforts']['work']      = '工作內容';
$lang->ai->dataSource['my']['efforts']['account']   = '記錄人';
$lang->ai->dataSource['my']['efforts']['consumed']  = '耗時';
$lang->ai->dataSource['my']['efforts']['left']      = '剩餘';
$lang->ai->dataSource['my']['efforts']['objectID']  = '對象';
$lang->ai->dataSource['my']['efforts']['product']   = '產品';
$lang->ai->dataSource['my']['efforts']['project']   = '項目';
$lang->ai->dataSource['my']['efforts']['execution'] = '執行';

$lang->ai->dataSource['product']['product']['common']  = '產品';
$lang->ai->dataSource['product']['product']['name']    = '產品名稱';
$lang->ai->dataSource['product']['product']['desc']    = '產品描述';
$lang->ai->dataSource['product']['modules']['common']  = '產品模組列表';
$lang->ai->dataSource['product']['modules']['name']    = '模組名稱';
$lang->ai->dataSource['product']['modules']['modules'] = '子模組';

$lang->ai->dataSource['productplan']['productplan']['common'] = '計劃';
$lang->ai->dataSource['productplan']['productplan']['title']  = '計劃名稱';
$lang->ai->dataSource['productplan']['productplan']['desc']   = '計劃描述';
$lang->ai->dataSource['productplan']['productplan']['begin']  = '開始時間';
$lang->ai->dataSource['productplan']['productplan']['end']    = '結束時間';

$lang->ai->dataSource['productplan']['stories']['common']   = '需求列表';
$lang->ai->dataSource['productplan']['stories']['title']    = '需求名稱';
$lang->ai->dataSource['productplan']['stories']['module']   = '所屬模組';
$lang->ai->dataSource['productplan']['stories']['pri']      = '優先順序';
$lang->ai->dataSource['productplan']['stories']['estimate'] = '預計故事點';
$lang->ai->dataSource['productplan']['stories']['status']   = '狀態';
$lang->ai->dataSource['productplan']['stories']['stage']    = '階段';

$lang->ai->dataSource['productplan']['bugs']['common'] = 'Bug列表';
$lang->ai->dataSource['productplan']['bugs']['title']  = 'Bug標題';
$lang->ai->dataSource['productplan']['bugs']['pri']    = '優先順序';
$lang->ai->dataSource['productplan']['bugs']['status'] = '狀態';

$lang->ai->dataSource['release']['release']['common']  = '發佈';
$lang->ai->dataSource['release']['release']['product'] = '所屬產品';
$lang->ai->dataSource['release']['release']['name']    = '發佈名稱';
$lang->ai->dataSource['release']['release']['desc']    = '發佈描述';
$lang->ai->dataSource['release']['release']['date']    = '發佈日期';

$lang->ai->dataSource['release']['stories']['common']   = '需求列表';
$lang->ai->dataSource['release']['stories']['title']    = '需求名稱';
$lang->ai->dataSource['release']['stories']['estimate'] = '預估故事點';

$lang->ai->dataSource['release']['bugs']['common'] = 'Bug列表';
$lang->ai->dataSource['release']['bugs']['title']  = 'Bug標題';

$lang->ai->dataSource['project']['project']['common']   = '項目';
$lang->ai->dataSource['project']['project']['name']     = '項目名稱';
$lang->ai->dataSource['project']['project']['type']     = '項目類型';
$lang->ai->dataSource['project']['project']['desc']     = '項目描述';
$lang->ai->dataSource['project']['project']['begin']    = '計劃開始';
$lang->ai->dataSource['project']['project']['end']      = '計劃結束';
$lang->ai->dataSource['project']['project']['estimate'] = '預計工時';

$lang->ai->dataSource['project']['programplans']['common']       = '階段列表';
$lang->ai->dataSource['project']['programplans']['name']         = '階段名稱';
$lang->ai->dataSource['project']['programplans']['desc']         = '階段描述';
$lang->ai->dataSource['project']['programplans']['status']       = '階段狀態';
$lang->ai->dataSource['project']['programplans']['begin']        = '計劃開始';
$lang->ai->dataSource['project']['programplans']['end']          = '計劃完成';
$lang->ai->dataSource['project']['programplans']['realBegan']    = '實際開始';
$lang->ai->dataSource['project']['programplans']['realEnd']      = '實際完成';
$lang->ai->dataSource['project']['programplans']['planDuration'] = '工期';
$lang->ai->dataSource['project']['programplans']['progress']     = '任務進度';
$lang->ai->dataSource['project']['programplans']['estimate']     = '預計工時';
$lang->ai->dataSource['project']['programplans']['consumed']     = '消耗工時';
$lang->ai->dataSource['project']['programplans']['left']         = '剩餘工時';

$lang->ai->dataSource['project']['executions']['common']    = '迭代列表';
$lang->ai->dataSource['project']['executions']['name']      = '執行名稱';
$lang->ai->dataSource['project']['executions']['desc']      = '執行描述';
$lang->ai->dataSource['project']['executions']['status']    = '執行狀態';
$lang->ai->dataSource['project']['executions']['begin']     = '計劃開始';
$lang->ai->dataSource['project']['executions']['end']       = '計劃完成';
$lang->ai->dataSource['project']['executions']['realBegan'] = '實際開始';
$lang->ai->dataSource['project']['executions']['realEnd']   = '實際完成';
$lang->ai->dataSource['project']['executions']['estimate']  = '預計工時';
$lang->ai->dataSource['project']['executions']['consumed']  = '消耗工時';
$lang->ai->dataSource['project']['executions']['left']      = '剩餘工時';
$lang->ai->dataSource['project']['executions']['progress']  = '進度';

$lang->ai->dataSource['story']['story']['common']   = '需求';
$lang->ai->dataSource['story']['story']['title']    = '需求標題';
$lang->ai->dataSource['story']['story']['spec']     = '需求描述';
$lang->ai->dataSource['story']['story']['verify']   = '驗收標準';
$lang->ai->dataSource['story']['story']['product']  = '產品';
$lang->ai->dataSource['story']['story']['module']   = '模組';
$lang->ai->dataSource['story']['story']['pri']      = '優先順序';
$lang->ai->dataSource['story']['story']['category'] = '需求類型';
$lang->ai->dataSource['story']['story']['estimate'] = '預計工時';

$lang->ai->dataSource['execution']['execution']['common']   = '執行';
$lang->ai->dataSource['execution']['execution']['name']     = '執行名稱';
$lang->ai->dataSource['execution']['execution']['desc']     = '執行描述';
$lang->ai->dataSource['execution']['execution']['estimate'] = '預計工時';

$lang->ai->dataSource['execution']['tasks']['common']       = '任務列表';
$lang->ai->dataSource['execution']['tasks']['name']         = '任務名稱';
$lang->ai->dataSource['execution']['tasks']['pri']          = '優先順序';
$lang->ai->dataSource['execution']['tasks']['status']       = '狀態';
$lang->ai->dataSource['execution']['tasks']['estimate']     = '預計工時';
$lang->ai->dataSource['execution']['tasks']['consumed']     = '已消耗';
$lang->ai->dataSource['execution']['tasks']['left']         = '剩餘';
$lang->ai->dataSource['execution']['tasks']['progress']     = '進度';
$lang->ai->dataSource['execution']['tasks']['estStarted']   = '預計開始';
$lang->ai->dataSource['execution']['tasks']['realStarted']  = '實際開始';
$lang->ai->dataSource['execution']['tasks']['finishedDate'] = '完成日期';
$lang->ai->dataSource['execution']['tasks']['closedReason'] = '關閉原因';

$lang->ai->dataSource['task']['task']['common']      = '任務';
$lang->ai->dataSource['task']['task']['name']        = '任務名稱';
$lang->ai->dataSource['task']['task']['desc']        = '任務描述';
$lang->ai->dataSource['task']['task']['pri']         = '優先順序';
$lang->ai->dataSource['task']['task']['status']      = '狀態';
$lang->ai->dataSource['task']['task']['estimate']    = '預計';
$lang->ai->dataSource['task']['task']['consumed']    = '消耗';
$lang->ai->dataSource['task']['task']['left']        = '剩餘';
$lang->ai->dataSource['task']['task']['progress']    = '進度';
$lang->ai->dataSource['task']['task']['estStarted']  = '預計開始';
$lang->ai->dataSource['task']['task']['realStarted'] = '實際開始';

$lang->ai->dataSource['case']['case']['common']        = '用例';
$lang->ai->dataSource['case']['case']['title']         = '標題';
$lang->ai->dataSource['case']['case']['precondition']  = '前置條件';
$lang->ai->dataSource['case']['case']['scene']         = '所屬場景';
$lang->ai->dataSource['case']['case']['product']       = '所屬產品';
$lang->ai->dataSource['case']['case']['module']        = '所屬模組';
$lang->ai->dataSource['case']['case']['pri']           = '優先順序';
$lang->ai->dataSource['case']['case']['type']          = '類型';
$lang->ai->dataSource['case']['case']['lastRunResult'] = '結果';
$lang->ai->dataSource['case']['case']['status']        = '狀態';

$lang->ai->dataSource['case']['steps']['common'] = '步驟列表';
$lang->ai->dataSource['case']['steps']['desc']   = '步驟描述';
$lang->ai->dataSource['case']['steps']['expect'] = '預期';

$lang->ai->dataSource['bug']['bug']['common']    = 'Bug';
$lang->ai->dataSource['bug']['bug']['title']     = 'Bug標題';
$lang->ai->dataSource['bug']['bug']['steps']     = '重現步驟';
$lang->ai->dataSource['bug']['bug']['severity']  = '級別';
$lang->ai->dataSource['bug']['bug']['pri']       = '優先順序';
$lang->ai->dataSource['bug']['bug']['status']    = '狀態';
$lang->ai->dataSource['bug']['bug']['confirmed'] = '確認';
$lang->ai->dataSource['bug']['bug']['type']      = 'Bug類型';

$lang->ai->dataSource['doc']['doc']['common']     = '文檔';
$lang->ai->dataSource['doc']['doc']['title']      = '文檔標題';
$lang->ai->dataSource['doc']['doc']['content']    = '文檔正文';
$lang->ai->dataSource['doc']['doc']['addedBy']    = '創建者';
$lang->ai->dataSource['doc']['doc']['addedDate']  = '創建日期';
$lang->ai->dataSource['doc']['doc']['editedBy']   = '修改者';
$lang->ai->dataSource['doc']['doc']['editedDate'] = '修改日期';

/* Target form definition. See `$config->ai->targetForm`. */
$lang->ai->targetForm = array();
$lang->ai->targetForm['product']['common']        = '產品';
$lang->ai->targetForm['story']['common']          = '需求';
$lang->ai->targetForm['productplan']['common']    = '計劃';
$lang->ai->targetForm['projectrelease']['common'] = '發佈';
$lang->ai->targetForm['project']['common']        = '項目';
$lang->ai->targetForm['execution']['common']      = '執行';
$lang->ai->targetForm['task']['common']           = '任務';
$lang->ai->targetForm['testcase']['common']       = '用例';
$lang->ai->targetForm['bug']['common']            = 'Bug';
$lang->ai->targetForm['doc']['common']            = '文檔';

$lang->ai->targetForm['product']['tree/managechild'] = '維護模組';
$lang->ai->targetForm['product']['doc/create']       = '創建文檔';

$lang->ai->targetForm['story']['create']         = '提需求';
$lang->ai->targetForm['story']['batchcreate']    = '批量提需求';
$lang->ai->targetForm['story']['change']         = '變更需求';
$lang->ai->targetForm['story']['totask']         = '需求建任務';
$lang->ai->targetForm['story']['testcasecreate'] = '需求建用例';
$lang->ai->targetForm['story']['subdivide']      = '需求細分';

$lang->ai->targetForm['productplan']['edit']   = '編輯計劃';
$lang->ai->targetForm['productplan']['create'] = '創建子計劃';

$lang->ai->targetForm['projectrelease']['doc/create'] = '創建文檔';

$lang->ai->targetForm['project']['risk/create']        = '創建風險';
$lang->ai->targetForm['project']['issue/create']       = '創建問題';
$lang->ai->targetForm['project']['doc/create']         = '創建文檔';
$lang->ai->targetForm['project']['programplan/create'] = '設置階段';

$lang->ai->targetForm['execution']['batchcreatetask']  = '批量創建任務';
$lang->ai->targetForm['execution']['createtestreport'] = '創建測試報告';
$lang->ai->targetForm['execution']['createqa']         = '創建 QA';
$lang->ai->targetForm['execution']['createrisk']       = '創建風險';
$lang->ai->targetForm['execution']['createissue']      = '創建問題';

$lang->ai->targetForm['task']['edit']        = '編輯任務';
$lang->ai->targetForm['task']['batchcreate'] = '批量創建子任務';

$lang->ai->targetForm['testcase']['edit']         = '編輯用例';
$lang->ai->targetForm['testcase']['createscript'] = '創建自動化腳本';

$lang->ai->targetForm['bug']['edit']            = '編輯 Bug';
$lang->ai->targetForm['bug']['story/create']    = 'Bug 轉需求';
$lang->ai->targetForm['bug']['testcase/create'] = 'Bug 建用例';

$lang->ai->targetForm['doc']['create'] = '創建文檔';
$lang->ai->targetForm['doc']['edit']   = '編輯文檔';

$lang->ai->prompts->statuses = array();
$lang->ai->prompts->statuses['']       = '全部';
$lang->ai->prompts->statuses['draft']  = '未發佈';
$lang->ai->prompts->statuses['active'] = '已發佈';

$lang->ai->prompts->modules = array();
$lang->ai->prompts->modules['']            = '所有分組';
// $lang->ai->prompts->modules['my']          = '地盤';
$lang->ai->prompts->modules['product']     = '產品';
$lang->ai->prompts->modules['project']     = '項目';
$lang->ai->prompts->modules['story']       = '需求';
$lang->ai->prompts->modules['productplan'] = '計劃';
$lang->ai->prompts->modules['release']     = '發佈';
$lang->ai->prompts->modules['execution']   = '執行';
$lang->ai->prompts->modules['task']        = '任務';
$lang->ai->prompts->modules['case']        = '用例';
$lang->ai->prompts->modules['bug']         = 'Bug';
$lang->ai->prompts->modules['doc']         = '文檔';

$lang->ai->conversations = new stdclass();
$lang->ai->conversations->common = '會話';

$lang->ai->miniPrograms                    = new stdClass();
$lang->ai->miniPrograms->common            = '小程序';
$lang->ai->miniPrograms->emptyList         = '暫時沒有小程序。';
$lang->ai->miniPrograms->create            = '創建小程序';
$lang->ai->miniPrograms->configuration     = '基本信息配置';
$lang->ai->miniPrograms->downloadTip       = '發佈後將在AI小程序廣場上展示，並會自動同步到客戶端上。';
$lang->ai->miniPrograms->download          = '下載禪道客戶端';
$lang->ai->miniPrograms->category          = '所屬分類';
$lang->ai->miniPrograms->icon              = '表徵圖';
$lang->ai->miniPrograms->desc              = '簡介';
$lang->ai->miniPrograms->categoryList      = array('work' => '工作', 'personal' => '個人', 'life' => '生活', 'creative' => '創意', 'others' => '其它');
$lang->ai->miniPrograms->allCategories     = array('' => '所有分組');
$lang->ai->miniPrograms->collect           = '收藏';
$lang->ai->miniPrograms->more              = '更多';
$lang->ai->miniPrograms->iconModification  = '表徵圖修改';
$lang->ai->miniPrograms->customBackground  = '自定義背景色';
$lang->ai->miniPrograms->customIcon        = '自定義icon';
$lang->ai->miniPrograms->backToListPage    = '返回列表頁';
$lang->ai->miniPrograms->lastStep          = '上一步';
$lang->ai->miniPrograms->backToListPageTip = '選擇對象的參數配置已變動，是否保存並返回？';
$lang->ai->miniPrograms->saveAndBack       = '保存並返回';
$lang->ai->miniPrograms->publishConfirm    = array('您確定要發佈嗎？', '發佈後將在一級導航AI模組中顯示，客戶端將會同步更新。');
$lang->ai->miniPrograms->emptyPrompterTip  = '小程序提詞為空，請編輯後再進行發佈';
$lang->ai->miniPrograms->maintenanceGroup  = '維護小程序分組';

$lang->ai->miniPrograms->latestPublishedDate = '最新發佈時間';
$lang->ai->miniPrograms->deleteTip           = '確定刪除該小程序？';
$lang->ai->miniPrograms->disableTip          = '下架小程序用戶將無法使用，是否確認下架？';
$lang->ai->miniPrograms->publishTip          = '發佈後將在一級導航AI模組中顯示，客戶端將會同步更新。';
$lang->ai->miniPrograms->unpublishedTip      = '您使用的小程序沒有發佈';

$lang->ai->miniPrograms->placeholder          = new stdClass();
$lang->ai->miniPrograms->placeholder->name    = '請輸入小程序名稱';
$lang->ai->miniPrograms->placeholder->desc    = '請輸入小程序簡介';
$lang->ai->miniPrograms->placeholder->default = '請輸入填寫提示，預設為“請輸入”';
$lang->ai->miniPrograms->placeholder->input   = '請輸入';
$lang->ai->miniPrograms->placeholder->prompt  = '請輸入提詞設計';
$lang->ai->miniPrograms->placeholder->asking  = '繼續追問';

$lang->ai->miniPrograms->deleteFieldTip = '您確定刪除該欄位嗎？';

$lang->ai->miniPrograms->field                      = new stdClass();
$lang->ai->miniPrograms->field->name                = '欄位名稱';
$lang->ai->miniPrograms->field->duplicatedNameTip   = '該名稱已使用，請嘗試其他名稱';
$lang->ai->miniPrograms->field->type                = '控件類型';
$lang->ai->miniPrograms->field->typeList            = array('text' => '單行文本', 'textarea' => '多行文本', 'radio' => '單選', 'checkbox' => '多選');
$lang->ai->miniPrograms->field->placeholder         = '填寫提示';
$lang->ai->miniPrograms->field->required            = '是否必填';
$lang->ai->miniPrograms->field->requiredOptions     = array('否', '是');
$lang->ai->miniPrograms->field->add                 = '新增欄位';
$lang->ai->miniPrograms->field->addTip              = '請點擊此處以添加欄位信息';
$lang->ai->miniPrograms->field->edit                = '編輯欄位';
$lang->ai->miniPrograms->field->configuration       = '欄位配置區';
$lang->ai->miniPrograms->field->debug               = '調試區';
$lang->ai->miniPrograms->field->preview             = '預覽區';
$lang->ai->miniPrograms->field->option              = '選項';
$lang->ai->miniPrograms->field->contentDebugging    = '內容調試';
$lang->ai->miniPrograms->field->contentDebuggingTip = '請在此處輸入欄位內容進行調試。';
$lang->ai->miniPrograms->field->prompterDesign      = '提詞設計';
$lang->ai->miniPrograms->field->prompterDesignTip   = '輸入“<>”符號可引用已配置的欄位，“<>”前後採用空格進行間隔。';
$lang->ai->miniPrograms->field->prompterPreview     = '提詞預覽';
$lang->ai->miniPrograms->field->generateResult      = '生成結果';
$lang->ai->miniPrograms->field->resultPreview       = '結果預覽';

$lang->ai->miniPrograms->field->default = array(
    '角色',
    '場景',
    '目標',
    '作為一名 <角色> ，我希望在 <場景> 時，能 <目標> 。'
);

$lang->ai->miniPrograms->field->emptyNameWarning       = '「%s」不能為空';
$lang->ai->miniPrograms->field->duplicatedNameWarning  = '「%s」重複';
$lang->ai->miniPrograms->field->emptyOptionWarning     = '請至少配置一個選項';

$lang->ai->miniPrograms->statuses = array(
    ''            => '全部',
    'draft'       => '未發佈',
    'active'      => '已發佈',
    'createdByMe' => '由我創建'
);

$lang->ai->miniPrograms->publishedOptions   = array('未發佈', '已發佈');
$lang->ai->miniPrograms->optionName         = '選項名稱';
$lang->ai->miniPrograms->promptTemplate     = '提詞模板';
$lang->ai->miniPrograms->fieldConfiguration = '欄位配置';
$lang->ai->miniPrograms->summary            = '本頁共 %s 個小程序。';
$lang->ai->miniPrograms->generate           = '生成';
$lang->ai->miniPrograms->regenerate         = '重新生成';
$lang->ai->miniPrograms->noModel            = array('尚未配置語言模型，請聯繫管理員或跳轉至後台配置<a id="to-language-model">語言模型</a>。', '若已完成相關配置，請嘗試<a id="reload-current">重新加載</a>頁面。');
$lang->ai->miniPrograms->clearContext       = '上下文內容已清除';
$lang->ai->miniPrograms->newVersionTip      = '小程序已于 %s 更新，以上為過往記錄';
$lang->ai->miniPrograms->disabledTip        = '當前小程序已被禁用。';
$lang->ai->miniPrograms->chatNoResponse     = '會話發生了錯誤';

$lang->ai->models = new stdclass();
$lang->ai->models->title          = '語言模型配置';
$lang->ai->models->common         = '語言模型';
$lang->ai->models->name           = '語言模型名稱';
$lang->ai->models->type           = '語言模型';
$lang->ai->models->vendor         = '供應商';
$lang->ai->models->base           = 'API 基礎地址';
$lang->ai->models->key            = 'API Key';
$lang->ai->models->secret         = 'Secret Key';
$lang->ai->models->resource       = 'Resource';
$lang->ai->models->deployment     = 'Deployment';
$lang->ai->models->proxyType      = '代理類型';
$lang->ai->models->proxyAddr      = '代理地址';
$lang->ai->models->description    = '描述';
$lang->ai->models->createdDate    = '添加時間';
$lang->ai->models->createdBy      = '添加者';
$lang->ai->models->editedDate     = '修改時間';
$lang->ai->models->editedBy       = '修改者';
$lang->ai->models->usesProxy      = '使用代理';
$lang->ai->models->testConnection = '測試連接';
$lang->ai->models->unconfigured   = '未配置';
$lang->ai->models->create         = '添加語言模型';
$lang->ai->models->edit           = '編輯模型參數';
$lang->ai->models->view           = '查看模型參數';
$lang->ai->models->enable         = '啟用語言模型';
$lang->ai->models->disable        = '禁用語言模型';
$lang->ai->models->details        = '語言模型詳情';
$lang->ai->models->concealTip     = '完整信息在編輯時可見';
$lang->ai->models->upgradeBiz     = '更多AI功能，盡在<a target="_blank" href="https://www.zentao.net/page/enterprise.html" class="text-blue">企業版</a>！';
$lang->ai->models->noModelError   = '暫無可用的語言模型，請聯繫管理員配置。';
$lang->ai->models->noModels       = '暫時沒有語言模型，添加模型並配置相關參數後可以使用 AI 相關功能。';
$lang->ai->models->confirmDelete  = '刪除模型後，關聯的AI提詞、小程序及AI會話將會無法使用，是否確認刪除？';
$lang->ai->models->confirmDisable = '您確認要禁用該語言模型嗎？';
$lang->ai->models->default        = '預設';
$lang->ai->models->defaultTip     = '預設語言模型（第一個可用的語言模型）將會用於運行未指定語言模型的提詞、小程序，也將會用於聊天。';
$lang->ai->models->authFailure    = 'API 認證失敗';

$lang->ai->models->testConnectionResult = new stdclass();
$lang->ai->models->testConnectionResult->success    = '連接成功';
$lang->ai->models->testConnectionResult->fail       = '連接失敗';
$lang->ai->models->testConnectionResult->failFormat = '連接失敗：%s';

$lang->ai->models->statusList = array();
$lang->ai->models->statusList['0']   = '停用';
$lang->ai->models->statusList['off'] = '停用';
$lang->ai->models->statusList['1']   = '啟用';
$lang->ai->models->statusList['on']  = '啟用';

$lang->ai->models->proxyStatusList = array();
$lang->ai->models->proxyStatusList['0']   = '否';
$lang->ai->models->proxyStatusList['off'] = '否';
$lang->ai->models->proxyStatusList['1']   = '是';
$lang->ai->models->proxyStatusList['on']  = '是';

$lang->ai->models->typeList = array();
$lang->ai->models->typeList['openai-gpt35'] = 'OpenAI / GPT-3.5';
$lang->ai->models->typeList['openai-gpt4']  = 'OpenAI / GPT-4';
$lang->ai->models->typeList['baidu-ernie']  = '百度 / 文心一言';

$lang->ai->models->vendorList = new stdclass();
$lang->ai->models->vendorList->{'openai-gpt35'} = array('openai' => 'OpenAI', 'azure' => 'Azure', 'openaiCompatible' => '自定義');
$lang->ai->models->vendorList->{'openai-gpt4'}  = array('openai' => 'OpenAI', 'azure' => 'Azure', 'openaiCompatible' => '自定義');
$lang->ai->models->vendorList->{'baidu-ernie'}  = array('baidu' => '百度千帆大模型平台');

$lang->ai->models->vendorTips = new stdclass();
$lang->ai->models->vendorTips->azure            = 'Azure 中 OpenAI GPT 版本 (3.5 或 4) 需要在創建資源時指定。';
$lang->ai->models->vendorTips->openaiCompatible = '指定的 API 需要支持 Function Calling，否則某些功能可能無法正常使用。';

$lang->ai->models->proxyTypes = array();
$lang->ai->models->proxyTypes['']       = '不使用代理';
$lang->ai->models->proxyTypes['socks5'] = 'SOCKS5';

$lang->ai->models->promptFor = '輸入給 %s';

$lang->ai->designStepNav = array();
$lang->ai->designStepNav['assignrole']       = '指定角色';
$lang->ai->designStepNav['selectdatasource'] = '選擇對象';
$lang->ai->designStepNav['setpurpose']       = '確認操作';
$lang->ai->designStepNav['settargetform']    = '結果處理';
$lang->ai->designStepNav['finalize']         = '準備發佈';

$lang->ai->dataTypeDesc = '%s是%s類型，%s';

$lang->ai->dataType            = new stdclass();
$lang->ai->dataType->pri       = new stdClass();
$lang->ai->dataType->pri->type = '數值';
$lang->ai->dataType->pri->desc = '1 是最高優先順序，4 是最低優先順序。';

$lang->ai->dataType->estimate       = new stdClass();
$lang->ai->dataType->estimate->type = '數值';
$lang->ai->dataType->estimate->desc = '單位為小時。';

$lang->ai->dataType->consumed = $lang->ai->dataType->estimate;
$lang->ai->dataType->left     = $lang->ai->dataType->estimate;

$lang->ai->dataType->progress       = new stdClass();
$lang->ai->dataType->progress->type = '百分比';
$lang->ai->dataType->progress->desc = '0 是未開始，100是已完成。';

$lang->ai->dataType->datetime       = new stdClass();
$lang->ai->dataType->datetime->type = '日期時間';
$lang->ai->dataType->datetime->desc = '格式為：1970-01-01 00:00:01，沒有則留空。';

$lang->ai->dataType->estStarted   = $lang->ai->dataType->datetime;
$lang->ai->dataType->realStarted  = $lang->ai->dataType->datetime;
$lang->ai->dataType->finishedDate = $lang->ai->dataType->datetime;

$lang->ai->demoData            = new stdclass();
$lang->ai->demoData->notExist  = '暫無演示數據。';
$lang->ai->demoData->story     = array(
    'story' => array(
        'title'    => '開發一個在綫學習平台',
        'spec'     => '我們需要開發一個在綫學習平台，能夠提供課程管理、學生管理、教師管理等功能。',
        'verify' => '1. 所有功能均能夠正常運行，沒有明顯的錯誤和異常。2. 界面美觀、易用性好。3. 平台能夠滿足用戶需求，具有較高的用戶滿意度。4. 代碼質量好，結構清晰、易於維護。',
        'module'   => 7,
        'pri'      => 1,
        'estimate' => 1,
        'product'  => 1,
        'category' => 'feature',
    ),
);
$lang->ai->demoData->execution = array(
    'execution' => array(
        'name'     => '在綫學習平台軟件開發',
        'desc'     => '本計劃旨在開發一款在綫學習平台軟件，該軟件將提供可訪問的學習資源，包括文本、視頻和音頻等，以及一些學習工具如考試、測試和討論論壇等。',
        'estimate' => 7,
    ),
    'tasks'     => array(
        0 =>
        array(
            'name'         => '技術選型',
            'pri'          => 1,
            'status'       => 'done',
            'estimate'     => 1,
            'consumed'     => 1,
            'left'         => 0,
            'progress'     => 100,
            'estStarted'   => '2023-07-02 00:00:00',
            'realStarted'  => '2023-07-02 00:00:00',
            'finishedDate' => '2023-07-02 00:00:00',
            'closedReason' => '已完成',
        ),
        1 =>
        array(
            'name'         => 'UI設計',
            'pri'          => 1,
            'status'       => 'doing',
            'estimate'     => 2,
            'consumed'     => 1,
            'left'         => 1,
            'progress'     => 50,
            'estStarted'   => '2023-07-03 00:00:00',
            'realStarted'  => '2023-07-03 00:00:00',
            'finishedDate' => '',
            'closedReason' => '',
        ),
        2 =>
        array(
            'name'         => '開發',
            'pri'          => 1,
            'status'       => 'wait',
            'estimate'     => 1,
            'consumed'     => 0,
            'left'         => 1,
            'progress'     => 0,
            'estStarted'   => '',
            'realStarted'  => '',
            'finishedDate' => '',
            'closedReason' => '',
        ),
    ),
);

/* Forms as JSON Schemas. */
$lang->ai->formSchema = array();
$lang->ai->formSchema['story']['create'] = new stdclass();
$lang->ai->formSchema['story']['create']->title = '需求';
$lang->ai->formSchema['story']['create']->type  = 'object';
$lang->ai->formSchema['story']['create']->properties = new stdclass();
$lang->ai->formSchema['story']['create']->properties->title  = new stdclass();
$lang->ai->formSchema['story']['create']->properties->spec   = new stdclass();
$lang->ai->formSchema['story']['create']->properties->verify = new stdclass();
$lang->ai->formSchema['story']['create']->properties->title->type         = 'string';
$lang->ai->formSchema['story']['create']->properties->title->description  = '需求的標題';
$lang->ai->formSchema['story']['create']->properties->spec->type          = 'string';
$lang->ai->formSchema['story']['create']->properties->spec->description   = '需求的描述';
$lang->ai->formSchema['story']['create']->properties->verify->type        = 'string';
$lang->ai->formSchema['story']['create']->properties->verify->description = '需求的驗收標準';
$lang->ai->formSchema['story']['create']->required = array('title', 'spec', 'verify');
$lang->ai->formSchema['story']['change'] = $lang->ai->formSchema['story']['create'];

$lang->ai->formSchema['story']['batchcreate'] = new stdclass();
$lang->ai->formSchema['story']['batchcreate']->title = '批量創建需求';
$lang->ai->formSchema['story']['batchcreate']->type  = 'object';
$lang->ai->formSchema['story']['batchcreate']->properties = new stdclass();
$lang->ai->formSchema['story']['batchcreate']->properties->stories  = new stdclass();
$lang->ai->formSchema['story']['batchcreate']->properties->stories->type        = 'array';
$lang->ai->formSchema['story']['batchcreate']->properties->stories->description = '需求列表';
$lang->ai->formSchema['story']['batchcreate']->properties->stories->items       = $lang->ai->formSchema['story']['create'];

$lang->ai->formSchema['productplan']['create'] = new stdclass();
$lang->ai->formSchema['productplan']['create']->title = '產品計劃';
$lang->ai->formSchema['productplan']['create']->type  = 'object';
$lang->ai->formSchema['productplan']['create']->properties = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->title  = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->begin  = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->end    = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->desc   = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->title->type         = 'string';
$lang->ai->formSchema['productplan']['create']->properties->title->description  = '產品計劃的標題';
$lang->ai->formSchema['productplan']['create']->properties->begin->type         = 'string';
$lang->ai->formSchema['productplan']['create']->properties->begin->description  = '產品計劃的開始時間';
$lang->ai->formSchema['productplan']['create']->properties->end->type           = 'string';
$lang->ai->formSchema['productplan']['create']->properties->end->description    = '產品計劃的結束時間';
$lang->ai->formSchema['productplan']['create']->properties->desc->type          = 'string';
$lang->ai->formSchema['productplan']['create']->properties->desc->description   = '產品計劃的描述';
$lang->ai->formSchema['productplan']['create']->required = array('title', 'begin', 'end');
$lang->ai->formSchema['productplan']['edit'] = $lang->ai->formSchema['productplan']['create'];

$lang->ai->formSchema['task']['create'] = new stdclass();
$lang->ai->formSchema['task']['create']->title = '任務';
$lang->ai->formSchema['task']['create']->type  = 'object';
$lang->ai->formSchema['task']['create']->properties = new stdclass();
$lang->ai->formSchema['task']['create']->properties->type     = new stdclass();
$lang->ai->formSchema['task']['create']->properties->name     = new stdclass();
$lang->ai->formSchema['task']['create']->properties->desc     = new stdclass();
$lang->ai->formSchema['task']['create']->properties->pri      = new stdclass();
$lang->ai->formSchema['task']['create']->properties->estimate = new stdclass();
$lang->ai->formSchema['task']['create']->properties->begin    = new stdclass();
$lang->ai->formSchema['task']['create']->properties->end      = new stdclass();
$lang->ai->formSchema['task']['create']->properties->type->type            = 'string';
$lang->ai->formSchema['task']['create']->properties->type->description     = '任務的類型';
$lang->ai->formSchema['task']['create']->properties->type->enum            = array('design', 'devel', 'request', 'test', 'study', 'discuss', 'ui', 'affair', 'misc');
$lang->ai->formSchema['task']['create']->properties->name->type            = 'string';
$lang->ai->formSchema['task']['create']->properties->name->description     = '任務的名稱';
$lang->ai->formSchema['task']['create']->properties->desc->type            = 'string';
$lang->ai->formSchema['task']['create']->properties->desc->description     = '任務的描述';
$lang->ai->formSchema['task']['create']->properties->pri->type             = 'string';
$lang->ai->formSchema['task']['create']->properties->pri->description      = '任務的優先順序';
$lang->ai->formSchema['task']['create']->properties->pri->enum             = array('1', '2', '3', '4');
$lang->ai->formSchema['task']['create']->properties->estimate->type        = 'number';
$lang->ai->formSchema['task']['create']->properties->estimate->description = '任務的預計工時';
$lang->ai->formSchema['task']['create']->properties->begin->type           = 'string';
$lang->ai->formSchema['task']['create']->properties->begin->format         = 'date';
$lang->ai->formSchema['task']['create']->properties->begin->description    = '任務的預計開始日期';
$lang->ai->formSchema['task']['create']->properties->end->type             = 'string';
$lang->ai->formSchema['task']['create']->properties->end->format           = 'date';
$lang->ai->formSchema['task']['create']->properties->end->description      = '任務的預計結束日期';
$lang->ai->formSchema['task']['create']->required = array('type', 'name');
$lang->ai->formSchema['task']['edit'] = $lang->ai->formSchema['task']['create'];

$lang->ai->formSchema['task']['batchcreate'] = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->title = '批量創建任務';
$lang->ai->formSchema['task']['batchcreate']->type  = 'object';
$lang->ai->formSchema['task']['batchcreate']->properties = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->properties->tasks  = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->type                          = 'array';
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->description                   = '任務列表';
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->items                         = $lang->ai->formSchema['task']['create'];
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->estStarted = clone $lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->begin;
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->deadline   = clone $lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->end;
unset($lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->begin);
unset($lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->end);

$lang->ai->formSchema['bug']['create'] = new stdclass();
$lang->ai->formSchema['bug']['create']->title = 'Bug';
$lang->ai->formSchema['bug']['create']->type  = 'object';
$lang->ai->formSchema['bug']['create']->properties = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->title       = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->steps       = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->severity    = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->pri         = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->openedBuild = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->title->type              = 'string';
$lang->ai->formSchema['bug']['create']->properties->title->description       = 'Bug 的標題';
$lang->ai->formSchema['bug']['create']->properties->steps->type              = 'string';
$lang->ai->formSchema['bug']['create']->properties->steps->description       = '重現步驟';
$lang->ai->formSchema['bug']['create']->properties->severity->type           = 'string';
$lang->ai->formSchema['bug']['create']->properties->severity->description    = 'Bug 的嚴重程度';
$lang->ai->formSchema['bug']['create']->properties->severity->enum           = array('1', '2', '3', '4');
$lang->ai->formSchema['bug']['create']->properties->pri->type                = 'string';
$lang->ai->formSchema['bug']['create']->properties->pri->description         = 'Bug 的優先順序';
$lang->ai->formSchema['bug']['create']->properties->pri->enum                = array('1', '2', '3', '4');
$lang->ai->formSchema['bug']['create']->properties->openedBuild->type        = 'string';
$lang->ai->formSchema['bug']['create']->properties->openedBuild->description = 'Bug 影響的版本';
$lang->ai->formSchema['bug']['create']->properties->openedBuild->enum        = array('trunk');
$lang->ai->formSchema['bug']['create']->required = array('title', 'steps', 'severity', 'pri', 'openedBuild');
$lang->ai->formSchema['bug']['edit'] = $lang->ai->formSchema['bug']['create'];

$lang->ai->formSchema['testcase']['create'] = new stdclass();
$lang->ai->formSchema['testcase']['create']->title = '用例';
$lang->ai->formSchema['testcase']['create']->type  = 'object';
$lang->ai->formSchema['testcase']['create']->properties = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->type         = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->stage        = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->title        = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->precondition = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps        = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items              = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties  = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->steps   = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->expects = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->type->type                                     = 'string';
$lang->ai->formSchema['testcase']['create']->properties->type->description                              = '用例的類型';
$lang->ai->formSchema['testcase']['create']->properties->type->enum                                     = array('feature', 'performance', 'config', 'install', 'security', 'interface', 'unit', 'other');
$lang->ai->formSchema['testcase']['create']->properties->stage->type                                    = 'string';
$lang->ai->formSchema['testcase']['create']->properties->stage->description                             = '用例適用階段';
$lang->ai->formSchema['testcase']['create']->properties->stage->enum                                    = array('unittest', 'feature', 'intergrate', 'system', 'smoke', 'bvt');
$lang->ai->formSchema['testcase']['create']->properties->title->type                                    = 'string';
$lang->ai->formSchema['testcase']['create']->properties->title->description                             = '用例的標題';
$lang->ai->formSchema['testcase']['create']->properties->precondition->type                             = 'string';
$lang->ai->formSchema['testcase']['create']->properties->precondition->description                      = '用例的前置條件';
$lang->ai->formSchema['testcase']['create']->properties->steps->type                                    = 'array';
$lang->ai->formSchema['testcase']['create']->properties->steps->description                             = '用例的步驟列表';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->type                             = 'object';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->steps->type          = 'string';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->steps->description   = '步驟的描述';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->expects->type        = 'string';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->expects->description = '步驟的預期結果';
$lang->ai->formSchema['testcase']['create']->required = array('type', 'title', 'steps');
$lang->ai->formSchema['testcase']['edit'] = $lang->ai->formSchema['testcase']['create'];

$lang->ai->formSchema['testreport']['create'] = new stdclass();
$lang->ai->formSchema['testreport']['create']->title = '測試報告';
$lang->ai->formSchema['testreport']['create']->type  = 'object';
$lang->ai->formSchema['testreport']['create']->properties = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->begin  = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->end    = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->title  = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->report = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->begin->type         = 'string';
$lang->ai->formSchema['testreport']['create']->properties->begin->format       = 'date';
$lang->ai->formSchema['testreport']['create']->properties->begin->description  = '測試開始時間';
$lang->ai->formSchema['testreport']['create']->properties->end->type           = 'string';
$lang->ai->formSchema['testreport']['create']->properties->end->format         = 'date';
$lang->ai->formSchema['testreport']['create']->properties->end->description    = '測試開始時間';
$lang->ai->formSchema['testreport']['create']->properties->title->type         = 'string';
$lang->ai->formSchema['testreport']['create']->properties->title->description  = '測試報告的標題';
$lang->ai->formSchema['testreport']['create']->properties->report->type        = 'string';
$lang->ai->formSchema['testreport']['create']->properties->report->description = '測試報告的內容';
$lang->ai->formSchema['testreport']['create']->required = array('begin', 'end', 'title', 'report');
$lang->ai->formSchema['execution']['testreport'] = $lang->ai->formSchema['testreport']['create'];

$lang->ai->formSchema['doc']['edit'] = new stdclass();
$lang->ai->formSchema['doc']['edit']->title = '文檔';
$lang->ai->formSchema['doc']['edit']->type  = 'object';
$lang->ai->formSchema['doc']['edit']->properties = new stdclass();
$lang->ai->formSchema['doc']['edit']->properties->title   = new stdclass();
$lang->ai->formSchema['doc']['edit']->properties->content = new stdclass();
$lang->ai->formSchema['doc']['edit']->properties->title->type          = 'string';
$lang->ai->formSchema['doc']['edit']->properties->title->description   = '文檔的標題';
$lang->ai->formSchema['doc']['edit']->properties->content->type        = 'string';
$lang->ai->formSchema['doc']['edit']->properties->content->description = '文檔的正文';
$lang->ai->formSchema['doc']['edit']->required = array('title', 'content');

$lang->ai->formSchema['tree']['browse'] = new stdclass();
$lang->ai->formSchema['tree']['browse']->title = '模組';
$lang->ai->formSchema['tree']['browse']->type  = 'object';
$lang->ai->formSchema['tree']['browse']->properties = new stdclass();
$lang->ai->formSchema['tree']['browse']->properties->modules = new stdclass();
$lang->ai->formSchema['tree']['browse']->properties->modules->type  = 'array';
$lang->ai->formSchema['tree']['browse']->properties->modules->title = '模組';
$lang->ai->formSchema['tree']['browse']->properties->modules->items = new stdclass();
$lang->ai->formSchema['tree']['browse']->properties->modules->items->type = 'string';
$lang->ai->formSchema['tree']['browse']->required = array('modules');

$lang->ai->formSchema['programplan']['create'] = new stdclass();
$lang->ai->formSchema['programplan']['create']->title = '計劃階段';
$lang->ai->formSchema['programplan']['create']->type  = 'object';
$lang->ai->formSchema['programplan']['create']->properties = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->type  = 'array';
$lang->ai->formSchema['programplan']['create']->properties->stages->title = '階段列表';
$lang->ai->formSchema['programplan']['create']->properties->stages->items = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->type = 'object';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->names      = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->milestone  = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin      = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end        = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->names->type             = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->names->description      = '階段名稱';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes->type        = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes->description = '階段類型';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes->enum        = array('request', 'design', 'dev', 'qa', 'release', 'review', 'other');
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->milestone->type         = 'boolean';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->milestone->description  = '是否為里程碑';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin->type             = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin->format           = 'date';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin->description      = '階段開始時間';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end->type               = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end->format             = 'date';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end->description        = '階段結束時間';
$lang->ai->formSchema['programplan']['create']->required = array('stages');

$lang->ai->promptMenu = new stdclass();
$lang->ai->promptMenu->dropdownTitle = 'AI';

$lang->ai->dataInject = new stdclass();
$lang->ai->dataInject->success = '已將提詞執行結果填寫到表單中';
$lang->ai->dataInject->fail    = '提詞執行結果填寫失敗';

$lang->ai->execute = new stdclass();
$lang->ai->execute->loading    = '提詞執行中';
$lang->ai->execute->auditing   = '即將跳轉至調試頁面並執行提詞';
$lang->ai->execute->success    = '提詞執行成功';
$lang->ai->execute->fail       = '提詞執行失敗';
$lang->ai->execute->failFormat = '提詞執行失敗：%s。';
$lang->ai->execute->failReasons = array();
$lang->ai->execute->failReasons['noPrompt']     = '提詞不存在';
$lang->ai->execute->failReasons['noObjectData'] = '對象數據獲取失敗';
$lang->ai->execute->failReasons['noResponse']   = '請求返回值為空';
$lang->ai->execute->failReasons['noTargetForm'] = '目標表單地址獲取失敗，或表單必要變數獲取失敗（可能原因為無法找到關聯的對象，請檢查對象間的關聯關係）';
$lang->ai->execute->executeErrors = array();
$lang->ai->execute->executeErrors['-1'] = '提詞不存在';
$lang->ai->execute->executeErrors['-2'] = '對象數據獲取失敗';
$lang->ai->execute->executeErrors['-3'] = '序列化對象數據失敗';
$lang->ai->execute->executeErrors['-4'] = '沒有可用的語言模型';
$lang->ai->execute->executeErrors['-5'] = '表單結構獲取失敗';
$lang->ai->execute->executeErrors['-6'] = 'API 返回值為空或返回了錯誤';

$lang->ai->audit = new stdclass();
$lang->ai->audit->designPrompt = '提詞設計';
$lang->ai->audit->afterSave    = '保存後';
$lang->ai->audit->regenerate   = '重新生成';
$lang->ai->audit->exit         = '退出調試';

$lang->ai->audit->backLocationList = array();
$lang->ai->audit->backLocationList[0] = '返回調試頁面';
$lang->ai->audit->backLocationList[1] = '返回調試頁面並重新生成';

$lang->ai->engineeredPrompts = new stdclass();
$lang->ai->engineeredPrompts->askForFunctionCalling = array((object)array('role' => 'user', 'content' => '請把我所發的下一條消息內容轉換為 function 調用。'), (object)array('role' => 'assistant', 'content' => '好的，我會把下一條消息轉換為 function 調用。'));

$lang->ai->aiResponseException = array();
$lang->ai->aiResponseException['notFunctionCalling'] = 'AI 提詞執行返回值結構不正確，請重試（可能可以通過優化提詞來解決）';

$lang->ai->assistant = new stdclass();
$lang->ai->assistant->view                     = 'AI 助手詳情';
$lang->ai->assistant->title                    = 'AI 助手';
$lang->ai->assistant->create                   = '添加助手';
$lang->ai->assistant->details                  = '基本信息';
$lang->ai->assistant->edit                     = '編輯助手';
$lang->ai->assistant->name                     = 'AI 助手';
$lang->ai->assistant->refModel                 = '引用語言模型';
$lang->ai->assistant->createdDate              = '添加時間';
$lang->ai->assistant->publishedDate            = '發佈時間';
$lang->ai->assistant->desc                     = '簡介';
$lang->ai->assistant->descPlaceholder          = '請簡述此 AI 助手的功能及可以給使用者帶來的體驗。';
$lang->ai->assistant->systemMessage            = '系統內置消息';
$lang->ai->assistant->systemMessagePlaceholder = '您可以賦予此 AI 對話“人設”，例如，“你是一個周報小助手，會根據輸入的內容生成格式化的周報”。';
$lang->ai->assistant->greetings                = '問候語';
$lang->ai->assistant->greetingsPlaceholder     = '您可以設置此AI對話的打招呼文案，例如，“哈嘍，我是你的周報小助手，還在為寫周報困擾嗎，試試將一周的工作發送給我試試？”';
$lang->ai->assistant->publish                  = '發佈';
$lang->ai->assistant->withdraw                 = '停用';
$lang->ai->assistant->confirmPublishTip        = '發佈後將顯示在禪道右下角 AI 對話和客戶端對話中，是否確認發佈？';
$lang->ai->assistant->confirmWithdrawTip       = '停用後前台用戶將無法看到此 AI 助手，是否確認停用？';
$lang->ai->assistant->duplicateTip             = '同一語言模型下的助手名稱不可重複。';
$lang->ai->assistant->confirmDeleteTip         = '確認刪除此 AI 助手？';
$lang->ai->assistant->switchAndClearContext    = '切換助手%s，上下文關係已清除';
$lang->ai->assistant->noLlm                    = '沒有可用的語言模型，請先創建一個。';
$lang->ai->assistant->defaultAssistant         = '全能助手';

$lang->ai->assistant->statusList = array();
$lang->ai->assistant->statusList['0']   = '未發佈';
$lang->ai->assistant->statusList['off'] = '未發佈';
$lang->ai->assistant->statusList['1']   = '已發佈';
$lang->ai->assistant->statusList['on']  = '已發佈';

// for render action changes.
$lang->aiassistant = $lang->ai->assistant;
