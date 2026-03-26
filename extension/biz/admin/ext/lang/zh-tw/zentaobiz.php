<?php
global $config;
$lang->admin->property = new stdclass();
$lang->admin->property->companyName     = '公司名稱';
$lang->admin->property->startDate       = '授權時間';
$lang->admin->property->expireDate      = '授權到期時間';
$lang->admin->property->serviceDeadline = '技術服務到期時間';
$lang->admin->property->user            = '授權人數';
$lang->admin->property->ip              = '授權IP';
$lang->admin->property->mac             = '授權MAC';
$lang->admin->property->domain          = '授權域名';

$lang->admin->menuList->system['subMenu']['libreoffice'] = array('link' => 'Office|custom|libreoffice|');
$lang->admin->menuList->system['menuOrder']['60']        = 'libreoffice';

$lang->admin->menuList->feature['subMenu']['feedback'] = array('link' => "反饋|custom|required|module=feedback", 'exclude' => 'set,required');
$lang->admin->menuList->feature['menuOrder']['35']     = 'feedback';

$lang->admin->menuList->feature['tabMenu']['feedback']['feedback'] = array('link' => "反饋|custom|required|module=feedback", 'links' => array('custom|set|module=feedback&field=review'), 'exclude' => 'custom-set,custom-required');
$lang->admin->menuList->feature['tabMenu']['feedback']['ticket']   = array('link' => "工單|custom|required|module=ticket", 'exclude' => 'custom-set,custom-required');

if($config->vision == 'lite') unset($lang->admin->menuList->feature['subMenu']['feedback'], $lang->admin->menuList->feature['menuOrder']['35']);

$lang->admin->menuList->feature['subMenu']['relation'] = array('link' => "關聯關係|custom|browseRelation|", 'alias' => 'browserelation');
$lang->admin->menuList->feature['menuOrder']['41']     = 'relation';
$lang->admin->menuList->feature['dividerMenu']         = ',relation,';

if($config->systemMode != 'light')
{
    $lang->admin->menuList->feature['subMenu']['charter'] = array('link' => "立項|custom|setCharterInfo|", 'alias' => 'setcharterinfo');
    $lang->admin->menuList->feature['menuOrder']['6']     = 'charter';
}
