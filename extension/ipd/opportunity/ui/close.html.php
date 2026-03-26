<?php
/**
 * The close view file of opportunity module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     opportunity
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->opportunity->close));
formPanel
(
    set::id('opportunity-close-form'),
    formGroup
    (
        set::label($lang->opportunity->resolvedBy),
        set::width('1/2'),
        set::name('resolvedBy'),
        set::control(array('control' => 'picker', 'required' => true)),
        set::items($users),
        set::value($app->user->account)
    ),
    formGroup
    (
        set::label($lang->opportunity->closedDate),
        set::width('1/2'),
        set::name('actualClosedDate'),
        set::control('datePicker'),
        set::value(helper::today())
    ),
    formGroup
    (
        set::label($lang->opportunity->resolution),
        set::name('resolution'),
        set::control('editor'),
        set::value(''),
        set::rows(6)
    )
);
