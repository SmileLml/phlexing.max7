<?php
/**
 * The batchclose view file of ticket module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     ticket
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('batchCloseTip', $batchCloseTip);

formBatchPanel
(
    set::title($lang->ticket->batchClose),
    set::mode('edit'),
    set::data(array_values($tickets)),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('[data-name="closedReason"]', 'closedReasonChange'),
    /* Field of id. */
    formBatchItem
    (
        set::label($lang->idAB),
        set::name('id'),
        set::control('hidden'),
        set::hidden(true)
    ),
    /* Field of id index. */
    formBatchItem
    (
        set::label($lang->idAB),
        set::width('50px'),
        set::name('id'),
        set::control('index')
    ),
    /* Field of title. */
    formBatchItem
    (
        set::width('200px'),
        set::name('title'),
        set::control('static'),
        set::label($lang->ticket->title)
    ),
    /* Field of status. */
    formBatchItem
    (
        set::label($lang->ticket->status),
        set::width('120px'),
        set::name('statusText'),
        set::control('static')
    ),
    /* Field of closedReason. */
    formBatchItem
    (
        set::label($lang->ticket->closedReason),
        set::labelClass('required'),
        set::width('200px'),
        set::name('closedReasonBox'),
        set::control('inputGroup'),
        inputGroup
        (
            picker
            (
                set::name('closedReason'),
                set::items($lang->ticket->closedReasonList),
                set::required(false)
            ),
            picker
            (
                setClass('duplicate-select hidden'),
                set::name('repeatTicket'),
                set::items(array()),
                set::required(true)
            )
        )
    ),
    /* Field of resolution. */
    formBatchItem
    (
        set::label($lang->ticket->resolution),
        set::width('200px'),
        set::name('resolution'),
        set::control('input'),
        set::disabled(true),
        set::hidden(!$showResolution)
    ),
    /* Field of comment. */
    formBatchItem
    (
        set::label($lang->comment),
        set::width('200px'),
        set::name('comment'),
        set::control('input')
    )
);
