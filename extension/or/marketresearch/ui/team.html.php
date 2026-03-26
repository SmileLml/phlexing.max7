<?php
/**
 * The team view file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

foreach($teamMembers as $member)
{
    $member->days    = $member->days . $this->lang->execution->day;
    $member->hours   = $member->hours . $this->lang->execution->workHour;
    $member->total   = $member->totalHours . $this->lang->execution->workHour;
    $member->actions = array();
    if(common::hasPriv('marketresearch', 'unlinkMember', $member)) $member->actions = array('unlink');
}

/* zin: Define the set::module('team') feature bar on main menu. */
featureBar
(
    set::current('all'),
    set::linkParams("researchID={$researchID}")
);

/* zin: Define the toolbar on main menu. */
$isLimitUser       = empty($app->user->admin) && !empty($app->user->rights['rights']['my']['limited']);
$canManageMembers  = hasPriv('marketresearch', 'manageMembers') && !$isLimitUser;
$wizardParams      = helper::safe64Encode("researchID={$researchID}");
$manageMembersLink = helper::createLink('marketresearch', 'manageMembers', "researchID={$researchID}");
if($canManageMembers) $manageMembersItem = array('icon' => 'persons', 'class' => 'primary', 'text' => $lang->marketresearch->manageMembers, 'url' => $manageMembersLink);

toolbar
(
    a
    (
        icon('back pr-1'),
        setClass('pr-2 text-black'),
        set::href($this->session->marketresearchList),
        $lang->goback
    ),
    $canManageMembers ? item(set($manageMembersItem)) : null
);

jsVar('confirmUnlinkMember', $lang->marketresearch->confirmUnlinkMember);
jsVar('pageSummary', $lang->team->totalHours . '：' .  "<strong>%totalHours%{$lang->execution->workHour}" . sprintf($lang->project->teamMembersCount, count($teamMembers)) . "</strong>");
jsVar('deptUsers', $deptUsers);
jsVar('noAccess', $lang->user->error->noAccess);

dtable
(
    set::cols($config->marketresearch->dtable->team->fieldList),
    set::data($teamMembers),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footer(jsRaw('function(){return window.setStatistics.call(this);}'))
);
