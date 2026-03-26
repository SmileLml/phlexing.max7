<?php
$lang->nc->browse  = '不符合項列表';
$lang->nc->common  = '不符合項';
$lang->nc->create  = '新建';
$lang->nc->edit    = '編輯';
$lang->nc->delete  = '刪除';
$lang->nc->view    = '詳情';
$lang->nc->resolve = '解決';
$lang->nc->close   = '關閉';
$lang->nc->export  = '導出數據';

$lang->nc->exportAction = '導出不符合項';

$lang->nc->assignTo     = '指派';
$lang->nc->activate     = '激活';
$lang->nc->noAssigned   = '未指派';
$lang->nc->id           = '編號';
$lang->nc->auditplan    = '檢查計劃';
$lang->nc->object       = '檢查對象';
$lang->nc->listID       = '檢查內容';
$lang->nc->title        = '名稱';
$lang->nc->desc         = '完成情況說明';
$lang->nc->type         = '分類';
$lang->nc->status       = '狀態';
$lang->nc->severity     = '嚴重程度';
$lang->nc->deadline     = '計劃解決日期';
$lang->nc->resolvedBy   = '由誰解決';
$lang->nc->resolution   = '解決措施';
$lang->nc->resolvedDate = '解決日期';
$lang->nc->closedBy     = '由誰關閉';
$lang->nc->closedDate   = '關閉日期';
$lang->nc->assignedTo   = '指派給';
$lang->nc->createdBy    = '由誰創建';
$lang->nc->createdDate  = '創建日期';
$lang->nc->execution    = '所屬' . $lang->execution->common;
$lang->nc->activateBy   = '由誰激活';
$lang->nc->activateDate = '激活日期';
$lang->nc->pageSummary  = '本頁共 %total% 個不符合項，激活 %active%。';

$lang->nc->basicInfo     = '基本信息';
$lang->nc->confirmDelete = '您確認要刪除嗎？';

$lang->nc->severityList[0] = '';
$lang->nc->severityList[1] = '嚴重';
$lang->nc->severityList[2] = '中等';
$lang->nc->severityList[3] = '輕微';

$lang->nc->statusList['active']   = '激活';
$lang->nc->statusList['resolved'] = '已解決';
$lang->nc->statusList['closed']   = '關閉';

$lang->nc->typeList[''] = '';

$lang->nc->resolutionList['']           = '';
$lang->nc->resolutionList['bydesign']   = '設計如此';
$lang->nc->resolutionList['external']   = '外部原因';
$lang->nc->resolutionList['fixed']      = '已解決';
$lang->nc->resolutionList['notrepro']   = '無法重現';
$lang->nc->resolutionList['postponed']  = '延期處理';
$lang->nc->resolutionList['willnotfix'] = "不予解決";

$lang->nc->featureBar['browse']['all']          = '全部';
$lang->nc->featureBar['browse']['unclosed']     = '未關閉';
$lang->nc->featureBar['browse']['assignedtome'] = '指派給我';
$lang->nc->featureBar['browse']['assignedbyme'] = '由我指派';

$lang->nc->action = new stdclass();
$lang->nc->action->resolved = array('main' => '$date, 由 <strong>$actor</strong> 解決，結果為 <strong>$extra</strong>。', 'extra' => 'resolutionList');
$lang->nc->action->closed   = array('main' => '$date, 由 <strong>$actor</strong> 關閉。');
