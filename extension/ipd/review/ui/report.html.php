<?php
/**
 * The report view file of review module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     review
 * @link        https://www.zentao.net
 */
namespace zin;

$n = 0;
foreach($approvalIDList as &$approvalItem)
{
    $n++;
    $approvalItem = sprintf($lang->review->selectApprovalText, $n);
}

div
(
    setClass('detail-header row gap-2 items-center flex-none justify-between'),
    div
    (
        setClass('row gap-2 items-center flex-none'),
        !$app->session->notHead ? backBtn($lang->goback, setClass('mr-2 size-md primary-outline'), set::icon('back')) : null,
        entityTitle(setClass('min-w-0'), set::id($review->id), set::object($review), set::title($review->title), set::titleClass('text-lg text-clip font-bold'), set::type('review'), set::joinerClass('text-lg')),
        icon('angle-right'),
        span(setClass('text-lg text-clip font-bold'), $lang->review->report->common),
        !$app->session->notHead ? picker(on::change()->call('changeReview', jsRaw('event')), setClass('w-44'), set::name('approval'), set::items($approvalIDList), set::required(true), set::value($approvalID ? $approvalID : '')) : null
    ),
    !$app->session->notHead && common::hasPriv('review', 'exportReport') ? btn
    (
        set::text($lang->export),
        set::icon('export'),
        set::className('ghost'),
        setData(array('toggle' => 'modal', 'size' => 'sm')),
        set::url(helper::createLink('review', 'exportReport', "reviewID={$reviewID}&approvalID={$approvalID}"))
    ) : null
);

pageJS(<<<JAVASCRIPT
changeReview = function(event)
{
    loadPage($.createLink('review', 'report', 'reviewID=' + $reviewID + '&approvalID=' + $(event.target).val()));
};
JAVASCRIPT
);

$objectScale = (float)$objectScale;
$consumed    = 0;
$accountConsumed = array();
unset($efforts['typeList']);
foreach($efforts as $effort)
{
    $accountConsumed[$effort->account][] = $effort->consumed;
    $consumed += empty($effort->consumed) ? 0 : $effort->consumed;
}

panel
(
    setClass('mt-2'),
    h::table
    (
        setClass('table bordered table-fixed'),
        h::thead(h::tr(h::th(setClass('text-center text-lg'), set::colspan('8'), $lang->review->explain))),
        h::tbody
        (
            h::tr(h::th(setClass('text-left'), set::colspan('2'), $lang->review->object), h::td(set::colspan('2'), zget($lang->baseline->objectList, $review->category)), h::th(setClass('text-left'), set::colspan('2'), $lang->review->reviewerCount), h::td(set::colspan('2'), $reviewerCount)),
            h::tr(h::th(setClass('text-left'), set::colspan('2'), $lang->review->reviewedDate), h::td(set::colspan('2'), isset($approvalNode[0]->reviewedDate) && !helper::isZeroDate($approvalNode[0]->reviewedDate) ? $approvalNode[0]->reviewedDate : ''), h::th(setClass('text-left'), set::colspan('2'), $lang->review->reviewedHours), h::td(set::colspan('2'), floor($consumed))),
            h::tr(h::th
            (
                setClass('text-left'),
                set::colspan('2'),
                $lang->review->issueCount,
                icon('help', setClass('ml-0.5'), toggle::tooltip(array('title' => $lang->review->issueCountTip, 'placement' => 'bottom', 'type' => 'white', 'className' => 'text-dark border border-light leading-5')))
            ), h::td(set::colspan('2'), count($issues)), h::th
            (
                setClass('text-left'),
                set::colspan('2'),
                $lang->review->objectScale,
                icon('help', setClass('ml-0.5'), toggle::tooltip(array('title' => $lang->review->objectScaleTip, 'placement' => 'bottom', 'type' => 'white', 'className' => 'text-dark border border-light leading-5')))
            ), h::td(set::colspan('2'), round($objectScale, 2))),
            h::tr(h::th
            (
                setClass('text-left'),
                set::colspan('2'),
                $lang->review->issueRate,
                icon('help', setClass('ml-0.5'), toggle::tooltip(array('title' => $lang->review->issueRateTip, 'placement' => 'bottom', 'type' => 'white', 'className' => 'text-dark border border-light leading-5')))
            ), h::td(set::colspan('2'), $objectScale == 0 ? 0 : round(count($issues) / $objectScale, 2)), h::th
            (
                setClass('text-left'),
                set::colspan('2'),
                $lang->review->issueFoundRate,
                icon('help', setClass('ml-0.5'), toggle::tooltip(array('title' => $lang->review->issueFoundRateTip, 'placement' => 'bottom', 'type' => 'white', 'className' => 'text-dark border border-light leading-5')))
            ), h::td(set::colspan('2'), $consumed == 0 ? 0 : round(count($issues) / $consumed, 2)))
        )
    )
);

