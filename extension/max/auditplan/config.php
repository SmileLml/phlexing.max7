<?php
$config->auditplan = new stdclass();
$config->auditplan->editor = new stdclass();
$config->auditplan->editor->create   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->auditplan->editor->edit     = array('id' => 'comment', 'tools' => 'simpleTools');
$config->auditplan->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');

$config->auditplan->datatable = new stdclass();
$config->auditplan->datatable->defaultField = array('id', 'objectID', 'execution', 'objectType', 'status', 'assignedTo', 'checkDate', 'realCheckDate', 'nc', 'actions');

$config->auditplan->datatable->fieldList['id']['title']    = 'idAB';
$config->auditplan->datatable->fieldList['id']['fixed']    = 'left';
$config->auditplan->datatable->fieldList['id']['width']    = '70';
$config->auditplan->datatable->fieldList['id']['required'] = 'yes';

$config->auditplan->datatable->fieldList['process']['title']    = 'process';
$config->auditplan->datatable->fieldList['process']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['process']['width']    = '100';
$config->auditplan->datatable->fieldList['process']['required'] = 'no';

$config->auditplan->datatable->fieldList['processType']['title']    = 'processType';
$config->auditplan->datatable->fieldList['processType']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['processType']['width']    = '95';
$config->auditplan->datatable->fieldList['processType']['required'] = 'no';

$config->auditplan->datatable->fieldList['objectID']['title']    = 'objectID';
$config->auditplan->datatable->fieldList['objectID']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['objectID']['width']    = 'auto';
$config->auditplan->datatable->fieldList['objectID']['required'] = 'no';

$config->auditplan->datatable->fieldList['execution']['title']    = 'execution';
$config->auditplan->datatable->fieldList['execution']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['execution']['width']    = '100';
$config->auditplan->datatable->fieldList['execution']['required'] = 'no';

$config->auditplan->datatable->fieldList['objectType']['title']    = 'objectType';
$config->auditplan->datatable->fieldList['objectType']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['objectType']['width']    = '95';
$config->auditplan->datatable->fieldList['objectType']['required'] = 'no';

$config->auditplan->datatable->fieldList['status']['title']    = 'status';
$config->auditplan->datatable->fieldList['status']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['status']['width']    = '60';
$config->auditplan->datatable->fieldList['status']['required'] = 'no';

$config->auditplan->datatable->fieldList['createdBy']['title']    = 'createdBy';
$config->auditplan->datatable->fieldList['createdBy']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['createdBy']['width']    = '80';
$config->auditplan->datatable->fieldList['createdBy']['required'] = 'no';

$config->auditplan->datatable->fieldList['assignedTo']['title']    = 'assignedTo';
$config->auditplan->datatable->fieldList['assignedTo']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['assignedTo']['width']    = '120';
$config->auditplan->datatable->fieldList['assignedTo']['required'] = 'no';

$config->auditplan->datatable->fieldList['checkDate']['title']    = 'checkDate';
$config->auditplan->datatable->fieldList['checkDate']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['checkDate']['width']    = '100';
$config->auditplan->datatable->fieldList['checkDate']['required'] = 'no';

$config->auditplan->datatable->fieldList['realCheckDate']['title']    = 'realCheckDate';
$config->auditplan->datatable->fieldList['realCheckDate']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['realCheckDate']['width']    = '100';
$config->auditplan->datatable->fieldList['realCheckDate']['required'] = 'no';

$config->auditplan->datatable->fieldList['nc']['title']    = 'nc';
$config->auditplan->datatable->fieldList['nc']['fixed']    = 'no';
$config->auditplan->datatable->fieldList['nc']['width']    = '80';
$config->auditplan->datatable->fieldList['nc']['required'] = 'no';
$config->auditplan->datatable->fieldList['nc']['sort']     = 'no';

$config->auditplan->datatable->fieldList['actions']['title']    = 'actions';
$config->auditplan->datatable->fieldList['actions']['fixed']    = 'right';
$config->auditplan->datatable->fieldList['actions']['width']    = '148';
$config->auditplan->datatable->fieldList['actions']['required'] = 'yes';

global $lang;
$config->auditplan->objectTypeList = array('activity' => $lang->auditplan->activity, 'zoutput' =>$lang->auditplan->zoutput);
$config->auditplan->search['onMenuBar']               = 'yes';
$config->auditplan->search['module']                  = 'auditplan';
$config->auditplan->search['fields']['objectID']      = $lang->auditplan->objectID;
$config->auditplan->search['fields']['id']            = $lang->auditplan->id;
$config->auditplan->search['fields']['status']        = $lang->auditplan->status;
$config->auditplan->search['fields']['assignedTo']    = $lang->auditplan->assignedTo;
$config->auditplan->search['fields']['process']       = $lang->auditplan->process;
$config->auditplan->search['fields']['processType']   = $lang->auditplan->processType;
$config->auditplan->search['fields']['execution']     = $lang->auditplan->execution;
$config->auditplan->search['fields']['objectType']    = $lang->auditplan->objectType;
$config->auditplan->search['fields']['checkDate']     = $lang->auditplan->checkDate;
$config->auditplan->search['fields']['realCheckDate'] = $lang->auditplan->realCheckDate;
$config->auditplan->search['fields']['createdBy']     = $lang->auditplan->createdBy;
$config->auditplan->search['fields']['createdDate']   = $lang->auditplan->createdDate;
$config->auditplan->search['fields']['checkedBy']     = $lang->auditplan->checkedBy;

$config->auditplan->search['params']['objectID']      = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->auditplan->search['params']['id']            = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->auditplan->search['params']['status']        = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->auditplan->search['params']['assignedTo']    = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->auditplan->search['params']['process']       = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->auditplan->search['params']['processType']   = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->auditplan->search['params']['execution']     = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->auditplan->search['params']['objectType']    = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->auditplan->search['params']['checkDate']     = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->auditplan->search['params']['realCheckDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->auditplan->search['params']['createdBy']     = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->auditplan->search['params']['createdDate']   = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->auditplan->search['params']['checkedBy']     = array('operator' => '=', 'control' => 'select', 'values' => 'users');

