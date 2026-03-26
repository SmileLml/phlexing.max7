<?php
/* Fields */
$lang->opportunity->source            = '來源';
$lang->opportunity->id                = 'ID';
$lang->opportunity->name              = '機會名稱';
$lang->opportunity->type              = '類型';
$lang->opportunity->strategy          = '策略';
$lang->opportunity->status            = '狀態';
$lang->opportunity->impact            = '影響程度';
$lang->opportunity->chance            = '發生概率';
$lang->opportunity->ratio             = '機會係數';
$lang->opportunity->execution         = '所屬' . $lang->execution->common;
$lang->opportunity->pri               = '優先順序';
$lang->opportunity->desc              = '機會描述';
$lang->opportunity->prevention        = '應對措施';
$lang->opportunity->identifiedDate    = '識別日期';
$lang->opportunity->plannedClosedDate = '計劃關閉日期';
$lang->opportunity->assignedTo        = '指派給';
$lang->opportunity->assignedDate      = '指派日期';
$lang->opportunity->createdBy         = '由誰添加';
$lang->opportunity->createdDate       = '添加日期';
$lang->opportunity->noAssigned        = '未指派';
$lang->opportunity->canceledBy        = '由誰取消';
$lang->opportunity->canceledDate      = '取消日期';
$lang->opportunity->cancelReason      = '取消原因';
$lang->opportunity->resolvedBy        = '解決者';
$lang->opportunity->resolvedDate      = '解決日期';
$lang->opportunity->closedBy          = '由誰關閉';
$lang->opportunity->closedDate        = '關閉日期';
$lang->opportunity->actualClosedDate  = '實際關閉日期';
$lang->opportunity->resolution        = '響應措施';
$lang->opportunity->hangupedBy        = '由誰掛起';
$lang->opportunity->hangupedDate      = '掛起日期';
$lang->opportunity->activatedBy       = '由誰激活';
$lang->opportunity->activatedDate     = '激活日期';
$lang->opportunity->isChange          = '機會是否變化';
$lang->opportunity->lastCheckedBy     = '由誰跟蹤';
$lang->opportunity->lastCheckedDate   = '跟蹤日期';
$lang->opportunity->editedBy          = '由誰編輯';
$lang->opportunity->editedDate        = '編輯日期';
$lang->opportunity->legendBasicInfo   = '基本信息';
$lang->opportunity->legendLifeTime    = '機會的一生';
$lang->opportunity->confirmDelete     = '您確認刪除該機會嗎？';
$lang->opportunity->deleted           = '已刪除';
$lang->opportunity->allLib            = '所有機會庫';
$lang->opportunity->lib               = '機會庫';
$lang->opportunity->approver          = '審批人';

/* Actions */
$lang->opportunity->create           = '添加機會';
$lang->opportunity->edit             = '編輯機會';
$lang->opportunity->browse           = '瀏覽機會';
$lang->opportunity->view             = '機會詳情';
$lang->opportunity->activate         = '激活';
$lang->opportunity->hangup           = '掛起';
$lang->opportunity->close            = '關閉';
$lang->opportunity->cancel           = '取消';
$lang->opportunity->batchCreate      = '批量添加';
$lang->opportunity->batchEdit        = '批量編輯';
$lang->opportunity->batchAssignTo    = '批量指派';
$lang->opportunity->batchClose       = '批量關閉';
$lang->opportunity->batchCancel      = '批量取消';
$lang->opportunity->batchHangup      = '批量掛起';
$lang->opportunity->batchActivate    = '批量激活';
$lang->opportunity->track            = '跟蹤';
$lang->opportunity->assignTo         = '指派';
$lang->opportunity->delete           = '刪除';
$lang->opportunity->byQuery          = '搜索';
$lang->opportunity->trackAction      = '跟蹤機會';
$lang->opportunity->assignToAction   = '指派機會';
$lang->opportunity->cancelAction     = '取消機會';
$lang->opportunity->closeAction      = '關閉機會';
$lang->opportunity->hangupAction     = '掛起機會';
$lang->opportunity->activateAction   = '激活機會';
$lang->opportunity->deleteAction     = '刪除機會';
$lang->opportunity->importToLib      = '導入機會庫';
$lang->opportunity->batchImportToLib = '批量導入機會庫';
$lang->opportunity->isExist          = '機會庫中已有此條記錄，請勿重複提交！';

