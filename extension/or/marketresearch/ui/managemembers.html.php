<?php
/**
 * The manageMembers view file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('users', $users);
jsVar('roles', $roles);
jsVar('researchID', $research->id);
jsVar('oldAccountList', array_keys($currentMembers));

/* zin: Define the set::module('team') feature bar on main menu. */
featureBar
(
    set::current('all'),
    set::linkParams("projectID={$research->id}"),
    div(setClass('select-dept-box ml-4'), span
    (
        set::className('flex items-center dept-title'),
        $lang->execution->selectDept
    ), picker
    (
        set::id('dept'),
        set::name('dept'),
        set::value($dept),
        set::items($depts),
        set('data-placeholder', $lang->execution->selectDeptTitle),
        set::width('200px'),
        on::change('setDeptUsers')
    ))
);

$memberTR = array();
$i        = 0;
foreach($teamMembers as $member)
{
    if($member->memberType == 'dept' && !isset($users[$member->account])) continue;
    $memberTR[] = h::tr
        (
            $member->memberType == 'default' ? h::td(
                input
                (
                    set::id("realnames{$i}"),
                    set::name("realnames[$i]"),
                    set::value($member->realname),
                    set::readonly(true)
                ),
                input
                (
                    set::id("account{$i}"),
                    set::name("account[$i]"),
                    set::value($member->account),
                    set::type('hidden')
                )
            ) : h::td(
                picker
                (
                    set::id("account{$i}"),
                    set::name("account[$i]"),
                    set::value($member->account),
                    set::items($users),
                    set('data-max-list-count', $config->maxCount),
                    set('onchange', "setRole(event, '{$i}')")
                )
            ),
            h::td
            (
                input
                (
                    set::id("role{$i}"),
                    set::name("role[$i]"),
                    set::value($member->role)
                )
            ),
            h::td
            (
                input
                (
                    set::id("days{$i}"),
                    set::name("days[$i]"),
                    set::value($member->days)
                )
            ),
            h::td
            (
                input
                (
                    set::id("hours{$i}"),
                    set::name("hours[$i]"),
                    set::value($member->hours)
                )
            ),
            h::td
            (
                radioList
                (
                    set::id("limited{$i}"),
                    setClass('justify-center'),
                    set::name("limited[$i]"),
                    set::items($lang->team->limitedList),
                    set::value($member->limited),
                    set::inline(true)
                )
            ),
            h::td
            (
                setClass('actions-list'),
                btnGroup
                (
                    setClass('justify-center'),
                    set::items(array(
                        array('class' => 'btn btn-link text-gray', 'icon' => 'plus', 'onclick' => 'addItem(this)'),
                        array('class' => 'btn btn-link text-gray', 'icon' => 'trash', 'onclick' => 'deleteItem(this)')
                    ))
                )
            )
        );

    if(in_array($member->memberType, array('default', 'dept'))) unset($users[$member->account]);
    $i ++;
}

jsVar('+itemIndex', $i);

$i = '_i';
h::table
(
    set::className('hidden'),
    set::id('addItem'),
    h::tr(
        h::td(
            picker
            (
                set::id("account{$i}"),
                set::name("account[$i]"),
                set::items($users),
                set('data-max-list-count', $config->maxCount),
                set('onchange', "setRole(event, '{$i}')")
            )
        ),
        h::td
        (
            input
            (
                set::id("role{$i}"),
                set::name("role[$i]")
            )
        ),
        h::td
        (
            input
            (
                set::id("days{$i}"),
                set::name("days[$i]"),
                set::value($research->days)
            )
        ),
        h::td
        (
            input
            (
                set::id("hours{$i}"),
                set::name("hours[$i]"),
                set::value($config->execution->defaultWorkhours)
            )
        ),
        h::td
        (
            radioList
            (
                set::id("limited{$i}"),
                setClass('justify-center'),
                set::name("limited[$i]"),
                set::items($lang->team->limitedList),
                set::value('no'),
                set::inline(true)
            )
        ),
        h::td
        (
            setClass('actions-list'),
            btnGroup
            (
                setClass('justify-center'),
                set::items(array(
                    array('class' => 'btn btn-link text-gray', 'icon' => 'plus', 'onclick' => 'addItem(this)'),
                    array('class' => 'btn btn-link text-gray', 'icon' => 'trash', 'onclick' => 'deleteItem(this)')
                ))
            )
        )
    )
);

div
(
    setClass('main-content'),
    form
    (
        setClass('main-form'),
        set::id('teamForm'),
        h::table
        (
            set::className('table table-form'),
            h::thead
            (
                h::tr
                (
                    h::th
                    (
                        $lang->team->account,
                        set::width('240px')
                    ),
                    h::th
                    (
                        $lang->team->role,
                        set::width('240px')
                    ),
                    h::th
                    (
                        $lang->team->days,
                        set::width('76px')
                    ),
                    h::th
                    (
                        $lang->team->hours,
                        set::width('136px')
                    ),
                    h::th
                    (
                        $lang->team->limited,
                        set::width('96px')
                    ),
                    h::th
                    (
                        $lang->actions,
                        set::width('48px')
                    )
                )
            ),
            h::tbody
            (
                $memberTR,
                input
                (
                    set::type('hidden'),
                    set::name('removeExecution'),
                    set::value('no')
                )
            )
        )
    )
);
