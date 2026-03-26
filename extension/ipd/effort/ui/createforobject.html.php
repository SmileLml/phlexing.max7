<?php
/**
 * The createforobject view file of effort module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     effort
 * @link        https://www.zentao.net
 */
namespace zin;

if(isInModal()) set::id("modal-record-hours-{$objectID}");
modalHeader
(
    set::title(''),
    set::entityText($modalTitle),
    set::entityID($objectID),
    $objectType == 'task' ? to::suffix
    (
        span
        (
            setClass('flex gap-x-2 mx-3'),
            $lang->task->estimate,
            span
            (
                setClass('label secondary-pale'),
                $task->estimate . $lang->task->suffixHour
            )
        ),
        span
        (
            setClass('flex gap-x-2 pr-4'),
            $lang->task->consumed,
            span
            (
                setClass('label warning-pale'),
                span
                (
                    setID('totalConsumed'),
                    $task->consumed
                ),
                $lang->task->suffixHour
            )
        )
    ) : null
);

if($efforts)
{
    $effortRows = array();
    foreach($efforts as $effort)
    {
        $effortRows[] = h::tr
        (
            h::td($effort->date),
            h::td(zget($users, $effort->account)),
            h::td(html($effort->work), setClass('break-all')),
            h::td("{$effort->consumed} {$lang->task->suffixHour}"),
            h::td
            (
                common::hasPriv('effort', 'edit') ? a
                (
                    icon('edit'),
                    setClass('btn ghost toolbar-item square size-sm text-primary'),
                    set::href(createLink('effort', 'edit', "effortID={$effort->id}")),
                    setData('toggle', 'modal')
                ) : null,
                common::hasPriv('effort', 'delete') ? a(icon('trash'), setClass('btn ghost toolbar-item square size-sm text-primary ajax-submit'), set::href(createLink('effort', 'delete', "effortID={$effort->id}&comfirim=yew&from=create")), set('data-confirm', $lang->effort->confirmDelete)) : null
            )
        );
    }

    h::table
    (
        setClass('table condensed bordered'),
        h::tr
        (
            h::th
            (
                width('120px'),
                $lang->effort->date
            ),
            h::th
            (
                width('120px'),
                $lang->effort->account
            ),
            h::th($lang->task->work),
            h::th
            (
                width('60px'),
                $lang->effort->consumed
            ),
            h::th
            (
                width('80px'),
                $lang->actions
            )
        ),
        $effortRows
    );
}

formBatchPanel
(
    set::shadow(!isAjaxRequest('modal')),
    set::actions(array('submit')),
    set::maxRows(5),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('input'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('objectType'),
        set::control('input'),
        set::value($objectType),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('objectID'),
        set::control('input'),
        set::value($objectID),
        set::hidden(true)
    ),
    formBatchItem(set::name('idText'), set::label($lang->idAB), set::control('index'), set::width('32px')),
    formBatchItem
    (
        set::name('dates'),
        set::label($lang->effort->date),
        set::width('120px'),
        set::control(array('control' => 'date', 'id' => '$GID')),
        set::value(helper::today())
    ),
    formBatchItem
    (
        set::name('work'),
        set::required(true),
        set::label($lang->effort->work),
        set::width('auto'),
        set::control('textarea')
    ),
    formBatchItem
    (
        set::name('consumed'),
        set::required(true),
        set::label($lang->effort->consumed),
        set::width('80px'),
        set::control
        (
            array(
                'type' => 'inputControl',
                'suffix' => $lang->task->suffixHour,
                'suffixWidth' => 20
            )
        )
    )
);
