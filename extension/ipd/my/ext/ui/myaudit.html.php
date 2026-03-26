<?php
/**
 * The myAudit view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar(set::current($browseType), set::linkParams("mode={$mode}&type={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"));

$cols = $this->loadModel('datatable')->getSetting($this->moduleName, 'myaudit');
$cols['category']['map'] = $lang->baseline->objectList;
$cols['project']['map']  = $projectPairs;
$cols['product']['map']  = $productPairs;

$reviewList  = initTableData($reviewList, $cols, $this->review);
$waitReviews = $reviewingReviews = $passReviews = $auditingReviews = $doneReviews = 0;
foreach($reviewList as $reviewID => $review)
{
    if($review->status === 'wait')      $waitReviews ++;
    if($review->status === 'reviewing') $reviewingReviews ++;
    if($review->status === 'pass')      $passReviews ++;
    if($review->status === 'auditing')  $auditingReviews ++;
    if($review->status === 'done')      $doneReviews ++;

    if(!empty($review->reviewedBy))
    {
        $reviewedBy = array();
        foreach(explode(',', $review->reviewedBy) as $reviewer) $reviewedBy[$reviewer] = $users[$reviewer];
        $reviewList[$reviewID]->reviewedBy = implode(',', array_filter($reviewedBy));
    }

    foreach($review->actions as $actionID => $action)
    {
        if(!empty($ipdProjects[$review->project]) && !in_array($action, array('submit', 'recall', 'assess', 'progress', 'report', 'edit'))) unset($reviewList[$reviewID]->actions[$actionID]);
        if($action['name'] == 'recall' && $this->approval->canCancel($review)) $reviewList[$reviewID]->actions[$actionID]['disabled'] = false;
    }
}
$checkInfo = str_replace(array('%total%', '%wait%', '%reviewing%', '%pass%', '%auditing%', '%done%'), array(count($reviewList), $waitReviews, $reviewingReviews, $passReviews, $auditingReviews, $doneReviews), $lang->review->pageAllSummary);

dtable
(
    set::cols(array_values($cols)),
    set::data(array_values($reviewList)),
    set::userMap($users),
    set::customCols(true),
    set::checkable(false),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$browseType}&param=&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::checkInfo(jsRaw("function(checkedIDList){return {html: '{$checkInfo}'}}")),
    set::footer(array($checkInfo, 'flex', 'pager')),
    set::footPager(usePager())
);
