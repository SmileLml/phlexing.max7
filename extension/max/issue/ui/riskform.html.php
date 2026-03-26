<?php
/**
 * The riskform view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('projectID', $projectID);
jsVar('issueID', $issue->id);
jsVar('from', $from);
form
(
    setID('resolveForm'),
    set::submitBtnText($lang->issue->resolve),
    set::actions(array('submit')),
    set::url(createLink('issue', 'resolve', "issueID={$issue->id}&from={$from}")),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->issue->resolution),
        picker
        (
            set::name('resolution'),
            set::items($lang->issue->resolveMethods),
            set::value($resolution),
            set::required(true),
            on::change('getSolutions')
        )
    ),
    formGroup
    (
        set::label($lang->risk->name),
        set::name('name'),
        set::value($issue->title),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->risk->source),
        set::name('source'),
        set::items($lang->risk->sourceList)
    ),
    formGroup
    (
        set::label($lang->risk->category),
        set::name('category'),
        set::items($lang->risk->categoryList)
    ),
    formGroup
    (
        set::label($lang->risk->strategy),
        set::name('strategy'),
        set::items($lang->risk->strategyList)
    ),
    formGroup
    (
        set::label($lang->issue->resolvedBy),
        set::name('resolvedBy'),
        set::items($users),
        set::value($app->user->account)
    ),
    formGroup
    (
        set::label($lang->issue->resolvedDate),
        set::control('datePicker'),
        set::name('resolvedDate'),
        set::value(date('Y-m-d'))
    ),
    formGroup
    (
        formHidden('execution', $issue->execution)
    )
);
