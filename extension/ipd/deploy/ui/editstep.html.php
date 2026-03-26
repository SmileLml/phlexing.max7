<?php
/**
 * The editstep view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->deploy->editStep)
);

formPanel
(
    formGroup
    (
        set::width('1/2'),
        set::label($lang->deploy->assignedTo),
        picker
        (
            set::name('assignedTo'),
            set::items($users),
            set::value($step->assignedTo)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->deploy->status),
        picker
        (
            set::name('status'),
            set::items($lang->deploy->statusList),
            set::value($step->status)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->deploy->finishedBy),
        picker
        (
            set::name('finishedBy'),
            set::items($users),
            set::value($step->finishedBy)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->deploy->title),
        set::name('title'),
        set::value($step->title),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->deploy->content),
        set::name('content'),
        set::control('textarea'),
        set::value($step->content)
    )
);