$lang->opportunity->importFromLib = '從機會庫中導入';
$lang->opportunity->import        = '導入';
$lang->opportunity->export        = '導出數據';
$lang->opportunity->exportAction  = '導出機會';

$lang->opportunity->action = new stdclass();
$lang->opportunity->action->hangup                   = '$date, 由 <strong>$actor</strong> 掛起。' . "\n";
$lang->opportunity->action->tracked                  = '$date, 由 <strong>$actor</strong> 跟蹤。' . "\n";
$lang->opportunity->action->importfromopportunitylib = array('main' => '$date, 由 <strong>$actor</strong> 從機會庫 <strong>$extra</strong>導入。');

$lang->opportunity->sourceList[''] = '';
$lang->opportunity->sourceList['business'] = '業務部門';
$lang->opportunity->sourceList['team']     =  $lang->projectCommon . '組';
$lang->opportunity->sourceList['logistic'] =  $lang->projectCommon . '保障科室';
$lang->opportunity->sourceList['manage']   = '管理層';
$lang->opportunity->sourceList['customer'] = '外部客戶';
$lang->opportunity->sourceList['others']   = '其他';

$lang->opportunity->typeList[''] = '';
$lang->opportunity->typeList['technical']   = '技術類';
$lang->opportunity->typeList['manage']      = '管理類';
$lang->opportunity->typeList['business']    = '業務類';
$lang->opportunity->typeList['requirement'] = '需求類';
$lang->opportunity->typeList['resource']    = '資源類';
$lang->opportunity->typeList['others']      = '其他';

$lang->opportunity->impactList[1]  = 1;
$lang->opportunity->impactList[2]  = 2;
$lang->opportunity->impactList[3]  = 3;
$lang->opportunity->impactList[4]  = 4;
$lang->opportunity->impactList[5]  = 5;

$lang->opportunity->chanceList[1]  = 1;
$lang->opportunity->chanceList[2]  = 2;
$lang->opportunity->chanceList[3]  = 3;
$lang->opportunity->chanceList[4]  = 4;
$lang->opportunity->chanceList[5]  = 5;

$lang->opportunity->priList['high']   = '高';
$lang->opportunity->priList['middle'] = '中';
$lang->opportunity->priList['low']    = '低';

$lang->opportunity->statusList[''] = '';
$lang->opportunity->statusList['active']   = '開放';
$lang->opportunity->statusList['closed']   = '關閉';
$lang->opportunity->statusList['hangup']   = '掛起';
$lang->opportunity->statusList['canceled'] = '取消';

$lang->opportunity->strategyList[''] = '';
$lang->opportunity->strategyList['monitor'] = '監控';
$lang->opportunity->strategyList['create']  = '創造';
$lang->opportunity->strategyList['utilize'] = '利用';
$lang->opportunity->strategyList['enhance'] = '增強';
$lang->opportunity->strategyList['accept']  = '接受';

$lang->opportunity->isChangeList[0] = '否';
$lang->opportunity->isChangeList[1] = '是';

$lang->opportunity->cancelReasonList[''] = '';
$lang->opportunity->cancelReasonList['disappeared'] = '機會自行消失';
$lang->opportunity->cancelReasonList['mistake']     = '識別錯誤';

$lang->opportunity->featureBar['browse']['all']      = '全部';
$lang->opportunity->featureBar['browse']['active']   = '開放';
$lang->opportunity->featureBar['browse']['assignTo'] = '指派給我';
$lang->opportunity->featureBar['browse']['closed']   = '已關閉';
$lang->opportunity->featureBar['browse']['hangup']   = '已掛起';
$lang->opportunity->featureBar['browse']['canceled'] = '已取消';
