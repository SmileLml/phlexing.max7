<?php
$lang->product->PMT            = '规划负责人';
$lang->product->storySummary   = "本页共 <strong>%s</strong> 个%s";

$lang->product->statusList['wait'] = '待立项';

$lang->product->aclList['private'] = "私有({$lang->productCommon}相关负责人、评审人、所属项目集的负责人及干系人、相关联{$lang->projectCommon}的团队成员和干系人可访问)";

$lang->product->featureBar['all'] = array();
$lang->product->featureBar['all']['all']    = '全部';
$lang->product->featureBar['all']['mine']   = '我负责';
$lang->product->featureBar['all']['wait']   = '待立项';
$lang->product->featureBar['all']['normal'] = '正常';
$lang->product->featureBar['all']['closed'] = '结束';

$lang->product->featureBar['browse'] = array();
$lang->product->featureBar['browse']['allstory']       = '全部';
$lang->product->featureBar['browse']['unclosed']       = $lang->product->unclosed;
$lang->product->featureBar['browse']['assignedtome']   = $lang->product->assignedToMe;
$lang->product->featureBar['browse']['draftstory']     = $lang->product->draftStory;
$lang->product->featureBar['browse']['reviewingstory'] = '评审中';
$lang->product->featureBar['browse']['changingstory']  = '变更中';
$lang->product->featureBar['browse']['launchedstory']  = '已立项';
$lang->product->featureBar['browse']['more']           = $lang->more;

$lang->product->moreSelects['browse'] = array();
$lang->product->moreSelects['browse']['more']['developingstory'] = '研发中';
$lang->product->moreSelects['browse']['more']['willclose']       = '待关闭';
$lang->product->moreSelects['browse']['more']['closedstory']     = '已关闭';

$lang->product->waitedRoadmaps    = '待立项路标';
$lang->product->launchedRoadmaps  = '已立项路标';
$lang->product->draftStories      = '草稿用户需求';
$lang->product->activeStories     = '激活用户需求';
$lang->product->launchedStories   = '已立项用户需求';
$lang->product->developingStories = '研发中用户需求';

$lang->product->requirementSummary = "本页共 <strong>%s</strong> 个{$lang->URCommon}，预计 <strong>%s</strong> 个{$lang->hourCommon}。";
$lang->product->checkedURSummary   = "选中 <strong>%total%</strong> 个{$lang->URCommon}，预计 <strong>%estimate%</strong> 个{$lang->hourCommon}。";
