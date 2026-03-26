<?php
/**
 * The browse view file of opportunity module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     opportunity
 * @link        https://www.zentao.net
 */
namespace zin;

$linkParams = "projectID={$projectID}&from={$from}&browseType={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerpage={$pager->recPerPage}";
featureBar
(
    set::current($browseType),
    set::linkParams($linkParams),
    li(searchToggle(set::module('opportunity'), set::open($browseType == 'bysearch')))
);

$hasOpportunitylib   = helper::hasFeature('opportunitylib');
$canChangeObject     = $object && common::canModify($from, $object);
$canImportFromLib    = ($canChangeObject && common::hasPriv('opportunity', 'importFromLib') && $hasOpportunitylib);
$canBatchImportToLib = ($canChangeObject && common::hasPriv('opportunity', 'batchImportToLib') && $hasOpportunitylib);
$canCreate           = $canChangeObject && common::hasPriv('opportunity', 'create');
$canBatchCreate      = $canChangeObject && common::hasPriv('opportunity', 'batchCreate');
$createLink          = createLink('opportunity', 'create', "projectID={$projectID}&from={$from}");
$batchCreateLink     = createLink('opportunity', 'batchCreate', "projectID={$projectID}&from={$from}");
$createItem          = array('text' => $lang->opportunity->create,      'url' => $createLink);
$batchCreateItem     = array('text' => $lang->opportunity->batchCreate, 'url' => $batchCreateLink);
$exportItem          = array('text' => $lang->opportunity->export, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('opportunity', 'export', "objectID={$projectID}&browseType={$browseType}&orderBy={$orderBy}"));
$importItem          = array('text' => $lang->opportunity->importFromLib, 'data-app' => $app->tab, 'url' => createLink('opportunity', 'importFromLib', "projectID={$projectID}&from={$from}"));

toolbar
(
    hasPriv('opportunity', 'export') ? dropdown
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
        btn(setClass('btn primary create-opportunity-btn'), set::icon('plus'), set::url($createLink), $lang->opportunity->create),
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

$canBatchEdit        = common::hasPriv('opportunity', 'batchEdit');
$canBatchAssignTo    = common::hasPriv('opportunity', 'batchAssignTo');
$canBatchClose       = common::hasPriv('opportunity', 'batchClose');
$canBatchCancel      = common::hasPriv('opportunity', 'batchCancel');
$canBatchHangup      = common::hasPriv('opportunity', 'batchHangup');
$canBatchActivate    = common::hasPriv('opportunity', 'batchActivate');
$canBatchImportToLib = (common::hasPriv('opportunity', 'batchImportToLib') and $hasOpportunitylib);
$canBatchAction      = $canChangeObject && ($canBatchEdit or $canBatchAssignTo or $canBatchClose or $canBatchCancel or $canBatchHangup or $canBatchActivate or $canBatchImportToLib);

$footToolbar = array();
if($canBatchAction)
{
    $cancelItems = array();
    foreach($lang->opportunity->cancelReasonList as $key => $cancelReason)
    {
        if(empty($key)) continue;
        $cancelItems[] = array('text' => $cancelReason, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => createLink('opportunity', 'batchCancel', "cancelReason={$key}"));
    }
    if($canBatchClose || $canBatchCancel || $canBatchHangup || $canBatchActivate)
    {
        $batchItems = array
        (
            array('text' => $lang->close,                 'innerClass' => 'batch-btn ajax-btn not-open-url', 'disabled' => !$canBatchClose, 'data-url' => createLink('opportunity', 'batchClose')),
            array('text' => $lang->opportunity->cancel,   'innerClass' => "batch-btn ajax-btn not-open-url", 'disabled' => !$canBatchCancel, 'data-url' => createLink('opportunity', 'batchCancel'), 'items' => $cancelItems),
            array('text' => $lang->opportunity->hangup,   'innerClass' => 'batch-btn ajax-btn not-open-url', 'disabled' => !$canBatchClose, 'data-url' => createLink('opportunity', 'batchHangup')),
            array('text' => $lang->opportunity->activate, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'disabled' => !$canBatchClose, 'data-url' => createLink('opportunity', 'batchActivate'))
        );
    }

    if($canBatchClose || $canBatchCancel || $canBatchHangup || $canBatchActivate || $canBatchEdit)
    {
        $editClass = $canBatchEdit ? 'batch-btn' : '';
        $footToolbar['items'][] = array(
            'type'  => 'btn-group',
            'items' => array(
                array('text' => $lang->edit, 'className' => "btn {$editClass}", 'disabled' => !$canBatchEdit , 'btnType' => 'secondary', 'data-url' => createLink('opportunity', 'batchEdit', "projectID={$projectID}&from={$from}")),
                array('caret' => 'up', 'className' => 'btn btn-caret not-open-url', 'items' => $batchItems, 'data-placement' => 'top-start')
            )
        );
    }

    if($canBatchAssignTo)
    {
        $assignedToItems = array();
        foreach($members as $account => $name)
        {
            $assignedToItems[] = array('text' => $name, 'innerClass' => 'batch-btn ajax-btn', 'data-url' => createLink('opportunity', 'batchAssignTo', "projectID={$projectID}&account={$account}"));
        }
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->opportunity->assignedTo, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $assignedToItems, 'data-menu' => array('searchBox' => true));
    }

    if($canBatchImportToLib)
    {
        $footToolbar['items'][] = array('text' => $lang->opportunity->importToLib, 'data-toggle' => 'modal', 'data-target' => '#importToLib', 'data-size' => 'sm');
    }

    $footToolbar['btnProps'] = array('size' => 'sm', 'btnType' => 'secondary');
}

$cols = $config->opportunity->dtable->fieldList;
$cols['actions']['list']['edit']['url']['params'] = str_replace('{from}', $from, $cols['actions']['list']['edit']['url']['params']);
$cols['name']['link']['url']['params'] = "opportunityID={id}&from={$from}";
if(!$canChangeObject) unset($cols['actions']['list']);

$opportunities = initTableData($opportunities, $cols);

dtable
(
    set::cols($cols),
    set::data($opportunities),
    set::checkable($canBatchAction),
    set::userMap($users),
    set::sortLink(createLink('opportunity', 'browse', "projectID={$projectID}&from={$from}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager()),
    set::footToolbar($footToolbar)
);

modal
(
    setID('importToLib'),
    on::click('button[type="submit"]', 'getCheckedOpportunityIDList'),
    set::modalProps(array('title' => $lang->opportunity->importToLib)),
    formPanel
    (
        set::url(createLink('opportunity', 'batchImportToLib')),
        set::actions(array('submit')),
        set::submitBtnText($lang->import),
        formGroup
        (
            set::label($lang->opportunity->lib),
            set::name('lib'),
            set::items($libs),
            set::required(true)
        ),
        !common::hasPriv('assetlib', 'approveOpportunity') && !common::hasPriv('assetlib', 'batchApproveOpportunity') ?  formGroup(set::label($lang->opportunity->approver), set::name('assignedTo'), set::items($approvers)) : null,
        formHidden('opportunityIDList', '')
    )
);
