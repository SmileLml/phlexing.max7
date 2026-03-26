<?php
/**
 * The doc module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: zh-tw.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->doclib = new stdclass();
$lang->doclib->name         = '庫名稱';
$lang->doclib->control      = '訪問控制';
$lang->doclib->group        = '分組';
$lang->doclib->user         = '用戶';
$lang->doclib->files        = '附件庫';
$lang->doclib->all          = '所有文檔庫';
$lang->doclib->select       = '選擇文檔庫';
$lang->doclib->execution    = $lang->executionCommon . '庫';
$lang->doclib->product      = $lang->productCommon . '庫';
$lang->doclib->apiLibName   = '庫名稱';
$lang->doclib->defaultSpace = '預設空間';
$lang->doclib->defaultMyLib = '我的庫';
$lang->doclib->spaceName    = '空間名稱';
$lang->doclib->createSpace  = '新建空間';
$lang->doclib->editSpace    = '編輯空間';
$lang->doclib->privateACL   = "私有 （僅創建者和有%s權限的白名單用戶可訪問）";
$lang->doclib->defaultOrder = '文檔預設排序';
$lang->doclib->migratedWiki = '已遷移的Wiki';

$lang->doclib->tip = new stdclass();
$lang->doclib->tip->selectExecution = "執行為空時，創建的庫為{$lang->projectCommon}庫";

$lang->doclib->type['wiki'] = '文檔庫';
$lang->doclib->type['api']  = '介面庫';

$lang->doclib->aclListA = array();
$lang->doclib->aclListA['default'] = '預設';
$lang->doclib->aclListA['custom']  = '自定義';

$lang->doclib->aclListB['open']    = '公開';
$lang->doclib->aclListB['custom']  = '自定義';
$lang->doclib->aclListB['private'] = '私有';

$lang->doclib->mySpaceAclList['private'] = "私有（僅創建者可訪問）";

$lang->doclib->aclList = array();
$lang->doclib->aclList['open']    = "公開 （有文檔視圖權限即可訪問）";
$lang->doclib->aclList['default'] = "預設 （有所選%s訪問權限用戶可以訪問）";
$lang->doclib->aclList['private'] = "私有 （僅創建者和白名單用戶可訪問）";

$lang->doclib->idOrder = array();
$lang->doclib->idOrder['id_asc']  = 'ID 正序';
$lang->doclib->idOrder['id_desc'] = 'ID 倒序';

$lang->doclib->create['product']   = '創建' . $lang->productCommon . '文檔庫';
$lang->doclib->create['execution'] = '創建' . $lang->executionCommon . '文檔庫';
$lang->doclib->create['custom']    = '創建自定義文檔庫';

$lang->doclib->main['product']   = $lang->productCommon . '主庫';
$lang->doclib->main['project']   = "{$lang->projectCommon}主庫";
$lang->doclib->main['execution'] = $lang->executionCommon . '主庫';

$lang->doclib->tabList['product']   = $lang->productCommon;
$lang->doclib->tabList['execution'] = $lang->executionCommon;
$lang->doclib->tabList['custom']    = '自定義';

$lang->doclib->nameList['custom'] = '自定義文檔庫名稱';

$lang->doclib->apiNameUnique = array();
$lang->doclib->apiNameUnique['product'] = '同一' . $lang->productCommon . '下的介面庫中';
$lang->doclib->apiNameUnique['project'] = '同一' . $lang->projectCommon . '下的介面庫中';
$lang->doclib->apiNameUnique['nolink']  = '獨立介面庫中';

$lang->docTemplate = new stdclass();
$lang->docTemplate->id                           = '編號';
$lang->docTemplate->title                        = '模板標題';
$lang->docTemplate->frequency                    = '頻率';
$lang->docTemplate->type                         = '分類';
$lang->docTemplate->addedBy                      = '創建者';
$lang->docTemplate->addedDate                    = '創建日期';
$lang->docTemplate->editedBy                     = '修改者';
$lang->docTemplate->editedDate                   = '修改日期';
$lang->docTemplate->views                        = '閲讀次數';
$lang->docTemplate->confirmDelete                = '您確定刪除該文檔模板嗎？';
$lang->docTemplate->scope                        = '所屬範圍';
$lang->docTemplate->lib                          = $lang->docTemplate->scope;
$lang->docTemplate->module                       = '模板分類';
$lang->docTemplate->desc                         = '描述';
$lang->docTemplate->parentModule                 = '上級分類';
$lang->docTemplate->typeName                     = '分類名稱';
$lang->docTemplate->parent                       = '所屬層級';
$lang->docTemplate->addTemplateType              = '添加模板分類';
$lang->docTemplate->editTemplateType             = '編輯模板分類';
$lang->docTemplate->docTitlePlaceholder          = '請輸入文檔模板標題';
$lang->docTemplate->docTitleRequired             = '文檔模板標題不能為空。';
$lang->docTemplate->errorDeleteType              = '當前分類存在文檔模板，不可刪除';
$lang->docTemplate->convertToNewDocConfirm       = '全新文檔格式使用現代化塊級編輯器，帶來全新的文檔功能體驗。確定要將此文檔模板轉換為新文檔格式嗎？存為草稿或者發佈後，不能再切換回舊編輯器。';
$lang->docTemplate->oldDocEditingTip             = '此文檔模板為舊版本編輯器創建，已啟用新版編輯器編輯，保存後將轉換為新版文檔模板';
$lang->docTemplate->leaveEditingConfirm          = '文檔模板編輯中，確定離開嗎？';
$lang->docTemplate->searchScopePlaceholder       = '搜索範圍';
$lang->docTemplate->searchTypePlaceholder        = '搜索分類';
$lang->docTemplate->moveDocTemplate              = '移動文檔模板';
$lang->docTemplate->moveSubTemplate              = '移動子文檔模板';
$lang->docTemplate->createTypeFirst              = '請先創建文檔模板分類。';
$lang->docTemplate->editedList                   = '模板編輯者';
$lang->docTemplate->content                      = '模板內容';
$lang->docTemplate->templateDesc                 = '模板描述';
$lang->docTemplate->status                       = '模板狀態';
$lang->docTemplate->emptyTip                     = '此參數與篩選條件下，暫無符合條件系統數據。';
$lang->docTemplate->previewTip                   = '配置參數後，此區塊會根據篩選器的配置展示相應的列表數據。';
$lang->docTemplate->confirmDeleteChapterWithSub  = "刪除章節後，章節下層級內容將一併隱藏，確定要刪除該章節嗎？";
$lang->docTemplate->confirmDeleteTemplateWithSub = "刪除文檔模板後，文檔模板下層級內容將一併隱藏，確定要刪除該文檔模板嗎？";
$lang->docTemplate->scopeHasTemplateTips         = '該範圍下有文檔模板，請移除後再刪除範圍。';
$lang->docTemplate->scopeHasModuleTips           = '該範圍下有模板分類數據，請移除後再刪除範圍。';
$lang->docTemplate->needEditable                 = '您沒有當前文檔模板的編輯權限。';

$lang->docTemplate->more       = '更多';
$lang->docTemplate->scopeLabel = '範圍';
$lang->docTemplate->noTemplate = '沒有文檔模板';
$lang->docTemplate->noDesc     = '暫時沒有描述';
$lang->docTemplate->of         = '的';
$lang->docTemplate->overdue    = '已過期';

$lang->docTemplate->create = '創建模板';
$lang->docTemplate->edit   = '編輯文檔模板';
$lang->docTemplate->delete = '刪除文檔模板';

$lang->docTemplate->addModule         = '添加分類';
$lang->docTemplate->addSameModule     = '添加同級分類';
$lang->docTemplate->addSubModule      = '添加子分類';
$lang->docTemplate->editModule        = '編輯分類';
$lang->docTemplate->deleteModule      = '刪除分類';
$lang->docTemplate->noModules         = '沒有文檔模板分類';
$lang->docTemplate->addSubDocTemplate = '添加子文檔模板';

$lang->docTemplate->filterTypes = array();
$lang->docTemplate->filterTypes[] = array('all', '全部');
$lang->docTemplate->filterTypes[] = array('draft', '草稿');
$lang->docTemplate->filterTypes[] = array('released', '已發佈');
$lang->docTemplate->filterTypes[] = array('createdByMe', '我創建的');

/* 欄位列表。*/
$lang->doc->common       = '文檔';
$lang->doc->id           = 'ID';
$lang->doc->product      = '所屬' . $lang->productCommon;
$lang->doc->project      = "所屬{$lang->projectCommon}";
$lang->doc->execution    = '所屬' . $lang->execution->common;
$lang->doc->plan         = '所屬計劃';
$lang->doc->lib          = '所屬庫';
$lang->doc->module       = '所屬父級';
$lang->doc->libAndModule = '所屬庫&目錄';
$lang->doc->object       = '所屬對象';
$lang->doc->title        = '文檔標題';
$lang->doc->digest       = '文檔摘要';
$lang->doc->comment      = '文檔備註';
$lang->doc->type         = '文檔類型';
$lang->doc->content      = '文檔正文';
$lang->doc->keywords     = '關鍵字';
$lang->doc->status       = '文檔狀態';
$lang->doc->url          = '文檔URL';
$lang->doc->files        = '附件';
$lang->doc->addedBy      = '由誰添加';
$lang->doc->addedByAB    = '創建者';
$lang->doc->addedDate    = '創建日期';
$lang->doc->editedBy     = '修改者';
$lang->doc->editedDate   = '修改日期';
$lang->doc->editingDate  = '正在修改者和時間';
$lang->doc->lastEditedBy = '最後更新者';
$lang->doc->updateInfo   = '更新信息';
$lang->doc->version      = '版本號';
$lang->doc->basicInfo    = '基本信息';
$lang->doc->deleted      = '已刪除';
$lang->doc->fileObject   = '所屬對象';
$lang->doc->whiteList    = '白名單';
$lang->doc->readonly     = '只讀';
$lang->doc->editable     = '可編輯';
$lang->doc->contentType  = '文檔格式';
$lang->doc->separator    = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle    = '附件名稱';
$lang->doc->filePath     = '地址';
$lang->doc->extension    = '類型';
$lang->doc->size         = '附件大小';
$lang->doc->source       = '來源';
$lang->doc->download     = '下載';
$lang->doc->acl          = '權限';
$lang->doc->fileName     = '附件';
$lang->doc->groups       = '分組';
$lang->doc->users        = '用戶';
$lang->doc->item         = '項';
$lang->doc->num          = '文檔數量';
$lang->doc->searchResult = '搜索結果';
$lang->doc->mailto       = '抄送給';
$lang->doc->noModule     = '文檔庫下沒有目錄和文檔，請維護目錄或者創建文檔';
$lang->doc->noChapter    = '手冊下沒有章節和文章，請維護手冊';
$lang->doc->views        = '瀏覽次數';
$lang->doc->draft        = '草稿';
$lang->doc->collector    = '收藏者';
$lang->doc->main         = '文檔主庫';
$lang->doc->order        = '排序';
$lang->doc->doc          = '文檔';
$lang->doc->updateOrder  = '更新排序';
$lang->doc->update       = '更新';
$lang->doc->nextStep     = '下一步';
$lang->doc->closed       = '已關閉';
$lang->doc->saveDraft    = '存為草稿';
$lang->doc->template     = '模板';
$lang->doc->position     = '所在位置';
$lang->doc->person       = '個人';
$lang->doc->team         = '團隊';
$lang->doc->manage       = '文檔管理';
$lang->doc->release      = '發佈';
$lang->doc->story        = '需求';
$lang->doc->convertdoc   = '轉換為文檔';
$lang->doc->needEditable = '您沒有當前文檔的編輯權限。';
$lang->doc->needReadable = '您沒有當前文檔的閲讀權限。';
$lang->doc->groupLabel   = '分組';
$lang->doc->userLabel    = '用戶';

