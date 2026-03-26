<?php
$lang->workflow->common = '工作流';

$lang->workflow->navigator = '所属导航';
$lang->workflow->app       = '所属视图';
$lang->workflow->all       = '全部';
$lang->workflow->execution = '执行';

$lang->workflow->scrum     = '敏捷';
$lang->workflow->waterfall = '瀑布';
$lang->workflow->kanban    = '看板';

$lang->workflow->flowList = new stdClass();

$lang->workflow->setApproval      = '设置审批流';
$lang->workflow->browseAction     = '流程列表';
$lang->workflow->releaseAction    = '发布流程';
$lang->workflow->deactivateAction = '停用流程';
$lang->workflow->activateAction   = '启用流程';
$lang->workflow->setJSAction      = 'JS设置';
$lang->workflow->setCSSAction     = 'CSS设置';

$lang->workflow->navigators['']          = '';
$lang->workflow->navigators['primary']   = '一级导航';
$lang->workflow->navigators['secondary'] = '二级导航';

$lang->workflow->notOverride = '动作的扩展方式不是重写，请修改<a href="%s" data-toggle="modal" data-width="600">扩展方式</a>';

if($this->config->db->driver == 'dm') unset($lang->workfloweditor->moreSettings['relation']);
