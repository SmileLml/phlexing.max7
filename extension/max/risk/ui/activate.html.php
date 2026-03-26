<?php
/**
 * The activate file of risk module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     risk
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->risk->activate));

formPanel
(
    formGroup
    (
        set::label($lang->risk->assignedTo),
        set::name('assignedTo'),
        set::items($users),
        set::value($app->user->account)
    ),
    formGroup
    (
        set::label($lang->risk->activateDate),
        set::name('activateDate'),
        set::control('datePicker'),
        set::value(helper::today())
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::value(''),
        set::rows(6)
    )
);

