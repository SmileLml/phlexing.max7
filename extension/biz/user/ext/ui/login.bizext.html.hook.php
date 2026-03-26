<?php
namespace zin;
global $app;

$properties = $app->control->loadModel('api')->getLicenses();
$html       = '';
$expireDate = data('properties.expireDate');
if(!empty($expireDate) && $expireDate != 'All Life')
{
    $expireDays    = helper::diffDate($expireDate, date('Y-m-d'));
    $expireWarning = data('lang.user.expireWarning');
    $version       = data('config.version');
    if(strpos($version, 'biz') !== false) $expireWarning = data('lang.user.expireBizWaring');
    if(strpos($version, 'max') !== false) $expireWarning = data('lang.user.expireMaxWaring');
    if($expireDays <= 30 && $expireDays > 0) $html = sprintf($expireWarning, $expireDays);
    if($expireDays == 0) $html = $lang->user->expiryReminderToday;

    if(!empty($html)) query('#poweredby')->append(html($html));
}
