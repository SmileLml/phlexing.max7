<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-tw.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */

include(dirname(__FILE__) . '/common.php');

global $config;

$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = '：';
$lang->hyphen    = '-';
$lang->comma     = '，';
$lang->dot       = '。';
$lang->at        = ' 于 ';
$lang->downArrow = '↓';
$lang->null      = '空';
$lang->ellipsis  = '…';
$lang->percent   = '%';
$lang->dash      = '-';
$lang->slash     = '/';
$lang->and       = '和';
$lang->to        = '至';

$lang->zentaoPMS      = '禪道';
$lang->pmsName        = '開源版';
$lang->proName        = '專業版';
$lang->bizName        = '企業版';
$lang->maxName        = '旗艦版';
$lang->liteName       = '迅捷版';
$lang->devopsPrefix   = '禪道DevOps平台';
$lang->logoImg        = 'zt-logo.png';
$lang->welcome        = "%s項目管理系統";
$lang->logout         = '退出';
$lang->login          = '登錄';
$lang->help           = '幫助';
$lang->aboutZenTao    = '關於禪道';
$lang->ztWebsite      = '禪道系統網址';
$lang->profile        = '個人檔案';
$lang->changePassword = '修改密碼';
$lang->unfoldMenu     = '展開導航';
$lang->collapseMenu   = '收起導航';
$lang->preference     = '個性化設置';
$lang->tutorialAB     = '使用教程';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>時間: %s 毫秒, 內存: %s KB, 查詢: %s.  </div></div>";
$lang->agreement      = "已閲讀並同意。<span class='text-danger'>未經許可，不得去除、隱藏或遮掩禪道軟件的任何標誌及連結。</span>";
$lang->designedByAIUX = "<a href='https://api.zentao.net/goto.php?item=aiux' class='link-aiux listitem item-inner menu-item-inner state' target='_blank'><i class='icon icon-aiux item-icon'></i><div class='item-content text'>艾體驗設計</div></a>";
$lang->bizVersion     = '<a href="https://www.zentao.net/page/enterprise.html" target="_blank">更多精彩，盡在企業版！</a>';
$lang->bizVersionINT  = '<a href="https://www.zentao.pm/page/vs.html" target="_blank">更多精彩，盡在企業版！</a>';

$lang->reset              = '重填';
$lang->cancel             = '取消';
$lang->refresh            = '刷新';
$lang->refreshIcon        = "<i title='$lang->refresh' class='icon icon-refresh'></i>";
$lang->create             = '新建';
$lang->edit               = '編輯';
$lang->delete             = '刪除';
$lang->activate           = '激活';
$lang->close              = '關閉';
$lang->unlink             = '移除';
$lang->import             = '導入';
$lang->export             = '導出';
$lang->setFileName        = '檔案名：';
$lang->submitting         = '稍候...';
$lang->save               = '保存';
$lang->confirm            = '確認';
$lang->preview            = '查看';
$lang->goback             = '返回';
$lang->goPC               = 'PC版';
$lang->more               = '更多';
$lang->moreLink           = 'More';
$lang->day                = '天';
$lang->today              = '今日';
$lang->yesterday          = '昨日';
$lang->number             = '個';
$lang->customConfig       = '自定義';
$lang->public             = '公共';
$lang->trunk              = '主幹';
$lang->sort               = '排序';
$lang->required           = '必填';
$lang->noData             = '暫無';
$lang->noDesc             = '暫無描述';
$lang->fullscreen         = '全屏';
$lang->retrack            = '收起';
$lang->whitelist          = '訪問白名單';
$lang->whitelistNotNeed   = '提示：公開訪問的對象不需設置白名單。';
$lang->globalSetting      = '通用';
$lang->waterfallModel     = '瀑布模型';
$lang->scrumModel         = '敏捷模型';
$lang->agilePlusModel     = '融合敏捷模型';
$lang->waterfallPlusModel = '融合瀑布模型';
$lang->all                = '全部';
$lang->viewDetails        = '查看詳情';
$lang->childrenAB         = '子';
$lang->branchName         = '分支/平台';
$lang->recommend          = '推薦';
$lang->schedule           = '日曆';

