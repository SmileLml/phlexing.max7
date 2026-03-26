<?php
$lang->admin->license       = 'License';
$lang->admin->uploadLicense = 'Replace License';

$lang->admin->licenseInfo['alllife'] = 'Permanent';
$lang->admin->licenseInfo['nouser']  = 'Unlimit';

$lang->admin->property = new stdclass();
$lang->admin->property->companyName = 'Company Name';
$lang->admin->property->startDate   = 'Start';
$lang->admin->property->expireDate  = 'Expiration';
$lang->admin->property->user        = 'User';
$lang->admin->property->ip          = 'IP';
$lang->admin->property->mac         = 'MAC';
$lang->admin->property->domain      = 'Domain';

$lang->admin->notWritable     = '<code>%s</code> is not writable. Modify permissions and refresh.';
$lang->admin->notZip          = 'Please upload zip file.';
$lang->admin->grantCountError = "Authorization limit exceeded: The system currently has %s users, but you've only authorized %s. Please adjust your settings before uploading.";

$lang->admin->extGrantCountError  = 'After you checked, the authorized number has reached %s people, exceedingi %s people';
$lang->admin->extGrantCountNotice = 'Support authorization: %s, authorized %s.';

$lang->admin->disableForExpire = 'Authorization has expired';
$lang->admin->disableForCount  = 'No authorization needs to be allocated.';

$lang->admin->solutionExt   = 'Authorized Solution Info';
$lang->admin->extensionName = 'Solution Name';
$lang->admin->authorUser    = 'Author User';
$lang->admin->noAuthorUser  = 'No Author User';

$lang->admin->extensionList['safe']     = 'SAFE';
$lang->admin->extensionList['zenboard'] = 'Board Solution';
$lang->admin->extensionList['devops']   = 'DevOps Solution';
$lang->admin->extensionList['thinmory'] = 'Thinmory Solution';
$lang->admin->extensionList['zenperf']  = 'Zen Perf Solution';

global $config;
if($config->vision == 'rnd')
{
    $lang->admin->menuList->system['subMenu']['license'] = array('link' => "License|admin|license|");
    $lang->admin->menuList->system['menuOrder']['25']    = 'license';
    $lang->admin->menuList->system['dividerMenu']        = str_replace(',safe,', ',license,', $lang->admin->menuList->system['dividerMenu']);
}
