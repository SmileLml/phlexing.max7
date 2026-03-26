<?php
/**
 * The close file of risk module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     risk
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->risk->close));

formPanel
(
    set::id('risk-close-form'),
    formGroup
    (
        set::label($lang->risk->resolvedBy),
        set::name('resolvedBy'),
        set::items($users),
        set::value($app->user->account)
    ),
    formGroup
    (
        set::label($lang->risk->closedDate),
        set::name('actualClosedDate'),
        set::control('datePicker'),
        set::value(helper::today())
    ),
    formGroup
    (
        set::label($lang->risk->resolution),
        set::name('resolution'),
        set::control('editor'),
        set::value(''),
        set::rows(6)
    )
);

