<?php
$lang->flow->template         = '模板';
$lang->flow->showImport       = '导入确认';
$lang->flow->link             = '关联';
$lang->flow->unlink           = '移除';
$lang->flow->detail           = '详情';
$lang->flow->search           = '搜索';
$lang->flow->ditto            = '同上';
$lang->flow->importMode       = '导入模式';
$lang->flow->approvalProgress = '审批进度';
$lang->flow->setExport        = '流程设计 => 导出设置';
$lang->flow->setSearch        = '流程设计 => 搜索设置';

$lang->flow->selectLinkType = '选择要关联的数据';
$lang->flow->unlinkConfirm  = '您确定要移除该%s吗？';
$lang->flow->filesNotEmpty  = '%s附件不能为空！';

$lang->flow->importModeList['template'] = '模版导入';
//$lang->flow->importModeList['auto']     = '自动导入';

$lang->flow->reportGranularity['week'] = '%s年%s周';
$lang->flow->reportGranularity['Q1']   = '%s年一季度';
$lang->flow->reportGranularity['Q2']   = '%s年二季度';
$lang->flow->reportGranularity['Q3']   = '%s年三季度';
$lang->flow->reportGranularity['Q4']   = '%s年四季度';

$lang->flow->notset     = '未设置';
$lang->flow->noAssigned = '未指派';

$lang->flow->tips = new stdclass();
$lang->flow->tips->notice                 = '发送提醒给所选择的用户';
$lang->flow->tips->emptyExportFields      = '没有可以导出的字段，请前往 <strong>%s</strong> 中进行设置。';
$lang->flow->tips->emptySearchFields      = '没有可以搜索的字段，请前往 <strong>%s</strong> 中进行设置。';
$lang->flow->tips->importMode['template'] = '按照导入模板匹配，符合条件的数据才会被导入。';
$lang->flow->tips->importMode['auto']     = '按照导出设置匹配，符合条件的数据才会被导入。';

$lang->flow->error = new stdclass();;
$lang->flow->error->notFound          = '数据未找到。';
$lang->flow->error->emptyLayoutFields = "请前往 『流程』 => 『%s』 => 『动作』 => 『%s』 => 『界面』 设置界面显示的内容。";
$lang->flow->error->approval          = '审批失败，请联系管理员。';
