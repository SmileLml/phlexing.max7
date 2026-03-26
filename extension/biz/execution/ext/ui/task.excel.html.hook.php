<?php
namespace zin;

global $app;

$lang       = data('lang');
$execution  = data('execution');
$moduleID   = data('moduleID');
$browseType = data('browseType');
$orderBy    = data('orderBy');
/* zin: Define the toolbar on main menu. */
$canCreate      = common::canModify('execution', $execution) && hasPriv('task', 'create');
$canBatchCreate = common::canModify('execution', $execution) && hasPriv('task', 'batchCreate');
$canImport      = common::canModify('execution', $execution) && hasPriv('task', 'import');
$canImportTask  = common::canModify('execution', $execution) && hasPriv('execution', 'importTask');
$canImportBug   = common::canModify('execution', $execution) && hasPriv('execution', 'importBug');

$importItems = array();
if(common::canModify('execution', $execution))
{
    $params          = isset($moduleID) ? "&storyID=0&moduleID=$moduleID" : "";
    $batchCreateLink = createLink('task', 'batchCreate', "executionID={$execution->id}{$params}")  . ($app->tab == 'project' ? '#app=project' : '');
    $createLink      = createLink('task', 'create',      "executionID={$execution->id}{$params}")  . ($app->tab == 'project' ? '#app=project' : '');
    if(commonModel::isTutorialMode())
    {
        $wizardParams   = helper::safe64Encode("executionID={$execution->id}{$params}");
        $taskCreateLink = createLink('tutorial', 'wizard', "module=task&method=create&params=$wizardParams");
    }

    $createItem      = array('text' => $lang->task->create,      'url' => $createLink);
    $batchCreateItem = array('text' => $lang->task->batchCreate, 'url' => $batchCreateLink);

    if($canImport) $importItems[] = array('text' => $lang->task->import, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('task', 'import', "executionID={$execution->id}"));
    if($canImportTask && $execution->multiple) $importItems[] = array('text' => $lang->execution->importTask, 'url' => createLink('execution', 'importTask', "execution={$execution->id}"));
    if($canImportBug && $execution->lifetime != 'ops' && !in_array($execution->attribute, array('request', 'review')))
    {
        $importItems[] = array('text' => $lang->execution->importBug, 'url' => createLink('execution', 'importBug', "execution={$execution->id}"), 'className' => 'importBug', 'data-app' => $execution->multiple ? '' : 'project');
    }
}

$exportItems = array();
if(hasPriv('task', 'export'))         $exportItems[] = array('text' => $lang->task->export,         'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('task', 'export', "execution={$execution->id}&orderBy={$orderBy}&type={$browseType}"));
if(hasPriv('task', 'exportTemplate')) $exportItems[] = array('text' => $lang->task->exportTemplate, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('task', 'exportTemplate', "executionID={$execution->id}"));


$viewType = isset($_COOKIE['taskViewType']) ? $_COOKIE['taskViewType'] : 'tree';
$toolbar  = toolbar
(
    item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(array
        (
            'icon'      => 'list',
            'class'     => 'btn-icon switchButton' . ($viewType == 'tiled' ? ' text-primary' : ''),
            'data-type' => 'tiled',
            'hint'      => $lang->task->viewTypeList['tiled']
        ), array
        (
            'icon'      => 'treeview',
            'class'     => 'switchButton btn-icon' . ($viewType == 'tree' ? ' text-primary' : ''),
            'data-type' => 'tree',
            'hint'      => $lang->task->viewTypeList['tree']
        ))
    ))),
    hasPriv('task', 'report') && empty($execution->isTpl) ? item(set(array
    (
        'icon'     => 'bar-chart',
        'class'    => 'ghost',
        'data-app' => $app->tab,
        'url'      => createLink('task', 'report', "execution={$execution->id}&browseType={$browseType}")
    ))) : null,
    $exportItems ? dropdown(
        btn
        (
            setClass('ghost btn square btn-default'),
            set::icon('export')
        ),
        set::items($exportItems),
        set::placement('bottom-end')
    ) : null,
    $importItems ? dropdown(
        btn
        (
            setClass('ghost btn square btn-default'),
            set::icon('import')
        ),
        set::items($importItems),
        set::placement('bottom-end')
    ) : null,
    $canCreate && $canBatchCreate ? btngroup
    (
        btn(setClass('btn primary createTask-btn'), set::icon('plus'), set::url($createLink), $lang->task->create),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array_filter(array($createItem, $batchCreateItem))),
            set::placement('bottom-end')
        )
    ) : null,
    $canCreate && !$canBatchCreate ? item(set($createItem + array('class' => 'btn primary createTask-btn', 'icon' => 'plus'))) : null,
    $canBatchCreate && !$canCreate ? item(set($batchCreateItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null
);

query('#actionBar')->replaceWith($toolbar);
pageJS('$(function(){$("#mainMenu .toolbar").prop("id", "actionBar"); });');
