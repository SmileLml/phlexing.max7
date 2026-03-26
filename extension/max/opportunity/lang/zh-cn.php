<?php
/* Fields */
$lang->opportunity->source            = '来源';
$lang->opportunity->id                = 'ID';
$lang->opportunity->name              = '机会名称';
$lang->opportunity->type              = '类型';
$lang->opportunity->strategy          = '策略';
$lang->opportunity->status            = '状态';
$lang->opportunity->impact            = '影响程度';
$lang->opportunity->chance            = '发生概率';
$lang->opportunity->ratio             = '机会系数';
$lang->opportunity->execution         = '所属' . $lang->execution->common;
$lang->opportunity->pri               = '优先级';
$lang->opportunity->desc              = '机会描述';
$lang->opportunity->prevention        = '应对措施';
$lang->opportunity->identifiedDate    = '识别日期';
$lang->opportunity->plannedClosedDate = '计划关闭日期';
$lang->opportunity->assignedTo        = '指派给';
$lang->opportunity->assignedDate      = '指派日期';
$lang->opportunity->createdBy         = '由谁添加';
$lang->opportunity->createdDate       = '添加日期';
$lang->opportunity->noAssigned        = '未指派';
$lang->opportunity->canceledBy        = '由谁取消';
$lang->opportunity->canceledDate      = '取消日期';
$lang->opportunity->cancelReason      = '取消原因';
$lang->opportunity->resolvedBy        = '解决者';
$lang->opportunity->resolvedDate      = '解决日期';
$lang->opportunity->closedBy          = '由谁关闭';
$lang->opportunity->closedDate        = '关闭日期';
$lang->opportunity->actualClosedDate  = '实际关闭日期';
$lang->opportunity->resolution        = '响应措施';
$lang->opportunity->hangupedBy        = '由谁挂起';
$lang->opportunity->hangupedDate      = '挂起日期';
$lang->opportunity->activatedBy       = '由谁激活';
$lang->opportunity->activatedDate     = '激活日期';
$lang->opportunity->isChange          = '机会是否变化';
$lang->opportunity->lastCheckedBy     = '由谁跟踪';
$lang->opportunity->lastCheckedDate   = '跟踪日期';
$lang->opportunity->editedBy          = '由谁编辑';
$lang->opportunity->editedDate        = '编辑日期';
$lang->opportunity->legendBasicInfo   = '基本信息';
$lang->opportunity->legendLifeTime    = '机会的一生';
$lang->opportunity->confirmDelete     = '您确认删除该机会吗？';
$lang->opportunity->deleted           = '已删除';
$lang->opportunity->allLib            = '所有机会库';
$lang->opportunity->lib               = '机会库';
$lang->opportunity->approver          = '审批人';

/* Actions */
$lang->opportunity->create           = '添加机会';
$lang->opportunity->edit             = '编辑机会';
$lang->opportunity->browse           = '浏览机会';
$lang->opportunity->view             = '机会详情';
$lang->opportunity->activate         = '激活';
$lang->opportunity->hangup           = '挂起';
$lang->opportunity->close            = '关闭';
$lang->opportunity->cancel           = '取消';
$lang->opportunity->batchCreate      = '批量添加';
$lang->opportunity->batchEdit        = '批量编辑';
$lang->opportunity->batchAssignTo    = '批量指派';
$lang->opportunity->batchClose       = '批量关闭';
$lang->opportunity->batchCancel      = '批量取消';
$lang->opportunity->batchHangup      = '批量挂起';
$lang->opportunity->batchActivate    = '批量激活';
$lang->opportunity->track            = '跟踪';
$lang->opportunity->assignTo         = '指派';
$lang->opportunity->delete           = '删除';
$lang->opportunity->byQuery          = '搜索';
$lang->opportunity->trackAction      = '跟踪机会';
$lang->opportunity->assignToAction   = '指派机会';
$lang->opportunity->cancelAction     = '取消机会';
$lang->opportunity->closeAction      = '关闭机会';
$lang->opportunity->hangupAction     = '挂起机会';
$lang->opportunity->activateAction   = '激活机会';
$lang->opportunity->deleteAction     = '删除机会';
$lang->opportunity->importToLib      = '导入机会库';
$lang->opportunity->batchImportToLib = '批量导入机会库';
$lang->opportunity->isExist          = '机会库中已有此条记录，请勿重复提交！';

$lang->opportunity->importFromLib = '从机会库中导入';
$lang->opportunity->import        = '导入';
$lang->opportunity->export        = '导出数据';
$lang->opportunity->exportAction  = '导出机会';

$lang->opportunity->action = new stdclass();
$lang->opportunity->action->hangup                   = '$date, 由 <strong>$actor</strong> 挂起。' . "\n";
$lang->opportunity->action->tracked                  = '$date, 由 <strong>$actor</strong> 跟踪。' . "\n";
$lang->opportunity->action->importfromopportunitylib = array('main' => '$date, 由 <strong>$actor</strong> 从机会库 <strong>$extra</strong>导入。');

$lang->opportunity->sourceList[''] = '';
$lang->opportunity->sourceList['business'] = '业务部门';
$lang->opportunity->sourceList['team']     =  $lang->projectCommon . '组';
$lang->opportunity->sourceList['logistic'] =  $lang->projectCommon . '保障科室';
$lang->opportunity->sourceList['manage']   = '管理层';
$lang->opportunity->sourceList['customer'] = '外部客户';
$lang->opportunity->sourceList['others']   = '其他';

$lang->opportunity->typeList[''] = '';
$lang->opportunity->typeList['technical']   = '技术类';
$lang->opportunity->typeList['manage']      = '管理类';
$lang->opportunity->typeList['business']    = '业务类';
$lang->opportunity->typeList['requirement'] = '需求类';
$lang->opportunity->typeList['resource']    = '资源类';
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
$lang->opportunity->statusList['active']   = '开放';
$lang->opportunity->statusList['closed']   = '关闭';
$lang->opportunity->statusList['hangup']   = '挂起';
$lang->opportunity->statusList['canceled'] = '取消';

$lang->opportunity->strategyList[''] = '';
$lang->opportunity->strategyList['monitor'] = '监控';
$lang->opportunity->strategyList['create']  = '创造';
$lang->opportunity->strategyList['utilize'] = '利用';
$lang->opportunity->strategyList['enhance'] = '增强';
$lang->opportunity->strategyList['accept']  = '接受';

$lang->opportunity->isChangeList[0] = '否';
$lang->opportunity->isChangeList[1] = '是';

$lang->opportunity->cancelReasonList[''] = '';
$lang->opportunity->cancelReasonList['disappeared'] = '机会自行消失';
$lang->opportunity->cancelReasonList['mistake']     = '识别错误';

$lang->opportunity->featureBar['browse']['all']      = '全部';
$lang->opportunity->featureBar['browse']['active']   = '开放';
$lang->opportunity->featureBar['browse']['assignTo'] = '指派给我';
$lang->opportunity->featureBar['browse']['closed']   = '已关闭';
$lang->opportunity->featureBar['browse']['hangup']   = '已挂起';
$lang->opportunity->featureBar['browse']['canceled'] = '已取消';
