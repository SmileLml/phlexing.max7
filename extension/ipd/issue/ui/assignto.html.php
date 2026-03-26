<?php
/**
 * The assignto view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->issue->assignTo));

formPanel
(
    set::submitBtnText($lang->issue->assignTo),
    formGroup
    (
        set::width('1/3'),
        set::name('assignedTo'),
        set::label($lang->issue->assignedTo),
        set::value($issue->assignedTo),
        set::items($users)
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6)
    )
);
