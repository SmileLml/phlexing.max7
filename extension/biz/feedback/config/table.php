<?php
global $lang;
$config->feedback->actionList['edit']['icon']  = 'edit';
$config->feedback->actionList['edit']['hint']  = $lang->feedback->edit;
$config->feedback->actionList['edit']['url']   = array('module' => 'feedback', 'method' => 'edit', 'params' => 'feedbackID={id}');

$config->feedback->actionList['create']['icon'] = 'copy';
$config->feedback->actionList['create']['hint'] = $lang->feedback->copy;
$config->feedback->actionList['create']['text'] = $lang->feedback->copy;
$config->feedback->actionList['create']['url']  = array('module' => 'feedback', 'method' => 'create', 'params' => 'extras=&copyFeedbackID={id}');

$config->feedback->actionList['effort']['icon']        = 'time';
$config->feedback->actionList['effort']['hint']        = $lang->feedback->effort;
$config->feedback->actionList['effort']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'objectType=feedback&objectID={id}');
$config->feedback->actionList['effort']['data-toggle'] = 'modal';

$config->feedback->actionList['review']['icon']        = 'glasses';
$config->feedback->actionList['review']['hint']        = $lang->feedback->review;
$config->feedback->actionList['review']['url']         = array('module' => 'feedback', 'method' => 'review', 'params' => 'feedbackID={id}');
$config->feedback->actionList['review']['data-toggle'] = 'modal';
$config->feedback->actionList['review']['class']       = 'feedback-review-btn';

$config->feedback->actionList['assignTo']['icon']        = 'hand-right';
$config->feedback->actionList['assignTo']['hint']        = $lang->feedback->assign;
$config->feedback->actionList['assignTo']['url']         = array('module' => 'feedback', 'method' => 'assignTo', 'params' => 'feedbackID={id}');
$config->feedback->actionList['assignTo']['data-toggle'] = 'modal';

$config->feedback->actionList['ask']['icon']        = 'chat-line';
$config->feedback->actionList['ask']['hint']        = $lang->feedback->ask;
$config->feedback->actionList['ask']['url']         = array('module' => 'feedback', 'method' => 'ask', 'params' => 'feedbackID={id}');
$config->feedback->actionList['ask']['data-toggle'] = 'modal';

$config->feedback->actionList['reply']['icon']        = 'restart';
$config->feedback->actionList['reply']['hint']        = $lang->feedback->reply;
$config->feedback->actionList['reply']['url']         = array('module' => 'feedback', 'method' => 'reply', 'params' => 'feedbackID={id}');
$config->feedback->actionList['reply']['data-toggle'] = 'modal';

$config->feedback->actionList['toTicket']['icon']        = $lang->icons['ticket'];
$config->feedback->actionList['toTicket']['hint']        = $lang->feedback->toTicket;
$config->feedback->actionList['toTicket']['url']         = array('module' => 'feedback', 'method' => 'toTicket', 'params' => 'extra=fromType=feedback,fromID={id}', 'onlybody' => true);
$config->feedback->actionList['toTicket']['data-toggle'] = 'modal';
$config->feedback->actionList['toTicket']['data-size']   = 'lg';

$config->feedback->actionList['toStory']['icon']        = $lang->icons['story'];
$config->feedback->actionList['toStory']['hint']        = $lang->feedback->toStory;
$config->feedback->actionList['toStory']['url']         = array('module' => 'feedback', 'method' => 'toStory', 'params' => 'product={product}&extra=fromType=feedback,fromID={id}');
$config->feedback->actionList['toStory']['data-toggle'] = 'modal';
$config->feedback->actionList['toStory']['data-size']   = 'lg';

$config->feedback->actionList['toTask']['icon']        = $lang->icons['task'];
$config->feedback->actionList['toTask']['hint']        = $lang->feedback->toTask;
$config->feedback->actionList['toTask']['url']         = '{id}&{product}';
$config->feedback->actionList['toTask']['className']   = 'toTask';
$config->feedback->actionList['toTask']['data-target'] = '#toTask';
$config->feedback->actionList['toTask']['data-toggle'] = 'modal';
$config->feedback->actionList['toTask']['data-size']   = 'sm';
$config->feedback->actionList['toTask']['data-on']     = 'click';
$config->feedback->actionList['toTask']['data-call']   = 'clickTotask';
$config->feedback->actionList['toTask']['data-params'] = 'event';

