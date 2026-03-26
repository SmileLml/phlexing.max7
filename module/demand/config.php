<?php
$config->demand = new stdclass();

$config->demand->create      = new stdclass();
$config->demand->edit        = new stdclass();
$config->demand->change      = new stdclass();
$config->demand->close       = new stdclass();
$config->demand->review      = new stdclass();
$config->demand->batchcreate = new stdclass();
$config->demand->distribute  = new stdclass();
$config->demand->retract     = new stdclass();

$config->demand->create->requiredFields      = 'title';
$config->demand->edit->requiredFields        = 'title';
$config->demand->batchcreate->requiredFields = 'title';
$config->demand->change->requiredFields      = 'title';
$config->demand->close->requiredFields       = 'closedReason';
$config->demand->review->requiredFields      = '';
$config->demand->distribute->requiredFields  = 'product';
$config->demand->retract->requiredFields     = 'retractedReason';

$config->demand->list = new stdclass();
$config->demand->list->customBatchCreateFields = 'product,verify,source,duration,BSA,keywords';
$config->demand->list->customCreateFields      = 'source,verify,pri,mailto,keywords';

$config->demand->custom = new stdclass();
$config->demand->custom->batchCreateFields = 'spec,pri,estimate,review';

$config->demand->editor = new stdclass();
$config->demand->editor->create     = array('id' => 'spec,verify', 'tools' => 'simpleTools');
$config->demand->editor->change     = array('id' => 'spec,verify,comment', 'tools' => 'simpleTools');
$config->demand->editor->edit       = array('id' => 'spec,verify,comment', 'tools' => 'simpleTools');
$config->demand->editor->view       = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->demand->editor->close      = array('id' => 'comment', 'tools' => 'simpleTools');
$config->demand->editor->review     = array('id' => 'comment', 'tools' => 'simpleTools');
$config->demand->editor->activate   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->demand->editor->assignto   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->demand->editor->distribute = array('id' => 'comment', 'tools' => 'simpleTools');
$config->demand->editor->retract    = array('id' => 'comment', 'tools' => 'simpleTools');

$config->demand->excludeCheckFileds = ',uploadImage,category,pri,';

global $lang;
$config->demand->search['module'] = 'demand';
$config->demand->search['fields']['title']          = $lang->demand->title;
$config->demand->search['fields']['keywords']       = $lang->demand->keywords;
$config->demand->search['fields']['pri']            = $lang->demand->pri;
$config->demand->search['fields']['status']         = $lang->demand->status;
$config->demand->search['fields']['stage']          = $lang->demand->stage;
$config->demand->search['fields']['assignedTo']     = $lang->demand->assignedTo;
$config->demand->search['fields']['assignedDate']   = $lang->demand->assignedDate;
$config->demand->search['fields']['category']       = $lang->demand->category;
$config->demand->search['fields']['duration']       = $lang->demand->duration;
$config->demand->search['fields']['BSA']            = $lang->demand->BSA;
$config->demand->search['fields']['pool']           = $lang->demand->pool;
$config->demand->search['fields']['product']        = $lang->demand->product;
$config->demand->search['fields']['source']         = $lang->demand->source;
$config->demand->search['fields']['sourceNote']     = $lang->demand->sourceNote;
$config->demand->search['fields']['feedbackedBy']   = $lang->demand->feedbackedBy;
$config->demand->search['fields']['email']          = $lang->demand->email;
$config->demand->search['fields']['reviewedBy']     = $lang->demand->reviewedBy;
$config->demand->search['fields']['reviewedDate']   = $lang->demand->reviewedDate;
$config->demand->search['fields']['createdBy']      = $lang->demand->createdBy;
$config->demand->search['fields']['createdDate']    = $lang->demand->createdDate;
$config->demand->search['fields']['closedBy']       = $lang->demand->closedBy;
$config->demand->search['fields']['closedDate']     = $lang->demand->closedDate;
$config->demand->search['fields']['closedReason']   = $lang->demand->closedReason;
$config->demand->search['fields']['lastEditedBy']   = $lang->demand->lastEditedBy;
$config->demand->search['fields']['lastEditedDate'] = $lang->demand->lastEditedDate;
$config->demand->search['fields']['activatedDate']  = $lang->demand->activatedDate;
$config->demand->search['fields']['mailto']         = $lang->demand->mailto;
$config->demand->search['fields']['version']        = $lang->demand->version;
$config->demand->search['fields']['id']             = $lang->demand->id;

$config->demand->search['params']['title']          = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->demand->search['params']['keywords']       = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->demand->search['params']['pri']            = array('operator' => '=', 'control' => 'select',  'values' => $lang->demand->priList);
$config->demand->search['params']['status']         = array('operator' => '=', 'control' => 'select', 'values' => $lang->demand->statusList);
$config->demand->search['params']['stage']          = array('operator' => '=', 'control' => 'select', 'values' => $lang->demand->stageList);
$config->demand->search['params']['assignedTo']     = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->demand->search['params']['assignedDate']   = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->demand->search['params']['category']       = array('operator' => '=', 'control' => 'select', 'values' => $lang->demand->categoryList);
$config->demand->search['params']['duration']       = array('operator' => '=', 'control' => 'select', 'values' => $lang->demand->durationList);
$config->demand->search['params']['BSA']            = array('operator' => '=', 'control' => 'select', 'values' => $lang->demand->bsaList);
$config->demand->search['params']['pool']           = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->demand->search['params']['product']        = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->demand->search['params']['source']         = array('operator' => '=', 'control' => 'select', 'values' => $lang->demand->sourceList);
$config->demand->search['params']['sourceNote']     = array('operator' => 'include', 'control' => 'input', 'class' => '', 'values' => '');
$config->demand->search['params']['feedbackedBy']   = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->demand->search['params']['email']          = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->demand->search['params']['reviewedBy']     = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->demand->search['params']['reviewedDate']   = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->demand->search['params']['createdBy']      = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->demand->search['params']['createdDate']    = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->demand->search['params']['closedBy']       = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->demand->search['params']['closedDate']     = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->demand->search['params']['closedReason']   = array('operator' => '=', 'control' => 'select', 'values' => $lang->demand->reasonList);
$config->demand->search['params']['lastEditedBy']   = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->demand->search['params']['lastEditedDate'] = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->demand->search['params']['activatedDate']  = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->demand->search['params']['mailto']         = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->demand->search['params']['version']        = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->demand->search['params']['id']             = array('operator' => '=', 'control' => 'input', 'values' => '');

$config->demand->templateFields = 'product,title,spec,source,sourceNote,verify,keywords,category,pri,assignedTo,duration,BSA';
$config->demand->listFields     = 'product,source,category,pri,assignedTo,duration,BSA,status,stage,feedbackedBy,reviewedBy,createdBy,closedBy,closedReason,lastEditedBy,mailto';
$config->demand->exportFields   = 'id,title,pri,status,stage,spec,verify,keywords,assignedTo,category,duration,BSA,product,source,sourceNote,feedbackedBy,email,reviewedBy,reviewedDate,createdBy,createdDate,assignedDate,closedBy,closedDate,closedReason,lastEditedBy,lastEditedDate,activatedDate,mailto';
$config->demand->selectedFields = 'id,title,pri,status,stage,spec,verify,keywords,assignedTo,category,duration,BSA';

$config->demand->feedbackSource  = array('customer', 'user', 'market', 'service', 'operation', 'support', 'forum');
$config->demand->defaultPriority = 3;
