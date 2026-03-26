<?php
/**
 * The execution module zh-tw file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: zh-tw.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
/* 欄位列表。*/
$lang->execution->allExecutions       = '所有' . $lang->execution->common;
$lang->execution->allExecutionAB      = "{$lang->execution->common}列表";
$lang->execution->id                  = $lang->executionCommon . '編號';
$lang->execution->type                = $lang->executionCommon . '類型';
$lang->execution->name                = $lang->executionCommon . '名稱';
$lang->execution->code                = $lang->executionCommon . '代號';
$lang->execution->projectName         = '所屬' . $lang->projectCommon;
$lang->execution->project             = '所屬' . $lang->projectCommon;
$lang->execution->execId              = "{$lang->execution->common}編號";
$lang->execution->execName            = "{$lang->execution->common}名稱";
$lang->execution->execCode            = "{$lang->execution->common}代號";
$lang->execution->execType            = "{$lang->execution->common}類型";
$lang->execution->lifetime            = $lang->projectCommon . '周期';
$lang->execution->attribute           = '階段類型';
$lang->execution->percent             = '工作量占比';
$lang->execution->milestone           = '里程碑';
$lang->execution->parent              = '所屬' . $lang->projectCommon;
$lang->execution->path                = '路徑';
$lang->execution->grade               = '層級';
$lang->execution->output              = '輸出';
$lang->execution->version             = '版本';
$lang->execution->parentVersion       = '父版本';
$lang->execution->planDuration        = '計劃周期天數';
$lang->execution->realDuration        = '實際周期天數';
$lang->execution->openedVersion       = '創建版本';
$lang->execution->lastEditedBy        = '最後編輯人';
$lang->execution->lastEditedDate      = '最後編輯日期';
$lang->execution->suspendedDate       = '暫停日期';
$lang->execution->vision              = '界面';
$lang->execution->displayCards        = '每列最大卡片數';
$lang->execution->fluidBoard          = '列寬度';
$lang->execution->stage               = '階段';
$lang->execution->pri                 = '優先順序';
$lang->execution->openedBy            = '由誰創建';
$lang->execution->openedDate          = '創建日期';
$lang->execution->closedBy            = '由誰關閉';
$lang->execution->closedDate          = '關閉日期';
$lang->execution->canceledBy          = '由誰取消';
$lang->execution->canceledDate        = '取消日期';
$lang->execution->begin               = '計劃開始';
$lang->execution->end                 = '計劃完成';
$lang->execution->dateRange           = '計划起止日期';
$lang->execution->realBeganAB         = '實際開始';
$lang->execution->realEndAB           = '實際完成';
$lang->execution->teamCount           = '人數';
$lang->execution->realBegan           = '實際開始日期';
$lang->execution->realEnd             = '實際完成日期';
$lang->execution->to                  = '至';
$lang->execution->days                = '可用工作日';
$lang->execution->day                 = '天';
$lang->execution->workHour            = '工時';
$lang->execution->workHourUnit        = 'h';
$lang->execution->totalHours          = '可用工時';
$lang->execution->totalDays           = '可用工日';
$lang->execution->status              = $lang->executionCommon . '狀態';
$lang->execution->execStatus          = "{$lang->execution->common}狀態";
$lang->execution->subStatus           = '子狀態';
$lang->execution->desc                = $lang->executionCommon . '描述';
$lang->execution->execDesc            = "{$lang->execution->common}描述";
$lang->execution->owner               = '負責人';
$lang->execution->PO                  = $lang->productCommon . '負責人';
$lang->execution->PM                  = $lang->executionCommon . '負責人';
$lang->execution->execPM              = "{$lang->execution->common}負責人";
$lang->execution->QD                  = '測試負責人';
$lang->execution->RD                  = '發佈負責人';
$lang->execution->release             = '發佈';
$lang->execution->acl                 = '訪問控制';
$lang->execution->auth                = '權限控制';
$lang->execution->teamName            = '團隊名稱';
$lang->execution->teamSetting         = '團隊設置';
$lang->execution->updateOrder         = '排序';
$lang->execution->order               = $lang->executionCommon . '排序';
$lang->execution->orderAB             = '排序';
$lang->execution->products            = '相關' . $lang->productCommon;
$lang->execution->whitelist           = '白名單';
$lang->execution->addWhitelist        = '添加白名單';
$lang->execution->unbindWhitelist     = '移除白名單';
$lang->execution->totalEstimate       = '預計';
$lang->execution->totalConsumed       = '消耗';
$lang->execution->totalLeft           = '剩餘';
$lang->execution->progress            = '進度';
$lang->execution->hours               = '預計 %s 消耗 %s 剩餘 %s';
$lang->execution->viewBug             = '查看bug';
$lang->execution->noProduct           = "無{$lang->executionCommon}";
$lang->execution->noStory             = "暫時沒有需求";
$lang->execution->createStory         = "提{$lang->SRCommon}";
$lang->execution->storyTitle          = "{$lang->SRCommon}名稱";
$lang->execution->storyView           = "{$lang->SRCommon}詳情";
$lang->execution->all                 = '所有';
$lang->execution->undone              = '未完成';
$lang->execution->unclosed            = '未關閉';
$lang->execution->closedExecution     = '已關閉的執行';
$lang->execution->typeDesc            = "運維{$lang->executionCommon}沒有{$lang->SRCommon}、bug、構建、測試功能。";
$lang->execution->mine                = '我負責：';
$lang->execution->involved            = '我參與';
$lang->execution->other               = '其他';
$lang->execution->deleted             = '已刪除';
$lang->execution->delayed             = '已延期';
$lang->execution->product             = $lang->execution->products;
$lang->execution->readjustTime        = "調整{$lang->executionCommon}起止時間";
$lang->execution->readjustTask        = '順延任務的起止時間';
$lang->execution->effort              = '工時';
$lang->execution->storyEstimate       = '需求估算';
$lang->execution->newEstimate         = '新一輪估算';
$lang->execution->reestimate          = '重新估算';
$lang->execution->selectRound         = '選擇輪次';
$lang->execution->average             = '平均值';
$lang->execution->relatedMember       = '相關成員';
$lang->execution->member              = '成員';
$lang->execution->watermark           = '由禪道導出';
$lang->execution->burnXUnit           = '(日期)';
$lang->execution->burnYUnit           = '(工時)';
$lang->execution->count               = '(數量)';
$lang->execution->waitTasks           = '待處理';
$lang->execution->viewByUser          = '按用戶查看';
$lang->execution->oneProduct          = "階段只能關聯一個{$lang->productCommon}";
$lang->execution->noLinkProduct       = "關聯{$lang->productCommon}不能為空！";
$lang->execution->recent              = '近期訪問：';
$lang->execution->noTeam              = '暫時沒有團隊成員';
$lang->execution->or                  = '或';
$lang->execution->selectProject       = '請選擇' . $lang->projectCommon;
$lang->execution->unfoldClosed        = '展開已結束';
$lang->execution->editName            = '編輯名稱';
$lang->execution->setWIP              = '在製品數量設置（WIP）';
$lang->execution->sortColumn          = '看板列卡片排序';
$lang->execution->batchCreateStory    = "批量新建{$lang->SRCommon}";
$lang->execution->batchCreateTask     = '批量建任務';
$lang->execution->kanbanNoLinkProduct = "看板沒有關聯{$lang->productCommon}";
$lang->execution->myTask              = "我的任務";
$lang->execution->list                = '列表';
$lang->execution->allProject          = '全部' . $lang->projectCommon;
$lang->execution->method              = '管理方法';
$lang->execution->sameAsParent        = "同父階段";
$lang->execution->selectStoryPlan     = '選擇計劃';
$lang->execution->parentStage         = '所屬父階段';

