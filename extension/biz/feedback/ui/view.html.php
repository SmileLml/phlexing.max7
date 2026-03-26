<?php
/**
 * The view view file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
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

$feedback->realStatus = $this->processStatus('feedback', $feedback);
$getBasicInfoItems = function($feedback) use($product, $products, $getModulePath, $modulePath, $users)
{
    global $app, $lang;
    $canViewProduct = hasPriv('product', 'view') && in_array($feedback->product, explode(',', $app->user->view->products)) && isset($products[$feedback->product]) && !$product->shadow;
    $modulePath     = $getModulePath($modulePath);
    $mailtoList     = '';
    $reviewList     = '';
    foreach(explode(',', str_replace(' ', '', $feedback->mailto)) as $account) $mailtoList .= zget($users, $account) . ' ';
    foreach(explode(',', $feedback->reviewedBy) as $reviewedBy) $reviewList .= zget($users, $reviewedBy) . ' ';

    $items = array();
    $items[$lang->feedback->product]     = $canViewProduct ? array('control' => 'link', 'url' => createLink('product', 'view', "productID={$feedback->product}"), 'text' => $product->name) : $product->name;
    $items[$lang->feedback->module]      = array('control' => 'text', 'content' => $modulePath['printModule'], 'title' => $modulePath['moduleTitle'], 'id' => 'moduleBox');
    $items[$lang->feedback->status]      = array('control' => 'status', 'class' => "status-feedback", 'status'  => $feedback->status, 'text' => $feedback->realStatus);
    $items[$lang->feedback->type]        = zget($lang->feedback->typeList, $feedback->type, '');
    $items[$lang->feedback->pri]         = array('control' => 'pri', 'pri' => $feedback->pri, 'text' => zget($lang->feedback->priList, $feedback->pri, ''));
    $items[$lang->feedback->solution]    = zget($lang->feedback->solutionList, $feedback->solution, '');
    $items[$lang->feedback->openedBy]    = zget($users, $feedback->openedBy) . ' ' . $lang->at . ' ' . $feedback->openedDate;
    $items[$lang->feedback->assignedTo]  = zget($users, $feedback->assignedTo);
    $items[$lang->feedback->feedbackBy]  = $feedback->feedbackBy;
    $items[$lang->feedback->notifyEmail] = $feedback->notifyEmail;
    $items[$lang->feedback->mailto]      = $mailtoList;
    $items[$lang->feedback->keywords]    = $feedback->keywords;
    if($feedback->activatedBy) $items[$lang->feedback->activatedBy] = zget($users, $feedback->activatedBy) . ' ' . $lang->at . ' ' . $feedback->activatedDate;
    if($feedback->reviewedBy)  $items[$lang->feedback->reviewedBy]  = $reviewList . ' ' . $lang->at . ' ' . substr($feedback->reviewedDate, 0, 10);
    if($feedback->processedBy) $items[$lang->feedback->processedBy] = zget($users, $feedback->processedBy) . ' ' . $lang->at . ' ' . $feedback->processedDate;
    if($feedback->closedBy)    $items[$lang->feedback->closedBy]    = zget($users, $feedback->closedBy) . ' ' . $lang->at . ' ' . $feedback->closedDate;
    $items[$lang->feedback->closedReason] = zget($lang->feedback->closedReasonList, $feedback->closedReason, '');

    return $items;
};

$getContactsItems = function() use($contacts, $lang)
{
    $items = array();
    foreach($contacts as $contact)
    {
        $realname = $contact->realname ? $contact->realname : $contact->account;
        $items[$realname] = array('control' => 'list', 'items' => array());
        if($contact->mobile) $items[$realname]['items'][] = $lang->user->mobile . ': ' . $contact->mobile;
        if($contact->email)  $items[$realname]['items'][] = $lang->user->email  . ': ' . $contact->email;
        if($contact->phone)  $items[$realname]['items'][] = $lang->user->phone  . ': ' . $contact->phone;
        if($contact->qq)     $items[$realname]['items'][] = $lang->user->qq     . ': ' . $contact->qq;
        if($contact->skype)  $items[$realname]['items'][] = $lang->user->skype  . ': ' . $contact->skype;
    }
    return $items;
};

$sections = array();
$sections[] = setting()
    ->title($lang->feedback->desc)
    ->control('html')
    ->content(empty($feedback->desc) ? $lang->noDesc : $feedback->desc);

if(!empty($feedback->files))
{
    $sections[] = array
    (
        'control' => 'fileList',
        'object'  => $feedback,
        'files'   => $feedback->files,
        'padding' => false
    );
}

$config->feedback->dtable->relation->fieldList['product']['map'] = $products;
$config->feedback->dtable->relation->fieldList['stage']['map']   = $lang->story->stageList;
$config->feedback->dtable->relation->fieldList['roadmap']['map'] = isset($roadmaps) ? $roadmaps : array();
if(!isset($relations)) $relations = array();
foreach($relations as $relationType => $relationList)
{
    $cols   = $config->feedback->dtable->relation->fieldList;
    $module = $relationType == 'userStory' ? 'requirement' : $relationType;
    if($config->vision != 'or' || $relationType != 'userStory')
    {
        unset($cols['product']);
        unset($cols['roadmap']);
    }

    if(!in_array($relationType, array('story', 'userStory', 'epic'))) unset($cols['stage']);

    $cols['title']['link'] = array('module' => $module, 'method' => 'view', 'params' => 'id={id}');

    $sections[] = setting()
        ->title($lang->feedback->{$relationType})
        ->control('dtable')
        ->cols(array_values($cols))
        ->data($relationList)
        ->checkable(false)
        ->width('100%')
        ->extensible(false);
}

$tabs[] = setting()
    ->group('basic')
    ->title($lang->feedback->labelBasic)
    ->control('datalist')
    ->labelWidth($app->clientLang == 'zh-cn' || $app->clientLang == 'zh-tw' ? 68 : 120)
    ->items($getBasicInfoItems($feedback));

$tabs[] = setting()
    ->group('feedbacksource')
    ->title($lang->custom->relateObject)
    ->control('relatedObjectList')
    ->objectID($feedback->id)
    ->objectType('feedback')
    ->browseType('byObject');

$tabs[] = setting()
    ->group('feedbacksource')
    ->title($lang->feedback->sourceInfo)
    ->control('datalist')
    ->items(array
    (
        $lang->feedback->feedbackBy => $feedback->feedbackBy,
        $lang->feedback->company    => $feedback->source,
        $lang->feedback->email      => $feedback->notifyEmail
    ));
$tabs[] = setting()
    ->group('feedbacksource')
    ->title($lang->feedback->internalContact)
    ->control('datalist')
    ->items($getContactsItems());

/* Remove can not show actions. */
if($feedback->status == 'closed')                              unset($config->feedback->actions->{$this->methodName}['mainActions']['effort']);
if($this->app->user->account != $feedback->openedBy)           unset($config->feedback->actions->{$this->methodName}['mainActions']['ask']);
if($feedback->status == 'noreview')                            unset($config->feedback->actions->{$this->methodName}['mainActions']['ask']);
if(!in_array($feedback->status, array('clarify', 'noreview'))) unset($config->feedback->actions->{$this->methodName}['mainActions']['toTodo']);
if(!$feedback->public)
{
    unset($config->feedback->actions->{$this->methodName}['mainActions']['comment']);
    unset($config->feedback->actions->{$this->methodName}['suffixActions']['ajaxLike']);
}
if($this->config->vision == 'lite' || strpos('closed|clarify|noreview', $feedback->status) !== false)
{
    unset($config->feedback->actions->{$this->methodName}['mainActions']['reply']);
    unset($config->feedback->actions->{$this->methodName}['mainActions']['convert']);
}

