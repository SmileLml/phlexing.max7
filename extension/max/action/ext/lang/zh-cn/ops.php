<?php
$lang->action->label->service    = '服务|service|view|id=%s';
$lang->action->label->serverroom = '机房|serverroom|browse|';
$lang->action->label->host       = '主机|host|view|id=%s';
$lang->action->label->vm         = '虚拟机|vm|view|id=%s';
$lang->action->label->vmtemplate = '虚拟机模板';
$lang->action->label->deploy     = '上线申请|deploy|view|id=%s';
$lang->action->label->deploystep = '上线步骤|deploy|viewStep|id=%s';
$lang->action->label->account    = "账号|account|view|id=%s";
$lang->action->label->domain     = "域名|domain|view|id=%s";

$lang->action->objectTypes['service']    = '服务';
$lang->action->objectTypes['serverroom'] = '机房';
$lang->action->objectTypes['host']       = '主机';
$lang->action->objectTypes['deploy']     = '上线申请';
$lang->action->objectTypes['deploystep'] = '上线步骤';
$lang->action->objectTypes['domain']     = '域名';
$lang->action->objectTypes['account']    = '账号';

$lang->action->desc->online        = '$date, 由 <strong>$actor</strong> 上架。' . "\n";
$lang->action->desc->offline       = '$date, 由 <strong>$actor</strong> 下架。' . "\n";
$lang->action->desc->linkhost      = '$date, 由 <strong>$actor</strong> 关联主机。' . "\n";
$lang->action->desc->linkservice   = '$date, 由 <strong>$actor</strong> 关联服务。' . "\n";
$lang->action->desc->linkcomponent = '$date, 由 <strong>$actor</strong> 关联组件' . "\n";
$lang->action->desc->linkcases     = '$date, 由 <strong>$actor</strong> 关联用例' . "\n";
$lang->action->desc->suspend       = '$date, 由 <strong>$actor</strong> 暂停。' . "\n";
$lang->action->desc->resume        = '$date, 由 <strong>$actor</strong> 恢复。' . "\n";
$lang->action->desc->reboot        = '$date, 由 <strong>$actor</strong> 重启。' . "\n";

$lang->action->label->online        = '上架了';
$lang->action->label->offline       = '下架了';
$lang->action->label->linkhost      = '主机关联到';
$lang->action->label->linkservice   = '服务关联到';
$lang->action->label->linkcomponent = '组件关联到';
$lang->action->label->linkcases     = '用例关联到';
$lang->action->label->suspend       = '暂停了';
$lang->action->label->resume        = '恢复了';
$lang->action->label->reboot        = '重启了';

$lang->action->dynamicAction->vm['created'] = '创建虚拟机';
$lang->action->dynamicAction->vm['suspend'] = '暂停虚拟机';
$lang->action->dynamicAction->vm['resume']  = '恢复虚拟机';
$lang->action->dynamicAction->vm['reboot']  = '重启虚拟机';
$lang->action->dynamicAction->vm['destroy'] = '销毁虚拟机';

$lang->action->dynamicAction->vmtemplate['created'] = '创建虚拟机模板';
$lang->action->dynamicAction->vmtemplate['edited']  = '编辑虚拟机模板';
