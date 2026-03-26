<?php
/**
 * The view view file of roadmap module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     roadmap
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('gradeGroup',      $gradeGroup);
jsVar('roadmapID',       $roadmap->id);
jsVar('orderBy',         $orderBy);
jsVar('storyPageID',     $storyPager->pageID);
jsVar('storyRecPerPage', $storyPager->recPerPage);
jsVar('storyRecTotal',   $storyPager->recTotal);
jsVar('summary',         $summary);
jsVar('checkedSummary',  $lang->roadmap->checkedSummary);
jsVar('link',            $link);

/* 初始化需求列表列。*/
$storyCols = array();
foreach($config->roadmap->defaultFields['story'] as $field)
{
    if($field == 'branch' && $product->type == 'normal') continue;
    $storyCols[$field] = zget($config->story->dtable->fieldList, $field, array());
    if($field == 'id' && common::hasPriv('execution', 'storySort'))
    {
        $storyCols['sort']['title'] = $lang->roadmap->updateOrder;
        $storyCols['sort']['fixed'] = 'left';
        $storyCols['sort']['align'] = 'center';
        $storyCols['sort']['group'] = 1;
        $storyCols['sort']['width'] = 60;
    }
}
$storyCols['title']['link']       = $this->createLink('story', 'storyView', "storyID={id}");
$storyCols['title']['title']      = $lang->roadmap->storyName;
$storyCols['assignedTo']['type']  = 'user';
$storyCols['module']['type']      = 'text';
$storyCols['module']['map']       = $modulePairs;
$storyCols['module']['sortType']  = true;
$storyCols['actions']['list']     = $config->roadmap->actionList;
$storyCols['actions']['menu']     = array('unlinkUR');
$storyCols['actions']['minWidth'] = 60;
if(isset($storyCols['branch'])) $storyCols['branch']['map'] = $branchOption;
if($config->vision != 'or') unset($storyCols['actions']);

/* 初始化需求数据。*/
$roadmapStories = initTableData($roadmapStories, $storyCols, $this->roadmap);
foreach($roadmapStories as $story)
{
    $story->estimate = $story->estimate . $config->hourUnit;
    if($config->vision == 'or')
    {
        foreach($story->actions as $key => $action)
        {
            if($action['name'] == 'unlinkUR' && !in_array($story->stage, array('wait', 'inroadmap', 'incharter'))) $story->actions[$key]['disabled'] = true;
        }
    }
}

/* 需求列表批量操作。*/
$canBatchClose         = common::hasPriv('requirement', 'batchClose');
$canBatchEdit          = common::hasPriv('requirement', 'batchEdit');
$canBatchChangeBranch  = common::hasPriv('requirement', 'batchChangeBranch');
$canBatchChangeModule  = common::hasPriv('requirement', 'batchChangeModule');
$canBatchChangeRoadmap = common::hasPriv('requirement', 'batchChangeRoadmap');
$canBatchAssignTo      = common::hasPriv('requirement', 'batchAssignTo');
$canBatchAction        = $config->vision == 'or' && $canBeChanged && ($canBatchClose || $canBatchEdit || $canBatchChangeBranch || $canBatchChangeModule || $canBatchChangeRoadmap || $canBatchAssignTo);

$branchItems = $moduleItems = $assignItems = $roadmapItems = array();
foreach($branchTagOption as $branchID => $branchName) $branchItems[] = array('text' => $branchName, 'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('requirement', 'batchChangeBranch', "branchID=$branchID"));
foreach($modules as $moduleID => $moduleName)         $moduleItems[] = array('text' => $moduleName, 'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('requirement', 'batchChangeModule', "moduleID=$moduleID"));
foreach($users as $account => $realname)
{
    if(empty($account) || $account == 'closed') continue;
    $assignItems[] = array('text' => $realname, 'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('requirement', 'batchAssignTo', "productID=$roadmap->product"), 'data-account' => $account);
}
foreach($roadmaps as $roadmapID => $roadmapName)
{
    if($roadmapID == $roadmap->id) continue;
    $roadmapItems[] = array('text' => $roadmapName, 'class' => 'batch-btn', 'disabled' => !in_array($roadmap->status, array('wait', 'reject')), 'data-type' => 'story', 'data-url' => $this->createLink('requirement', 'batchChangeRoadmap', "roadmapID=$roadmapID"), 'data-disabled' => !in_array($roadmap->status, array('wait', 'reject')));
}

$navStoryActionItems = array();
if($canBatchClose)         $navStoryActionItems[] = array('text' => $lang->close, 'class' => 'batch-btn', 'data-type' => 'story', 'data-page' => 'batch', 'data-url' => helper::createLink('requirement', 'batchClose', "productID={$roadmap->product}&executionID=0&storyType=requirement"));
if($canBatchEdit)          $navStoryActionItems[] = array('text' => $lang->edit,  'class' => 'batch-btn', 'data-type' => 'story', 'data-page' => 'batch', 'data-url' => helper::createLink('requirement', 'batchEdit', "productID=$roadmap->product&projectID=$projectID&branch=$branch&storyType=requirement"));
if($canBatchChangeRoadmap) $navStoryActionItems[] = array('class' => 'not-hide-menu', 'disabled' => !in_array($roadmap->status, array('wait', 'reject')), 'text' => $lang->roadmap->common, 'items' => $roadmapItems);
if($canBatchChangeBranch && $product->type != 'normal') $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->product->branchName[$product->type], 'items' => $branchItems);
if($canBatchChangeModule) $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->moduleAB,   'items' => $moduleItems);
if($canBatchAssignTo)     $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->assignedTo, 'items' => $assignItems);