$config->feedback->actionList['toBug']['icon']        = $lang->icons['bug'];
$config->feedback->actionList['toBug']['hint']        = $lang->feedback->toBug;
$config->feedback->actionList['toBug']['url']         = array('module' => 'bug', 'method' => 'create', 'params' => 'product={product}&branch=0&extras=projectID=0,fromType=feedback,fromID={id}');
$config->feedback->actionList['toBug']['data-toggle'] = 'modal';
$config->feedback->actionList['toBug']['data-size']   = 'lg';
$config->feedback->actionList['toBug']['class']       = 'feedback-toBug-btn';

$config->feedback->actionList['toUserStory']['icon']        = 'customer';
$config->feedback->actionList['toUserStory']['hint']        = $lang->feedback->toUserStory;
$config->feedback->actionList['toUserStory']['url']         = array('module' => 'feedback', 'method' => 'toUserStory', 'params' => 'product={product}&extra=fromType=feedback,fromID={id}');
$config->feedback->actionList['toUserStory']['data-toggle'] = 'modal';
$config->feedback->actionList['toUserStory']['data-size']   = 'lg';

if($config->enableER)
{
    $config->feedback->actionList['toEpic']['icon']        = 'product';
    $config->feedback->actionList['toEpic']['hint']        = $lang->feedback->toEpic;
    $config->feedback->actionList['toEpic']['url']         = array('module' => 'feedback', 'method' => 'toEpic', 'params' => 'product={product}&extra=fromType=feedback,fromID={id}');
    $config->feedback->actionList['toEpic']['data-toggle'] = 'modal';
    $config->feedback->actionList['toEpic']['data-size']   = 'lg';
}

$config->feedback->actionList['toTodo']['icon']        = $lang->icons['todo'];
$config->feedback->actionList['toTodo']['hint']        = $lang->feedback->toTodo;
$config->feedback->actionList['toTodo']['url']         = array('module' => 'feedback', 'method' => 'toTodo', 'params' => 'feedbackID={id}');
$config->feedback->actionList['toTodo']['data-toggle'] = 'modal';

$config->feedback->actionList['comment']['icon']        = 'confirm';
$config->feedback->actionList['comment']['hint']        = $lang->feedback->comment;
$config->feedback->actionList['comment']['url']         = array('module' => 'feedback', 'method' => 'comment', 'params' => 'feedbackID={id}&type=commented');
$config->feedback->actionList['comment']['data-toggle'] = 'modal';

$config->feedback->actionList['convert']['icon']           = 'arrow-right';
$config->feedback->actionList['convert']['hint']           = $lang->feedback->convert;
$config->feedback->actionList['convert']['text']           = $lang->feedback->convert;
$config->feedback->actionList['convert']['url']            = '';
$config->feedback->actionList['convert']['data-toggle']    = 'dropdown';
$config->feedback->actionList['convert']['data-placement'] = 'top-end';
$config->feedback->actionList['convert']['caret']          = 'up';
$config->feedback->actionList['convert']['key']            = 'convert';

$config->feedback->actionList['ajaxLike']['icon']      = 'thumbs-up';
$config->feedback->actionList['ajaxLike']['hint']      = '(0)';
$config->feedback->actionList['ajaxLike']['url']       = "javascript:like({id})";
$config->feedback->actionList['ajaxLike']['key']       = "like";

$config->feedback->actionList['close']['icon']        = 'off';
$config->feedback->actionList['close']['hint']        = $lang->feedback->close;
$config->feedback->actionList['close']['url']         = array('module' => 'feedback', 'method' => 'close', 'params' => 'feedbackID={id}');
$config->feedback->actionList['close']['data-toggle'] = 'modal';
$config->feedback->actionList['close']['class']       = 'feedback-close-btn';

$config->feedback->actionList['activate']['icon']        = 'magic';
$config->feedback->actionList['activate']['hint']        = $lang->feedback->activate;
$config->feedback->actionList['activate']['url']         = array('module' => 'feedback', 'method' => 'activate', 'params' => 'feedbackID={id}');
$config->feedback->actionList['activate']['data-toggle'] = 'modal';

