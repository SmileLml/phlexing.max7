<?php
$lang->admin->license       = '授权信息';
$lang->admin->uploadLicense = '替换授权';

$lang->admin->licenseInfo['alllife'] = '终生';
$lang->admin->licenseInfo['nouser']  = '不限人数';

$lang->admin->property = new stdclass();
$lang->admin->property->companyName = '公司名称';
$lang->admin->property->startDate   = '授权时间';
$lang->admin->property->expireDate  = '到期时间';
$lang->admin->property->user        = '授权人数';
$lang->admin->property->ip          = '授权IP';
$lang->admin->property->mac         = '授权MAC';
$lang->admin->property->domain      = '授权域名';

$lang->admin->notWritable     = '<code>%s</code> 目录不可写，请修改目录权限正确后，刷新。';
$lang->admin->notZip          = '请上传zip文件。';
$lang->admin->grantCountError = '授权人数不足，系统中已有人数为%s人，您当前授权人数为%s人，为避免影响正常使用，请调整后再上传。';

$lang->admin->extGrantCountError  = '您勾选后授权人数已达%s人，超出%s人';
$lang->admin->extGrantCountNotice = '支持授权：%s人，已授权%s人。';

$lang->admin->disableForExpire = '授权已过期';
$lang->admin->disableForCount  = '授权人数超过用户数，不需要分配授权';

$lang->admin->solutionExt   = '解决方案插件授权信息';
$lang->admin->extensionName = '解决方案名称';
$lang->admin->authorUser    = '授权用户';
$lang->admin->noAuthorUser  = '非授权用户';

$lang->admin->extensionList['safe']     = '禅道多团队协作SAFe版';
$lang->admin->extensionList['zenboard'] = '禅道创新能力版';
$lang->admin->extensionList['devops']   = '禅道DevOps版';
$lang->admin->extensionList['thinmory'] = '禅道决策分析版';
$lang->admin->extensionList['zenperf']  = '研发效能版';

global $config;
if($config->vision == 'rnd')
{
    $lang->admin->menuList->system['subMenu']['license'] = array('link' => "授权信息|admin|license|");
    $lang->admin->menuList->system['menuOrder']['25']    = 'license';
    $lang->admin->menuList->system['dividerMenu']        = str_replace(',safe,', ',license,', $lang->admin->menuList->system['dividerMenu']);
}
