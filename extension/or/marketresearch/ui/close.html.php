<?php
/**
* The close file of marketresearch module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
* @package     marketresearch
* @link        https://www.zentao.net
*/
namespace zin;

modalHeader(set::title($lang->marketresearch->close), set::entityText($research->name), set::entityID($research->id));
formPanel
(
    formGroup
    (
        set::label($lang->project->realEnd),
        set::width('1/3'),
        set::control('date'),
        set::name('realEnd'),
        set::required(true),
        set::value(!helper::isZeroDate($research->realEnd) ? $research->realEnd : helper::today())
    ),
    formGroup
    (
        set::label($lang->marketresearch->closedReason),
        set::width('1/3'),
        set::control('picker'),
        set::required(true),
        set::name('closedReason'),
        set::items($lang->marketresearch->reasonList)
    ),
    formGroup
    (
        set::label($lang->comment),
        set::control('editor'),
        set::name('comment')
    ),
    formHidden('status', 'closed'),
    set::submitBtnText($lang->marketresearch->close)
);
hr();
history();
