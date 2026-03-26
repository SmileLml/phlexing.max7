<?php
/**
 * The browse view file of artifact module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     artifact
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('pageLink', $pageLink);

$statusMap     = array();
$canCreate     = hasPriv('artifactrepo', 'create');
$artifactRepos = initTableData($artifactRepos, $config->artifactrepo->dtable->fieldList, $this->artifactrepo);

foreach($artifactRepos as &$repo)
{
    $productNames = array();
    $productList  = explode(',', str_replace(' ', '', $repo->products));
    if($productList)
    {
        foreach($productList as $productID)
        {
            if(!isset($products[$productID])) continue;
            $productNames[] = zget($products, $productID, $productID);
        }
        $repo->productNames = implode('，', $productNames);
    }

    if(isset($repo->actions))
    {
        array_unshift($repo->actions, array('url' => $repo->url, 'target' => '_blank', 'icon' => 'menu-my', 'hint' => $lang->artifactrepo->visit));
    }
}

featureBar();

toolBar
(
    $canCreate ? item(set(array
    (
        'text'  => $lang->artifactrepo->create,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('artifactrepo', 'create'),
    ))) : null
);

dtable
(
    set::cols($config->artifactrepo->dtable->fieldList),
    set::data($artifactRepos),
    set::onRenderCell(jsRaw('window.renderList')),
    set::sortLink(createLink('artifactrepo', 'browse', "browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