$actions = $feedback->deleted ? array() : $this->loadModel('common')->buildOperateMenu($feedback);
$hasDivider = !empty($actions['mainActions']) && !empty($actions['suffixActions']);
if(!empty($actions)) $actions = array_merge($actions['mainActions'], $hasDivider ? array(array('type' => 'divider')) : array(), $actions['suffixActions']);

/* Build convert dropmenu items. */
$hasProductPriv = in_array($feedback->product, explode(',', $this->app->user->view->products));
$inORVision     = $config->vision == 'or';
$canCreateBug   = commonModel::hasPriv('bug', 'create');
$convertItems   = array();
if($hasProductPriv && \feedbackModel::isClickable($feedback, 'toDemand') && $inORVision)                 $convertItems[] = array('text' => $lang->feedback->toDemand, 'data-url' => createLink('feedback', 'toDemand', "poolID=0&demandID=0&extra=fromType=feedback,fromID=$feedback->id"), 'data-app' => 'feedback');
if($hasProductPriv && \feedbackModel::isClickable($feedback, 'toTicket') && !$inORVision)                $convertItems[] = array('text' => $lang->ticket->common, 'data-url' => createLink('feedback', 'toTicket', "extras=fromType=feedback,fromID=$feedback->id"), 'data-app' => 'feedback', 'data-toggle' => 'modal', 'data-size' => 'lg');
if($hasProductPriv && \feedbackModel::isClickable($feedback, 'toStory')  && !$inORVision)                $convertItems[] = array('text' => $lang->SRCommon, 'data-url' => createLink('feedback', 'toStory', "product=$feedback->product&extra=fromType=feedback,fromID=$feedback->id"), 'data-app' => 'feedback');
if($hasProductPriv && \feedbackModel::isClickable($feedback, 'toUserStory'))                             $convertItems[] = array('text' => $lang->URCommon, 'data-url' => createLink('feedback', 'toUserStory', "product=$feedback->product&extra=fromType=feedback,fromID=$feedback->id"), 'data-app' => 'feedback');
if($hasProductPriv && \feedbackModel::isClickable($feedback, 'toEpic'))                                  $convertItems[] = array('text' => $lang->ERCommon, 'data-url' => createLink('feedback', 'toEpic', "product=$feedback->product&extra=fromType=feedback,fromID=$feedback->id"), 'data-app' => 'feedback');
if($hasProductPriv && \feedbackModel::isClickable($feedback, 'toTask'))                                  $convertItems[] = array('text' => $lang->task->common, 'data-url' => '###', 'data-toggle' => 'modal', 'data-target' => '#toTask', 'data-id' => $feedback->id, 'data-product' => $feedback->product, 'data-on' => 'click', 'data-call' => 'getFeedbackID', 'data-params' => '$element');
if($hasProductPriv && \feedbackModel::isClickable($feedback, 'toBug')  && !$inORVision && $canCreateBug) $convertItems[] = array('text' => $lang->bug->common, 'data-url' => createLink('bug', 'create', "product=$feedback->product&branch=0&extras=projectID=0,fromType=feedback,fromID=$feedback->id"), 'data-id' => $feedback->id, 'data-app' => 'feedback');
if($hasProductPriv && \feedbackModel::isClickable($feedback, 'toTodo'))                                  $convertItems[] = array('text' => $lang->todo->common, 'data-url' => createLink('feedback', 'toTodo', "feedbackID=$feedback->id"), 'data-toggle' => 'modal');

