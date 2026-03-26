<?php
$lang->admin->license       = '授權信息';
$lang->admin->uploadLicense = '替換授權';

$lang->admin->licenseInfo['alllife'] = '終生';
$lang->admin->licenseInfo['nouser']  = '不限人數';

$lang->admin->property = new stdclass();
$lang->admin->property->companyName = '公司名稱';
$lang->admin->property->startDate   = '授權時間';
$lang->admin->property->expireDate  = '到期時間';
$lang->admin->property->user        = '授權人數';
$lang->admin->property->ip          = '授權IP';
$lang->admin->property->mac         = '授權MAC';
$lang->admin->property->domain      = '授權域名';

$lang->admin->notWritable     = '<code>%s</code> 目錄不可寫，請修改目錄權限正確後，刷新。';
$lang->admin->notZip          = '請上傳zip檔案。';
$lang->admin->grantCountError = '授權人數不足，系統中已有人數為%s人，您當前授權人數為%s人，為避免影響正常使用，請調整後再上傳。';

$lang->admin->extGrantCountError  = '您勾選後授權人數已達%s人，超出%s人';
$lang->admin->extGrantCountNotice = '支持授權：%s人，已授權%s人。';

$lang->admin->disableForExpire = '授權已過期';
$lang->admin->disableForCount  = '授權人數超過用戶數，不需要分配授權';

$lang->admin->solutionExt   = '解決方案插件授權信息';
$lang->admin->extensionName = '解決方案名稱';
$lang->admin->authorUser    = '授權用戶';
$lang->admin->noAuthorUser  = '非授權用戶';

$lang->admin->extensionList['safe']     = '禪道多團隊協作SAFe版';
$lang->admin->extensionList['zenboard'] = '禪道創新能力版';
$lang->admin->extensionList['devops']   = '禪道DevOps版';
$lang->admin->extensionList['thinmory'] = '禪道決策分析版';
$lang->admin->extensionList['zenperf']  = '研發效能版';

global $config;
if($config->vision == 'rnd')
{
    $lang->admin->menuList->system['subMenu']['license'] = array('link' => "授權信息|admin|license|");
    $lang->admin->menuList->system['menuOrder']['25']    = 'license';
    $lang->admin->menuList->system['dividerMenu']        = str_replace(',safe,', ',license,', $lang->admin->menuList->system['dividerMenu']);
}
