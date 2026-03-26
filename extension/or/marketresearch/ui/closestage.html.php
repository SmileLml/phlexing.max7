<?php
/**
 * The closestage file of marketresearch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@easycorp.ltd>
 * @package     marketresearch
 * @version     $Id: closestage.html.php 4769 2023-09-06 07:24:21Z
 * @link        http://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->marketresearch->closeStage),
    set::entityText($stage->name),
    set::entityID($stage->id)
);

formPanel
(
    formGroup
    (
        set::label($lang->marketresearch->realEnd),
        datePicker
        (
            set::name('realEnd'),
            set::value(helper::isZeroDate($stage->realEnd) ? helper::today() : $stage->realEnd)
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        editor(set::name('comment'))
    ),
    set::submitBtnText($lang->marketresearch->closeStage)
);

hr();
history();
