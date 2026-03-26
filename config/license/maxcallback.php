<?php
function ioncube_event_handler($err_code, $params)
{
    $expiredate = "2026-01-02";
    $domain     = "";
    $ip         = "";
    $mac        = "";
    $users      = "3";
    $feedback   = "0";
    $lite       = "20";
    $account    = "";
    $version    = "%version%";
    $email      = "%email%";
    $mobile     = "%mobile%";
    $qq         = "%qq%";
    if(empty($email) or strpos($email, '%email') === 0) $email = 'co@zentao.net';
    if(empty($mobile) or strpos($mobile, '%mobile') === 0) $mobile = '4006 889923';
    if(empty($qq) or strpos($qq, '%qq') === 0) $qq = 'co@zentao.net(1492153927)';
    $expired = "
        <html>
        <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' />
        <title>error</title>
        </head>
        <body>
        <h2 style='color:red;text-align:center'>您使用的版本已经过期</h2>
        <p>您当前使用的版本截止日期是{$expiredate}，已经过期，请联系我们购买授权。</p>
        <p>手机：13730922971</p>
        <p>电话：$mobile</p>
        <p>QQ：$qq</p>
        <p>微信：13730922971</p>
        <p>Email：$email</p>
        <p>网站：<a href='http://www.zentao.net' target='_blank'>http://www.zentao.net</a></p>
        <br /> <br /> <br />
        <h2 style='color:red;text-align:center'>The license is expired!</h2>
        <p>The license expiration date is {$expiredate}. Please contact us to renew your license.</p>
        <p>Email：philip@easycorp.ltd</p>
        <p>Website: <a href='http://www.zentao.pm' target='_blank'>http://www.zentao.pm</a></p>
        </body>
        </html>
        ";
    $server = "
        <html>
        <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' />
        <title>error</title>
        </head>
        <body>
        <h2 style='color:red;text-align:center'>错误的IP地址或MAC地址，或错误的域名访问</h2>
        <p>软件授权的IP地址或MAC地址和当前系统的IP地址或MAC地址不一致，请使用最初授权的服务器。或你访问的域名与绑定的域名不一致。</p>
        <p>手机：13730922971</p>
        <p>电话：$mobile</p>
        <p>QQ：$qq</p>
        <p>微信：13730922971</p>
        <p>Email：$email</p>
        <p>网站：<a href='http://www.zentao.net' target='_blank'>http://www.zentao.net</a></p>
        <br /> <br /> <br />
        <h2 style='color:red;text-align:center'>Wrong IP, MAC address, or domains!</h2>
        <p>The IP, MAC address, or the domains of your server is not the same one in your license.</p>
        <p>Email : philip@easycorp.ltd</p>
        <p>Website : <a href='http://www.zentao.pm' target='_blank'>http://www.zentao.pm</a></p>
        </body>
        </html>
        ";
    if($err_code == ION_LICENSE_EXPIRED)
    {
        echo $expired;
    }
    elseif($err_code == ION_LICENSE_SERVER_INVALID)
    {
        echo $server;
    }
    exit;
}
