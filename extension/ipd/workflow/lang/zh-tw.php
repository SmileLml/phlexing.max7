<?php
global $config;
$lang->workflow->common         = '工作流';
$lang->workflow->browseFlow     = '瀏覽工作流';
$lang->workflow->browseDB       = '瀏覽子表';
$lang->workflow->create         = '新增工作流';
$lang->workflow->copy           = '複製工作流';
$lang->workflow->edit           = '編輯工作流';
$lang->workflow->view           = '工作流詳情';
$lang->workflow->delete         = '刪除工作流';
$lang->workflow->fullTextSearch = '全文檢索';
$lang->workflow->buildIndex     = '重建索引';
$lang->workflow->custom         = '自定義';
$lang->workflow->setApproval    = '審批設置';
$lang->workflow->setJS          = 'JS';
$lang->workflow->setCSS         = 'CSS';
$lang->workflow->backup         = '備份';
$lang->workflow->upgrade        = '升級';
$lang->workflow->upgradeAction  = '升級';
$lang->workflow->preview        = '預覽';
$lang->workflow->design         = '設計';
$lang->workflow->release        = '發佈';
$lang->workflow->syncRelease    = '同步啟用';
$lang->workflow->deactivate     = '停用';
$lang->workflow->activate       = '啟用';
$lang->workflow->createApp      = '新建';
$lang->workflow->cover          = '覆蓋';
$lang->workflow->approval       = '審批';
$lang->workflow->delimiter      = '、';
$lang->workflow->belong         = '從屬於';

$lang->workflow->setFulltextSearch = '全文檢索';

$lang->workflow->id            = '編號';
$lang->workflow->parent        = '父工作流';
$lang->workflow->type          = '類型';
$lang->workflow->app           = '所屬應用';
$lang->workflow->position      = '位置';
$lang->workflow->module        = '工作流代號';
$lang->workflow->table         = '工作流表';
$lang->workflow->name          = '工作流名';
$lang->workflow->icon          = '表徵圖';
$lang->workflow->titleField    = '標題欄位';
$lang->workflow->contentField  = '內容欄位';
$lang->workflow->ui            = '界面設計';
$lang->workflow->js            = 'JS';
$lang->workflow->css           = 'CSS';
$lang->workflow->order         = '順序';
$lang->workflow->buildin       = '內置';
$lang->workflow->administrator = '白名單';
$lang->workflow->desc          = '描述';
$lang->workflow->version       = '版本';
$lang->workflow->status        = '狀態';
$lang->workflow->createdBy     = '由誰創建';
$lang->workflow->createdDate   = '創建時間';
$lang->workflow->editedBy      = '由誰編輯';
$lang->workflow->editedDate    = '編輯時間';
$lang->workflow->currentTime   = '當前時間';

$lang->workflow->actionFlowWidth = 165;

$lang->workflow->copyFlow         = '複製';
$lang->workflow->source           = '源工作流';
$lang->workflow->field            = '欄位';
$lang->workflow->action           = '動作';
$lang->workflow->label            = '標籤';
$lang->workflow->mainTable        = '主表';
$lang->workflow->subTable         = '子表';
$lang->workflow->relation         = '跨工作流設置';
$lang->workflow->report           = '報表';
$lang->workflow->export           = '導出';
$lang->workflow->subTableSettings = '子表及欄位屬性設置';
$lang->workflow->flowchart        = '流程圖';
$lang->workflow->quoteDB          = '使用其他流程子表';

$lang->workflow->statusList['wait']   = '待發佈';
$lang->workflow->statusList['normal'] = '使用中';
$lang->workflow->statusList['pause']  = '停用';

$lang->workflow->syncReleaseList['self']    = '僅啟用工作流對象';
$lang->workflow->syncReleaseList['default'] = '在預設流程啟用該工作流';
$lang->workflow->syncReleaseList['all']     = '在所有流程中啟用該工作流';

$lang->workflow->activateList['all']    = '全部啟用';
$lang->workflow->activateList['single'] = '單獨啟用';

$lang->workflow->releaseList['all']    = '全部發佈';
$lang->workflow->releaseList['single'] = '單獨發佈';

$lang->workflow->positionList['before'] = '之前';
$lang->workflow->positionList['after']  = '之後';

