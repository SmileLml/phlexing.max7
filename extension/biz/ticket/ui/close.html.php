<?php
/**
 * The close view file of ticket module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     ticket
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

formPanel
(
    setID('ticketCloseForm'),
    formGroup
    (
        set::label($lang->ticket->closedReason),
        set::required(true),
        picker
        (
            on::change()->toggleClass('#ticketCloseForm .repeatBox', 'hidden', 'target.value !== "repeat"')->toggleClass('#ticketCloseForm .resolvedBox', 'hidden', 'target.value !== "commented"'),
            set::name('closedReason'),
            set::items($lang->ticket->closedReasonList)
        )
    ),
    formGroup
    (
        setClass('hidden repeatBox'),
        set::label($lang->ticket->repeatTicket),
        set::required(true),
        set::control(array('control' => 'picker', 'required' => false)),
        set::name('repeatTicket'),
        set::items($tickets)
    ),
    formGroup
    (
        setClass('hidden resolvedBox'),
        set::label($lang->ticket->resolvedBy),
        set::required(true),
        set::name('resolvedBy'),
        set::items($users),
        set::value($app->user->account)
    ),
    formGroup
    (
        setClass('hidden resolvedBox'),
        set::label($lang->ticket->resolvedDate),
        set::required(true),
        set::name('resolvedDate'),
        set::control('datePicker')
    ),
    formGroup
    (
        setClass('hidden resolvedBox'),
        set::label($lang->files),
        fileSelector()
    ),
    formGroup
    (
        setClass('hidden resolvedBox'),
        set::label($lang->ticket->resolution),
        set::required(true),
        set::name('resolution'),
        set::control('editor')
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor')
    )
);

history();

render();