/* Fields of zt_team. */
$lang->execution->root          = '源ID';
$lang->execution->estimate      = '預計';
$lang->execution->estimateHours = '預計工時';
$lang->execution->consumed      = '消耗';
$lang->execution->consumedHours = '消耗工時';
$lang->execution->left          = '剩餘';
$lang->execution->leftHours     = '預計剩餘';

$lang->execution->copyTeamTip        = "可以選擇複製{$lang->projectCommon}或{$lang->execution->common}團隊的成員";
$lang->execution->daysGreaterProject = '可用工日不能大於執行的可用工日『%s』';
$lang->execution->errorHours         = '可用工時/天不能大於『24』';
$lang->execution->agileplusMethodTip = "融合{$lang->projectCommon}創建執行時，支持{$lang->executionCommon}和看板兩種管理方法。";
$lang->execution->typeTip            = '“綜合”類型的父階段可以創建其它類型的子級，其它類型的父階段只能創建同類型的子級';
$lang->execution->waterfallTip       = "瀑布{$lang->projectCommon}和融合瀑布{$lang->projectCommon}中，";
$lang->execution->progressTip        = '總進度 = 已消耗工時 / (已消耗工時 + 剩餘工時)';
$lang->execution->limitedTip         = "受限用戶只能編輯與自己相關的任務，不能新增任務。與自己相關的內容，包含指派給、已完成、已取消、已關閉、最後編輯，不包含抄送給的內容。 \n如果該用戶之前不是受限用戶，現在是受限用戶，那麼之前由他創建的任務還有相關的操作權限。";

$lang->execution->start    = "開始";
$lang->execution->activate = "激活";
$lang->execution->putoff   = "延期";
$lang->execution->suspend  = "掛起";
$lang->execution->close    = "關閉";
$lang->execution->export   = "導出";
$lang->execution->next     = "下一步";

$lang->execution->endList[7]   = '一星期';
$lang->execution->endList[14]  = '兩星期';
$lang->execution->endList[31]  = '一個月';
$lang->execution->endList[62]  = '兩個月';
$lang->execution->endList[93]  = '三個月';
$lang->execution->endList[186] = '半年';
$lang->execution->endList[365] = '一年';

$lang->execution->lifeTimeList['short'] = "短期";
$lang->execution->lifeTimeList['long']  = "長期";
$lang->execution->lifeTimeList['ops']   = "運維";

$lang->execution->cfdTypeList['story'] = "按{$lang->SRCommon}查看";
$lang->execution->cfdTypeList['task']  = "按任務查看";
$lang->execution->cfdTypeList['bug']   = "按Bug查看";

$lang->team->account    = '用戶';
$lang->team->realname   = '姓名';
$lang->team->role       = '角色';
$lang->team->roleAB     = '我的角色';
$lang->team->join       = '加盟日期';
$lang->team->hours      = '可用工時/天';
$lang->team->days       = '可用工日';
$lang->team->totalHours = '總計';

$lang->team->limited            = '受限用戶';
$lang->team->limitedList['yes'] = '是';
$lang->team->limitedList['no']  = '否';

$lang->execution->basicInfo = '基本信息';
$lang->execution->otherInfo = '其他信息';

/* 欄位取值列表。*/
$lang->execution->statusList['wait']      = '未開始';
$lang->execution->statusList['doing']     = '進行中';
$lang->execution->statusList['suspended'] = '已掛起';
$lang->execution->statusList['closed']    = '已關閉';

$lang->execution->aclList['open']    = "繼承{$lang->projectCommon}訪問權限（能訪問當前{$lang->projectCommon}，即可訪問）";
$lang->execution->aclList['private'] = "私有（團隊成員和{$lang->projectCommon}負責人、干係人可訪問）";

$lang->execution->kanbanAclList['open']    = "繼承{$lang->projectCommon}";
$lang->execution->kanbanAclList['private'] = '私有';

$lang->execution->storyPoint = '故事點';

$lang->execution->burnByList['left']       = '按剩餘工時查看';
$lang->execution->burnByList['estimate']   = "按計劃工時查看";
$lang->execution->burnByList['storyPoint'] = '按故事點查看';

