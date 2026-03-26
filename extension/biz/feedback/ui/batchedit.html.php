<?php
/**
 * The batchEdit view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('modules', $moduleList);

formBatchPanel
(
    set::title($lang->feedback->batchEdit),
    set::mode('edit'),
    set::data(array_values($feedbacks)),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('[data-name="product"]', 'changeProduct'),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('50px')
    ),
    formBatchItem
    (
        set::name('product'),
        set::label($lang->feedback->product),
        set::control('picker'),
        set::items($products),
        set::required(true),
        set::width('200px')
    ),
    formBatchItem
    (
        set::name('module'),
        set::label($lang->feedback->module),
        set::control('picker'),
        set::items(array('0' => '/')),
        set::value('0'),
        set::width('200px')
    ),
    formBatchItem
    (
        set::name('title'),
        set::label($lang->feedback->title),
        set::control('input'),
        set::required(true)
    ),
    formBatchItem
    (
        set::name('assignedTo'),
        set::label($lang->feedback->assignedTo),
        set::control('picker'),
        set::items($users),
        set::width('160px')
    )
);

render();

