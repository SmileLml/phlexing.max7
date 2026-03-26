<?php
/**
 * The baseline view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar(set::current($browseType), set::linkParams("mode={$mode}&type={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"));

$cols = $config->my->baseline->dtable->fieldList;
$cols['project']['map']  = $projectPairs;

$baselines = initTableData($baselines, $cols);
dtable
(
    set::cols(array_values($cols)),
    set::data(array_values($baselines)),
    set::userMap($users),
    set::checkable(false),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$browseType}&param=&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager())
);