$lang->doc->moduleDoc     = '按模組瀏覽';
$lang->doc->searchDoc     = '搜索';
$lang->doc->fast          = '快速訪問';
$lang->doc->allDoc        = '全部文檔';
$lang->doc->allVersion    = '全部版本';
$lang->doc->openedByMe    = '我的創建';
$lang->doc->editedByMe    = '我的編輯';
$lang->doc->orderByOpen   = '最近添加';
$lang->doc->orderByEdit   = '最近更新';
$lang->doc->orderByVisit  = '最近訪問';
$lang->doc->todayEdited   = '今日更新';
$lang->doc->pastEdited    = '往日更新';
$lang->doc->myDoc         = '我的文檔';
$lang->doc->myView        = '最近瀏覽';
$lang->doc->myCollection  = '我收藏的';
$lang->doc->myCreation    = '我創建的';
$lang->doc->myEdited      = '我編輯的';
$lang->doc->myLib         = '我的個人庫';
$lang->doc->tableContents = '目錄';
$lang->doc->addCatalog    = '添加目錄';
$lang->doc->editCatalog   = '編輯目錄';
$lang->doc->deleteCatalog = '刪除目錄';
$lang->doc->sortCatalog   = '目錄排序';
$lang->doc->sortDoclib    = '庫排序';
$lang->doc->sortDoc       = '文檔排序';
$lang->doc->docStatistic  = '文檔統計';
$lang->doc->docCreated    = '創建的文檔';
$lang->doc->docEdited     = '編輯的文檔';
$lang->doc->docViews      = '被瀏覽量';
$lang->doc->docCollects   = '被收藏量';
$lang->doc->todayUpdated  = '今天更新';
$lang->doc->daysUpdated   = '%s天前更新';
$lang->doc->monthsUpdated = '%s月前更新';
$lang->doc->yearsUpdated  = '%s年前更新';
$lang->doc->viewCount     = '%s次瀏覽';
$lang->doc->collectCount  = '%s次收藏';

