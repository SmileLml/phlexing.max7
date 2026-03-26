<?php
$lang->navIcons['workflow']     = "<i class='icon icon-flow'></i>";
$lang->navIconNames['workflow'] = "flow";

/* Workflow */
$lang->workflow = new stdclass();
$lang->workflow->common = 'Workflow';

$lang->workflowgroup = new stdClass();
$lang->workflowgroup->common = 'Workflow Template';

$lang->mainNav->workflow = "{$lang->navIcons['workflow']} {$lang->workflow->common}|workflow|browseFlow|";
$lang->mainNav->menuOrder[80] = 'workflow';

$lang->semicolon        = ':';
$lang->view             = 'View';
$lang->detail           = 'Detail';
$lang->basicInfo        = 'Basic Info';
$lang->extInfo          = 'Extention Info';
$lang->chooseUserToMail = 'Choose users to notify...';
$lang->importIcon       = "<i class='icon-import'> </i>";
$lang->exportIcon       = "<i class='icon-export'> </i>";
$lang->determine        = 'Determine';

$lang->workflow->menu = new stdclass();
$lang->workflow->menu->flow         = array('link' => 'Flows|workflow|browseflow|', 'alias' => 'browse', 'subModule' => 'workflowaction,workflowcondition,workflowlabel,workflowlayout,workflowlinkage,workflowhook');
$lang->workflow->menu->flowgroup    = array('link' => "Flow Template|workflowgroup|product|", 'alias' => 'browse', 'subModule' => 'workflowgroup,workflowaction,workflowcondition,workflowlabel,workflowlayout,workflowlinkage,workflowhook');
$lang->workflow->menu->datasource   = array('link' => 'Datasource|workflowdatasource|browse|');
$lang->workflow->menu->workflowrule = array('link' => 'Rules|workflowrule|browse|');

$lang->workflow->menuOrder[5]  = 'flow';
$lang->workflow->menuOrder[10] = 'flowgroup';
$lang->workflow->menuOrder[15] = 'datasource';
$lang->workflow->menuOrder[20] = 'workflowrule';

$lang->workflow->menu->flowgroup['subMenu'] = new stdclass();
$lang->workflow->menu->flowgroup['subMenu']->product = array('link' => "{$lang->productCommon} Template|workflowgroup|product|", 'subModule' => 'workflowaction,workflowcondition,workflowlabel,workflowlayout,workflowlinkage,workflowhook');
$lang->workflow->menu->flowgroup['subMenu']->project = array('link' => "{$lang->projectCommon} Template|workflowgroup|project|", 'subModule' => 'workflowaction,workflowcondition,workflowlabel,workflowlayout,workflowlinkage,workflowhook');

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
$lang->exportAll      = 'Export All';
$lang->exportThisPage = 'Export This Page';
$lang->setFileNum     = 'File Number';
$lang->setFileType    = 'File Type';
$lang->flowNotRelease = 'The workflow has not been published.';
