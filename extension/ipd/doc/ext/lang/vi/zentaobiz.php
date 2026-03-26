<?php
global $app;
if(!empty($app->user->feedback) or !empty($_COOKIE['feedbackView']))
{
 unset($lang->doc->libTypeList['product']);
 unset($lang->doc->libTypeList['execution']);

 unset($lang->doc->aclList['custom']);
 $lang->doc->aclList['dept'] = 'Phòng/Ban';
 $lang->doc->customAB = $lang->doclib->all;
}

$lang->doc->bookName        = 'Tên sách';
$lang->doc->editBook        = 'Sửa sách';
$lang->doc->manageBook      = 'Quản lý sách';
$lang->doc->catalog         = 'Chương';
$lang->doc->chapter         = 'Chương';
$lang->doc->catalogAction   = 'Quản lý chương';
$lang->doc->editChapter     = 'Sửa chương';
$lang->doc->bookBrowseTip   = 'Kiểm tra bài viết trên cột trái để xem chi tiết, hoặc';
$lang->doc->feedbackBookTip = 'Kiểm tra bài viết trên cột trái để xem chi tiết';
$lang->doc->addCatalogTip   = 'Sách hiện tại trống, bạn có thể ';

$lang->doc->noticeAcl['lib']['book'] = $lang->doc->noticeAcl['lib']['custom'];

$lang->doc->libTypeList['book'] = 'Sách';

$lang->doc->libIconList['book'] = 'icon-book';

$lang->doclib->tabList['book'] = 'Sách';

$lang->doclib->nameList['book']  = 'Tên sách';

$lang->doclib->create['book'] = 'Tạo sách';

$lang->book = new stdclass();

$lang->book->type = 'Loại';
$lang->book->title = 'Tiêu đề';
$lang->book->keywords = 'Tags';

$lang->book->typeList['chapter'] = 'Chương';
$lang->book->typeList['article'] = 'Bài';
