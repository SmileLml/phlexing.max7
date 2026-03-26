<?php
/**
 * The stepview view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

detailHeader
(
    isAjaxRequest('modal') ? to::prefix() : '',
    to::title
    (
        entityLabel(set(array('entityID' => $step->id, 'level' => 1, 'text' => $step->title))),
        $step->deleted == 1 ? label(setClass('danger-outline'), $lang->deleted) : null
    )
);

detailBody
(
    setID('stepViewPage'),
    sectionList
    (
        section
        (
            setClass('flex-1 mr-4'),
            tableData
            (
                set::useTable(false),
                item
                (
                    set::trClass('border-b pb-4'),
                    set::name($lang->deploy->content),
                    $step->content
                )
            )
        ),
        section
        (
            setClass('w-1/3'),
            set::title($lang->deploy->lblBasic),
            tableData
            (
                item
                (
                    set::name($lang->deploy->assignedTo),
                    $step->assignedTo ? zget($users, $step->assignedTo) . $lang->at . $step->assignedDate : ''
                ),
                item
                (
                    set::name($lang->deploy->status),
                    zget($lang->deploy->stepStatusList, $step->status)
                ),
                item
                (
                    set::name($lang->deploy->createdBy),
                    zget($users, $step->createdBy) . $lang->at . $step->createdDate
                ),
                item
                (
                    set::name($lang->deploy->finishedBy),
                    $step->finishedBy ? zget($users, $step->finishedBy) . $lang->at . $step->finishedDate : ''
                )
            )
        )
    ),
    history(set::objectType('deploystep'), set::objectID($step->id), set::commentUrl(createLink('action', 'comment', array('objectType' => 'deploystep', 'objectID' => $step->id)))),
    floatToolbar
    (
        set::object($step),
        to::prefix
        (
            btn
            (
                setData(array('load' => 'modal')),
                set::icon('icon-hand-right'),
                set::className('ghost text-white'),
                set::url(createLink('deploy', 'assignTo', "stepID=$step->id")),
                $lang->deploy->assignTo
            )
        )
    )
);
