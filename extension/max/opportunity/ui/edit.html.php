<?php
/**
 * The edit view file of opportunity module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     opportunity
 * @link        https://www.zentao.net
 */
namespace zin;

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($opportunity->id),
            set::level(1),
            set::text($opportunity->name),
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
            set::title($lang->opportunity->name),
            set::required(true),
            input(set::name('name'), set::value($opportunity->name))
        ),
        section
        (
            set::title($lang->opportunity->desc),
            editor
            (
                set::name('desc'),
                $opportunity->desc && isHTML($opportunity->desc) ? html($opportunity->desc) : $opportunity->desc
            )
        ),
        section
        (
            set::title($lang->opportunity->prevention),
            editor
            (
                set::name('prevention'),
                $opportunity->prevention && isHTML($opportunity->prevention) ? html($opportunity->prevention) : $opportunity->prevention
            )
        ),
        section
        (
            set::title($lang->opportunity->resolution),
            editor
            (
                set::name('resolution'),
                $opportunity->resolution && isHTML($opportunity->resolution) ? html($opportunity->resolution) : $opportunity->resolution
            )
        )
    ),
    history(),
    detailSide
    (
        set::isForm(true),
        tableData
        (
            setClass('mt-5 legendBasicInfoTable'),
            set::title($lang->opportunity->legendBasicInfo),
            item
            (
                set::name($lang->opportunity->source),
                formGroup
                (
                    set::name('source'),
                    set::items($lang->opportunity->sourceList),
                    set::value($opportunity->source)
                )
            ),
            item
            (
                set::name($lang->opportunity->type),
                formGroup
                (
                    set::name('type'),
                    set::items($lang->opportunity->typeList),
                    set::value($opportunity->type)
                )
            ),
            item
            (
                set::name($lang->opportunity->strategy),
                formGroup
                (
                    set::name('strategy'),
                    set::items($lang->opportunity->strategyList),
                    set::value($opportunity->strategy)
                )
            ),
            item
            (
                set::name($lang->opportunity->status),
                formGroup
                (
                    set::name('status'),
                    set::items($lang->opportunity->statusList),
                    set::value($opportunity->status)
                )
            ),
            !empty($project->multiple) ? item
            (
                set::name($lang->opportunity->execution),
                formGroup
                (
                    set::name('execution'),
                    set::items($executions),
                    set::value($opportunity->execution)
                )
            ) : null,
            item
            (
                set::name($lang->opportunity->impact),
                formGroup
                (
                    set::control(array('control' => 'picker', 'required' => true)),
                    set::name('impact'),
                    set::items($lang->opportunity->impactList),
                    set::value($opportunity->impact),
                    on::change('computeIndex')
                )
            ),
            item
            (
                set::name($lang->opportunity->chance),
                formGroup
                (
                    set::control(array('control' => 'picker', 'required' => true)),
                    set::name('chance'),
                    set::items($lang->opportunity->impactList),
                    set::value($opportunity->chance),
                    on::change('computeIndex')
                )
            ),
            item
            (
                set::name($lang->opportunity->ratio),
                formGroup
                (
                    set::name('ratio'),
                    set::value($opportunity->ratio),
                    set::readonly(true)
                )
            ),
            item
            (
                set::name($lang->opportunity->pri),
                formGroup
                (
                    set::control('priPicker'),
                    set::name('pri'),
                    set::items($lang->opportunity->priList),
                    set::value($opportunity->pri),
                    set::readonly(true)
                )
            ),
            item
            (
                set::name($lang->opportunity->identifiedDate),
                formGroup
                (
                    set::control('datePicker'),
                    set::name('identifiedDate'),
                    helper::isZeroDate($opportunity->identifiedDate) ? null : set::value($opportunity->identifiedDate)
                )
            ),
            item
            (
                set::name($lang->opportunity->plannedClosedDate),
                formGroup
                (
                    set::name('plannedClosedDate'),
                    helper::isZeroDate($opportunity->plannedClosedDate) ? null : set::value($opportunity->plannedClosedDate)
                )
            ),
            item
            (
                set::name($lang->opportunity->actualClosedDate),
                formGroup
                (
                    set::control('datePicker'),
                    set::name('actualClosedDate'),
                    helper::isZeroDate($opportunity->actualClosedDate) ? null : set::value($opportunity->actualClosedDate)
                )
            ),
            item
            (
                set::name($lang->opportunity->assignedTo),
                formGroup
                (
                    set::name('assignedTo'),
                    set::items($users),
                    set::value(empty($opportunity->assignedTo) ? '' : $opportunity->assignedTo)
                )
            )
        ),
        tableData
        (
            setClass('mt-4'),
            set::title($lang->opportunity->legendLifeTime),
            item
            (
                set::name($lang->opportunity->lastCheckedBy),
                formGroup
                (
                    set::name('lastCheckedBy'),
                    set::items($users),
                    set::value(empty($opportunity->lastCheckedBy) ? '' : $opportunity->lastCheckedBy)
                )
            ),
            item
            (
                set::name($lang->opportunity->lastCheckedDate),
                formGroup
                (
                    set::control('datePicker'),
                    set::name('lastCheckedDate'),
                    helper::isZeroDate($opportunity->lastCheckedDate) ? null : set::value($opportunity->lastCheckedDate)
                )
            ),
            item
            (
                set::name($lang->opportunity->hangupedBy),
                formGroup
                (
                    set::name('hangupedBy'),
                    set::items($users),
                    set::value(empty($opportunity->hangupedBy) ? '' : $opportunity->hangupedBy)
                )
            ),
            item
            (
                set::name($lang->opportunity->hangupedDate),
                formGroup
                (
                    set::control('datePicker'),
                    set::name('hangupedDate'),
                    helper::isZeroDate($opportunity->hangupedDate) ? null : set::value($opportunity->hangupedDate)
                )
            ),
            item
            (
                set::name($lang->opportunity->canceledBy),
                formGroup
                (
                    set::name('canceledBy'),
                    set::items($users),
                    set::value(empty($opportunity->canceledBy) ? '' : $opportunity->canceledBy)
                )
            ),
            item
            (
                set::name($lang->opportunity->cancelReason),
                formGroup
                (
                    set::name('cancelReason'),
                    set::items($lang->opportunity->cancelReasonList),
                    set::value($opportunity->cancelReason)
                )
            ),
            item
            (
                set::name($lang->opportunity->canceledDate),
                formGroup
                (
                    set::control('datePicker'),
                    set::name('canceledDate'),
                    helper::isZeroDate($opportunity->canceledDate) ? null : set::value($opportunity->canceledDate)
                )
            ),
            item
            (
                set::name($lang->opportunity->closedBy),
                formGroup
                (
                    set::name('closedBy'),
                    set::items($users),
                    set::value(empty($opportunity->closedBy) ? '' : $opportunity->closedBy)
                )
            ),
            item
            (
                set::name($lang->opportunity->closedDate),
                formGroup
                (
                    set::control('datePicker'),
                    set::name('closedDate'),
                    helper::isZeroDate($opportunity->closedDate) ? null : set::value($opportunity->closedDate)
                )
            ),
            item
            (
                set::name($lang->opportunity->activatedBy),
                formGroup
                (
                    set::name('activatedBy'),
                    set::items($users),
                    set::value(empty($opportunity->activatedBy) ? '' : $opportunity->activatedBy)
                )
            ),
            item
            (
                set::name($lang->opportunity->activatedDate),
                formGroup
                (
                    set::control('datePicker'),
                    set::name('activatedDate'),
                    helper::isZeroDate($opportunity->activatedDate) ? null : set::value($opportunity->activatedDate)
                )
            ),
            item
            (
                set::name($lang->opportunity->resolvedBy),
                formGroup
                (
                    set::name('resolvedBy'),
                    set::items($users),
                    set::value(empty($opportunity->resolvedBy) ? '' : $opportunity->resolvedBy)
                )
            )
        )
    )
);
