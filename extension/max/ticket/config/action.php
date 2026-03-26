<?php
global $lang,$config;

$config->ticket->actionList = array();
$config->ticket->actionList['start']['icon']        = 'play';
$config->ticket->actionList['start']['text']        = $lang->ticket->start;
$config->ticket->actionList['start']['hint']        = $lang->ticket->start;
$config->ticket->actionList['start']['url']         = array('module' => 'ticket', 'method' => 'start', 'params' => 'ticketID={id}', 'onlybody' => true);
$config->ticket->actionList['start']['data-toggle'] = 'modal';
$config->ticket->actionList['start']['data-size']   = 'lg';
$config->ticket->actionList['start']['data-type']   = 'iframe';

$config->ticket->actionList['finish']['icon']        = 'checked';
$config->ticket->actionList['finish']['text']        = $lang->ticket->finish;
$config->ticket->actionList['finish']['hint']        = $lang->ticket->finish;
$config->ticket->actionList['finish']['url']         = array('module' => 'ticket', 'method' => 'finish', 'params' => 'ticketID={id}', 'onlybody' => true);
$config->ticket->actionList['finish']['data-toggle'] = 'modal';
$config->ticket->actionList['finish']['data-size']   = 'lg';
$config->ticket->actionList['finish']['data-type']   = 'iframe';

$config->ticket->actionList['activate']['icon']        = 'magic';
$config->ticket->actionList['activate']['text']        = $lang->ticket->activate;
$config->ticket->actionList['activate']['hint']        = $lang->ticket->activate;
$config->ticket->actionList['activate']['url']         = array('module' => 'ticket', 'method' => 'activate', 'params' => 'ticketID={id}', 'onlybody' => true);
$config->ticket->actionList['activate']['data-toggle'] = 'modal';
$config->ticket->actionList['activate']['data-size']   = 'lg';
$config->ticket->actionList['activate']['data-type']   = 'iframe';

$config->ticket->actionList['close']['icon']        = 'off';
$config->ticket->actionList['close']['text']        = $lang->ticket->close;
$config->ticket->actionList['close']['hint']        = $lang->ticket->close;
$config->ticket->actionList['close']['url']         = array('module' => 'ticket', 'method' => 'close', 'params' => 'ticketID={id}');
$config->ticket->actionList['close']['data-toggle'] = 'modal';

$config->ticket->actionList['edit']['icon'] = 'edit';
$config->ticket->actionList['edit']['text'] = $lang->ticket->edit;
$config->ticket->actionList['edit']['hint'] = $lang->ticket->edit;
$config->ticket->actionList['edit']['url']  = array('module' => 'ticket', 'method' => 'edit', 'params' => 'ticketID={id}');

$config->ticket->actionList['delete']['icon']         = 'trash';
$config->ticket->actionList['delete']['text']         = $lang->ticket->delete;
$config->ticket->actionList['delete']['hint']         = $lang->ticket->delete;
$config->ticket->actionList['delete']['url']          = array('module' => 'ticket', 'method' => 'delete', 'params' => 'ticketID={id}');
$config->ticket->actionList['delete']['className']    = 'ajax-submit';
$config->ticket->actionList['delete']['data-confirm'] = array('message' => $lang->ticket->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->ticket->actionList['delete']['notInModal']   = true;

$config->ticket->actionList['createBug']['icon'] = 'bug';
$config->ticket->actionList['createBug']['text'] = $lang->ticket->toBug;
$config->ticket->actionList['createBug']['hint'] = $lang->ticket->toBug;
$config->ticket->actionList['createBug']['url']  = array('module' => 'ticket', 'method' => 'createBug', 'params' => 'product={product}&extra=projectID=0,fromType=ticket,fromID={id}');

$config->ticket->actionList['createStory']['icon'] = 'lightbulb';
$config->ticket->actionList['createStory']['text'] = $lang->ticket->toStory;
$config->ticket->actionList['createStory']['hint'] = $lang->ticket->toStory;
$config->ticket->actionList['createStory']['url']  = array('module' => 'ticket', 'method' => 'createStory', 'params' => 'product={product}&extra=fromType=ticket,fromID={id}');

$config->ticket->actionList['recordHour']['icon']        = 'time';
$config->ticket->actionList['recordHour']['text']        = $lang->ticket->effort;
$config->ticket->actionList['recordHour']['hint']        = $lang->ticket->effort;
$config->ticket->actionList['recordHour']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'objectType=ticket&objectID={id}');
$config->ticket->actionList['recordHour']['data-toggle'] = 'modal';

$config->ticket->actionList['assignTo']['icon']        = 'hand-right';
$config->ticket->actionList['assignTo']['text']        = $lang->ticket->assignTo;
$config->ticket->actionList['assignTo']['hint']        = $lang->ticket->assignTo;
$config->ticket->actionList['assignTo']['url']         = array('module' => 'ticket', 'method' => 'assignTo', 'params' => 'ticketID={id}', 'onlybody' => true);
$config->ticket->actionList['assignTo']['data-toggle'] = 'modal';
$config->ticket->actionList['assignTo']['data-type']   = 'iframe';
