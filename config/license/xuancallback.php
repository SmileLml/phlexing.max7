<?php
function ioncube_event_handler($errCode, $params)
{
    $company    = "%company%";
    $expireDate = "2026-01-02";
    $ip         = "";
    $mac        = "";
    $users      = "3";
    $email      = "%email%";
    $mobile     = "%mobile%";
    $qq         = "%qq%";
    $version    = "%version%";
    if(empty($email) or strpos($email, '%email') === 0) $email = 'co@zentao.net';
    if(empty($mobile) or strpos($mobile, '%mobile') === 0) $mobile = '4006 889923';
    if(empty($qq) or strpos($qq, '%qq') === 0) $qq = 'co@zentao.net(1492153927)';

    $errType = '';
    if($errCode == ION_LICENSE_EXPIRED)
    {
        $errType = 'expired';
    }
    elseif($errCode == ION_LICENSE_SERVER_INVALID)
    {
        $errType = 'serverInvalid';
    }
    elseif($errCode == ION_LICENSE_NOT_FOUND)
    {
        $errType = 'notFound';
    }
    elseif($errCode == ION_LICENSE_CORRUPT)
    {
        $errType = 'corrupt';
    }

    $licenseData = new stdClass();
    $licenseData->errCode    = $errCode;
    $licenseData->errType    = $errType;
    $licenseData->company    = $company;
    $licenseData->expireDate = $expireDate;
    $licenseData->ip         = $ip;
    $licenseData->mac        = $mac;
    $licenseData->users      = $users;
    $licenseData->mobile     = $mobile;
    $licenseData->email      = $email;
    $licenseData->qq         = $qq;
    $licenseData->version    = $version;
    $licenseData->params     = $params;

    include dirname(dirname(dirname(__FILE__))) . '/www/license.php';

    exit;
}

