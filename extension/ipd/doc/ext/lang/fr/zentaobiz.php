<?php
global $app;
if(!empty($app->user->feedback) or !empty($_COOKIE['feedbackView']))
{
    unset($lang->doc->libTypeList['product']);
    unset($lang->doc->libTypeList['execution']);

    unset($lang->doc->aclList['custom']);
    $lang->doc->aclList['dept'] = 'Department';
    $lang->doc->customAB = $lang->doclib->all;
}

$lang->doc->bookName         = 'Book Name';
$lang->doc->editBook         = 'Edit Book';
$lang->doc->manageBook       = 'Manage Book';
$lang->doc->catalog          = 'Chapter';
$lang->doc->chapter          = 'Chapter';
$lang->doc->catalogAction    = 'Manage Chapter';
$lang->doc->wiki2export      = 'Export';
$lang->doc->mine2export      = "My Space Export";
$lang->doc->product2export   = "{$lang->productCommon} Space Export";
$lang->doc->execution2export = 'Export Execution Document';
$lang->doc->project2export   = "{$lang->projectCommon} Space Export";
$lang->doc->custom2export    = 'Team Space Export';
$lang->doc->editChapter      = 'Edit Chapter';
$lang->doc->bookBrowseTip    = 'Check the articles on the left column to read the details, or';
$lang->doc->feedbackBookTip  = 'Check the articles on the left column to read the details';
$lang->doc->addCatalogTip    = 'Current book is empyt, you colud';
$lang->doc->versionNotFound  = 'The version does not exist.';
$lang->doc->untitled         = 'Untitled';
$lang->doc->readUsers        = 'Read Only Users';
$lang->doc->readGroups       = 'Read Only Groups';
$lang->doc->users            = 'Editable Users';
$lang->doc->groups           = 'Editable Groups';

$lang->doc->noticeAcl['lib']['book'] = $lang->doc->noticeAcl['lib']['custom'];

$lang->doc->libTypeList['book'] = 'Book';

$lang->doc->libIconList['book'] = 'icon-book';

$lang->doclib->tabList['book'] = 'Book';

$lang->doclib->nameList['book']  = 'Book Name';

$lang->doclib->create['book'] = 'Create book';

$lang->book = new stdclass();

$lang->book->type = 'Type';
$lang->book->title = 'Title';
$lang->book->keywords = 'Tags';

$lang->book->typeList['chapter'] = 'Chapter';
$lang->book->typeList['article'] = 'Article';

$lang->doc->exportFiles = 'Export Files';
$lang->doc->zip         = 'Zip';

$lang->doc->exportFilesRanger = array();
$lang->doc->exportFilesRanger['all']      = 'All';
$lang->doc->exportFilesRanger['selected'] = 'Selected';
