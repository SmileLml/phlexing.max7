<?php
/**
 * The view task file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hucheng Tang<tanghucheng@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

$isInModal = isInModal();

$task->executionInfo = $execution;
$actions             = $this->loadModel('common')->buildOperateMenu($task);
$hasDivider          = !empty($actions['mainActions']) && !empty($actions['suffixActions']);
if(!empty($actions)) $actions = array_merge($actions['mainActions'], array('type' => 'divider'), $actions['suffixActions']);
if(!$hasDivider) unset($actions['type']);
foreach($actions as $key => $action)
{
    if(isset($action['url']) && strpos($action['url'], 'createBranch') !== false && empty($hasGitRepo)) unset($actions[$key]);
    if(isset($action['url']) && strpos($action['url'], 'view') !== false && strpos($action['url'], 'review') === false)
    {
        if($isInModal)
        {
            $actions[$key]['data-toggle'] = 'modal';
            $actions[$key]['data-size']   = 'lg';
        }
        if($task->parent == 0) unset($actions[$key]);
    }
    if(isonlybody() && isset($actions[$key]['data-load']))
    {
        unset($actions[$key]['data-load']);
        $actions[$key]['data-toggle'] = 'modal';
    }
    if(isset($actions[$key]['url']))
    {
        $actions[$key]['url'] = str_replace(array('{story}', '{module}', '{parent}', '{execution}'), array($task->story, $task->module, $task->parent, $task->execution), $action['url']);
    }
}

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->title($lang->task->legendDesc)
    ->control('html')
    ->content(empty($task->desc) ? $lang->noDesc : $task->desc);

if($task->children)
{
    $children = initTableData($task->children, $config->task->dtable->children->fieldList, $this->task);
    $sections[] = setting()
        ->title($lang->task->children)
        ->control('dtable')
        ->className('ring')
        ->cols(array_values($config->task->dtable->children->fieldList))
        ->userMap($users)
        ->data($children)
        ->checkable(false);
}
if($task->files)
{
    $sections[] = array
    (
        'control' => 'fileList',
        'files'   => $task->files,
        'object'  => $task,
        'padding' => false
    );
}

/* 初始化侧边栏标签页。Init sidebar tabs. */
$tabs = array();

/* 基本信息。Legend basic items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->task->legendBasic)
    ->control('researchtaskBasicinfo')
    ->statusText($this->processStatus('task', $task));

/* 一生信息。Legend life items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->task->legendLife)
    ->control('taskLifeInfo');

if($task->team)
{
    $tabs[] = setting()
        ->group('basic')
        ->title($lang->task->team)
        ->control('taskTeam');
}

$tabs[] = setting()
    ->group('related')
    ->title($lang->task->legendEffort)
    ->control('taskEffortInfo');

detail
(
    set::objectType('task'),
    $task->parent > 0 ? array
    (
        set::parentTitle($task->parentName),
        set::parentUrl(createLink('marketresearch', 'viewTask', "taskID={$task->parent}")),
        set::parentTitleProps(array('data-load' => 'modal')),
        to::title(to::leading(label(setClass('gray-pale rounded-full'), $lang->task->childrenAB)))
    ) : null,
    set::urlFormatter(array('{id}' => $task->id, '{parent}' => $task->parent, '{execution}' => $task->execution)),
    set::toolbar($toolbar),
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);
