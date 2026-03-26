<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao<chentao@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('gradeGroup', $gradeGroup);
jsVar('showGrade', $showGrade);
jsVar('oldShowGrades', $showGrades);

$viewType = $this->cookie->storyViewType ? $this->cookie->storyViewType : 'tree';
foreach($libs as $id => $name) $libItems[] = array('text' => $name, 'url' => inlink('story', "libID=$id"), 'active' => $libID == $id);
featureBar
(
    to::leading
    (
        dropdown
        (
            btn(zget($libs, $libID), setClass('ghost')),
            set::items($libItems),
            set::trigger('click')
        ),
        picker
        (
            set::tree(),
            set::name('showGrades'),
            set::items($gradeMenu),
            set::search(false),
            set::multiple(true),
            set::width('150px'),
            setStyle('justify-content', 'center'),
            set::display($lang->story->viewAllGrades),
            set::menu(array('checkbox' => true)),
            set::value($showGrades),
            set::onPopHidden(jsRaw('setShowGrades'))
        )
    ),
    li(searchToggle(set::open($browseType == 'bysearch'), set::module('storyLib'))),
    set::link(inlink('story', "libID=$libID&browseType={key}"))
);

$importItem = array('text' => $lang->assetlib->importStory, 'data-size' => 'sm','url' => inlink('importstory', "libID=$libID"));
toolbar
(
    item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(array
        (
            'icon'      => 'list',
            'class'     => 'btn-icon switchButton' . ($viewType == 'tiled' ? ' text-primary' : ''),
            'data-type' => 'tiled',
            'hint'      => $lang->story->viewTypeList['tiled']
        ), array
        (
            'icon'      => 'treeview',
            'class'     => 'switchButton btn-icon' . ($viewType == 'tree' ? ' text-primary' : ''),
            'data-type' => 'tree',
            'hint'      => $lang->story->viewTypeList['tree']
        ))
    ))),
    hasPriv('assetlib', 'storyLibView') ? item(set(array('id' => 'storyLibView', 'text' => $lang->assetlib->libView, 'icon' => 'list-alt', 'class' => 'ghost', 'url' => inlink('storyLibView', "libID=$libID")))) : null,
    common::hasPriv('assetlib', 'importstory') ? dropdown
    (
        btn(setID('importBtn'), setClass('btn ghost square dropdown-toggle'), set::icon('import')),
        set::items(array($importItem)),
        set::placement('bottom-end')
    ): null
);

$canBatchAssignTo = common::hasPriv('assetlib', 'batchAssignToStory');
$canBatchApprove  = common::hasPriv('assetlib', 'batchApproveStory');
$canBatchRemove   = common::hasPriv('assetlib', 'batchRemoveStory');
$canBatchAction   = ($browseType == 'all' or $browseType == 'bysearch') ? ($canBatchApprove or $canBatchRemove) : ($canBatchAssignTo or $canBatchApprove or $canBatchRemove);

$footToolbar = array();
if($canBatchAction)
{
    if($canBatchAssignTo && $browseType == 'draft')
    {
        $assignedToItems = array();
        foreach($approvers as $account => $name)
        {
            $assignedToItems[] = array('text' => $name, 'class' => 'batch-btn', 'data-formaction' => createLink('assetlib', 'batchAssignToStory', "libID={$libID}&assignedTo={$account}"));
        }

        $footToolbar['items'][] = array('text' => $lang->assetlib->assignedTo, 'class' => 'btn btn-caret size-sm', 'btnType' => 'secondary', 'items' => $assignedToItems,'type' => 'dropdown');
    }

    if($canBatchApprove)
    {
        $approveItems = array();
        foreach($lang->assetlib->resultList as $key => $value)
        {
            $approveItems[] = array('text' => $value, 'class' => 'batch-btn', 'data-formaction' => createLink('assetlib', 'batchApproveStory', "libID={$libID}&result={$key}"));
        }

        $footToolbar['items'][] = array('text' => $lang->assetlib->approve, 'class' => 'batch-btn', 'btnType' => 'secondary', 'items' => $approveItems, 'data-url' => createLink('assetlib', 'batchApproveStory', "libID={$libID}"));
    }

    if($canBatchRemove)
    {
        $footToolbar['items'][] = array('text' => $lang->assetlib->removeStory, 'class' => 'batch-btn', 'btnType' => 'secondary', 'data-formaction' => createLink('assetlib', 'batchRemoveStory'));
    }
}
foreach($stories as $story)
{
    if($story->status != 'active') $story->assignedTo = '';
}
if($viewType == 'tiled') $config->assetlib->dtable->story->fieldList['title']['nestedToggle'] = false;
$stories = initTableData($stories, $config->assetlib->dtable->story->fieldList, $this->assetlib);
$cols = array_values($config->assetlib->dtable->story->fieldList);
$data = array_values($stories);

dtable(set::id('stories'), set::cols($cols), set::data($data), set::userMap($users), set::fixedLeftWidth('44%'), set::checkable($canBatchAction ? true : false), set::onRenderCell(jsRaw('window.renderCell')), set::orderBy($orderBy), set::sortLink(inlink('story', "libkID={$libID}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")), set::footToolbar($footToolbar), set::footPager(usePager()));