/* 方法列表。*/
$lang->doc->index            = '儀表盤';
$lang->doc->createAB         = '創建';
$lang->doc->create           = '創建文檔';
$lang->doc->createOrUpload   = '創建/導入文檔';
$lang->doc->edit             = '編輯文檔';
$lang->doc->effort           = '日誌';
$lang->doc->delete           = '刪除文檔';
$lang->doc->createBook       = '創建手冊';
$lang->doc->browse           = '文檔列表';
$lang->doc->view             = '文檔詳情';
$lang->doc->diff             = '對比';
$lang->doc->cancelDiff       = '取消對比';
$lang->doc->diffAction       = '對比文檔';
$lang->doc->sort             = '文檔排序';
$lang->doc->manageType       = '維護目錄';
$lang->doc->editType         = '編輯目錄';
$lang->doc->editChildType    = '維護子目錄';
$lang->doc->deleteType       = '刪除目錄';
$lang->doc->addType          = '增加目錄';
$lang->doc->childType        = '子目錄';
$lang->doc->catalogName      = '目錄名稱';
$lang->doc->collect          = '收藏';
$lang->doc->collectSuccess   = '收藏成功';
$lang->doc->cancelCollection = '取消收藏';
$lang->doc->deleteFile       = '刪除附件';
$lang->doc->menuTitle        = '目錄';
$lang->doc->api              = '介面';
$lang->doc->displaySetting   = '顯示設置';
$lang->doc->collectAction    = '收藏文檔';

$lang->doc->libName            = '庫名稱';
$lang->doc->libType            = '庫類型';
$lang->doc->custom             = '自定義文檔庫';
$lang->doc->customAB           = '自定義庫';
$lang->doc->createLib          = '創建庫';
$lang->doc->createLibAction    = '創建庫';
$lang->doc->createSpace        = '創建空間';
$lang->doc->allLibs            = '庫列表';
$lang->doc->objectLibs         = "庫文檔詳情";
$lang->doc->showFiles          = '附件庫';
$lang->doc->editLib            = '編輯庫';
$lang->doc->editSpaceAction    = '編輯空間';
$lang->doc->editLibAction      = '編輯庫';
$lang->doc->deleteSpaceAction  = '刪除空間';
$lang->doc->deleteLibAction    = '刪除庫';
$lang->doc->moveLibAction      = '移動庫';
$lang->doc->moveDocAction      = '移動文檔';
$lang->doc->batchMove          = '批量移動';
$lang->doc->batchMoveDocAction = '批量移動文檔';
$lang->doc->fixedMenu          = '固定到菜單欄';
$lang->doc->removeMenu         = '從菜單欄移除';
$lang->doc->search             = '搜索';
$lang->doc->allCollections     = '查看全部收藏文檔';
$lang->doc->keywordsTips       = '多個關鍵字請用逗號分隔。';
$lang->doc->sortLibs           = '文檔庫排序';
$lang->doc->titlePlaceholder   = '在此輸入標題';
$lang->doc->confirm            = '確認';
$lang->doc->docSummary         = '本頁共 <strong>%s</strong> 個文檔。';
$lang->doc->docCheckedSummary  = '共選中 <strong>%total%</strong> 個文檔。';
$lang->doc->showDoc            = '是否顯示文檔';
$lang->doc->uploadFile         = '上傳檔案';
$lang->doc->uploadDoc          = '導入';
$lang->doc->uploadFormat       = '上傳格式';
$lang->doc->editedList         = '文檔編輯者';
$lang->doc->moveTo             = '移動至';
$lang->doc->notSupportExport   = '（此文檔暫不支持導出）';
$lang->doc->downloadTemplate   = '下載模板';
$lang->doc->addFile            = '提交檔案';

$lang->doc->preview         = '預覽';
$lang->doc->insertTitle     = '插入%s列表';
$lang->doc->previewTip      = '通過篩選配置可以修改插入內容的展示數據，插入的數據為靜態的數據快照。';
$lang->doc->insertTip       = '請預覽後至少選擇一條數據。';
$lang->doc->insertText      = '插入';
$lang->doc->searchCondition = '搜索條件';
$lang->doc->list            = '列表';
$lang->doc->detail          = '詳情';
$lang->doc->zentaoData      = '禪道數據';
$lang->doc->emptyError      = '不能為空';
$lang->doc->caselib         = '用例庫';
$lang->doc->customSearch    = '自定義搜索';

$lang->doc->addChapter     = '添加章節';
$lang->doc->editChapter    = '編輯章節';
$lang->doc->sortChapter    = '章節排序';
$lang->doc->deleteChapter  = '刪除章節';
$lang->doc->addSubChapter  = '添加子章節';
$lang->doc->addSameChapter = '添加同級章節';
$lang->doc->addSubDoc      = '添加子文檔';
$lang->doc->chapterName    = '章節名稱';

$lang->doc->tips = new stdclass();
$lang->doc->tips->noProduct   = '暫時沒有產品，請先創建';
$lang->doc->tips->noProject   = '暫時沒有項目，請先創建';
$lang->doc->tips->noExecution = '暫時沒有執行，請先創建';
$lang->doc->tips->noCaselib   = '暫時沒有用例庫，請先創建';

