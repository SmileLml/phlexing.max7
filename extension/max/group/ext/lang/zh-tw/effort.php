<?php
$lang->resource->my->effort                   = 'effortAction';
$lang->resource->company->effort              = 'companyEffort';
$lang->resource->company->alleffort           = 'allEffort';
$lang->resource->execution->effort            = 'effortAction';
$lang->resource->execution->taskEffort        = 'taskEffort';
$lang->resource->execution->computeTaskEffort = 'computeTaskEffort';

if(!isset($lang->resource->effort)) $lang->resource->effort = new stdclass();
$lang->resource->effort->batchCreate     = 'batchCreate';
$lang->resource->effort->createForObject = 'createForObject';
$lang->resource->effort->edit            = 'edit';
$lang->resource->effort->batchEdit       = 'batchEdit';
$lang->resource->effort->view            = 'view';
$lang->resource->effort->delete          = 'delete';
$lang->resource->effort->export          = 'exportAction';

$lang->resource->user->effort = 'effort';