$lang->actions         = '操作';
$lang->restore         = '恢復預設';
$lang->confirmDraft    = '有未保存的%name%表單，是否恢復？';
$lang->resume          = '恢復';
$lang->comment         = '備註';
$lang->history         = '歷史記錄';
$lang->attach          = '附件';
$lang->reverse         = '切換順序';
$lang->switchDisplay   = '切換顯示';
$lang->switchTo        = '切換工作界面';
$lang->expand          = '展開全部';
$lang->collapse        = '收起';
$lang->liteMode        = '簡潔版';
$lang->fullMode        = '完整版';
$lang->showMoreInfo    = '顯示更多信息';
$lang->hideMoreInfo    = '隱藏更多信息';
$lang->saveSuccess     = '保存成功';
$lang->importSuccess   = '導入成功';
$lang->fail            = '失敗';
$lang->addFiles        = '上傳了附件 ';
$lang->deleteSuccess   = '刪除成功';
$lang->deleted         = '已刪除';
$lang->files           = '附件 ';
$lang->pasteText       = '多項錄入';
$lang->uploadImages    = '多圖上傳 ';
$lang->uploadImagesTip = '程序會以檔案名為標題，以圖片作為內容。';
$lang->timeout         = '連接超時，請檢查網絡環境，或重試！';
$lang->repairTable     = '資料庫表可能損壞，請用phpmyadmin或myisamchk檢查修復。';
$lang->duplicate       = '已有相同標題的%s';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>抱歉，管理員限制當前IP登錄，請聯繫管理員解除限制。</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = '設為模組首頁';
$lang->noviceTutorial  = '新手教程';
$lang->changeLog       = '修改日誌';
$lang->manual          = '手冊';
$lang->customMenu      = '自定義導航';
$lang->customField     = '自定義表單項';
$lang->lineNumber      = '行號';
$lang->tutorialConfirm = '檢測到你尚未退出新手教程模式，是否現在退出？';
$lang->levelExceeded   = '層級已超過顯示範圍，更多信息請前往網頁端查看或者是通過搜索方式查看。';
$lang->noticeOkFile    = '為了安全起見，系統需要確認您的管理員身份。\n 請登錄禪道所在的伺服器，創建%s檔案。\n 注意：\n 1. 檔案內容為空。\n 2. 如果之前檔案存在，刪除之後重新創建。';
$lang->noticeDrag      = '可點擊添加或拖拽上傳, 不超過 %s';
$lang->allProgress     = '總進度';
$lang->hasReviewed     = '該內容已經評審過，無需重複評審！';

$lang->fieldDisplaySetting = '欄位顯示設置';
$lang->fieldSettingTip     = '以下欄位預設收起，點擊「顯示更多欄位」按鈕展開查看，若您希望某些欄位預設不收起，通過圖釘固定即可。';

$lang->serviceAgreement = "服務協議";
$lang->privacyPolicy    = "隱私政策";
$lang->needAgreePrivacy = "請先閲讀《服務協議》和《隱私政策》";
$lang->iAgreedPrivacy   = "我已閲讀並同意";
$lang->inMaintenance    = "系統正在維護中！";
$lang->maintainReason   = "維護原因：%s。";
$lang->systemMaintainer = "如有疑問請聯繫管理員。";
$lang->unknown          = "未知";

$lang->preShortcutKey    = '[快捷鍵:←]';
$lang->nextShortcutKey   = '[快捷鍵:→]';
$lang->backShortcutKey   = '[快捷鍵:Alt+↑]';
$lang->shortcutOperation = '快捷操作';

