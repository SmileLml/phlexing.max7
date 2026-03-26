<?php
$lang->instance->terminal = '終端';
$lang->instance->errors->connectFailed = '連接失敗';
$lang->instance->errors->connectClosed = '連接已關閉，是否刷新頁面';

$lang->instance->errors->connectErrors = array();
$lang->instance->errors->connectErrors['invalid_token']          = '傳入的token不正確, 是否刷新頁面, 取消則頁面關閉';
$lang->instance->errors->connectErrors['expired_token']          = 'token 已過期, 是否刷新頁面, 取消則頁面關閉';
$lang->instance->errors->connectErrors['verify_failed_token']    = 'token校驗失敗, 是否刷新頁面, 取消則頁面關閉';
$lang->instance->errors->connectErrors['resource_not_found']     = '操作對象不存在, 是否刷新頁面, 取消則頁面關閉';
$lang->instance->errors->connectErrors['session_expired']        = '長時間未收到請求，服務端主動關閉了連接, 是否刷新頁面, 取消則頁面關閉';
$lang->instance->errors->connectErrors['create_executor_failed'] = '內部錯誤, 創建 exec 連接失敗, 是否刷新頁面, 取消則頁面關閉';
$lang->instance->errors->connectErrors['upstream_closed']        = '內部錯誤，上游 stream 連接失敗, 是否刷新頁面, 取消則頁面關閉';

$lang->instance->sourceList['system'] = '內置';
