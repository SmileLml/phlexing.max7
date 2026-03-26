<?php
/**
 * The admin view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;

if($browseType == 'byProduct' || $browseType == 'byModule') $browseType = $this->session->feedbackBrowseType;
$productID   = $productID != 'all' ? $this->session->feedbackProduct : $productID;
$closeLink   = createLink('feedback', 'browse', "browseType=byProduct&param=all&orderBy=$orderBy&recTotal=0");

foreach($feedbacks as $feedback)
{
    $feedback->solution = zget($lang->feedback->solutionList, $feedback->solution, '');
}

foreach(array_keys($config->feedback->dtable->fieldList['actions']['list']) as $actionName)
{
    if(strpos(',edit,close,activate,delete,', ",$actionName,") === false) unset($config->feedback->dtable->fieldList['actions']['list'][$actionName]);
}

$cols      = $this->loadModel('datatable')->getSetting('feedback');
$feedbacks = initTableData($feedbacks, $cols, $this->feedback);
if(!empty($cols['product'])) $cols['product']['map'] = $products;
if(!empty($cols['module']))  $cols['module']['map']  = $modules;
if(!empty($cols['dept']))    $cols['dept']['map']    = $depts;

$canBatchEdit     = common::hasPriv('feedback', 'batchEdit');
$canBatchClose    = common::hasPriv('feedback', 'batchClose');
$canBatchAssignTo = common::hasPriv('feedback', 'batchAssignTo');
$canExport        = common::hasPriv('feedback', 'export');
$canBatchAction   = $canBatchEdit || $canBatchClose || $canBatchAssignTo || $canExport;
$footToolbar      = array();
$assignedToItems  = array();
foreach($users as $key => $value)
{
    if($value && $key != 'closed') $assignedToItems[] = array('text' => $value, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('feedback', 'batchAssignTo', "assignedTo=$key"));
}

if($canBatchAction)
{
    $footToolbar['items'] = array();
    if($canBatchEdit)
    {
        $footToolbar['items'][] = array('text' => $lang->edit, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('feedback', 'batchEdit', "browseType=$browseType"));
    }
    if($canBatchClose)
    {
        $footToolbar['items'][] = array('text' => $lang->close, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('feedback', 'batchClose'));
    }
    if($canBatchAssignTo)
    {
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->feedback->assignedTo, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $assignedToItems, 'data-menu' => array('searchBox' => true));
    }
    $footToolbar['btnProps'] = array('size' => 'sm', 'btnType' => 'secondary');
}

jsVar('errorNoProject',   $lang->feedback->noProject);
jsVar('errorNoExecution', $lang->feedback->noExecution);

dropmenu(set::text($productID == 'all' ? $lang->product->allProduct : ''), set::tab('product'));

sidebar
(
    moduleMenu(set(array
    (
        'modules'     => $moduleTree,
        'activeKey'   => $this->session->objectID,
        'closeLink'   => $closeLink,
        'showDisplay' => false
    )))
);

featureBar
(
    set::current($browseType),
    set::linkParams("browseType={key}"),
    li(searchToggle(set::open($browseType == 'bysearch')))
);

$canExportTemplate  = hasPriv('feedback', 'exportTemplate');
$exportItem         = array('text' => $lang->feedback->export,         'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('feedback', 'export', "browseType=$browseType&orderBy=$orderBy"));
$exportTemplateItem = array('text' => $lang->feedback->exportTemplate, 'data-toggle' => 'modal', 'data-size' => 'sm', 'url' => createLink('feedback', 'exportTemplate'));

$createLink      = createLink('feedback', 'create', "extras=moduleID=$moduleID,productID=$productID");
$batchCreateLink = createLink('feedback', 'batchCreate', "productID={$productID}&moduleID={$moduleID}");
$createItems     = array();
if(hasPriv('feedback', 'create')) $createItems[] = array('text' => $lang->feedback->create, 'url' => $createLink);
if(hasPriv('feedback', 'batchCreate') && $product && $product->status == 'normal') $createItems[] = array('text' => $lang->feedback->batchCreate, 'url' => $batchCreateLink);

toolbar
(
    $canExport && $canExportTemplate ? dropdown(btn
    (
        setClass('btn ghost dropdown-toggle'),
        set::icon('export'),
        $lang->export
    ), set::items(array($exportItem, $exportTemplateItem)), set::placement('bottom-end')) : null,
    $canExport && !$canExportTemplate ? item(set($exportItem         + array('class' => 'btn ghost', 'icon' => 'export'))) : null,
    $canExportTemplate && !$canExport ? item(set($exportTemplateItem + array('class' => 'btn ghost', 'icon' => 'export'))) : null,
    hasPriv('feedback', 'import') ? item
    (
        setClass('ghost'),
        set::icon('import'),
        set::url(createLink('feedback', 'import')),
        setData(array('toggle' => 'modal')),
        set::text($lang->feedback->import)
    ) : null,
    count($createItems) > 1 ? btnGroup
    (
        btn(setClass('btn primary create-bug-btn'), set::icon('plus'), set::url($createLink), $lang->feedback->create),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items($createItems),
            set::placement('bottom-end')
        )
    ) : null,
    count($createItems) == 1 ? item(set(current($createItems)), setClass('primary'), set::icon('plus')) : null
);

dtable
(
    set::cols(array_values($cols)),
    set::data(array_values($feedbacks)),
    set::checkable($canBatchAction),
    set::userMap($users),
    set::orderBy($orderBy),
    set::customCols(true),
    set::sortLink(createLink('feedback', 'browse', "browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::modules($modulePairs)
);

render();
