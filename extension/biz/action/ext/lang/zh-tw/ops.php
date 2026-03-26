<?php
$lang->action->label->service    = '服務|service|view|id=%s';
$lang->action->label->serverroom = '機房|serverroom|browse|';
$lang->action->label->host       = '主機|host|view|id=%s';
$lang->action->label->vm         = '虛擬機|vm|view|id=%s';
$lang->action->label->vmtemplate = '虛擬機模板';
$lang->action->label->deploy     = '上線申請|deploy|view|id=%s';
$lang->action->label->deploystep = '上線步驟|deploy|viewStep|id=%s';
$lang->action->label->account    = "賬號|account|view|id=%s";
$lang->action->label->domain     = "域名|domain|view|id=%s";

$lang->action->objectTypes['service']    = '服務';
$lang->action->objectTypes['serverroom'] = '機房';
$lang->action->objectTypes['host']       = '主機';
$lang->action->objectTypes['deploy']     = '上線申請';
$lang->action->objectTypes['deploystep'] = '上線步驟';
$lang->action->objectTypes['domain']     = '域名';
$lang->action->objectTypes['account']    = '賬號';

$lang->action->desc->online        = '$date, 由 <strong>$actor</strong> 上架。' . "\n";
$lang->action->desc->offline       = '$date, 由 <strong>$actor</strong> 下架。' . "\n";
$lang->action->desc->linkhost      = '$date, 由 <strong>$actor</strong> 關聯主機。' . "\n";
$lang->action->desc->linkservice   = '$date, 由 <strong>$actor</strong> 關聯服務。' . "\n";
$lang->action->desc->linkcomponent = '$date, 由 <strong>$actor</strong> 關聯組件' . "\n";
$lang->action->desc->linkcases     = '$date, 由 <strong>$actor</strong> 關聯用例' . "\n";
$lang->action->desc->suspend       = '$date, 由 <strong>$actor</strong> 暫停。' . "\n";
$lang->action->desc->resume        = '$date, 由 <strong>$actor</strong> 恢復。' . "\n";
$lang->action->desc->reboot        = '$date, 由 <strong>$actor</strong> 重啟。' . "\n";

$lang->action->label->online        = '上架了';
$lang->action->label->offline       = '下架了';
$lang->action->label->linkhost      = '主機關聯到';
$lang->action->label->linkservice   = '服務關聯到';
$lang->action->label->linkcomponent = '組件關聯到';
$lang->action->label->linkcases     = '用例關聯到';
$lang->action->label->suspend       = '暫停了';
$lang->action->label->resume        = '恢復了';
$lang->action->label->reboot        = '重啟了';

$lang->action->dynamicAction->vm['created'] = '創建虛擬機';
$lang->action->dynamicAction->vm['suspend'] = '暫停虛擬機';
$lang->action->dynamicAction->vm['resume']  = '恢復虛擬機';
$lang->action->dynamicAction->vm['reboot']  = '重啟虛擬機';
$lang->action->dynamicAction->vm['destroy'] = '銷毀虛擬機';

$lang->action->dynamicAction->vmtemplate['created'] = '創建虛擬機模板';
$lang->action->dynamicAction->vmtemplate['edited']  = '編輯虛擬機模板';
