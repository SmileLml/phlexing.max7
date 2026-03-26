<?php
/**
 * The assignTo view file of risk module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     risk
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->risk->assignTo));

formPanel
(
    set::submitBtnText($lang->risk->assignedTo),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->risk->assignedTo),
        set::name("assignedTo"),
        set::items($users),
        set::value($risk->assignedTo)
    ),
    formGroup
    (
        set::name("comment"),
        set::label($lang->comment),
        set::control("editor")
    )
);
