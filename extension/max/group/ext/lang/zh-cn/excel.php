<?php
$lang->resource->testcase->import         = 'importCaseAction';
$lang->resource->testcase->exportTemplate = 'exportTemplate';

$lang->resource->bug->import         = 'importCase';
$lang->resource->bug->exportTemplate = 'exportTemplate';

if(!isset($lang->resource->task)) $lang->resource->task = new stdclass();
$lang->resource->task->import         = 'import';
$lang->resource->task->exportTemplate = 'exportTemplate';

if(!isset($lang->resource->story)) $lang->resource->story = new stdclass();
$lang->resource->story->import         = 'importCase';
$lang->resource->story->exportTemplate = 'exportTemplate';

if(!isset($lang->resource->projectstory)) $lang->resource->projectstory = new stdclass();
$lang->resource->projectstory->import         = 'importCase';
$lang->resource->projectstory->exportTemplate = 'exportTemplate';

if(!isset($lang->resource->programplan)) $lang->resource->programplan = new stdclass();
$lang->resource->programplan->import         = 'importTask';
$lang->resource->programplan->exportTemplate = 'exportTemplate';

if($config->URAndSR)
{
    if(!isset($lang->resource->requirement)) $lang->resource->requirement = new stdclass();
    $lang->resource->requirement->import         = 'importCase';
    $lang->resource->requirement->exportTemplate = 'exportTemplate';
}

if($config->enableER)
{
    if(!isset($lang->resource->epic)) $lang->resource->epic = new stdclass();
    $lang->resource->epic->import         = 'importCase';
    $lang->resource->epic->exportTemplate = 'exportTemplate';
}

$lang->resource->user->import = 'import';
