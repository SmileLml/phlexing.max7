<?php
$lang->roadmap = new stdclass();
$lang->roadmap->common = '路标';

$lang->roadmap->linkURAB      = '关联';
$lang->roadmap->linkUR        = "关联需求";
$lang->roadmap->storyName     = "需求名称";
$lang->roadmap->activate      = '激活';
$lang->roadmap->name          = '路标名称';
$lang->roadmap->begin         = '计划开始';
$lang->roadmap->end           = '计划结束';
$lang->roadmap->status        = '路标状态';
$lang->roadmap->desc          = '描述';
$lang->roadmap->future        = '待定';
$lang->roadmap->view          = '路标详情';
$lang->roadmap->delete        = '删除';
$lang->roadmap->basicInfo     = '基本信息';
$lang->roadmap->updateOrder   = '排序';
$lang->roadmap->linkedURS     = '需求';
$lang->roadmap->unlinkUR      = "移除需求";
$lang->roadmap->unlinkURAB    = '移除';
$lang->roadmap->unlinkedUR    = "未关联需求";
$lang->roadmap->batchUnlinkUR = "批量移除需求";
$lang->roadmap->createdBy     = '由谁创建';
$lang->roadmap->createdDate   = '创建日期';
$lang->roadmap->closedBy      = '由谁关闭';
$lang->roadmap->closedDate    = '关闭日期';
$lang->roadmap->closedReason  = '关闭原因';
$lang->roadmap->unlinkReason  = '移除原因';

$lang->roadmap->browse = '路标甘特图';
$lang->roadmap->create = '创建路标';
$lang->roadmap->edit   = '编辑路标';
$lang->roadmap->close  = '关闭路标';

$lang->roadmap->branch      = '所属%s';
$lang->roadmap->requirment  = '用户需求';
$lang->roadmap->requirments = '需求数量';
$lang->roadmap->actions     = '操作';

$lang->roadmap->statusList['wait']      = '待立项';
$lang->roadmap->statusList['launching'] = '立项中';
$lang->roadmap->statusList['launched']  = '已立项';
$lang->roadmap->statusList['reject']    = '未通过';
$lang->roadmap->statusList['closed']    = '已关闭';

$lang->roadmap->confirmDelete              = '您确认删除该路标吗？';
$lang->roadmap->confirmActivate            = '您确定要激活该路标吗？';
$lang->roadmap->confirmUnlinkStory         = "您确认移除该需求吗？";
$lang->roadmap->confirmBatchUnlinkStory    = "您确定要批量移除这些需求吗？";
$lang->roadmap->confirmUnlinkLaunchedStory = "该需求已立项，您确定要从路标中移除吗？";

$lang->roadmap->changeBranchTips   = '路标调整分支后，之前所关联分支的需求与调整后分支有冲突的需求将会从路标中移除，请确认是否继续修改路标的所属分支。';
$lang->roadmap->changeRoadmapTips  = '该路标为%s状态，不能调整所属路标。';
$lang->roadmap->batchUnlinkURSTips = '该路标为%s状态，不能移除需求。';
$lang->roadmap->failedRemoveTip    = '仅移除了处于未开始、已设路标、Charter立项阶段的需求，其他阶段的需求无法移除。';
$lang->roadmap->deleteRoadmapTips  = '该路标为%s状态，不能删除。';
$lang->roadmap->unlinkReasonTips   = '执行移除操作后，该需求将取消立项，状态变更为激活，IPD研发界面将无法查看到该需求。';
$lang->roadmap->checkedSummary     = "选中 <strong>%total%</strong> 个需求，预计 <strong>%estimate%</strong> 个{$lang->hourCommon}。";

$lang->roadmap->zooming['month']   = '月';
$lang->roadmap->zooming['quarter'] = '季';
$lang->roadmap->zooming['year']    = '年';

$lang->roadmap->action = new stdclass();
$lang->roadmap->action->linkur   = '$date, 由 <strong>$actor</strong> 关联需求 <strong>$extra</strong>。' . "\n";
$lang->roadmap->action->unlinkur = '$date, 由 <strong>$actor</strong> 从路标移除需求 <strong>$extra</strong>。' . "\n";
$lang->roadmap->action->changedbycharter = array('main' => '$date, 由于立项 <strong>$extra</strong> 被删除，路标状态调整为待立项。');
$lang->roadmap->action->linked2charter   = array('main' => '$date, 由 <strong>$actor</strong> 关联到立项 <strong>$extra</strong>。');

$lang->roadmap->beginGtEnd = '计划开始日期不能大于计划完成日期';

$lang->roadmap->reasonList['']         = '';
$lang->roadmap->reasonList['done']     = '已完成';
$lang->roadmap->reasonList['canceled'] = '已取消';

$lang->roadmap->unlinkReasonList['omit']  = '不做';
$lang->roadmap->unlinkReasonList['other'] = '其他';
