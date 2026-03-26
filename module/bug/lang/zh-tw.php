<?php
/**
 * The bug module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: zh-tw.php 4536 2013-03-02 13:39:37Z wwccss $
 * @link        https://www.zentao.net
 */
/* 欄位列表。*/
$lang->bug->common           = 'Bug';
$lang->bug->id               = 'Bug編號';
$lang->bug->product          = '所屬' . $lang->productCommon;
$lang->bug->branch           = '平台/分支';
$lang->bug->module           = '所屬模組';
$lang->bug->project          = '所屬' . $lang->projectCommon;
$lang->bug->execution        = '所屬' . $lang->execution->common;
$lang->bug->kanban           = '所屬看板';
$lang->bug->storyVersion     = "{$lang->SRCommon}版本";
$lang->bug->color            = '標題顏色';
$lang->bug->title            = 'Bug標題';
$lang->bug->severity         = '嚴重程度';
$lang->bug->pri              = '優先順序';
$lang->bug->type             = 'Bug類型';
$lang->bug->os               = '操作系統';
$lang->bug->browser          = '瀏覽器';
$lang->bug->hardware         = '硬件';
$lang->bug->result           = '結果';
$lang->bug->repo             = '所屬版本庫';
$lang->bug->mr               = '合併請求';
$lang->bug->entry            = '代碼路徑';
$lang->bug->lines            = '代碼行';
$lang->bug->v1               = '版本1';
$lang->bug->v2               = '版本2';
$lang->bug->issueKey         = 'Sonarqube問題鍵值';
$lang->bug->repoType         = '版本庫類型';
$lang->bug->steps            = '重現步驟';
$lang->bug->status           = 'Bug狀態';
$lang->bug->subStatus        = '子狀態';
$lang->bug->activatedCount   = '激活次數';
$lang->bug->activatedDate    = '激活時間';
$lang->bug->confirmed        = '是否確認';
$lang->bug->toTask           = '轉任務';
$lang->bug->toStory          = "轉{$lang->SRCommon}";
$lang->bug->feedbackBy       = '反饋者';
$lang->bug->notifyEmail      = '通知郵箱';
$lang->bug->mailto           = '抄送給';
$lang->bug->openedBy         = '由誰創建';
$lang->bug->openedDate       = '創建日期';
$lang->bug->openedBuild      = '影響版本';
$lang->bug->assignedTo       = '指派給';
$lang->bug->assignedToMe     = '指派給我';
$lang->bug->assignedDate     = '指派日期';
$lang->bug->resolvedBy       = '解決者';
$lang->bug->resolution       = '解決方案';
$lang->bug->resolvedBuild    = '解決版本';
$lang->bug->resolvedDate     = '解決日期';
$lang->bug->deadline         = '截止日期';
$lang->bug->plan             = '所屬計劃';
$lang->bug->closedBy         = '由誰關閉';
$lang->bug->closedDate       = '關閉日期';
$lang->bug->duplicateBug     = '重複Bug';
$lang->bug->lastEditedBy     = '最後修改者';
$lang->bug->caseVersion      = '用例版本';
$lang->bug->testtask         = '測試單';
$lang->bug->files            = '附件';
$lang->bug->keywords         = '關鍵詞';
$lang->bug->lastEditedDate   = '修改日期';
$lang->bug->fromCase         = '來源用例';
$lang->bug->toCase           = '生成用例';
$lang->bug->colorTag         = '顏色標籤';
$lang->bug->fixedRate        = '修復率';
$lang->bug->noticefeedbackBy = '通知反饋者';
$lang->bug->selectProjects   = '選擇' . $lang->projectCommon;
$lang->bug->nextStep         = '下一步';
$lang->bug->noProject        = "還沒有選擇{$lang->projectCommon}！";
$lang->bug->noExecution      = "還沒有選擇{$lang->execution->common}！";
$lang->bug->story            = "相關需求";
$lang->bug->task             = '相關任務';
$lang->bug->relatedBug       = '相關Bug';
$lang->bug->case             = '相關用例';
$lang->bug->linkMR           = '相關合併請求';
$lang->bug->linkPR           = '相關推送請求';
$lang->bug->linkCommit       = '相關代碼版本';
$lang->bug->productplan      = $lang->bug->plan;
$lang->bug->codeBranch       = '代碼分支';
$lang->bug->unlinkBranch     = '解除關聯代碼分支';
$lang->bug->branchName       = '分支名稱';
$lang->bug->branchFrom       = '創建自';
$lang->bug->codeRepo         = '倉庫名稱';

