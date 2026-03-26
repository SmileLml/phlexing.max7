<?php
$lang->workflowgroup->common         = '工作流流程';
$lang->workflowgroup->product        = '瀏覽產品流程';
$lang->workflowgroup->project        = '瀏覽項目流程';
$lang->workflowgroup->create         = '添加流程';
$lang->workflowgroup->createProduct  = '添加產品流程';
$lang->workflowgroup->createProject  = '添加項目流程';
$lang->workflowgroup->edit           = '編輯';
$lang->workflowgroup->delete         = '刪除';
$lang->workflowgroup->view           = '流程詳情';
$lang->workflowgroup->design         = '設計';
$lang->workflowgroup->release        = '發佈';
$lang->workflowgroup->deactivate     = '停用';
$lang->workflowgroup->activate       = '啟用';
$lang->workflowgroup->setExclusive   = '自定義';
$lang->workflowgroup->activateFlow   = '啟用流程';
$lang->workflowgroup->deactivateFlow = '停用流程';

$lang->workflowgroup->id           = '編號';
$lang->workflowgroup->type         = '流程類型';
$lang->workflowgroup->projectModel = '適用的項目模型';
$lang->workflowgroup->projectType  = '適用的項目類型';
$lang->workflowgroup->name         = '流程名';
$lang->workflowgroup->status       = '狀態';
$lang->workflowgroup->vision       = '所屬界面';
$lang->workflowgroup->desc         = '描述';
$lang->workflowgroup->createdBy    = '由誰創建';
$lang->workflowgroup->createdDate  = '創建日期';
$lang->workflowgroup->editedBy     = '由誰編輯';
$lang->workflowgroup->editedDate   = '編輯日期';
$lang->workflowgroup->deleted      = '是否刪除';
$lang->workflowgroup->template     = '模板';
$lang->workflowgroup->flow         = '流程';
$lang->workflowgroup->rule         = '規則引擎';

$lang->workflowgroup->notice = new stdclass();
$lang->workflowgroup->notice->confirmDeactivate = '您確定要停用此流程嗎？';
$lang->workflowgroup->notice->confirmDelete     = "您確定要刪除此流程嗎？刪除不影響已使用該流程的%s。";
$lang->workflowgroup->notice->confirmExclusive  = "設為自定義後，該工作流可以在此流程模板下進行個性化配置，僅對該流程生效，不影響其他流程，操作不可逆。";

$lang->workflowgroup->typeList['product'] = '產品流程';
$lang->workflowgroup->typeList['project'] = '項目流程';

$lang->workflowgroup->projectModelList['scrum']     = '敏捷';
$lang->workflowgroup->projectModelList['waterfall'] = '瀑布';

$lang->workflowgroup->projectTypeList['product'] = '產品型';
$lang->workflowgroup->projectTypeList['project'] = '項目型';

$lang->workflowgroup->statusList['wait']   = '待發佈';
$lang->workflowgroup->statusList['normal'] = '使用中';
$lang->workflowgroup->statusList['pause']  = '停用';

$lang->workflowgroup->abbr = new stdclass();
$lang->workflowgroup->abbr->design     = '設計';
$lang->workflowgroup->abbr->activate   = '啟用';
$lang->workflowgroup->abbr->deactivate = '停用';

$lang->workflowgroup->workflow = new stdclass();
$lang->workflowgroup->workflow->name      = '流程名';
$lang->workflowgroup->workflow->module    = '流程代號';
$lang->workflowgroup->workflow->app       = '所屬視圖';
$lang->workflowgroup->workflow->exclusive = '通用/自定義';
$lang->workflowgroup->workflow->buildin   = '內置';
$lang->workflowgroup->workflow->desc      = '描述';

$lang->workflowgroup->workflow->exclusiveList[0] = '通用';
$lang->workflowgroup->workflow->exclusiveList[1] = '自定義';

global $config;
if($config->systemMode == 'light') unset($lang->workflowgroup->projectModelList['waterfall']);
