<?php
/**
 * The browse view file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     demand
 * @link        https://www.zentao.net
 */
namespace zin;

$canExport          = hasPriv('demand', 'export');
$canExportTemplate  = hasPriv('demand', 'exportTemplate');
$canCreate          = hasPriv('demand', 'create');
$canBatchCreate     = hasPriv('demand', 'batchCreate');
$exportItem         = array('text' => $lang->demand->export,         'data-toggle' => 'modal', 'data-size' => 'sm','url' => createLink('demand', 'export', "poolID={$poolID}&orderBy={$orderBy}"));
$exportTemplateItem = array('text' => $lang->demand->exportTemplate, 'data-toggle' => 'modal', 'data-size' => 'sm','url' => createLink('demand', 'exportTemplate', "poolID={$poolID}"));
$createItem         = array('text' => $lang->demand->create,         'url' => createLink('demand', 'create', "poolID={$poolID}"));
$batchCreateItem    = array('text' => $lang->demand->batchCreate,    'url' => createLink('demand', 'batchCreate', "poolID={$poolID}"));

$cols = $this->loadModel('datatable')->getSetting('demand');
$cols['product']['map']    = $products + array('' => $lang->demand->undetermined);
$cols['mailto']['map']     = $users;
$cols['reviewedBy']['map'] = $users;

$demands = initTableData($demands, $cols, $this->demand);

$linkParams = '';
foreach($app->rawParams as $key => $value) $linkParams = $key != 'orderBy' ? "{$linkParams}&{$key}={$value}" : "{$linkParams}&orderBy={name}_{sortType}";

featureBar
(
    set::current($browseType),
    set::linkParams("poolID={$poolID}&browseType={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::open($browseType == 'bysearch')))
);

toolbar
(
    $canExport && $canExportTemplate ? dropdown
    (
        btn
        (
            setClass('btn ghost dropdown-toggle'),
            set::icon('export'),
            $lang->export
        ),
        set::items(array($exportItem, $exportTemplateItem)),
        set::placement('bottom-end')
    ) : null,
    $canExport && !$canExportTemplate ? item(set($exportItem     + array('class' => 'btn ghost', 'icon' => 'export'))) : null,
    $canExportTemplate && !$canExport ? item(set($exportTemplateItem + array('class' => 'btn ghost', 'icon' => 'export'))) : null,
    hasPriv('demand', 'import') ? item
    (
        setClass('ghost'),
        set::icon('import'),
        set::url(createLink('demand', 'import', "poolID={$poolID}")),
        setData(array('toggle' => 'modal')),
        set::text($lang->demand->import)
    ) : null,
    $canCreate && $canBatchCreate ? btngroup
    (
        btn(setClass('btn primary create-demand-btn'), set::icon('plus'), set::url(createLink('demand', 'create', "poolID={$poolID}")), $lang->demand->create),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array($createItem, $batchCreateItem)),
            set::placement('bottom-end')
        )
    ) : null,
    $canCreate && !$canBatchCreate ? item(set($createItem      + array('class' => 'btn primary', 'icon' => 'plus'))) : null,
    $canBatchCreate && !$canCreate ? item(set($batchCreateItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null
);

jsVar('recall',       $lang->demand->recall);
jsVar('recallChange', $lang->demand->recallChange);
jsVar('childrenAB',   $lang->demand->childrenAB);

dtable
(
    set::cols($cols),
    set::data($demands),
    set::customCols(true),
    set::checkable($canExport),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(createLink($app->rawModule, $app->rawMethod, $linkParams)),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::footPager(usePager())
);

render();
