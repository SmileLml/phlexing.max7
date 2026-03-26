<?php
/**
 * The edit view of researchtask of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hucheng Tang <tanghucheng@easycorp.ltd>
 * @package     researchtask
 * @link        https://www.zentao.net
 */

namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

detailHeader
(
    to::prefix(null),
    to::title
    (
        entityLabel
        (
            set::entityID($task->id),
            set::level(1),
            set::text($task->name),
            set::reverse(true)
        )
    )
);

detailBody
(
    set::isForm(true),
    sectionList
    (
        section
        (
            set::title($lang->researchtask->name),
            set::required(true),
            formGroup
            (
                inputControl
                (
                    input(set::name('name'), set::value($task->name), set::placeholder($lang->researchtask->name)),
                    to::suffix
                    (
                        colorPicker
                        (
                            set::heading($lang->researchtask->colorTag),
                            set::name('color'),
                            set::value($task->color),
                            set::syncColor('#name')
                        )
                    ),
                    set::suffixWidth('35')
                )
            )
        ),
        section
        (
            set::title($lang->researchtask->desc),
            editor
            (
                set::name('desc'),
                $task->desc && isHTML($task->desc) ? html($task->desc) : $task->desc
            )
        ),
        section
        (
            set::title($lang->files),
            $task->files ? fileList
            (
                set::files($task->files),
                set::fieldset(false),
                set::showEdit(true),
                set::showDelete(true)
            ) : null,
            fileSelector()
        ),
        formHidden('lastEditedDate', helper::isZeroDate($task->lastEditedDate) ? '' : $task->lastEditedDate)
    ),
    history
    (
        set::objectID($task->id),
        set::objectType('task')
    ),
    detailSide
    (
        set::isForm(true),
        tableData
        (
            setClass('mt-5'),
            set::title($lang->researchtask->legendBasic),
            item
            (
                set::name($lang->researchtask->execution),
                formGroup
                (
                    picker
                    (
                        set::name('execution'),
                        set::value($task->execution),
                        set::items($stages),
                        on::change('loadAll')
                    )
                )
            ),
            $task->parent >= 0 && empty($task->team) ? item
            (
                set::name($lang->researchtask->parent),
                picker
                (
                    set::name('parent'),
                    set::value($task->parent),
                    set::items($tasks)
                )
            ) : formHidden('parent', $task->parent),
            item
            (
                set::name($lang->researchtask->assignedTo),
                inputGroup
                (
                    div
                    (
                        setClass('flex grow'),
                        picker(setID('assignedTo'), setClass('w-full'), set::name('assignedTo'), set::value($task->assignedTo), set::items(data('users')))
                    )
                )
            ),
            formHidden('type', $task->type),
            empty($task->children) ? item
            (
                set::name($lang->researchtask->status),
                picker
                (
                    set::name('status'),
                    set::value($task->status),
                    set::items($lang->researchtask->statusList),
                    set::required(true)
                )
            ) : formHidden('status', $task->status),
            item
            (
                set::name($lang->researchtask->pri),
                set::required(strpos(",{$this->config->researchtask->edit->requiredFields},", ",pri,") !== false),
                formGroup
                (
                    priPicker(set::name('pri'), set::value($task->pri), set::items($lang->researchtask->priList))
                )
            ),
            item
            (
                set::name($lang->researchtask->progress),
                progresscircle
                (
                    set::percent($task->progress),
                    set::circleColor('var(--color-success-500)'),
                    set::circleBg('var(--color-border)'),
                    set::circleWidth(1)
                )
            ),
            item
            (
                set::name($lang->researchtask->keywords),
                input(set::name('keywords'), set::value($task->keywords))
            ),
            item
            (
                set::name($lang->researchtask->mailto),
                mailto(set::items($users), set::value($task->mailto))
            )
        ),
        tableData
        (
            setClass('mt-4'),
            set::title($lang->researchtask->legendEffort),
            item
            (
                set::name($lang->researchtask->estStarted),
                set::required(strpos(",{$this->config->researchtask->edit->requiredFields},", ",estStarted,") !== false),
                formGroup
                (
                    datePicker
                    (
                        set::name('estStarted'),
                        helper::isZeroDate($task->estStarted) ? null : set::value($task->estStarted)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->deadline),
                set::required(strpos(",{$this->config->researchtask->edit->requiredFields},", ",deadline,") !== false),
                formGroup
                (
                    datePicker
                    (
                        set::name('deadline'),
                        helper::isZeroDate($task->deadline) ? null : set::value($task->deadline)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->estimate),
                set::required(strpos(",{$this->config->researchtask->edit->requiredFields},", ",estimate,") !== false),
                formGroup
                (
                    inputControl
                    (
                        input
                        (
                            set::name('estimate'),
                            set::value($task->estimate),
                            !empty($task->team) || !empty($task->children) ? set::readonly(true) : null
                        ),
                        to::suffix($lang->researchtask->suffixHour),
                        set::suffixWidth(20)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->consumed),
                inputGroup
                (
                    setClass('items-center'),
                    span
                    (
                        setClass('span-text'),
                        setID('consumedSpan'),
                        $task->consumed . $lang->researchtask->suffixHour
                    ),
                    common::hasPriv('task', 'recordWorkhour') ? btn
                    (
                        setClass('ghost text-primary', !empty($task->children) ? 'disabled' : true),
                        icon('time'),
                        set::href(inlink('recordWorkhour', "id={$task->id}&from=edittask")),
                        setData('toggle', 'modal')
                    ) : null,
                    formHidden('consumed', $task->consumed)
                )
            ),
            item
            (
                set::name($lang->researchtask->left),
                formGroup
                (
                    inputControl
                    (
                        input
                        (
                            set::name('left'),
                            set::value($task->left),
                            !empty($task->team) || !empty($task->children) ? set::readonly(true) : null
                        ),
                        to::suffix($lang->researchtask->suffixHour),
                        set::suffixWidth(20)
                    )
                )
            )
        ),
        tableData
        (
            setClass('mt-4'),
            set::title($lang->researchtask->legendLife),
            item
            (
                set::name($lang->researchtask->realStarted),
                formGroup
                (
                    datetimePicker
                    (
                        set::name('realStarted'),
                        set::value(helper::isZeroDate($task->realStarted) ? '' : $task->realStarted)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->finishedBy),
                formGroup
                (
                    picker
                    (
                        set::name('finishedBy'),
                        set::value($task->finishedBy),
                        set::items($members)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->finishedDate),
                formGroup
                (
                    datetimePicker
                    (
                        set::name('finishedDate'),
                        set::value(helper::isZeroDate($task->finishedDate) ? '' : $task->finishedDate)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->canceledBy),
                formGroup
                (
                    picker
                    (
                        set::name('canceledBy'),
                        set::value($task->canceledBy),
                        set::items($users)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->canceledDate),
                formGroup
                (
                    datetimePicker
                    (
                        set::name('canceledDate'),
                        set::value(helper::isZeroDate($task->canceledDate) ? '' : $task->canceledDate)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->closedBy),
                formGroup
                (
                    picker
                    (
                        set::name('closedBy'),
                        set::value($task->closedBy),
                        set::items($users)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->closedReason),
                formGroup
                (
                    picker
                    (
                        set::name('closedReason'),
                        set::value($task->closedReason),
                        set::items($lang->researchtask->reasonList)
                    )
                )
            ),
            item
            (
                set::name($lang->researchtask->closedDate),
                formGroup
                (
                    datetimePicker
                    (
                        set::name('closedDate'),
                        set::value(helper::isZeroDate($task->closedDate) ? '' : $task->closedDate)
                    )
                )
            )
        )
    )
);
