<?php
$lang->navIcons['workflow']     = "<i class='icon icon-flow'></i>";
$lang->navIconNames['workflow'] = "flow";

/* Workflow */
$lang->workflow = new stdclass();
$lang->workflow->common = '工作流';

$lang->workflowgroup = new stdClass();
$lang->workflowgroup->common = '流程管理';

$lang->mainNav->workflow = "{$lang->navIcons['workflow']} {$lang->workflow->common}|workflow|browseFlow|";
$lang->mainNav->menuOrder[80] = 'workflow';

$lang->semicolon        = '；';
$lang->view             = '查看';
$lang->detail           = '详情';
$lang->basicInfo        = '基本信息';
$lang->extInfo          = '扩展信息';
$lang->chooseUserToMail = '选择要发送提醒的用户...';
$lang->importIcon       = "<i class='icon-import'> </i>";
$lang->exportIcon       = "<i class='icon-export'> </i>";
$lang->determine        = '确定';

$lang->workflow->menu = new stdclass();
$lang->workflow->menu->flow         = array('link' => '工作流|workflow|browseflow|', 'alias' => 'browse', 'subModule' => 'workflowaction,workflowcondition,workflowlabel,workflowlayout,workflowlinkage,workflowhook');
$lang->workflow->menu->flowgroup    = array('link' => "流程管理|workflowgroup|product|", 'alias' => 'browse', 'subModule' => 'workflowgroup,workflowaction,workflowcondition,workflowlabel,workflowlayout,workflowlinkage,workflowhook');
$lang->workflow->menu->datasource   = array('link' => '数据源|workflowdatasource|browse|');
$lang->workflow->menu->workflowrule = array('link' => '验证规则|workflowrule|browse|');

$lang->workflow->menuOrder[5]  = 'flow';
$lang->workflow->menuOrder[10] = 'flowgroup';
$lang->workflow->menuOrder[15] = 'datasource';
$lang->workflow->menuOrder[20] = 'workflowrule';

$lang->workflow->menu->flowgroup['subMenu'] = new stdclass();
$lang->workflow->menu->flowgroup['subMenu']->product = array('link' => "{$lang->productCommon}流程管理|workflowgroup|product|", 'subModule' => 'workflowaction,workflowcondition,workflowlabel,workflowlayout,workflowlinkage,workflowhook');
$lang->workflow->menu->flowgroup['subMenu']->project = array('link' => "{$lang->projectCommon}流程管理|workflowgroup|project|", 'subModule' => 'workflowaction,workflowcondition,workflowlabel,workflowlayout,workflowlinkage,workflowhook');

/* Makes the main menu high light. */
$lang->navGroup->workflow           = 'workflow';
$lang->navGroup->workflowgroup      = 'workflow';
$lang->navGroup->workflowrule       = 'workflow';
$lang->navGroup->workflowaction     = 'workflow';
$lang->navGroup->workflowhook       = 'workflow';
$lang->navGroup->workflowlinkage    = 'workflow';
$lang->navGroup->workflowlayout     = 'workflow';
$lang->navGroup->workflowlabel      = 'workflow';
$lang->navGroup->workflowfield      = 'workflow';
$lang->navGroup->workflowdatasource = 'workflow';
$lang->navGroup->workflowcondition  = 'workflow';
$lang->navGroup->workflowrelation   = 'workflow';
$lang->navGroup->workflowreport     = 'workflow';

/* Init flow module. */
$lang->flow = new stdclass();

/* Add lang from ranzhi. */
$lang->exportAll      = '导出全部记录';
$lang->exportThisPage = '导出本页记录';
$lang->setFileNum     = '记录数';
$lang->setFileType    = '文件类型';
$lang->flowNotRelease = '该工作流还没有发布';
