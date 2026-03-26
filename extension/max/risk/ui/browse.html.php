<?php
/**
 * The browse file of risk module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     risk
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('pageSummary', $lang->risk->pageSummary);
jsVar('checkedSummary', $lang->risk->checkedSummary);
jsVar('browseType', $browseType);

$linkParams = "projectID={$projectID}&from={$from}&browseType={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerpage={$pager->recPerPage}";
featureBar
(
    set::current($browseType),
    set::linkParams($linkParams),
    li(searchToggle(set::module('risk'), set::open($browseType == 'bysearch')))
);
$hasRisklib          = helper::hasFeature('risklib');
$canChangeObject     = $object && common::canModify($from, $object);
$canImportFromLib    = ($canChangeObject && common::hasPriv('risk', 'importFromLib') && $hasRisklib);
$canBatchImportToLib = ($canChangeObject && common::hasPriv('risk', 'batchImportToLib') && $hasRisklib);
$canCreate           = $canChangeObject && common::hasPriv('risk', 'create');
$canBatchCreate      = $canChangeObject && common::hasPriv('risk', 'batchCreate');
$exportItem          = array('text' => $lang->bug->export, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('risk', 'export', "objectID={$projectID}&browseType={$browseType}&orderBy={$orderBy}"));
$importItem          = array('text' => $lang->risk->importFromLib, 'data-app' => $app->tab, 'url' => createLink('risk', 'importFromLib', "projectID={$projectID}&from={$from}"));
$createLink          = createLink('risk', 'create', "projectID={$projectID}&from={$from}");
$batchCreateLink     = createLink('risk', 'batchCreate', "projectID={$projectID}&from={$from}");
$createItem          = array('text' => $lang->risk->create,      'url' => $createLink);
$batchCreateItem     = array('text' => $lang->risk->batchCreate, 'url' => $batchCreateLink);

toolbar
(
    hasPriv('risk', 'export') ? dropdown
    (
        btn
        (
            setClass('btn ghost dropdown-toggle'),
            set::icon('export'),
            $lang->export
        ),
        set::items(array($exportItem)),
        set::placement('bottom-end')
    ) : null,
    $canImportFromLib ? dropdown
    (
        btn
        (
            setClass('btn ghost dropdown-toggle'),
            set::icon('import'),
            $lang->import
        ),
        set::items(array($importItem)),
        set::placement('bottom-end')
    ) : null,
    $canCreate && $canBatchCreate ? btngroup
    (
        btn(setClass('btn primary create-risk-btn'), set::icon('plus'), set::url($createLink), $lang->risk->create),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array($createItem, $batchCreateItem)),
            set::placement('bottom-end')
        )
    ) : null,
    $canCreate && !$canBatchCreate ? item(set($createItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null,
    $canBatchCreate && !$canCreate ? item(set($batchCreateItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null

);


$cols = $config->risk->dtable->fieldList;
$cols['actions']['list']['edit']['url']['params'] = str_replace('{from}', $from, $cols['actions']['list']['edit']['url']['params']);
$cols['name']['link']['url']['params'] = "riskID={id}&from={$from}";
if(!$canChangeObject) unset($cols['actions']['list']);

$risks = initTableData($risks, $cols, $this->risk);

dtable
(
    set::cols($cols),
    set::data($risks),
    set::userMap($users),
    set::priList($lang->risk->priList),
    set::sortLink(createLink('risk', 'browse', "projectID={$projectID}&from={$from}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::checkInfo(jsRaw('function(checks){return window.setStatistics(this, checks);}')),
    set::footToolbar(array('items' => array(array('text' => $lang->risk->importToLib, 'btnType' => 'secondary', 'data-toggle' => 'modal', 'data-target' => '#importToLib', 'data-size' => 'sm'))))
);

modal
(
    on::click('button[type="submit"]', 'getCheckedCaseIdList'),
    setID('importToLib'),
    set::modalProps(array('title' => $lang->risk->importToLib)),
    formPanel
    (
        set::url(createLink('risk', 'batchImportToLib')),
        set::actions(array('submit')),
        set::submitBtnText($lang->import),
        formRow
        (
            formGroup
            (
                set::label($lang->risk->lib),
                set::name('lib'),
                set::items($libs),
                set::value(''),
                set::required(true)
            )
        ),
        !common::hasPriv('assetlib', 'approveIssue') && !common::hasPriv('assetlib', 'batchApproveIssue') ? formRow
        (
            formGroup(set::label($lang->risk->approver), set::name('assignedTo'), set::items($approvers))
        ) : null,
        formRow
        (
            setClass('hidden'),
            formGroup
            (
                set::name('riskIdList'),
                set::value('')
            )
        )
    )
);
