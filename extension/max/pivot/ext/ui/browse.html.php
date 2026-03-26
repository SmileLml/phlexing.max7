<?php
/**
 * The browse view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xinzhi Qi <qixinzhi@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('draftIcon', $lang->pivot->draftIcon);
jsVar('disableVersionTip', $lang->pivot->disableVersionTip);
jsVar('viewVersion', $lang->pivot->viewVersion);
jsVar('groupID', $groupID);

featureBar();

toolbar
(
    btn
    (
        set::type('secondary'),
        set::url(inlink('preview')),
        $lang->pivot->toPreview
    ),
    common::hasPriv('pivot', 'create') ? item(set(array
    (
        'text' => $lang->pivot->create,
        'icon' => 'plus',
        'class' => 'primary',
        'url' => inlink('create', "dimensionID=$dimensionID", '', true),
        'data-toggle' => 'modal'
    ))) : null
);

sidebar
(
    moduleMenu
    (
        set::activeKey($groupID),
        set::modules($groupMenus),
        set::settingLink(createLink('tree', 'browsegroup', "dimensionID=$dimensionID&groupID=$groupID&type=pivot")),
        set::settingApp('bi'),
        set::settingText($lang->pivot->manageGroup),
        set::closeLink(createLink('pivot', 'browse')),
        set::showDisplay(false)
    )
);

$tableData = initTableData($pivots, $this->config->pivot->dtable->definition->fieldList, $this->loadModel('pivot'));
$tableData = $this->initAction($tableData);

$cols = $this->config->pivot->dtable->definition->fieldList;

dtable(set::cols($cols), set::data($tableData), set::userMap($users), set::sortLink(createLink('pivot', 'browse', "dimensionID={$dimensionID}&groupID={$groupID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")), set::onRenderCell(jsRaw('window.onRenderCell')), set::footPager(usePager(array
(
    'recPerPage'  => $pager->recPerPage,
    'recTotal'    => $pager->recTotal,
    'linkCreator' => helper::createLink('pivot', 'browse', "dimensionID={$dimensionID}&groupID={$groupID}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}") . "#app={$app->tab}"
))));
