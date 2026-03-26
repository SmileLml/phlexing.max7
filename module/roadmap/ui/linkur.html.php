<?php
/**
 * The linkUR view file of roadmap module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     roadmap
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = array();
foreach($config->roadmap->defaultFields['linkUR'] as $field) $cols[$field] = zget($config->story->dtable->fieldList, $field, array());
$cols = array_map(function($col){$col['show'] = true; return $col;}, $cols);
$cols['title']['link']         = $this->createLink('story', 'storyView', "storyID={id}");
$cols['title']['title']        = $lang->roadmap->storyName;
$cols['title']['nestedToggle'] = true;
$cols['title']['data-toggle']  = 'modal';
$cols['title']['data-size']    = 'lg';
$cols['assignedTo']['type']    = 'user';
$cols['module']['type']        = 'text';
$cols['module']['map']         = $modules;

foreach($cols as $colKey => $colConfig) $cols[$colKey]['sort'] = true;

foreach($allStories as $story) $story->estimate = $story->estimate . $config->hourUnit;

$config->product->search['fields']['title'] = $lang->requirement->title;

searchForm
(
    set('zui-key', 'searchForm'),
    set::module('story'),
    set::simple(true),
    set::show(true),
    set::onSearch(jsRaw("window.onSearchLinks.bind(null)"))
);

dtable
(
    setID('unlinkStoryList'),
    set::userMap($users),
    set::cols($cols),
    set::data(array_values($allStories)),
    set::noNestedCheck(),
    set::onRenderCell(jsRaw('window.renderStoryCell')),
    set::beforeCheckRows(jsRaw('function(checkedIds, checkedStatus){return window.beforeCheckRows(checkedIds, checkedStatus);}')),
    set::extraHeight('+144'),
    set::loadPartial(true),
    set::footToolbar(array('items' => array(array
        (
            'text'         => $lang->roadmap->linkUR,
            'btnType'      => 'secondary',
            'className'    => 'size-sm linkObjectBtn',
            'data-type'    => 'story',
            'data-url'     => inlink('linkUR', "roadmapID={$roadmap->id}&browseType=$browseType&param=$param&orderBy=$orderBy"),
            'zui-on-click' => 'handleLinkObjectClick($target)'
        ))
    )),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(inlink('view', "roadmapID={$roadmap->id}&type=story&orderBy=$orderBy"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager())
);

render();
