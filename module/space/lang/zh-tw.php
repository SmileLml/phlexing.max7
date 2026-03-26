<?php
global $config;

$lang->space->common          = '服務';
$lang->space->browse          = '服務列表';
$lang->space->getStoreAppInfo = '獲取服務信息';
$lang->space->status          = '狀態';
$lang->space->noApps          = '暫無服務';
$lang->space->defaultSpace    = '預設空間';
$lang->space->systemSpace     = '系統空間';
$lang->space->searchInstance  = '搜索服務';
$lang->space->upgrade         = '升級';
$lang->space->install         = '添加服務';
$lang->space->createdBy       = '創建者';
$lang->space->createdAt       = '創建時間';
$lang->space->handConfig      = '手工配置';
$lang->space->addType         = '添加方式';
$lang->space->instanceType    = '實例類型';

$lang->space->notice =  new stdClass();
$lang->space->notice->toInstall = '請到應用市場安裝';

$lang->space->byList = '列表';
$lang->space->byCard = '卡片';

$lang->space->featureBar['browse']['all'] = '全部';
if($config->inQuickon) $lang->space->featureBar['browse']['running']  = '運行中';
if($config->inQuickon) $lang->space->featureBar['browse']['stopped']  = '已關閉';
if($config->inQuickon) $lang->space->featureBar['browse']['abnormal'] = '異常';

$lang->space->appType['gitlab']    = 'GitLab';
$lang->space->appType['jenkins']   = 'Jenkins';
$lang->space->appType['sonarqube'] = 'SonarQube';
if(!$config->inQuickon)
{
    $lang->space->appType['gitea'] = 'Gitea';
    $lang->space->appType['gogs']  = 'Gogs';
}
