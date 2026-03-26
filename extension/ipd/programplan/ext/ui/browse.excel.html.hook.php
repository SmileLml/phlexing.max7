<?php
namespace zin;

$lang      = data('lang');
$project   = data('project');
$projectID = data('projectID');

$canModifyProject = common::canModify('project', $project);

$toolbar = toolbar
(
    btnGroup
    (
        btn(setClass('square switchBtn text-primary'), set::title($lang->programplan->gantt), icon('gantt-alt')),
        btn(setClass('square switchBtn'), set::title($lang->project->bylist), set::url(createLink('project', 'execution', "status=all&projectID=$projectID")), icon('list'))
    ),
    btn(setClass('no-underline text-primary'), set::type('link'), setID('criticalPath'), $lang->execution->gantt->showCriticalPath, set::url('javascript:updateCriticalPath()')),
    btn(setClass('no-underline'), set::type('link'), setID('fullScreenBtn'), set::icon('fullscreen'), $lang->programplan->full),
    dropdown
    (
        btn(set::type('link'), setClass('no-underline'), set::icon('export'), $lang->export),
        set::items(array
        (
            array('text' => $lang->execution->gantt->exportImg, 'url' => 'javascript:exportGantt()'),
            array('text' => $lang->execution->gantt->exportPDF, 'url' => 'javascript:exportGantt("pdf")'),
            $canModifyProject && hasPriv('programplan', 'exportTemplate') ? array('text' => $lang->programplan->exportTemplate, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('programplan', 'exportTemplate', "projectID={$projectID}")) : null
        ))
    ),
    $canModifyProject && hasPriv('programplan', 'import') ? item(set(array
    (
        'icon'        => 'import',
        'text'        => $lang->programplan->importTask,
        'class'       => "no-underline btn btn-link",
        'data-toggle' => 'modal',
        'data-size'   => 'sm',
        'url'         => createLink('programplan', 'import', "projectID={$projectID}")
    ))) : null,

    btn(set::url(createLink('programplan', 'ajaxcustom')), set::icon('cog-outline'), $lang->settings, setClass('no-underline'), set::type('link'), set('data-toggle', 'modal'), set('data-size', 'sm')),
    common::hasPriv('programplan', 'relation') ? btn(set::url(createLink('programplan', 'relation', "projectID={$projectID}")), set::icon('list-alt'), $lang->programplan->setTaskRelation, setClass('no-underline'), set::type('link')) : null,
    (common::canModify('project', $project) && common::hasPriv('programplan', 'create') && empty($product->deleted)) ? btn(set::url(createLink('programplan', 'create', "projectID=$projectID")), set::icon('plus'), $lang->programplan->create, setClass('primary programplan-create-btn')) : null
);

query('#actionBar')->replaceWith($toolbar);
pageJS('$(function(){$("#mainMenu .toolbar").prop("id", "actionBar"); });');
