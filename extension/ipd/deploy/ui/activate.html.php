<?php
/**
 * The activate view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->deploy->activate)
);

formPanel
(
    formRow
    (
        formGroup
        (
            set::label($lang->deploy->lblBeginEnd),
            set::required(true),
            inputGroup
            (
                datetimePicker
                (
                    set::name('begin'),
                    set::value(substr($deploy->begin, 0, 16)),
                    set::placeholder($lang->deploy->begin)
                ),
                span
                (
                    '~',
                    set::className('input-group-addon')
                ),
                datetimePicker
                (
                    set::name('end'),
                    set::value(substr($deploy->end, 0, 16)),
                    set::placeholder($lang->deploy->end)
                )
            )
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::row(10)
    )
);