$lang->select        = '選擇';
$lang->selectAll     = '全選';
$lang->cancelSelect  = '取消選擇';
$lang->selectReverse = '反選';
$lang->loading       = '稍候...';
$lang->notFound      = '抱歉，您訪問的對象不存在！';
$lang->notPage       = '抱歉，您訪問的功能正在開發中！';
$lang->showAll       = '[[全部顯示]]';
$lang->selectedItems = '已選擇 <strong>{0}</strong> 項';
$lang->noAssigned    = '未指派';

$lang->future      = '未來';
$lang->year        = '年';
$lang->month       = '月';
$lang->hour        = '小時';
$lang->minute      = '分';
$lang->second      = '秒';
$lang->workingHour = '工時';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '狀態';
$lang->openedByAB   = '創建者';
$lang->assignedToAB = '指派';
$lang->typeAB       = '類型';
$lang->nameAB       = '名稱';
$lang->code         = '代號';

$lang->pri     = '優先順序';
$lang->delayed = '已延期';

$lang->contactUs = new stdClass();
$lang->contactUs->common = '您有任何問題都可以聯繫我們。';
$lang->contactUs->phone  = '電話';
$lang->contactUs->email  = '郵箱';
$lang->contactUs->qq     = 'QQ';
$lang->contactUs->wechat = '微信';

$lang->common->common       = '公有模組';
$lang->common->story        = '需求';
$lang->cache->common        = '緩存';
$lang->my->common           = '地盤';
$lang->todo->common         = '待辦';
$lang->block->common        = '區塊';
$lang->program->common      = '項目集';
$lang->product->common      = $lang->productCommon;
$lang->project->common      = $lang->projectCommon;
$lang->execution->common    = '執行';
$lang->kanban->common       = '看板';
$lang->qa->common           = '測試';
$lang->devops->common       = 'DevOps';
$lang->devops->configure    = '配置';
$lang->devops->monitor      = '監控';
$lang->doc->common          = '文檔';
$lang->repo->common         = '代碼';
$lang->repo->commit         = '提交';
$lang->repo->tag            = '標籤';
$lang->repo->branch         = '分支';
$lang->repo->codeRepo       = '代碼庫';
$lang->bi->common           = 'BI';
$lang->screen->common       = '大屏';
$lang->pivot->common        = '透視表';
$lang->chart->common        = '圖表';
$lang->metric->common       = '度量項';
$lang->report->common       = '統計';
$lang->system->common       = '組織';
$lang->admin->common        = '後台';
$lang->epic->common         = $lang->ERCommon;
$lang->story->common        = $lang->SRCommon;
$lang->task->common         = '任務';
$lang->bug->common          = 'Bug';
$lang->testcase->common     = '用例';
$lang->testtask->common     = '測試單';
$lang->score->common        = '我的積分';
$lang->build->common        = '構建';
$lang->testreport->common   = '測試報告';
$lang->automation->common   = '自動化';
$lang->autotest->common     = '自動化';
$lang->team->common         = '團隊';
$lang->user->common         = '用戶';
$lang->custom->common       = '自定義';
$lang->custom->mode         = '模式';
$lang->custom->flow         = '流程設置';
$lang->extension->common    = '插件';
$lang->company->common      = '公司';
$lang->dept->common         = '部門';
$lang->upgrade->common      = '升級';
$lang->editor->common       = '編輯器';
$lang->program->list        = '項目集列表';
$lang->program->kanban      = '項目集看板';
$lang->program->projectView = '項目視角';
$lang->program->productView = '產品視角';
$lang->design->common       = '設計';
$lang->design->HLDS         = '概要設計';
$lang->design->DDS          = '詳細設計';
$lang->design->DBDS         = '資料庫設計';
$lang->design->ADS          = '介面設計';
$lang->stage->common        = '階段';
$lang->stage->type          = '階段類型';
$lang->stage->list          = '階段列表';
$lang->stage->percent       = '工作量占比';
$lang->execution->list      = "{$lang->executionCommon}列表";
$lang->execution->CFD       = "累積流圖";
$lang->kanban->common       = '看板';
$lang->backup->common       = '備份';
$lang->action->trash        = '資源回收筒';
$lang->app->common          = '服務';
$lang->app->store           = '應用市場';
$lang->app->serverLink      = '伺服器連結';
$lang->review->common       = '審批';
$lang->zahost->common       = '宿主機';
$lang->zanode->common       = '執行節點';
$lang->zanode->instruction  = '說明';
$lang->dimension->common    = '維度';
$lang->contact->common      = '聯繫人';
$lang->space->common        = '服務管理';
$lang->store->common        = '應用市場';
$lang->instance->common     = '服務';
$lang->ai->common           = 'AI';
$lang->aiapp->common        = 'AI';
$lang->product->system      = '應用';
$lang->configure->common    = '配置';