$lang->workflow->belongList['program']   = '項目集';
$lang->workflow->belongList['product']   = $lang->productCommon;
$lang->workflow->belongList['project']   = $lang->projectCommon;
$lang->workflow->belongList['execution'] = $lang->executionCommon;
if($config->vision == 'lite') unset($lang->workflow->belongList['project']);
if($config->systemMode == 'light') unset($lang->workflow->belongList['program']);
if($config->vision == 'or')
{
    $lang->workflow->belongList = array();
    $lang->workflow->belongList['product'] = $lang->productCommon;
}

$lang->workflow->buildinList['0'] = '否';
$lang->workflow->buildinList['1'] = '是';

$lang->workflow->fullTextSearch = new stdclass();
$lang->workflow->fullTextSearch->common       = '全文檢索';
$lang->workflow->fullTextSearch->titleField   = '標題欄位';
$lang->workflow->fullTextSearch->contentField = '內容欄位';

$lang->workflow->charterApprovalAction                      = '發起審批動作';
$lang->workflow->charterApproval['projectApproval']         = '發起立項審批';
$lang->workflow->charterApproval['completionApproval']      = '發起結項審批';
$lang->workflow->charterApproval['cancelProjectApproval']   = '發起取消立項審批';
$lang->workflow->charterApproval['activateProjectApproval'] = '發起激活立項審批';

$lang->workflow->upgrade = new stdclass();
$lang->workflow->upgrade->common         = '升級';
$lang->workflow->upgrade->backup         = '備份';
$lang->workflow->upgrade->backupSuccess  = '備份成功';
$lang->workflow->upgrade->newVersion     = '發現新版本！';
$lang->workflow->upgrade->clickme        = '點擊升級';
$lang->workflow->upgrade->start          = '開始升級';
$lang->workflow->upgrade->currentVersion = '當前版本';
$lang->workflow->upgrade->selectVersion  = '選擇版本';
$lang->workflow->upgrade->confirm        = '確認要執行的SQL語句';
$lang->workflow->upgrade->upgrade        = '升級現有模組';
$lang->workflow->upgrade->upgradeFail    = '升級失敗';
$lang->workflow->upgrade->upgradeSuccess = '升級成功';
$lang->workflow->upgrade->install        = '安裝一個新模組';
$lang->workflow->upgrade->installFail    = '安裝失敗';
$lang->workflow->upgrade->installSuccess = '安裝成功';

/* Tips */
$lang->workflow->tips = new stdclass();
$lang->workflow->tips->noCSSTag              = '不需要&lt;style&gt;&lt;/style&gt;標籤';
$lang->workflow->tips->noJSTag               = '不需要&lt;script&gt;&lt;/script&gt;標籤';
$lang->workflow->tips->flowCSS               = '，加載到所有頁面';
$lang->workflow->tips->flowJS                = '，加載到所有頁面';
$lang->workflow->tips->actionCSS             = '，僅加載到當前動作的頁面';
$lang->workflow->tips->actionJS              = '，僅加載到當前動作的頁面';
$lang->workflow->tips->firstRelease          = '該工作流從屬於%s，發佈後會自動添加到對應流程中，是否同步發佈流程中的工作流？';
$lang->workflow->tips->release               = '該工作流發佈後，流程管理中的通用流程會同步修改。';
$lang->workflow->tips->activate              = '工作流啟用後，流程管理中的通用流程和自定義流程是否也要一起啟用？';
$lang->workflow->tips->deactivate            = '您確定要停用此工作流嗎？';
$lang->workflow->tips->syncDeactivate        = '該工作流停用後，流程管理中不能使用該工作流對象。';
$lang->workflow->tips->belongDisabled        = '該工作流已在流程管理中設置了自定義流程，不能修改從屬對象。';
$lang->workflow->tips->create                = '太棒了！您已經成功創建了一個新工作流，現在要去設計您的工作流嗎？';
$lang->workflow->tips->subTable              = '填寫的表單中，還需要填寫具體的明細信息時，可以通過子表來實現。場景舉例：提交報銷申請時，還需填寫報銷明細。此時，可通過在報銷中新增子表"報銷明細"來實現。';
$lang->workflow->tips->buildinFlow           = '內置工作流暫不支持使用快捷編輯器。';
$lang->workflow->tips->fullTextSearch        = '使用全文檢索功能需要設置哪些欄位的內容可以被檢索到。標題欄位在全文檢索中的權重較大，內容欄位權重較小。<br/>權重越大，在搜索結果中越靠前。<br/>設置欄位後需要重建索引才能生效。重建索引的速度和內容數量成正比，請耐心等待索引重建完成。';
$lang->workflow->tips->buildIndex            = '重建索引可能需要一段時間，確定執行操作嗎？';
$lang->workflow->tips->deleteConfirm         = "<p class='text-lg font-bold'>您確定要刪除該工作流嗎？</p><p>刪除後，關聯的數據都會被刪除，如歷史記錄、審批記錄等。</p><p>包括該工作流配置的自定義工作流及自定義工作流產生的數據。</p><p class='text-danger'><b>該操作是不可逆的，刪除的內容無法恢復！</b></p>";
$lang->workflow->tips->belong                = '該工作流將按照從屬對象進行數據隔離，選擇從屬於產品、項目、執行後會加入對應的流程中。';
$lang->workflow->tips->belongError           = '該工作流已在%s流程管理下設置了自定義流程，無法切換所屬視圖。';
$lang->workflow->tips->noQuoteTables         = '其他模板沒有可以引用的子表。';
$lang->workflow->tips->subTableSync          = '該子表已在%s中引用，修改後將同步修改。';
$lang->workflow->tips->notEditTable          = '引用的子表不能編輯。';
$lang->workflow->tips->confirmDeleteHasQuote = '刪除後，其他模板中引用該子表的數據均被同步刪除，操作不可逆，您確定要刪除嗎？';
$lang->workflow->tips->confirmDeleteInQuote  = '移除後，該工作流內使用子表內欄位的配置均同步刪除，操作不可逆，您確定要移除嗎？';

