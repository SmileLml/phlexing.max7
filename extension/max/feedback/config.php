<?php
$config->feedback->create = new stdclass();
$config->feedback->create->requiredFields = 'product,title';
$config->feedback->edit = new stdclass();
$config->feedback->edit->requiredFields = 'product,title';
$config->feedback->comment = new stdclass();
$config->feedback->comment->requiredFields = 'comment';

$config->feedback->editor = new stdclass();
$config->feedback->editor->create   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->feedback->editor->edit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->feedback->editor->view     = array('id' => 'lastComment', 'tools' => 'simpleTools');
$config->feedback->editor->comment  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->feedback->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->feedback->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');
$config->feedback->editor->review   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->feedback->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');

$config->feedback->exportFields = 'id,module,product,title,desc,status,type,solution,pri,source,notifyEmail,feedbackBy,openedBy,openedDate,assignedTo,assignedDate,processedBy,processedDate,closedBy,closedDate,closedReason,mailto,editedBy,editedDate';
$config->feedback->frontFields  = 'id,module,product,title,desc,status,type,solution,closedReason';

$config->feedback->relationTypes = array('story' => TABLE_STORY, 'userStory' => TABLE_STORY, 'epic' => TABLE_STORY, 'bug' => TABLE_BUG, 'todo' => TABLE_TODO, 'task' => TABLE_TASK, 'ticket' => TABLE_TICKET, 'demand' => TABLE_DEMAND);

$config->feedback->relationStatusList              = array();
$config->feedback->relationStatusList['task']      = array('done', 'closed');
$config->feedback->relationStatusList['story']     = array('closed');
$config->feedback->relationStatusList['epic']      = array('closed');
$config->feedback->relationStatusList['userStory'] = array('closed');
$config->feedback->relationStatusList['bug']       = array('resolved', 'closed');
$config->feedback->relationStatusList['todo']      = array('done', 'closed');
$config->feedback->relationStatusList['ticket']    = array('done', 'closed');
$config->feedback->relationStatusList['demand']    = array('closed');

$config->feedback->undoneStatusList = array();
$config->feedback->undoneStatusList['task']      = array('wait', 'doing');
$config->feedback->undoneStatusList['story']     = array('draft', 'changing', 'active');
$config->feedback->undoneStatusList['userStory'] = array('draft', 'changing', 'active');
$config->feedback->undoneStatusList['epic']      = array('draft', 'changing', 'active');
$config->feedback->undoneStatusList['bug']       = array('active');
$config->feedback->undoneStatusList['todo']      = array('wait', 'doing');
$config->feedback->undoneStatusList['ticket']    = array('doing');
$config->feedback->undoneStatusList['demand']    = array('draft', 'reviewing', 'changing', 'active');

global $lang;
$config->feedback->search['module'] = 'feedback';
$config->feedback->search['fields']['title']         = $lang->feedback->title;
$config->feedback->search['fields']['id']            = 'ID';
$config->feedback->search['fields']['keywords']      = $lang->feedback->keywords;
$config->feedback->search['fields']['product']       = $lang->feedback->product;
$config->feedback->search['fields']['module']        = $lang->feedback->module;
$config->feedback->search['fields']['status']        = $lang->feedback->status;
$config->feedback->search['fields']['type']          = $lang->feedback->type;
$config->feedback->search['fields']['solution']      = $lang->feedback->solution;
$config->feedback->search['fields']['desc']          = $lang->feedback->desc;
$config->feedback->search['fields']['assignedTo']    = $lang->feedback->assignedTo;
$config->feedback->search['fields']['mailto']        = $lang->feedback->mailto;
$config->feedback->search['fields']['public']        = $lang->feedback->publicAB;
$config->feedback->search['fields']['openedBy']      = $lang->feedback->openedBy;
$config->feedback->search['fields']['openedDate']    = $lang->feedback->openedDate;
$config->feedback->search['fields']['feedbackBy']    = $lang->feedback->feedbackBy;
$config->feedback->search['fields']['notifyEmail']   = $lang->feedback->notifyEmail;
$config->feedback->search['fields']['reviewedBy']    = $lang->feedback->reviewedBy;
$config->feedback->search['fields']['processedBy']   = $lang->feedback->processedBy;
$config->feedback->search['fields']['processedDate'] = $lang->feedback->processedDate;
$config->feedback->search['fields']['closedBy']      = $lang->feedback->closedBy;
$config->feedback->search['fields']['closedDate']    = $lang->feedback->closedDate;
$config->feedback->search['fields']['activatedBy']   = $lang->feedback->activatedBy;
$config->feedback->search['fields']['activatedDate'] = $lang->feedback->activatedDate;
$config->feedback->search['fields']['closedReason']  = $lang->feedback->closedReason;
$config->feedback->search['fields']['pri']           = $lang->feedback->pri;
$config->feedback->search['fields']['source']        = $lang->feedback->source;