$config->feedback->actionList['delete']['icon']         = 'trash';
$config->feedback->actionList['delete']['hint']         = $lang->feedback->delete;
$config->feedback->actionList['delete']['url']          = array('module' => 'feedback', 'method' => 'delete', 'params' => 'feedbackID={id}&type=newPage');
$config->feedback->actionList['delete']['data-confirm'] = array('message' => $lang->feedback->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->feedback->actionList['delete']['className']    = 'ajax-submit';

$config->feedback->dtable = new stdclass();

$config->feedback->dtable->fieldList['id']['name']  = 'id';
$config->feedback->dtable->fieldList['id']['title'] = $lang->idAB;
$config->feedback->dtable->fieldList['id']['type']  = 'checkID';

$viewMethod = $config->vision != 'lite' ? 'adminView' : 'view';
$config->feedback->dtable->fieldList['title']['name']  = 'title';
$config->feedback->dtable->fieldList['title']['title'] = $lang->feedback->title;
$config->feedback->dtable->fieldList['title']['link']  = array('module' => 'feedback', 'method' => $viewMethod, 'params' => "feedbackID={id}");
$config->feedback->dtable->fieldList['title']['type']  = 'title';
$config->feedback->dtable->fieldList['title']['fixed'] = 'left';

$config->feedback->dtable->fieldList['product']['name']  = 'product';
$config->feedback->dtable->fieldList['product']['title'] = $lang->feedback->product;
$config->feedback->dtable->fieldList['product']['type']  = 'category';

$config->feedback->dtable->fieldList['module']['name']  = 'module';
$config->feedback->dtable->fieldList['module']['title'] = $lang->feedback->module;
$config->feedback->dtable->fieldList['module']['type']  = 'category';

$config->feedback->dtable->fieldList['pri']['name']  = 'pri';
$config->feedback->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->feedback->dtable->fieldList['pri']['type']  = 'pri';
$config->feedback->dtable->fieldList['pri']['show']  = true;

$config->feedback->dtable->fieldList['status']['name']      = 'status';
$config->feedback->dtable->fieldList['status']['title']     = $lang->feedback->status;
$config->feedback->dtable->fieldList['status']['type']      = 'status';
$config->feedback->dtable->fieldList['status']['statusMap'] = $lang->feedback->statusList;
$config->feedback->dtable->fieldList['status']['show']      = true;

$config->feedback->dtable->fieldList['type']['name']  = 'type';
$config->feedback->dtable->fieldList['type']['title'] = $lang->feedback->type;
$config->feedback->dtable->fieldList['type']['type']  = 'category';
$config->feedback->dtable->fieldList['type']['map']   = $lang->feedback->typeList;
$config->feedback->dtable->fieldList['type']['show']  = true;

$config->feedback->dtable->fieldList['assignedTo']['name']        = 'assignedTo';
$config->feedback->dtable->fieldList['assignedTo']['title']       = $lang->feedback->assignedTo;
$config->feedback->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->feedback->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'feedback', 'method' => 'assignTo', 'params' => 'feedbackID={id}');
$config->feedback->dtable->fieldList['assignedTo']['data-toggle'] = 'modal';
$config->feedback->dtable->fieldList['assignedTo']['show']        = true;

$config->feedback->dtable->fieldList['solution']['name']  = 'solution';
$config->feedback->dtable->fieldList['solution']['title'] = $lang->feedback->solution;
$config->feedback->dtable->fieldList['solution']['map']   = $lang->feedback->solutionList;
$config->feedback->dtable->fieldList['solution']['type']  = 'text';
$config->feedback->dtable->fieldList['solution']['show']  = true;

$config->feedback->dtable->fieldList['dept']['name']  = 'dept';
$config->feedback->dtable->fieldList['dept']['title'] = $lang->feedback->dept;
$config->feedback->dtable->fieldList['dept']['type']  = 'category';

$config->feedback->dtable->fieldList['keywords']['name']  = 'keywords';
$config->feedback->dtable->fieldList['keywords']['title'] = $lang->feedback->keywords;
$config->feedback->dtable->fieldList['keywords']['width'] = '90';