$lang->workflow->notNow   = '暫不';
$lang->workflow->toDesign = '去設計';

/* Title */
$lang->workflow->title = new stdclass();
$lang->workflow->title->subTable   = '明細表用來存儲%s記錄的明細';
$lang->workflow->title->noCopy     = '內置工作流不能複製。';
$lang->workflow->title->noLabel    = '內置工作流不能添加標籤。';
$lang->workflow->title->noSubTable = '內置工作流不能添加明細表。';
$lang->workflow->title->noRelation = '內置工作流不能進行跨工作流設置。';
$lang->workflow->title->noJS       = '內置工作流不能設置js。';
$lang->workflow->title->noCSS      = '內置工作流不能設置css。';
$lang->workflow->title->remove     = '移除';

/* Placeholder */
$lang->workflow->placeholder = new stdclass();
$lang->workflow->placeholder->module       = '只能包含英文字母，保存後不可更改';
$lang->workflow->placeholder->titleField   = '標題欄位只能有一個，在全文檢索中的權重較小';
$lang->workflow->placeholder->contentField = '內容欄位可以有多個，在全文檢索中的權重較大';

/* Error */
$lang->workflow->error = new stdclass();
$lang->workflow->error->createTableFail = '自定義流程數據表創建失敗。';
$lang->workflow->error->buildInModule   = '不能使用系統內置模組作為工作流代號。';
$lang->workflow->error->wrongCode       = '『%s』只能包含英文字母。';
$lang->workflow->error->conflict        = '『%s』與系統語言衝突。';
$lang->workflow->error->notFound        = '工作流『%s』未找到。';
$lang->workflow->error->flowLimit       = '您只能創建 %s 個工作流。';
$lang->workflow->error->buildIndexFail  = '重建索引失敗。';
$lang->workflow->error->unique          = '『%s』已被其他模板使用。如需使用此欄位，請從【使用其他流程子表】中選擇。';

$lang->workflow->notice = new stdclass();
$lang->workflow->notice->autoAddBelong = '系統將自動為您在新建頁面增加“%s”欄位。';

$lang->workflowtable = new stdclass();
$lang->workflowtable->common = '明細表';
$lang->workflowtable->browse = '瀏覽表';
$lang->workflowtable->create = '新增表';
$lang->workflowtable->edit   = '編輯表';
$lang->workflowtable->view   = '表詳情';
$lang->workflowtable->delete = '刪除表';
$lang->workflowtable->module = '表代號';
$lang->workflowtable->name   = '表名';
$lang->workflowtable->use    = '使用子表';

