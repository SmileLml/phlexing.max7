<?php
$lang->nc->browse  = 'Non Conformity List';
$lang->nc->common  = 'Non conformity';
$lang->nc->create  = 'Create';
$lang->nc->edit    = 'Edit';
$lang->nc->delete  = 'Delete';
$lang->nc->view    = 'View';
$lang->nc->resolve = 'Resolve';
$lang->nc->close   = 'Close';
$lang->nc->export  = 'Export Data';

$lang->nc->exportAction = 'Export non conformity';

$lang->nc->assignTo     = 'Assign';
$lang->nc->activate     = 'Active';
$lang->nc->noAssigned   = 'Unassigned';
$lang->nc->id           = 'ID';
$lang->nc->auditplan    = 'Auditplan';
$lang->nc->object       = 'Object';
$lang->nc->listID       = 'Checklist';
$lang->nc->title        = 'Title';
$lang->nc->desc         = 'Description';
$lang->nc->type         = 'Type';
$lang->nc->status       = 'Status';
$lang->nc->severity     = 'Severity';
$lang->nc->deadline     = 'Deadline';
$lang->nc->resolvedBy   = 'Resolved By';
$lang->nc->resolution   = 'Resolution';
$lang->nc->resolvedDate = 'ResolvedDate';
$lang->nc->closedBy     = 'Closed By';
$lang->nc->closedDate   = 'ClosedDate';
$lang->nc->assignedTo   = 'Assigned To';
$lang->nc->createdBy    = 'Created By';
$lang->nc->createdDate  = 'CreatedDate';
$lang->nc->execution    = $lang->execution->common;
$lang->nc->activateBy   = 'ActivatedBy';
$lang->nc->activateDate = 'ActivatedDate';
$lang->nc->pageSummary  = 'Total: %total%, Active: %active%.';

$lang->nc->basicInfo     = 'Basic Information';
$lang->nc->confirmDelete = 'Do you want to delete this bug?';

$lang->nc->severityList[0] = '';
$lang->nc->severityList[1] = '1';
$lang->nc->severityList[2] = '2';
$lang->nc->severityList[3] = '3';

$lang->nc->statusList['active']       = 'Activate';
$lang->nc->statusList['resolved']     = 'Resolved';
$lang->nc->statusList['closed']       = 'Closed';

$lang->nc->typeList[''] = '';

$lang->nc->resolutionList['']           = '';
$lang->nc->resolutionList['bydesign']   = 'As Designed';
$lang->nc->resolutionList['external']   = 'External';
$lang->nc->resolutionList['fixed']      = 'Resolved';
$lang->nc->resolutionList['notrepro']   = 'Irreproducible';
$lang->nc->resolutionList['postponed']  = 'Postponed';
$lang->nc->resolutionList['willnotfix'] = "Won't Fix";

$lang->nc->featureBar['browse']['all']          = 'All';
$lang->nc->featureBar['browse']['unclosed']     = 'Unclosed';
$lang->nc->featureBar['browse']['assignedtome'] = 'Assigned To Me';
$lang->nc->featureBar['browse']['assignedbyme'] = 'Assigned By Me';

$lang->nc->action = new stdclass();
$lang->nc->action->resolved = array('main' => '$date, resolved by <strong>$actor</strong> ，<strong>$extra</strong>。', 'extra' => 'resolutionList');
$lang->nc->action->closed   = array('main' => '$date, closed by <strong>$actor</strong> 。');