$lang->doc->zentaoList = array();
$lang->doc->zentaoList['story']          = $lang->SRCommon;
$lang->doc->zentaoList['productStory']   = $lang->productCommon . $lang->SRCommon;
$lang->doc->zentaoList['projectStory']   = $lang->projectCommon . $lang->SRCommon;
$lang->doc->zentaoList['executionStory'] = $lang->execution->common . $lang->SRCommon;
$lang->doc->zentaoList['planStory']      = $lang->productplan->shortCommon . $lang->SRCommon;

$lang->doc->zentaoList['case']        = $lang->testcase->common;
$lang->doc->zentaoList['productCase'] = $lang->productCommon . $lang->testcase->common;
$lang->doc->zentaoList['projectCase'] = $lang->projectCommon . $lang->testcase->common;
$lang->doc->zentaoList['caselib']     = '用例庫' . $lang->testcase->common;

$lang->doc->zentaoList['task']       = $lang->task->common;
$lang->doc->zentaoList['bug']        = $lang->bug->common;
$lang->doc->zentaoList['productBug'] = '產品Bug';
$lang->doc->zentaoList['planBug']    = '計劃Bug';

$lang->doc->zentaoList['more']               = '更多';
$lang->doc->zentaoList['productPlan']        = $lang->productCommon . '下計劃';
$lang->doc->zentaoList['productPlanContent'] = $lang->productCommon . '計划下的內容';
$lang->doc->zentaoList['productRelease']     = $lang->productCommon . $lang->release->common;
$lang->doc->zentaoList['projectRelease']     = $lang->projectCommon . $lang->release->common;
$lang->doc->zentaoList['ER']                 = $lang->defaultERName;
$lang->doc->zentaoList['UR']                 = $lang->URCommon;
$lang->doc->zentaoList['feedback']           = '反饋';
$lang->doc->zentaoList['ticket']             = '工單';
$lang->doc->zentaoList['gantt']              = '甘特圖';

$lang->doc->zentaoAction = array();
$lang->doc->zentaoAction['set']       = '設置';
$lang->doc->zentaoAction['delete']    = '刪除';
$lang->doc->zentaoAction['setParams'] = '配置參數';

$lang->doc->uploadFormatList = array();
$lang->doc->uploadFormatList['separateDocs'] = '每個檔案存為不同文檔';
$lang->doc->uploadFormatList['combinedDocs'] = '所有檔案存為一個文檔';

$lang->doc->fileType = new stdclass();
$lang->doc->fileType->stepResult = '測試結果';

global $config;
/* 查詢條件列表 */
$lang->doc->allProduct    = '所有' . $lang->productCommon;
$lang->doc->allExecutions = '所有' . $lang->execution->common;
$lang->doc->allProjects   = '所有' . $lang->projectCommon;

$lang->doc->libTypeList['product']   = $lang->productCommon . '文檔庫';
$lang->doc->libTypeList['project']   = "{$lang->projectCommon}文檔庫";
$lang->doc->libTypeList['execution'] = $lang->execution->common . '文檔庫';
$lang->doc->libTypeList['api']       = '介面庫';
$lang->doc->libTypeList['custom']    = '自定義文檔庫';

$lang->doc->libGlobalList['api'] = '介面文檔庫';

$lang->doc->libIconList['product']   = 'icon-product';
$lang->doc->libIconList['execution'] = 'icon-stack';
$lang->doc->libIconList['custom']    = 'icon-folder-o';

$lang->doc->systemLibs['product']   = $lang->productCommon;
$lang->doc->systemLibs['execution'] = $lang->executionCommon;

$lang->doc->statusList['']       = "";
$lang->doc->statusList['normal'] = "已發佈";
$lang->doc->statusList['draft']  = "草稿";

$lang->doc->aclList['open']    = "公開（所有人都可查看和編輯）";
$lang->doc->aclList['private'] = "私有（僅特定人員可查看和編輯）";

$lang->doc->aclListA['open']    = "公開（所有人均可訪問，有編輯文檔模板權限可訪問並維護）";
$lang->doc->aclListA['private'] = "私有（僅創建者自己可以編輯、使用）";

$lang->doc->selectSpace = '選擇空間';
$lang->doc->space       = '所屬空間';
$lang->doc->spaceList['mine']    = '我的空間';
$lang->doc->spaceList['custom']  = '團隊空間';
$lang->doc->spaceList['product'] = $lang->productCommon . '空間';
$lang->doc->spaceList['project'] = $lang->projectCommon . '空間';
$lang->doc->spaceList['api']     = '介面空間';

$lang->doc->apiType = '介面類型';
$lang->doc->apiTypeList['product'] = $lang->productCommon . '介面';
$lang->doc->apiTypeList['project'] = $lang->projectCommon . '介面';
$lang->doc->apiTypeList['nolink']  = '獨立介面';

$lang->doc->typeList['html']     = '富文本';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url']      = '連結';
$lang->doc->typeList['word']     = 'Word';
$lang->doc->typeList['ppt']      = 'PPT';
$lang->doc->typeList['excel']    = 'Excel';

$lang->doc->createList['template']   = 'Wiki文檔';
$lang->doc->createList['word']       = 'Word';
$lang->doc->createList['ppt']        = 'PPT';
$lang->doc->createList['excel']      = 'Excel';
$lang->doc->createList['attachment'] = $lang->doc->uploadDoc;

$lang->doc->types['doc'] = '文檔';
$lang->doc->types['api'] = '介面文檔';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = '瀏覽方式';
$lang->doc->browseTypeList['list'] = '列表';
$lang->doc->browseTypeList['grid'] = '目錄';

$lang->doc->fastMenuList['byediteddate']  = '最近更新';
//$lang->doc->fastMenuList['visiteddate']   = '最近訪問';
$lang->doc->fastMenuList['openedbyme']    = '我的文檔';
$lang->doc->fastMenuList['collectedbyme'] = '我的收藏';

$lang->doc->fastMenuIconList['byediteddate']  = 'icon-folder-upload';
//$lang->doc->fastMenuIconList['visiteddate']   = 'icon-folder-move';
$lang->doc->fastMenuIconList['openedbyme']    = 'icon-folder-account';
$lang->doc->fastMenuIconList['collectedbyme'] = 'icon-folder-star';

