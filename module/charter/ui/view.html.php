<?php
/**
 * The view file of charter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     charter
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('browseType', zget($_SESSION, 'browseType', 'all'));
jsVar('vision', $config->vision);
jsVar('userViewProjects', ",{$app->user->view->projects},");

/* 初始化头部右上方工具栏。Init detail toolbar. */
$toolbar = array();
if(!isInModal() && hasPriv('charter', 'create'))
{
    $toolbar[] = array
    (
        'icon' => 'plus',
        'type' => 'primary',
        'text' => $lang->charter->create,
        'url'  => createLink('charter', 'create')
    );
}

/* 基本信息 */
$basicInfoItems = array();
$basicInfoItems[$lang->charter->level]        = array('control' => 'priLabel', 'text' => zget($levelList, $charter->level), 'pri' => $charter->level);
$basicInfoItems[$lang->charter->category]     = array('control' => 'text',     'text' => zget($lang->charter->categoryList, $charter->category));
$basicInfoItems[$lang->charter->market]       = array('control' => 'text',     'text' => zget($lang->charter->marketList, $charter->market));
$basicInfoItems[$lang->charter->budget]       = array('control' => 'text',     'text' => $charter->budget);
$basicInfoItems[$lang->charter->status]       = array('control' => 'text',     'text' => zget($lang->charter->statusList, $charter->status));
$basicInfoItems[$lang->charter->reviewStatus] = array('control' => 'text',     'text' => $charter->status == 'closed' || ($charter->status == 'wait' && $charter->reviewStatus == 'wait') || ($charter->status == 'canceled' && $charter->prevCanceledStatus == 'wait') ? '' : zget($lang->charter->reviewStatusList, $charter->reviewStatus));
$basicInfo = datalist
(
    set::className('charter-basic-info'),
    set::items($basicInfoItems)
);

$projectApproval         = zget($charter->approvalList, 'projectApproval', array());
$completionApproval      = zget($charter->approvalList, 'completionApproval', array());
$cancelProjectApproval   = zget($charter->approvalList, 'cancelProjectApproval', array());
$activateProjectApproval = zget($charter->approvalList, 'activateProjectApproval', array());
function convertUserName($accounts, $users)
{
    $userName = '';
    foreach(explode(',', $accounts) as $account)
    {
        if(empty($account)) continue;
        $userName .= zget($users, trim($account)) . ' ';
    }
    return $userName;
}

/* 立项的一生 */
$lifeTimeItems = array();
$lifeTimeItems[$lang->charter->createdBy]           = array('control' => 'text', 'text' => $charter->createdBy ? zget($users, $charter->createdBy) . $lang->at . $charter->createdDate : '');
$lifeTimeItems[$lang->charter->charteredBy]         = array('control' => 'text', 'text' => !empty($projectApproval['appliedBy']) ? zget($users, $projectApproval['appliedBy']) . $lang->at . $projectApproval['appliedDate'] : '');
$lifeTimeItems[$lang->charter->charterReviewers]    = array('control' => 'text', 'text' => !empty($projectApproval['reviewers']) ? convertUserName($projectApproval['reviewers'], $users) : '');
$lifeTimeItems[$lang->charter->completionBy]        = array('control' => 'text', 'text' => !empty($completionApproval['appliedBy']) ? zget($users, $completionApproval['appliedBy']) . $lang->at . $completionApproval['appliedDate'] : '');
$lifeTimeItems[$lang->charter->completionReviewers] = array('control' => 'text', 'text' => !empty($completionApproval['reviewers']) ? convertUserName($completionApproval['reviewers'], $users) : '');
$lifeTimeItems[$lang->charter->canceledBy]          = array('control' => 'text', 'text' => !empty($cancelProjectApproval['appliedBy']) ? zget($users, $cancelProjectApproval['appliedBy']) . $lang->at . $cancelProjectApproval['appliedDate'] : '');
$lifeTimeItems[$lang->charter->canceledReviewers]   = array('control' => 'text', 'text' => !empty($cancelProjectApproval['reviewers']) ? convertUserName($cancelProjectApproval['reviewers'], $users) : '');
$lifeTimeItems[$lang->charter->activatedBy]         = array('control' => 'text', 'text' => !empty($activateProjectApproval['appliedBy']) ? zget($users, $activateProjectApproval['appliedBy']) . $lang->at . $activateProjectApproval['appliedDate'] : '');
$lifeTimeItems[$lang->charter->activatedReviewers]  = array('control' => 'text', 'text' => !empty($activateProjectApproval['reviewers']) ? convertUserName($activateProjectApproval['reviewers'], $users) : '');
$lifeTimeItems[$lang->charter->closedBy]            = array('control' => 'text', 'text' => $charter->closedBy ? zget($users, $charter->closedBy) . $lang->at . $charter->closedDate : '');
$lifeTime = datalist
(
    set::className('charter-life-item'),
    set::items($lifeTimeItems)
);