$lang->bug->abbr = new stdclass();
$lang->bug->abbr->module         = '模組';
$lang->bug->abbr->severity       = '級別';
$lang->bug->abbr->status         = '狀態';
$lang->bug->abbr->activatedCount = '激活次數';
$lang->bug->abbr->confirmed      = '確認';
$lang->bug->abbr->openedBy       = '創建者';
$lang->bug->abbr->openedDate     = '創建日期';
$lang->bug->abbr->assignedTo     = '指派給';
$lang->bug->abbr->resolvedBy     = '解決';
$lang->bug->abbr->resolution     = '方案';
$lang->bug->abbr->resolvedDate   = '解決日期';
$lang->bug->abbr->deadline       = '截止';
$lang->bug->abbr->lastEditedBy   = '修改者';
$lang->bug->abbr->lastEditedDate = '修改日期';
$lang->bug->abbr->assignToMe     = '指派給我';
$lang->bug->abbr->openedByMe     = '由我創建';
$lang->bug->abbr->resolvedByMe   = '由我解決';

/* 方法列表。*/
$lang->bug->index              = '首頁';
$lang->bug->browse             = 'Bug列表';
$lang->bug->create             = '提Bug';
$lang->bug->batchCreate        = '批量提Bug';
$lang->bug->createCase         = '創建用例';
$lang->bug->copy               = '複製Bug';
$lang->bug->edit               = '編輯Bug';
$lang->bug->batchEdit          = '批量編輯';
$lang->bug->view               = 'Bug詳情';
$lang->bug->delete             = '刪除';
$lang->bug->deleteAction       = '刪除Bug';
$lang->bug->confirm            = '確認';
$lang->bug->confirmAction      = '確認Bug';
$lang->bug->batchConfirm       = '批量確認';
$lang->bug->assignTo           = '指派';
$lang->bug->assignAction       = '指派Bug';
$lang->bug->batchAssignTo      = '批量指派';
$lang->bug->resolve            = '解決';
$lang->bug->resolveAction      = '解決Bug';
$lang->bug->batchResolve       = '批量解決';
$lang->bug->createAB           = '新增';
$lang->bug->close              = '關閉';
$lang->bug->closeAction        = '關閉Bug';
$lang->bug->batchClose         = '批量關閉';
$lang->bug->activate           = '激活';
$lang->bug->activateAction     = '激活Bug';
$lang->bug->batchActivate      = '批量激活';
$lang->bug->reportChart        = '報表統計';
$lang->bug->reportAction       = 'Bug報表統計';
$lang->bug->export             = '導出數據';
$lang->bug->exportAction       = '導出Bug';
$lang->bug->confirmStoryChange = "確認{$lang->SRCommon}變動";
$lang->bug->search             = '搜索';
$lang->bug->batchChangeModule  = '批量修改模組';
$lang->bug->batchChangeBranch  = '批量修改分支';
$lang->bug->batchChangePlan    = '批量修改計劃';
$lang->bug->linkBugs           = '關聯相關Bug';
$lang->bug->unlinkBug          = '移除相關Bug';

/* 查詢條件列表。*/
$lang->bug->assignToMe         = '指派給我';
$lang->bug->openedByMe         = '由我創建';
$lang->bug->resolvedByMe       = '由我解決';
$lang->bug->closedByMe         = '由我關閉';
$lang->bug->assignedByMe       = '由我指派';
$lang->bug->assignToNull       = '未指派';
$lang->bug->unResolved         = '未解決';
$lang->bug->toClosed           = '待關閉';
$lang->bug->unclosed           = '未關閉';
$lang->bug->unconfirmed        = '未確認';
$lang->bug->longLifeBugs       = '久未處理';
$lang->bug->postponedBugs      = '被延期';
$lang->bug->overdueBugs        = '過期Bug';
$lang->bug->allBugs            = '所有';
$lang->bug->byQuery            = '搜索';
$lang->bug->needConfirm        = "{$lang->SRCommon}變動";
$lang->bug->allProject         = '所有' . $lang->projectCommon;
$lang->bug->allProduct         = '所有' . $lang->productCommon;
$lang->bug->my                 = '我的';
$lang->bug->yesterdayResolved  = '昨天解決Bug數';
$lang->bug->yesterdayConfirmed = '昨天確認';
$lang->bug->yesterdayClosed    = '昨天關閉';

