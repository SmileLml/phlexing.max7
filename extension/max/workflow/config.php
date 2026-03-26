<?php
$config->workflow->require = new stdclass();
$config->workflow->require->create      = 'module,name';
$config->workflow->require->copy        = 'module,name';
$config->workflow->require->edit        = 'module,name';
$config->workflow->require->release     = 'module,name,app';
$config->workflow->require->setapproval = 'approval';

$config->workflowtable = new stdclass();
$config->workflowtable->require = new stdclass();
$config->workflowtable->require->create = 'module';
$config->workflowtable->require->edit   = 'module';

$config->workflow->apps = array('crm', 'oa', 'proj', 'doc', 'cash', 'team', 'welfare', 'hr', 'psi', 'flow', 'ameba');
$config->workflow->icons = array('flow', 'time', 'trigger', 'ver', 'thumbs-up', 'unlink', 'target', 'manager', 'meh', 'smile', 'manual', 'person', 'private', 'publish', 'report-list', 'resume', 'star-empty', 'theme', 'cube', 'lang', 'menu-backend');

$config->workflow->uniqueFields  = 'module';
$config->workflow->virtualParams = ',currentUser,currentDept,deptManager,actor,today,now,';

global $lang;
$config->workflow->search['module'] = 'workflow';

/* If not set alias t1 for table there occurs an error caused by search them from both  TABLE_CUSTOMER and TABLE_DATING by left-join expression when search some fields such as createdBy or createdDate. */
$config->workflow->search['fields']['name']        = $lang->workflow->name;
$config->workflow->search['fields']['app']         = $lang->workflow->app;
$config->workflow->search['fields']['desc']        = $lang->workflow->desc;
$config->workflow->search['fields']['createdBy']   = $lang->workflow->createdBy;
$config->workflow->search['fields']['createdDate'] = $lang->workflow->createdDate;
$config->workflow->search['fields']['id']          = $lang->workflow->id;

$config->workflow->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->workflow->search['params']['app']         = array('operator' => '=',       'control' => 'select', 'values' => 'set in control');
$config->workflow->search['params']['desc']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->workflow->search['params']['createdBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->workflow->search['params']['createdDate'] = array('operator' => '>=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->workflow->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