$lang->programstakeholder->common   = '干係人';
$lang->featureswitch->common        = '功能開關';
$lang->importdata->common           = '數據導入';
$lang->systemsetting->common        = '系統設置';
$lang->staffmanage->common          = '人員管理';
$lang->modelconfig->common          = '項目模型配置';
$lang->featureconfig->common        = '功能配置';
$lang->doctemplate->common          = '文檔模板';
$lang->notifysetting->common        = '通知設置';
$lang->bidesign->common             = 'BI設計';
$lang->personalsettings->common     = '個人設置';
$lang->projectsettings->common      = '設置';
$lang->dataaccess->common           = '數據權限';
$lang->executiongantt->common       = '甘特圖';
$lang->executionkanban->common      = '看板';
$lang->executionburn->common        = '燃盡圖';
$lang->executioncfd->common         = '累積流圖';
$lang->executionstory->common       = '研發需求';
$lang->executionqa->common          = '測試';
$lang->executionsettings->common    = '設置';
$lang->generalcomment->common       = '備註';
$lang->generalping->common          = '防超時';
$lang->generaltemplate->common      = '模板';
$lang->generaleffort->common        = '通用日誌';
$lang->productsettings->common      = '產品設置';
$lang->projectreview->common        = '評審';
$lang->projecttrack->common         = '矩陣';
$lang->projectqa->common            = '測試';
$lang->holidayseason->common        = '節假日';
$lang->codereview->common           = '問題';
$lang->repocode->common             = '代碼';
$lang->deliverable->common          = '交付物';
$lang->projectDeliverable->common   = '項目交付物';
$lang->executionDeliverable->common = '執行交付物';
$lang->projectTemplate->common      = '項目模板';

$lang->personnel->common     = '人員';
$lang->personnel->invest     = '投入人員';
$lang->personnel->accessible = '可訪問人員';

$lang->stakeholder->common = '干係人';
$lang->release->common     = '發佈';
$lang->message->common     = '系統通知';
$lang->mail->common        = '郵件';

$lang->my->shortCommon          = '地盤';
$lang->testcase->shortCommon    = '用例';
$lang->productplan->shortCommon = '計劃';
$lang->score->shortCommon       = '積分';
$lang->testreport->shortCommon  = '報告';
$lang->qa->shortCommon          = 'QA';
$lang->researchplan->common     = '調研';
$lang->workestimation->common   = '估算';
$lang->gapanalysis->common      = '培訓';
$lang->executionview->common    = '視圖';
$lang->managespace->common      = '空間管理';
$lang->systemteam->common       = '組織團隊';
$lang->systemschedule->common   = '組織日程';
$lang->systemeffort->common     = '組織日誌';
$lang->systemdynamic->common    = '組織動態';
$lang->systemcompany->common    = '組織公司';
$lang->pipeline->common         = '流水綫';
$lang->devopssetting->common    = '設置';
$lang->deployment->common       = '主機';

