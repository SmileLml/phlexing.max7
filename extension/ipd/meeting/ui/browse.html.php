<?php
/**
 * The browse view file of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     meeting
 * @link        https://www.zentao.net
 */
namespace zin;

$moduleName = $app->tab == 'my' ? 'my' : 'meeting';
$methodName = $app->tab == 'my' ? 'meeting' : 'browse';
$cols = $this->loadModel('datatable')->getSetting($moduleName, $methodName);
if(isset($cols['project'])) $cols['project']['map']   = $projects;
if(isset($cols['room']))    $cols['room']['map']      = $rooms;
if(isset($cols['dept']))    $cols['dept']['map']      = $depts;

if($projectID && empty($project->multiple)) unset($cols['execution']);
if(isset($cols['execution'])) $cols['execution']['map'] = $executions;

if(isset($cols['actions'])) $cols['actions']['list']['edit']['url']['params'] = str_replace('{from}', $from, $cols['actions']['list']['edit']['url']['params']);

$meetings = initTableData($meetings, $cols, $this->meeting);

$linkParams = '';
foreach($app->rawParams as $key => $value) $linkParams = $key != 'orderBy' ? "{$linkParams}&{$key}={$value}" : "{$linkParams}&orderBy={name}_{sortType}";

$params = $app->tab == 'my' ? "browseType={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}" : "objectID={$objectID}&from={$from}&browseType={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
featureBar
(
    set::current($browseType),
    set::linkParams($params),
    li(searchToggle(set::open($browseType == 'bysearch'), set::module('meeting')))
);

toolbar
(
    (empty($project) || common::canModify(data('from'), $project)) && hasPriv('meeting', 'create') ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(createLink('meeting', 'create', "projectID={$projectID}&executionID={$executionID}&from={$from}")),
        setData('app', $from),
        $lang->meeting->create
    ) : null
);

dtable
(
    set::cols($cols),
    set::data($meetings),
    set::customCols(true),
    set::moduleName($moduleName),
    set::methodName($methodName),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(createLink($app->rawModule, $app->rawMethod, $linkParams)),
    set::footPager(usePager())
);
