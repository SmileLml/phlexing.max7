<?php
global $config;
$lang->message->label->totask          = '转任务';
$lang->message->label->tostory         = "转{$lang->SRCommon}";
$lang->message->label->totodo          = '转待办';
$lang->message->label->tobug           = '转Bug';
$lang->message->label->toticket        = '转工单';
$lang->message->label->asked           = '追问';
$lang->message->label->replied         = '回复';
$lang->message->label->reviewed        = '审批';
$lang->message->label->processed       = '已处理';
$lang->message->label->deploypublished = '上线';
$lang->message->label->recall          = '撤回';

if($config->enableER)$lang->message->label->toepic      = "转{$lang->ERCommon}";
if($config->URAndSR) $lang->message->label->touserstory = "转{$lang->URCommon}";
