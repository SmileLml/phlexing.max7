<?php
global $app, $config;
$app->loadLang('story');
$lang->epic = clone $lang->story;

foreach($lang->epic as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->epic->$key = str_replace($lang->SRCommon, $lang->ERCommon, $value);
}

$lang->epic->common = $lang->ERCommon;

$lang->epic->stageList = array();
$lang->epic->stageList[''] = '';
$lang->epic->stageList['wait'] = '未開始';
if($config->edition == 'ipd')
{
    $lang->epic->stageList['inroadmap'] = '已設路標';
    $lang->epic->stageList['incharter'] = 'Charter立項';
}
$lang->epic->stageList['planned']    = '已計劃';
$lang->epic->stageList['projected']  = '研發立項';
$lang->epic->stageList['developing'] = '研發中';
$lang->epic->stageList['delivering'] = '交付中';
$lang->epic->stageList['delivered']  = '已交付';
$lang->epic->stageList['closed']     = '已關閉';