$config->feedback->search['params']['title']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->feedback->search['params']['id']            = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->feedback->search['params']['keywords']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->feedback->search['params']['module']        = array('operator' => '=', 'control' => 'select',  'values' => '');
$config->feedback->search['params']['product']       = array('operator' => '=', 'control' => 'select',  'values' => '');
$config->feedback->search['params']['status']        = array('operator' => '=', 'control' => 'select',  'values' => $lang->feedback->statusList);
$config->feedback->search['params']['type']          = array('operator' => '=', 'control' => 'select',  'values' => $lang->feedback->typeList);
$config->feedback->search['params']['solution']      = array('operator' => '=', 'control' => 'select',  'values' => $lang->feedback->solutionList);
$config->feedback->search['params']['assignedTo']    = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->feedback->search['params']['activatedBy']   = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->feedback->search['params']['mailto']        = array('operator' => 'include', 'control' => 'select',  'values' => 'users');
$config->feedback->search['params']['desc']          = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->feedback->search['params']['public']        = array('operator' => '=', 'control' => 'select',  'values' => $lang->feedback->publicList);
$config->feedback->search['params']['openedBy']      = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->feedback->search['params']['openedDate']    = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->feedback->search['params']['activatedDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->feedback->search['params']['reviewedBy']    = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->feedback->search['params']['feedbackBy']    = array('operator' => 'include', 'control' => 'input',  'values' => '', 'class' => '');
$config->feedback->search['params']['notifyEmail']   = array('operator' => 'include', 'control' => 'input',  'values' => '', 'class' => '');
$config->feedback->search['params']['processedBy']   = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->feedback->search['params']['processedDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->feedback->search['params']['closedBy']      = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->feedback->search['params']['closedDate']    = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->feedback->search['params']['closedReason']  = array('operator' => '=', 'control' => 'select',  'values' => $lang->feedback->closedReasonList);
$config->feedback->search['params']['pri']           = array('operator' => '=', 'control' => 'select',  'values' => $lang->feedback->priList);
$config->feedback->search['params']['source']        = array('operator' => 'include', 'control' => 'select',  'values' => 'sources');

$config->feedback->datatable = new stdclass();
$config->feedback->datatable->defaultField = array('id', 'title', 'pri', 'status', 'type', 'assignedTo', 'solution', 'openedBy', 'openedDate', 'editedDate', 'actions');
$config->feedback->datatable->fieldList['id']['title']    = 'idAB';
$config->feedback->datatable->fieldList['id']['fixed']    = 'left';
$config->feedback->datatable->fieldList['id']['width']    = '50';
$config->feedback->datatable->fieldList['id']['required'] = 'yes';

$config->feedback->datatable->fieldList['title']['title']    = 'title';
$config->feedback->datatable->fieldList['title']['fixed']    = 'left';
$config->feedback->datatable->fieldList['title']['width']    = 'auto';
$config->feedback->datatable->fieldList['title']['required'] = 'yes';

$config->feedback->datatable->fieldList['product']['title']      = 'product';
$config->feedback->datatable->fieldList['product']['fixed']      = 'left';
$config->feedback->datatable->fieldList['product']['width']      = '120';
$config->feedback->datatable->fieldList['product']['required']   = 'no';
$config->feedback->datatable->fieldList['product']['control']    = 'select';
$config->feedback->datatable->fieldList['product']['dataSource'] = array('module' => 'product', 'method' => 'getPairs', 'params' => "&0&&all");

$config->feedback->datatable->fieldList['pri']['title']    = 'P';
$config->feedback->datatable->fieldList['pri']['fixed']    = 'left';
$config->feedback->datatable->fieldList['pri']['width']    = '50';
$config->feedback->datatable->fieldList['pri']['required'] = 'no';
$config->feedback->datatable->fieldList['pri']['name']     = $lang->feedback->pri;

$config->feedback->datatable->fieldList['status']['title']    = 'status';
$config->feedback->datatable->fieldList['status']['fixed']    = 'no';
$config->feedback->datatable->fieldList['status']['width']    = '80';
$config->feedback->datatable->fieldList['status']['required'] = 'no';

$config->feedback->datatable->fieldList['type']['title']    = 'type';
$config->feedback->datatable->fieldList['type']['fixed']    = 'no';
$config->feedback->datatable->fieldList['type']['width']    = '80';
$config->feedback->datatable->fieldList['type']['required'] = 'no';

$config->feedback->datatable->fieldList['assignedTo']['title']    = 'assignedTo';
$config->feedback->datatable->fieldList['assignedTo']['fixed']    = 'no';
$config->feedback->datatable->fieldList['assignedTo']['width']    = '120';
$config->feedback->datatable->fieldList['assignedTo']['required'] = 'no';

$config->feedback->datatable->fieldList['solution']['title']    = 'solution';
$config->feedback->datatable->fieldList['solution']['fixed']    = 'no';
$config->feedback->datatable->fieldList['solution']['width']    = '150';
$config->feedback->datatable->fieldList['solution']['required'] = 'no';

$config->feedback->datatable->fieldList['dept']['title']    = 'dept';
$config->feedback->datatable->fieldList['dept']['fixed']    = 'no';
$config->feedback->datatable->fieldList['dept']['width']    = '120';
$config->feedback->datatable->fieldList['dept']['required'] = 'no';

$config->feedback->datatable->fieldList['openedBy']['title']    = 'openedBy';
$config->feedback->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->feedback->datatable->fieldList['openedBy']['width']    = '100';
$config->feedback->datatable->fieldList['openedBy']['required'] = 'no';

$config->feedback->datatable->fieldList['openedDate']['title']    = 'openedDate';
$config->feedback->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->feedback->datatable->fieldList['openedDate']['width']    = '100';
$config->feedback->datatable->fieldList['openedDate']['required'] = 'no';

$config->feedback->datatable->fieldList['feedbackBy']['title']    = 'feedbackBy';
$config->feedback->datatable->fieldList['feedbackBy']['fixed']    = 'no';
$config->feedback->datatable->fieldList['feedbackBy']['width']    = '100';
$config->feedback->datatable->fieldList['feedbackBy']['required'] = 'no';

$config->feedback->datatable->fieldList['company']['title']    = 'company';
$config->feedback->datatable->fieldList['company']['fixed']    = 'no';
$config->feedback->datatable->fieldList['company']['width']    = '150';
$config->feedback->datatable->fieldList['company']['required'] = 'no';

$config->feedback->datatable->fieldList['notifyEmail']['title']    = 'notifyEmailAB';
$config->feedback->datatable->fieldList['notifyEmail']['fixed']    = 'no';
$config->feedback->datatable->fieldList['notifyEmail']['width']    = '120';
$config->feedback->datatable->fieldList['notifyEmail']['required'] = 'no';

$config->feedback->datatable->fieldList['processedBy']['title']    = 'processedBy';
$config->feedback->datatable->fieldList['processedBy']['fixed']    = 'no';
$config->feedback->datatable->fieldList['processedBy']['width']    = '90';
$config->feedback->datatable->fieldList['processedBy']['required'] = 'no';

$config->feedback->datatable->fieldList['processedDate']['title']    = 'processedDate';
$config->feedback->datatable->fieldList['processedDate']['fixed']    = 'no';
$config->feedback->datatable->fieldList['processedDate']['width']    = '90';
$config->feedback->datatable->fieldList['processedDate']['required'] = 'no';

$config->feedback->datatable->fieldList['editedDate']['title']    = 'editedDate';
$config->feedback->datatable->fieldList['editedDate']['fixed']    = 'no';
$config->feedback->datatable->fieldList['editedDate']['width']    = '110';
$config->feedback->datatable->fieldList['editedDate']['required'] = 'no';

$config->feedback->datatable->fieldList['closedDate']['title']    = 'closedDate';
$config->feedback->datatable->fieldList['closedDate']['fixed']    = 'no';
$config->feedback->datatable->fieldList['closedDate']['width']    = '100';
$config->feedback->datatable->fieldList['closedDate']['required'] = 'no';

$config->feedback->datatable->fieldList['closedReason']['title']    = 'closedReason';
$config->feedback->datatable->fieldList['closedReason']['fixed']    = 'no';
$config->feedback->datatable->fieldList['closedReason']['width']    = '120';
$config->feedback->datatable->fieldList['closedReason']['required'] = 'no';

$config->feedback->datatable->fieldList['activatedBy']['title']    = 'activatedBy';
$config->feedback->datatable->fieldList['activatedBy']['fixed']    = 'no';
$config->feedback->datatable->fieldList['activatedBy']['width']    = '80';
$config->feedback->datatable->fieldList['activatedBy']['required'] = 'no';

$config->feedback->datatable->fieldList['activatedDate']['title']    = 'activatedDate';
$config->feedback->datatable->fieldList['activatedDate']['fixed']    = 'no';
$config->feedback->datatable->fieldList['activatedDate']['width']    = '100';
$config->feedback->datatable->fieldList['activatedDate']['required'] = 'no';

$config->feedback->datatable->fieldList['actions']['title']    = 'actions';
$config->feedback->datatable->fieldList['actions']['fixed']    = 'right';
$config->feedback->datatable->fieldList['actions']['width']    = '150';
$config->feedback->datatable->fieldList['actions']['required'] = 'yes';

$config->feedback->datatable->fieldList['notify']['title']      = 'notify';
$config->feedback->datatable->fieldList['notify']['control']    = 'select';
$config->feedback->datatable->fieldList['notify']['dataSource'] = array('lang' => 'notifyList');

$config->feedback->datatable->fieldList['public']['title']      = 'public';
$config->feedback->datatable->fieldList['public']['dataSource'] = array('lang' => 'publicList');

$config->feedback->datatable->fieldList['module']['title']      = 'module';
$config->feedback->datatable->fieldList['module']['dataSource'] = array('module' => 'feedback', 'method' => 'getModuleList', 'params' => "feedback&true&no");
$config->feedback->datatable->fieldList['module']['display']    = false;

$config->feedback->actions = new stdclass();
$config->feedback->actions->adminview = array();
$config->feedback->actions->adminview['mainActions']   = array('effort' => 'effort', 'ask' => 'ask', 'assignTo' => 'assignTo', 'review' => 'review', 'reply' => 'reply', 'convert' => 'convert', 'toTodo' => 'toTodo', 'activate' => 'activate', 'close' => 'close', 'comment' => 'comment');
$config->feedback->actions->adminview['suffixActions'] = array('edit' => 'edit', 'create' => 'create', 'delete' => 'delete', 'ajaxLike' => 'ajaxLike');

$config->feedback->actions->view = $config->feedback->actions->adminview;

$config->feedback->list = new stdclass();
$config->feedback->list->customBatchCreateFields = 'source,notifyEmail,keywords,mailto';

$config->feedback->custom = new stdclass();
$config->feedback->custom->batchCreateFields = '';