/* Append to convert button, and process like action, and filter empty action. */
if($convertItems) menu(setID('convertActions'), setClass('menu dropdown-menu'), set::items($convertItems));
foreach($actions as $key => $action)
{
    if(empty($action)) unset($actions[$key]);
    if(isset($action['key']) && $action['key'] == 'convert')
    {
        if($convertItems) $actions[$key]['url'] = '#convertActions';
        if(empty($convertItems)) unset($actions[$key]);
    }

    if(isset($action['key']) && $action['key'] == 'like')
    {
        $likeByTitle = '';
        foreach(explode(',', $feedback->likes) as $likeBy)
        {
            $likeByTitle .= zget($users, $likeBy) . ',';
            if($likeBy == $app->user->account) $actions[$key]['icon'] = 'thumbs-up-solid';
        }
        $likeByTitle = trim($likeByTitle, ',');
        if($likeByTitle) $likeByTitle .= $this->lang->feedback->feelLike;

        $actions[$key]['text'] = "({$feedback->likesCount})";
        $actions[$key]['hint'] = $likeByTitle;
    }

    if($app->tab == 'my' && isInModal() && isset($action['data-load']) && $action['data-load'] == 'modal')
    {
        $action['data-toggle'] = 'modal';
        unset($action['data-load']);
        $actions[$key] = $action;
    }
}

jsVar('langNoProject', $lang->feedback->noProject);
jsVar('langNoExecution', $lang->feedback->noExecution);
jsVar('moduleID', $feedback->module);
jsVar('feedbackID', $feedback->id);

detail($feedback->public ? to::titleLeading(label($lang->feedback->public, setClass('secondary size-sm whitespace-nowrap'))) : null, to::title
(
    label($feedback->realStatus, setClass("status-{$feedback->status} size-sm whitespace-nowrap")),
    label($product->name, setClass('secondary size-sm whitespace-nowrap'))
), set::objectType('feedback'), set::sections($sections), set::tabs($tabs), set::actions(array_values($actions)));

if($config->vision != 'lite')
{
    modal
    (
        setID('toTask'),
        set::title($lang->feedback->selectProjects),
        form
        (
            formGroup
            (
                set::label($lang->feedback->project),
                set::required(true),
                on::change('[name=taskProjects]', 'getExecutions(event.target.value)'),
                picker(set::name('taskProjects'), set::items($projects))
            ),
            formGroup
            (
                set::label($lang->feedback->execution),
                set::labelClass('executionHead'),
                set::required(true),
                picker(set::name('executions'), set::items(array()))
            ),
            set::actions(array(array('text' => $lang->feedback->nextStep, 'class' => 'primary', 'data-on' => 'click', 'data-call' => 'createTask'), array('text' => $lang->cancel, 'data-type' => 'submit', 'data-dismiss' => 'modal')))
        )
    );
}
