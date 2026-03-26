<?php
$lang->user->importLDAP = '從LDAP導入用戶';
$lang->user->link       = '關聯本地賬號';

$lang->user->allLDAP = '所有';

$lang->user->featureBar['importldap']['all'] = '所有';

if(!isset($lang->user->error)) $lang->user->error = new stdclass();
$lang->user->error->repeat     = "%s，因為用戶名重複，不能添加！，請修改用戶名後再添加";
$lang->user->error->illaccount = "%s，因為用戶名不合法，添加失敗！，請修改用戶名後再添加";
$lang->user->error->userLimit  = "人數已經達到授權的上限，不能從LDAP導入新用戶！";
$lang->user->error->duplicated = '重複關聯賬號';
$lang->user->error->role       = '%s，職位不能為空。';
$lang->user->error->noImport   = '當前帳號不能創建對應的禪道帳號，請聯繫禪道管理員用LDAP導入該賬號';
$lang->user->error->connect    = '系統登錄失敗，請檢查LDAP服務是否正常。';
$lang->user->error->ldap       = "LDAP錯誤，錯誤碼：%s，錯誤信息：%s";

$lang->user->notice = new stdclass();
$lang->user->notice->checkbox = '沒有勾選，則不導入！';
$lang->user->notice->ldapoff  = 'LDAP處于關閉狀態。';