$lang->bug->deleted        = '已刪除';
$lang->bug->labelConfirmed = '已確認';
$lang->bug->labelPostponed = '被延期';
$lang->bug->changed        = '已變動';
$lang->bug->storyChanged   = '需求變動';
$lang->bug->ditto          = '同上';

/* 頁面標籤。*/
$lang->bug->lblAssignedTo = '當前指派';
$lang->bug->lblMailto     = '抄送給';
$lang->bug->lblLastEdited = '最後修改';
$lang->bug->lblResolved   = '由誰解決';
$lang->bug->loadAll       = '加載所有';
$lang->bug->createBuild   = '創建';

global $config;
/* legend列表。*/
$lang->bug->legendBasicInfo             = '基本信息';
$lang->bug->legendAttach                = '附件';
$lang->bug->legendPRJExecStoryTask      = "{$lang->projectCommon}/{$lang->executionCommon}/{$lang->SRCommon}/任務";
$lang->bug->legendExecStoryTask         = "{$lang->projectCommon}/{$lang->SRCommon}/任務";
$lang->bug->lblTypeAndSeverity          = '類型/嚴重程度';
$lang->bug->lblSystemBrowserAndHardware = '系統/瀏覽器';
$lang->bug->legendSteps                 = '重現步驟';
$lang->bug->legendComment               = '備註';
$lang->bug->legendLife                  = 'Bug的一生';
$lang->bug->legendMisc                  = '其他相關';
$lang->bug->legendRelated               = '其他信息';
$lang->bug->legendThisWeekCreated       = '本週新增';

/* 模板。*/
$lang->bug->tplStep   = "<p>[步驟]</p><p></p>";
$lang->bug->tplResult = "<p>[結果]</p><p></p>";
$lang->bug->tplExpect = "<p>[期望]</p><p></p>";

/* 各個欄位取值列表。*/
$lang->bug->severityList[0] = '';
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[3] = '3';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']         = '';
$lang->bug->osList['all']      = '全部';
$lang->bug->osList['windows']  = 'Windows';
$lang->bug->osList['win11']    = 'Windows 11';
$lang->bug->osList['win10']    = 'Windows 10';
$lang->bug->osList['win8']     = 'Windows 8';
$lang->bug->osList['win7']     = 'Windows 7';
$lang->bug->osList['winxp']    = 'Windows XP';
$lang->bug->osList['osx']      = 'Mac OS';
$lang->bug->osList['android']  = 'Android';
$lang->bug->osList['ios']      = 'IOS';
$lang->bug->osList['linux']    = 'Linux';
$lang->bug->osList['ubuntu']   = 'Ubuntu';
$lang->bug->osList['chromeos'] = 'Chrome OS';
$lang->bug->osList['fedora']   = 'Fedora';
$lang->bug->osList['unix']     = 'Unix';
$lang->bug->osList['others']   = '其他';

$lang->bug->browserList['']        = '';
$lang->bug->browserList['all']     = '全部';
$lang->bug->browserList['chrome']  = 'Chrome';
$lang->bug->browserList['edge']    = 'Edge';
$lang->bug->browserList['ie']      = 'IE系列';
$lang->bug->browserList['ie11']    = 'IE11';
$lang->bug->browserList['ie10']    = 'IE10';
$lang->bug->browserList['ie9']     = 'IE9';
$lang->bug->browserList['ie8']     = 'IE8';
$lang->bug->browserList['firefox'] = 'firefox系列';
$lang->bug->browserList['opera']   = 'Opera系列';
$lang->bug->browserList['safari']  = 'safari';
$lang->bug->browserList['360']     = '360瀏覽器';
$lang->bug->browserList['qq']      = 'QQ瀏覽器';
$lang->bug->browserList['other']   = '其他';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = '代碼錯誤';
$lang->bug->typeList['config']       = '配置相關';
$lang->bug->typeList['install']      = '安裝部署';
$lang->bug->typeList['security']     = '安全相關';
$lang->bug->typeList['performance']  = '性能問題';
$lang->bug->typeList['standard']     = '標準規範';
$lang->bug->typeList['automation']   = '測試腳本';
$lang->bug->typeList['designdefect'] = '設計缺陷';
$lang->bug->typeList['others']       = '其他';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = '激活';
$lang->bug->statusList['resolved'] = '已解決';
$lang->bug->statusList['closed']   = '已關閉';