$lang->workfloweditor = new stdclass();
$lang->workfloweditor->nextStep              = '下一步';
$lang->workfloweditor->prevStep              = '上一步';
$lang->workfloweditor->quickEditor           = '快捷編輯器';
$lang->workfloweditor->advanceEditor         = '高級編輯器';
$lang->workfloweditor->switchTo              = '切換至%s';
$lang->workfloweditor->switchConfirmMessage  = '將切換到高級工作流編輯器，<br>您可以在高級編輯器進行擴展設置、標籤設計和子表設計等操作。<br>確定切換嗎？';
$lang->workfloweditor->cancelSwitch          = '暫不切換';
$lang->workfloweditor->confirmSwitch         = '確認切換';
$lang->workfloweditor->elementCode           = '代號';
$lang->workfloweditor->elementType           = '類型';
$lang->workfloweditor->elementName           = '名稱';
$lang->workfloweditor->nameAndCodeRequired   = '名稱和代號不能為空';
$lang->workfloweditor->uiDesign              = '界面設計';
$lang->workfloweditor->selectField           = '欄位控制選取';
$lang->workfloweditor->uiPreview             = '界面預覽';
$lang->workfloweditor->fieldProperties       = '欄位屬性操作';
$lang->workfloweditor->uiControls            = '控件';
$lang->workfloweditor->showedFields          = '已有欄位';
$lang->workfloweditor->selectFieldToEditTip  = '點擊選擇表單欄位後在此處編輯';
$lang->workfloweditor->addFieldOption        = '添加選項';
$lang->workfloweditor->confirmReleaseMessage = '您還可以通過工作流高級編輯器進行例如擴展動作、篩選標籤等設置，您確定現在要發佈嗎？';
$lang->workfloweditor->switchMessage         = '您也可以通過此處進行編輯器的切換哦';
$lang->workfloweditor->continueRelease       = '繼續發佈';
$lang->workfloweditor->enterToAdvance        = '進入高級編輯器';
$lang->workfloweditor->labelAll              = '所有';
$lang->workfloweditor->confirmToDelete       = '確定刪除此%s？';
$lang->workfloweditor->leavePageTip          = '當前頁面有沒有保存的內容，確定要離開頁面嗎？';
$lang->workfloweditor->addFile               = '添加附件';
$lang->workfloweditor->fieldWidth            = '列寬度';
$lang->workfloweditor->fieldPosition         = '對齊方式';
$lang->workfloweditor->dragDropTip           = '拖放到這裡';
$lang->workfloweditor->moreSettingsLabel     = '更多設置';

$lang->workfloweditor->quickSteps = array();
$lang->workfloweditor->quickSteps['ui'] = '界面設計|workflow|ui';

$lang->workfloweditor->advanceSteps = array();
$lang->workfloweditor->advanceSteps['mainTable'] = '主表設計|workflowfield|browse';
$lang->workfloweditor->advanceSteps['subTable']  = '子表設計|workflow|browsedb';
$lang->workfloweditor->advanceSteps['action']    = '動作設計|workflowaction|browse';
$lang->workfloweditor->advanceSteps['label']     = '標籤設計|workflowlabel|browse';
$lang->workfloweditor->advanceSteps['setting']   = array('link' => '更多設置|workflow|more', 'subMenu' => array('workflowrelation' => 'admin', 'workflowfield' => 'setValue,setExport,setSearch', 'workflow' => 'setJS,setCSS,setFulltextSearch,setApproval', 'workflowreport' => 'browse'));

$lang->workfloweditor->moreSettings = array();
$lang->workfloweditor->moreSettings['approval']  = "審批設置|workflow|setapproval|module=%s";
$lang->workfloweditor->moreSettings['relation']  = "跨工作流設置|workflowrelation|admin|prev=%s";
$lang->workfloweditor->moreSettings['setReport'] = "報表設置|workflowreport|browse|module=%s";
$lang->workfloweditor->moreSettings['setValue']  = "顯示值設置|workflowfield|setValue|module=%s";
$lang->workfloweditor->moreSettings['setExport'] = "導出設置|workflowfield|setExport|module=%s";
$lang->workfloweditor->moreSettings['setSearch'] = "搜索設置|workflowfield|setSearch|module=%s";
$lang->workfloweditor->moreSettings['fulltext']  = "全文檢索|workflow|setFulltextSearch|id=%s";
$lang->workfloweditor->moreSettings['setJS']     = "JS|workflow|setJS|id=%s";
$lang->workfloweditor->moreSettings['setCSS']    = "CSS|workflow|setCSS|id=%s";

