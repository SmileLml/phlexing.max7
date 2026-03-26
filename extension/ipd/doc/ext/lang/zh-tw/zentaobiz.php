<?php
global $app;
if(!empty($app->user->feedback) or !empty($_COOKIE['feedbackView']))
{
    unset($lang->doc->libTypeList['product']);
    unset($lang->doc->libTypeList['execution']);

    unset($lang->doc->aclList['custom']);
    $lang->doc->aclList['dept'] = '部門';
    $lang->doc->customAB = $lang->doclib->all;
}

$lang->doc->bookName         = '手冊名稱';
$lang->doc->editBook         = '編輯手冊';
$lang->doc->manageBook       = '維護手冊';
$lang->doc->catalog          = '章節';
$lang->doc->chapter          = '所屬章節';
$lang->doc->catalogAction    = '管理章節';
$lang->doc->wiki2export      = 'Wiki導出';
$lang->doc->mine2export      = "我的空間導出";
$lang->doc->product2export   = "{$lang->productCommon}空間導出";
$lang->doc->execution2export = '導出執行文檔';
$lang->doc->project2export   = "{$lang->projectCommon}空間導出";
$lang->doc->custom2export    = '團隊空間導出';
$lang->doc->editChapter      = '編輯章節';
$lang->doc->bookBrowseTip    = '點擊左側章節文章樹狀圖查看手冊詳情，您也可以';
$lang->doc->feedbackBookTip  = '點擊左側章節文章樹狀圖查看手冊詳情';
$lang->doc->addCatalogTip    = '當前文檔還沒有章節，您可以';
$lang->doc->versionNotFound  = '版本不存在';
$lang->doc->untitled         = '未命名';
$lang->doc->readUsers        = '只讀用戶';
$lang->doc->readGroups       = '只讀分組';
$lang->doc->users            = '可編輯用戶';
$lang->doc->groups           = '可編輯分組';

$lang->doc->noticeAcl['lib']['book'] = $lang->doc->noticeAcl['lib']['custom'];

$lang->doc->libTypeList['book'] = '手冊';

$lang->doc->libIconList['book'] = 'icon-book';

$lang->doclib->tabList['book'] = '手冊';

$lang->doclib->nameList['book'] = '手冊名稱';

$lang->doclib->create['book'] = '創建手冊';

$lang->book = new stdclass();

$lang->book->type = '類型';
$lang->book->title = '標題';
$lang->book->keywords = '關鍵字';

$lang->book->typeList['chapter'] = '章節';
$lang->book->typeList['article'] = '文章';

$lang->doc->exportFiles = '附件庫導出';
$lang->doc->zip         = '壓縮包';

$lang->doc->exportFilesRanger = array();
$lang->doc->exportFilesRanger['all']      = '全部附件';
$lang->doc->exportFilesRanger['selected'] = '選中附件';
