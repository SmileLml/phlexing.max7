<?php
$lang->view    = 'Xem';
$lang->detail     = 'Chi tiết';
$lang->basicInfo  = 'Thông tin cơ bản';
$lang->extInfo    = 'Extention Info';
$lang->chooseUserToMail = 'Chọn users to notify...';
$lang->importIcon    = "<i class='icon-import'> </i>";
$lang->exportIcon    = "<i class='icon-export'> </i>";
$lang->determine        = 'Determine';

/* The subModule options makes the module menu high light. */
$lang->menu->workflow = 'Workflow|workflow|browseFlow';
$lang->menuOrder[39]  = 'workflow';

/* Workflow */
$lang->workflow = new stdclass();

$lang->workflow->menu = new stdclass();
$lang->workflow->menu->flow    = array('link' => 'Flows|workflow|browseflow|', 'alias' => 'browse', 'subModule' => 'workflowaction,workflowcondition,workflowlabel,workflowlayout,workflowlinkage,workflowhook');
$lang->workflow->menu->datasource = array('link' => 'Datasource|workflowdatasource|browse|');
$lang->workflow->menu->rule    = array('link' => 'Rules|workflowrule|browse|');

$lang->workflow->menuOrder[5]  = 'flow';
$lang->workflow->menuOrder[10] = 'database';
$lang->workflow->menuOrder[15] = 'datasource';
$lang->workflow->menuOrder[20] = 'rule';

/* Workflowaction */
$lang->workflowaction = new stdclass();
$lang->workflowaction->menu   = $lang->workflow->menu;
$lang->workflowaction->menuOrder = $lang->workflow->menuOrder;

/* Workflowcondition */
$lang->workflowcondition = new stdclass();
$lang->workflowcondition->menu   = $lang->workflow->menu;
$lang->workflowcondition->menuOrder = $lang->workflow->menuOrder;

/* Workflowdatasource */
$lang->workflowdatasource = new stdclass();
$lang->workflowdatasource->menu   = $lang->workflow->menu;
$lang->workflowdatasource->menuOrder = $lang->workflow->menuOrder;

/* Workflowfield */
$lang->workflowfield = new stdclass();
$lang->workflowfield->menu   = $lang->workflow->menu;
$lang->workflowfield->menuOrder = $lang->workflow->menuOrder;

/* Workflowlabel */
$lang->workflowlabel = new stdclass();
$lang->workflowlabel->menu   = $lang->workflow->menu;
$lang->workflowlabel->menuOrder = $lang->workflow->menuOrder;

/* Workflowlayout */
$lang->workflowlayout = new stdclass();
$lang->workflowlayout->menu   = $lang->workflow->menu;
$lang->workflowlayout->menuOrder = $lang->workflow->menuOrder;

/* Workflowlinkage */
$lang->workflowlinkage = new stdclass();
$lang->workflowlinkage->menu   = $lang->workflow->menu;
$lang->workflowlinkage->menuOrder = $lang->workflow->menuOrder;

/* Workflowhook */
$lang->workflowhook = new stdclass();
$lang->workflowhook->menu   = $lang->workflow->menu;
$lang->workflowhook->menuOrder = $lang->workflow->menuOrder;

/* Workflowrule */
$lang->workflowrule = new stdclass();
$lang->workflowrule->menu   = $lang->workflow->menu;
$lang->workflowrule->menuOrder = $lang->workflow->menuOrder;

/* Makes the main menu high light. */

/* Init flow module. */
$lang->flow = new stdclass();

/* Add lang from ranzhi. */
$lang->exportAll   = 'Xuất tất cả';
$lang->exportThisPage = 'Xuất This Page';
$lang->setFileNum  = 'File Number';
$lang->setFileType = 'File loại';
$lang->flowNotRelease = 'The workflow has not been published.';
