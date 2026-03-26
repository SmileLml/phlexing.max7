<?php
/**
 * The view file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('confirmDeleteTip', $lang->project->confirmDelete);

$membersDom = array();
if(!empty($research->PM))
{
    $user = isset($userList[$research->PM]) ? $userList[$research->PM] : null;
    if($user)
    {
        $membersDom[] = div
        (
            setClass('w-1/8 center-y'),
            avatar(setClass('primary-outline'), set::text($user->realname), set::src($user->avatar)),
            span(setClass('my-2'), $user->realname),
            span(setClass('text-gray'), $lang->project->PM)
        );
    }
    unset($teamMembers[$research->PM]);
}

$memberCount = count($membersDom);
foreach($teamMembers as $teamMember)
{
    if($memberCount >= 7) break;

    $user = isset($userList[$teamMember->account]) ? $userList[$teamMember->account] : null;
    if(!$user) continue;
    $membersDom[] = div
    (
        setClass('w-1/8 center-y'),
        avatar(set::text($user->realname), set::src($user->avatar)),
        span(setClass('my-2'), $user->realname),
        span(setClass('text-gray'), $lang->project->member)
    );
    $memberCount ++;
}

if(common::hasPriv('marketresearch', 'manageMembers'))
{
    $membersDom[] = a
    (
        setClass('w-1/8 center-y cursor-pointer'),
        set::href(createLink('marketresearch', 'manageMembers', "researchID={$research->id}")),
        avatar
        (
            setClass('mb-2'),
            set::foreColor('var(--color-primary-500-rgb)'),
            set::background('var(--color-primary-50)'),
            icon('plus')
        ),
        $lang->project->manage
    );
}

$status = $this->processStatus('marketresearch', $research);

row
(
    setClass('w-full'),
    cell
    (
        setClass('main w-2/3'),
        div
        (
            setClass('flex-auto canvas flex p-4 basic'),
            div
            (
                setClass('text-center w-1/3 flex flex-col justify-center items-center progressBox'),
                div
                (
                    set('class', 'chart pie-chart'),
                    echarts
                    (
                        set::color(array('#2B80FF', '#E3E4E9')),
                        set::width(120),
                        set::height(120),
                        set::series(array(
                        array
                        (
                            'type'      => 'pie',
                            'radius'    => array('80%', '90%'),
                            'itemStyle' => array('borderRadius' => '40'),
                            'label'     => array('show' => false),
                            'data'      => array($research->progress, 100 - $research->progress)
                        )))
                    ),
                    div
                    (
                        set::className('pie-chart-title text-center'),
                        div(span(set::className('text-2xl font-bold'), $research->progress . '%')),
                        div
                        (
                            span
                            (
                                setClass('text-sm text-gray'),
                                $lang->allProgress,
                                btn
                                (
                                    set::size('sm'),
                                    set::icon('help'),
                                    setClass('ghost form-label-hint text-gray-300 ml-2'),
                                    toggle::tooltip(array('title' => $lang->execution->progressTip, 'className' => 'text-gray border border-gray-300', 'type' => 'white', 'placement' => 'right'))
                                )
                            )
                        )
                    )
                )
            ),
            div
            (
                setClass('flex-none w-2/3'),
                div
                (
                    setClass('flex items-center'),
                    label
                    (
                        setClass('label rounded-full ring-gray-400 gray-300-pale'),
                        $research->id
                    ),
                    span
                    (
                        setClass('text-md font-bold ml-2 clip'),
                        set::title($research->name),
                        $research->name
                    ),
                    $research->deleted ? label
                    (
                        setClass('danger-outline text-danger flex-none ml-2'),
                        $lang->research->deleted
                    ) : null,
                    isset($research->delay) ? label
                    (
                        setClass("ml-2 flex-none danger-pale"),
                        $lang->marketresearch->delayed
                    ) : label
                    (
                        setClass("ml-2 flex-none bg-white status status-{$research->status}"),
                        $status
                    ),
                    span
                    (
                        setClass('ml-2 text-gray flex-none acl'),
                        $lang->marketresearch->shortAclList[$research->acl],
                        btn
                        (
                            set::size('sm'),
                            set::icon('help'),
                            setClass('ghost form-label-hint text-gray-300 ml-2'),
                            toggle::tooltip(array('title' => $lang->marketresearch->aclList[$research->acl], 'className' => 'text-gray border border-gray-300', 'type' => 'white', 'placement' => 'right'))
                        )
                    )
                ),
                div
                (
                    set::className('detail-content mt-4 overflow-hidden desc-box'),
                    set::title(strip_tags($research->desc)),
                    html($research->desc)
                )
            )
        ),
        div
        (
            setClass('flex flex-auto p-4 mt-4 canvas'),
            div
            (
                setClass('w-full'),
                /* Project team. */
                h::table
                (
                    setClass('table condensed bordered teams ' . ($research->hasProduct ? 'mt-4' : '')),
                    h::thead
                    (
                        h::tr
                        (
                            h::th
                            (
                                div
                                (
                                    setClass('flex items-center justify-between'),
                                    span($lang->marketresearch->common . $lang->project->team),
                                    hasPriv('marketresearch', 'team') ? btn
                                    (
                                        setClass('ghost text-gray'),
                                        set::trailingIcon('caret-right pb-0.5'),
                                        set::url(createLink('marketresearch', 'team', "researchID={$research->id}")),
                                        span($lang->more, setClass('font-normal'))
                                    ) : null
                                )
                            )
                        )
                    ),
                    h::tbody
                    (
                        h::tr
                        (
                            h::td(div(setClass('flex flex-wrap member-list pt-2'), $membersDom))
                        )
                    )
                ),
                /* Estimate statistics. */
                h::table
                (
                    setClass('table condensed bordered mt-4 duration'),
                    h::thead
                    (
                        h::tr
                        (
                            h::th
                            (
                                div(setClass('flex items-center justify-between'), span($lang->execution->DurationStats))
                            )
                        )
                    ),
                    h::tbody
                    (
                        h::tr
                        (
                            h::td
                            (
                                div
                                (
                                    setClass('flex flex-wrap pt-2 mx-4'),
                                    div
                                    (
                                        setClass('w-1/4'),
                                        span(setClass('text-gray'), $lang->project->begin),
                                        span(setClass('ml-2'), $research->begin)
                                    ),
                                    div
                                    (
                                        setClass('w-1/4'),
                                        span(setClass('text-gray'), $lang->project->end),
                                        span
                                        (
                                            setClass('ml-2'),
                                            $research->end = $research->end == LONG_TIME ? $this->lang->project->longTime : $research->end
                                        )
                                    ),
                                    div
                                    (
                                        setClass('w-1/4'),
                                        span(setClass('text-gray'), $lang->project->realBeganAB),
                                        span
                                        (
                                            setClass('ml-2'),
                                            helper::isZeroDate($research->realBegan) ? '' : $research->realBegan
                                        )
                                    ),
                                    div
                                    (
                                        setClass('w-1/4'),
                                        span(setClass('text-gray'), $lang->project->realEndAB),
                                        span
                                        (
                                            setClass('ml-2'),
                                            helper::isZeroDate($research->realEnd) ? '' : $research->realEnd
                                        )
                                    )
                                )
                            )
                        )
                    )
                ),
                h::table
                (
                    setClass('table condensed bordered mt-4 estimate'),
                    h::thead
                    (
                        h::tr
                        (
                            h::th
                            (
                                div(setClass('flex items-center justify-between'), span($lang->execution->lblStats))
                            )
                        )
                    ),
                    h::tbody
                    (
                        h::tr
                        (
                            h::td
                            (
                                div
                                (
                                    setClass('flex flex-wrap pt-2 mx-4'),
                                    div
                                    (
                                        setClass('w-1/4'),
                                        span(setClass('text-gray'), $lang->execution->estimateHours),
                                        span(setClass('ml-2'), (float)$research->estimate . 'h')
                                    ),
                                    div
                                    (
                                        setClass('w-1/4'),
                                        span(setClass('text-gray'), $lang->execution->consumedHours),
                                        span(setClass('ml-2'), (float)$research->consumed . 'h')
                                    ),
                                    div
                                    (
                                        setClass('w-1/4'),
                                        span(setClass('text-gray'), $lang->execution->leftHours),
                                        span(setClass('ml-2'), (float)$research->left . 'h')
                                    ),
                                    div
                                    (
                                        setClass('w-1/4'),
                                        span(setClass('text-gray'), $lang->execution->totalHours),
                                        span(setClass('ml-2'), (float)$research->left . 'h')
                                    )
                                )
                            )
                        )
                    )
                ),
                html($this->printExtendFields($research, 'html', 'position=info', false))
            )
        )
    ),
    cell
    (
        setClass('side ml-4'),
        panel
        (
            setID('dynamicBlock'),
            to::heading
            (
                div
                (
                    set('class', 'panel-title text-md font-bold'),
                    $lang->execution->latestDynamic
                )
            ),
            set::bodyClass('pt-0 h-80 overflow-y-auto'),
            set::shadow(false),
            dynamic()
        ),
        html($this->printExtendFields($research, 'html', 'position=basic', false)),
        div
        (
            setID('historyBlock'),
            setClass('mt-4'),
            history
            (
                set::objectID($research->id),
                set::commentUrl(createLink('action', 'comment', array('objectType' => 'marketresearch', 'objectID' => $research->id))),
                set::bodyClass('maxh-80 overflow-y-auto')
            )
        )
    )
);

$config->project->actionList['edit']['url']['params'] = 'projectID={id}&from=view';
$actions = $this->loadModel('common')->buildOperateMenu($research);
div
(
    setClass('w-2/3 center fixed actions-menu'),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($actions['mainActions']),
        set::suffix($actions['suffixActions']),
        set::object($research)
    )
);
