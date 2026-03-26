<?php
$lang->user->expireBizWaring     = "<p style='color:yellow'> 您的軟件授權將在%s天后到期，為避免影響使用，請及時聯繫客戶經理續費。</p>";
$lang->user->expiryReminderToday = "<p style='color:yellow'> 您的軟件授權將於今天到期，為避免影響使用，請及時聯繫客戶經理續費。</p>";
$lang->user->noticeUserLimit     = "系統用戶人數已達授權的上限，不能繼續添加用戶！";

$lang->user->serviceWarning        = "<p style='color:yellow'>您的軟件技術服務將在%s天后到期，為避免影響使用，請及時聯繫客戶經理續費。</p>";
$lang->user->serviceReminderToday  = "<p style='color:yellow'>您的軟件技術服務將於今天到期，為避免影響使用，請及時聯繫客戶經理續費。</p>";
$lang->user->expiredServiceWarning = "<p style='color:yellow'>您的軟件技術服務已過期，為避免影響使用，請及時聯繫客戶經理續費。</p>";

$lang->user->userAddWarning = "剩餘授權人數%d人，超過授權人數後新增人員將不會被保存";

if(!isset($lang->dept)) $lang->dept = new stdclass();
$lang->dept->manager = '部門經理';
