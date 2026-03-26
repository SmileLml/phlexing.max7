<?php
$lang->deliverable->name           = '交付物名称';
$lang->deliverable->desc           = '描述';
$lang->deliverable->module         = '适用对象';
$lang->deliverable->method         = '动作';
$lang->deliverable->model          = '适用范围';
$lang->deliverable->createdByAB    = '创建者';
$lang->deliverable->createdBy      = '由谁创建';
$lang->deliverable->createdDate    = '创建日期';
$lang->deliverable->lastEditedBy   = '最后修改';
$lang->deliverable->lastEditedDate = '最后修改日期';
$lang->deliverable->template       = '引用模板';
$lang->deliverable->files          = '上传模板';
$lang->deliverable->or             = '或';
$lang->deliverable->basicInfo      = '基本信息';

$lang->deliverable->browse = '交付物列表';
$lang->deliverable->create = '添加交付物';
$lang->deliverable->edit   = '编辑交付物';
$lang->deliverable->delete = '删除交付物';
$lang->deliverable->view   = '交付物详情';

$lang->deliverable->abbr = new stdclass();
$lang->deliverable->abbr->template = '模板';

$lang->deliverable->moduleList['project']   = '项目';
$lang->deliverable->moduleList['execution'] = '执行';

$lang->deliverable->methodList['create'] = '创建';
$lang->deliverable->methodList['close']  = '关闭';

$lang->deliverable->modelList['product_waterfall'] = '产品型瀑布项目';
$lang->deliverable->modelList['project_waterfall'] = '项目型瀑布项目';
$lang->deliverable->modelList['product_scrum']     = '产品型敏捷项目';
$lang->deliverable->modelList['project_scrum']     = '项目型敏捷项目';

$lang->deliverable->confirmDelete    = '删除后将同步移除流程模板中已配置的交付物，是否继续？';
$lang->deliverable->summary          = '本页共有%s个交付物';
$lang->deliverable->exceededCountTip = '每个交付物只能上传一个文件';

$lang->deliverable->featureBar['browse']['all']       = '全部';
$lang->deliverable->featureBar['browse']['project']   = '项目';
$lang->deliverable->featureBar['browse']['execution'] = '执行';

$lang->deliverable->addedDoc    = '新增交付物文档：%s';
$lang->deliverable->deletedDoc  = '删除交付物文档：%s';
$lang->deliverable->addedFile   = '新增交付物附件：%s';
$lang->deliverable->deletedFile = '删除交付物附件：%s';
$lang->deliverable->renamedFile = '重命名交付物附件：%s -> %s';
$lang->deliverable->renamedDoc  = '重命名交付物文档：%s -> %s';