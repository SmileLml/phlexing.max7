<?php
global $lang;
$config->risk->actionList = array();

$config->risk->actionList['track']['icon']        = 'checked';
$config->risk->actionList['track']['text']        = $lang->risk->track;
$config->risk->actionList['track']['hint']        = $lang->risk->track;
$config->risk->actionList['track']['url']         = array('module' => 'risk', 'method' => 'track', 'params' => 'riskID={id}');
$config->risk->actionList['track']['data-toggle'] = 'modal';
$config->risk->actionList['track']['class']       = 'risk-track-btn';

$config->risk->actionList['assignTo']['icon']        = 'hand-right';
$config->risk->actionList['assignTo']['hint']        = $lang->risk->assignTo;
$config->risk->actionList['assignTo']['text']        = $lang->risk->assignTo;
$config->risk->actionList['assignTo']['url']         = helper::createLink('risk', 'assignTo', 'riskID={id}');
$config->risk->actionList['assignTo']['data-toggle'] = 'modal';

$config->risk->actionList['createForObject']['icon']        = 'time';
$config->risk->actionList['createForObject']['text']        = $lang->risk->effort;
$config->risk->actionList['createForObject']['hint']        = $lang->risk->effort;
$config->risk->actionList['createForObject']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'objectType=risk&objectID={id}');
$config->risk->actionList['createForObject']['data-toggle'] = 'modal';

$config->risk->actionList['cancel']['icon']        = 'cancel';
$config->risk->actionList['cancel']['text']        = $lang->risk->cancel;
$config->risk->actionList['cancel']['hint']        = $lang->risk->cancel;
$config->risk->actionList['cancel']['url']         = array('module' => 'risk', 'method' => 'cancel', 'params' => 'riskID={id}');
$config->risk->actionList['cancel']['data-toggle'] = 'modal';

$config->risk->actionList['close']['icon']        = 'off';
$config->risk->actionList['close']['text']        = $lang->risk->close;
$config->risk->actionList['close']['hint']        = $lang->risk->close;
$config->risk->actionList['close']['url']         = array('module' => 'risk', 'method' => 'close', 'params' => 'riskID={id}');
$config->risk->actionList['close']['data-toggle'] = 'modal';
$config->risk->actionList['close']['class']       = 'risk-close-btn';

$config->risk->actionList['hangup']['icon']        = 'pause';
$config->risk->actionList['hangup']['text']        = $lang->risk->hangup;
$config->risk->actionList['hangup']['hint']        = $lang->risk->hangup;
$config->risk->actionList['hangup']['url']         = array('module' => 'risk', 'method' => 'hangup', 'params' => 'riskID={id}');
$config->risk->actionList['hangup']['data-toggle'] = 'modal';

$config->risk->actionList['activate']['icon']        = 'magic';
$config->risk->actionList['activate']['text']        = $lang->risk->activate;
$config->risk->actionList['activate']['hint']        = $lang->risk->activate;
$config->risk->actionList['activate']['url']         = array('module' => 'risk', 'method' => 'activate', 'params' => 'riskID={id}');
$config->risk->actionList['activate']['data-toggle'] = 'modal';

$config->risk->actionList['importToLib']['icon']        = 'assets';
$config->risk->actionList['importToLib']['hint']        = $lang->risk->importToLib;
$config->risk->actionList['importToLib']['text']        = $lang->risk->importToLib;
$config->risk->actionList['importToLib']['data-target'] = '#importToLib';
$config->risk->actionList['importToLib']['data-toggle'] = 'modal';
$config->risk->actionList['importToLib']['data-size']   = 'sm';

$config->risk->actionList['edit']['icon']       = 'edit';
$config->risk->actionList['edit']['text']       = $lang->risk->edit;
$config->risk->actionList['edit']['hint']       = $lang->risk->edit;
$config->risk->actionList['edit']['url']        = array('module' => 'risk', 'method' => 'edit', 'params' => 'riskID={id}&from={from}');
$config->risk->actionList['edit']['notInModal'] = true;

$config->risk->actionList['delete']['icon']         = 'trash';
$config->risk->actionList['delete']['hint']         = $lang->risk->delete;
$config->risk->actionList['delete']['text']         = $lang->risk->delete;
$config->risk->actionList['delete']['url']          = helper::createLink('risk', 'delete', 'taskID={id}');
$config->risk->actionList['delete']['data-confirm'] = array('message' => $lang->risk->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->risk->actionList['delete']['class']        = 'ajax-submit';
$config->risk->actionList['delete']['notInModal']   = true;
