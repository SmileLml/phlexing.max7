<?php
/**
 * The view view file of ticket module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     ticket
 * @link        https://www.zentao.net
 */
namespace zin;

$getModulePath = function() use($modulePath, $lang)
{
    if(empty($modulePath)) return array('moduleTitle' => '/', 'printModule' => '/');

    $moduleTitle = '';
    $printModule = '';
    foreach($modulePath as $key => $module)
    {
        $moduleTitle .= $module->name;
        $printModule .= $module->name;
        if(isset($modulePath[$key + 1]))
        {
            $moduleTitle .= '/';
            $printModule .= ' / ';
        }
    }
    return array('moduleTitle' => $moduleTitle, 'printModule' => $printModule);
};

$getBasicInfoItems = function($ticket)  use($product, $products, $getModulePath, $modulePath, $users, $feedback, $builds)
{
    global $app, $lang;
    $canViewProduct = hasPriv('product', 'view') && in_array($ticket->product, explode(',', $app->user->view->products)) && isset($products[$ticket->product]) && !$product->shadow;
    $modulePath     = $getModulePath($modulePath);
    $mailtoList     = '';
    foreach(explode(',', str_replace(' ', '', $ticket->mailto)) as $account) $mailtoList .= zget($users, $account) . ' ';

    $feedbackTitle = isset($feedback->id) && isset($feedback->title) ? "#{$feedback->id} {$feedback->title}" : '';
    $feedbackTitle = hasPriv('feedback', 'adminView') ? array('control' => 'link', 'text' => $feedbackTitle, 'url' => createLink('feedback', 'adminView', "ticketID=$ticket->feedback")) : $feedbackTitle;

    $openedBuilds = '';
    if(!empty($ticket->openedBuild))
    {
        foreach(explode(',', str_replace(' ', '', $ticket->openedBuild)) as $openedBuild) $openedBuilds .= zget($builds, $openedBuild) . ' ';
        $openedBuilds = trim($openedBuilds);
    }

    $items = array();
    $items[$lang->ticket->product]     = $canViewProduct ? array('control' => 'link', 'url' => createLink('product', 'view', "productID={$ticket->product}"), 'text' => $product->name) : $product->name;
    $items[$lang->ticket->module]      = array('control' => 'text', 'content' => $modulePath['printModule'], 'title' => $modulePath['moduleTitle'], 'id' => 'moduleBox');
    $items[$lang->ticket->openedBuild] = array('control' => 'text', 'content' => $openedBuilds, 'title' => $openedBuilds);
    $items[$lang->ticket->type]        = zget($lang->ticket->typeList, $ticket->type, '');
    $items[$lang->ticket->source]      = !empty($ticket->feedback) ? $feedbackTitle : '';
    $items[$lang->ticket->status]      = array('control' => 'status', 'class' => "status-ticket", 'status'  => $ticket->status, 'text' => zget($lang->ticket->statusList, $ticket->status, ''));
    $items[$lang->ticket->pri]         = array('control' => 'pri', 'pri' => $ticket->pri, 'text' => zget($lang->ticket->priList, $ticket->pri, ''));
    $items[$lang->ticket->openedBy]    = zget($users, $ticket->openedBy) . ' ' . $lang->at . ' ' . $ticket->openedDate;
    $items[$lang->ticket->assignedTo]  = zget($users, $ticket->assignedTo);
    $items[$lang->ticket->keywords]    = $ticket->keywords;
    $items[$lang->ticket->mailto]      = $mailtoList;
    $items[$lang->ticket->estimate]    = $ticket->estimate . $lang->workingHour;
    $items[$lang->ticket->consumed]    = $ticket->consumed . $lang->workingHour;
    $items[$lang->ticket->deadline]    = !helper::isZeroDate($ticket->deadline) ? $ticket->deadline : '';
    return $items;
};
$getLifeItems = function($ticket) use($users)
{
    global $lang;
    $items = array();
    $items[$lang->ticket->createdByAB]    = zget($users, $ticket->openedBy) . $lang->at . $ticket->openedDate;
    $items[$lang->ticket->assignedTo]     = $ticket->assignedTo && $ticket->assignedTo != 'closed' ? zget($users, $ticket->assignedTo) . $lang->at . $ticket->assignedDate : '';
    $items[$lang->ticket->startedBy]      = $ticket->startedBy ? zget($users, $ticket->startedBy) . $lang->at . $ticket->startedDate : '';
    $items[$lang->ticket->finishedByAB]   = $ticket->finishedBy ? zget($users, $ticket->finishedBy) . $lang->at . $ticket->finishedDate : '';
    $items[$lang->ticket->closedByAB]     = $ticket->closedBy ? zget($users, $ticket->closedBy) . $lang->at . $ticket->closedDate : '';
    $items[$lang->ticket->closedReason]   = zget($lang->ticket->closedReasonList, $ticket->closedReason);
    $items[$lang->ticket->activatedBy]    = $ticket->activatedBy ? zget($users, $ticket->activatedBy) . $lang->at . $ticket->activatedDate : '';
    $items[$lang->ticket->activatedCount] = $ticket->activatedCount ? (string)$ticket->activatedCount : '';
    $items[$lang->ticket->editedByAB]     = $ticket->editedBy ? zget($users, $ticket->editedBy) . $lang->at . $ticket->editedDate : '';
    return $items;
};
$getContactItems = function($ticketSources)
{
    global $lang;
    $items = array();
    foreach($ticketSources as $ticketSource)
    {
        $items[] = array('label' => $lang->ticket->customer,    'children' => $ticketSource->customer);
        $items[] = array('label' => $lang->ticket->contact,     'children' => $ticketSource->contact);
        $items[] = array('label' => $lang->ticket->notifyEmail, 'children' => $ticketSource->notifyEmail);
    }
    return $items;
};

