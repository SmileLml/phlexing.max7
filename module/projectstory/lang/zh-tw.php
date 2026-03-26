<?php
/* Field. */
$lang->projectstory->project = "{$lang->projectCommon}ID";
$lang->projectstory->product = "{$lang->productCommon}ID";
$lang->projectstory->story   = "需求ID";
$lang->projectstory->version = "版本";
$lang->projectstory->order   = "排序";

$lang->projectstory->storyCommon = $lang->projectCommon . '需求';
$lang->projectstory->storyList   = $lang->projectCommon . '需求列表';
$lang->projectstory->storyView   = $lang->projectCommon . '需求詳情';

$lang->projectstory->common            = "{$lang->projectCommon}需求";
$lang->projectstory->index             = "需求主頁";
$lang->projectstory->view              = "需求詳情";
$lang->projectstory->story             = "需求列表";
$lang->projectstory->track             = '矩陣';
$lang->projectstory->linkStory         = '關聯需求';
$lang->projectstory->unlinkStory       = '移除需求';
$lang->projectstory->report            = '統計報表';
$lang->projectstory->export            = '導出需求';
$lang->projectstory->batchReview       = '批量評審需求';
$lang->projectstory->batchClose        = '批量關閉需求';
$lang->projectstory->batchChangePlan   = '批量修改計劃';
$lang->projectstory->batchAssignTo     = '批量指派需求';
$lang->projectstory->batchEdit         = '批量編輯需求';
$lang->projectstory->importToLib       = '導入需求庫';
$lang->projectstory->batchImportToLib  = '批量導入需求庫';
$lang->projectstory->importCase        = '導入需求';
$lang->projectstory->exportTemplate    = '導出模板';
$lang->projectstory->batchUnlinkStory  = '批量移除需求';
$lang->projectstory->importplanstories = '按計劃關聯需求';
$lang->projectstory->trackAction       = '跟蹤矩陣';
$lang->projectstory->confirm           = '確定';

/* Notice. */
$lang->projectstory->whyNoStories   = "看起來沒有需求可以關聯。請檢查下{$lang->projectCommon}關聯的{$lang->productCommon}中有沒有需求，而且要確保它們已經審核通過。";
$lang->projectstory->batchUnlinkTip = "其他需求已經移除，如下需求已與該{$lang->projectCommon}下執行相關聯，請從執行中移除後再操作。";

$lang->projectstory->featureBar['story']['allstory']  = '全部';
$lang->projectstory->featureBar['story']['unclosed']  = '未關閉';
$lang->projectstory->featureBar['story']['draft']     = '草稿';
$lang->projectstory->featureBar['story']['reviewing'] = '評審中';
$lang->projectstory->featureBar['story']['changing']  = '變更中';
$lang->projectstory->featureBar['story']['more']      = $lang->more;

$lang->projectstory->moreSelects['story']['more']['closed']            = '已關閉';
$lang->projectstory->moreSelects['story']['more']['linkedexecution']   = '已關聯' . $lang->execution->common;
$lang->projectstory->moreSelects['story']['more']['unlinkedexecution'] = '未關聯' . $lang->execution->common;
