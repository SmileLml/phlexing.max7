<?php
global $lang, $app;

$config->product->customBatchEditFields = 'PO,PMT,type,acl';
$config->product->custom->batchEditFields = 'program,line,PMT,acl';
$app->loadLang('story');
$app->loadLang('demand');
$config->product->search['fields'] = array();
$config->product->search['fields']['title']          = $lang->story->title;
$config->product->search['fields']['id']             = $lang->story->id;
$config->product->search['fields']['pri']            = $lang->story->pri;
$config->product->search['fields']['status']         = $lang->story->status;
$config->product->search['fields']['stage']          = $lang->story->stage;
$config->product->search['fields']['product']        = $lang->story->product;
$config->product->search['fields']['branch']         = '';
$config->product->search['fields']['module']         = $lang->story->module;
$config->product->search['fields']['roadmap']        = $lang->story->roadmap;
$config->product->search['fields']['assignedTo']     = $lang->story->assignedTo;
$config->product->search['fields']['assignedDate']   = $lang->story->assignedDate;
$config->product->search['fields']['category']       = $lang->story->category;
$config->product->search['fields']['duration']       = $lang->story->duration;
$config->product->search['fields']['BSA']            = $lang->story->BSA;
$config->product->search['fields']['source']         = $lang->story->source;
$config->product->search['fields']['sourceNote']     = $lang->story->sourceNote;
$config->product->search['fields']['feedbackBy']     = $lang->story->feedbackBy;
$config->product->search['fields']['notifyEmail']    = $lang->story->notifyEmail;
$config->product->search['fields']['reviewedBy']     = $lang->story->reviewedBy;
$config->product->search['fields']['reviewedDate']   = $lang->story->reviewedDate;
$config->product->search['fields']['openedBy']       = $lang->story->openedBy;
$config->product->search['fields']['openedDate']     = $lang->story->openedDate;
$config->product->search['fields']['closedBy']       = $lang->story->closedBy;
$config->product->search['fields']['closedDate']     = $lang->story->closedDate;
$config->product->search['fields']['closedReason']   = $lang->story->closedReason;
$config->product->search['fields']['lastEditedBy']   = $lang->story->lastEditedBy;
$config->product->search['fields']['lastEditedDate'] = $lang->story->lastEditedDate;
$config->product->search['fields']['activatedDate']  = $lang->story->activatedDate;
$config->product->search['fields']['mailto']         = $lang->story->mailto;
$config->product->search['fields']['version']        = $lang->story->version;

$config->product->search['params']['BSA']         = array('operator' => '=',       'control' => 'select',  'values' => $lang->demand->bsaList);
$config->product->search['params']['duration']    = array('operator' => '=',       'control' => 'select',  'values' => $lang->demand->durationList);
$config->product->search['params']['feedbackBy']  = array('operator' => 'include', 'control' => 'input',   'values' => '');
$config->product->search['params']['notifyEmail'] = array('operator' => 'include', 'control' => 'input',   'values' => '');

$app->loadLang('product');
$config->product->all->search = array();
$config->product->all->search['module']                = 'product';
$config->product->all->search['fields']['name']        = $lang->product->name;
$config->product->all->search['fields']['program']     = $lang->product->program;
$config->product->all->search['fields']['line']        = $lang->product->line;
if(isset($config->setCode) and $config->setCode == 1) $config->product->all->search['fields']['code'] = $lang->product->code;
$config->product->all->search['fields']['desc']        = $lang->product->desc;
$config->product->all->search['fields']['PO']          = $lang->product->PO;
$config->product->all->search['fields']['PMT']         = $lang->product->PMT;
$config->product->all->search['fields']['reviewer']    = $lang->product->reviewer;
$config->product->all->search['fields']['type']        = $lang->product->type;
$config->product->all->search['fields']['createdDate'] = $lang->product->createdDate;
$config->product->all->search['fields']['createdBy']   = $lang->product->createdBy;

$config->product->all->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->all->search['params']['program']     = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->all->search['params']['line']        = array('operator' => '=',       'control' => 'select', 'values' => '');
if(isset($config->setCode) and $config->setCode == 1) $config->product->all->search['params']['code'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->all->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->product->all->search['params']['desc']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->all->search['params']['PO']          = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['PMT']         = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['reviewer']    = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['type']        = array('operator' => '=',       'control' => 'select', 'values' => $lang->product->typeList);
$config->product->all->search['params']['createdDate'] = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->product->all->search['params']['createdBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->product->list->exportFields = 'id,name,PO,PMT,type,waitedRoadmaps,launchedRoadmaps,draftStories,activeStories,launchedStories,developingStories';
$config->product->list->customBatchEditFields = 'PO,PMT,type,acl';

$config->product->memberFields = array('PO', 'PMT', 'createdBy');

$allConfig = $config->product->all->dtable->fieldList;

$config->product->all->dtable->fieldList = array();
$config->product->all->dtable->fieldList['name'] = $allConfig['name'];

if(!empty($config->setCode)) $config->product->all->dtable->fieldList['code'] = $allConfig['code'];

$config->product->all->dtable->fieldList['productLine'] = $allConfig['productLine'];

$config->product->all->dtable->fieldList['PO']  = $allConfig['PO'];
$config->product->all->dtable->fieldList['PO']['title'] = $lang->product->PO;

$config->product->all->dtable->fieldList['PMT'] = $allConfig['PO'];
$config->product->all->dtable->fieldList['PMT']['name']  = 'PMT';
$config->product->all->dtable->fieldList['PMT']['title'] = $lang->product->PMT;

$config->product->all->dtable->fieldList['waitedRoadmaps']['title'] = $lang->product->waitedRoadmaps;
$config->product->all->dtable->fieldList['waitedRoadmaps']['type']  = 'text';
$config->product->all->dtable->fieldList['waitedRoadmaps']['show']  = true;
$config->product->all->dtable->fieldList['waitedRoadmaps']['group'] = 2;

$config->product->all->dtable->fieldList['launchedRoadmaps'] = $config->product->all->dtable->fieldList['waitedRoadmaps'];
$config->product->all->dtable->fieldList['launchedRoadmaps']['title'] = $lang->product->launchedRoadmaps;

$config->product->all->dtable->fieldList['draftStories'] = $config->product->all->dtable->fieldList['waitedRoadmaps'];
$config->product->all->dtable->fieldList['draftStories']['title'] = $lang->product->draftStories;

$config->product->all->dtable->fieldList['activeStories'] = $config->product->all->dtable->fieldList['draftStories'];
$config->product->all->dtable->fieldList['activeStories']['title'] = $lang->product->activeStories;

$config->product->all->dtable->fieldList['launchedStories'] = $config->product->all->dtable->fieldList['activeStories'];
$config->product->all->dtable->fieldList['launchedStories']['title'] = $lang->product->launchedStories;

$config->product->all->dtable->fieldList['developingStories'] = $config->product->all->dtable->fieldList['launchedStories'];
$config->product->all->dtable->fieldList['developingStories']['title'] = $lang->product->developingStories;

$batchEditConfig = array();
foreach($config->product->form->batchEdit as $field => $fieldConfig)
{
    $batchEditConfig[$field] = $fieldConfig;
    if($field == 'PO') $batchEditConfig['PMT'] = array('type' => 'string', 'control' => 'select', 'width' => '128px', 'required' => false, 'default' => '', 'options' => array());
}
$config->product->form->batchEdit = $batchEditConfig;
