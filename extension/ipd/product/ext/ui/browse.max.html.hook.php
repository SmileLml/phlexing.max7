<?php
namespace zin;

global $lang, $config, $app;

$product    = data('product');
$projectID  = data('projectID');
$branch     = data('branch');
$orderBy    = data('orderBy');
$browseType = data('browseType');
$storyType  = data('storyType');

if(empty($product)) return false;

$canBeChanged = common::canModify('product', $product);
if(!empty($project)) $canBeChanged = $canBeChanged && common::canModify('project', $project);

$isProjectStory = $app->tab == 'project';

$moduleName = $isProjectStory ? 'projectstory' : $storyType;

$canExport          = hasPriv($moduleName, 'export');
$canExportTemplate  = hasPriv($moduleName, 'exportTemplate');
$exportLink         = createLink($moduleName, 'export', "productID={$product->id}&orderBy={$orderBy}&executionID={$projectID}&browseType={$browseType}&storyType={$storyType}");
$exportTemplateLink = createLink($moduleName, 'exportTemplate', "productID={$product->id}&branch={$branch}&type={$storyType}");
$importLink         = createLink($moduleName, 'import', "productID={$product->id}&branch={$branch}&type={$storyType}&projectID={$projectID}");
$importFromLibLink  = createLink('projectstory', 'importFromLib', "projectID={$projectID}&productID={$product->id}&libID=0&storyType={$storyType}");
$exportItem         = array('text' => $lang->story->export,         'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => $exportLink);
$exportTemplateItem = array('text' => $lang->story->exportTemplate, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => $exportTemplateLink);
$importItems = array();
if($canBeChanged && hasPriv($moduleName, 'import')) $importItems[] = array('text' => $lang->story->import, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => $importLink);
if($canBeChanged && hasPriv('projectstory', 'importFromLib') && $app->tab == 'project') $importItems[] = array('text' => $lang->projectstory->importFromLib, 'url' => $importFromLibLink);

query('#exportBtn')->replaceWith(
    $canExport && $canExportTemplate ? dropdown
    (
        btn
        (
            setID('exportBtn'),
            setClass('btn ghost square dropdown-toggle'),
            set::icon('export')
        ),
        set::items(array($exportItem, $exportTemplateItem)),
        set::placement('bottom-end')
    ) : null,
    $canExport && !$canExportTemplate ? btn(setID('exportBtn'), setClass('toolbar-item ghost btn btn-default'), set::url($exportLink),         set::icon('export'), setData('toggle', 'modal'), setData('size', 'sm'), $lang->story->export) : null,
    $canExportTemplate && !$canExport ? btn(setID('exportBtn'), setClass('toolbar-item ghost btn btn-default'), set::url($exportTemplateLink), set::icon('export'), setData('toggle', 'modal'), setData('size', 'sm'), $lang->story->exportTemplate) : null,
    $canBeChanged && common::hasPriv('story', 'import') ? dropdown
    (
        btn
        (
            setID('importBtn'),
            setClass('btn ghost square dropdown-toggle'),
            set::icon('import')
        ),
        set::items($importItems),
        set::placement('bottom-end')
    ): null
);