/* 方法列表。*/
$lang->execution->index                     = "{$lang->execution->common}主頁";
$lang->execution->task                      = '任務列表';
$lang->execution->groupTask                 = '分組瀏覽任務';
$lang->execution->story                     = "{$lang->SRCommon}列表";
$lang->execution->qa                        = '測試儀表盤';
$lang->execution->bug                       = 'Bug列表';
$lang->execution->testcase                  = '用例列表';
$lang->execution->dynamic                   = '動態';
$lang->execution->latestDynamic             = '最新動態';
$lang->execution->build                     = '構建列表';
$lang->execution->testtask                  = '測試單';
$lang->execution->burn                      = '燃盡圖';
$lang->execution->computeBurn               = '更新燃盡圖';
$lang->execution->computeCFD                = '更新累積流圖';
$lang->execution->fixFirst                  = '修改首天工時';
$lang->execution->team                      = '團隊成員';
$lang->execution->doc                       = '文檔列表';
$lang->execution->doclib                    = '文檔庫';
$lang->execution->manageProducts            = '關聯' . $lang->productCommon;
$lang->execution->linkStory                 = "關聯需求";
$lang->execution->linkStoryByPlan           = "按照計劃關聯";
$lang->execution->linkPlan                  = "關聯計劃";
$lang->execution->unlinkStoryTasks          = "未關聯{$lang->SRCommon}任務";
$lang->execution->linkedProducts            = '已關聯';
$lang->execution->unlinkedProducts          = '未關聯';
$lang->execution->view                      = "{$lang->execution->common}概況";
$lang->execution->startAction               = "開始{$lang->execution->common}";
$lang->execution->activateAction            = "激活{$lang->execution->common}";
$lang->execution->delayAction               = "延期{$lang->execution->common}";
$lang->execution->suspendAction             = "掛起{$lang->execution->common}";
$lang->execution->closeAction               = "關閉{$lang->execution->common}";
$lang->execution->testtaskAction            = "{$lang->execution->common}測試單";
$lang->execution->teamAction                = "{$lang->execution->common}團隊";
$lang->execution->kanbanAction              = "{$lang->execution->common}看板";
$lang->execution->printKanbanAction         = "打印看板";
$lang->execution->treeAction                = "{$lang->execution->common}樹狀圖";
$lang->execution->exportAction              = "導出{$lang->execution->common}";
$lang->execution->computeBurnAction         = "計算燃盡圖";
$lang->execution->create                    = "添加{$lang->executionCommon}";
$lang->execution->createExec                = "添加{$lang->execution->common}";
$lang->execution->createAction              = "添加{$lang->execution->common}";
$lang->execution->copyExec                  = "複製{$lang->execution->common}";
$lang->execution->copy                      = "複製{$lang->executionCommon}";
$lang->execution->delete                    = "刪除{$lang->executionCommon}";
$lang->execution->deleteAB                  = "刪除{$lang->execution->common}";
$lang->execution->browse                    = "瀏覽{$lang->execution->common}";
$lang->execution->edit                      = "設置{$lang->executionCommon}";
$lang->execution->editAction                = "編輯{$lang->execution->common}";
$lang->execution->batchEdit                 = "編輯";
$lang->execution->batchEditAction           = "批量編輯";
$lang->execution->batchChangeStatus         = "批量修改狀態";
$lang->execution->manageMembers             = '團隊管理';
$lang->execution->manageTeamMember          = '維護團隊成員';
$lang->execution->unlinkMember              = '移除成員';
$lang->execution->unlinkStory               = "移除{$lang->SRCommon}";
$lang->execution->unlinkStoryAB             = "移除{$lang->SRCommon}";
$lang->execution->batchUnlinkStory          = "批量移除{$lang->SRCommon}";
$lang->execution->importTask                = '轉入任務';
$lang->execution->importPlanStories         = "按計劃關聯{$lang->SRCommon}";
$lang->execution->importBug                 = '導入Bug';
$lang->execution->tree                      = '樹狀圖';
$lang->execution->treeTask                  = '只看任務';
$lang->execution->treeStory                 = "僅需求";
$lang->execution->treeViewTask              = '樹狀圖查看任務';
$lang->execution->treeViewStory             = "樹狀圖查看{$lang->SRCommon}";
$lang->execution->storyKanban               = "{$lang->SRCommon}看板";
$lang->execution->storySort                 = "{$lang->SRCommon}排序";
$lang->execution->importPlanStory           = "創建{$lang->executionCommon}成功！\n是否導入計劃關聯的相關%s？導入時只能導入激活狀態的" . $lang->common->story . '。';
$lang->execution->importEditPlanStory       = "編輯{$lang->executionCommon}成功！\n是否導入計劃關聯的相關%s？導入時將自動過濾掉草稿狀態的" . $lang->common->story . '。';
$lang->execution->importBranchPlanStory     = "創建{$lang->executionCommon}成功！\n是否導入計劃關聯的相關%s？導入時將只關聯本" . $lang->executionCommon . '所關聯分支的激活需求。';
$lang->execution->importBranchEditPlanStory = "編輯{$lang->executionCommon}成功！\n是否導入計劃關聯的相關%s？導入時將只關聯本" . $lang->executionCommon . '所關聯分支的激活需求。';
$lang->execution->needLinkProducts          = "該執行還未關聯任何{$lang->productCommon}，相關功能無法使用，請先關聯{$lang->productCommon}後再試。";
$lang->execution->iteration                 = '版本迭代';
$lang->execution->iterationInfo             = '迭代%s次';
$lang->execution->viewAll                   = '查看所有';
$lang->execution->testreport                = '測試報告';
$lang->execution->taskKanban                = '任務看板';
$lang->execution->RDKanban                  = '研發看板';

/* 分組瀏覽。*/
$lang->execution->allTasks     = '所有';
$lang->execution->assignedToMe = '指派給我';
$lang->execution->myInvolved   = '由我參與';
$lang->execution->assignedByMe = '由我指派';

