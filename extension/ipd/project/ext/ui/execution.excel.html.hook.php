<?php
namespace zin;

global $app, $config;
if($config->vision == 'lite') return;

$lang       = data('lang');
$project    = data('project');
$projectID  = data('projectID');
$productID  = data('productID');
$isStage    = data('isStage');
$orderBy    = data('orderBy');
$status     = data('status');
$stageMode  = array('waterfall', 'waterfallplus', 'ipd');

$createLink       = $isStage ? createLink('programplan', 'create', "projectID={$projectID}&productID={$productID}") : createLink('execution', 'create', "projectID={$projectID}");
$canModifyProject = common::canModify('project', $project);

$exportItems = array();
if(hasPriv('execution', 'export'))           $exportItems[] = array('text' => $lang->export, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('execution', 'export', "status={$status}&productID={$productID}&orderBy={$orderBy}&from=project"));
if(hasPriv('programplan', 'exportTemplate') && in_array($project->model, $stageMode)) $exportItems[] = array('text' => $lang->programplan->exportTemplate, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('programplan', 'exportTemplate', "projectID={$projectID}"));

$toolbar = toolbar
(
    in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')) && in_array($config->edition, array('max', 'ipd')) ? btnGroup
    (
        a(setClass('btn square'), icon('gantt-alt'), set::title($lang->programplan->gantt), set::href(createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=gantt"))),
        a(setClass('btn square text-primary'), icon('list'), set::title($lang->project->bylist))
    ) : null,
    $exportItems ? dropdown
    (
        btn(set::type('link'), setClass('no-underline'), set::icon('export'), $lang->export),
        set::items($exportItems),
        set::placement('bottom-end')
    ) : null,
    $canModifyProject && hasPriv('programplan', 'import') && in_array($project->model, $stageMode) ? item(set(array
    (
        'icon'        => 'import',
        'text'        => $lang->programplan->importTask,
        'class'       => "no-underline btn btn-link",
        'data-toggle' => 'modal',
        'data-size'   => 'sm',
        'url'         => createLink('programplan', 'import', "projectID={$projectID}")
    ))) : null,
    $canModifyProject && hasPriv('programplan', 'create') && $isStage && empty($product->deleted) ? item(set(array
    (
        'icon'  => 'plus',
        'text'  => $lang->programplan->create,
        'class' => "primary create-execution-btn",
        'url'   => $createLink
    ))) : null,
    $canModifyProject && hasPriv('execution', 'create') && !$isStage && $project->model != 'agileplus' ? item(set(array
    (
        'icon'  => 'plus',
        'text'  => $isStage ? $lang->programplan->create : $lang->execution->create,
        'class' => "primary create-execution-btn",
        'url'   => $createLink
    ))) : null,
    $canModifyProject && hasPriv('execution', 'create') && !$isStage && $project->model == 'agileplus' ?  btngroup(
        setClass('create-execution-btn'),
        btn(setClass('btn primary'), set::icon('plus'), set::url($createLink), $lang->execution->create),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items
            (
                array('text' => $lang->execution->create, 'url' => $createLink),
                array('text' => $lang->project->createKanban, 'url' => createLink('execution', 'create', "projectID={$projectID}&executionID=0&copyExecutionID=&planID=0&confirm=no&productID=0&extra=type=kanban"))
            ),
            set::placement('bottom-end')
        )
    ) : null
);

query('#actionBar')->replaceWith($toolbar);
pageJS('$(function(){$("#mainMenu .toolbar").prop("id", "actionBar"); });');