$lang->dashboard       = '儀表盤';
$lang->contribute      = '貢獻';
$lang->dynamic         = '動態';
$lang->whitelist       = '白名單';
$lang->roadmap         = '路線圖';
$lang->track           = '矩陣';
$lang->settings        = '設置';
$lang->overview        = '概況';
$lang->module          = '模組';
$lang->priv            = '權限';
$lang->other           = '其他';
$lang->estimation      = '估算';
$lang->measure         = '度量';
$lang->treeView        = '樹狀圖';
$lang->groupView       = '分組視圖';
$lang->executionKanban = '看板';
$lang->burn            = '燃盡圖';
$lang->view            = '視圖';
$lang->intro           = '介紹';
$lang->indexPage       = '首頁';
$lang->model           = '模型';
$lang->redev           = '二次開發';
$lang->browser         = '瀏覽器';
$lang->db              = '資料庫';
$lang->langItem        = '語言項';
$lang->api->doc        = '介面文檔';
$lang->database        = '數據字典';
$lang->timezone        = '時區';
$lang->security        = '安全';
$lang->calendar        = '日程';

$lang->my->work = '待處理';

$lang->project->list   = $lang->projectCommon . '列表';
$lang->project->kanban = $lang->projectCommon . '看板';

$lang->execution->executionKanban = "{$lang->execution->common}看板";
$lang->execution->all             = "{$lang->execution->common}列表";

$lang->doc->recent        = '最近文檔';
$lang->doc->my            = '我的文檔';
$lang->doc->favorite      = '我的收藏';
$lang->doc->product       = $lang->productCommon . '庫';
$lang->doc->project       = $lang->projectCommon . '庫';
$lang->doc->api           = '介面庫';
$lang->doc->execution     = "{$lang->execution->common}庫";
$lang->doc->custom        = '自定義庫';
$lang->doc->wiki          = 'Wiki';
$lang->doc->apiDoc        = '文檔';
$lang->doc->apiStruct     = '資料結構';
$lang->doc->quick         = '快捷訪問';
$lang->doc->mySpace       = '我的空間';
$lang->doc->productSpace  = "{$lang->productCommon}空間";
$lang->doc->projectSpace  = "{$lang->projectCommon}空間";
$lang->doc->apiSpace      = '介面空間';
$lang->doc->teamSpace     = '團隊空間';
$lang->doc->template      = '文檔模板';

$lang->product->list   = $lang->productCommon . '列表';
$lang->product->kanban = $lang->productCommon . '看板';

$lang->project->report = '報告';

$lang->report->weekly       = '周報';
$lang->report->notice       = new stdclass();
$lang->report->notice->help = '<i class="icon icon-help text-warning text-xl mr-2"></i>統計報表的數據來源於列表頁面的檢索結果，生成統計報表前請先在列表頁面進行檢索。比如列表頁面我們檢索的是%tab%，那麼報表就是基于之前檢索的%tab%的結果集進行統計。';

$lang->testcase->case      = '用例';
$lang->testcase->testsuite = '套件';
$lang->testcase->caselib   = '用例庫';

$lang->devops->compile      = '流水綫';
$lang->devops->mr           = '合併請求';
$lang->devops->repo         = '代碼庫';
$lang->devops->rules        = '指令';
$lang->devops->settings     = '合併請求設置';
$lang->devops->platform     = '平台';
$lang->devops->set          = '設置';
$lang->devops->environment  = '環境';
$lang->devops->resource     = '資源';
$lang->devops->dblist       = '資料庫';
$lang->devops->domain       = '域名';
$lang->devops->oss          = '對象存儲';
$lang->devops->host         = '主機';
$lang->devops->serverroom   = '機房';
$lang->devops->deploy       = '上線申請';
$lang->devops->provider     = '服務商';
$lang->devops->city         = '城市';
$lang->devops->os           = '系統版本';
$lang->devops->service      = '服務';
$lang->devops->platform     = '平台';
$lang->devops->components   = '組件';

