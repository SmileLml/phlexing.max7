<?php
$lang->dividerMenu = ',qa,report,feedback,admin,';

$lang->mainNav->feedback      = "<i class='icon icon-feedback'></i> Feedback|feedback|admin|";
$lang->navGroup->feedback     = 'feedback';
$lang->navGroup->faq          = 'feedback';
$lang->mainNav->menuOrder[45] = 'feedback';

$lang->searchLang  = 'Tìm kiếm';

$lang->feedback = new stdclass();
$lang->feedback->menu = new stdclass();
$lang->feedback->menu->unclosed   = array('link' => 'Unclosed|feedback|admin|browseType=unclosed');
$lang->feedback->menu->all  = array('link' => 'All|feedback|admin|browseType=all');
$lang->feedback->menu->public  = array('link' => 'Public|feedback|admin|browseType=public');
$lang->feedback->menu->tostory = array('link' => 'Tới Story|feedback|admin|browseType=tostory');
$lang->feedback->menu->totask  = array('link' => 'Tới Task|feedback|admin|browseType=totask');
$lang->feedback->menu->tobug   = array('link' => 'Tới Bug|feedback|admin|browseType=tobug');
$lang->feedback->menu->totodo  = array('link' => 'Tới Todo|feedback|admin|browseType=totodo');
$lang->feedback->menu->review  = array('link' => 'Review|feedback|admin|browseType=review');
$lang->feedback->menu->assigntome = array('link' => 'AssignedToMe|feedback|admin|browseType=assigntome');
$lang->feedback->menu->bysearch   = array('link' => '<a href="javascript:;" class="querybox-toggle"><i class="icon-search icon"></i> ' . $lang->searchLang . '</a>');
$lang->feedback->menu->faq        = array('link' => 'FAQ|faq|browse', 'alias' => 'create');
$lang->feedback->menu->products   = array('link' => 'Phân quyền|feedback|products', 'alias' => 'manageproduct');

$lang->feedback->menuOrder[5]  = 'unclosed';
$lang->feedback->menuOrder[10] = 'all';
$lang->feedback->menuOrder[15] = 'public';
$lang->feedback->menuOrder[20] = 'tostory';
$lang->feedback->menuOrder[25] = 'totask';
$lang->feedback->menuOrder[30] = 'tobug';
$lang->feedback->menuOrder[35] = 'totodo';
$lang->feedback->menuOrder[40] = 'review';
$lang->feedback->menuOrder[45] = 'assigntome';
$lang->feedback->menuOrder[50] = 'bysearch';
$lang->feedback->menuOrder[55] = 'faq';
$lang->feedback->menuOrder[60] = 'products';

$lang->faq = new stdclass();
$lang->faq->menu   = $lang->feedback->menu;
$lang->faq->menuOrder = $lang->feedback->menuOrder;

$lang->feedbackView[0] = 'Giao diện phát triển';
$lang->feedbackView[1] = 'Giao diện phản hồi';

global $app;
if(!empty($_SESSION['user']->feedback) or !empty($_COOKIE['feedbackView']) and $app and $app->viewType == 'mhtml')
{
 $lang->feedback->menu = new stdclass();
 $lang->feedback->menu->unclosed = array('link' => 'Unclosed|feedback|browse|browseType=unclosed');
 $lang->feedback->menu->all   = array('link' => 'All|feedback|browse|browseType=all');
 $lang->feedback->menu->public   = array('link' => 'Public|feedback|browse|browseType=public');

 $lang->feedback->menuOrder = array();
 $lang->feedback->menuOrder[5]  = 'unclosed';
 $lang->feedback->menuOrder[10] = 'all';
 $lang->feedback->menuOrder[15] = 'public';
}

$lang->noMenuModule[] = 'faq';
$lang->noMenuModule[] = 'feedback';
$lang->noMenuModule[] = 'deploy';
$lang->noMenuModule[] = 'host';
$lang->noMenuModule[] = 'serverroom';
$lang->noMenuModule[] = 'service';
$lang->noMenuModule[] = 'ops';
