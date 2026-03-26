<?php
/**
 * The batchedit view file of effort module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     effort
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('shadowProducts', $shadowProducts);

foreach($efforts as $effort)
{
    $efforts[$effort->id]->objectType = $effort->objectType . '_' . $effort->objectID;
}

formBatchPanel
(
    set::title($lang->effort->batchEdit),
    set::mode('edit'),
    set::data(array_values($efforts)),
    set::url(createLink('effort', 'batchEdit', 'from=batchEdit')),
    on::change('[data-name="objectType"]', 'setLeftInput'),
    set::onRenderRow(jsRaw('renderRowData')),
    /* Field of id index. */
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('50px')
    ),
    /* Field of id. */
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true)
    ),
    /* Field of id. */
    formBatchItem
    (
        set::name('objectID'),
        set::label($lang->effort->objectID),
        set::control('hidden'),
        set::hidden(true)
    ),
    /* Field of product. */
    $config->vision != 'lite' ? formBatchItem
    (
        set::name('product'),
        set::label($lang->effort->product),
        set::control('picker'),
        set::multiple(true),
        set::items($products),
        set::width('160px')
    ) : formBatchItem
    (
        set::name('product'),
        set::label($lang->effort->product),
        set::control('hidden'),
        set::hidden(true)
    ),
    /* Field of execution. */
    formBatchItem
    (
        set::name('execution'),
        set::label($lang->effort->execution),
        set::width('160px'),
        set::control(array('control' => 'picker', 'items' => $executions, 'popWidth' => 'auto', 'maxItemsCount' => 50))
    ),
    /* Field of date. */
    formBatchItem
    (
        set::name('date'),
        set::label($lang->effort->date),
        set::control('datePicker'),
        set::width('160px')
    ),
    /* Field of work. */
    formBatchItem
    (
        set::name('work'),
        set::label($lang->effort->work),
        set::control('input'),
        set::width('160px')
    ),
    /* Field of consumed. */
    formBatchItem
    (
        set::name('consumed'),
        set::label($lang->effort->consumed . '(' . $lang->effort->hour . ')'),
        set::control('input'),
        set::width('160px')
    ),
    /* Field of objectType. */
    formBatchItem
    (
        set::name('objectType'),
        set::label($lang->effort->objectType),
        set::control('picker'),
        set::items(isset($typeList) ? $typeList : array()),
        set::width('160px')
    ),
    /* Field of left. */
    formBatchItem
    (
        set::name('left'),
        set::label($lang->effort->left . '(' . $lang->effort->hour . ')'),
        set::control('input'),
        set::width('160px')
    )
);
