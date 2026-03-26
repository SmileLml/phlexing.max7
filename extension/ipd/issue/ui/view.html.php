<?php
/**
 * The view view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

$canModify = !$issue->deleted;
if($canModify && $project) $canModify = common::canModify('project', $project);
if($canModify && $execution) $canModify = common::canModify('execution', $execution);
$actions = $canModify ? $this->loadModel('common')->buildOperateMenu($issue) : array();
if(!empty($actions)) $actions = array_merge($actions['mainActions'], $actions['suffixActions']);

foreach($actions as $key => $action)
{
    if(isset($actions[$key]['url']))
    {
        $actions[$key]['url'] = str_replace('{from}', $from, $action['url']);
    }
}

$sections   = array();
$sections[] = setting()
    ->title($lang->issue->desc)
    ->control('html')
    ->content($issue->desc);

if($issue->files)
{
    $sections[] = array
    (
        'control' => 'fileList',
        'files'   => $issue->files,
        'object'  => $issue,
        'padding' => false
    );
}

$basicInfoItems = array();
$basicInfoItems[$lang->issue->type]         = array('control' => 'text',          'text' => zget($lang->issue->typeList, $issue->type));
$basicInfoItems[$lang->issue->severity]     = array('control' => 'severitylabel', 'text' => zget($lang->issue->severityList, $issue->severity), 'level' => $issue->severity);
if(!empty($project->multiple)) $basicInfoItems[$lang->issue->execution] = $issue->execution ? array('control' => 'link', 'url' => createLink('execution', 'view', "executionID={$issue->execution}"), 'text' => zget($execution, 'name', '')) : array();
$basicInfoItems[$lang->issue->pri]          = array('control' => 'pri', 'text' => zget($lang->issue->priList, $issue->pri), 'pri' => $issue->pri);
$basicInfoItems[$lang->issue->deadline]     = array('control' => 'text', 'text' => helper::isZeroDate($issue->deadline) ? '' : $issue->deadline);
$basicInfoItems[$lang->issue->resolvedDate] = array('control' => 'text', 'text' => helper::isZeroDate($issue->resolvedDate) ? '' : $issue->resolvedDate);
$basicInfoItems[$lang->issue->owner]        = array('control' => 'text', 'text' => zget($users, $issue->owner, ''));
$basicInfoItems[$lang->issue->assignedTo]   = array('control' => 'text', 'text' => $issue->assignedTo ? zget($users, $issue->assignedTo) . $lang->at . $issue->assignedDate : '');
$basicInfoItems[$lang->issue->status]       = array('control' => 'text', 'text' => zget($lang->issue->statusList, $issue->status, ''));

$basicInfo = datalist
(
    set::className('issue-basic-info'),
    set::items($basicInfoItems)
);

$lifeTimeItems = array();
$lifeTimeItems[$lang->issue->createdBy]    = array('control' => 'text', 'text' => zget($users, $issue->createdBy));
$lifeTimeItems[$lang->issue->createdDate]  = array('control' => 'text', 'text' => helper::isZeroDate($issue->createdDate) ? '' : $issue->createdDate);
$lifeTimeItems[$lang->issue->assignedBy]   = array('control' => 'text', 'text' => zget($users, $issue->assignedBy));
$lifeTimeItems[$lang->issue->assignedDate] = array('control' => 'text', 'text' => helper::isZeroDate($issue->assignedDate) ? '' : $issue->assignedDate);
$lifeTimeItems[$lang->issue->closedBy]     = array('control' => 'text', 'text' => zget($users, $issue->closedBy));
$lifeTimeItems[$lang->issue->closedDate]   = array('control' => 'text', 'text' => helper::isZeroDate($issue->closedDate) ? '' : $issue->closedDate);
$lifeTimeItems[$lang->issue->activateBy]   = array('control' => 'text', 'text' => zget($users, $issue->activateBy));
$lifeTimeItems[$lang->issue->activateDate] = array('control' => 'text', 'text' => helper::isZeroDate($issue->activateDate) ? '' : $issue->activateDate);
$lifeTimeItems[$lang->issue->editedBy]     = array('control' => 'text', 'text' => zget($users, $issue->editedBy));
$lifeTimeItems[$lang->issue->editedDate]   = array('control' => 'text', 'text' => helper::isZeroDate($issue->editedDate) ? '' : $issue->editedDate);

$lifeTime = datalist
(
    set::className('issue-lift-item'),
    set::items($lifeTimeItems)
);

$tabs = array();
$tabs[] = setting()
    ->group('basic')
    ->class('issue-basic-info')
    ->title($lang->issue->basicInfo)
    ->children(wg($basicInfo));

$tabs[] = setting()
    ->group('basic')
    ->class('issue-left-info')
    ->title($lang->issue->lifeTime)
    ->children(wg($lifeTime));

$tabs[] = setting()
    ->group('misc')
    ->title($lang->custom->relateObject)
    ->control('relatedObjectList')
    ->objectID($issue->id)
    ->objectType('issue')
    ->browseType('byObject');

detail
(
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);

modal
(
    setID('importToLib'),
    set::modalProps(array('title' => $lang->issue->importToLib)),
    form
    (
        setID('importToLibForm'),
        setClass('text-center py-4'),
        set::actions(array('submit')),
        set::submitBtnText($lang->import),
        set::url(createLink('issue', 'importToLib', "issueID={$issue->id}")),
        formGroup(set::label($lang->issue->lib), set::required(true), setClass('text-left'), picker
        (
            set::name('lib'),
            set::required(true),
            set::items($libs)
        )),
        !common::hasPriv('assetlib', 'approveIssue') && !common::hasPriv('assetlib', 'batchApproveIssue') ? formGroup
        (
            set::label($lang->issue->approver),
            setClass('text-left'),
            picker
            (
                set::name('assignedTo'),
                set::required(true),
                set::items($approvers)
            )
        ) : null
    )

);