$lang->execution->statusSelects['']             = '更多';
$lang->execution->statusSelects['wait']         = '未開始';
$lang->execution->statusSelects['doing']        = '進行中';
$lang->execution->statusSelects['undone']       = '未完成';
$lang->execution->statusSelects['finishedbyme'] = '我完成';
$lang->execution->statusSelects['done']         = '已完成';
$lang->execution->statusSelects['closed']       = '已關閉';
$lang->execution->statusSelects['cancel']       = '已取消';
$lang->execution->statusSelects['delayed']      = '已延期';

$lang->execution->groups['']           = '分組查看';
$lang->execution->groups['story']      = "{$lang->SRCommon}分組";
$lang->execution->groups['status']     = '狀態分組';
$lang->execution->groups['pri']        = '優先順序分組';
$lang->execution->groups['assignedTo'] = '指派給分組';
$lang->execution->groups['finishedBy'] = '完成者分組';
$lang->execution->groups['closedBy']   = '關閉者分組';
$lang->execution->groups['type']       = '類型分組';

$lang->execution->groupFilter['story']['all']         = '全部';
$lang->execution->groupFilter['story']['linked']      = "已關聯{$lang->SRCommon}的任務";
$lang->execution->groupFilter['pri']['all']           = '全部';
$lang->execution->groupFilter['pri']['noset']         = '未設置';
$lang->execution->groupFilter['assignedTo']['undone'] = '未完成';
$lang->execution->groupFilter['assignedTo']['all']    = '全部';

$lang->execution->byQuery = '搜索';

/* 查詢條件列表。*/
$lang->execution->allExecution      = "所有{$lang->executionCommon}";
$lang->execution->aboveAllProduct   = "以上所有{$lang->productCommon}";
$lang->execution->aboveAllExecution = "以上所有{$lang->executionCommon}";

/* 頁面提示。*/
$lang->execution->linkStoryByPlanTips  = "此操作會將所選計划下面的{$lang->SRCommon}全部關聯到此{$lang->executionCommon}中";
$lang->execution->batchCreateStoryTips = "請選擇需要批量新建研發需求的{$lang->productCommon}";
$lang->execution->selectExecution      = "請選擇{$lang->execution->common}";
$lang->execution->beginAndEnd          = '起止時間';
$lang->execution->lblStats             = '工時信息';
$lang->execution->DurationStats        = '工期信息';
$lang->execution->stats                = '可用工時 <strong>%s</strong> 工時，總共預計 <strong>%s</strong> 工時，已經消耗 <strong>%s</strong> 工時，預計剩餘 <strong>%s</strong> 工時';
$lang->execution->taskSummary          = "本頁共 <strong>%s</strong> 個任務，未開始 <strong>%s</strong>，進行中 <strong>%s</strong>，總預計 <strong>%s</strong> 工時，已消耗 <strong>%s</strong> 工時，剩餘 <strong>%s</strong> 工時。";
$lang->execution->pageSummary          = "本頁共 <strong>%total%</strong> 個任務，未開始 <strong>%wait%</strong>，進行中 <strong>%doing%</strong>，總預計 <strong>%estimate%</strong> 工時，已消耗 <strong>%consumed%</strong> 工時，剩餘 <strong>%left%</strong> 工時。";
$lang->execution->checkedSummary       = "選中 <strong>%total%</strong> 個任務，未開始 <strong>%wait%</strong>，進行中 <strong>%doing%</strong>，總預計 <strong>%estimate%</strong> 工時，已消耗 <strong>%consumed%</strong> 工時，剩餘 <strong>%left%</strong> 工時。";
$lang->execution->executionSummary     = "本頁共 <strong>%s</strong> 個{$lang->executionCommon}。";
$lang->execution->pageExecSummary      = "本頁共 <strong>%total%</strong> 個{$lang->executionCommon}，未開始 <strong>%wait%</strong>，進行中 <strong>%doing%</strong>。";
$lang->execution->checkedExecSummary   = "選中 <strong>%total%</strong> 個{$lang->executionCommon}，未開始 <strong>%wait%</strong>，進行中 <strong>%doing%</strong>。";
$lang->execution->memberHoursAB        = "<div>%s有 <strong>%s</strong> 工時</div>";
$lang->execution->memberHours          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s可用工時</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">總任務</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">進行中</div><div class="segment-value"><span class="label label-dot primary"></span> %s</div></div><div class="segment"><div class="segment-title">未開始</div><div class="segment-value"><span class="label label-dot secondary"></span> %s</div></div></div></div>';
$lang->execution->timeSummary          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">總預計</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">已消耗</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">剩餘</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB       = "<div>總任務 <strong>%s : </strong><span class='text-muted'>未開始</span> %s &nbsp; <span class='text-muted'>進行中</span> %s</div><div>總預計 <strong>%s : </strong><span class='text-muted'>已消耗</span> %s &nbsp; <span class='text-muted'>剩餘</span> %s</div>";
$lang->execution->wbs                  = "分解任務";
$lang->execution->batchWBS             = "批量分解";
$lang->execution->howToUpdateBurn      = "<a href='https://api.zentao.net/goto.php?item=burndown&lang=zh-tw' target='_blank' title='如何更新燃盡圖？'>幫助 <i class='icon icon-help text-gray'></i></a>";
$lang->execution->whyNoStories         = "看起來沒有需求可以關聯。請檢查下{$lang->executionCommon}關聯的{$lang->productCommon}中有沒有需求，而且要確保它們已經審核通過。";
$lang->execution->projectNoStories     = "看起來沒有需求可以關聯。請檢查下{$lang->projectCommon}中有沒有需求，而且要確保它們已經審核通過。";
$lang->execution->productStories       = "{$lang->executionCommon}關聯的{$lang->SRCommon}是{$lang->productCommon}需求的子集，並且只有評審通過的{$lang->SRCommon}才能關聯。請<a href='%s'>關聯{$lang->SRCommon}</a>。";
$lang->execution->haveBranchDraft      = "導入完成！有%s條非激活狀態或不是{$lang->executionCommon}關聯分支的需求無法導入";
$lang->execution->haveDraft            = "導入完成！有%s條非激活狀態的需求無法導入";
$lang->execution->doneExecutions       = '已結束';
$lang->execution->selectDept           = '選擇部門';
$lang->execution->selectDeptTitle      = '選擇一個部門的成員';
$lang->execution->copyTeam             = '複製團隊';
$lang->execution->copyFromTeam         = "複製自{$lang->execution->common}團隊： <strong>%s</strong>";
$lang->execution->noMatched            = "找不到包含'%s'的{$lang->execution->common}";
$lang->execution->copyTitle            = "請選擇一個{$lang->execution->common}來複制";
$lang->execution->copyNoExecution      = "沒有可用的{$lang->execution->common}來複制";
$lang->execution->copyFromExecution    = "複製自{$lang->execution->common} <strong>%s</strong>";
$lang->execution->cancelCopy           = '取消複製';
$lang->execution->byPeriod             = '按時間段';
$lang->execution->byUser               = '按用戶';
$lang->execution->noExecution          = "暫時沒有{$lang->executionCommon}。";
$lang->execution->noExecutions         = "暫時沒有{$lang->execution->common}。";
$lang->execution->noPrintData          = "暫無數據可打印";
$lang->execution->noMembers            = '暫時沒有團隊成員。';
$lang->execution->workloadTotal        = "工作量占比累計不應當超過100%s, 當前{$lang->productCommon}下的工作量之和為%s";
$lang->execution->linkAllStoryTip      = "({$lang->projectCommon}下還未關聯{$lang->SRCommon}，可直接關聯該{$lang->execution->common}所關聯{$lang->productCommon}的{$lang->SRCommon})";
$lang->execution->copyTeamTitle        = "選擇一個{$lang->project->common}或{$lang->execution->common}團隊";

