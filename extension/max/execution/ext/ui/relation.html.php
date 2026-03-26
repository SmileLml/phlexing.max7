<?php
/**
 * The relation view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Qiyu Xie <xieqiyu@chandao.com>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('typeHintList', $lang->execution->relation->typeHintList);
jsVar('confirmBatchDelete', $lang->execution->gantt->confirmBatchDelete);

featureBar(set::current('all'), set::linkParams('executionID=' . ($executionID ? $executionID : $projectID)));

toolbar
(
    common::hasPriv($app->rawModule, 'createRelation') ? btn
    (
        setClass('btn secondary'),
        set::icon('plus'),
        set::url($this->createLink($app->rawModule, 'createRelation', "projectID=$projectID&executionID=$executionID")),
        setData(array('app' => $this->app->tab)),
        $lang->execution->createRelation
    ) : null,
    common::hasPriv('task', 'create') && !$isLimited && !empty($executionID) ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url($this->createLink('task', 'create', "execution=$executionID")),
        setData(array('toggle' => 'modal', 'size' => 'lg')),
        $lang->task->create
    ) : null
);

$cols = $this->loadModel('datatable')->getSetting('execution');
$cols['actions']['list']['edit']['url']   = array('module' => $app->rawModule, 'method' => 'editRelation',   'params' => "relationID={id}&projectID=$projectID&executionID=$executionID");
$cols['actions']['list']['delete']['url'] = array('module' => $app->rawModule, 'method' => 'deleteRelation', 'params' => "relationID={id}&projectID=$projectID&executionID=$executionID");
$cols['task']['map']    = $tasks;
$cols['pretask']['map'] = $tasks;

$relations = initTableData($relations, $cols, $this->loadModel($app->rawModule));

$canBatchEdit   = common::hasPriv($app->rawModule, 'batchEditRelation');
$canBatchDelete = common::hasPriv($app->rawModule, 'batchDeleteRelation');
$canBatchAction = $canBatchEdit || $canBatchDelete;

$footToolbar = array();
if($canBatchAction)
{
    $footToolbar['items'] = array();
    if($canBatchEdit)   $footToolbar['items'][] = array('text' => $lang->execution->maintain, 'className' => 'batch-btn',                'btnType' => 'secondary', 'data-url' => createLink($app->rawModule, 'batchEditRelation', "projectID=$projectID&executionID=$executionID"));
    if($canBatchDelete) $footToolbar['items'][] = array('text' => $lang->delete,              'className' => 'batch-btn batchDeleteBtn', 'btnType' => 'secondary', 'data-url' => createLink($app->rawModule, 'batchDeleteRelation', "projectID=$projectID&executionID=$executionID"));
}

dtable
(
    set::cols($cols),
    set::data(array_values($relations)),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager(array
    (
        'recPerPage'  => $pager->recPerPage,
        'recTotal'    => $pager->recTotal,
        'linkCreator' => helper::createLink($app->rawModule, 'relation', "executionID=" . ($executionID ? $executionID : $projectID) . "&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}")
    ))),
    set::checkable($canBatchAction),
    set::footToolbar($footToolbar)
);
