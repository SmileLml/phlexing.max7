<?php
/**
 * The all view file of marketresearch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = $this->loadModel('datatable')->getSetting('marketresearch');
$cols['market']['map'] = $markets;

$researches = initTableData($researches, $cols, $this->marketresearch);

$linkParams = '';
foreach($app->rawParams as $key => $value) $linkParams = $key != 'orderBy' ? "{$linkParams}&{$key}={$value}" : "{$linkParams}&orderBy={name}_{sortType}";

featureBar(set::current($browseType), set::linkParams("marketID={$marketID}&browseType={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), checkbox(set::text($lang->marketresearch->mine), set::value('1'), set::checked($this->cookie->involvedResearch), setData(array('on' => 'change', 'call' => 'changeInvolved', 'params' => 'event'))));

toolbar(hasPriv('marketresearch', 'create') ? item
(
    setClass('btn primary'),
    set::icon('plus'),
    set::url(createLink('marketresearch', 'create', "marketID=$marketID")),
    setData(array('size' => 'lg')),
    set::text($lang->marketresearch->create)
) : null);

dtable
(
    set::cols($cols),
    set::data($researches),
    set::customCols(true),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(createLink($app->rawModule, $app->rawMethod, $linkParams)),
    set::footPager(usePager())
);
