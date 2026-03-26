<?php
/**
 * The activate view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->issue->activate));

formPanel
(
    set::submitBtnText($lang->issue->activate),
    formGroup
    (
        set::name('assignedTo'),
        set::label($lang->issue->assignedTo),
        set::items($users)
    ),
    formGroup
    (
        set::label($lang->issue->activateDate),
        set::name('activateDate'),
        set::control('datetimePicker'),
        set::value(helper::now())
    )
);
