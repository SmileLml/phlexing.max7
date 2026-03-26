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

jsVar('blockID',     $blockID);

if($browseType == 'byProduct' || $browseType == 'byModule') $browseType = $this->session->feedbackBrowseType;
$productID   = $productID != 'all' ? $this->session->feedbackProduct : $productID;
$closeLink   = createLink('feedback', 'admin', "browseType=byProduct&param=all&orderBy=$orderBy&recTotal=0");
$settingLink = hasPriv('tree', 'browse') && $productID != 'all' ? createLink('tree', 'browse', "productID={$productID}&view=feedback") : '';
$isFromDoc   = $from === 'doc';
if($isFromDoc) $this->app->loadLang('doc');

$canExport          = hasPriv('feedback', 'export');
$canExportTemplate  = hasPriv('feedback', 'exportTemplate');
$exportItem         = array('text' => $lang->feedback->export,         'data-toggle' => 'modal', 'data-size' => 'sm','url' => createLink('feedback', 'export', "browseType=$browseType&orderBy=$orderBy"));
$exportTemplateItem = array('text' => $lang->feedback->exportTemplate, 'data-toggle' => 'modal', 'data-size' => 'sm','url' => createLink('feedback', 'exportTemplate'));

foreach($feedbacks as $feedback)
{
    $feedback->realStatus = $this->processStatus('feedback', $feedback);
    $feedback->solution   =  zget($lang->feedback->solutionList, $feedback->solution, '');
}

$cols = $this->loadModel('datatable')->getSetting('feedback');
if($isFromDoc)
{
    if(isset($cols['actions'])) unset($cols['actions']);
    foreach($cols as $key => $col)
    {
        $cols[$key]['sortType'] = false;
        if(isset($col['link'])) unset($cols[$key]['link']);
        if($key == 'assignedTo') $cols[$key]['type'] = 'user';
        if($key == 'title') $cols[$key]['link'] = array('url' => createLink('feedback', $config->vision != 'lite' ? 'adminView' : 'view', "feedbackID={id}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
    }
}

$feedbacks = initTableData($feedbacks, $cols, $this->feedback);
if(!empty($cols['product'])) $cols['product']['map'] = $products;
if(!empty($cols['module']))  $cols['module']['map']  = $modules;
if(!empty($cols['dept']))    $cols['dept']['map']    = $depts;

$canBatchEdit     = common::hasPriv('feedback', 'batchEdit');
$canBatchClose    = common::hasPriv('feedback', 'batchClose');
$canBatchAssignTo = common::hasPriv('feedback', 'batchAssignTo');
$canBatchAction   = $canBatchEdit || $canBatchClose || $canBatchAssignTo || $canExport;
if($browseType == 'review')
{
    $canBatchReview = common::hasPriv('feedback', 'batchReview');
    $canBatchAction = $canBatchAction || $canBatchReview;
}

$footToolbar     = array();
$reviewItems     = array();
$assignedToItems = array();
foreach($lang->feedback->reviewResultList as $key => $value)
{
    if($value) $reviewItems[] = array('text' => $value, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('feedback', 'batchReview', "result=$key"));
}
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
    if($browseType == 'review' && $canBatchReview)
    {
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->feedback->review, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $reviewItems);
    }
    if($canBatchAssignTo)
    {
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->feedback->assignedTo, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $assignedToItems, 'data-menu' => array('searchBox' => true));
    }
    $footToolbar['btnProps'] = array('size' => 'sm', 'btnType' => 'secondary');
}
if($isFromDoc) $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc"));

jsVar('errorNoProject',   $lang->feedback->noProject);
jsVar('errorNoExecution', $lang->feedback->noExecution);

if(!$isFromDoc)
{
    dropmenu(set::text($productID == 'all' ? $lang->product->allProduct : ''));
    sidebar
    (
        moduleMenu(set(array
        (
            'modules'     => $moduleTree,
            'activeKey'   => $this->session->objectID,
            'closeLink'   => $closeLink,
            'settingLink' => $settingLink,
            'settingApp'  => $app->tab
        )))
    );
}

if($isFromDoc)
{
    $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
    $productChangeLink = createLink($app->rawModule, $app->rawMethod, "browseType=byProduct&param={productID}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");

    $linkParams = $productID == 'all' ?  "browseType={$browseType}&param=0" : "browseType=byProduct&param=$productID";
    jsVar('insertListLink', createLink($app->rawModule, $app->rawMethod, "$linkParams&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID={blockID}"));

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['feedback'])),
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

$createLink      = createLink('feedback', 'create', "extras=moduleID=$moduleID,productID=$productID");
$batchCreateLink = createLink('feedback', 'batchCreate', "productID={$productID}&moduleID={$moduleID}");
$createItems     = array();
if(hasPriv('feedback', 'create')) $createItems[] = array('text' => $lang->feedback->create, 'url' => $createLink, 'class' => 'create-feedback-btn');
if(hasPriv('feedback', 'batchCreate') && $product && $product->status == 'normal') $createItems[] = array('text' => $lang->feedback->batchCreate, 'url' => $batchCreateLink);

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
        btn(setClass('btn primary create-feedback-btn'), set::icon('plus'), set::url($createLink), $lang->feedback->create),
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
    set::id('feedbacks'),
    set::cols(array_values($cols)),
    set::data(array_values($feedbacks)),
    set::userMap($users),
    set::orderBy($orderBy),
    set::checkable($canBatchAction),
    !$isFromDoc ? null : set::height(400),
    !$isFromDoc ? null : set::onCheckChange(jsRaw('window.checkedChange')),
    !$isFromDoc ? null : set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    $isFromDoc ? null : set::customCols(true),
    $isFromDoc ? null : set::sortLink(createLink('feedback', 'admin', "browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::modules($modulePairs)
);

modal
(
    setID('toTask'),
    set::modalProps(array('title' => $lang->feedback->selectProjects)),
    to::footer
    (
        div
        (
            setClass('toolbar gap-4 w-full justify-center'),
            btn($lang->bug->nextStep, setID('toTaskButton'), setClass('primary'), set('data-on', 'click'), set('data-call', 'toTask')),
            btn($lang->cancel, setID('cancelButton'), setData(array('dismiss' => 'modal')))
        )
    ),
    formPanel
    (
        on::change('#taskProjects', 'changeTaskProjects'),
        set::actions(''),
        formRow
        (
            formGroup(set::label($lang->feedback->project), set::required(true), set::control('picker'), set::name('taskProjects'), set::items($projects))
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->feedback->execution),
                set::required(true),
                inputGroup
                (
                    setID('executionBox'),
                    picker(set::name('executions'), set::items(array())),
                    input(setClass('hidden'), set::name('feedbackID'))
                )
            )
        )
    )
);

render();