$lang->doc->customObjectLibs['files']       = '顯示附件庫';
$lang->doc->customObjectLibs['customFiles'] = '顯示自定義文檔庫';

$lang->doc->orderLib                       = '文檔庫排序';
$lang->doc->customShowLibs                 = '顯示設置';
$lang->doc->customShowLibsList['zero']     = '顯示空文檔的庫';
$lang->doc->customShowLibsList['children'] = '顯示子分類的文檔';
$lang->doc->customShowLibsList['unclosed'] = '只顯示未關閉的' . $lang->executionCommon;

$lang->doc->mail = new stdclass();
$lang->doc->mail->releasedDoc = new stdclass();
$lang->doc->mail->edit        = new stdclass();
$lang->doc->mail->releasedDoc->title = "%s發佈了文檔 #%s:%s";
$lang->doc->mail->edit->title        = "%s編輯了文檔 #%s:%s";

$lang->doc->confirmDelete               = "您確定刪除該文檔嗎？";
$lang->doc->confirmDeleteWithSub        = "刪除文檔後，將同步刪除文檔下的所有內容，確認要刪除嗎？";
$lang->doc->confirmDeleteLib            = "您確定刪除該文檔庫嗎？";
$lang->doc->confirmDeleteSpace          = "刪除空間後，同步刪除空間下的庫、目錄和文檔，確認要刪除嗎？";
$lang->doc->confirmDeleteBook           = "您確定刪除該手冊嗎？";
$lang->doc->confirmDeleteChapter        = "您確定刪除該章節嗎？";
$lang->doc->confirmDeleteChapterWithSub = "刪除章節後，將同步刪除章節下的子章節和文檔，確認要刪除嗎？";
$lang->doc->confirmDeleteModule         = "您確定刪除該目錄嗎？";
$lang->doc->confirmDeleteModuleWithSub  = "刪除目錄後，同步刪除目錄下的子目錄、章節和文檔，確認要刪除嗎？";
$lang->doc->confirmOtherEditing         = "該文檔正在編輯中，如果繼續編輯將覆蓋他人編輯內容，是否繼續？";
$lang->doc->errorEditSystemDoc          = "系統文檔庫無需修改。";
$lang->doc->errorEmptyProduct           = "沒有{$lang->productCommon}，無法創建文檔";
$lang->doc->errorEmptyProject           = "沒有{$lang->executionCommon}，無法創建文檔";
$lang->doc->errorEmptySpaceLib          = "該空間下沒有文檔庫，無法創建文檔，請先創建文檔庫";
$lang->doc->errorMainSysLib             = "該系統文檔庫不能刪除！";
$lang->doc->accessDenied                = "您沒有權限訪問！";
$lang->doc->versionNotFount             = '該版本文檔不存在';
$lang->doc->noDoc                       = '暫時沒有文檔。';
$lang->doc->noArticle                   = '暫時沒有文章。';
$lang->doc->noLib                       = '暫時沒有庫。';
$lang->doc->noBook                      = 'Wiki庫還未創建手冊，請新建 ：）';
$lang->doc->cannotCreateOffice          = '<p>對不起，企業版才能創建%s文檔。</p><p>試用企業版，請聯繫我們：4006-8899-23 &nbsp; 0532-86893032。</p>';
$lang->doc->notSetOffice                = "創建 %s 文檔，需要配置 <a href='%s'>Collabora Online</a>。";
$lang->doc->requestTypeError            = "當前禪道 requestType 配置不是 PATH_INFO，無法使用 Collabora Online 在綫編輯功能，請聯繫管理員修改 requestType 配置。";
$lang->doc->notSetCollabora             = "沒有設置 Collabora Online，無法創建%s文檔，請配置 <a href='%s'>Collabora Online</a>。";
$lang->doc->noSearchedDoc               = '沒有搜索到任何文檔。';
$lang->doc->noEditedDoc                 = '您還沒有編輯任何文檔。';
$lang->doc->noOpenedDoc                 = '您還沒有創建任何文檔。';
$lang->doc->noCollectedDoc              = '您還沒有收藏任何文檔。';
$lang->doc->errorEmptyLib               = '文檔庫暫無數據。';
$lang->doc->confirmUpdateContent        = '檢查到您有未保存的文檔內容，是否繼續編輯？';
$lang->doc->selectLibType               = '請選擇文檔庫類型';
$lang->doc->selectDoc                   = '請選擇文檔';
$lang->doc->noLibreOffice               = '您還沒有office轉換設置訪問權限!';
$lang->doc->errorParentChapter          = '父章節不能是自身章節及子章節！';
$lang->doc->errorOthersCreated          = '該庫下其他人創建的文檔暫不支持移動，是否確認移動？';
$lang->doc->confirmLeaveOnEdit          = '檢查到您有未保存的文檔內容，是否繼續跳轉？';
$lang->doc->errorOccurred               = '操作失敗，請稍後再試！';
$lang->doc->selectLibFirst              = '請先選擇文檔庫。';
$lang->doc->createLibFirst              = '請先創建文檔庫。';
$lang->doc->nopriv                      = '您暫無 %s 的訪問權限，無法查看該文檔，如需調整權限可聯繫相關人員處理。';
$lang->doc->docConvertComment           = "文檔已經轉換為新編輯器格式，切換版本 %s 來查看轉換前的文檔。";
$lang->doc->previewNotAvailable         = '預覽功能暫不可用，請訪問禪道查看文檔 %s。';
$lang->doc->hocuspocusConnect           = '協作編輯服務已連接。';
$lang->doc->hocuspocusDisconnect        = '協作編輯服務已斷開，編輯內容將在重新連接後同步。';
$lang->doc->docTemplateConvertComment   = "文檔模板已經轉換為新編輯器格式，切換版本 %s 來查看轉換前的文檔模板。";

$lang->doc->noticeAcl['lib']['product']['default']   = "有所選{$lang->productCommon}訪問權限的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['product']['custom']    = "有所選{$lang->productCommon}訪問權限或白名單裡的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['project']['default']   = "有所選{$lang->projectCommon}訪問權限的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['project']['open']      = "有所選{$lang->projectCommon}訪問權限的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['project']['private']   = "有所選{$lang->projectCommon}訪問權限或白名單裡的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['project']['custom']    = "白名單的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['execution']['default'] = "有所選{$lang->execution->common}訪問權限的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['execution']['custom']  = "有所選{$lang->execution->common}訪問權限或白名單裡的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['api']['open']          = '所有人都可以訪問。';
$lang->doc->noticeAcl['lib']['api']['custom']        = '白名單的用戶可以訪問。';
$lang->doc->noticeAcl['lib']['api']['private']       = '只有創建者自己可以訪問。';
$lang->doc->noticeAcl['lib']['custom']['open']       = '所有人都可以訪問。';
$lang->doc->noticeAcl['lib']['custom']['custom']     = '白名單的用戶可以訪問。';
$lang->doc->noticeAcl['lib']['custom']['private']    = '只有創建者自己可以訪問。';

