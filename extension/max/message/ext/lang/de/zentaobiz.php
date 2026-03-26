<?php
global $config;
$lang->message->label->totask          = 'To Task';
$lang->message->label->tostory         = "To {$lang->SRCommon}";
$lang->message->label->totodo          = 'To Todo';
$lang->message->label->tobug           = 'To Bug';
$lang->message->label->toticket        = 'To Ticket';
$lang->message->label->asked           = 'Asked';
$lang->message->label->replied         = 'Replied';
$lang->message->label->reviewed        = 'Reviewed';
$lang->message->label->processed       = 'Processed';
$lang->message->label->deploypublished = 'Published';
$lang->message->label->recall          = 'Recalled';

if($config->enableER)$lang->message->label->toepic      = "To {$lang->ERCommon}";
if($config->URAndSR) $lang->message->label->touserstory = "To {$lang->URCommon}";
