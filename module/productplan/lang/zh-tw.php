<?php
/**
 * The productplan module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: zh-tw.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->productplan->common     = $lang->productCommon . '計劃';
$lang->productplan->browse     = "計劃列表";
$lang->productplan->index      = "計劃列表";
$lang->productplan->create     = "創建計劃";
$lang->productplan->edit       = "編輯計劃";
$lang->productplan->delete     = "刪除計劃";
$lang->productplan->start      = "開始計劃";
$lang->productplan->finish     = "完成計劃";
$lang->productplan->close      = "關閉計劃";
$lang->productplan->activate   = "激活計劃";
$lang->productplan->startAB    = "開始";
$lang->productplan->finishAB   = "完成";
$lang->productplan->closeAB    = "關閉";
$lang->productplan->activateAB = "激活";
$lang->productplan->view       = "計劃詳情";
$lang->productplan->bugSummary = "本頁共 <strong>%s</strong> 個Bug";
$lang->productplan->basicInfo  = '基本信息';
$lang->productplan->batchEdit  = '批量編輯';
$lang->productplan->project    = $lang->projectCommon;
$lang->productplan->plan       = '計劃';
$lang->productplan->allAB      = '所有';
$lang->productplan->to         = '至';
$lang->productplan->more       = '更多操作';
$lang->productplan->comment    = '備註';
$lang->productplan->storyPoint = '故事點';

$lang->productplan->batchEditAction   = '批量編輯計劃';
$lang->productplan->batchUnlink       = "批量移除";
$lang->productplan->batchClose        = "批量關閉";
$lang->productplan->batchChangeStatus = "批量修改狀態";
$lang->productplan->unlinkAB          = "移除";
$lang->productplan->linkStory         = "關聯需求";
$lang->productplan->unlinkStory       = "移除需求";
$lang->productplan->unlinkStoryAB     = "移除";
$lang->productplan->batchUnlinkStory  = "批量移除需求";
$lang->productplan->linkedStories     = '需求';
$lang->productplan->unlinkedStories   = "未關聯需求";
$lang->productplan->updateOrder       = '排序';
$lang->productplan->createChildren    = "創建子計劃";
$lang->productplan->createExecution   = "創建{$lang->execution->common}";
$lang->productplan->list              = '列表';
$lang->productplan->kanban            = '看板';

$lang->productplan->linkBug          = "關聯Bug";
$lang->productplan->unlinkBug        = "移除Bug";
$lang->productplan->batchUnlinkBug   = "批量移除Bug";
$lang->productplan->linkedBugs       = 'Bug';
$lang->productplan->unlinkedBugs     = '未關聯Bug';
$lang->productplan->unexpired        = "未過期";
$lang->productplan->noAssigned       = '未指派';
$lang->productplan->all              = "所有計劃";
$lang->productplan->setDate          = "設置計划起止時間";
$lang->productplan->expired          = "已過期";
$lang->productplan->closedReason     = "關閉原因";

$lang->productplan->confirmDelete      = "您確認刪除該計劃嗎？";
$lang->productplan->confirmUnlinkStory = "您確認移除該需求嗎？";
$lang->productplan->confirmUnlinkBug   = "您確認移除該Bug嗎？";
$lang->productplan->confirmStart       = "您確認開始該計劃嗎？";
$lang->productplan->confirmFinish      = "您確認完成該計劃嗎？";
$lang->productplan->confirmClose       = "您確認關閉該計劃嗎？";
$lang->productplan->confirmActivate    = "您確認激活該計劃嗎？";
$lang->productplan->noPlan             = "暫時沒有計劃。";
$lang->productplan->cannotDeleteParent = "不能刪除父計劃";
$lang->productplan->selectProjects     = "請選擇所屬" . $lang->projectCommon;
$lang->productplan->projectNotEmpty    = "所屬{$lang->projectCommon}不能為空。";
$lang->productplan->nextStep           = "下一步";
$lang->productplan->summary            = "本頁共 <strong>%s</strong> 個計劃，父計劃 <strong>%s</strong>，子計劃 <strong>%s</strong>，獨立計劃 <strong>%s</strong>。";
$lang->productplan->checkedSummary     = "共選中 <strong>%total%</strong> 個計劃，父計劃 <strong>%parent%</strong>，子計劃 <strong>%child%</strong>，獨立計劃 <strong>%independent%</strong>。";
$lang->productplan->storySummary       = "本頁共 <strong>%s</strong> 個{$lang->ERCommon}，<strong>%s</strong> 個{$lang->URCommon}， <strong>%s</strong> 個{$lang->SRCommon}，預計 <strong>%s</strong> 個{$lang->hourCommon}， 用例覆蓋率 <strong>%s</strong> 。";
$lang->productplan->confirmChangePlan  = "分支『%s』解除關聯後，分支下的%s個需求和%s個Bug將同步從計劃中移除，是否解除？";
$lang->productplan->confirmRemoveStory = "分支『%s』解除關聯後，分支下的%s個需求將同步從計劃中移除，是否解除？";
$lang->productplan->confirmRemoveBug   = "分支『%s』解除關聯後，分支下的%s個Bug將同步從計劃中移除，是否解除？";

$lang->productplan->id          = '編號';
$lang->productplan->product     = '所屬' . $lang->productCommon;
$lang->productplan->branch      = '平台/分支';
$lang->productplan->title       = '計劃名稱';
$lang->productplan->desc        = '描述';
$lang->productplan->begin       = '開始日期';
$lang->productplan->end         = '結束日期';
$lang->productplan->status      = '狀態';
$lang->productplan->last        = "上次計劃";
$lang->productplan->future      = '待定';
$lang->productplan->stories     = "需求數";
$lang->productplan->bugs        = 'Bug數';
$lang->productplan->hour        = $lang->hourCommon;
$lang->productplan->execution   = $lang->execution->common;
$lang->productplan->parent      = "父計劃";
$lang->productplan->parentAB    = "父";
$lang->productplan->children    = "子計劃";
$lang->productplan->childrenAB  = "子";
$lang->productplan->order       = "排序";
$lang->productplan->deleted     = "已刪除";
$lang->productplan->mailto      = "抄送給";
$lang->productplan->planStatus  = "狀態";
$lang->productplan->storyTitle  = "需求名稱";
$lang->productplan->createdBy   = '由誰創建';
$lang->productplan->createdDate = '創建時間';

$lang->productplan->statusList['wait']   = '未開始';
$lang->productplan->statusList['doing']  = '進行中';
$lang->productplan->statusList['done']   = '已完成';
$lang->productplan->statusList['closed'] = '已關閉';

$lang->productplan->closedReasonList['done']   = '已完成';
$lang->productplan->closedReasonList['cancel'] = '已取消';

$lang->productplan->parentActionList['startedbychild']   = '系統判斷由於子計劃 <strong>開始</strong> ，將計劃狀態置為 <strong>進行中</strong> 。';
$lang->productplan->parentActionList['finishedbychild']  = '系統判斷由於子計劃 <strong>全部完成</strong> ，將計劃狀態置為 <strong>已完成</strong> 。';
$lang->productplan->parentActionList['closedbychild']    = '系統判斷由於子計劃 <strong>全部關閉</strong> ，將計劃狀態置為 <strong>已關閉</strong> 。';
$lang->productplan->parentActionList['activatedbychild'] = '系統判斷由於子計劃 <strong>激活</strong> ，將計劃狀態置為 <strong>進行中</strong> 。';
$lang->productplan->parentActionList['createchild']      = '系統判斷由於 <strong>創建</strong> 子計劃 ，將計劃狀態置為 <strong>進行中</strong> 。';

$lang->productplan->endList[7]   = '一星期';
$lang->productplan->endList[14]  = '兩星期';
$lang->productplan->endList[31]  = '一個月';
$lang->productplan->endList[62]  = '兩個月';
$lang->productplan->endList[93]  = '三個月';
$lang->productplan->endList[186] = '半年';
$lang->productplan->endList[365] = '一年';

$lang->productplan->errorNoTitle           = 'ID %s 標題不能為空';
$lang->productplan->errorNoBegin           = 'ID %s 開始時間不能為空';
$lang->productplan->errorNoEnd             = 'ID %s 結束時間不能為空';
$lang->productplan->beginGeEnd             = 'ID %s 開始時間不能大於結束時間';
$lang->productplan->beginLessThanParent    = "父計劃的開始日期：%s，開始日期不能小於父計劃的開始日期";
$lang->productplan->endGreatThanParent     = "父計劃的結束日期：%s，結束日期不能大於父計劃的結束日期";
$lang->productplan->beginGreaterChild      = "子計劃的開始日期：%s，開始日期不能大於子計劃的開始日期";
$lang->productplan->endLessThanChild       = "子計劃的結束日期：%s，結束日期不能小於子計劃的結束日期";
$lang->productplan->noLinkedProject        = "當前{$lang->productCommon}還未關聯{$lang->projectCommon}，請進入{$lang->productCommon}的{$lang->projectCommon}列表關聯或創建一個{$lang->projectCommon}";
$lang->productplan->enterProjectList       = "進入{$lang->productCommon}的{$lang->projectCommon}列表";
$lang->productplan->beginGreaterChildTip   = "父計劃[%s]的開始日期：%s，不能大於子計劃的開始日期: %s";
$lang->productplan->endLessThanChildTip    = "父計劃[%s]的結束日期：%s，不能小於子計劃的結束日期: %s";
$lang->productplan->beginLessThanParentTip = "子計劃[%s]的開始日期：%s，不能小於父計劃的開始日期: %s";
$lang->productplan->endGreatThanParentTip  = "子計劃[%s]的結束日期：%s，不能大於父計劃的結束日期: %s";
$lang->productplan->diffBranchesTip        = "父計劃的@branch@『%s』未被子計劃關聯，對應@branch@的需求和bug將自動從計劃中移除，是否保存？";
$lang->productplan->deleteBranchTip        = "@branch@『%s』被子計劃關聯，無法修改。";

$lang->productplan->featureBar['browse']['all']    = '全部';
$lang->productplan->featureBar['browse']['undone'] = '未完成';
$lang->productplan->featureBar['browse']['wait']   = '未開始';
$lang->productplan->featureBar['browse']['doing']  = '進行中';
$lang->productplan->featureBar['browse']['done']   = '已完成';
$lang->productplan->featureBar['browse']['closed'] = '已關閉';

$lang->productplan->orderList['begin_desc'] = '計劃開始時間倒序';
$lang->productplan->orderList['begin_asc']  = '計劃開始時間正序';
$lang->productplan->orderList['title_desc'] = '計劃名稱倒序';
$lang->productplan->orderList['title_asc']  = '計劃名稱正序';

$lang->productplan->action = new stdclass();
$lang->productplan->action->changebychild = array('main' => '$date, $extra', 'extra' => 'parentActionList');
