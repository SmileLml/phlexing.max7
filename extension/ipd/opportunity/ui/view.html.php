<?php
/**
 * The view view file of opportunity module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     opportunity
 * @link        https://www.zentao.net
 */
namespace zin;

$canModify = !$opportunity->deleted;
if($canModify && $project) $canModify = common::canModify('project', $project);
if($canModify && $execution) $canModify = common::canModify('execution', $execution);

$actions = $canModify ? $this->loadModel('common')->buildOperateMenu($opportunity) : array();
if(!empty($actions)) $actions = array_merge($actions['mainActions'], $actions['suffixActions']);
foreach($actions as $key => $action)
{
    if(isset($actions[$key]['url']))
    {
        $actions[$key]['url'] = str_replace('{from}', $from, $action['url']);
    }
}

$sections = array();
$sections[] = setting()
    ->title($lang->opportunity->desc)
    ->control('html')
    ->content($opportunity->desc);

$sections[] = setting()
    ->title($lang->opportunity->prevention)
    ->control('html')
    ->content($opportunity->prevention);

$sections[] = setting()
    ->title($lang->opportunity->resolution)
    ->control('html')
    ->content($opportunity->resolution);

$basicInfoItems = array();
$basicInfoItems[$lang->opportunity->source]   = array('control' => 'text', 'text' => zget($lang->opportunity->sourceList, $opportunity->source));
$basicInfoItems[$lang->opportunity->type]     = array('control' => 'text', 'text' => zget($lang->opportunity->typeList, $opportunity->type));
$basicInfoItems[$lang->opportunity->strategy] = array('control' => 'text', 'text' => zget($lang->opportunity->strategyList, $opportunity->strategy));
$basicInfoItems[$lang->opportunity->status]   = array('control' => 'text', 'text' => $this->processStatus('opportunity', $opportunity), 'contentClass' => 'status-' . $opportunity->status);
if(!empty($project->multiple)) $basicInfoItems[$lang->opportunity->execution] = array('control' => 'link', 'text' => !empty($execution->name) ? $execution->name : '', 'url' => createLink('execution', 'view', "executionID={$opportunity->execution}"));
$basicInfoItems[$lang->opportunity->impact]            = array('control' => 'text', 'text' => zget($lang->opportunity->impactList, $opportunity->impact));
$basicInfoItems[$lang->opportunity->chance]            = array('control' => 'text', 'text' => zget($lang->opportunity->chanceList, $opportunity->chance));
$basicInfoItems[$lang->opportunity->ratio]             = array('control' => 'text', 'text' => $opportunity->ratio);
$basicInfoItems[$lang->opportunity->pri]               = array('control' => 'pri',  'text' => $lang->opportunity->priList, 'pri' => $opportunity->pri);
$basicInfoItems[$lang->opportunity->identifiedDate]    = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->identifiedDate)    ? '' : $opportunity->identifiedDate);
$basicInfoItems[$lang->opportunity->plannedClosedDate] = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->plannedClosedDate) ? '' : $opportunity->plannedClosedDate);
$basicInfoItems[$lang->opportunity->actualClosedDate]  = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->actualClosedDate)  ? '' : $opportunity->actualClosedDate);
$basicInfo = datalist
(
    set::className('opportunity-basic-info'),
    set::items($basicInfoItems)
);

$lifeTimeItems = array();
$lifeTimeItems[$lang->opportunity->assignedTo]       = array('control' => 'text', 'text' => zget($users, $opportunity->assignedTo));
$lifeTimeItems[$lang->opportunity->lastCheckedBy]    = array('control' => 'text', 'text' => zget($users, $opportunity->lastCheckedBy));
$lifeTimeItems[$lang->opportunity->lastCheckedDate]  = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->lastCheckedDate) ? '' : $opportunity->lastCheckedDate);
$lifeTimeItems[$lang->opportunity->createdBy]        = array('control' => 'text', 'text' => zget($users, $opportunity->createdBy));
$lifeTimeItems[$lang->opportunity->createdDate]      = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->createdDate) ? '' : $opportunity->createdDate);
$lifeTimeItems[$lang->opportunity->editedBy]         = array('control' => 'text', 'text' => zget($users, $opportunity->editedBy));
$lifeTimeItems[$lang->opportunity->editedDate]       = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->editedDate)   ? '' : $opportunity->editedDate);
$lifeTimeItems[$lang->opportunity->assignedDate]     = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->assignedDate) ? '' : $opportunity->assignedDate);
$lifeTimeItems[$lang->opportunity->resolvedBy]       = array('control' => 'text', 'text' => zget($users, $opportunity->resolvedBy));
$lifeTimeItems[$lang->opportunity->resolvedDate]     = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->resolvedDate) ? '' : $opportunity->resolvedDate);
$lifeTimeItems[$lang->opportunity->actualClosedDate] = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->actualClosedDate) ? '' : $opportunity->actualClosedDate);
$lifeTimeItems[$lang->opportunity->canceledBy]       = array('control' => 'text', 'text' => zget($users, $opportunity->canceledBy));
$lifeTimeItems[$lang->opportunity->canceledDate]     = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->canceledDate) ? '' : $opportunity->canceledDate);
$lifeTimeItems[$lang->opportunity->cancelReason]     = array('control' => 'text', 'text' => zget($lang->opportunity->cancelReasonList, $opportunity->cancelReason));
$lifeTimeItems[$lang->opportunity->hangupedBy]       = array('control' => 'text', 'text' => zget($users, $opportunity->hangupedBy));
$lifeTimeItems[$lang->opportunity->hangupedDate]     = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->hangupedDate) ? '' : $opportunity->hangupedDate);
$lifeTimeItems[$lang->opportunity->activatedBy]      = array('control' => 'text', 'text' => zget($users, $opportunity->activatedBy));
$lifeTimeItems[$lang->opportunity->activatedDate]    = array('control' => 'text', 'text' => helper::isZeroDate($opportunity->activatedDate) ? '' : $opportunity->activatedDate);
$lifeTime = datalist
(
    set::className('opportunity-lift-item'),
    set::items($lifeTimeItems)
);

$tabs = array();
$tabs[] = setting()
    ->group('basic')
    ->title($lang->opportunity->legendBasicInfo)
    ->children(wg($basicInfo));

$tabs[] = setting()
    ->group('life')
    ->title($lang->opportunity->legendLifeTime)
    ->children(wg($lifeTime));

detail
(
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);

modal
(
    setID('importToLib'),
    set::modalProps(array('title' => $lang->opportunity->importToLib)),
    form
    (
        setID('importToLibForm'),
        setClass('text-center py-4'),
        set::actions(array('submit')),
        set::submitBtnText($lang->import),
        set::url(createLink('opportunity', 'importToLib', "opportunityID={$opportunity->id}")),
        formGroup
        (
            setClass('text-left'),
            set::label($lang->opportunity->lib),
            set::name('lib'),
            set::required(true),
            set::items($libs)
        ),
        !common::hasPriv('assetlib', 'approveOpportunity') && !common::hasPriv('assetlib', 'batchApproveOpportunity') ? formGroup
        (
            setClass('text-left'),
            set::label($lang->opportunity->approver),
            set::control(array('control' => 'picker', 'required' => true)),
            set::name('assignedTo'),
            set::items($approvers)
        ) : null
    )
);
