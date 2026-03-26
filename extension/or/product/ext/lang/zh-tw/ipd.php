<?php
$lang->product->PMT            = '規劃負責人';
$lang->product->storySummary   = "本頁共 <strong>%s</strong> 個%s";

$lang->product->statusList['wait'] = '待立項';

$lang->product->aclList['private'] = "私有({$lang->productCommon}相關負責人、評審人、所屬項目集的負責人及干係人、相關聯{$lang->projectCommon}的團隊成員和干係人可訪問)";

$lang->product->featureBar['all'] = array();
$lang->product->featureBar['all']['all']    = '全部';
$lang->product->featureBar['all']['mine']   = '我負責';
$lang->product->featureBar['all']['wait']   = '待立項';
$lang->product->featureBar['all']['normal'] = '正常';
$lang->product->featureBar['all']['closed'] = '結束';

$lang->product->featureBar['browse'] = array();
$lang->product->featureBar['browse']['allstory']       = '全部';
$lang->product->featureBar['browse']['unclosed']       = $lang->product->unclosed;
$lang->product->featureBar['browse']['assignedtome']   = $lang->product->assignedToMe;
$lang->product->featureBar['browse']['draftstory']     = $lang->product->draftStory;
$lang->product->featureBar['browse']['reviewingstory'] = '評審中';
$lang->product->featureBar['browse']['changingstory']  = '變更中';
$lang->product->featureBar['browse']['launchedstory']  = '已立項';
$lang->product->featureBar['browse']['more']           = $lang->more;

$lang->product->moreSelects['browse'] = array();
$lang->product->moreSelects['browse']['more']['developingstory'] = '研發中';
$lang->product->moreSelects['browse']['more']['willclose']       = '待關閉';
$lang->product->moreSelects['browse']['more']['closedstory']     = '已關閉';

$lang->product->waitedRoadmaps    = '待立項路標';
$lang->product->launchedRoadmaps  = '已立項路標';
$lang->product->draftStories      = '草稿用戶需求';
$lang->product->activeStories     = '激活用戶需求';
$lang->product->launchedStories   = '已立項用戶需求';
$lang->product->developingStories = '研發中用戶需求';

$lang->product->requirementSummary = "本頁共 <strong>%s</strong> 個{$lang->URCommon}，預計 <strong>%s</strong> 個{$lang->hourCommon}。";
$lang->product->checkedURSummary   = "選中 <strong>%total%</strong> 個{$lang->URCommon}，預計 <strong>%estimate%</strong> 個{$lang->hourCommon}。";
