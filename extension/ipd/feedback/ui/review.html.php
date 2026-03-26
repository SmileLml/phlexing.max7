<?php
/**
 * The review view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader();

jsVar('openedBy', $feedback->openedBy);
jsVar('assignedTo', $assignedTo ? $assignedTo : $feedback->assignedTo);

formPanel
(
    set::id('reviewFeedbackForm'),
    formGroup
    (
        set::label($lang->feedback->reviewedDateAB),
        set::width('1/3'),
        datePicker
        (
            set::name('reviewedDate'),
            set::value(helper::today())
        )
    ),
    formGroup
    (
        set::label($lang->feedback->reviewResultAB),
        set::width('1/3'),
        set::required(true),
        picker
        (
            set::name('result'),
            set::items($lang->feedback->reviewResultList),
            on::change('resultChange')
        )
    ),
    formGroup
    (
        setClass('assignedToBox hidden'),
        set::label($lang->feedback->assignedTo),
        set::width('1/3'),
        picker
        (
            set::name('assignedTo'),
            set::items($users)
        )
    ),
    formGroup
    (
        set::label($lang->feedback->reviewedByAB),
        picker
        (
            set::name('reviewedBy[]'),
            set::items($users),
            set::value($app->user->account),
            set::multiple(true)
        )
    ),
    formGroup
    (
        set::label($lang->feedback->reviewOpinion),
        set::name('comment'),
        set::control('editor'),
        set::rows(6)
    ),
    formHidden('status', $feedback->status)
);
history();
