<?php
/**
 * The bugform view file of issue module of ZenTaoPMS.
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
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->product),
            inputGroup
            (
                picker
                (
                    set::width('2/3'),
                    set::name('product'),
                    set::items($products),
                    set::value($productID),
                    set::required(true),
                    on::change('loadProduct')
                ),
                $product->type != 'normal' && isset($products[$productID]) ? picker
                (
                    set::width('120px'),
                    set::name('branch'),
                    set::items($branches),
                    set::value($branch),
                    set::required(true),
                    on::change('loadBranch')
                ) : null
            )
        ),
        formGroup
        (
            set::label($lang->bug->module),
            set::required(strpos(",{$config->bug->create->requiredFields},", ",module,") !== false),
            modulePicker
            (
                set::manageLink(createLink('tree', 'browse', "rootID={$productID}&view=bug&currentModuleID=0&branch={$branch}")),
                set::items($moduleOptionMenu),
                set::value($moduleID),
                set::required(true),
                on::change('loadModuleRelated')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->project),
            set::required(strpos(",{$config->bug->create->requiredFields},", ",project,") !== false),
            picker
            (
                set::name('project'),
                set::items($projects),
                set::value($projectID),
                on::change('loadProductExecutions')
            )
        ),
        formGroup
        (
            set::width('1/2'),
            setClass(empty($project->multiple) ? 'hidden' : ''),
            set::label($lang->bug->execution),
            set::required(strpos(",{$config->bug->create->requiredFields},", ",execution,") !== false),
            picker
            (
                set::name('execution'),
                set::items($executions),
                on::change('loadExecutionRelated')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->openedBuild),
            set::required(true),
            inputGroup
            (
                picker
                (
                    set::name('openedBuild[]'),
                    set::items($builds),
                    set::value($buildID),
                    set::multiple(true),
                    set::menu(array('checkbox' => true)),
                    setData(array('items' => count($builds)))
                ),
                span
                (
                    setClass('input-group-btn'),
                    a
                    (
                        setClass('btn'),
                        on::click('loadAllBuilds'),
                        $lang->bug->loadAll
                    )
                )
            )
        ),
        formGroup
        (
            set::label($lang->bug->browser),
            set::required(strpos(",{$config->bug->create->requiredFields},", ",browser,") !== false || strpos(",{$config->bug->create->requiredFields},", ",os,") !== false),
            inputGroup
            (
                picker
                (
                    set::name('browser'),
                    set::items($lang->bug->browserList)
                ),
                $lang->bug->os,
                picker
                (
                    set::name('os'),
                    set::items($lang->bug->osList)
                )
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->bug->lblAssignedTo),
            set::name('assignedTo'),
            set::items($users)
        ),
        formGroup
        (
            set::label($lang->bug->deadline),
            set::required(strpos(",{$config->bug->create->requiredFields},", ",deadline,") !== false),
            set::control('datePicker'),
            set::name('deadline'),
            set::value($issue->deadline)
        )
    ),
    formGroup
    (
        set::label($lang->bug->title),
        set::required(true),
        inputGroup
        (
            input
            (
                set::name('title'),
                set::value($issue->title)
            ),
            $lang->bug->type,
            picker(set::width('225px'), set::name('type'), set::items($lang->bug->typeList)),
            $lang->bug->pri,
            priPicker
            (
                set::width('180px'),
                set::name('pri'),
                set::items($lang->bug->priList),
                set::value($issue->pri)
            )
        )
    ),
    formGroup
    (
        set::label($lang->bug->steps),
        set::required(strpos(",{$config->bug->create->requiredFields},", ",steps,") !== false),
        set::control('editor'),
        set::name('steps'),
        set::value($issue->desc)
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->issue->resolvedBy),
            set::name('resolvedBy'),
            set::items($users),
            set::value($app->user->account)
        ),
        formGroup
        (
            set::label($lang->issue->resolvedDate),
            datePicker
            (
                set::name('resolvedDate'),
                set::value(date('Y-m-d'))
            )
        )
    )
);
