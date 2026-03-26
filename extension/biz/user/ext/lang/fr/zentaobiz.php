<?php
$lang->user->expireBizWaring     = "<p style='color:yellow'>Your enterprise license will expire in %s days. Please renew it by contacting your designated account manager.</p>";
$lang->user->expiryReminderToday = "<p style='color:yellow'>Your enterprise license will expire today. Please renew it by contacting your designated account manager.</p>";
$lang->user->noticeUserLimit     = "The maximum number of authorized system users has been reached, and currently it is not possible to add more users!";

$lang->user->serviceWarning        = "<p style='color:yellow'>Your technical support service will expire in %s days. To ensure uninterrupted usage, please contact our sales promptly for renewal.</p>";
$lang->user->serviceReminderToday  = "<p style='color:yellow'>Your technical support service is scheduled to expire today. To ensure uninterrupted usage, please contact our sales promptly for renewal.</p>";
$lang->user->expiredServiceWarning = "<p style='color:yellow'>Your technical support service has been expired. To ensure uninterrupted usage, please contact our sales promptly for renewal.</p>";

$lang->user->userAddWarning = "There are %d authorized personnel remaining. Any new members added beyond the authorized count will not be saved.";

if(!isset($lang->dept)) $lang->dept = new stdclass();
$lang->dept->manager = 'Manager';
