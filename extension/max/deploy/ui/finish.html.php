<?php
/**
 * The finish view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->deploy->finish)
);

formPanel
(
    formGroup(set::width('1/2'), set::label($lang->deploy->result), set::required(true), set::name('result'), set::control('picker'), set::items($lang->deploy->resultList)),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->deploy->lblBeginEnd),
            set::required(true),
            inputGroup
            (
                datetimePicker(set::name('begin'), set::value($deploy->begin), set::placeholder($lang->deploy->begin)),
                span('~', set::className('input-group-addon')),
                datetimePicker(set::name('end'), set::value($deploy->end), set::placeholder($lang->deploy->end))
            )
        )
    ),
    formGroup
    (
        set::label($lang->deploy->members),
        set::width('1/2'),
        set::name('members'),
        set::control('picker'),
        set::items($users),
        set::multiple(true),
        set::value($deploy->members)
    ),
    formGroup
    (
        set::label($lang->comment),
        set::control('editor'),
        set::name('desc'),
        set::row(10)
    )
);
