<?php
/**
 * The stage view file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hucheng Tang <tanghucheng@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('stageLang', $lang->stage->common);
jsVar('delayed', $lang->execution->delayed);
jsVar('delayWarning',   $lang->task->delayWarning);

$canViewResearch = hasPriv('marketresearch', 'view');
$canCreateTask   = hasPriv('researchtask', 'create');
$canCreateStage  = hasPriv('marketresearch', 'createStage');

featureBar
(
    set::current($browseType),
    set::linkParams("researchID={$researchID}&browseType={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    set::labelCount($taskTotal),
    li(searchToggle(set::module('task'), set::open($browseType == 'bysearch')))
);

toolbar($canViewResearch ? btn(setClass('ghost'), set::icon('list-alt'), set::hint($lang->marketresearch->view), set::text($lang->marketresearch->view), set::url(inlink('view', "researchID={$researchID}"))) : null, $canCreateTask ? btn(set::icon('plus'), set::type('secondary'), set::url(createLink('researchtask', 'create', "researchID={$researchID}")), set::text($lang->researchtask->create), set(array('data-app' => $app->tab, 'class' => 'researchtask-create-btn'))) : null, $canCreateStage ? btn(set::icon('plus'), set::type('primary'), set::url(createLink('marketresearch', 'createStage', "researchID=$researchID&stageID=0&executionType=stage")), set::text($lang->marketresearch->createStage), set(array('data-app' => $app->tab, 'class' => 'createStage-btn'))) : null);

$cols = $this->loadModel('datatable')->getSetting('marketresearch');

foreach($taskStats as $task)
{
    $actionType = $task->type == 'stage' ? 'stage' : 'task';
    $cols['actions']['menu'] = $config->marketresearch->actionMenu->$actionType;
    if($actionType == 'stage') $task->PM = zget($users, $task->PM);
    $task = initTableData(array($task), $cols, $this->marketresearch);

    if($task[0]->type == 'stage')
    {
        foreach($task[0]->actions as &$action)
        {
            if($action['name'] == 'createStage' && !empty($task[0]->hasTask))
            {
                $action['disabled'] = 1;
                $action['hint']     = $lang->programplan->error->createdTask;
            }
        }
    }
}

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::orderBy($orderBy),
    set::data($taskStats),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::sortLink(createLink('marketresearch', 'task', "researchID={$researchID}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::footer(array(array('html' => $lang->marketresearch->summary), 'flex', 'pager')),
    set::footPager(usePager())
);