$lang->bug->confirmedList[''] = '';
$lang->bug->confirmedList[1]  = '已確認';
$lang->bug->confirmedList[0]  = '未確認';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = '設計如此';
$lang->bug->resolutionList['duplicate']  = '重複Bug';
$lang->bug->resolutionList['external']   = '外部原因';
$lang->bug->resolutionList['fixed']      = '已解決';
$lang->bug->resolutionList['notrepro']   = '無法重現';
$lang->bug->resolutionList['postponed']  = '延期處理';
$lang->bug->resolutionList['willnotfix'] = "不予解決";
$lang->bug->resolutionList['tostory']    = "轉為{$lang->SRCommon}";

/* 統計報表。*/
$lang->bug->report = new stdclass();
$lang->bug->report->common = '報表';
$lang->bug->report->select = '請選擇報表類型';
$lang->bug->report->create = '生成報表';

$lang->bug->report->charts['bugsPerExecution']      = $lang->executionCommon . 'Bug數量';
$lang->bug->report->charts['bugsPerBuild']          = '版本Bug數量';
$lang->bug->report->charts['bugsPerModule']         = '模組Bug數量';
$lang->bug->report->charts['openedBugsPerDay']      = '每天新增Bug數';
$lang->bug->report->charts['resolvedBugsPerDay']    = '每天解決Bug數';
$lang->bug->report->charts['closedBugsPerDay']      = '每天關閉的Bug數';
$lang->bug->report->charts['openedBugsPerUser']     = '每人提交的Bug數';
$lang->bug->report->charts['resolvedBugsPerUser']   = '每人解決的Bug數';
$lang->bug->report->charts['closedBugsPerUser']     = '每人關閉的Bug數';
$lang->bug->report->charts['bugsPerSeverity']       = '按Bug嚴重程度統計';
$lang->bug->report->charts['bugsPerResolution']     = '按Bug解決方案統計';
$lang->bug->report->charts['bugsPerStatus']         = '按Bug狀態統計';
$lang->bug->report->charts['bugsPerActivatedCount'] = '按Bug激活次數統計';
$lang->bug->report->charts['bugsPerPri']            = '按Bug優先順序統計';
$lang->bug->report->charts['bugsPerType']           = '按Bug類型統計';
$lang->bug->report->charts['bugsPerAssignedTo']     = '按指派給統計';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug處理時間統計';
//$lang->bug->report->charts['bugHistories']       = 'Bug處理步驟統計';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph  = new stdclass();
$lang->bug->report->options->type   = 'pie';
$lang->bug->report->options->width  = 500;
$lang->bug->report->options->height = 140;

$lang->bug->report->bugsPerExecution      = new stdclass();
$lang->bug->report->bugsPerBuild          = new stdclass();
$lang->bug->report->bugsPerModule         = new stdclass();
$lang->bug->report->openedBugsPerDay      = new stdclass();
$lang->bug->report->resolvedBugsPerDay    = new stdclass();
$lang->bug->report->closedBugsPerDay      = new stdclass();
$lang->bug->report->openedBugsPerUser     = new stdclass();
$lang->bug->report->resolvedBugsPerUser   = new stdclass();
$lang->bug->report->closedBugsPerUser     = new stdclass();
$lang->bug->report->bugsPerSeverity       = new stdclass();
$lang->bug->report->bugsPerResolution     = new stdclass();
$lang->bug->report->bugsPerStatus         = new stdclass();
$lang->bug->report->bugsPerActivatedCount = new stdclass();
$lang->bug->report->bugsPerType           = new stdclass();
$lang->bug->report->bugsPerPri            = new stdclass();
$lang->bug->report->bugsPerAssignedTo     = new stdclass();
$lang->bug->report->bugLiveDays           = new stdclass();
$lang->bug->report->bugHistories          = new stdclass();

