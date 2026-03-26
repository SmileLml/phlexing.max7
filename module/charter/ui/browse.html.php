<?php
/**
 * The browse view file of charter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     charter
 * @link        https://www.zentao.net
 */
namespace zin;

foreach($config->charter->dtable->fieldList['actions']['list'] as &$action)
{
    if(!isset($action['url'])) continue;
    $action['url']['params'] = str_replace('{from}', 'list', $action['url']['params']);
}

$cols     = $this->loadModel('datatable')->getSetting('charter');
$charters = initTableData($charters, $cols, $this->charter);

$linkParams = '';
foreach($app->rawParams as $key => $value) $linkParams = $key != 'orderBy' ? "{$linkParams}&{$key}={$value}" : "{$linkParams}&orderBy={name}_{sortType}";

jsVar('browseType', $browseType);
jsVar('vision', $config->vision);
jsVar('reviewStatusList', $lang->charter->reviewStatusList);
jsVar('activateHint', $lang->charter->abbr->activate);

featureBar
(
    set::current($browseType),
    set::linkParams("browseType={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    set::queryMenuLinkCallback(array(function ($key) use ($browseType) {
        return str_replace('{queryID}', (string)$key, createLink('charter', 'browse', "browseType={$browseType}&param={queryID}"));
    })),
    li(searchToggle(set::open($browseType == 'bysearch')))
);

toolbar
(
    hasPriv('charter', 'create') ? item
    (
        setClass('primary charter-create-btn'),
        set::icon('plus'),
        set::url(createLink('charter', 'create')),
        set::text($lang->charter->create)
    ) : null
);

dtable
(
    set::cols($cols),
    set::data(array_values($charters)),
    set::customCols(true),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(createLink($app->rawModule, $app->rawMethod, $linkParams)),
    set::footPager(usePager())
);

render();