/* 交互提示。*/
$lang->execution->confirmDelete                = "您確定刪除{$lang->executionCommon}[%s]嗎？";
$lang->execution->confirmUnlinkMember          = "您確定從該{$lang->executionCommon}中移除該用戶嗎？";
$lang->execution->confirmUnlinkStory           = "移除該需求後，該需求關聯的用例將被移除，該需求關聯的任務將被取消，請確認。";
$lang->execution->confirmBatchUnlinkStory      = "移除需求後，需求關聯的用例將被移除，需求關聯的任務將被取消。";
$lang->execution->confirmSync                  = "修改所屬{$lang->projectCommon}後,為了保持數據的一致性，該執行所關聯的{$lang->productCommon}、{$lang->SRCommon}、團隊和白名單數據將會同步到新的{$lang->projectCommon}中，請知悉。";
$lang->execution->confirmUnlinkExecutionStory  = "您確定從該{$lang->projectCommon}中移除該需求嗎？";
$lang->execution->notAllowedUnlinkStory        = "該{$lang->SRCommon}已經與{$lang->projectCommon}下{$lang->executionCommon}相關聯，請從{$lang->executionCommon}中移除後再操作。";
$lang->execution->notAllowRemoveProducts       = "該{$lang->productCommon}中的{$lang->SRCommon}%s已與該{$lang->executionCommon}進行了關聯，請取消關聯後再操作。";
$lang->execution->errorNoLinkedProducts        = "該{$lang->executionCommon}沒有關聯的{$lang->productCommon}，系統將轉到{$lang->productCommon}關聯頁面";
$lang->execution->errorSameProducts            = "{$lang->executionCommon}不能關聯多個相同的{$lang->productCommon}。";
$lang->execution->errorSameBranches            = "{$lang->executionCommon}不能關聯多個相同的分支。";
$lang->execution->errorBegin                   = "{$lang->executionCommon}的開始時間不能小於所屬{$lang->projectCommon}的開始時間%s。";
$lang->execution->errorEnd                     = "{$lang->executionCommon}的截止時間不能大於所屬{$lang->projectCommon}的結束時間%s。";
$lang->execution->errorLesserProject           = "{$lang->executionCommon}的計劃開始時間不能小於所屬{$lang->projectCommon}的計劃開始時間%s。";
$lang->execution->errorGreaterProject          = "{$lang->executionCommon}的計劃完成時間不能大於所屬{$lang->projectCommon}的計劃完成時間%s。";
$lang->execution->errorCommonBegin             = $lang->executionCommon . "開始日期應大於等於{$lang->projectCommon}的開始日期：%s。";
$lang->execution->errorCommonEnd               = $lang->executionCommon . "截止日期應小於等於{$lang->projectCommon}的截止日期：%s。";
$lang->execution->errorLesserParent            = '計劃開始時間不能小於所屬父階段的計劃開始時間：%s。';
$lang->execution->errorGreaterParent           = '計劃完成時間不能大於所屬父階段的計劃完成時間：%s。';
$lang->execution->errorNameRepeat              = "相同父階段的子%s名稱不能相同";
$lang->execution->errorAttrMatch               = "父階段類型為[%s]，階段類型需與父階段一致";
$lang->execution->errorLesserPlan              = "『%s』應當不小於計劃開始時間『%s』。";
$lang->execution->errorParentExecution         = '當前階段是父階段，不允許訪問。';
$lang->execution->errorFloat                   = '『%s』應當是不小於0的數字，可以是小數。';
$lang->execution->accessDenied                 = "您無權訪問該{$lang->executionCommon}！";
$lang->execution->tips                         = '提示';
$lang->execution->afterInfo                    = "%s添加成功，您現在可以進行以下操作：";
$lang->execution->setTeam                      = '設置團隊';
$lang->execution->createTask                   = '創建任務';
$lang->execution->goback                       = "返回任務列表";
$lang->execution->gobackExecution              = "返回{$lang->execution->common}列表";
$lang->execution->noweekend                    = '去除周末';
$lang->execution->nodelay                      = '去除延期日期';
$lang->execution->withweekend                  = '顯示周末';
$lang->execution->withdelay                    = '顯示延期日期';
$lang->execution->unitTemplate                 = '（單位：%s）';
$lang->execution->interval                     = '間隔';
$lang->execution->fixFirstWithLeft             = '修改剩餘工時';
$lang->execution->unfinishedExecution          = "該{$lang->executionCommon}下還有";
$lang->execution->unfinishedTask               = "[%s]個未完成的任務，";
$lang->execution->unresolvedBug                = "[%s]個未解決的bug，";
$lang->execution->projectNotEmpty              = "所屬{$lang->projectCommon}不能為空。";
$lang->execution->confirmStoryToTask           = '%s' . $lang->SRCommon . '已經在當前' . $lang->execution->common . '中轉了任務，請確認是否重複轉任務。';
$lang->execution->ge                           = "『%s』應當不小於實際開始時間『%s』。";
$lang->execution->storyDragError               = "該{$lang->SRCommon}不是激活狀態，請激活後再拖動";
$lang->execution->countTip                     = '（%s人）';
$lang->execution->pleaseInput                  = "請輸入";
$lang->execution->week                         = '周';
$lang->execution->checkedExecutions            = "共選中%s個{$lang->executionCommon}。";
$lang->execution->hasStartedTaskOrSubStage     = "%s%s下的任務或子階段已經開始，無法修改，已過濾。";
$lang->execution->hasSuspendedOrClosedChildren = "階段%s下的子階段未全部掛起或關閉，無法修改，已過濾。";
$lang->execution->hasNotClosedChildren         = "階段%s下的子階段未全部關閉，無法修改，已過濾。";
$lang->execution->hasStartedTask               = "%s%s下的任務已經開始，無法修改，已過濾。";
$lang->execution->cannotManageProducts         = "當前{$lang->execution->common}的{$lang->project->common}為%s{$lang->project->common}，不能關聯{$lang->productCommon}。";
$lang->execution->confirmCloseExecution        = "該{$lang->executionCommon}下還有未關閉的任務：%s，您確定要關閉{$lang->executionCommon}嗎？";
$lang->execution->confirmBatchCloseExecution   = "%s中還有未關閉的任務，您確定要關閉{$lang->executionCommon}嗎？";

