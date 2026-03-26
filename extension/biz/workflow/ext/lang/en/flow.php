<?php
$lang->workflow->common = 'Workflow';

$lang->workflow->navigator = 'Navigator Position';
$lang->workflow->app       = 'Belongs To';
$lang->workflow->all       = 'All';
$lang->workflow->execution = 'Execution';

$lang->workflow->scrum     = 'Scrum';
$lang->workflow->waterfall = 'Waterfall';
$lang->workflow->kanban    = 'Kanban';

$lang->workflow->flowList = new stdClass();

$lang->workflow->setApproval      = 'SetApproval';
$lang->workflow->browseAction     = 'Browse workflow';
$lang->workflow->releaseAction    = 'Release workflow';
$lang->workflow->deactivateAction = 'Deactivate workflow';
$lang->workflow->activateAction   = 'Activate workflow';
$lang->workflow->setJSAction      = 'Set JS';
$lang->workflow->setCSSAction     = 'Set CSS';

$lang->workflow->navigators['']          = '';
$lang->workflow->navigators['primary']   = 'Top';
$lang->workflow->navigators['secondary'] = 'Secondary';

$lang->workflow->notOverride = 'The extension type of action is not override, please change <a href="%s" data-toggle="modal" data-width="600"> extension type</a>';

if($this->config->db->driver == 'dm') unset($lang->workfloweditor->moreSettings['relation']);
