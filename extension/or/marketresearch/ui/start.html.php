<?php
/**
 * The start view file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->marketresearch->start), set::entityText($research->name), set::entityID($research->id));
formPanel
(
    formGroup
    (
        set::width('1/2'),
        set::label($lang->project->realBegan),
        set::name('realBegan'),
        set::control('date'),
        set::value(helper::today())
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('6')
        )
    )
);
hr();
history();