$sections = array();
$sections[] = setting()
    ->title($lang->ticket->desc)
    ->control('html')
    ->content(empty($ticket->desc) ? $lang->noDesc : $ticket->desc);
if(!empty($feedback))
{
    $cols = array();
    $cols['id']     = $config->feedback->dtable->fieldList['id'];
    $cols['title']  = $config->feedback->dtable->fieldList['title'];
    $cols['status'] = $config->feedback->dtable->fieldList['status'];
    $cols['title']['width']  = '550px';
    $cols['status']['width'] = '100px';
    $sections[] = setting()
        ->title($lang->ticket->fromFeedback)
        ->control('dtable')
        ->extensible(false)
        ->cols($cols)
        ->data(array($feedback));
}
$sections[] = setting()
    ->control('fileList')
    ->fileTitle($lang->ticket->descFiles)
    ->files($ticket->createFiles)
    ->extra('create')
    ->showDelete(false)
    ->padding(false)
    ->object($ticket);
if(!empty($ticket->resolution))
{
    $sections[] = setting()
        ->title($lang->ticket->resolution)
        ->control('html')
        ->content($ticket->resolution);
    $sections[] = setting()
        ->control('fileList')
        ->fileTitle($lang->ticket->resolutionFiles)
        ->files($ticket->finishFiles)
        ->extra('finished')
        ->showDelete(false)
        ->padding(false)
        ->object($ticket);
}

$tabs = array();
$tabs[] = setting()
    ->group('basic')
    ->title($lang->ticket->legendBasicInfo)
    ->control('datalist')
    ->items($getBasicInfoItems($ticket));
$tabs[] = setting()
    ->group('basic')
    ->title($lang->ticket->legendLife)
    ->control('datalist')
    ->items($getLifeItems($ticket));
$tabs[] = setting()
    ->group('contacts')
    ->title($lang->custom->relateObject)
    ->control('relatedObjectList')
    ->objectID($ticket->id)
    ->objectType('ticket')
    ->browseType('byObject');
$tabs[] = setting()
    ->group('contacts')
    ->title($lang->ticket->contacts)
    ->control('datalist')
    ->items($getContactItems($ticketSources));

$config->ticket->actionList['createBug']['url']['params']   = "product={$ticket->product}&extra=projectID=0,fromType=ticket,fromID={id}";
$config->ticket->actionList['createStory']['url']['params'] = "product={$ticket->product}&extra=fromType=ticket,fromID={id}";
$operateList = $ticket->deleted ? array() : $this->loadModel('common')->buildOperateMenu($ticket);
$actions     = $ticket->deleted ? array() : array_merge($operateList['mainActions'], !empty($operateList['suffixActions']) && !empty($operateList['suffixActions']) ? array(array('type' => 'divider')) : array(), $operateList['suffixActions']);

detail
(
    to::title
    (
        label(zget($lang->ticket->statusList, $ticket->status), setClass("status-{$ticket->status} size-sm")),
        label($product->name, setClass('secondary size-sm'))
    ),
    set::objectType('ticket'),
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);

render();
