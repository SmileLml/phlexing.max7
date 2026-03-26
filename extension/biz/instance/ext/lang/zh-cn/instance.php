<?php
$lang->instance->terminal = '终端';
$lang->instance->errors->connectFailed = '连接失败';
$lang->instance->errors->connectClosed = '连接已关闭，是否刷新页面';

$lang->instance->errors->connectErrors = array();
$lang->instance->errors->connectErrors['invalid_token']          = '传入的token不正确, 是否刷新页面, 取消则页面关闭';
$lang->instance->errors->connectErrors['expired_token']          = 'token 已过期, 是否刷新页面, 取消则页面关闭';
$lang->instance->errors->connectErrors['verify_failed_token']    = 'token校验失败, 是否刷新页面, 取消则页面关闭';
$lang->instance->errors->connectErrors['resource_not_found']     = '操作对象不存在, 是否刷新页面, 取消则页面关闭';
$lang->instance->errors->connectErrors['session_expired']        = '长时间未收到请求，服务端主动关闭了连接, 是否刷新页面, 取消则页面关闭';
$lang->instance->errors->connectErrors['create_executor_failed'] = '内部错误, 创建 exec 连接失败, 是否刷新页面, 取消则页面关闭';
$lang->instance->errors->connectErrors['upstream_closed']        = '内部错误，上游 stream 连接失败, 是否刷新页面, 取消则页面关闭';

$lang->instance->sourceList['system'] = '内置';
