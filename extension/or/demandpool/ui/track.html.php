<?php
/**
* The UI file of demandpool module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
* @package     demandpool
* @link        https://www.zentao.net
*/

namespace zin;

featureBar
(
    set::module('demand'),
    set::method('browse'),
    set::current($browseType),
    set::linkParams("poolID={$poolID}&browseType={key}&storyType=demand&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::open($browseType == 'bysearch'), set::module($config->demand->search['module']), set::text($lang->searchAB)))
);

$privs['demand']      = commonModel::hasPriv('demand',      'view');
$privs['epic']        = commonModel::hasPriv('epic',        'view');
$privs['requirement'] = commonModel::hasPriv('requirement', 'view');
$privs['story']       = false;
$privs['project']     = false;
$privs['execution']   = false;
$privs['task']        = false;
$privs['bug']         = false;
$privs['case']        = false;
$privs['design']      = false;
$privs['commit']      = false;

$app->loadLang('demand');
$app->loadLang('epic');
$app->loadLang('requirement');
$app->loadLang('project');
$app->loadLang('task');
$app->loadLang('bug');
$app->loadLang('testcase');
jsVar('langStoryPriList',      array('demand' => $lang->demand->priList,    'epic' => $lang->epic->priList,    'requirement' => $lang->requirement->priList,    'story' => $lang->story->priList));
jsVar('langStoryStatusList',   array('demand' => $lang->demand->statusList, 'epic' => $lang->epic->statusList, 'requirement' => $lang->requirement->statusList, 'story' => $lang->story->statusList));
jsVar('langStoryStageList',    array('demand' => $lang->demand->stageList,  'epic' => $lang->epic->stageList,  'requirement' => $lang->requirement->stageList,  'story' => $lang->story->stageList));
jsVar('langProjectStatusList', $lang->project->statusList);
jsVar('langTaskPriList',       $lang->task->priList);
jsVar('langTaskStatusList',    $lang->task->statusList);
jsVar('langChildren',          $lang->task->childrenAB);
jsVar('langBugPriList',        $lang->bug->priList);
jsVar('langBugSeverityList',   $lang->bug->severityList);
jsVar('langCasePriList',       $lang->testcase->priList);
jsVar('langCaseResultList',    $lang->testcase->resultList);
jsVar('langUnexecuted',        $lang->testcase->unexecuted);

jsVar('mergeCells', $mergeCells);
jsVar('storyType',  'demand');
jsVar('users',      $users);
jsVar('privs',      $privs);

empty($tracks) ? div(setClass('dtable-empty-tip bg-white shadow'), span(setClass('text-gray'), $lang->noData)) : div
(
    set::id('track'),
    zui::kanbanList
    (
        set::key('kanban'),
        set::items(array(array(
            'data'        => $tracks,
            'getLaneCol'  => jsRaw('window.getLaneCol'),
            'getCol'      => jsRaw('window.getCol'),
            'getItem'     => jsRaw('window.getItem'),
            'itemRender'  => jsRaw('window.itemRender'),
            'draggable'   => false
        ))),
        set('$replace', false),
        set::height('calc(100vh - 130px)')
    ),
    pager(setClass('justify-end'))
);