$tabs = array();
$tabs[] = setting()
    ->group('basic')
    ->title($lang->charter->legendBasicInfo)
    ->children(wg($basicInfo));
$tabs[] = setting()
    ->group('life')
    ->title($lang->charter->legendLifeTime)
    ->children(wg($lifeTime));

/* 获取文件信息。*/
function getFileData($charter, $reviewType)
{
    global $config;
    $fileData = array();
    $charterFiles = !empty($charter->filesConfig) ? json_decode($charter->filesConfig, true) : json_decode($config->custom->charterFiles, true);
    if(!empty($charterFiles[$charter->level]))
    {
        foreach($charterFiles[$charter->level][$reviewType] as $file)
        {
            $fileData[] = div(setClass('flex'), cell(set::width('100px'), setClass('p-1 text-left text-gray text-clip'), set::title($file['name']), $file['name']), cell(setClass('pl-1'), fileList(set::files($charter->files), set::extra($reviewType . '-' . $file['index']), set::object($charter), set::fieldset(false), set::showDelete(false))));
        }
    }
    return $fileData;
}

/* 产品和计划/路标 */
$tableData      = array();
$canViewProduct = common::hasPriv('product', 'view');
$this->loadModel('productplan');
foreach($groupDate as $productID => $linkedList)
{
    $hasPriv     = $this->product->checkPriv($productID);
    $linkedData  = array();
    $productInfo = zget($products, $productID);
    $isLaunched  = $productInfo->vision == 'rnd' || strpos(",{$productInfo->vision},", ",{$config->vision},") !== false;
    foreach($linkedList as $data)
    {
        $module     = $data->linkedType == 'plan' ? 'productplan' : $data->linkedType;
        $linkedURL  = $this->createLink($module, 'view', "id=$data->id");
        $linkedName = $data->linkedName . " [{$data->begin} ~ {$data->end}]";;
        if($data->begin == $this->config->productplan->future && $data->end == $this->config->productplan->future) $linkedName = $data->linkedName . ' ' . $this->lang->productplan->future;
        if($productInfo->type != 'normal')
        {
            $branchName = '';
            foreach(explode(',', (string)$data->branch) as $dataBranch) $branchName .= !empty($branchGroups[$data->product]) && !empty($branchGroups[$data->product][$dataBranch]) ? $branchGroups[$data->product][$dataBranch] . ',' : '';
            $linkedName = $linkedName . ' / ' . trim($branchName, ',');
        }

        $class = 'clip';
        if(count($linkedList) <= 2)     $class .= ' flex flex-1 w-0 items-center';
        if(count($linkedList) > 2)      $class .= ' flex-none w-1/3';
        if(count($linkedData) > 2)      $class .= ' mt-2';
        if(count($linkedData) % 3 != 0) $class .= ' pl-6';

        $linkedData[] = div
            (
                setClass($class),
                icon('productplan mr-2 '),
                hasPriv($module, 'view') ? a
                (
                    set::title($linkedName),
                    set::href($linkedURL),
                    span($linkedName)
                )
                : span($linkedName)
            );
    }

    $productItems = array();
    $productLink  = $this->createLink('product', 'view', "productID=$productID");
    if(isset($branchGroups[$productID]))
    {
        foreach($branchGroups[$productID] as $branchID => $branchName)
        {
            $branchName = $productInfo->name . '/' . $branchName;
            $productItems[] = $canViewProduct && $isLaunched && $hasPriv ? div(setClass('flex clip w-full items-center'), icon('product mr-2'), a(setClass('flex'), set::href($productLink), set::title($branchName), span(setClass('flex-1'), setStyle('width', '0'), $branchName))) : div($branchName);
        }
    }
    else
    {
        $productItems[] = $canViewProduct && $isLaunched && $hasPriv ? div(setClass('flex clip w-full items-center'), icon('product mr-2'), a(setClass('flex'), set::href($productLink), set::title($productInfo->name),  span(setClass('flex-1'), setStyle('width', '0'), $productInfo->name))) : div($productInfo->name);
    }
    $tableData[] = h::tr(setClass('text-center'), h::td(html($productItems)), h::td(div(setClass('flex flex-wrap'), $linkedData)));
}

