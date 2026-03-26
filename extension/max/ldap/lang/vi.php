<?php
$lang->ldap->common = 'Thiết lập LDAP';
$lang->ldap->index  = 'Trang LDAP';
$lang->ldap->base   = 'Thiết lập cơ bản';
$lang->ldap->attr   = 'Thiết lập thuộc tính';
$lang->ldap->other  = 'Khác';
$lang->ldap->import = 'Nhập người dùng từ LDAP';

$lang->ldap->turnon    = 'Tình trạng';
$lang->ldap->type   = 'Loại máy chủ';
$lang->ldap->host   = 'Máy chủ LDAP';
$lang->ldap->port   = 'Cổng';
$lang->ldap->version   = 'LDAP Version';
$lang->ldap->admin  = 'Tài khoản quản trị';
$lang->ldap->password  = 'Mật khẩu';
$lang->ldap->baseDN    = 'Base DN';
$lang->ldap->account   = 'Tài khoản đăng nhập';
$lang->ldap->realname  = 'Họ và Tên';
$lang->ldap->email  = 'Email';
$lang->ldap->phone  = 'Điện thoại';
$lang->ldap->mobile    = 'Mobile';
$lang->ldap->anonymous = 'Anonymous';
$lang->ldap->charset   = 'LDAP Charset';
$lang->ldap->custom    = 'Tùy biến';
$lang->ldap->repeatPolicy = 'Repeat Policy';
$lang->ldap->defaultGroup = 'Default Group';
$lang->ldap->autoCreate   = 'Automatically Create User';

$lang->ldap->example  = 'For example,';
$lang->ldap->accountPS   = 'Account on LDAP server';
$lang->ldap->groupPS  = 'Nhóm to which LDAP user belongs after login';

$lang->ldap->successSave = 'Saved!';

$lang->ldap->error    = new stdclass();
$lang->ldap->error->connect = "LDAP server không là connected. LDAP address or port error!";
$lang->ldap->error->verify  = 'Account/Password Error, or LDAP verison Error!';
$lang->ldap->error->noempty = '[%s] không nên trống.';

$lang->ldap->turnonList[0] = 'OFF';
$lang->ldap->turnonList[1] = 'ON';

$lang->ldap->versionList[3] = '3';
$lang->ldap->versionList[2] = '2';

$lang->ldap->typeList['ldap'] = "LDAP Server";
$lang->ldap->typeList['ad']   = "Active Directory";

$lang->ldap->repeatPolicyList['number'] = 'Thêm number, for example admin,admin2';
$lang->ldap->repeatPolicyList['dept']   = 'Thêm dept, for example admin(Dev)，admin(Test)';

$lang->ldap->autoCreateList[1] = 'Yes';
$lang->ldap->autoCreateList[0] = 'No';

$lang->ldap->noldap    = new stdclass();
$lang->ldap->noldap->header  = "ERROR:LDAP extension of PHP không là loaded. ";
$lang->ldap->noldap->content = 'This feature depends on LDAP extension,you could modify the php.ini file, or install it. Specific instructions can refer to <a href="https://www.zentao.pm/book/zentaopromanual/137.html" target="_blank">Install LDAP extension</a>.';

$lang->ldap->importTip = 'Đăng ký LDAP thành công, bạn có thể nhập khẩu người dùng từ LDAP!';
$lang->ldap->goImport  = 'Để nhập khẩu';
