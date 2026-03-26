<?php
global $app;
if(!empty($app->user->feedback) or !empty($_COOKIE['feedbackView']))
{
    unset($lang->doc->libTypeList['product']);
    unset($lang->doc->libTypeList['execution']);

    unset($lang->doc->aclList['custom']);
    $lang->doc->aclList['dept'] = '部门';
    $lang->doc->customAB = $lang->doclib->all;
}

$lang->doc->bookName         = '手册名称';
$lang->doc->editBook         = '编辑手册';
$lang->doc->manageBook       = '维护手册';
$lang->doc->catalog          = '章节';
$lang->doc->chapter          = '所属章节';
$lang->doc->catalogAction    = '管理章节';
$lang->doc->wiki2export      = 'Wiki导出';
$lang->doc->mine2export      = "我的空间导出";
$lang->doc->product2export   = "{$lang->productCommon}空间导出";
$lang->doc->execution2export = '导出执行文档';
$lang->doc->project2export   = "{$lang->projectCommon}空间导出";
$lang->doc->custom2export    = '团队空间导出';
$lang->doc->editChapter      = '编辑章节';
$lang->doc->bookBrowseTip    = '点击左侧章节文章树状图查看手册详情，您也可以';
$lang->doc->feedbackBookTip  = '点击左侧章节文章树状图查看手册详情';
$lang->doc->addCatalogTip    = '当前文档还没有章节，您可以';
$lang->doc->versionNotFound  = '版本不存在';
$lang->doc->untitled         = '未命名';
$lang->doc->readUsers        = '只读用户';
$lang->doc->readGroups       = '只读分组';
$lang->doc->users            = '可编辑用户';
$lang->doc->groups           = '可编辑分组';

$lang->doc->noticeAcl['lib']['book'] = $lang->doc->noticeAcl['lib']['custom'];

$lang->doc->libTypeList['book'] = '手册';

$lang->doc->libIconList['book'] = 'icon-book';

$lang->doclib->tabList['book'] = '手册';

$lang->doclib->nameList['book'] = '手册名称';

$lang->doclib->create['book'] = '创建手册';

$lang->book = new stdclass();

$lang->book->type = '类型';
$lang->book->title = '标题';
$lang->book->keywords = '关键字';

$lang->book->typeList['chapter'] = '章节';
$lang->book->typeList['article'] = '文章';

$lang->doc->exportFiles = '附件库导出';
$lang->doc->zip         = '压缩包';

$lang->doc->exportFilesRanger = array();
$lang->doc->exportFilesRanger['all']      = '全部附件';
$lang->doc->exportFilesRanger['selected'] = '选中附件';
