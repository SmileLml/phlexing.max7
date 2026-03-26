<?php
/**
 * The testcase module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: zh-tw.php 4764 2013-05-05 04:07:04Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->testcase->id               = '用例編號';
$lang->testcase->product          = "所屬{$lang->productCommon}";
$lang->testcase->project          = '所屬' . $lang->projectCommon;
$lang->testcase->execution        = '所屬' . $lang->executionCommon;
$lang->testcase->linkStory        = '關聯需求';
$lang->testcase->module           = '所屬模組';
$lang->testcase->auto             = '自動化測試用例';
$lang->testcase->frame            = '自動化測試框架';
$lang->testcase->howRun           = '測試方式';
$lang->testcase->frequency        = '使用頻率';
$lang->testcase->path             = '路徑';
$lang->testcase->lib              = "所屬庫";
$lang->testcase->branch           = "平台/分支";
$lang->testcase->moduleAB         = '模組';
$lang->testcase->story            = "相關{$lang->SRCommon}";
$lang->testcase->storyVersion     = "{$lang->SRCommon}版本";
$lang->testcase->color            = '標題顏色';
$lang->testcase->order            = '排序';
$lang->testcase->title            = '用例名稱';
$lang->testcase->precondition     = '前置條件';
$lang->testcase->pri              = '優先順序';
$lang->testcase->type             = '用例類型';
$lang->testcase->status           = '用例狀態';
$lang->testcase->statusAB         = '狀態';
$lang->testcase->subStatus        = '子狀態';
$lang->testcase->steps            = '用例步驟';
$lang->testcase->openedBy         = '由誰創建';
$lang->testcase->openedByAB       = '創建者';
$lang->testcase->openedDate       = '創建日期';
$lang->testcase->lastEditedBy     = '最後修改者';
$lang->testcase->result           = '結果';
$lang->testcase->resultAB         = '測試結果';
$lang->testcase->real             = '實際情況';
$lang->testcase->keywords         = '關鍵詞';
$lang->testcase->files            = '附件';
$lang->testcase->linkCase         = '相關用例';
$lang->testcase->linkCases        = '關聯相關用例';
$lang->testcase->unlinkCase       = '移除相關用例';
$lang->testcase->linkBug          = '相關Bug';
$lang->testcase->linkBugs         = '關聯相關Bug';
$lang->testcase->unlinkBug        = '移除相關Bug';
$lang->testcase->stage            = '適用階段';
$lang->testcase->script           = '自動化腳本';
$lang->testcase->scriptedBy       = '腳本由誰創建';
$lang->testcase->scriptedDate     = '腳本創建日期';
$lang->testcase->scriptStatus     = '腳本狀態';
$lang->testcase->scriptLocation   = '腳本地址';
$lang->testcase->reviewedBy       = '由誰評審';
$lang->testcase->reviewedDate     = '評審時間';
$lang->testcase->reviewResult     = '評審結果';
$lang->testcase->reviewedByAB     = '評審人';
$lang->testcase->reviewedDateAB   = '日期';
$lang->testcase->forceReview      = '需要評審';
$lang->testcase->isReviewed       = '是否評審';
$lang->testcase->lastEditedByAB   = '修改者';
$lang->testcase->lastEditedDateAB = '修改日期';
$lang->testcase->lastEditedDate   = '修改日期';
$lang->testcase->version          = '用例版本';
$lang->testcase->lastRunner       = '執行人';
$lang->testcase->lastRunDate      = '執行時間';
$lang->testcase->assignedTo       = '指派給';
$lang->testcase->colorTag         = '顏色標籤';
$lang->testcase->lastRunResult    = '結果';
$lang->testcase->desc             = '步驟';
$lang->testcase->parent           = '上級步驟';
$lang->testcase->xml              = 'XML';
$lang->testcase->expect           = '預期';
$lang->testcase->allProduct       = "所有{$lang->productCommon}";
$lang->testcase->fromBug          = '來源Bug';
$lang->testcase->toBug            = '生成Bug';
$lang->testcase->changed          = '原用例更新';
$lang->testcase->bugs             = '產生Bug數';
$lang->testcase->bugsAB           = 'B';
$lang->testcase->results          = '執行結果數';
$lang->testcase->resultsAB        = 'R';
$lang->testcase->stepNumber       = '用例步驟數';
$lang->testcase->stepNumberAB     = 'S';
$lang->testcase->createBug        = '提Bug';
$lang->testcase->fromModule       = '來源模組';
$lang->testcase->fromCase         = '來源用例';
$lang->testcase->sync             = '同步';
$lang->testcase->ignore           = '忽略';
$lang->testcase->fromTesttask     = '來自測試單用例';
$lang->testcase->fromCaselib      = '來自用例庫用例';
$lang->testcase->fromCaseID       = '用例來源ID';
$lang->testcase->fromCaseVersion  = '用例來源版本';
$lang->testcase->mailto           = '抄送給';
$lang->testcase->deleted          = '已刪除';
$lang->testcase->browseUnits      = '單元測試';
$lang->testcase->suite            = '套件';
$lang->testcase->executionStatus  = '執行狀態';
$lang->testcase->caseType         = '用例類型';
$lang->testcase->allType          = '所有類型';
$lang->testcase->automated        = '自動化';
$lang->testcase->automation       = '自動化設置';

$lang->case = $lang->testcase;  // 用於DAO檢查時使用。因為case是系統關鍵字，所以無法定義該模組為case，只能使用testcase，但表還是使用的case。

$lang->testcase->stepID            = '編號';
$lang->testcase->stepDesc          = '步驟';
$lang->testcase->stepExpect        = '預期';
$lang->testcase->stepVersion       = '版本';
$lang->testcase->stepSameLevel     = '同級';
$lang->testcase->stepSubLevel      = '子級';
$lang->testcase->expectDisabledTip = '分層級後不可填寫預期';
$lang->testcase->deleteStepTip     = '此步驟含有層級，無法刪除';
$lang->testcase->dragNestedTip     = '最多支持三層嵌套，無法拖拽到此處';
$lang->testcase->stepsPlaceholder  = "最多支持3個層級，超出層級不能識別
每個用例步驟在新行用數字 + ‘.’來標記
預期也是用數字 + ‘.’與步驟對應";

$lang->testcase->index                   = "用例管理首頁";
$lang->testcase->create                  = "建用例";
$lang->testcase->batchCreate             = "批量建用例";
$lang->testcase->delete                  = "刪除";
$lang->testcase->deleteAction            = "刪除用例";
$lang->testcase->view                    = "用例詳情";
$lang->testcase->review                  = "評審";
$lang->testcase->reviewAB                = "評審";
$lang->testcase->reviewAction            = "評審用例";
$lang->testcase->batchReview             = "批量評審";
$lang->testcase->edit                    = "編輯用例";
$lang->testcase->batchEdit               = "批量編輯 ";
$lang->testcase->batchChangeModule       = "批量修改模組";
$lang->testcase->confirmLibcaseChange    = "同步用例庫用例修改";
$lang->testcase->ignoreLibcaseChange     = "忽略用例庫用例修改";
$lang->testcase->batchChangeBranch       = "批量修改分支";
$lang->testcase->groupByStories          = "{$lang->common->story}分組";
$lang->testcase->batchDelete             = "批量刪除 ";
$lang->testcase->batchConfirmStoryChange = "批量確認變更";
$lang->testcase->batchChangeType         = "批量修改類型";
$lang->testcase->browse                  = "用例列表";
$lang->testcase->listView                = "列表視圖";
$lang->testcase->groupCase               = "分組瀏覽用例";
$lang->testcase->groupView               = "分組視圖";
$lang->testcase->zeroCase                = "零用例{$lang->common->story}";
$lang->testcase->import                  = "導入";
$lang->testcase->importAction            = "導入用例";
$lang->testcase->importCaseAction        = "導入用例";
$lang->testcase->fileImport              = "導入CSV";
$lang->testcase->importFile              = "導入檔案";
$lang->testcase->importFromLib           = "從用例庫中導入";
$lang->testcase->showImport              = "顯示導入內容";
$lang->testcase->exportTemplate          = "導出模板";
$lang->testcase->export                  = "導出數據";
$lang->testcase->exportAction            = "導出用例";
$lang->testcase->reportChart             = '報表統計';
$lang->testcase->reportAction            = '用例報表統計';
$lang->testcase->confirmChange           = '確認用例變動';
$lang->testcase->confirmStoryChange      = "確認{$lang->SRCommon}變動";
$lang->testcase->copy                    = '複製用例';
$lang->testcase->group                   = '分組';
$lang->testcase->groupName               = '分組名稱';
$lang->testcase->step                    = '步驟';
$lang->testcase->stepChild               = '子步驟';
$lang->testcase->viewAll                 = '查看所有';
$lang->testcase->importToLib             = '導入用例庫';
$lang->testcase->showScript              = '查看自動化腳本';
$lang->testcase->autoScript              = '自動化腳本';
$lang->testcase->autoCase                = '自動化';

$lang->testcase->new = '新增';

$lang->testcase->num      = '用例條數';
$lang->testcase->encoding = '編碼';

$lang->testcase->deleteStep   = '刪除';
$lang->testcase->insertBefore = '之前添加';
$lang->testcase->insertAfter  = '之後添加';

$lang->testcase->assignToMe   = '指派給我的用例';
$lang->testcase->openedByMe   = '我建的用例';
$lang->testcase->allCases     = '全部';
$lang->testcase->allTestcases = '所有用例';
$lang->testcase->needConfirm  = "{$lang->common->story}變動";
$lang->testcase->bySearch     = '搜索';
$lang->testcase->unexecuted   = '未執行';

$lang->testcase->lblStory       = "相關{$lang->SRCommon}";
$lang->testcase->lblLastEdited  = '最後編輯';
$lang->testcase->lblTypeValue   = '類型可選值列表';
$lang->testcase->lblStageValue  = '階段可選值列表';
$lang->testcase->lblStatusValue = '狀態可選值列表';

$lang->testcase->legendBasicInfo   = '基本信息';
$lang->testcase->legendAttach      = '附件';
$lang->testcase->legendLinkBugs    = '相關Bug';
$lang->testcase->legendOpenAndEdit = '創建編輯';
$lang->testcase->legendComment     = '備註';
$lang->testcase->legendOther       = '其他相關';

$lang->testcase->confirmDelete         = '您確認要刪除該測試用例嗎？';
$lang->testcase->confirmBatchDelete    = '您確認要批量刪除這些測試用例嗎？';
$lang->testcase->ditto                 = '同上';
$lang->testcase->dittoNotice           = "該用例與上一用例不屬於同一{$lang->productCommon}！";
$lang->testcase->confirmUnlinkTesttask = '用例[%s]已關聯在之前所屬平台/分支的測試單中，調整平台/分支後，將從之前所屬平台/分支的測試單中移除，請確認是否繼續修改。';

$lang->testcase->autoList['']     = '';
$lang->testcase->autoList['auto'] = '是';
$lang->testcase->autoList['no']   = '否';

$lang->testcase->reviewList[0] = '否';
$lang->testcase->reviewList[1] = '是';

$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['unit']        = '單元測試';
$lang->testcase->typeList['interface']   = '介面測試';
$lang->testcase->typeList['feature']     = '功能測試';
$lang->testcase->typeList['install']     = '安裝部署';
$lang->testcase->typeList['config']      = '配置相關';
$lang->testcase->typeList['performance'] = '性能測試';
$lang->testcase->typeList['security']    = '安全相關';
$lang->testcase->typeList['other']       = '其他';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = '單元測試階段';
$lang->testcase->stageList['feature']    = '功能測試階段';
$lang->testcase->stageList['intergrate'] = '整合測試階段';
$lang->testcase->stageList['system']     = '系統測試階段';
$lang->testcase->stageList['smoke']      = '冒煙測試階段';
$lang->testcase->stageList['bvt']        = '版本驗證階段';

$lang->testcase->reviewResultList['']        = '';
$lang->testcase->reviewResultList['pass']    = '確認通過';
$lang->testcase->reviewResultList['clarify'] = '繼續完善';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['wait']        = '待評審';
$lang->testcase->statusList['normal']      = '正常';
$lang->testcase->statusList['blocked']     = '被阻塞';
$lang->testcase->statusList['investigate'] = '研究中';

$lang->testcase->resultList['n/a']     = '忽略';
$lang->testcase->resultList['pass']    = '通過';
$lang->testcase->resultList['fail']    = '失敗';
$lang->testcase->resultList['blocked'] = '阻塞';

$lang->testcase->buttonToList = '返回';

$lang->testcase->whichLine        = '第%s行';
$lang->testcase->stepsEmpty       = '步驟%s不能為空';
$lang->testcase->errorEncode      = '無數據，請選擇正確的編碼重新上傳！';
$lang->testcase->noFunction       = '不存在iconv和mb_convert_encoding轉碼方法，不能將數據轉成想要的編碼！';
$lang->testcase->noRequire        = "%s行的“%s”是必填欄位，不能為空";
$lang->testcase->noRequireTip     = "“%s”是必填欄位，不能為空";
$lang->testcase->noLibrary        = "現在還沒有用例庫，請先創建！";
$lang->testcase->mustChooseResult = '必須選擇評審結果';
$lang->testcase->noModule         = '<div>您現在還沒有模組信息</div><div>請維護測試模組</div>';
$lang->testcase->noCase           = '暫時沒有用例。';
$lang->testcase->importedCases    = 'ID為 %s 的用例在相同模組已經導入，已忽略。';
$lang->testcase->importedFromLib  = '導入成功%s項：%s。';
$lang->testcase->noStep           = '暫時沒有步驟。';

$lang->testcase->searchStories = "鍵入來搜索{$lang->SRCommon}";
$lang->testcase->selectLib     = '請選擇庫';
$lang->testcase->selectLibAB   = '選擇用例庫';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib               = array('main' => '$date, 由 <strong>$actor</strong> 從用例庫 <strong>$extra</strong>導入。');
$lang->testcase->action->reviewed              = array('main' => '$date, 由 <strong>$actor</strong> 記錄評審結果，結果為 <strong>$extra</strong>。', 'extra' => 'reviewResultList');
$lang->testcase->action->linked2project        = array('main' => '$date, 由 <strong>$actor</strong> 關聯到' . $lang->projectCommon . ' <strong>$extra</strong>。');
$lang->testcase->action->unlinkedfromproject   = array('main' => '$date, 由 <strong>$actor</strong> 從' . $lang->projectCommon . ' <strong>$extra</strong> 移除。');
$lang->testcase->action->linked2execution      = array('main' => '$date, 由 <strong>$actor</strong> 關聯到' . $lang->executionCommon . ' <strong>$extra</strong>。');
$lang->testcase->action->unlinkedfromexecution = array('main' => '$date, 由 <strong>$actor</strong> 從' . $lang->executionCommon . ' <strong>$extra</strong> 移除。');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = '待評審';
$lang->testcase->featureBar['browse'][]              = '-';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;

$lang->testcase->importXmind     = "導入Xmind";
$lang->testcase->exportXmind     = "導出Xmind";
$lang->testcase->exportFreeMind  = "導出FreeMind";
$lang->testcase->getXmindImport  = "獲取導圖";
$lang->testcase->showXMindImport = "顯示導圖";
$lang->testcase->saveXmindImport = "保存導圖";

$lang->testcase->xmindImport           = "導入Xmind";
$lang->testcase->xmindExport           = "導出Xmind";
$lang->testcase->xmindImportEdit       = "Xmind 編輯";
$lang->testcase->errorFileNotEmpty     = '上傳檔案不能為空';
$lang->testcase->errorXmindUpload      = '上傳失敗';
$lang->testcase->errorFileFormat       = '檔案格式錯誤';
$lang->testcase->moduleSelector        = '模組選擇';
$lang->testcase->errorImportBadProduct = '產品不存在，導入錯誤';
$lang->testcase->errorSceneNotExist    = '場景[%d]不存在';
$lang->testcase->errorMindConfig       = "%s特徵字元串只能是1-10個字母";

$lang->testcase->save  = '保存';
$lang->testcase->close = '關閉';

$lang->testcase->xmindImportSetting = '導入特徵字元設置';
$lang->testcase->xmindExportSetting = '導出特徵字元設置';
$lang->testcase->xmindSettingTip    = '特徵字元設置後，Xmind主題可與禪道測試用例結構相對應。';

$lang->testcase->settingModule = '模組';
$lang->testcase->settingScene  = '場景';
$lang->testcase->settingCase   = '測試用例';
$lang->testcase->settingPre    = '前置條件';
$lang->testcase->settingPri    = '優先順序';
$lang->testcase->settingGroup  = '步驟分組';

$lang->testcase->jsLng = new stdclass();
$lang->testcase->jsLng->caseNotExist =  '未識別導入數據中的用例，導入失敗';
$lang->testcase->jsLng->saveFail     =  '保存失敗';
$lang->testcase->jsLng->set2Scene    =  '設為場景';
$lang->testcase->jsLng->set2Testcase =  '設為測試用例';
$lang->testcase->jsLng->clearSetting =  '清除設置';
$lang->testcase->jsLng->setModule    =  '設置場景模組';
$lang->testcase->jsLng->pickModule   =  '請選擇模組';
$lang->testcase->jsLng->clearBefore  =  '清除前面場景';
$lang->testcase->jsLng->clearAfter   =  '清除後面場景';
$lang->testcase->jsLng->clearCurrent =  '清除當前場景';
$lang->testcase->jsLng->removeGroup  =  '移除分組';
$lang->testcase->jsLng->set2Group    =  '設為分組';

$lang->testcase->exportTemplet = '導出模板';

$lang->testcase->createScene      = "建場景";
$lang->testcase->changeScene      = "拖動改變所屬場景";
$lang->testcase->batchChangeScene = "批量改變所屬場景";
$lang->testcase->updateOrder      = "拖動排序";
$lang->testcase->differentProduct = "所屬產品不同";

$lang->testcase->newScene           = "建場景";
$lang->testcase->sceneTitle         = '場景名稱';
$lang->testcase->parentScene        = "父場景";
$lang->testcase->scene              = "所屬場景";
$lang->testcase->summary            = '本頁共 %d 個頂級場景，%d 個獨立用例。';
$lang->testcase->summaryScene       = '本頁共 %d 個頂級場景。';
$lang->testcase->failSummary        = '本頁共 %d 個用例，未通過 %d。';
$lang->testcase->checkedSummary     = '已選中 {checked} 個用例，已執行 {run} 個。';
$lang->testcase->failCheckedSummary = '已選中 %total% 個用例，未通過 %fail%。';
$lang->testcase->deleteScene        = '刪除場景';
$lang->testcase->editScene          = '編輯場景';
$lang->testcase->hasChildren        = '該場景有子場景或測試用例存在，要全部刪除嗎？';
$lang->testcase->confirmDeleteScene = '您確定要刪除場景：“%s”嗎？';
$lang->testcase->sceneb             = "場景";
$lang->testcase->onlyAutomated      = '僅自動化';
$lang->testcase->onlyScene          = '僅場景';
$lang->testcase->iScene             = '所屬場景';
$lang->testcase->generalTitle       = '標題';
$lang->testcase->noScene            = '暫時沒有場景。';
$lang->testcase->rowIndex           = '行索引';
$lang->testcase->nestTotal          = '嵌套總數';
$lang->testcase->normal             = '正常';

/* Translation for drag modal message box. */
$lang->testcase->dragModalTitle       = '拖拽操作選擇';
$lang->testcase->dragModalDesc        = '當前操作有兩種可能的情況：';
$lang->testcase->dragModalOrder       = '1) 調整排序';
$lang->testcase->dragModalScene       = '2) 更改所屬場景，所屬模組同時變更為目標場景的模組';
$lang->testcase->dragModalAction      = '請選擇您要執行的操作';
$lang->testcase->dragModalChangeScene = '更改所屬場景';
$lang->testcase->dragModalChangeOrder = '調整排序';

$lang->testcase->confirmBatchDeleteSceneCase = '您確認要批量刪除這些場景或測試用例嗎？';

$lang->scene = new stdclass();
$lang->scene->product = '所屬產品';
$lang->scene->branch  = '所屬分支';
$lang->scene->module  = '所屬模組';
$lang->scene->parent  = '父場景';
$lang->scene->title   = '場景名稱';
$lang->scene->noCase  = '暫無用例';
