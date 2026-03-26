<?php
/**
 * The view file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     demand
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('storyGrades', $storyGrades);
jsVar('roadmapPlans', $roadmapPlans);
jsVar('roadmapCommon', $lang->roadmap->common);
jsVar('planCommon', $lang->productplan->shortCommon);

/* 左侧描述区块。 */
$sections[] = setting()
    ->title($lang->demand->spec)
    ->control('html')
    ->content($demand->spec);

/* 左侧验收标准区块。 */
$sections[] = setting()
    ->title($lang->demand->verify)
    ->control('html')
    ->content($demand->verify);

/* 左侧附件区块。 */
if($demand->files)
{
    $sections[] = array
    (
        'control'    => 'fileList',
        'files'      => $demand->files,
        'object'     => $demand,
        'padding'    => false,
        'showDelete' => false
    );
}

/* 左侧子需求列表。 */
if(!empty($demand->children))
{
    $cols = array();
    $cols['id']         = $config->demand->dtable->fieldList['id'];
    $cols['title']      = $config->demand->dtable->fieldList['title'];
    $cols['pri']        = $config->demand->dtable->fieldList['pri'];
    $cols['assignedTo'] = $config->demand->dtable->fieldList['assignedTo'];
    $cols['status']     = $config->demand->dtable->fieldList['status'];
    $cols['stage']      = $config->demand->dtable->fieldList['stage'];
    $cols['actions']    = $config->demand->dtable->fieldList['actions'];

    foreach($cols as $key => $col) $cols[$key]['sortType'] = false;

    $cols['title']['data-toggle'] = 'modal';
    $cols['title']['data-size']   = 'lg';

    $children = initTableData($demand->children, $cols, $this->demand);
    $sections[] = array
    (
        'title'          => $lang->demand->children,
        'control'        => 'dtable',
        'cols'           => $cols,
        'userMap'        => $users,
        'data'           => $children,
        'fixedLeftWidth' => '0.4',
        'nested'         => false
    );
}

/* 左侧分发需求列表。 */
if(!empty($demand->stories))
{
    $this->loadModel('story');
    $config->story->dtable->fieldList['title']['title'] = $lang->demand->title;
    $cols = array();
    $cols['id']      = array('name' => 'id', 'title' => $lang->idAB, 'type' => 'id');
    $cols['title']   = $config->story->dtable->fieldList['title'];
    $cols['product'] = $config->story->dtable->fieldList['product'];
    $cols['roadmap'] = array('name' => 'roadmapOrPlan', 'title' => $lang->demand->roadmapOrPlanAB, 'type' => 'text');
    $cols['status']  = $config->story->dtable->fieldList['status'];
    $cols['stage']   = $config->story->dtable->fieldList['stage'];
    $cols['actions'] = array('title' => $lang->actions, 'type' => 'actions', 'list' => array('retract' => array('icon' => 'back', 'hint' => $lang->demand->retract, 'url' => array('module' => 'demand', 'method' => 'retract', 'params' => 'storyID={id}'), 'data-toggle' => 'modal', 'data-size' => 'lg')), 'menu' => array('retract'));

    unset($cols['title']['nestedToggle']);
    $cols['title']['data-toggle'] = 'modal';
    $cols['title']['data-size']   = 'lg';
    $cols['product']['map']       = $products;
    $cols['stage']['title']       = $lang->demand->storyStage;
    $cols['stage']['width']       = '125';
    $cols['stage']['statusMap']  += $lang->story->stageList;

    foreach(array_keys($cols) as $fieldName) $cols[$fieldName]['sortType'] = false;

    $stories = initTableData($demand->stories, $cols, $this->story);
    $sections[] = array
    (
        'title'          => $lang->demand->distributedStory,
        'control'        => 'dtable',
        'cols'           => $cols,
        'userMap'        => $users,
        'data'           => $stories,
        'fixedLeftWidth' => '0.4',
        'extensible'     => false,
        'onRenderCell'   => jsRaw('window.renderCell')
    );
}

/* 右侧基本信息区块。 */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->demand->basicInfo)
    ->control('demandBasicInfo');

/* 右侧需求一生区块。 */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->demand->lifeTime)
    ->control('demandLifeInfo');

/* 关联对象。*/
$tabs[] = setting()
    ->group('related')
    ->title($lang->custom->relateObject)
    ->control('relatedObjectList')
    ->objectID($demand->id)
    ->objectType('demand')
    ->browseType('byObject');

if($demand->status == 'changing') $config->demand->actionList['recall']['text'] = $lang->demand->recallChange;

/* 操作按钮。 */
$operateList = $this->loadModel('common')->buildOperateMenu($demand);
$actions     = $operateList['mainActions'];
if(!empty($operateList['suffixActions'])) $actions = array_merge($actions, array(array('type' => 'divider')), $operateList['suffixActions']);

/* 版本列表。Version list. */
$versions = array();
for($i = $demand->version; $i >= 1; $i--)
{
    $versionItem = setting()->text("#{$i}")->url(inlink('view', "demandID={$demand->id}&version={$i}"))->selected($version == $i);
    if(isInModal()) $versionItem->set(array('data-load' => 'modal', 'data-target' => '.modal.show'));
    $versions[] = $versionItem;
}
$versionBtn = count($versions) > 1 ? to::title(dropdown
(
    btn(set::type('ghost'), setClass('text-link font-normal text-base'), "#{$version}"),
    set::items($versions)
)) : null;

detail
(
    set::urlFormatter(array('{id}' => $demand->id, '{pool}' => $demand->pool)),
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions)),
    $versionBtn
);
