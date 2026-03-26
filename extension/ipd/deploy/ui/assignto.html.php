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
    set::title($lang->deploy->assignTo)
);

formPanel
(
    formGroup
    (
        set::width('1/2'),
        set::label($lang->deploy->assignedTo),
        picker(set::name('assignedTo'), set::items($users), set::value($step->assignedTo))
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::row(10)
    )
);