$lang->bug->report->bugsPerExecution->graph      = new stdclass();
$lang->bug->report->bugsPerBuild->graph          = new stdclass();
$lang->bug->report->bugsPerModule->graph         = new stdclass();
$lang->bug->report->openedBugsPerDay->graph      = new stdclass();
$lang->bug->report->resolvedBugsPerDay->graph    = new stdclass();
$lang->bug->report->closedBugsPerDay->graph      = new stdclass();
$lang->bug->report->openedBugsPerUser->graph     = new stdclass();
$lang->bug->report->resolvedBugsPerUser->graph   = new stdclass();
$lang->bug->report->closedBugsPerUser->graph     = new stdclass();
$lang->bug->report->bugsPerSeverity->graph       = new stdclass();
$lang->bug->report->bugsPerResolution->graph     = new stdclass();
$lang->bug->report->bugsPerStatus->graph         = new stdclass();
$lang->bug->report->bugsPerActivatedCount->graph = new stdclass();
$lang->bug->report->bugsPerType->graph           = new stdclass();
$lang->bug->report->bugsPerPri->graph            = new stdclass();
$lang->bug->report->bugsPerAssignedTo->graph     = new stdclass();
$lang->bug->report->bugLiveDays->graph           = new stdclass();
$lang->bug->report->bugHistories->graph          = new stdclass();

$lang->bug->report->bugsPerExecution->graph->xAxisName = $lang->executionCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName     = '版本';
$lang->bug->report->bugsPerModule->graph->xAxisName    = '模組';

$lang->bug->report->openedBugsPerDay->type             = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName = '日期';

$lang->bug->report->resolvedBugsPerDay->type             = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = '日期';

$lang->bug->report->closedBugsPerDay->type             = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName = '日期';

$lang->bug->report->openedBugsPerUser->graph->xAxisName   = '用戶';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName = '用戶';
$lang->bug->report->closedBugsPerUser->graph->xAxisName   = '用戶';

$lang->bug->report->bugsPerSeverity->graph->xAxisName       = '嚴重程度';
$lang->bug->report->bugsPerResolution->graph->xAxisName     = '解決方案';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = '狀態';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = '激活次數';
$lang->bug->report->bugsPerPri->graph->xAxisName            = '優先順序';
$lang->bug->report->bugsPerType->graph->xAxisName           = '類型';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = '指派給';
$lang->bug->report->bugLiveDays->graph->xAxisName           = '處理時間';
$lang->bug->report->bugHistories->graph->xAxisName          = '處理步驟';

