<?php
/**
* The activate file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Qiyu Xie <xieqiyu@easycorp.ltd>
* @package     demand
* @link        https://www.zentao.net
*/

namespace zin;

modalHeader();

formPanel
(
    formGroup
    (
        set::label($lang->demand->assignedTo),
        set::width('1/3'),
        set::name('assignedTo'),
        set::value($demand->closedBy),
        set::items($users)
    ),
    formGroup
    (
        set::label($lang->comment),
        set::control('editor'),
        set::name('comment')
    ),
    set::submitBtnText($lang->demand->activate)
);

hr();
history();
