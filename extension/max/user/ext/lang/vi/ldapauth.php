<?php
$lang->user->importLDAP = 'Nhập người dùng từ LDAP';
$lang->user->link    = 'Liên kết Local tài khoản';

$lang->user->allLDAP = 'Tất cả';

if(!isset($lang->user->error)) $lang->user->error = new stdclass();
$lang->user->error->repeat     = "%s, cùng tên người dùng tồn tại trong ZenTao, vì vậy không thể thêm tên này! Chỉnh sửa tên người dùng và sau đó thêm nó.";
$lang->user->error->illaccount = "%s, tên người dùng không hợp lệ, vì vậy nó không thành công! Chỉnh sửa tên người dùng và thêm nó.";
$lang->user->error->userLimit  = "Số lượng người dùng đã đạt đến giới hạn của giấy phép! Không có thêm người dùng có thể được nhập từ LDAP!";
$lang->user->error->duplicated = 'Liên kết tài khoản nội bộ đã nhân bản';
$lang->user->error->role       = '%s, vai trò không thể để trống.';
$lang->user->error->noImport   = 'This account cannot create to ZenTao. Please contact the ZenTao administrator to import the account with LDAP.';
$lang->user->error->ldap       = "LDAP error, error code: %s, error message: %s.";

$lang->user->notice = new stdclass();
$lang->user->notice->checkbox = 'Nếu không được kiểm tra, nó sẽ không được nhập!';
$lang->user->notice->ldapoff  = "LDAP is OFF!";