if(empty($config->openedApproval)) unset($lang->workfloweditor->moreSettings['approval']);

$lang->workfloweditor->validateMessages = array();
$lang->workfloweditor->validateMessages['nameRequired']        = '必須填寫欄位名稱';
$lang->workfloweditor->validateMessages['nameDuplicated']      = '存在重複的欄位名稱，請使用不同名稱';
$lang->workfloweditor->validateMessages['fieldRequired']       = '必須填寫欄位代號';
$lang->workfloweditor->validateMessages['fieldInvalid']        = '欄位代號只能包含英文字母';
$lang->workfloweditor->validateMessages['fieldDuplicated']     = '欄位代號與已有欄位“%s”重複，請使用不同的代號';
$lang->workfloweditor->validateMessages['lengthRequired']      = '必須指定類型長度';
$lang->workfloweditor->validateMessages['failSummary']         = '%s個欄位存在錯誤，請修改後再進行保存。';
$lang->workfloweditor->validateMessages['defaultNotInOptions'] = '預設值“%s”不在可選項中。';
$lang->workfloweditor->validateMessages['defaultNotOptionKey'] = '應該使用選項“%s”的“鍵”作為預設值。';
$lang->workfloweditor->validateMessages['widthInvalid']        = '寬度值必須為數值或者 “auto”';

$lang->workfloweditor->error = new stdclass();
$lang->workfloweditor->error->unknown = '未知的錯誤，請重新提交。如果重複多次仍無法保存，請刷新頁面後再嘗試。';

$lang->workflowapproval = new stdclass();
$lang->workflowapproval->enabled         = '啟用審批功能';
$lang->workflowapproval->approval        = '審批';
$lang->workflowapproval->approvalFlow    = '審批流';
$lang->workflowapproval->noApproval      = '沒有可以使用的審批流，';
$lang->workflowapproval->createTips      = array('您可以', '您可以聯繫管理員創建審批流。');
$lang->workflowapproval->createApproval  = '創建審批流';
$lang->workflowapproval->waiting         = '審批中';
$lang->workflowapproval->conflictField   = '欄位：';
$lang->workflowapproval->conflictAction  = '動作：';
$lang->workflowapproval->openLater       = '您也可以稍後在高級編輯器中開啟或關閉審批功能。';
$lang->workflowapproval->disableApproval = '該工作流無法開啟審批功能。';
$lang->workflowapproval->conflict        = array('啟用審批', '啟用審批功能需要添加新的欄位和動作，系統檢測到以下欄位和動作存在衝突：', '您可以點擊【取消】自己解決衝突，如“修改欄位代號、刪除欄位、刪除動作”，之後重新啟用審批功能。', '您也可以點擊【覆蓋】由系統解決衝突。系統會刪除衝突的欄位和動作，並添加新的欄位和動作。', '注意：覆蓋操作是不可逆的，刪除的欄位和動作無法恢復！');

$lang->workflowapproval->approvalList = array('enabled' => '開啟', 'disabled' => '關閉');

$lang->workflowapproval->tips = new stdclass();
$lang->workflowapproval->tips->processesInProgress = '有審批流程正在進行中，請審批完成或撤回。';

$lang->workflowapproval->buildInFields = array('name' => array(), 'options' => array());
$lang->workflowapproval->buildInFields['name']['reviewers']     = '審批人';
$lang->workflowapproval->buildInFields['name']['reviewStatus']  = '審批狀態';
$lang->workflowapproval->buildInFields['name']['reviewResult']  = '審批結果';
$lang->workflowapproval->buildInFields['name']['reviewOpinion'] = '審批意見';

$lang->workflowapproval->buildInFields['options']['reviewStatus'] = array('wait' => '待審批', 'doing' => '審批中', 'pass' => '通過', 'reject' => '不通過', 'reverting' => '回退');
$lang->workflowapproval->buildInFields['options']['reviewResult'] = array('pass' => '通過', 'reject' => '不通過');

$lang->workflowapproval->buildInActions = array('name' => array('submit' => '提交', 'cancel' => '撤回', 'review' => '評審'));