$storyFootToolbar = array();
if($canBatchAction)
{
    $storyFootToolbar = array('items' => array
    (
        array('type' => 'btn-group', 'items' => array
        (
            array('text' => $lang->roadmap->unlinkURAB, 'className' => 'batch-btn size-sm', 'btnType' => 'secondary', 'data-type' => 'story', 'data-url' => helper::createLink('roadmap', 'batchUnlinkUR', "roadmapID=$roadmap->id")),
            array('caret' => 'up', 'className' => 'size-sm', 'btnType' => 'secondary', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start', 'items' => $navStoryActionItems)
        ))
    ));
}

$actions = $this->loadModel('common')->buildOperateMenu($roadmap);
foreach($actions as $actionType => $typeActions)
{
    foreach($typeActions as $key => $action)
    {
        $actions[$actionType][$key]['url']       = str_replace(array('{roadmapID}'), array((string)$roadmap->id), $action['url']);
        $actions[$actionType][$key]['className'] = isset($action['className']) ? $action['className'] . ' ghost' : 'ghost';
        $actions[$actionType][$key]['iconClass'] = isset($action['iconClass']) ? $action['iconClass'] . ' text-primary' : 'text-primary';
    }
}

detailHeader
(
    to::prefix
    (
        backBtn(set::icon('back'), set::type('secondary'), set::url(inlink('browse', "productID=$roadmap->product")), $lang->goback),
        entityLabel(set(array('entityID' => $roadmap->id, 'level' => 1, 'text' => $roadmap->name))),
        span(setClass('label circle primary'), ($roadmap->begin == $config->roadmap->future || $roadmap->end == $config->roadmap->future) ? $lang->roadmap->future : $roadmap->begin . '~' . $roadmap->end),
        $roadmap->deleted ? span(setClass('label danger'), $lang->product->deleted) : null
    ),
    !$roadmap->deleted && $actions && $config->vision == 'or' ? to::suffix
    (
        btnGroup(set::items($actions['mainActions'])),
        !empty($actions['mainActions']) && !empty($actions['suffixActions']) ? div(setClass('divider mx-2')): null,
        btnGroup(set::items($actions['suffixActions']))
    ) : null
);

detailBody
(
    set::hasExtraMain(false),
    sectionList
    (
        tabs
        (
            setClass('w-full relative'),
            tabPane
            (
                to::prefix(icon($lang->icons['story'], setClass('text-secondary'))),
                set::key('stories'),
                set::title($lang->roadmap->linkedURS),
                set::active($type == 'story'),
                toolbar
                (
                    setClass('tab-actions absolute right-0 gap-2'),
                    setStyle('top', '-8px'),
                    common::hasPriv('roadmap', 'linkUR') && $config->vision == 'or' ? btn
                    (
                        set::type('primary'),
                        set::icon('link'),
                        set::text($lang->roadmap->linkUR),
                        bind::click("window.showLink()")
                    ) : null
                ),
                dtable
                (
                    setID('storyDTable'),
                    set::plugins(array('sortable')),
                    set::sortHandler('.move-roadmap'),
                    set::onSortEnd(jsRaw('window.onSortEnd')),
                    set::style(array('min-width' => '100%')),
                    set::userMap($users),
                    set::bordered(true),
                    set::cols($storyCols),
                    set::data(array_values($roadmapStories)),
                    set::noNestedCheck(),
                    set::checkable($canBatchAction),
                    set::onRenderCell(jsRaw('window.renderStoryCell')),
                    set::footToolbar($storyFootToolbar),
                    set::sortLink(createLink('roadmap', 'view', "roadmapID={$roadmap->id}&type=story&orderBy={name}_{sortType}&link=false&param={$param}&recTotal={$storyPager->recTotal}&recPerPage={$storyPager->recPerPage}&page={$storyPager->pageID}")),
                    set::orderBy($orderBy),
                    set::extraHeight('+144'),
                    set::checkInfo(jsRaw("function(checkedIDList){return window.setStatistics(this, checkedIDList, '{$summary}');}")),
                    set::footPager
                    (
                        usePager('storyPager', '', array(
                            'recPerPage' => $storyPager->recPerPage,
                            'recTotal' => $storyPager->recTotal,
                            'linkCreator' => helper::createLink('roadmap', 'view', "roadmapID={$roadmap->id}&type=story&orderBy={$orderBy}&link=false&param={$param}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&pageID={page}")
                        ))
                    )
                )
            ),
            tabPane
            (
                to::prefix(icon('flag', setClass('text-special'))),
                set::key('roadmapInfo'),
                set::title($lang->roadmap->view),
                set::active($type == 'roadmapInfo'),
                tableData
                (
                    set::title($lang->roadmap->basicInfo),
                    item(set::name($lang->roadmap->name), $roadmap->name),
                    $product->type != 'normal' ? item(set::name($lang->product->branch), $branchOption[$roadmap->branch]) : null,
                    item(set::name($lang->roadmap->begin), $roadmap->begin == $config->roadmap->future ? $lang->roadmap->future : $roadmap->begin),
                    item(set::name($lang->roadmap->end), $roadmap->end == $config->roadmap->future ? $lang->roadmap->future : $roadmap->end),
                    item(set::name($lang->roadmap->status), $lang->roadmap->statusList[$roadmap->status]),
                    item(set::name($lang->roadmap->desc), empty($roadmap->desc) ? $lang->noData : html(($roadmap->desc)))
                ),
                h::hr(setClass('mt-4')),
                history(set::objectID($roadmap->id), set::commentBtn(false))
            )
        )
    )
);
