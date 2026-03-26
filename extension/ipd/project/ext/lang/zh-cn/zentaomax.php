<?php
$lang->project->approval           = '审批';
$lang->project->previous           = '上一步';
$lang->project->deliverable        = '维护交付物';
$lang->project->deliverableAbbr    = '交付物';
$lang->project->template           = '项目模板';
$lang->project->templateList       = '项目模板列表';
$lang->project->templateName       = '项目模板名称';
$lang->project->createTemplate     = '创建项目模板';
$lang->project->createTemplateAbbr = '从已有项目创建';
$lang->project->copyProjectID      = '选择项目';
$lang->project->model              = '项目模型';
$lang->project->newProject         = '全新项目';
$lang->project->deleteTemplate     = '删除项目模板';
$lang->project->inUse              = '使用中';
$lang->project->noDesc             = '暂时没有描述';

$lang->project->approvalflow = new stdclass();
$lang->project->approvalflow->flow   = '审批流程';
$lang->project->approvalflow->object = '审批对象';

$lang->project->approvalflow->objectList[''] = '';
$lang->project->approvalflow->objectList['stage'] = '阶段';
$lang->project->approvalflow->objectList['task']  = '任务';

$lang->project->deliverableList['create'] = '创建时的交付物';
$lang->project->deliverableList['close']  = '关闭时的交付物';

$lang->project->copyProjectConfirm    = '完善' . $lang->projectCommon . '信息';
$lang->project->executionInfoConfirm  = '完善' . $lang->projectCommon . '信息';
$lang->project->stageInfoConfirm      = '完善阶段信息';
$lang->project->confirmDeleteTemplate = '确认要删除项目模板吗?';

$lang->project->executionInfoTips     = "为了避免重复，请修改{$lang->executionCommon}名称和{$lang->executionCommon}代号，设置计划开始时间和计划完成时间。";
$lang->project->executionInfoTipsAbbr = "为了避免重复，请修改{$lang->executionCommon}名称和{$lang->executionCommon}代号。";
$lang->project->deliverableTips       = '交付物提交比例=已提交交付物个数/必填和已提交交付物的总数';
$lang->project->whenClosedTips        = '（项目未关闭时，不会对关闭时的交付物进行严格校验）';

$lang->project->chosenProductStage = '请为 “%s”' . $lang->productCommon . '选择要复制的对应' . $lang->productCommon . '的阶段' . $lang->productCommon . '：%s';
$lang->project->notCopyStage       = '不复制';
$lang->project->completeCopy       = '复制完成';
$lang->project->noTemplateData     = '暂无项目模板';

$lang->project->copyProject->code               = '『' . $lang->projectCommon . '』代号不可重复需要修改';
$lang->project->copyProject->executionCode      = '『' . $lang->executionCommon . '』代号不可重复需要修改';
$lang->project->copyProject->select             = '选择要复制的' . $lang->projectCommon;
$lang->project->copyProject->confirmData        = '确认要复制的数据';
$lang->project->copyProject->improveData        = '完善新' . $lang->projectCommon . '的数据';
$lang->project->copyProject->completeData       = '完成' . $lang->projectCommon . '复制';
$lang->project->copyProject->selectPlz          = '请选择要复制的' . $lang->projectCommon;
$lang->project->copyProject->cancel             = '取消复制';
$lang->project->copyProject->all                = '全部数据';
$lang->project->copyProject->basic              = '基础数据';
$lang->project->copyProject->allList            = array($lang->projectCommon . '自身的数据', $lang->projectCommon . '所包含的%s', $lang->projectCommon . '和%s的文档目录', $lang->projectCommon . '%s所包含的任务', 'QA质量保证计划', '过程裁剪设置', '团队成员安排与权限');
$lang->project->copyProject->noSprintList       = array($lang->projectCommon . '自身的数据', $lang->projectCommon . '所包含的任务', $lang->projectCommon . '的文档目录', '团队成员安排与权限');
$lang->project->copyProject->ipdAllList         = array($lang->projectCommon . '自身的数据', $lang->projectCommon . '所包含的%s', $lang->projectCommon . '和%s的文档目录', $lang->projectCommon . '%s所包含的任务', '团队成员安排与权限');
$lang->project->copyProject->toComplete         = '去完善';
$lang->project->copyProject->selectProjectPlz   = '请选择' . $lang->projectCommon;
$lang->project->copyProject->confirmCopyDataTip = '请确认要复制的数据：';
$lang->project->copyProject->basicInfo          = $lang->projectCommon . '数据（所属' . $lang->projectCommon . '集，' . $lang->projectCommon . '名称，' . $lang->projectCommon . '代号，所属' . $lang->productCommon . '）';
$lang->project->copyProject->selectProgram      = '请选择' . $lang->projectCommon . '集';
$lang->project->copyProject->sprint             = $lang->executionCommon;

$lang->project->action->managedeliverable = '$date, 由 <strong>$actor</strong> 维护交付物。' . "\n";

$lang->project->featureBar['template']['all'] = '全部';

global $config;
if($config->systemMode == 'light') $lang->project->copyProject->basicInfo = $lang->projectCommon . '数据（' . $lang->projectCommon . '名称，' . $lang->projectCommon . '代号，所属' . $lang->productCommon . '）';