/* 統計。*/
$lang->execution->charts = new stdclass();
$lang->execution->charts->burn = new stdclass();
$lang->execution->charts->burn->graph = new stdclass();
$lang->execution->charts->burn->graph->caption      = "燃盡圖";
$lang->execution->charts->burn->graph->xAxisName    = "日期";
$lang->execution->charts->burn->graph->yAxisName    = "HOUR";
$lang->execution->charts->burn->graph->baseFontSize = 12;
$lang->execution->charts->burn->graph->formatNumber = 0;
$lang->execution->charts->burn->graph->animation    = 0;
$lang->execution->charts->burn->graph->rotateNames  = 1;
$lang->execution->charts->burn->graph->showValues   = 0;
$lang->execution->charts->burn->graph->reference    = '參考';
$lang->execution->charts->burn->graph->actuality    = '實際';
$lang->execution->charts->burn->graph->delay        = '延期';

$lang->execution->charts->cfd = new stdclass();
$lang->execution->charts->cfd->cfdTip        = "<p>
1.累積流圖反應各個階段累積處理的工作項數量隨時間的變化趨勢。</br>
2.橫軸代表日期，縱軸代表工作項數量。</br>
3.通過此圖可計算出在製品數量，交付速率以及平均前置時間，從而瞭解團隊的交付情況。</p>";
$lang->execution->charts->cfd->cycleTime     = '平均周期時間';
$lang->execution->charts->cfd->cycleTimeTip  = '平均每個卡片從開發啟動到完成的周期時間';
$lang->execution->charts->cfd->throughput    = '吞吐率';
$lang->execution->charts->cfd->throughputTip = '吞吐率 = 在製品 / 平均周期時間';

$lang->execution->charts->cfd->begin          = '開始日期';
$lang->execution->charts->cfd->end            = '結束日期';
$lang->execution->charts->cfd->errorBegin     = '開始日期應小於結束日期';
$lang->execution->charts->cfd->errorDateRange = '累積流圖只提供3個月內的數據展示';
$lang->execution->charts->cfd->dateRangeTip   = '累積流圖只展示3個月內的數據';

$lang->execution->placeholder = new stdclass();
$lang->execution->placeholder->code      = '團隊內部的簡稱';
$lang->execution->placeholder->totalLeft = "{$lang->executionCommon}開始時的總預計工時";

$lang->execution->selectGroup = new stdclass();
$lang->execution->selectGroup->done = '(已結束)';

$lang->execution->orderList['order_asc']  = "{$lang->SRCommon}排序正序";
$lang->execution->orderList['order_desc'] = "{$lang->SRCommon}排序倒序";
$lang->execution->orderList['pri_asc']    = "{$lang->SRCommon}優先順序正序";
$lang->execution->orderList['pri_desc']   = "{$lang->SRCommon}優先順序倒序";
$lang->execution->orderList['stage_asc']  = "{$lang->SRCommon}階段正序";
$lang->execution->orderList['stage_desc'] = "{$lang->SRCommon}階段倒序";

$lang->execution->kanban        = "看板";
$lang->execution->kanbanSetting = "看板設置";
$lang->execution->setKanban     = "設置看板";
$lang->execution->resetKanban   = "恢復預設";
$lang->execution->printKanban   = "打印看板";
$lang->execution->fullScreen    = "看板全屏展示";
$lang->execution->bugList       = "Bug列表";

