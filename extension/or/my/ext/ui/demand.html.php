<?php
/**
 * The demand view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include($app->getModuleRoot() . 'my/ui/header.html.php');

jsVar('recallChange', $lang->demand->recallChange);
jsVar('recall',       $lang->demand->recall);
jsVar('children',     $lang->story->children);
jsVar('childrenAB',   $lang->story->childrenAB);

featureBar
(
    set::current($type),
    set::linkParams("mode=demand&type={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::module($this->app->rawMethod . 'Demand'), set::open($type == 'bysearch')))
);

$demands = initTableData($demands, $config->my->demand->dtable->fieldList, $this->demand);
$cols    = array_values($config->my->demand->dtable->fieldList);
$data    = array_values($demands);
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager()),
    set::emptyTip(sprintf($lang->my->noData, $lang->demand->common))
);

render();
