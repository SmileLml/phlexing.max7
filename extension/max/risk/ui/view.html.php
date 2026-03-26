<?php
/**
 * The view view file of risk module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     risk
 * @link        https://www.zentao.net
 */

namespace zin;

$canModify = !$risk->deleted;
if($canModify && $project) $canModify = common::canModify('project', $project);
if($canModify && $execution) $canModify = common::canModify('execution', $execution);
$actions = $canModify ? $this->loadModel('common')->buildOperateMenu($risk) : array();
if(!empty($actions)) $actions = array_merge($actions['mainActions'], $actions['suffixActions']);
foreach($actions as $key => $action)
{
    if(isset($actions[$key]['url']))
    {
        $actions[$key]['url'] = str_replace('{from}', $from, $action['url']);
    }
}

$basicInfoItems = array();
$basicInfoItems[$lang->risk->id]                = array('control' => 'text', 'text' => $risk->id);
$basicInfoItems[$lang->risk->source]            = array('control' => 'text', 'text' => zget($lang->risk->sourceList, $risk->source));
if(!empty($project->multiple)) $basicInfoItems[$lang->risk->execution] = array('control' => 'link', 'text' => !empty($execution->name) ? $execution->name : '', 'url' => createLink('execution', 'view', "executionID={$risk->execution}"));
$basicInfoItems[$lang->risk->category]          = array('control' => 'text', 'text' => zget($lang->risk->categoryList, $risk->category));
$basicInfoItems[$lang->risk->strategy]          = array('control' => 'text', 'text' => zget($lang->risk->strategyList, $risk->strategy));
$basicInfoItems[$lang->risk->status]            = array('control' => 'text', 'text' => $this->processStatus('risk', $risk), 'contentClass' => 'status-' . $risk->status);
$basicInfoItems[$lang->risk->impact]            = array('control' => 'text', 'text' => zget($lang->risk->impactList, $risk->impact));
$basicInfoItems[$lang->risk->probability]       = array('control' => 'text', 'text' => zget($lang->risk->probabilityList, $risk->probability));
$basicInfoItems[$lang->risk->rate]              = array('control' => 'text', 'text' => $risk->rate);
$basicInfoItems[$lang->risk->pri]               = array('control' => 'pri',  'text' => $lang->risk->priList, 'pri' => $risk->pri);
$basicInfoItems[$lang->risk->identifiedDate]    = array('control' => 'text', 'text' => helper::isZeroDate($risk->identifiedDate)    ? '' : $risk->identifiedDate);
$basicInfoItems[$lang->risk->plannedClosedDate] = array('control' => 'text', 'text' => helper::isZeroDate($risk->plannedClosedDate) ? '' : $risk->plannedClosedDate);
$basicInfoItems[$lang->risk->actualClosedDate]  = array('control' => 'text', 'text' => helper::isZeroDate($risk->actualClosedDate)  ? '' : $risk->actualClosedDate);
$basicInfo = datalist
(
    set::className('risk-basic-info'),
    set::items($basicInfoItems)
);

$lifeTimeItems = array();
$lifeTimeItems[$lang->risk->assignedTo]       = array('control' => 'text', 'text' => zget($users, $risk->assignedTo));
$lifeTimeItems[$lang->risk->trackedBy]        = array('control' => 'text', 'text' => zget($users, $risk->trackedBy));
$lifeTimeItems[$lang->risk->trackedDate]      = array('control' => 'text', 'text' => helper::isZeroDate($risk->trackedDate) ? '' : $risk->trackedDate);
$lifeTimeItems[$lang->risk->createdBy]        = array('control' => 'text', 'text' => zget($users, $risk->createdBy));
$lifeTimeItems[$lang->risk->createdDate]      = array('control' => 'text', 'text' => helper::isZeroDate($risk->createdDate) ? '' : $risk->createdDate);
$lifeTimeItems[$lang->risk->editedBy]         = array('control' => 'text', 'text' => zget($users, $risk->editedBy));
$lifeTimeItems[$lang->risk->editedDate]       = array('control' => 'text', 'text' => helper::isZeroDate($risk->editedDate)   ? '' : $risk->editedDate);
$lifeTimeItems[$lang->risk->assignedDate]     = array('control' => 'text', 'text' => helper::isZeroDate($risk->assignedDate) ? '' : $risk->assignedDate);
$lifeTimeItems[$lang->risk->resolvedBy]       = array('control' => 'text', 'text' => zget($users, $risk->resolvedBy));
$lifeTimeItems[$lang->risk->actualClosedDate] = array('control' => 'text', 'text' => helper::isZeroDate($risk->actualClosedDate) ? '' : $risk->actualClosedDate);
$lifeTimeItems[$lang->risk->cancelBy]         = array('control' => 'text', 'text' => zget($users, $risk->cancelBy));
$lifeTimeItems[$lang->risk->cancelDate]       = array('control' => 'text', 'text' => helper::isZeroDate($risk->cancelDate) ? '' : $risk->cancelDate);
$lifeTimeItems[$lang->risk->cancelReason]     = array('control' => 'text', 'text' => zget($lang->risk->cancelReasonList, $risk->cancelReason));
$lifeTimeItems[$lang->risk->hangupBy]         = array('control' => 'text', 'text' => zget($users, $risk->hangupBy));
$lifeTimeItems[$lang->risk->hangupDate]       = array('control' => 'text', 'text' => helper::isZeroDate($risk->hangupDate) ? '' : $risk->hangupDate);
$lifeTimeItems[$lang->risk->activateBy]       = array('control' => 'text', 'text' => zget($users, $risk->activateBy));
$lifeTimeItems[$lang->risk->activateDate]     = array('control' => 'text', 'text' => helper::isZeroDate($risk->activateDate) ? '' : $risk->activateDate);
$lifeTime = datalist
(
    set::className('risk-lift-item'),
    set::items($lifeTimeItems)
);

$tabs = array();
$tabs[] = setting()
    ->group('basic')
    ->title($lang->risk->legendBasicInfo)
    ->children(wg($basicInfo));

$tabs[] = setting()
    ->group('basic')
    ->title($lang->risk->legendLifeTime)
    ->children(wg($lifeTime));

$tabs[] = setting()
    ->group('misc')
    ->title($lang->custom->relateObject)
    ->control('relatedObjectList')
    ->objectID($risk->id)
    ->objectType('risk')
    ->browseType('byObject');

$sections = array();
$sections[] = setting()
    ->title($lang->risk->prevention)
    ->control('html')
    ->content($risk->prevention);

$sections[] = setting()
    ->title($lang->risk->remedy)
    ->control('html')
    ->content($risk->remedy);

$sections[] = setting()
    ->title($lang->risk->resolution)
    ->control('html')
    ->content($risk->resolution);

detail
(
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);

modal
(
    setID('importToLib'),
    set::modalProps(array('title' => $lang->risk->importToLib)),
    form
    (
        setID('importToLibForm'),
        setClass('text-center py-4'),
        set::actions(array('submit')),
        set::submitBtnText($lang->import),
        set::url(createLink('risk', 'importToLib', "riskID={$risk->id}")),
        formGroup(set::label($lang->risk->lib), set::required(true), setClass('text-left'), picker
        (
            set::name('lib'),
            set::required(true),
            set::items($libs)
        )),
        !common::hasPriv('assetlib', 'approveRisk') && !common::hasPriv('assetlib', 'batchApproveRisk') ? formGroup
        (
            set::label($lang->risk->approver),
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
