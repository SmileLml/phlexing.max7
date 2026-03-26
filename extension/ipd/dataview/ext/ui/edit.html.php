<?php
/**
 * The edit view file of dataview module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@easycorp.ltd>
 * @package     dataview
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

formPanel
(
    formGroup
    (
        set::label($lang->dataview->group),
        set::required(true),
        select
        (
            set::name('group'),
            set::items($groups),
            set::value($dataview->group)
        )
    ),
    formGroup
    (
        set::label($lang->dataview->name),
        set::required(true),
        input
        (
            set::name('name'),
            set::value($dataview->name)
        )
    ),
    set::submitBtnText($lang->save)
);

render();
