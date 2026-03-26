<?php
global $lang;
$config->deliverable = new stdclass();
$config->deliverable->create = new stdclass();
$config->deliverable->edit   = new stdclass();
$config->deliverable->create->requiredFields = 'name,module,method,model';
$config->deliverable->edit->requiredFields   = 'name,module,method,model';

$config->deliverable->actionList = array();
$config->deliverable->actionList['edit']['icon'] = 'edit';
$config->deliverable->actionList['edit']['text'] = $lang->deliverable->edit;
$config->deliverable->actionList['edit']['hint'] = $lang->deliverable->edit;
$config->deliverable->actionList['edit']['url']  = array('module' => 'deliverable', 'method' => 'edit', 'params' => 'id={id}');

$config->deliverable->actionList['delete']['icon']         = 'trash';
$config->deliverable->actionList['delete']['text']         = $lang->deliverable->delete;
$config->deliverable->actionList['delete']['hint']         = $lang->deliverable->delete;
$config->deliverable->actionList['delete']['url']          = array('module' => 'deliverable', 'method' => 'delete', 'params' => 'id={id}');
$config->deliverable->actionList['delete']['data-confirm'] = $lang->deliverable->confirmDelete;
$config->deliverable->actionList['delete']['class']        = 'ajax-submit';

$config->deliverable->actions = new stdclass();
$config->deliverable->actions->view = array();
$config->deliverable->actions->view['mainActions']   = array('edit');
$config->deliverable->actions->view['suffixActions'] = array('delete');

$config->deliverable->search['module']                   = 'deliverable';
$config->deliverable->search['fields']['name']           = $lang->deliverable->name;
$config->deliverable->search['fields']['module']         = $lang->deliverable->module;
$config->deliverable->search['fields']['method']         = $lang->deliverable->method;
$config->deliverable->search['fields']['model']          = $lang->deliverable->model;
$config->deliverable->search['fields']['createdBy']      = $lang->deliverable->createdBy;
$config->deliverable->search['fields']['lastEditedBy']   = $lang->deliverable->lastEditedBy;
$config->deliverable->search['fields']['id']             = $lang->idAB;

$config->deliverable->search['params']['name']           = array('operator' => 'include',  'control' => 'input', 'values' => '');
$config->deliverable->search['params']['module']         = array('operator' => '=',        'control' => 'select', 'values' => $lang->deliverable->moduleList);
$config->deliverable->search['params']['method']         = array('operator' => '=',        'control' => 'select', 'values' => $lang->deliverable->methodList);
$config->deliverable->search['params']['model']          = array('operator' => 'include',  'control' => 'select', 'values' => '');
$config->deliverable->search['params']['createdBy']      = array('operator' => '=',        'control' => 'select', 'values' => 'users');
$config->deliverable->search['params']['lastEditedBy']   = array('operator' => '=',        'control' => 'select', 'values' => 'users');
$config->deliverable->search['params']['id']             = array('operator' => '=',        'control' => 'input',  'values' => '');
