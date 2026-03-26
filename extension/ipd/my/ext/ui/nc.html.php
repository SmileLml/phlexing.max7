<?php
/**
 * The nc view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include $app->getModuleRoot() . 'my/ui/header.html.php';

$linkParams = $app->rawMethod == 'work' ? "mode={key}&type=" : "mode={$mode}&type={key}";
featureBar
(
    set::current($app->rawMethod == 'work' ? 'nc' : $browseType),
    set::linkParams("$linkParams&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")
);

$ncs = initTableData($ncs, $config->my->nc->dtable->fieldList, $this->nc);
$config->my->nc->dtable->fieldList['project']['map'] = $projects;

dtable
(
    set::cols(array_values($config->my->nc->dtable->fieldList)),
    set::data(array_values($ncs)),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager())
);

render();
