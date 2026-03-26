<?php
global $lang;
$config->opportunity->actionList = array();

$config->opportunity->actionList['track']['icon']        = 'checked';
$config->opportunity->actionList['track']['text']        = $lang->opportunity->track;
$config->opportunity->actionList['track']['hint']        = $lang->opportunity->track;
$config->opportunity->actionList['track']['url']         = array('module' => 'opportunity', 'method' => 'track', 'params' => 'opportunityID={id}');
$config->opportunity->actionList['track']['data-toggle'] = 'modal';
$config->opportunity->actionList['track']['class']       = 'opportunity-track-btn';

$config->opportunity->actionList['close']['icon']        = 'off';
$config->opportunity->actionList['close']['text']        = $lang->opportunity->close;
$config->opportunity->actionList['close']['hint']        = $lang->opportunity->close;
$config->opportunity->actionList['close']['url']         = array('module' => 'opportunity', 'method' => 'close', 'params' => 'opportunityID={id}');
$config->opportunity->actionList['close']['data-toggle'] = 'modal';
$config->opportunity->actionList['close']['class']       = 'opportunity-close-btn';

$config->opportunity->actionList['cancel']['icon']        = 'cancel';
$config->opportunity->actionList['cancel']['text']        = $lang->opportunity->cancel;
$config->opportunity->actionList['cancel']['hint']        = $lang->opportunity->cancel;
$config->opportunity->actionList['cancel']['url']         = array('module' => 'opportunity', 'method' => 'cancel', 'params' => 'opportunityID={id}');
$config->opportunity->actionList['cancel']['data-toggle'] = 'modal';

$config->opportunity->actionList['hangup']['icon']        = 'arrow-up';
$config->opportunity->actionList['hangup']['text']        = $lang->opportunity->hangup;
$config->opportunity->actionList['hangup']['hint']        = $lang->opportunity->hangup;
$config->opportunity->actionList['hangup']['url']         = array('module' => 'opportunity', 'method' => 'hangup', 'params' => 'opportunityID={id}');
$config->opportunity->actionList['hangup']['data-toggle'] = 'modal';

$config->opportunity->actionList['activate']['icon']        = 'magic';
$config->opportunity->actionList['activate']['text']        = $lang->opportunity->activate;
$config->opportunity->actionList['activate']['hint']        = $lang->opportunity->activate;
$config->opportunity->actionList['activate']['url']         = array('module' => 'opportunity', 'method' => 'activate', 'params' => 'opportunityID={id}');
$config->opportunity->actionList['activate']['data-toggle'] = 'modal';

$config->opportunity->actionList['edit']['icon']       = 'edit';
$config->opportunity->actionList['edit']['text']       = $lang->opportunity->edit;
$config->opportunity->actionList['edit']['hint']       = $lang->opportunity->edit;
$config->opportunity->actionList['edit']['url']        = array('module' => 'opportunity', 'method' => 'edit', 'params' => 'opportunityID={id}&from={from}');
$config->opportunity->actionList['edit']['notInModal'] = true;

$config->opportunity->actionList['assignTo']['icon']        = 'hand-right';
$config->opportunity->actionList['assignTo']['hint']        = $lang->opportunity->assignTo;
$config->opportunity->actionList['assignTo']['text']        = $lang->opportunity->assignTo;
$config->opportunity->actionList['assignTo']['url']         = helper::createLink('opportunity', 'assignTo', 'opportunityID={id}');
$config->opportunity->actionList['assignTo']['data-toggle'] = 'modal';

$config->opportunity->actionList['importToLib']['icon']        = 'assets';
$config->opportunity->actionList['importToLib']['hint']        = $lang->opportunity->importToLib;
$config->opportunity->actionList['importToLib']['text']        = $lang->opportunity->importToLib;
$config->opportunity->actionList['importToLib']['data-target'] = '#importToLib';
$config->opportunity->actionList['importToLib']['data-toggle'] = 'modal';
$config->opportunity->actionList['importToLib']['data-size']   = 'sm';

$config->opportunity->actionList['delete']['icon']         = 'trash';
$config->opportunity->actionList['delete']['hint']         = $lang->opportunity->delete;
$config->opportunity->actionList['delete']['text']         = $lang->opportunity->delete;
$config->opportunity->actionList['delete']['url']          = helper::createLink('opportunity', 'delete', 'opportunityID={id}');
$config->opportunity->actionList['delete']['data-confirm'] = array('message' => $lang->opportunity->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->opportunity->actionList['delete']['class']        = 'ajax-submit';
$config->opportunity->actionList['delete']['notInModal']   = true;