$lang->execution->kanbanHideCols   = '看板隱藏已關閉、已取消列';
$lang->execution->kanbanShowOption = '顯示摺疊信息';
$lang->execution->kanbanColsColor  = '看板列自定義顏色';
$lang->execution->kanbanCardsUnit  = '個';

$lang->execution->kanbanViewList['all']   = '綜合看板';
$lang->execution->kanbanViewList['story'] = "{$lang->SRCommon}看板";
$lang->execution->kanbanViewList['bug']   = 'Bug看板';
$lang->execution->kanbanViewList['task']  = '任務看板';

$lang->execution->teamWords  = '團隊';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = '是否恢復看板預設設置？';
$lang->kanbanSetting->optionList['0'] = '隱藏';
$lang->kanbanSetting->optionList['1'] = '顯示';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = '打印看板';
$lang->printKanban->content = '內容';
$lang->printKanban->print   = '打印';

$lang->printKanban->taskStatus = '狀態';

$lang->printKanban->typeList['all']       = '全部';
$lang->printKanban->typeList['increment'] = '增量';

$lang->execution->typeList['']       = '';
$lang->execution->typeList['stage']  = '階段';
$lang->execution->typeList['sprint'] = '迭代';
$lang->execution->typeList['kanban'] = '看板';

$lang->execution->featureBar['tree']['all'] = '全部';

$lang->execution->featureBar['task']['all']          = '全部';
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['assignedbyme'] = $lang->execution->assignedByMe;
$lang->execution->featureBar['task']['needconfirm']  = "{$lang->SRCommon}變更";
$lang->execution->featureBar['task']['status']       = $lang->more;

$lang->execution->moreSelects['task']['status']['wait']         = '未開始';
$lang->execution->moreSelects['task']['status']['doing']        = '進行中';
$lang->execution->moreSelects['task']['status']['undone']       = '未完成';
$lang->execution->moreSelects['task']['status']['finishedbyme'] = '我完成';
$lang->execution->moreSelects['task']['status']['done']         = '已完成';
$lang->execution->moreSelects['task']['status']['closed']       = '已關閉';
$lang->execution->moreSelects['task']['status']['cancel']       = '已取消';
$lang->execution->moreSelects['task']['status']['delayed']      = '已延期';

$lang->execution->featureBar['all']['all']       = '全部';
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['delayed']   = $lang->execution->delayed;
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->featureBar['bug']['all']        = '全部';
$lang->execution->featureBar['bug']['unresolved'] = '未解決';

$lang->execution->featureBar['build']['all'] = '全部構建';

$lang->execution->featureBar['story']['all']       = '全部';
$lang->execution->featureBar['story']['unclosed']  = '未關閉';
$lang->execution->featureBar['story']['draft']     = '草稿';
$lang->execution->featureBar['story']['reviewing'] = '評審中';

$lang->execution->featureBar['testcase']['all']         = '全部';
$lang->execution->featureBar['testcase']['wait']        = '待評審';
$lang->execution->featureBar['testcase'][]              = '-';
$lang->execution->featureBar['testcase']['needconfirm'] = "{$lang->common->story}變動";

$lang->execution->featureBar['importtask']['all'] = $lang->execution->importTask;

$lang->execution->featureBar['importbug']['all'] = $lang->execution->importBug;

$lang->execution->myExecutions = '我參與的';
$lang->execution->doingProject = "進行中的{$lang->projectCommon}";

$lang->execution->kanbanColType['wait']      = $lang->execution->statusList['wait']      . '的' . $lang->execution->common;
$lang->execution->kanbanColType['doing']     = $lang->execution->statusList['doing']     . '的' . $lang->execution->common;
$lang->execution->kanbanColType['suspended'] = $lang->execution->statusList['suspended'] . '的' . $lang->execution->common;
$lang->execution->kanbanColType['closed']    = $lang->execution->statusList['closed']    . '的' . $lang->execution->common . '(最近2期)';

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = '全部展開';
$lang->execution->treeLevel['root']  = '全部摺疊';
$lang->execution->treeLevel['task']  = '全部顯示';
$lang->execution->treeLevel['story'] = "僅需求";

$lang->execution->action = new stdclass();
$lang->execution->action->opened               = '$date, 由 <strong>$actor</strong> 創建。$extra' . "\n";
$lang->execution->action->managed              = '$date, 由 <strong>$actor</strong> 維護。$extra' . "\n";
$lang->execution->action->edited               = '$date, 由 <strong>$actor</strong> 編輯。$extra' . "\n";
$lang->execution->action->extra                = "相關{$lang->productCommon}為 %s。";
$lang->execution->action->startbychildactivate = '$date, 系統判斷由於子階段激活，將' . $lang->executionCommon . '狀態置為進行中。' . "\n";
$lang->execution->action->waitbychilddelete    = '$date, 系統判斷由於子階段刪除，將' . $lang->executionCommon . '狀態置為未開始。' . "\n";
$lang->execution->action->closebychilddelete   = '$date, 系統判斷由於子階段刪除，將' . $lang->executionCommon . '狀態置為已關閉。' . "\n";
$lang->execution->action->closebychildclose    = '$date, 系統判斷由於子階段關閉，將' . $lang->executionCommon . '狀態置為已關閉。' . "\n";
$lang->execution->action->waitbychild          = '$date, 系統判斷由於子階段 <strong>全部為未開始</strong> ，將階段狀態置為 <strong>未開始</strong> 。';
$lang->execution->action->suspendedbychild     = '$date, 系統判斷由於子階段 <strong>全部掛起</strong> ，將階段狀態置為 <strong>已掛起</strong> 。';
$lang->execution->action->closedbychild        = '$date, 系統判斷由於子階段 <strong>全部關閉</strong> ，將階段狀態置為 <strong>已關閉</strong> 。';
$lang->execution->action->startbychildstart    = '$date, 系統判斷由於子階段 <strong>開始</strong> ，將階段狀態置為 <strong>進行中</strong> 。';
$lang->execution->action->startbychildactivate = '$date, 系統判斷由於子階段 <strong>激活</strong> ，將階段狀態置為 <strong>進行中</strong> 。';
$lang->execution->action->startbychildsuspend  = '$date, 系統判斷由於子階段 <strong>掛起</strong> ，將階段狀態置為 <strong>進行中</strong> 。';
$lang->execution->action->startbychildclose    = '$date, 系統判斷由於子階段 <strong>關閉</strong> ，將階段狀態置為 <strong>進行中</strong> 。';
$lang->execution->action->startbychildcreate   = '$date, 系統判斷由於 <strong>創建</strong> 子階段 ，將階段狀態置為 <strong>進行中</strong> 。';
$lang->execution->action->startbychildedit     = '$date, 系統判斷由於子階段 <strong>狀態修改</strong> ，將階段狀態置為 <strong>進行中</strong> 。';
$lang->execution->action->startbychild         = '$date, 系統判斷由於子階段 <strong>激活</strong> ，將階段狀態置為 <strong>進行中</strong> 。';
$lang->execution->action->waitbychild          = '$date, 系統判斷由於子階段 <strong>狀態修改</strong> ，將階段狀態置為 <strong>未開始</strong> 。';
$lang->execution->action->suspendbychild       = '$date, 系統判斷由於子階段 <strong>狀態修改</strong> ，將階段狀態置為 <strong>已掛起</strong> 。';
$lang->execution->action->closebychild         = '$date, 系統判斷由於子階段 <strong>狀態修改</strong> ，將階段狀態置為 <strong>已關閉</strong> 。';

