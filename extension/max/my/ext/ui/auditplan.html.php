<?php
/**
 * The auditplan view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include $app->getModuleRoot() . 'my/ui/header.html.php';

featureBar
(
    set::current('auditplan'),
    set::linkParams("mode={key}&type=&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")
);

$auditplans = initTableData($auditplans, $config->my->auditplan->dtable->fieldList, $this->auditplan);
$config->my->auditplan->dtable->fieldList['process']['map']   = $processes;
$config->my->auditplan->dtable->fieldList['project']['map']   = $projects;
$config->my->auditplan->dtable->fieldList['execution']['map'] = array(0 => '') + $executions;
foreach($auditplans as $auditplan)
{
    $auditplan->objectID = $auditplan->objectType == 'activity' ? zget($activities, $auditplan->objectID) : zget($outputs, $auditplan->objectID);
}

dtable
(
    set::cols(array_values($config->my->auditplan->dtable->fieldList)),
    set::data(array_values($auditplans)),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager())
);

render();