$lang->admin->module      = '功能配置';
$lang->admin->system      = '系統';
$lang->admin->entry       = '接入禪道';
$lang->admin->data        = '數據';
$lang->admin->cron        = '定時';
$lang->admin->buildIndex  = '重建索引';
$lang->admin->tableEngine = '表引擎';
$lang->admin->register    = '禪道社區';

$lang->convert->importJira = '導入Jira數據';

$lang->storyConcept  = '需求概念';
$lang->defaultERName = '業務需求';

$lang->searchTips = '';
$lang->searchAB   = '搜索';

/* 查詢中可以選擇的對象列表。*/
$lang->searchObjects['all']         = '全部';
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = $lang->SRCommon;
if($config->enableER) $lang->searchObjects['epic']        = $lang->ERCommon;
if($config->URAndSR)  $lang->searchObjects['requirement'] = $lang->URCommon;
$lang->searchObjects['task']        = '任務';
$lang->searchObjects['testcase']    = '用例';
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['build']       = '構建';
$lang->searchObjects['release']     = '發佈';
$lang->searchObjects['productplan'] = $lang->productCommon . '計劃';
$lang->searchObjects['testtask']    = '測試單';
$lang->searchObjects['doc']         = '文檔';
$lang->searchObjects['caselib']     = '用例庫';
$lang->searchObjects['testreport']  = '測試報告';
$lang->searchObjects['program']     = '項目集';
$lang->searchObjects['project']     = $lang->projectCommon;
$lang->searchObjects['execution']   = $lang->execution->common;
$lang->searchObjects['user']        = '用戶';
$lang->searchObjects['aiapp']       = 'AI';
$lang->searchTips                   = '編號(ctrl+g)';

/* 導入支持的編碼格式。*/
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* 導出檔案的類型列表。*/
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = '全部記錄';
$lang->exportTypeList['selected'] = '選中記錄';

$lang->visionList = array();
$lang->visionList['rnd']  = '研發綜合界面';
$lang->visionList['lite'] = '運營管理界面';

$lang->createObjects['todo']        = '待辦';
$lang->createObjects['effort']      = '日誌';
$lang->createObjects['bug']         = 'Bug';
$lang->createObjects['story']       = $lang->SRCommon;
$lang->createObjects['task']        = '任務';
$lang->createObjects['testcase']    = '用例';
$lang->createObjects['execution']   = $lang->execution->common;
$lang->createObjects['project']     = $lang->projectCommon;
$lang->createObjects['product']     = $lang->productCommon;
$lang->createObjects['program']     = '項目集';
$lang->createObjects['doc']         = '文檔';
$lang->createObjects['board']       = '白板';
$lang->createObjects['kanbanspace'] = '空間';
$lang->createObjects['kanban']      = '看板';

/* 語言 */
$lang->lang    = 'Language';
$lang->setLang = '語言設置';

/* 風格列表。*/
$lang->theme                = '主題';
$lang->themes['default']    = '禪道藍';
$lang->themes['blue']       = '青春藍';
$lang->themes['green']      = '葉蘭綠';
$lang->themes['red']        = '赤誠紅';
$lang->themes['purple']     = '萱萱紫';

