<?php
/**
 * The browse view file of review module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     review
 * @link        https://www.zentao.net
 */
namespace zin;

$queryMenuLink = createLink('bug', 'browse', "projectID={$projectID}&browseType={$browseType}&orderBy={$orderBy}&param={queryID}");
featureBar
(
    set::current($browseType),
    set::linkParams("project={$projectID}&browseType={key}&orderBy={$orderBy}&param={$param}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}"),
    set::queryMenuLinkCallback(array(function ($key) use ($queryMenuLink) {
        return str_replace('{queryID}', (string)$key, $queryMenuLink);
    })),
    li(searchToggle(set::open($browseType == 'bysearch')))
);

$canBeChanged = common::canModify('project', $project);

if(!isInModal())
{
    toolbar
    (
        $canBeChanged && hasPriv('review', 'create') ? btn
        (
            setClass('primary review-create-btn'),
            set::icon('plus'),
            set::url(createLink('review', 'create', "project={$projectID}")),
            $lang->review->create
        ) : null
    );
}

if($project->model == 'ipd') $config->review->dtable->fieldList['actions']['menu'] = array('submit', 'recall', 'assess', 'progress', 'report', 'edit');
$cols = $this->loadModel('datatable')->getSetting('review');
$cols['product']['map']  = $products;
$cols['category']['map'] = $lang->baseline->objectList;
if(!$project->hasProduct) unset($cols['product']);

if($browseType == 'reviewing' || $browseType == 'done')
{
    $checkInfo = sprintf($lang->review->pageSummary, count($reviewList));
}
else
{
    $waitReviews = $reviewingReviews = $passReviews = $auditingReviews = $doneReviews = 0;
    foreach($reviewList as $review)
    {
        if($review->status === 'wait')      $waitReviews ++;
        if($review->status === 'reviewing') $reviewingReviews ++;
        if($review->status === 'pass')      $passReviews ++;
        if($review->status === 'auditing')  $auditingReviews ++;
        if($review->status === 'done')      $doneReviews ++;
    }
    $checkInfo = str_replace(array('%total%', '%wait%', '%reviewing%', '%pass%', '%auditing%', '%done%'), array(count($reviewList), $waitReviews, $reviewingReviews, $passReviews, $auditingReviews, $doneReviews), $lang->review->pageAllSummary);
}

$reviewList = initTableData($reviewList, $cols, $this->review);
foreach($reviewList as $reviewID => $review)
{
    if(!empty($review->reviewedBy))
    {
        $reviewedBy = array();
        foreach(explode(',', $review->reviewedBy) as $reviewer) $reviewedBy[$reviewer] = $users[$reviewer];
        $reviewList[$reviewID]->reviewedBy = implode(',', array_filter($reviewedBy));
    }
    foreach($review->actions as $actionID => $action)
    {
        if($action['name'] == 'recall' && $this->approval->canCancel($review)) $reviewList[$reviewID]->actions[$actionID]['disabled'] = false;
    }
}

dtable
(
    set::cols($cols),
    set::data(array_values($reviewList)),
    set::userMap($users),
    set::customCols(true),
    set::checkable(false),
    set::orderBy($orderBy),
    set::sortLink(inlink('browse', "project={$projectID}&browseType={$browseType}&orderBy={name}_{sortType}&param={$param}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::checkInfo(jsRaw("function(checkedIDList){return {html: '{$checkInfo}'}}")),
    set::footPager(usePager()),
    set::footer(array($checkInfo, 'flex','pager')),
    set::createTip($lang->review->create),
    set::createLink($canBeChanged && hasPriv('review', 'create') ? createLink('review', 'create', "project={$projectID}") : '')
);

render();
