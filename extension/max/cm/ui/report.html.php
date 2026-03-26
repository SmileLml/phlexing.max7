<?php
/**
 * The report view file of cm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     cm
 * @link        https://www.zentao.net
 */
namespace zin;

if(hasPriv('cm', 'exportReport') && !isInModal() && !$this->session->notHead)
{
    toolbar
    (
        setClass('w-full justify-end'),
        btn
        (
            set::text($lang->export),
            set::icon('export'),
            set::className('ghost'),
            setData(array('toggle' => 'modal', 'size' => 'sm')),
            set::url(createLink('cm', 'exportReport', "projectID={$projectID}"))
        )
    );
}

$auditTrList = array();
foreach($report['audit'] as $type => $item)
{
    $rowspan = count($item);
    foreach($item as $audit)
    {
        $auditTrList[] = h::tr
            (
                $rowspan ? h::td(set::colspan('2'), set::rowspan($rowspan), $lang->cm->$type) : null,
                h::td(set::colspan('2'), zget($lang->baseline->objectList, $audit->category)),
                h::td(set::colspan('3'), $audit->title),
                h::td(set::colspan('3'), $audit->version),
                h::td(set::colspan('2'), !helper::isZeroDate($audit->createdDate) ? $audit->createdDate : ''),
                h::td(set::colspan('1'), zget($users, $audit->createdBy))
            );

        $rowspan = 0;
    }
}

$issueTrList = array();
foreach($report['issue'] as $issue)
{
    $issueTrList[] = h::tr
        (
            h::td($issue->id),
            h::td(set::colspan('2'), $issue->title),
            h::td(set::colspan('2'), $issue->reviewTitle),
            h::td(!helper::isZeroDate($issue->createdDate) ? $issue->createdDate : ''),
            h::td(zget($users, $issue->createdBy)),
            h::td($issue->opinion),
            h::td(!helper::isZeroDate($issue->opinionDate) ? $issue->opinionDate : ''),
            h::td(zget($lang->reviewissue->resolutionList, $issue->resolution)),
            h::td(!helper::isZeroDate($issue->resolutionDate) ? $issue->resolutionDate : ''),
            h::td(zget($users, $issue->resolutionBy)),
            h::td(zget($lang->reviewissue->statusList, $issue->status))
        );
}

panel
(
    h::table
    (
        setClass('table bordered table-fixed'),
        h::tbody
        (
            h::tr(h::td(setClass('text-center'), set::colspan('13'), h5($lang->cm->baselineReport))),
            h::tr
            (
                h::th($lang->cm->projectID),
                h::td(set::colspan('3'), $report['project']->id),
                h::th($lang->cm->release),
                h::td(set::colspan('4'), zget($lang->project->modelList, $report['project']->model)),
                h::th($lang->cm->approver),
                h::td(set::colspan('3'))
            ),
            h::tr
            (
                h::th($lang->cm->projectName),
                h::td(set::colspan('8'), $report['project']->name),
                h::th($lang->cm->compiling),
                h::td(set::colspan('3'), zget($users, $report['project']->PM))
            ),
            h::tr
            (
                setClass('text-center'),
                h::td(setClass('text-center'), set::colspan('13'), h5($lang->cm->changeDesc))
            ),
            h::tr
            (
                h::td(setClass('font-bold'), set::colspan('2'), $lang->cm->baselineItem),
                h::td(setClass('font-bold'), set::colspan('2'), $lang->cm->configItemName),
                h::td(setClass('font-bold'), set::colspan('3'), $lang->cm->configIdentify),
                h::td(setClass('font-bold'), set::colspan('3'), $lang->cm->currentVersion),
                h::td(setClass('font-bold'), set::colspan('2'), $lang->cm->releaseDate),
                h::td(setClass('font-bold'), set::colspan('1'), $lang->cm->publisher)
            ),
            $auditTrList,
            h::tr
            (
                h::td(setClass('font-bold'), set::colspan('8'), $lang->cm->configAdmin),
                h::td(setClass('font-bold'), set::colspan('4'), $lang->cm->solutionMan),
                h::td(setClass('font-bold'), $lang->cm->confManagement)
            ),
            h::tr
            (
                h::td(setClass('font-bold'), $lang->cm->issueID),
                h::td(setClass('font-bold'), set::colspan('2'), $lang->cm->issueDesc),
                h::td(setClass('font-bold'), set::colspan('2'), $lang->cm->baselineAudit),
                h::td(setClass('font-bold'), $lang->cm->discoveryDate),
                h::td(setClass('font-bold'), $lang->cm->personLiable),
                h::td(setClass('font-bold'), $lang->cm->proposedScheme),
                h::td(setClass('font-bold'), $lang->cm->proposedDate),
                h::td(setClass('font-bold'), $lang->cm->solutionResult),
                h::td(setClass('font-bold'), $lang->cm->resolvingDate),
                h::td(setClass('font-bold'), $lang->cm->resolvingBy),
                h::td(setClass('font-bold'), $lang->cm->currentState)
            ),
            $issueTrList
        )
    )
);

$this->session->notHead ? render('fragment') : render();
