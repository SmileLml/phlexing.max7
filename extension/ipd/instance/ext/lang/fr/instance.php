<?php
$lang->instance->terminal = 'Terminal';
$lang->instance->errors->connectFailed = 'Connect failed';
$lang->instance->errors->connectClosed = 'Connect closed, whether to refresh the page';

$lang->instance->errors->connectErrors = array();
$lang->instance->errors->connectErrors['invalid_token']          = 'The token entered is incorrect, whether to refresh the page, cancel otherwise';
$lang->instance->errors->connectErrors['expired_token']          = 'The token has expired, whether to refresh the page, cancel otherwise';
$lang->instance->errors->connectErrors['verify_failed_token']    = 'The token verification failed, whether to refresh the page, cancel otherwise';
$lang->instance->errors->connectErrors['resource_not_found']     = 'The object operated on does not exist, whether to refresh the page, cancel otherwise';
$lang->instance->errors->connectErrors['session_expired']        = 'The session has expired, whether to refresh the page, cancel otherwise';
$lang->instance->errors->connectErrors['create_executor_failed'] = 'Internal error, failed to create exec connection, whether to refresh the page, cancel otherwise';
$lang->instance->errors->connectErrors['upstream_closed']        = 'Internal error, upstream stream connection failed, whether to refresh the page, cancel otherwise';

$lang->instance->sourceList['system'] = 'System';
