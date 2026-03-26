<?php
$lang->workflowrelation->common           = 'Workflow Relation';
$lang->workflowrelation->admin            = 'Admin Relation';
$lang->workflowrelation->createForeignKey = 'Create';

$lang->workflowrelation->prev       = 'Prev Flow';
$lang->workflowrelation->next       = 'Next Flow';
$lang->workflowrelation->action     = 'Action';
$lang->workflowrelation->foreignKey = 'Foreign Key';

$lang->workflowrelation->relationActionList['one2one']   = 'One prev flow create one next flow';
$lang->workflowrelation->relationActionList['one2many']  = 'One prev flow create many next flows';
$lang->workflowrelation->relationActionList['many2one']  = 'Many prev flow create one next flow';
$lang->workflowrelation->relationActionList['many2many'] = 'Many prev flow create many next flows';

$lang->workflowrelation->tableWidth = 1000;

/* Tips */
$lang->workflowrelation->tips = new stdclass();
$lang->workflowrelation->tips->foreignKey = '<strong>Foreign Key</strong> a field of current flow used to display data related to the prev flow. Field set as a foreign key should use a <strong>drop-down</strong> or a <strong>radio</strong> as controls. If not, a <strong>drop-down</strong> menu will be set by default and data source as a <strong>prev flow</strong>.';

/* Error */
$lang->workflowrelation->error = new stdclass();
$lang->workflowrelation->error->existNextField = 'The field has been used in the relations of <strong> %s </strong>.';
