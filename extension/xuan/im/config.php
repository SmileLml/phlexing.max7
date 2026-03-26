<?php
$config->im->logLine = 20;

$config->im->require = new stdclass();
$config->im->require->create = 'gid, name, type';
$config->im->require->edit   = 'gid, name, type';

$config->im->user = new stdclass();
$config->im->user->canEditFields = array('avatar', 'birthday', 'gender', 'email', 'skype', 'qq', 'mobile', 'phone', 'address', 'zipcode', 'clientStatus', 'weixin', 'realname');

$config->im->retract = new stdclass();
$config->im->retract->validTime = 2;

$config->im->xxdDownloadUrl = 'https://www.zentao.net/dl/zentaoclient/';

$config->im->xxdConfig = array('ip', 'commonPort', 'chatPort', 'https', 'enableAES', 'enableClientAES=1', 'enableCompression=1', 'uploadPath', 'uploadFileSize', 'pollingInterval', 'maxOnlineUser', 'logPath', 'certPath', 'debug', 'thumbnail', 'syncConfig', 'host', 'key');

$config->im->osMap = array();
$config->im->osMap['win_i386']      = 'win32';
$config->im->osMap['win_x86_64']    = 'win64';
$config->im->osMap['linux_i386']    = 'linux.ia32';
$config->im->osMap['linux_x86_64']  = 'linux.x64';
$config->im->osMap['darwin_x86_64'] = 'mac';

$config->im->partition = new stdclass();
$config->im->partition->messagePerTable = 100000;
