<?php
$lang->devops->homeMenu->deploy = array('link' => "{$lang->deployment->common}|deploy|browse", 'alias' => 'steps,managestep,create,edit,browse,view,scope,cases,treemap', 'subModule' => 'host,deploy,env,publishtemplate,tree,serverroom');

$lang->devops->homeMenu->deploy['subMenu'] = new stdclass();
$lang->devops->homeMenu->deploy['subMenu']->deploy = array('link' => "{$lang->devops->deploy}|deploy|browse", 'subModule' => 'deploy');
$lang->devops->homeMenu->deploy['subMenu']->host   = array('link' => "{$lang->devops->host}|host|browse", 'alias' => 'treemap,create,edit,tree,view,tree-browse', 'subModule' => 'tree,serverroom');

$lang->devops->homeMenu->deploy['menuOrder'][10] = 'deploy';
$lang->devops->homeMenu->deploy['menuOrder'][25] = 'host';

$lang->workflow->menu->approval = array('link' => "{$lang->approvalflow->common}|approvalflow|browse|", 'subModule' => 'approvalflow,approvalrole');

$lang->workflow->menuOrder[25] = 'approval';
$lang->workflow->dividerMenu = ',approval,';

$lang->scrum->menu->settings['alias'] .= ',workflowgroup';

$lang->waterfall->menu->settings['subMenu']->workflow = array('link' => "{$lang->projectFlow}|project|workflowgroup|project=%s", 'alias' => 'workflowgroup');
$lang->waterfall->menu->settings['alias'] .= ',workflowgroup';