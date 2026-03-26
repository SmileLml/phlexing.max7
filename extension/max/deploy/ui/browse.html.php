<?php
/**
 * The browse view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('deployLang', $this->lang->deploy);

$queryMenuLink = inLink('browse', "productID={$productID}&status=bySearch&param={queryID}");
featureBar
(
    set::current($status == 'bySearch' ? 'all' : $status),
    set::linkParams("productID={$productID}&status={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    set::queryMenuLinkCallback(array(function ($key) use ($queryMenuLink) {
        return str_replace('{queryID}', (string)$key, $queryMenuLink);
    })),
    li(searchToggle(set::module('deploy'), set::open($status == 'bySearch')))
);
toolBar
(
    common::hasPriv('deploy', 'create') ? item
    (
        set::type('primary'),
        set::url(createLink('deploy', 'create')),
        set::icon('plus'),
        set::text($this->lang->deploy->create)
    ) : null
);

$config->deploy->dtable->fieldList['product']['map'] = $products;
$config->deploy->dtable->fieldList['system']['map']  = $systems;

if(!common::hasPriv('deploy', 'steps') && common::hasPriv('deploy', 'view'))
{
    $config->deploy->dtable->fieldList['name']['link'] = array('module' => 'deploy', 'method' => 'view', 'params' => 'deployID={id}');
}

$cols  = $this->loadModel('datatable')->getSetting('deploy');
$plans = initTableData($plans, $cols, $this->deploy);
dtable
(
    set::customCols(true),
    set::userMap($users),
    set::cols($cols),
    set::data($plans),
    set::sortLink(inLink('browse', "productID={$productID}&status={$status}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::actionItemCreator(jsRaw('window.renderActions')),
    set::footPager(usePager())
);
