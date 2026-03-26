<?php
$lang->story->submit            = '提交评审';
$lang->story->reviewRange       = '评审范围';
$lang->story->unlinkAB          = '移除';
$lang->story->affectedStories   = "影响的{$lang->SRCommon}";
$lang->story->link              = '关联';
$lang->story->design            = '相关设计';
$lang->story->case              = '相关用例';
$lang->story->bug               = '相关缺陷';
$lang->story->repoFile          = '代码文件';
$lang->story->repoCommit        = '代码提交';
$lang->story->requirementStatus = "{$lang->URCommon}状态";
$lang->story->importToLib       = '导入需求库';
$lang->story->batchImportToLib  = '批量导入需求库';
$lang->story->lib               = '需求库';
$lang->story->approver          = '审批人';
$lang->story->noCheckedItem     = '还没有选中记录！';
$lang->story->isExist           = '需求库中已有此条记录，请勿重复提交！';
$lang->story->import            = '导入Excel';

$lang->story->createDesign   = '设计';
$lang->story->meeting        = '所属会议';
$lang->story->researchreport = '调研报告';

$lang->story->category = '类别';
$lang->story->categoryList[''] = '';
$lang->story->categoryList['performance'] = '性能';
$lang->story->categoryList['interface']   = '接口';

unset($lang->story->sourceList['other']);
$lang->story->sourceList['meeting']        = '会议';
$lang->story->sourceList['researchreport'] = '调研报告';
$lang->story->sourceList['other']          = '其他';

$lang->story->action->importfromstorylib = array('main' => '$date, 由 <strong>$actor</strong> 从需求库 <strong>$extra</strong>导入。');
