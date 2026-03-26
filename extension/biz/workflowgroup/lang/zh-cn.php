<?php
$lang->workflowgroup->common         = '工作流流程';
$lang->workflowgroup->product        = '浏览产品流程';
$lang->workflowgroup->project        = '浏览项目流程';
$lang->workflowgroup->create         = '添加流程';
$lang->workflowgroup->createProduct  = '添加产品流程';
$lang->workflowgroup->createProject  = '添加项目流程';
$lang->workflowgroup->edit           = '编辑';
$lang->workflowgroup->delete         = '删除';
$lang->workflowgroup->view           = '流程详情';
$lang->workflowgroup->design         = '设计';
$lang->workflowgroup->release        = '发布';
$lang->workflowgroup->deactivate     = '停用';
$lang->workflowgroup->activate       = '启用';
$lang->workflowgroup->setExclusive   = '自定义';
$lang->workflowgroup->activateFlow   = '启用流程';
$lang->workflowgroup->deactivateFlow = '停用流程';

$lang->workflowgroup->id           = '编号';
$lang->workflowgroup->type         = '流程类型';
$lang->workflowgroup->projectModel = '适用的项目模型';
$lang->workflowgroup->projectType  = '适用的项目类型';
$lang->workflowgroup->name         = '流程名';
$lang->workflowgroup->status       = '状态';
$lang->workflowgroup->vision       = '所属界面';
$lang->workflowgroup->desc         = '描述';
$lang->workflowgroup->createdBy    = '由谁创建';
$lang->workflowgroup->createdDate  = '创建日期';
$lang->workflowgroup->editedBy     = '由谁编辑';
$lang->workflowgroup->editedDate   = '编辑日期';
$lang->workflowgroup->deleted      = '是否删除';
$lang->workflowgroup->template     = '模板';
$lang->workflowgroup->flow         = '流程';
$lang->workflowgroup->rule         = '规则引擎';

$lang->workflowgroup->notice = new stdclass();
$lang->workflowgroup->notice->confirmDeactivate = '您确定要停用此流程吗？';
$lang->workflowgroup->notice->confirmDelete     = "您确定要删除此流程吗？删除不影响已使用该流程的%s。";
$lang->workflowgroup->notice->confirmExclusive  = "设为自定义后，该工作流可以在此流程模板下进行个性化配置，仅对该流程生效，不影响其他流程，操作不可逆。";

$lang->workflowgroup->typeList['product'] = '产品流程';
$lang->workflowgroup->typeList['project'] = '项目流程';

$lang->workflowgroup->projectModelList['scrum']     = '敏捷';
$lang->workflowgroup->projectModelList['waterfall'] = '瀑布';

$lang->workflowgroup->projectTypeList['product'] = '产品型';
$lang->workflowgroup->projectTypeList['project'] = '项目型';

$lang->workflowgroup->statusList['wait']   = '待发布';
$lang->workflowgroup->statusList['normal'] = '使用中';
$lang->workflowgroup->statusList['pause']  = '停用';

$lang->workflowgroup->abbr = new stdclass();
$lang->workflowgroup->abbr->design     = '设计';
$lang->workflowgroup->abbr->activate   = '启用';
$lang->workflowgroup->abbr->deactivate = '停用';

$lang->workflowgroup->workflow = new stdclass();
$lang->workflowgroup->workflow->name      = '流程名';
$lang->workflowgroup->workflow->module    = '流程代号';
$lang->workflowgroup->workflow->app       = '所属视图';
$lang->workflowgroup->workflow->exclusive = '通用/自定义';
$lang->workflowgroup->workflow->buildin   = '内置';
$lang->workflowgroup->workflow->desc      = '描述';

$lang->workflowgroup->workflow->exclusiveList[0] = '通用';
$lang->workflowgroup->workflow->exclusiveList[1] = '自定义';

global $config;
if($config->systemMode == 'light') unset($lang->workflowgroup->projectModelList['waterfall']);