$lang->doc->noticeAcl['doc']['open']    = '有文檔所屬文檔庫訪問權限的，都可以訪問。';
$lang->doc->noticeAcl['doc']['custom']  = '白名單的用戶可以訪問。';
$lang->doc->noticeAcl['doc']['private'] = '只有創建者自己可以訪問。';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url       = '相應的連結地址';
$lang->doc->placeholder->execution = '執行為空時，創建文檔在項目庫下';

$lang->doc->summary = "本頁共 <strong>%s</strong> 個附件，共計 <strong>%s</strong>，其中<strong>%s</strong>。";
$lang->doc->ge      = '個';
$lang->doc->point   = '、';

$lang->doc->libDropdown['editLib']       = '編輯庫';
$lang->doc->libDropdown['deleteLib']     = '刪除庫';
$lang->doc->libDropdown['editSpace']     = '編輯空間';
$lang->doc->libDropdown['deleteSpace']   = '刪除空間';
$lang->doc->libDropdown['addModule']     = '添加目錄';
$lang->doc->libDropdown['addSameModule'] = '添加同級目錄';
$lang->doc->libDropdown['addSubModule']  = '添加子目錄';
$lang->doc->libDropdown['editModule']    = '編輯目錄';
$lang->doc->libDropdown['delModule']     = '刪除目錄';

$lang->doc->featureBar['tableContents']['all']   = '全部';
$lang->doc->featureBar['tableContents']['draft'] = '草稿';

$lang->doc->featureBar['myspace']['all']   = '全部';
$lang->doc->featureBar['myspace']['draft'] = '草稿';

$lang->doc->showDocList[1] = '是';
$lang->doc->showDocList[0] = '否';

$lang->doc->whitelistDeny['product']   = "<i class='icon pr-1 text-important icon-exclamation'></i>用戶<span class='px-1 text-important'>%s</span>暫無產品訪問權限，因此無法訪問文檔。如需訪問，請維護產品訪問控制權限。";
$lang->doc->whitelistDeny['project']   = "<i class='icon pr-1 text-important icon-exclamation'></i>用戶<span class='px-1 text-important'>%s</span>暫無項目訪問權限，因此無法訪問文檔。如需訪問，請維護項目訪問控制權限。";
$lang->doc->whitelistDeny['execution'] = "<i class='icon pr-1 text-important icon-exclamation'></i>用戶<span class='px-1 text-important'>%s</span>暫無執行訪問權限，因此無法訪問文檔。如需訪問，請維護執行訪問控制權限。";
$lang->doc->whitelistDeny['doc']       = "<i class='icon pr-1 text-important icon-exclamation'></i>用戶<span class='px-1 text-important'>%s</span>暫無所在庫訪問權限，因此無法訪問文檔。如需訪問，請維護所在庫的訪問控制權限。";

$lang->doc->filterTypes[] = array('all', '全部');
$lang->doc->filterTypes[] = array('draft', '草稿');
$lang->doc->filterTypes[] = array('collect', '我收藏的');
$lang->doc->filterTypes[] = array('createdByMe', '我創建的');
$lang->doc->filterTypes[] = array('editedByMe', '我編輯的');

$lang->doc->fileFilterTypes[] = array('all', '全部');
$lang->doc->fileFilterTypes[] = array('addedByMe', '我添加');

$lang->doc->productFilterTypes[] = array('all',  '全部');
$lang->doc->productFilterTypes[] = array('mine', '我負責的');

$lang->doc->projectFilterTypes[] = array('all', '全部');
$lang->doc->projectFilterTypes[] = array('mine', '我參與的');

$lang->doc->spaceFilterTypes[] = array('all', '全部');

$lang->doc->manageScope        = '維護範圍';
$lang->doc->browseTemplate     = '模板廣場';
$lang->doc->createTemplate     = '創建文檔模板';
$lang->doc->editTemplate       = '編輯文檔模板';
$lang->doc->moveTemplate       = '移動文檔模板';
$lang->doc->deleteTemplate     = '刪除文檔模板';
$lang->doc->viewTemplate       = '文檔模板詳情';
$lang->doc->addTemplateType    = '添加模板分類';
$lang->doc->editTemplateType   = '編輯模板分類';
$lang->doc->deleteTemplateType = '刪除模板分類';
$lang->doc->sortTemplate       = '排序';

