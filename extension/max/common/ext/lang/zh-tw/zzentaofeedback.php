<?php
$lang->nonRDMenu = new stdclass();
$lang->nonRDMenu->my       = $lang->navIcons['my'] . '地盤|my|calendar|';
$lang->nonRDMenu->oa       = $lang->navIcons['oa'] . '辦公|attend|personal|';
$lang->nonRDMenu->feedback = $lang->navIcons['feedback'] . '反饋|feedback|browse|';
$lang->nonRDMenu->admin    = "{$lang->navIcons['admin']} {$lang->admin->common}|company|browse|";

$lang->nonRDMenu->menuOrder[5]  = 'my';
$lang->nonRDMenu->menuOrder[10] = 'oa';
$lang->nonRDMenu->menuOrder[15] = 'feedback';
$lang->nonRDMenu->menuOrder[20] = 'admin';

$lang->my->nonRDMenu = new stdclass();
$lang->my->nonRDMenu->calendar = array('link' => "$lang->calendar|my|calendar|", 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->nonRDMenu->effort   = array('link' => "日誌|effort|calendar|", 'subModule' => 'effort', 'alias' => 'effort');

$lang->my->nonRDMenuOrder[5]  = 'calendar';
$lang->my->nonRDMenuOrder[10] = 'effort';

$lang->admin->nonRDMenu = new stdclass();
$lang->admin->nonRDMenu->company = array('link' => "{$lang->personnel->common}|company|browse|", 'subModule' => ',user,dept,');
$lang->admin->nonRDMenu->group   = array('link' => "權限|group|browse|");

$lang->admin->nonRDMenuOrder[5]  = 'company';
$lang->admin->nonRDMenuOrder[10] = 'group';

$lang->nonRDWebMenu = new stdclass();
$lang->nonRDWebMenu->my       = $lang->navIcons['my'] . '地盤|my|calendar|';
$lang->nonRDWebMenu->oa       = $lang->navIcons['oa'] . '辦公|attend|personal|';
$lang->nonRDWebMenu->feedback = $lang->navIcons['feedback'] . '反饋|feedback|browse|';
$lang->nonRDWebMenu->company  = "{$lang->navIcons['admin']} {$lang->admin->common}|company|browse|";

$lang->nonRDWebMenu->menuOrder[5]  = 'my';
$lang->nonRDWebMenu->menuOrder[10] = 'oa';
$lang->nonRDWebMenu->menuOrder[15] = 'feedback';
$lang->nonRDWebMenu->menuOrder[20] = 'company';

$lang->admin->nonRDWebMenu = new stdclass();
$lang->admin->nonRDWebMenu->company = array('link' => "{$lang->personnel->common}|company|browse|", 'subModule' => ',user,dept,');
$lang->admin->nonRDWebMenuOrder[5] = 'company';

global $config;
if($config->vision == 'lite')
{
    $lang->mainNav = $lang->nonRDMenu;
    $lang->mainNav->menuOrder = $lang->nonRDMenu->menuOrder;

    $lang->my->menu      = $lang->my->nonRDMenu;
    $lang->my->menuOrder = $lang->my->nonRDMenuOrder;

    $lang->webMainNav = $lang->nonRDWebMenu;
    $lang->webMenuOrder = $lang->nonRDWebMenu->menuOrder;

    $lang->my->webMenu      = $lang->my->nonRDMenu;
    $lang->my->webMenuOrder = $lang->my->nonRDMenuOrder;

    $lang->company->webMenu      = $lang->admin->nonRDWebMenu;
    $lang->company->webMenuOrder = $lang->admin->nonRDWebMenuOrder;

    $lang->feedback->webMenu = new stdclass();
    $lang->feedback->webMenu->unclosed   = array('link' => '未關閉|feedback|browse|browseType=unclosed', 'subModule' => 'tree');
    $lang->feedback->webMenu->all        = array('link' => '全部|feedback|browse|browseType=all');
    $lang->feedback->webMenu->public     = array('link' => '公開|feedback|browse|browseType=public');

    $lang->feedback->webMenuOrder = array();
    $lang->feedback->webMenuOrder[5]  = 'unclosed';
    $lang->feedback->webMenuOrder[10] = 'all';
    $lang->feedback->webMenuOrder[15] = 'public';
}
