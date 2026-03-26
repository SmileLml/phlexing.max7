<?php
/**
 * The effort view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('confirmDeleteTip', $this->lang->effort->confirmDelete);
jsVar('confirmTaskDeleteTip', $this->lang->task->confirmDeleteLastEstimate);
jsVar('canViewList', $canViewList);
jsVar('vision', $config->vision);

featureBar
(
    set::current($type),
    set::linkParams("type={key}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    datePicker
    (
        set::_class('w-32'),
        set::value($date),
        set::onChange(jsRaw("(value) => loadPage($.createLink('my', 'effort', 'date=' + (value ? zui.formatDate(value, 'yyyyMMdd') : '')), '#mainContent,#featureBar')"))
    )
);

toolbar
(
    !empty($app->user) && common::hasPriv('effort', 'calendar') ? item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(array
        (
            'icon'  => 'cards-view',
            'class' => 'btn-icon',
            'hint'  => $lang->effort->calendar,
            'url'   => createLink('effort', 'calendar')
        ), array
        (
            'icon'  => 'list',
            'class' => 'btn-icon text-primary',
            'hint'  => $lang->effort->list,
            'url'   => createLink('my', 'effort', "type=all")
        ))
    ))) : null,
    hasPriv('effort', 'export') ? item
    (
        set(array(
            'text'  => $lang->effort->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'url'   => createLink('effort', 'export', "userID={$app->user->id}&orderBy={$orderBy}"),
            'data-toggle' => 'modal'
        ))
    ) : null,
    hasPriv('effort', 'batchCreate') ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(helper::createLink('effort', 'batchCreate', "date=today&userID=&from=list", '', true)),
        setData('toggle', 'modal'),
        setData('type', 'iframe'),
        setData('size', 'lg'),
        $lang->effort->batchCreate
    ) : null
);

$footToolbar = array('items' => array(
    hasPriv('effort', 'batchEdit') ? array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('effort', 'batchEdit', "from=browse")) : null
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

$consumed   = 0;
$taskIdList = array();
$effortData = array();
foreach($efforts as $effort)
{
    $consumed += (float)$effort->consumed;
    $effort->product = trim($effort->product, ',');
    if(!empty($effort->objectID)) $effort->objectTitle = zget($this->lang->effort->objectTypeList, $effort->objectType, strtoupper($effort->objectType)) . " #{$effort->objectID} " . $effort->objectTitle;
    if($effort->objectType == 'task') $taskIdList[$effort->objectID] = $effort->objectID;
    if(isset($productProjects[$effort->product]))
    {
        $effort->project = $productProjects[$effort->product];
        $effort->product = 0;
    }

    $effortData[$effort->id] = array('objectType' => $effort->objectType, 'objectID' => $effort->objectID, 'consumed' => $effort->consumed);
}
$tasks = $this->loadModel('task')->getByIdList($taskIdList);

jsVar('tasks', $tasks);
jsVar('efforts', $effortData);
jsVar('noProjectProjects', array_values($productProjects));

$config->my->effort->dtable->fieldList['product']['map']   = $products;
$config->my->effort->dtable->fieldList['project']['map']   = $projects;
$config->my->effort->dtable->fieldList['execution']['map'] = $executions;
$efforts = initTableData($efforts, $config->my->effort->dtable->fieldList, $this->effort);

$cols = $this->loadModel('datatable')->getSetting('my', 'effort');
dtable
(
    set::onRenderCell(jsRaw('window.renderCell')),
    set::cols($cols),
    set::data($efforts),
    set::customCols(true),
    set::userMap($users),
    set::checkable(true),
    set::checkInfo(jsRaw("function(checkedIDList){return {html: '" . sprintf($lang->company->effort->timeStat, round($consumed, 2)) . "'}}")),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', 'effort', "type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);
