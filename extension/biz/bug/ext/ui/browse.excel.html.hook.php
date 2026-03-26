<?php
namespace zin;

$product         = data('product');
$branch          = data('branch');
$browseType      = data('browseType');
$currentModuleID = data('currentModuleID');
$lang            = data('lang');

if(!isonlybody())
{
    $canBeChanged   = common::canModify('product', $product);
    $canCreate      = false;
    $canBatchCreate = false;
    if($canBeChanged)
    {
        $canCreate      = hasPriv('bug', 'create');
        $canBatchCreate = hasPriv('bug', 'batchCreate');

        $selectedBranch  = $branch != 'all' ? $branch : 0;
        $createLink      = createLink('bug', 'create', "productID={$product->id}&branch=$selectedBranch&extra=moduleID=$currentModuleID");
        $batchCreateLink = createLink('bug', 'batchCreate', "productID={$product->id}&branch=$branch&executionID=0&moduleID=$currentModuleID");
        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("productID={$product->id}&branch=$branch&extra=moduleID=$currentModuleID");
            $createLink   = createLink('tutorial', 'wizard', "module=bug&method=create&params=$wizardParams");
        }

        $createItem      = array('text' => $lang->bug->create,      'url' => $createLink);
        $batchCreateItem = array('text' => $lang->bug->batchCreate, 'url' => $batchCreateLink);
    }

    $canExport          = hasPriv('bug', 'export');
    $canExportTemplate  = hasPriv('bug', 'exportTemplate');
    $exportItem         = array('text' => $lang->bug->export,         'data-toggle' => 'modal', 'data-size' => 'sm','url' => createLink('bug', 'export', "productID={$product->id}&browseType={$browseType}"), 'innerClass' => 'export-btn');
    $exportTemplateItem = array('text' => $lang->bug->exportTemplate, 'data-toggle' => 'modal', 'data-size' => 'sm','url' => createLink('bug', 'exportTemplate', "productID={$product->id}&branch={$branch}"));

    query('#actionBar')->replaceWith(
        toolbar
        (
            hasPriv('bug', 'report') ? item(set(array
            (
                'icon'  => 'bar-chart',
                'text'  => $lang->bug->report->common,
                'class' => 'ghost',
                'url'   => createLink('bug', 'report', "productID=$product->id&browseType=$browseType&branch=$branch&module=$currentModuleID")
            ))) : null,
            $canExport && $canExportTemplate ? dropdown
            (
                btn
                (
                    setClass('btn ghost dropdown-toggle'),
                    set::icon('export'),
                    $lang->export
                ),
                set::items(array($exportItem, $exportTemplateItem)),
                set::placement('bottom-end')
            ) : null,
            $canExport && !$canExportTemplate ? item(set($exportItem + array('class' => 'btn ghost', 'icon' => 'export'))) : null,
            $canExportTemplate && !$canExport ? item(set($exportTemplateItem + array('class' => 'btn ghost', 'icon' => 'export'))) : null,
            $canBeChanged && hasPriv('bug', 'import') ? item
            (
                setClass('ghost'),
                set::icon('import'),
                set::url(createLink('bug', 'import', "productID={$product->id}&branch={$branch}")),
                setData(array('toggle' => 'modal')),
                set::text($lang->bug->import)
            ) : null,
            $canCreate && $canBatchCreate ? btngroup
            (
                btn(setClass('btn primary create-bug-btn'), set::icon('plus'), set::url($createLink), $lang->bug->create),
                dropdown
                (
                    btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
                    set::items(array($createItem, $batchCreateItem)),
                    set::placement('bottom-end')
                )
            ) : null,
            $canCreate && !$canBatchCreate ? item(set($createItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null,
            $canBatchCreate && !$canCreate ? item(set($batchCreateItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null
        )
    );
    pageJS('$(function(){$("#mainMenu .toolbar").prop("id", "actionBar"); });');
}