/* 操作記錄。*/
$lang->bug->action = new stdclass();
$lang->bug->action->resolved             = array('main' => '$date, 由 <strong>$actor</strong> 解決，方案為 <strong>$extra</strong> $appendLink。', 'extra' => 'resolutionList');
$lang->bug->action->tostory              = array('main' => '$date, 由 <strong>$actor</strong> 轉為<strong> ' . $lang->SRCommon . '</strong>，編號為 <strong>$extra</strong>。');
$lang->bug->action->totask               = array('main' => '$date, 由 <strong>$actor</strong> 導入為<strong>任務</strong>，編號為 <strong>$extra</strong>。');
$lang->bug->action->converttotask        = array('main' => '$date, 由 <strong>$actor</strong> 轉為<strong>任務</strong>，編號為 <strong>$extra</strong>。');
$lang->bug->action->linked2plan          = array('main' => '$date, 由 <strong>$actor</strong> 關聯到計劃 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfromplan     = array('main' => '$date, 由 <strong>$actor</strong> 從計劃 <strong>$extra</strong> 移除。');
$lang->bug->action->linked2build         = array('main' => '$date, 由 <strong>$actor</strong> 關聯到版本 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfrombuild    = array('main' => '$date, 由 <strong>$actor</strong> 從版本 <strong>$extra</strong> 移除。');
$lang->bug->action->unlinkedfromrelease  = array('main' => '$date, 由 <strong>$actor</strong> 從發佈 <strong>$extra</strong> 移除。');
$lang->bug->action->linked2release       = array('main' => '$date, 由 <strong>$actor</strong> 關聯到發佈 <strong>$extra</strong>。');
$lang->bug->action->linked2revision      = array('main' => '$date, 由 <strong>$actor</strong> 關聯到代碼提交 <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromrevision = array('main' => '$date, 由 <strong>$actor</strong> 取消關聯到代碼提交 <strong>$extra</strong>。');
$lang->bug->action->linkrelatedbug       = array('main' => '$date, 由 <strong>$actor</strong> 關聯相關Bug <strong>$extra</strong>。');
$lang->bug->action->unlinkrelatedbug     = array('main' => '$date, 由 <strong>$actor</strong> 移除相關Bug <strong>$extra</strong>。');

$lang->bug->featureBar['browse']['all']          = '全部';
$lang->bug->featureBar['browse']['unclosed']     = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['openedbyme']   = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['assigntome']   = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['resolvedbyme'] = $lang->bug->resolvedByMe;
$lang->bug->featureBar['browse']['assignedbyme'] = $lang->bug->assignedByMe;
$lang->bug->featureBar['browse']['more']         = $lang->more;

$lang->bug->moreSelects['browse']['more']['unresolved']    = $lang->bug->unResolved;
$lang->bug->moreSelects['browse']['more']['unconfirmed']   = $lang->bug->unconfirmed;
$lang->bug->moreSelects['browse']['more']['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->moreSelects['browse']['more']['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->moreSelects['browse']['more']['toclosed']      = $lang->bug->toClosed;
$lang->bug->moreSelects['browse']['more']['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->moreSelects['browse']['more']['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->moreSelects['browse']['more']['needconfirm']   = $lang->bug->needConfirm;

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = '選擇相關版本...';
$lang->bug->placeholder->newBuildName = '新版本名稱';
$lang->bug->placeholder->duplicate    = '請輸入關鍵字';

/* 交互提示。*/
$lang->bug->notice = new stdclass();
$lang->bug->notice->summary               = "本頁共 <strong>%s</strong> 個Bug，未解決 <strong>%s</strong>。";
$lang->bug->notice->unClosedSummary       = "本頁共 %s 個Bug，未關閉 %s。";
$lang->bug->notice->checkedSummary        = "已選擇 {checked} 項，共 {total} 項。";
$lang->bug->notice->confirmChangeProduct  = "修改{$lang->productCommon}會導致相應的{$lang->executionCommon}、{$lang->SRCommon}和任務發生變化，確定嗎？";
$lang->bug->notice->confirmDelete         = '您確認要刪除該Bug嗎？';
$lang->bug->notice->remindTask            = '該Bug已經轉化為任務，是否更新任務(編號:%s)狀態 ?';
$lang->bug->notice->skipClose             = 'Bug %s 不是已解決狀態，不能關閉，將自動忽略。';
$lang->bug->notice->executionAccessDenied = "您無權訪問該Bug所屬的{$lang->executionCommon}！";
$lang->bug->notice->confirmUnlinkBuild    = "更換解決版本將取消與舊版本的關聯，您確定取消該bug與%s的關聯嗎？";
$lang->bug->notice->noSwitchBranch        = 'Bug%s所屬模組不在當前分支下，將自動忽略。';
$lang->bug->notice->confirmToStory        = '轉需求後Bug將自動關閉，關閉原因為轉為需求。';
$lang->bug->notice->productDitto          = "該 bug 與上一 bug 不屬於同一{$lang->productCommon}！";
$lang->bug->notice->noBug                 = '暫時沒有 Bug。';
$lang->bug->notice->noModule              = '<div>您現在還沒有模組信息</div><div>請維護測試模組</div>';
$lang->bug->notice->delayWarning          = " <strong class='text-danger'> 延期%s天 </strong>";
$lang->bug->notice->skipNotActive         = "Bug %s 已解決或已關閉，不再修改。";

$lang->bug->error = new stdclass();
$lang->bug->error->notExist             = 'Bug不存在。';
$lang->bug->error->cannotActivate       = '狀態不是已解決或已關閉的Bug不能激活。';
$lang->bug->error->stepsNotEmpty        = "重現步驟不能為空。";
$lang->bug->error->duplicateBugNotExist = '重複Bug不存在。';
