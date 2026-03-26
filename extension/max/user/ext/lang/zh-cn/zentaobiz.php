<?php
$lang->user->expireBizWaring     = "<p style='color:yellow'> 您的软件授权将在%s天后到期，为避免影响使用，请及时联系客户经理续费。</p>";
$lang->user->expiryReminderToday = "<p style='color:yellow'> 您的软件授权将于今天到期，为避免影响使用，请及时联系客户经理续费。</p>";
$lang->user->noticeUserLimit     = "系统用户人数已达授权的上限，不能继续添加用户！";

$lang->user->serviceWarning        = "<p style='color:yellow'>您的软件技术服务将在%s天后到期，为避免影响使用，请及时联系客户经理续费。</p>";
$lang->user->serviceReminderToday  = "<p style='color:yellow'>您的软件技术服务将于今天到期，为避免影响使用，请及时联系客户经理续费。</p>";
$lang->user->expiredServiceWarning = "<p style='color:yellow'>您的软件技术服务已过期，为避免影响使用，请及时联系客户经理续费。</p>";

$lang->user->userAddWarning = "剩余授权人数%d人，超过授权人数后新增人员将不会被保存";

if(!isset($lang->dept)) $lang->dept = new stdclass();
$lang->dept->manager = '部门经理';