/* 錯誤提示信息。*/
$lang->error                  = new stdclass();
$lang->error->companyNotFound = "您訪問的域名 %s 沒有對應的公司。";
$lang->error->length          = array("『%s』長度錯誤，應當為『%s』", "『%s』長度應當不超過『%s』，且大於『%s』。");
$lang->error->reg             = "『%s』不符合格式，應當為:『%s』。";
$lang->error->unique          = "『%s』已經有『%s』這條記錄了。如果您確定該記錄已刪除，請到後台-系統設置-資源回收筒還原。";
$lang->error->repeat          = "『%s』已經有『%s』這條記錄了。";
$lang->error->gt              = "『%s』應當大於『%s』。";
$lang->error->ge              = "『%s』應當不小於『%s』。";
$lang->error->lt              = "『%s』應當小於『%s』。";
$lang->error->le              = "『%s』應當不大於『%s』。";
$lang->error->in              = "『%s』的值應當是『%s』。";
$lang->error->notempty        = "『%s』不能為空。";
$lang->error->empty           = "『%s』必須為空。";
$lang->error->equal           = "『%s』必須為『%s』。";
$lang->error->int             = array("『%s』應當是數字。", "『%s』應當介於『%s-%s』之間。");
$lang->error->float           = "『%s』應當是數字，可以是小數。";
$lang->error->email           = "『%s』應當為合法的EMAIL。";
$lang->error->phone           = "『%s』應當為合法的電話號碼。";
$lang->error->mobile          = "『%s』應當為合法的手機號碼。";
$lang->error->URL             = "『%s』應當為合法的URL。";
$lang->error->date            = "『%s』應當為合法的日期。";
$lang->error->datetime        = "『%s』應當為合法的日期。";
$lang->error->code            = "『%s』應當為字母或數字的組合。";
$lang->error->account         = "『%s』只能是字母、數字或下劃線的組合三位以上。";
$lang->error->passwordsame    = "兩次密碼應該相同。";
$lang->error->passwordrule    = "密碼應該符合規則，長度至少為六位。";
$lang->error->accessDenied    = '您沒有訪問權限';
$lang->error->unsupportedReq  = '不支持的請求類型';
$lang->error->pasteImg        = '您的瀏覽器不支持粘貼圖片！';
$lang->error->noData          = '暫時沒有數據';
$lang->error->editedByOther   = '該記錄可能已經被改動。請刷新頁面重新編輯！';
$lang->error->tutorialData    = '新手模式下不會插入數據，請退出新手模式操作';
$lang->error->noCurlExt       = '伺服器未安裝Curl模組。';
$lang->error->loginTimeout    = '登錄已超時，請重新登入!';
$lang->error->httpServerError = '伺服器錯誤';

/* 分頁信息。*/
$lang->pager               = new stdclass();
$lang->pager->noRecord     = "暫時沒有記錄";
$lang->pager->digest       = "共 <strong>%s</strong> 條記錄，%s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = "每頁 <strong>%s</strong> 條";
$lang->pager->first        = "<i class='icon-step-backward' title='首頁'></i>";
$lang->pager->pre          = "<i class='icon-play icon-flip-horizontal' title='上一頁'></i>";
$lang->pager->next         = "<i class='icon-play' title='下一頁'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='末頁'></i>";
$lang->pager->locate       = "GO!";
$lang->pager->previousPage = "上一頁";
$lang->pager->nextPage     = "下一頁";
$lang->pager->summery      = "第 <strong>%s-%s</strong> 項，共 <strong>%s</strong> 項";
$lang->pager->pageOfText   = '第 {0} 頁';
$lang->pager->firstPage    = '第一頁';
$lang->pager->lastPage     = '最後一頁';
$lang->pager->goto         = '跳轉';
$lang->pager->pageOf       = '第 <strong>{page}</strong> 頁';
$lang->pager->totalPage    = '共 <strong>{totalPage}</strong> 頁';
$lang->pager->totalCount   = '共 <strong>{recTotal}</strong> 項';
$lang->pager->pageSize     = '每頁 <strong>{recPerPage}</strong> 項';
$lang->pager->itemsRange   = '第 <strong>{start}</strong> ~ <strong>{end}</strong> 項';
$lang->pager->pageOfTotal  = '第 <strong>{page}</strong>/<strong>{totalPage}</strong> 頁';
$lang->pager->totalCountAB = '共 {recTotal} 項';
$lang->pager->pageSizeAB   = '每頁 {recPerPage} 項';

