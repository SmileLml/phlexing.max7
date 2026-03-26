<?php
$lang->navIcons['feedback']     = "<i class='icon icon-feedback'></i>";
$lang->navIconNames['feedback'] = 'feedback';

$lang->feedback     = new stdclass();
$lang->feedbackpriv = new stdclass();

$lang->feedback->common     = '反饋';
$lang->feedbackpriv->common = '反饋權限';

$lang->mainNav->feedback      = "{$lang->navIcons['feedback']} {$lang->feedback->common}|feedback|admin|browseType=wait";
$lang->navGroup->feedback     = 'feedback';
$lang->navGroup->faq          = 'feedback';
$lang->navGroup->ticket       = 'feedback';
$lang->navGroup->feedbackpriv = 'feedback';

$lang->searchLang = '搜索';

$lang->feedback->menu = new stdclass();
$lang->feedback->menu->browse   = array('link' => '反饋|feedback|admin|browseType=wait', 'alias' => 'create,batchcreate,edit,view,adminview,batchedit,browse,showimport,batchclose,tostory,touserstory,toepic,totodo,todemand');
$lang->feedback->menu->ticket   = array('link' => '工單|ticket|browse', 'alias' => 'create,edit,view,batchedit,browse,showimport,createstory,createbug');
$lang->feedback->menu->faq      = array('link' => 'FAQ|faq|browse', 'alias' => 'create,edit');
$lang->feedback->menu->products = array('link' => '設置|feedback|products', 'alias' => 'manageproduct');

$lang->feedback->menuOrder[5]  = 'browse';
$lang->feedback->menuOrder[10] = 'ticket';
$lang->feedback->menuOrder[15] = 'faq';
$lang->feedback->menuOrder[20] = 'products';

$lang->feedback->SRCommon = $lang->SRCommon;
$lang->feedback->URCommon = $lang->URCommon;

$lang->ticket = new stdclass();
$lang->ticket->common = '工單';
$lang->ticket->navGroup['ticket'] = 'feedback';

$lang->faq = new stdclass();
$lang->faq->navGroup['faq'] = 'feedback';

$lang->my->menu->work['subMenu']->feedback = array('link' => "{$lang->feedback->common}|my|work|mode=feedback&type=assigntome", 'alias' => 'feedback');
$lang->my->menu->work['subMenu']->ticket   = array('link' => "{$lang->ticket->common}|my|work|mode=ticket&type=assignedtome", 'alias' => 'ticket');
$lang->my->menu->work['menuOrder'][80] = 'feedback';
$lang->my->menu->work['menuOrder'][85] = 'ticket';

$lang->my->menu->contribute['subMenu']->feedback = array('link' => "{$lang->feedback->common}|my|contribute|mode=feedback&type=openedbyme", 'alias' => 'feedback');
$lang->my->menu->contribute['subMenu']->ticket   = array('link' => "{$lang->ticket->common}|my|contribute|mode=ticket&type=openedbyme", 'alias' => 'ticket');
$lang->my->menu->contribute['menuOrder'][35] = 'feedback';
$lang->my->menu->contribute['menuOrder'][40] = 'ticket';
$lang->my->menu->contribute['menuOrder'][45] = 'audit';
$lang->my->menu->contribute['menuOrder'][50] = 'doc';

$lang->feedbackView[0] = '研發界面';
$lang->feedbackView[1] = '非研發界面';

$lang->switchFeedbackView[1] = '切換研發界面';
$lang->switchFeedbackView[0] = '切換非研發界面';

global $config;
if($config->vision == 'lite')
{
    $lang->feedback->menu->browse = array('link' => '反饋|feedback|browse|browseType=unclosed', 'alias' => 'create,batchcreate,edit,view,adminview,batchedit,browse,admin,batchclose');

    unset($lang->feedback->menu->products);
    unset($lang->feedback->menuOrder[15]);
}

$lang->noMenuModule[] = 'faq';
$lang->noMenuModule[] = 'feedback';
$lang->noMenuModule[] = 'deploy';
$lang->noMenuModule[] = 'host';
$lang->noMenuModule[] = 'serverroom';
$lang->noMenuModule[] = 'service';
$lang->noMenuModule[] = 'ops';

$lang->icons['ticket'] = 'support-ticket';
