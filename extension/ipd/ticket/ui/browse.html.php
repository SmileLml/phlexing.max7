<?php
/**
 * The admin view file of ticket module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     ticket
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('feedbackLang', $lang->feedback->common);
jsVar('blockID',      $blockID);

if($browseType == 'byProduct' || $browseType == 'byModule') $browseType = $this->session->ticketBrowseType;
$closeLink = createLink('ticket', 'browse', "browseType=byProduct&param=all&orderBy=$orderBy&recTotal=0");
$isFromDoc = $from === 'doc';
if($isFromDoc) $this->app->loadLang('doc');

$cols = $this->loadModel('datatable')->getSetting('ticket');
if($isFromDoc)
{
    if(isset($cols['actions'])) unset($cols['actions']);
    foreach($cols as $key => $col)
    {
        $cols[$key]['sortType'] = false;
        if(isset($col['link'])) unset($cols[$key]['link']);
        if($key == 'assignedTo') $cols[$key]['type'] = 'user';
        if($key == 'title') $cols[$key]['link'] = array('url' => createLink('ticket', 'view', "id={id}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
    }
}

$tickets = initTableData($tickets, $cols, $this->ticket);
if(!empty($cols['product']))     $cols['product']['map']     = $products;
if(!empty($cols['module']))      $cols['module']['map']      = $modules;
if(!empty($cols['dept']))        $cols['dept']['map']        = $depts;
if(!empty($cols['feedback']))    $cols['feedback']['map']    = $feedbacks;
if(!empty($cols['mailto']))      $cols['mailto']['map']      = $users;
if(!empty($cols['openedBuild'])) $cols['openedBuild']['map'] = $builds;

if(!$isFromDoc)
{
    dropmenu(set::text($productID == 'all' ? $lang->product->allProduct : ''), set::tab('feedback'));
    sidebar
    (
        moduleMenu(set(array
        (
            'modules'     => $moduleTree,
            'activeKey'   => $this->session->ticketObjectID,
            'closeLink'   => $closeLink,
            'settingLink' => hasPriv('tree', 'browse') && $productID != 'all' && $config->vision == 'rnd' ? createLink('tree', 'browse', "productID={$productID}&view=ticket") : '',
            'settingApp'  => $app->tab,
            'showDisplay' => $this->config->vision != 'lite'
        )))
    );
}

if($isFromDoc)
{
    $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
    $productChangeLink = createLink($app->rawModule, $app->rawMethod, "browseType=byProduct&param={productID}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");

    $linkParams = $productID == 'all' ?  'browseType=wait&param=0' : "browseType=byProduct&param=$productID";
    jsVar('insertListLink', createLink($app->rawModule, $app->rawMethod, "$linkParams&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID={blockID}"));

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['ticket'])),
        set::actions(array()),
        to::titleSuffix
        (
            span
            (
                setClass('text-muted text-sm text-gray-600 font-light'),
                span(setClass('text-warning mr-1'), icon('help')),
                $lang->doc->previewTip
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('product'),
                set::label($lang->doc->product),
                set::control(array('required' => false)),
                set::items($products),
                set::value($productID),
                set::required(),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                ),
                on::change('[name="product"]')->do("loadModal('$productChangeLink'.replace('{productID}', $(this).val()))")
            )
        )
    );
}

featureBar
(
    set::current($browseType),
    set::linkParams("browseType={key}&param=$productID&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID"),
    set::isModal($isFromDoc),
    li(searchToggle
    (
        set::simple($isFromDoc),
        set::open($browseType == 'bysearch'),
        $isFromDoc ? set::target('#docSearchForm') : null,
        $isFromDoc ? set::onSearch(jsRaw('function(){$(this.element).closest(".modal").find("#featureBar .nav-item>.active").removeClass("active").find(".label").hide()}')) : null
    ))
);

if($isFromDoc)
{
    div(setID('docSearchForm'));
}

$canExport          = hasPriv('ticket', 'export');
$canExportTemplate  = hasPriv('ticket', 'exportTemplate');
$exportItem         = array('text' => $lang->ticket->export,         'data-toggle' => 'modal', 'data-size' => 'sm','url' => createLink('ticket', 'export', "browseType=$browseType&orderBy=$orderBy"));
$exportTemplateItem = array('text' => $lang->ticket->exportTemplate, 'data-toggle' => 'modal', 'data-size' => 'sm','url' => createLink('ticket', 'exportTemplate'));

$canBatchEdit     = common::hasPriv('ticket', 'batchEdit');
$canBatchAssignTo = common::hasPriv('ticket', 'batchAssignTo');
$canBatchFinish   = common::hasPriv('ticket', 'batchFinish');
$canBatchClose    = common::hasPriv('ticket', 'batchClose');
$canBatchActivate = common::hasPriv('ticket', 'batchActivate');
$canBatchAction   = ($canBatchEdit || $canBatchAssignTo || $canBatchFinish || $canBatchClose || $canBatchActivate || $canExport);

$assignedToItems = array();
foreach ($users as $key => $value)
{
    if(!empty($key)) $assignedToItems[] = array('text' => $value, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('ticket', 'batchAssignTo', "assignedTo={$key}"));
}

$footToolbar = array();
if($canBatchAction)
{
    $footToolbar['items'] = array();
    if($canBatchEdit)
    {
        $footToolbar['items'][] = array('text' => $lang->edit, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('ticket', 'batchEdit'));
    }
    if($canBatchAssignTo)
    {
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->ticket->assignedTo, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $assignedToItems, 'data-menu' => array('searchBox' => true));
    }
    if($canBatchFinish)
    {
        $footToolbar['items'][] = array('text' => $lang->ticket->finish, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('ticket', 'batchFinish'));
    }
    if($canBatchClose)
    {
        $footToolbar['items'][] = array('text' => $lang->ticket->close, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('ticket', 'batchClose'));
    }
    if($canBatchActivate)
    {
        $footToolbar['items'][] = array('text' => $lang->ticket->activate, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('ticket', 'batchActivate'));
    }
    $footToolbar['btnProps'] = array('size' => 'sm', 'btnType' => 'secondary');
}
if($isFromDoc) $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc"));

$createLink      = createLink('ticket', 'create', "productID={$productID}&extras=moduleID={$moduleID}");
$batchCreateLink = createLink('ticket', 'batchCreate', "productID={$productID}&moduleID={$moduleID}");
$createItems     = array();
if(hasPriv('ticket', 'create')) $createItems[] = array('text' => $lang->ticket->create, 'url' => $createLink);
if(hasPriv('ticket', 'batchCreate') && $product && $product->status == 'normal') $createItems[] = array('text' => $lang->ticket->batchCreate, 'url' => $batchCreateLink);

toolbar
(
    setClass(array('hidden' => $isFromDoc)),
    $canExport && $canExportTemplate ? dropdown(btn
    (
        setClass('btn ghost dropdown-toggle'),
        set::icon('export'),
        $lang->export
    ), set::items(array($exportItem, $exportTemplateItem)), set::placement('bottom-end')) : null,
    $canExport && !$canExportTemplate ? item(set($exportItem         + array('class' => 'btn ghost', 'icon' => 'export'))) : null,
    $canExportTemplate && !$canExport ? item(set($exportTemplateItem + array('class' => 'btn ghost', 'icon' => 'export'))) : null,
    hasPriv('ticket', 'import') ? item
    (
        setClass('ghost'),
        set::icon('import'),
        set::url(createLink('ticket', 'import')),
        setData(array('toggle' => 'modal')),
        set::text($lang->ticket->import)
    ) : null,
    count($createItems) > 1 ? btnGroup
    (
        btn(setClass('btn primary create-bug-btn'), set::icon('plus'), set::url($createLink), $lang->ticket->create),
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
    set::id('tickets'),
    set::cols(array_values($cols)),
    set::data(array_values($tickets)),
    set::checkable($canBatchAction),
    set::userMap($users),
    set::orderBy($orderBy),
    !$isFromDoc ? null : set::height(400),
    !$isFromDoc ? null : set::onCheckChange(jsRaw('window.checkedChange')),
    !$isFromDoc ? null : set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    $isFromDoc ? null : set::customCols(true),
    $isFromDoc ? null : set::sortLink(createLink('ticket', 'browse', "browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from={$from}&blockID={$blockID}")),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::modules($modulePairs)
);

render();