$config->feedback->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->feedback->dtable->fieldList['openedBy']['title'] = $lang->feedback->openedBy;
$config->feedback->dtable->fieldList['openedBy']['type']  = 'user';
$config->feedback->dtable->fieldList['openedBy']['show']  = true;

$config->feedback->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->feedback->dtable->fieldList['openedDate']['title'] = $lang->feedback->openedDate;
$config->feedback->dtable->fieldList['openedDate']['type']  = 'date';
$config->feedback->dtable->fieldList['openedDate']['show']  = true;

$config->feedback->dtable->fieldList['feedbackBy']['name']  = 'feedbackBy';
$config->feedback->dtable->fieldList['feedbackBy']['title'] = $lang->feedback->feedbackBy;
$config->feedback->dtable->fieldList['feedbackBy']['type']  = 'text';

$config->feedback->dtable->fieldList['source']['name']  = 'source';
$config->feedback->dtable->fieldList['source']['title'] = $lang->feedback->source;
$config->feedback->dtable->fieldList['source']['type']  = 'text';

$config->feedback->dtable->fieldList['notifyEmail']['name']  = 'notifyEmail';
$config->feedback->dtable->fieldList['notifyEmail']['title'] = $lang->feedback->notifyEmail;
$config->feedback->dtable->fieldList['notifyEmail']['type']  = 'text';

$config->feedback->dtable->fieldList['processedBy']['name']  = 'processedBy';
$config->feedback->dtable->fieldList['processedBy']['title'] = $lang->feedback->processedBy;
$config->feedback->dtable->fieldList['processedBy']['type']  = 'user';

$config->feedback->dtable->fieldList['processedDate']['name']  = 'processedDate';
$config->feedback->dtable->fieldList['processedDate']['title'] = $lang->feedback->processedDate;
$config->feedback->dtable->fieldList['processedDate']['type']  = 'datetime';

$config->feedback->dtable->fieldList['editedBy']['name']  = 'editedBy';
$config->feedback->dtable->fieldList['editedBy']['title'] = $lang->feedback->editedBy;
$config->feedback->dtable->fieldList['editedBy']['type']  = 'user';

$config->feedback->dtable->fieldList['editedDate']['name']  = 'editedDate';
$config->feedback->dtable->fieldList['editedDate']['title'] = $lang->feedback->editedDate;
$config->feedback->dtable->fieldList['editedDate']['type']  = 'datetime';
$config->feedback->dtable->fieldList['editedDate']['show']  = true;

$config->feedback->dtable->fieldList['closedBy']['name']  = 'closedBy';
$config->feedback->dtable->fieldList['closedBy']['title'] = $lang->feedback->closedBy;
$config->feedback->dtable->fieldList['closedBy']['type']  = 'user';

$config->feedback->dtable->fieldList['closedDate']['name']  = 'closedDate';
$config->feedback->dtable->fieldList['closedDate']['title'] = $lang->feedback->closedDate;
$config->feedback->dtable->fieldList['closedDate']['type']  = 'datetime';

$config->feedback->dtable->fieldList['closedReason']['name']  = 'closedReason';
$config->feedback->dtable->fieldList['closedReason']['title'] = $lang->feedback->closedReason;
$config->feedback->dtable->fieldList['closedReason']['type']  = 'category';
$config->feedback->dtable->fieldList['closedReason']['map']   = $lang->feedback->closedReasonList;

$config->feedback->dtable->fieldList['relatedObject']['name']        = 'relatedObject';
$config->feedback->dtable->fieldList['relatedObject']['title']       = $lang->custom->relateObject;
$config->feedback->dtable->fieldList['relatedObject']['sortType']    = false;
$config->feedback->dtable->fieldList['relatedObject']['width']       = '70';
$config->feedback->dtable->fieldList['relatedObject']['type']        = 'text';
$config->feedback->dtable->fieldList['relatedObject']['link']        = common::hasPriv('custom', 'showRelationGraph') ? "RAWJS<function(info){ if(info.row.data.relatedObject == 0) return 0; else return '" . helper::createLink('custom', 'showRelationGraph', 'objectID={id}&objectType=feedback') . "'; }>RAWJS" : null;
$config->feedback->dtable->fieldList['relatedObject']['data-toggle'] = 'modal';
$config->feedback->dtable->fieldList['relatedObject']['data-size']   = 'lg';
$config->feedback->dtable->fieldList['relatedObject']['show']        = true;
$config->feedback->dtable->fieldList['relatedObject']['group']       = 8;
$config->feedback->dtable->fieldList['relatedObject']['flex']        = false;
$config->feedback->dtable->fieldList['relatedObject']['align']       = 'center';