$lang->pager->shortPageSize = '<strong>{recPerPage}</strong> / 頁';

$lang->colorPicker           = new stdclass();
$lang->colorPicker->errorTip = '不是有效的顏色值';

$lang->downNotify     = "下載桌面提醒";
$lang->clientName     = "大桌面";
$lang->downloadClient = "下載大桌面";
$lang->downloadMobile = "下載移動端";
$lang->clientHelp     = "大桌面使用說明";
$lang->clientHelpLink = "https://www.zentao.net/book/zentaopmshelp/302.html?fullScreen=zentao&theme=default#4";
$lang->website        = "https://www.zentao.net";

$lang->suhosinInfo     = "警告：數據太多，請在php.ini中修改<font color=red>sohusin.post.max_vars</font>和<font color=red>sohusin.request.max_vars</font>（大於%s的數）。 保存並重新啟動apache或php-fpm，否則會造成部分數據無法保存。";
$lang->maxVarsInfo     = "警告：數據太多，請在php.ini中修改<font color=red>max_input_vars</font>（大於%s的數）。 保存並重新啟動apache或php-fpm，否則會造成部分數據無法保存。";
$lang->pasteTextInfo   = "粘貼文本到文本域中，每行文字作為一條數據的標題。";
$lang->noticeImport    = "導入數據中，含有已經存在系統的數據，請確認這些數據要覆蓋或者全新插入。";
$lang->importConfirm   = "導入確認";
$lang->importAndCover  = "覆蓋";
$lang->importAndInsert = "全新插入";

$lang->noResultsMatch    = "沒有匹配結果";
$lang->searchMore        = "搜索此關鍵字的更多結果：";
$lang->chooseUsersToMail = "選擇要發信通知的用戶...";
$lang->noticePasteImg    = "可以在編輯器直接貼圖。";
$lang->pasteImgFail      = "貼圖失敗，請稍後重試。";
$lang->pasteImgUploading = "正在上傳圖片，請稍後...";

$lang->visionTips = "您可以在這裡切換界面";
$lang->IKnow      = "我知道了";

/* 時間格式設置。*/
if(!defined('DT_DATETIME1'))  define('DT_DATETIME1',  'Y-m-d H:i:s');
if(!defined('DT_DATETIME2'))  define('DT_DATETIME2',  'y-m-d H:i');
if(!defined('DT_MONTHTIME1')) define('DT_MONTHTIME1', 'n/d H:i');
if(!defined('DT_MONTHTIME2')) define('DT_MONTHTIME2', 'n月d日 H:i');
if(!defined('DT_DATE1'))      define('DT_DATE1',      'Y-m-d');
if(!defined('DT_DATE2'))      define('DT_DATE2',      'Ymd');
if(!defined('DT_DATE3'))      define('DT_DATE3',      'Y年m月d日');
if(!defined('DT_DATE4'))      define('DT_DATE4',      'n月j日');
if(!defined('DT_DATE5'))      define('DT_DATE5',      'j/n');
if(!defined('DT_TIME1'))      define('DT_TIME1',      'H:i:s');
if(!defined('DT_TIME2'))      define('DT_TIME2',      'H:i');

/* datepicker 時間*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText                   = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = '或 ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = '去年';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = '上月';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = '上周';
$lang->datepicker->dpText->TEXT_YESTERDAY   = '昨天';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = '本月';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = '本週';
$lang->datepicker->dpText->TEXT_TODAY       = '今天';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = '明年';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = '下月';
$lang->datepicker->dpText->TEXT_CLOSE       = '關閉';
$lang->datepicker->dpText->TEXT_DATE        = '選擇時間段';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = '選擇日期';

$lang->datepicker->dayNames     = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
$lang->datepicker->abbrDayNames = array('日', '一', '二', '三', '四', '五', '六');
$lang->datepicker->monthNames   = array('一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');

include(dirname(__FILE__) . '/menu.php');