$lang->execution->startbychildactivate = '激活了';
$lang->execution->waitbychilddelete    = '停止了';
$lang->execution->closebychilddelete   = '關閉了';
$lang->execution->closebychildclose    = '關閉了';
$lang->execution->waitbychild          = '激活了';
$lang->execution->suspendedbychild     = '掛起了';
$lang->execution->closedbychild        = '關閉了';
$lang->execution->startbychildstart    = '開始了';
$lang->execution->startbychildactivate = '激活了';
$lang->execution->startbychildsuspend  = '激活了';
$lang->execution->startbychildclose    = '激活了';
$lang->execution->startbychildcreate   = '激活了';
$lang->execution->startbychildedit     = '激活了';
$lang->execution->startbychild         = '激活了';
$lang->execution->waitbychild          = '停止了';
$lang->execution->suspendbychild       = '掛起了';
$lang->execution->closebychild         = '關閉了';

$lang->execution->statusColorList = array();
$lang->execution->statusColorList['wait']      = '#0991FF';
$lang->execution->statusColorList['doing']     = '#0BD986';
$lang->execution->statusColorList['suspended'] = '#fdc137';
$lang->execution->statusColorList['closed']    = '#838A9D';

if(!isset($lang->execution->gantt)) $lang->execution->gantt = new stdclass();
$lang->execution->gantt->progressColor[0] = '#B7B7B7';
$lang->execution->gantt->progressColor[1] = '#FF8287';
$lang->execution->gantt->progressColor[2] = '#FFC73A';
$lang->execution->gantt->progressColor[3] = '#6BD5F5';
$lang->execution->gantt->progressColor[4] = '#9DE88A';
$lang->execution->gantt->progressColor[5] = '#9BA8FF';

$lang->execution->gantt->color[0] = '#E7E7E7';
$lang->execution->gantt->color[1] = '#FFDADB';
$lang->execution->gantt->color[2] = '#FCECC1';
$lang->execution->gantt->color[3] = '#D3F3FD';
$lang->execution->gantt->color[4] = '#DFF5D9';
$lang->execution->gantt->color[5] = '#EBDCF9';

$lang->execution->gantt->textColor[0] = '#2D2D2D';
$lang->execution->gantt->textColor[1] = '#8D0308';
$lang->execution->gantt->textColor[2] = '#9D4200';
$lang->execution->gantt->textColor[3] = '#006D8E';
$lang->execution->gantt->textColor[4] = '#1A8100';
$lang->execution->gantt->textColor[5] = '#660ABC';

$lang->execution->gantt->stage = new stdclass();
$lang->execution->gantt->stage->progressColor = '#70B8FE';
$lang->execution->gantt->stage->color         = '#D2E7FC';
$lang->execution->gantt->stage->textColor     = '#0050A7';

$lang->execution->gantt->defaultColor         = '#EBDCF9';
$lang->execution->gantt->defaultProgressColor = '#9BA8FF';
$lang->execution->gantt->defaultTextColor     = '#660ABC';

$lang->execution->gantt->bar_height = '24';

$lang->execution->gantt->exportImg  = '導出圖片';
$lang->execution->gantt->exportPDF  = '導出 PDF';
$lang->execution->gantt->exporting  = '正在導出……';
$lang->execution->gantt->exportFail = '導出失敗。';

$lang->execution->boardColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#7FBB00', '#424BAC', '#66c5f8', '#EC2761');

$lang->execution->linkBranchStoryByPlanTips = "{$lang->execution->common}按計劃關聯需求時，只導入本{$lang->execution->common}所關聯%s的激活狀態的需求。";
$lang->execution->linkNormalStoryByPlanTips = "{$lang->execution->common}按計劃關聯需求時，只導入激活狀態的需求。";

$lang->execution->featureBar['dynamic']['all']       = '全部';
$lang->execution->featureBar['dynamic']['today']     = '今天';
$lang->execution->featureBar['dynamic']['yesterday'] = '昨天';
$lang->execution->featureBar['dynamic']['thisWeek']  = '本週';
$lang->execution->featureBar['dynamic']['lastWeek']  = '上周';
$lang->execution->featureBar['dynamic']['thisMonth'] = '本月';
$lang->execution->featureBar['dynamic']['lastMonth'] = '上月';

$lang->execution->featureBar['team']['all'] = '團隊成員';

$lang->execution->featureBar['managemembers']['all'] = '團隊管理';