$lang->doc->docLang = new stdClass();
$lang->doc->docLang->cancel                      = $lang->cancel;
$lang->doc->docLang->export                      = $lang->export;
$lang->doc->docLang->exportWord                  = "導出 Word";
$lang->doc->docLang->exportPdf                   = "導出 PDF";
$lang->doc->docLang->exportImage                 = "導出圖片";
$lang->doc->docLang->exportHtml                  = "導出 HTML";
$lang->doc->docLang->exportMarkdown              = "導出 Markdown";
$lang->doc->docLang->exportJSON                  = "導出備份(.json)";
$lang->doc->docLang->importMarkdown              = "導入 Markdown";
$lang->doc->docLang->importConfluence            = "導入 Confluence 存儲格式";
$lang->doc->docLang->importJSON                  = "導入備份(.json)";
$lang->doc->docLang->importConfirm               = "導入將覆蓋當前文檔內容，確定導入嗎？";
$lang->doc->docLang->settings                    = $lang->settings;
$lang->doc->docLang->save                        = $lang->save;
$lang->doc->docLang->createSpace                 = $lang->doc->createSpace;
$lang->doc->docLang->createLib                   = $lang->doc->createLib;
$lang->doc->docLang->actions                     = $lang->doc->libDropdown;
$lang->doc->docLang->moveTo                      = $lang->doc->moveTo;
$lang->doc->docLang->create                      = $lang->doc->createAB;
$lang->doc->docLang->createDoc                   = $lang->doc->create;
$lang->doc->docLang->editDoc                     = $lang->doc->edit;
$lang->doc->docLang->effort                      = $lang->doc->effort;
$lang->doc->docLang->deleteDoc                   = $lang->doc->delete;
$lang->doc->docLang->uploadDoc                   = $lang->doc->uploadDoc;
$lang->doc->docLang->createList                  = $lang->doc->createList;
$lang->doc->docLang->confirmDelete               = $lang->doc->confirmDelete;
$lang->doc->docLang->confirmDeleteWithSub        = $lang->doc->confirmDeleteWithSub;
$lang->doc->docLang->confirmDeleteLib            = $lang->doc->confirmDeleteLib;
$lang->doc->docLang->confirmDeleteSpace          = $lang->doc->confirmDeleteSpace;
$lang->doc->docLang->confirmDeleteModule         = $lang->doc->confirmDeleteModule;
$lang->doc->docLang->confirmDeleteModuleWithSub  = $lang->doc->confirmDeleteModuleWithSub;
$lang->doc->docLang->confirmDeleteChapter        = $lang->doc->confirmDeleteChapter;
$lang->doc->docLang->confirmDeleteChapterWithSub = $lang->doc->confirmDeleteChapterWithSub;
$lang->doc->docLang->collect                     = $lang->doc->collect;
$lang->doc->docLang->edit                        = $lang->doc->edit;
$lang->doc->docLang->delete                      = $lang->doc->delete;
$lang->doc->docLang->cancelCollection            = $lang->doc->cancelCollection;
$lang->doc->docLang->moveDoc                     = $lang->doc->moveDocAction;
$lang->doc->docLang->moveTo                      = $lang->doc->moveTo;
$lang->doc->docLang->moveLib                     = $lang->doc->moveLibAction;
$lang->doc->docLang->moduleName                  = $lang->doc->catalogName;
$lang->doc->docLang->saveDraft                   = $lang->doc->saveDraft;
$lang->doc->docLang->template                    = $lang->doc->template;
$lang->doc->docLang->release                     = $lang->doc->release;
$lang->doc->docLang->batchMove                   = $lang->doc->batchMove;
$lang->doc->docLang->filterTypes                 = $lang->doc->filterTypes;
$lang->doc->docLang->fileFilterTypes             = $lang->doc->fileFilterTypes;
$lang->doc->docLang->productFilterTypes          = $lang->doc->productFilterTypes;
$lang->doc->docLang->projectFilterTypes          = $lang->doc->projectFilterTypes;
$lang->doc->docLang->spaceFilterTypes            = $lang->doc->spaceFilterTypes;
$lang->doc->docLang->sortCatalog                 = $lang->doc->sortCatalog;
$lang->doc->docLang->sortDoclib                  = $lang->doc->sortDoclib;
$lang->doc->docLang->sortDoc                     = $lang->doc->sortDoc;
$lang->doc->docLang->errorOccurred               = $lang->doc->errorOccurred;
$lang->doc->docLang->selectLibFirst              = $lang->doc->selectLibFirst;
$lang->doc->docLang->createLibFirst              = $lang->doc->createLibFirst;
$lang->doc->docLang->space                       = '空間';
$lang->doc->docLang->spaceTypeNames              = array();
$lang->doc->docLang->spaceTypeNames['mine']      = $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['product']   = $lang->productCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['project']   = $lang->projectCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['execution'] = $lang->executionCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['api']       = $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['custom']    = $lang->doc->docLang->space;
$lang->doc->docLang->enterSpace                  = '進入空間';
$lang->doc->docLang->noDocs                      = '沒有文檔';
$lang->doc->docLang->noFiles                     = '沒有檔案';
$lang->doc->docLang->noLibs                      = '沒有文檔庫';
$lang->doc->docLang->noModules                   = '沒有目錄';
$lang->doc->docLang->docsTotalInfo               = '共 {0} 個文檔';
$lang->doc->docLang->createSpace                 = $lang->doc->createSpace;
$lang->doc->docLang->createModule                = $lang->doc->addCatalog;
$lang->doc->docLang->leaveEditingConfirm         = '文檔編輯中，確定要離開嗎？';
$lang->doc->docLang->saveDocFailed               = '文檔保存失敗，請稍後重試';
$lang->doc->docLang->loadingDocsData             = '正在加載文檔數據...';
$lang->doc->docLang->loadDataFailed              = '加載數據失敗';
$lang->doc->docLang->noSpaceTip                  = '這裡什麼也沒有，先創建一個空間再使用吧！';
$lang->doc->docLang->searchModulePlaceholder     = '搜索目錄';
$lang->doc->docLang->searchDocPlaceholder        = '搜索文檔';
$lang->doc->docLang->searchChapterPlaceholder    = '搜索章節';
$lang->doc->docLang->searchSpacePlaceholder      = '搜索空間';
$lang->doc->docLang->searchLibPlaceholder        = '搜索庫';
$lang->doc->docLang->searchPlaceholder           = '搜索';
$lang->doc->docLang->newDocLabel                 = '新文檔';
$lang->doc->docLang->editingDocLabel             = '編輯中';
$lang->doc->docLang->filesLib                    = $lang->doclib->files;
$lang->doc->docLang->currentDocVersionHint       = '當前版本，點擊切換';
$lang->doc->docLang->viewsCount                  = $lang->doc->views;
$lang->doc->docLang->keywords                    = $lang->doc->keywords;
$lang->doc->docLang->keywordsPlaceholder         = $lang->doc->keywordsTips;
$lang->doc->docLang->loadingDocTip               = '正在加載文檔...';
$lang->doc->docLang->loadingEditorTip            = '正在加載編輯器...';
$lang->doc->docLang->pasteImageTip               = $lang->noticePasteImg;
$lang->doc->docLang->downloadFile                = '下載檔案';
$lang->doc->docLang->loadingFilesTip             = '正在加載檔案...';
$lang->doc->docLang->recTotalFormat              = $lang->pager->totalCountAB;
$lang->doc->docLang->recPerPageFormat            = $lang->pager->pageSizeAB;
$lang->doc->docLang->firstPage                   = $lang->pager->firstPage;
$lang->doc->docLang->prevPage                    = $lang->pager->previousPage;
$lang->doc->docLang->nextPage                    = $lang->pager->nextPage;
$lang->doc->docLang->lastPage                    = $lang->pager->lastPage;
$lang->doc->docLang->docOutline                  = '文檔大綱';
$lang->doc->docLang->noOutline                   = '沒有大綱';
$lang->doc->docLang->loading                     = $lang->loading;
$lang->doc->docLang->libNamePrefix               = '庫';
$lang->doc->docLang->colon                       = $lang->colon;
$lang->doc->docLang->createdByUserAt             = '由 {name} 創建於 {time}';
$lang->doc->docLang->editedByUserAt              = '由 {name} 編輯于 {time}';
$lang->doc->docLang->docInfo                     = '文檔信息';
$lang->doc->docLang->docStatus                   = $lang->doc->status;
$lang->doc->docLang->creator                     = $lang->doc->addedByAB;
$lang->doc->docLang->createDate                  = $lang->doc->addedDate;
$lang->doc->docLang->modifier                    = $lang->doc->editedBy;
$lang->doc->docLang->editDate                    = $lang->doc->editedDate;
$lang->doc->docLang->collectCount                = $lang->doc->docCollects;
$lang->doc->docLang->collected                   = '已收藏';
$lang->doc->docLang->history                     = $lang->history;
$lang->doc->docLang->updateHistory               = $lang->doc->updateInfo;
$lang->doc->docLang->updateInfoFormat            = '{name} {time} 更新';
$lang->doc->docLang->noUpdateInfo                = '暫無更新記錄';
$lang->doc->docLang->enterFullscreen             = '進入全屏';
$lang->doc->docLang->exitFullscreen              = '退出全屏';
$lang->doc->docLang->collapse                    = '收起';
$lang->doc->docLang->draft                       = $lang->doc->statusList['draft'];
$lang->doc->docLang->released                    = $lang->doc->statusList['normal'];
$lang->doc->docLang->attachment                  = $lang->doc->files;
$lang->doc->docLang->docTitleRequired            = '文檔標題不能為空。';
$lang->doc->docLang->docTitlePlaceholder         = '請輸入文檔標題';
$lang->doc->docLang->noDataYet                   = '暫無數據';
$lang->doc->docLang->position                    = $lang->doc->position;
$lang->doc->docLang->relateObject                = '關聯對象';
$lang->doc->docLang->showHasDocsOnlyProduct      = '僅顯示有文檔的產品';
$lang->doc->docLang->showHasDocsOnlyProject      = '僅顯示有文檔的項目';
$lang->doc->docLang->showClosedProduct           = '顯示已關閉的產品';
$lang->doc->docLang->showClosedProject           = '顯示已關閉的項目';
$lang->doc->docLang->noProducts                  = '沒有產品';
$lang->doc->docLang->noProjects                  = '沒有項目';
$lang->doc->docLang->productMine                 = '我負責的';
$lang->doc->docLang->projectMine                 = '我參與的';
$lang->doc->docLang->productOther                = '其他';
$lang->doc->docLang->projectOther                = '其他';
$lang->doc->docLang->accessDenied                = $lang->doc->accessDenied;
$lang->doc->docLang->convertToNewDoc             = '轉換文檔';
$lang->doc->docLang->convertToNewDocConfirm      = '全新文檔格式使用現代化塊級編輯器，帶來全新的文檔功能體驗。發佈後，不能在切換回舊編輯器，確定要將此文檔轉換為新文檔格式嗎？';
$lang->doc->docLang->created                     = '創建';
$lang->doc->docLang->edited                      = '修改';
$lang->doc->docLang->notSaved                    = '未保存';
$lang->doc->docLang->oldDocEditingTip            = '此文檔為舊版本編輯器創建，已啟用新版編輯器編輯，保存後將轉換為新版文檔';
$lang->doc->docLang->switchToOldEditor           = '切換回舊編輯器';
$lang->doc->docLang->zentaoList                  = $lang->doc->zentaoList;
$lang->doc->docLang->list                        = $lang->doc->list;
$lang->doc->docLang->loadingFile                 = '正在下載圖片...';
$lang->doc->docLang->needEditable                = $lang->doc->needEditable;
$lang->doc->docLang->addChapter                  = $lang->doc->addChapter;
$lang->doc->docLang->editChapter                 = $lang->doc->editChapter;
$lang->doc->docLang->sortChapter                 = $lang->doc->sortChapter;
$lang->doc->docLang->deleteChapter               = $lang->doc->deleteChapter;
$lang->doc->docLang->addSubChapter               = $lang->doc->addSubChapter;
$lang->doc->docLang->addSameChapter              = $lang->doc->addSameChapter;
$lang->doc->docLang->addSubDoc                   = $lang->doc->addSubDoc;
$lang->doc->docLang->chapterName                 = $lang->doc->chapterName;
$lang->doc->docLang->autoSaveHint                = '已自動保存';
$lang->doc->docLang->editing                     = '正在編輯';
$lang->doc->docLang->restoreVersionHint          = '恢復到版本';
$lang->doc->docLang->restoreVersion              = '恢復';
$lang->doc->docLang->restoreVersionConfirm       = '這將使用文檔版本 {version} 的內容創建一個新的版本，確定要繼續嗎？';