$config->feedback->dtable->fieldList['actions']['name']  = 'actions';
$config->feedback->dtable->fieldList['actions']['title'] = $lang->actions;
$config->feedback->dtable->fieldList['actions']['width'] = 'auto';
$config->feedback->dtable->fieldList['actions']['type']  = 'actions';
$config->feedback->dtable->fieldList['actions']['list']  = $config->feedback->actionList;
$config->feedback->dtable->fieldList['actions']['menu']  = array('review', 'toStory', 'toBug', 'toTicket', 'more' => array('toEpic', 'toUserStory', 'toTask', 'toTodo'), 'edit', 'close|activate');
if($config->vision == 'lite') $config->feedback->dtable->fieldList['actions']['menu'] = array('edit', 'close|activate');

global $app;
$app->loadLang('story');

$config->feedback->dtable->relation = new stdclass();
$config->feedback->dtable->relation->fieldList['id']['name']  = 'id';
$config->feedback->dtable->relation->fieldList['id']['title'] = $lang->idAB;
$config->feedback->dtable->relation->fieldList['id']['type']  = 'id';
$config->feedback->dtable->relation->fieldList['id']['sort']  = false;

if($config->vision == 'or')
{
    $config->feedback->dtable->relation->fieldList['product']['name']  = 'product';
    $config->feedback->dtable->relation->fieldList['product']['title'] = $lang->story->product;
    $config->feedback->dtable->relation->fieldList['product']['type']  = 'category';
    $config->feedback->dtable->relation->fieldList['product']['sort']  = false;
    $config->feedback->dtable->relation->fieldList['product']['width'] = '120px';
}

$config->feedback->dtable->relation->fieldList['pri']['name']  = 'pri';
$config->feedback->dtable->relation->fieldList['pri']['title'] = 'P';
$config->feedback->dtable->relation->fieldList['pri']['type']  = 'pri';
$config->feedback->dtable->relation->fieldList['pri']['sort']  = false;
$config->feedback->dtable->relation->fieldList['pri']['width'] = '60px';

$config->feedback->dtable->relation->fieldList['title']['name']        = 'title';
$config->feedback->dtable->relation->fieldList['title']['title']       = $lang->feedback->title;
$config->feedback->dtable->relation->fieldList['title']['type']        = 'title';
$config->feedback->dtable->relation->fieldList['title']['sort']        = false;
$config->feedback->dtable->relation->fieldList['title']['data-toggle'] = 'modal';
$config->feedback->dtable->relation->fieldList['title']['data-size']   = 'lg';

$config->feedback->dtable->relation->fieldList['stage']['name']  = 'stage';
$config->feedback->dtable->relation->fieldList['stage']['title'] = $lang->story->stage;
$config->feedback->dtable->relation->fieldList['stage']['type']  = 'category';
$config->feedback->dtable->relation->fieldList['stage']['sort']  = false;

$config->feedback->dtable->relation->fieldList['status']['name']  = 'statusLabel';
$config->feedback->dtable->relation->fieldList['status']['title'] = $lang->story->status;
$config->feedback->dtable->relation->fieldList['status']['type']  = 'text';
$config->feedback->dtable->relation->fieldList['status']['sort']  = false;

if($config->vision == 'or')
{
    $config->feedback->dtable->relation->fieldList['roadmap']['name']  = 'roadmap';
    $config->feedback->dtable->relation->fieldList['roadmap']['title'] = $lang->story->roadmap;
    $config->feedback->dtable->relation->fieldList['roadmap']['type']  = 'category';
    $config->feedback->dtable->relation->fieldList['roadmap']['sort']  = false;
}