$issueTrs = array();
foreach($issues as $issue)
{
    $issueTrs[] = h::tr
    (
        h::td($issue->id),
        h::td(set::colspan('2'), $issue->title),
        h::td(set::colspan('2'), zget($users, $issue->createdBy)),
        h::td(setClass('text-ellipsis'), set::colspan('5'), set::title(strip_tags($issue->opinion)), html($issue->opinion))
    );
}
$issueTable = !empty($issues) ? h::table
(
    setClass('table bordered table-fixed'),
    h::thead
    (
        h::tr
        (
            h::th($lang->idAB),
            h::th(set::colspan('2'), $lang->review->issues),
            h::th(set::colspan('2'), $lang->review->reviewer),
            h::th(set::colspan('5'), $lang->comment)
        )
    ),
    h::tbody
    (
        $issueTrs
    )
) : null;

$approvalTrs = array();
foreach($approvalNode as $reviewItem)
{
    $approvalTrs[] = h::tr
    (
        h::td(!helper::isZeroDate($reviewItem->reviewedDate) ? $reviewItem->reviewedDate : ''),
        h::td(set::colspan('2'), zget($users, $reviewItem->reviewedBy)),
        h::td(zget($lang->review->resultList, $reviewItem->result)),
        h::td(!empty($accountConsumed[$reviewItem->reviewedBy]) && $reviewItem->result != 'ignore' ? array_shift($accountConsumed[$reviewItem->reviewedBy]) : 0),
        h::td(set::colspan('5'), setClass('finalOpinion'), set::title(strip_tags($reviewItem->opinion)), html($reviewItem->opinion))
    );
}
$approvalTable = !empty($approvalNode) ? h::table
(
    setClass('table bordered table-fixed'),
    h::thead
    (
        h::tr
        (
            h::th(setClass('w-32'), $lang->review->reviewedDate),
            h::th(setClass('w-16'), set::colspan('2'), $lang->review->reviewer),
            h::th(setClass('w-32'), $lang->review->reviewResult),
            h::th(setClass('w-24'), $lang->reviewresult->consumed),
            h::th(setClass('w-64'), set::colspan('5'), $lang->review->finalOpinion)
        )
    ),
    h::tbody
    (
        $approvalTrs
    )
) : null;

$reviewResult   = isset($approval->result) ? $approval->result : ($review->result == 'pass' ? 'pass' : 'fail');
$reportReviwers = '';
foreach($reviewer as $account) $reportReviwers .= zget($users, $account) . ' ';

panel
(
    setClass('mt-2.5'),
    $issueTable,
    $approvalTable,
    h::table
    (
        setClass('table bordered table-fixed'),
        h::thead
        (
            h::tr
            (
                h::th(setClass('text-center'), set::colspan('8'), $lang->review->resultExplain),
                h::th(setClass('text-center'), set::colspan('2'), $lang->review->conclusion)
            )
        ),
        h::tbody(h::tr
        (
            h::td(set::colspan('8'), $lang->review->resultExplainList['pass']),
            h::td(setClass('text-center status-' . $reviewResult), set::colspan('2'), set::rowspan('2'), zget($lang->review->resultList, $reviewResult))
        ), h::tr(h::td(set::colspan('8'), $lang->review->resultExplainList['fail'])))
    ),
    h::table
    (
        setClass('table border border-t-0 table-fixed'),
        h::tr
        (
            h::th(setClass('text-right w-1/4'), set::colspan('2'), $lang->review->reportCreatedBy),
            h::th(setClass('text-left w-1/4'), set::colspan('2'), zget($users, $review->createdBy)),
            h::th(setClass('text-right w-1/4'), set::colspan('2'), $lang->review->reportApprovedBy),
            h::th(setClass('text-left w-1/4'), set::colspan('2'), $reportReviwers)
        )
    )
);

$this->session->notHead ? render('fragment') : render();