/* 立项信息 */
$charterInfo = div
(
    div
    (
        setClass('text-base font-semibold py-1'),
        $lang->charter->spec
    ),
    div
    (
        setClass('py-1'),
        html($charter->spec)
    ),
    div
    (
        setClass('text-base font-semibold py-1'),
        $lang->charter->charterFiles
    ),
    div
    (
        setClass('py-1'),
        div(getFileData($charter, 'projectApproval'))
    ),
    div
    (
        setClass('text-base font-semibold py-1'),
        $charter->type == 'plan' ? $lang->charter->productAndPlan : $lang->charter->productRoadmap
    ),
    !empty($groupDate) ? h::table
    (
        setClass('table bordered'),
        h::tr(h::th(setClass('w-64'), $lang->charter->product), h::th($charter->type == 'plan' ? $lang->charter->plan : $lang->charter->roadmap)),
        $tableData
    ) : null
);
$sections   = array();
$sections[] = setting()
    ->title($lang->charter->charterInfo)
    ->children(wg($charterInfo));

/* 项目集和项目 */
$programAndProjectDetail = null;
foreach($programList as $program)
{
    $programBudget   = $this->loadModel('project')->getBudgetWithUnit($program->budget);
    $program->budget = !empty($program->budget) ? zget($lang->project->currencySymbol, $program->budgetUnit) . ' ' . $programBudget : $lang->project->future;
}
if(!empty($programList))
{
    $programAndProjectDetail = div
    (
        setClass('detail-sections canvas shadow rounded px-6 py-4'),
        div
        (
            setClass('detail-section'),
            div
            (
                setClass('detail-section-title row items-center gap-2'),
                span
                (
                    setClass('text-md py-1 font-bold'),
                    $lang->charter->programAndProject
                )
            ),
            div
            (
                dtable
                (
                    set::onRenderCell(jsRaw('window.onRenderCell')),
                    set::cols($config->charter->programList->fieldList),
                    set::data(array_values($programList)),
                    set::checkable(false),
                    set::sortType(false),
                    set::userMap(array(0 => '') + $users)
                )
            )
        )
    );
}

function printBlockInfo($blockTitle, $blockContent)
{
    $blockInfo = div
    (
        setClass('detail-sections canvas shadow rounded px-6 py-4'),
        div
        (
            setClass('detail-section'),
            div
            (
                setClass('detail-section-title row items-center gap-2'),
                span
                (
                    setClass('text-md py-1 font-bold'),
                    $blockTitle
                )
            ),
            div
            (
                setClass('detail-section-content py-1'),
                div
                (
                    setClass('text-base font-semibold py-1'),
                    $blockContent['desc']['title']
                ),
                div
                (
                    setClass('py-1'),
                    html($blockContent['desc']['content'])
                ),
                div
                (
                    setClass('text-base font-semibold py-1'),
                    $blockContent['file']['title']
                ),
                div
                (
                    setClass('py-1'),
                    div($blockContent['file']['content'])
                )
            )
        )
    );
    return $blockInfo;
}

/* 操作栏 */
if(!empty($charter->approval)) $config->charter->actions->view['mainActions'][] = 'approvalProgress';
if($charter->status == 'launched' && !in_array($charter->reviewStatus, array('completionDoing', 'cancelDoing'))) $config->charter->actions->view['mainActions'][] = 'createProgramAndProject';
if($this->charter->isClickable($charter, 'projectapproval'))
{
    $config->charter->actionList['cancelProjectApproval']['hint'] = $lang->charter->abbr->cancel;
    $config->charter->actionList['cancelProjectApproval']['data-toggle'] = 'modal';
}
if($charter->prevCanceledStatus == 'wait')
{
    $config->charter->actionList['activateProjectApproval']['hint'] = $lang->charter->abbr->activate;
}
$operateList = $this->loadModel('common')->buildOperateMenu($charter);
$actions     = empty($operateList['suffixActions']) ? $operateList['mainActions'] : array_merge($operateList['mainActions'], array(array('type' => 'divider')), $operateList['suffixActions']);

detail
(
    set::urlFormatter(array('{id}' => $charter->id, '{from}' => 'view', '{approval}' => $charter->approval)),
    set::toolbar($toolbar),
    set::sections($sections),
    $programAndProjectDetail,
    set::tabs($tabs),
    set::actions(array_values($actions)),
    in_array($charter->reviewStatus, array('completionDoing', 'completionReject', 'completionPass'))                        ? printBlockInfo($lang->charter->completionInfo, array('desc' => array('title' => $lang->charter->completionDesc, 'content' => zget($charter, 'completionApprovalDesc', '')),    'file' => array('title' => $lang->charter->completionFiles, 'content' => getFileData($charter, 'completeApproval')))) : null,
    in_array($charter->reviewStatus, array('cancelDoing', 'cancelReject', 'cancelPass', 'activateDoing', 'activateReject')) ? printBlockInfo($lang->charter->canceledInfo,   array('desc' => array('title' => $lang->charter->canceledDesc,   'content' => zget($charter, 'cancelProjectApprovalDesc', '')), 'file' => array('title' => $lang->charter->canceledFiles,   'content' => getFileData($charter, 'cancelApproval'))))   : null
);
