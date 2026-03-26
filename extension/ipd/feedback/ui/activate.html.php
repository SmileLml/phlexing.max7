<?php
/**
 * The activate view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

formPanel(formGroup
(
    set::label($lang->feedback->assignedTo),
    set::width('1/2'),
    picker
    (
        set::name('assignedTo'),
        set::items($users),
        set::value($assignedTo)
    )
), formGroup
(
    set::label($lang->comment),
    set::name('comment'),
    set::control('editor'),
    set::rows(6)
));

history();
