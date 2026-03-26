<?php
/**
 * The activatestage view file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->marketresearch->activateStage),
    set::entityText($stage->name),
    set::entityID($stage->id)
);

formPanel
(
    set::submitBtnText($lang->marketresearch->activateStage),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->execution->beginAndEnd),
            inputGroup
            (
                datePicker
                (
                    set::name('begin'),
                    set::value($newBegin)
                ),
                $lang->execution->to,
                datePicker
                (
                    set::name('end'),
                    set::value($newEnd)
                )
            )
        ),
        formGroup
        (
            set::width('1/2'),
            setClass('items-center'),
            checkbox(
                set::name('readjustTask'),
                set::text($lang->execution->readjustTask),
                set::value(1),
                set::rootClass('ml-4')
            )
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('6')
        )
    )
);
hr();
history();
