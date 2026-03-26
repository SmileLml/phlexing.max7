<?php
/**
 * The browse view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

$queryMenuLink  = createLink('issue', 'browse', "objectID={$projectID}&from={$from}&browseType=bysearch&queryID={queryID}");
$currentType    = $browseType == 'bysearch' ? $param : $browseType;
featureBar(set::current($currentType), set::linkParams("objectID={$projectID}&from={$from}&browseType={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), set::queryMenuLinkCallback(array(function ($key) use ($queryMenuLink) {
    return str_replace('{queryID}', (string)$key, $queryMenuLink);
})), li(searchToggle(set::module('issue'), set::open(strtolower($browseType) == 'bysearch'))));

$hasIssuelib         = helper::hasFeature('issuelib');
$canChangeObject     = common::canModify($from, $object);
$canImportFromLib    = ($canChangeObject && common::hasPriv('issue', 'importFromLib') && $hasIssuelib);
$canBatchImportToLib = ($canChangeObject && common::hasPriv('issue', 'batchImportToLib') && $hasIssuelib);
$canCreate           = $canChangeObject && common::hasPriv('issue', 'create');
$canBatchCreate      = $canChangeObject && common::hasPriv('issue', 'batchCreate');
$exportItem          = array('text' => $lang->bug->export, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('issue', 'export', "objectID={$projectID}&from={$from}&browseType={$browseType}&orderBy={$orderBy}"));
$importItem          = array('text' => $lang->issue->importFromLib, 'data-app' => $app->tab, 'url' => createLink('issue', 'importFromLib', "projectID={$projectID}&from={$from}"));
$createLink          = createLink('issue', 'create', "projectID={$projectID}&from={$from}");
$batchCreateLink     = createLink('issue', 'batchCreate', "projectID={$projectID}&from={$from}");
$createItem          = array('text' => $lang->issue->create,      'url' => $createLink);
$batchCreateItem     = array('text' => $lang->issue->batchCreate, 'url' => $batchCreateLink);

toolbar
(
    hasPriv('issue', 'export') ? dropdown
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
        btn(setClass('btn primary create-issue-btn'), set::icon('plus'), set::url($createLink), $lang->issue->create),
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

$cols = $config->issue->dtable->fieldList;
$cols['title']['link']['params'] = str_replace('{from}', $from, $cols['title']['link']['params']);
$cols['actions']['list']['edit']['url'] = str_replace('{from}', $from, $cols['actions']['list']['edit']['url']);
if($browseType == 'assignto') unset($cols['assignedTo']);
if($browseType != 'assignto') unset($cols['assignedBy']);
if(!$canChangeObject) unset($cols['actions']['list']);

$issueList = initTableData($issueList, $cols, $this->issue);

$footToolbar = array();
if($canBatchImportToLib) $footToolbar['items'][] = array('btnType' => 'secondary', 'text' => $lang->issue->importToLib, 'url' => '#batchImportToLib', 'data-toggle' => 'modal', 'data-size' => 'sm');
dtable
(
    set::cols($cols),
    set::data($issueList),
    set::fixedLeftWidth('0.44'),
    set::checkable($canBatchImportToLib),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(inlink('browse', "objectID={$projectID}&from={$from}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::createTip($lang->issue->create),
    set::createLink($canCreate ? $createLink : ''),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);

modal
(
    setID('batchImportToLib'),
    set::modalProps(array('title' => $lang->issue->importToLib)),
    form
    (
        setID('batchImportToLibForm'),
        setClass('text-center py-4'),
        on::click('button[type="submit"]', 'getCheckedIssueIdList'),
        set::actions(array('submit')),
        set::submitBtnText($lang->import),
        set::url(createLink('issue', 'batchImportToLib')),
        formGroup
        (
            set::label($lang->issue->lib),
            set::required(true),
            setClass('text-left'),
            picker
            (
                set::name('lib'),
                set::required(true),
                set::items($libs)
            ),
            formHidden('issueIDList', '')
        ),
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
