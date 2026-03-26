<?php
$lang->roadmap = new stdclass();
$lang->roadmap->common = '路標';

$lang->roadmap->linkURAB      = '關聯';
$lang->roadmap->linkUR        = "關聯需求";
$lang->roadmap->storyName     = "需求名稱";
$lang->roadmap->activate      = '激活';
$lang->roadmap->name          = '路標名稱';
$lang->roadmap->begin         = '計劃開始';
$lang->roadmap->end           = '計劃結束';
$lang->roadmap->status        = '路標狀態';
$lang->roadmap->desc          = '描述';
$lang->roadmap->future        = '待定';
$lang->roadmap->view          = '路標詳情';
$lang->roadmap->delete        = '刪除';
$lang->roadmap->basicInfo     = '基本信息';
$lang->roadmap->updateOrder   = '排序';
$lang->roadmap->linkedURS     = '需求';
$lang->roadmap->unlinkUR      = "移除需求";
$lang->roadmap->unlinkURAB    = '移除';
$lang->roadmap->unlinkedUR    = "未關聯需求";
$lang->roadmap->batchUnlinkUR = "批量移除需求";
$lang->roadmap->createdBy     = '由誰創建';
$lang->roadmap->createdDate   = '創建日期';
$lang->roadmap->closedBy      = '由誰關閉';
$lang->roadmap->closedDate    = '關閉日期';
$lang->roadmap->closedReason  = '關閉原因';
$lang->roadmap->unlinkReason  = '移除原因';

$lang->roadmap->browse = '路標甘特圖';
$lang->roadmap->create = '創建路標';
$lang->roadmap->edit   = '編輯路標';
$lang->roadmap->close  = '關閉路標';

$lang->roadmap->branch      = '所屬%s';
$lang->roadmap->requirment  = '用戶需求';
$lang->roadmap->requirments = '需求數量';
$lang->roadmap->actions     = '操作';

$lang->roadmap->statusList['wait']      = '待立項';
$lang->roadmap->statusList['launching'] = '立項中';
$lang->roadmap->statusList['launched']  = '已立項';
$lang->roadmap->statusList['reject']    = '未通過';
$lang->roadmap->statusList['closed']    = '已關閉';

$lang->roadmap->confirmDelete              = '您確認刪除該路標嗎？';
$lang->roadmap->confirmActivate            = '您確定要激活該路標嗎？';
$lang->roadmap->confirmUnlinkStory         = "您確認移除該需求嗎？";
$lang->roadmap->confirmBatchUnlinkStory    = "您確定要批量移除這些需求嗎？";
$lang->roadmap->confirmUnlinkLaunchedStory = "該需求已立項，您確定要從路標中移除嗎？";

$lang->roadmap->changeBranchTips   = '路標調整分支後，之前所關聯分支的需求與調整後分支有衝突的需求將會從路標中移除，請確認是否繼續修改路標的所屬分支。';
$lang->roadmap->changeRoadmapTips  = '該路標為%s狀態，不能調整所屬路標。';
$lang->roadmap->batchUnlinkURSTips = '該路標為%s狀態，不能移除需求。';
$lang->roadmap->failedRemoveTip    = '僅移除了處于未開始、已設路標、Charter立項階段的需求，其他階段的需求無法移除。';
$lang->roadmap->deleteRoadmapTips  = '該路標為%s狀態，不能刪除。';
$lang->roadmap->unlinkReasonTips   = '執行移除操作後，該需求將取消立項，狀態變更為激活，IPD研發界面將無法查看到該需求。';
$lang->roadmap->checkedSummary     = "選中 <strong>%total%</strong> 個需求，預計 <strong>%estimate%</strong> 個{$lang->hourCommon}。";

$lang->roadmap->zooming['month']   = '月';
$lang->roadmap->zooming['quarter'] = '季';
$lang->roadmap->zooming['year']    = '年';

$lang->roadmap->action = new stdclass();
$lang->roadmap->action->linkur   = '$date, 由 <strong>$actor</strong> 關聯需求 <strong>$extra</strong>。' . "\n";
$lang->roadmap->action->unlinkur = '$date, 由 <strong>$actor</strong> 從路標移除需求 <strong>$extra</strong>。' . "\n";
$lang->roadmap->action->changedbycharter = array('main' => '$date, 由於立項 <strong>$extra</strong> 被刪除，路標狀態調整為待立項。');
$lang->roadmap->action->linked2charter   = array('main' => '$date, 由 <strong>$actor</strong> 關聯到立項 <strong>$extra</strong>。');

$lang->roadmap->beginGtEnd = '計劃開始日期不能大於計劃完成日期';

$lang->roadmap->reasonList['']         = '';
$lang->roadmap->reasonList['done']     = '已完成';
$lang->roadmap->reasonList['canceled'] = '已取消';

$lang->roadmap->unlinkReasonList['omit']  = '不做';
$lang->roadmap->unlinkReasonList['other'] = '其他';
