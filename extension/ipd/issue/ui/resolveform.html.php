<?php
/**
 * The resolveform view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

form
(
    setID('resolveForm'),
    set::submitBtnText($lang->issue->resolve),
    set::actions(array('submit')),
    set::url(createLink('issue', 'resolve', "issueID={$issue->id}&from={$from}")),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->issue->resolution),
        picker
        (
            set::name('resolution'),
            set::items($lang->issue->resolveMethods),
            set::value('resolved'),
            set::required(true),
            on::change('getSolutions')
        )
    ),
    formGroup(set::label($lang->issue->resolutionComment), set::name('resolutionComment'), set::control('editor'), set::value($issue->resolutionComment)),
    formGroup(set::width('1/3'), set::label($lang->issue->resolvedBy), set::name('resolvedBy'), set::items($users), set::value($app->user->account)),
    formGroup(set::width('1/3'), set::label($lang->issue->resolvedDate), set::name('resolvedDate'), set::control('datePicker'), set::value(date('Y-m-d')))
);
