<?php
$lang->workflow->common = '工作流';

$lang->workflow->navigator = '所屬導航';
$lang->workflow->app       = '所屬視圖';
$lang->workflow->all       = '全部';
$lang->workflow->execution = '執行';

$lang->workflow->scrum     = '敏捷';
$lang->workflow->waterfall = '瀑布';
$lang->workflow->kanban    = '看板';

$lang->workflow->flowList = new stdClass();

$lang->workflow->setApproval      = '設置審批流';
$lang->workflow->browseAction     = '流程列表';
$lang->workflow->releaseAction    = '發佈流程';
$lang->workflow->deactivateAction = '停用流程';
$lang->workflow->activateAction   = '啟用流程';
$lang->workflow->setJSAction      = 'JS設置';
$lang->workflow->setCSSAction     = 'CSS設置';

$lang->workflow->navigators['']          = '';
$lang->workflow->navigators['primary']   = '一級導航';
$lang->workflow->navigators['secondary'] = '二級導航';

$lang->workflow->notOverride = '動作的擴展方式不是重寫，請修改<a href="%s" data-toggle="modal" data-width="600">擴展方式</a>';

if($this->config->db->driver == 'dm') unset($lang->workfloweditor->moreSettings['relation']);