$lang->docTemplate->types = array();
$lang->docTemplate->types['plan']   = '計劃';
$lang->docTemplate->types['story']  = '需求';
$lang->docTemplate->types['design'] = '設計';
$lang->docTemplate->types['dev']    = '開發';
$lang->docTemplate->types['test']   = '測試';
$lang->docTemplate->types['desc']   = '說明';
$lang->docTemplate->types['other']  = '其他';

$lang->docTemplate->builtInScopes = array();
$lang->docTemplate->builtInScopes['rnd']  = array();
$lang->docTemplate->builtInScopes['or']   = array();
$lang->docTemplate->builtInScopes['lite'] = array();
$lang->docTemplate->builtInScopes['rnd']['product']   = '產品';
$lang->docTemplate->builtInScopes['rnd']['project']   = '項目';
$lang->docTemplate->builtInScopes['rnd']['execution'] = '執行';
$lang->docTemplate->builtInScopes['rnd']['personal']  = '個人';
$lang->docTemplate->builtInScopes['or']['market']     = '市場';
$lang->docTemplate->builtInScopes['or']['product']    = '產品';
$lang->docTemplate->builtInScopes['or']['personal']   = '個人';
$lang->docTemplate->builtInScopes['lite']['project']  = '項目';
$lang->docTemplate->builtInScopes['lite']['personal'] = '個人';
