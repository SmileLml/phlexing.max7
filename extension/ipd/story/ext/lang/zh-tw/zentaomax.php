<?php
$lang->story->submit            = '提交評審';
$lang->story->reviewRange       = '評審範圍';
$lang->story->unlinkAB          = '移除';
$lang->story->affectedStories   = "影響的{$lang->SRCommon}";
$lang->story->link              = '關聯';
$lang->story->design            = '相關設計';
$lang->story->case              = '相關用例';
$lang->story->bug               = '相關缺陷';
$lang->story->repoFile          = '代碼檔案';
$lang->story->repoCommit        = '代碼提交';
$lang->story->requirementStatus = "{$lang->URCommon}狀態";
$lang->story->importToLib       = '導入需求庫';
$lang->story->batchImportToLib  = '批量導入需求庫';
$lang->story->lib               = '需求庫';
$lang->story->approver          = '審批人';
$lang->story->noCheckedItem     = '還沒有選中記錄！';
$lang->story->isExist           = '需求庫中已有此條記錄，請勿重複提交！';
$lang->story->import            = '導入Excel';

$lang->story->createDesign   = '設計';
$lang->story->meeting        = '所屬會議';
$lang->story->researchreport = '調研報告';

$lang->story->category = '類別';
$lang->story->categoryList[''] = '';
$lang->story->categoryList['performance'] = '性能';
$lang->story->categoryList['interface']   = '介面';

unset($lang->story->sourceList['other']);
$lang->story->sourceList['meeting']        = '會議';
$lang->story->sourceList['researchreport'] = '調研報告';
$lang->story->sourceList['other']          = '其他';

$lang->story->action->importfromstorylib = array('main' => '$date, 由 <strong>$actor</strong> 從需求庫 <strong>$extra</strong>導入。');
