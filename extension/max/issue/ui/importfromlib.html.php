<?php
/**
 * The importfromlib view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

$libItems = array();
foreach($libraries as $id => $value)
{
    $url = createLink('issue', 'importFromLib', "projectID={$projectID}&from={$from}&libID={$id}");
    $libItems[] = array('value' => $id, 'text' => $value, 'data-url' => $url, 'data-on' => 'click', 'data-do' => "loadPage(options.url)");
}

featureBar
(
    backBtn
    (
        set::icon('back'),
        set::type('primary-outline'),
        $lang->goback
    ),
    inputGroup
    (
        setClass('ml-2'),
        $lang->assetlib->selectLib,
        picker
        (
            set::width(200),
            set::name('fromlib'),
            set::items($libItems),
            set::value($libID),
            set::required(true)
        )
    )
);

searchForm
(
    set::module('importIssue'),
    set::show(true)
);

$footToolbar['items'][] = array(
    'text'      => $lang->issue->import,
    'className' => 'btn secondary toolbar-item batch-btn size-sm',
    'data-url'  => createLink('issue', 'importFromLib', "projectID={$projectID}&from={$from}&libID={$libID}")
);

dtable
(
    set::cols($config->issue->dtable->importIssue->fieldList),
    set::data($issues),
    set::checkable(true),
    set::orderBy($orderBy),
    set::sortLink(createLink('issue', 'importFromLib', "projectID={$projectID}&from={$from}&libID={$libID}&orderBy={name}_{sortType}&browseType={$browseType}&queryID={$queryID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);
