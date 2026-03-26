<?php
$config->nc = new stdclass();
$config->nc->datatable = new stdclass();
$config->nc->editor    = new stdclass();
$config->nc->editor->edit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->nc->editor->resolve  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->nc->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->nc->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->nc->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');

$config->nc->create = new stdclass();
$config->nc->edit   = new stdclass();
$config->nc->create->requiredFields = 'title,auditplan,listID,severity';
$config->nc->edit->requiredFields   = 'title,auditplan,listID,severity';

$config->nc->list = new stdclass();
$config->nc->list->exportFields = 'id,severity,title,object,type,status,execution,auditplan,listID,desc,
    assignedTo,createdBy,createdDate,deadline,
    resolvedBy,resolvedDate,resolution,
    activateBy,activateDate,closedBy,closedDate';

$config->nc->datatable->defaultField = array('id', 'title', 'severity', 'status', 'type', 'auditplan', 'createdBy', 'createdDate', 'deadline', 'actions');
$config->nc->datatable->fieldList['id']['title']    = 'idAB';
$config->nc->datatable->fieldList['id']['fixed']    = 'left';
$config->nc->datatable->fieldList['id']['width']    = '70';
$config->nc->datatable->fieldList['id']['required'] = 'yes';

$config->nc->datatable->fieldList['title']['title']    = 'title';
$config->nc->datatable->fieldList['title']['fixed']    = 'no';
$config->nc->datatable->fieldList['title']['width']    = 'auto';
$config->nc->datatable->fieldList['title']['required'] = 'yes';

$config->nc->datatable->fieldList['severity']['title']    = 'severity';
$config->nc->datatable->fieldList['severity']['fixed']    = 'no';
$config->nc->datatable->fieldList['severity']['width']    = '80';
$config->nc->datatable->fieldList['severity']['required'] = 'no';

$config->nc->datatable->fieldList['status']['title']    = 'status';
$config->nc->datatable->fieldList['status']['fixed']    = 'no';
$config->nc->datatable->fieldList['status']['width']    = '100';
$config->nc->datatable->fieldList['status']['required'] = 'no';

$config->nc->datatable->fieldList['type']['title']    = 'type';
$config->nc->datatable->fieldList['type']['fixed']    = 'no';
$config->nc->datatable->fieldList['type']['width']    = '100';
$config->nc->datatable->fieldList['type']['required'] = 'no';

$config->nc->datatable->fieldList['auditplan']['title']    = 'object';
$config->nc->datatable->fieldList['auditplan']['fixed']    = 'no';
$config->nc->datatable->fieldList['auditplan']['width']    = '150';
$config->nc->datatable->fieldList['auditplan']['required'] = 'no';

$config->nc->datatable->fieldList['createdBy']['title']    = 'createdBy';
$config->nc->datatable->fieldList['createdBy']['fixed']    = 'no';
$config->nc->datatable->fieldList['createdBy']['width']    = '120';
$config->nc->datatable->fieldList['createdBy']['required'] = 'no';

$config->nc->datatable->fieldList['createdDate']['title']    = 'createdDate';
$config->nc->datatable->fieldList['createdDate']['fixed']    = 'no';
$config->nc->datatable->fieldList['createdDate']['width']    = '120';
$config->nc->datatable->fieldList['createdDate']['required'] = 'no';

$config->nc->datatable->fieldList['assignedTo']['title']    = 'assignedTo';
$config->nc->datatable->fieldList['assignedTo']['fixed']    = 'no';
$config->nc->datatable->fieldList['assignedTo']['width']    = '120';
$config->nc->datatable->fieldList['assignedTo']['required'] = 'no';

$config->nc->datatable->fieldList['deadline']['title']    = 'deadline';
$config->nc->datatable->fieldList['deadline']['fixed']    = 'no';
$config->nc->datatable->fieldList['deadline']['width']    = '120';
$config->nc->datatable->fieldList['deadline']['required'] = 'no';

$config->nc->datatable->fieldList['resolution']['title']    = 'resolution';
$config->nc->datatable->fieldList['resolution']['fixed']    = 'no';
$config->nc->datatable->fieldList['resolution']['width']    = '100';
$config->nc->datatable->fieldList['resolution']['required'] = 'no';

$config->nc->datatable->fieldList['resolvedBy']['title']    = 'resolvedBy';
$config->nc->datatable->fieldList['resolvedBy']['fixed']    = 'no';
$config->nc->datatable->fieldList['resolvedBy']['width']    = '120';
$config->nc->datatable->fieldList['resolvedBy']['required'] = 'no';

$config->nc->datatable->fieldList['resolvedDate']['title']    = 'resolvedDate';
$config->nc->datatable->fieldList['resolvedDate']['fixed']    = 'no';
$config->nc->datatable->fieldList['resolvedDate']['width']    = '120';
$config->nc->datatable->fieldList['resolvedDate']['required'] = 'no';

$config->nc->datatable->fieldList['closedBy']['title']    = 'closedBy';
$config->nc->datatable->fieldList['closedBy']['fixed']    = 'no';
$config->nc->datatable->fieldList['closedBy']['width']    = '120';
$config->nc->datatable->fieldList['closedBy']['required'] = 'no';

$config->nc->datatable->fieldList['closedDate']['title']    = 'closedDate';
$config->nc->datatable->fieldList['closedDate']['fixed']    = 'no';
$config->nc->datatable->fieldList['closedDate']['width']    = '120';
$config->nc->datatable->fieldList['closedDate']['required'] = 'no';

$config->nc->datatable->fieldList['actions']['title']    = 'actions';
$config->nc->datatable->fieldList['actions']['fixed']    = 'right';
$config->nc->datatable->fieldList['actions']['width']    = '140';
$config->nc->datatable->fieldList['actions']['required'] = 'yes';


global $lang;
$config->nc->search['onMenuBar']              = 'yes';
$config->nc->search['module']                 = 'nc';
$config->nc->search['fields']['title']        = $lang->nc->title;
$config->nc->search['fields']['id']           = $lang->nc->id;
$config->nc->search['fields']['status']       = $lang->nc->status;
$config->nc->search['fields']['severity']     = $lang->nc->severity;
$config->nc->search['fields']['assignedTo']   = $lang->nc->assignedTo;
$config->nc->search['fields']['execution']    = $lang->nc->execution;
$config->nc->search['fields']['auditplan']    = $lang->nc->auditplan;
$config->nc->search['fields']['listID']       = $lang->nc->listID;
$config->nc->search['fields']['type']         = $lang->nc->type;
$config->nc->search['fields']['deadline']     = $lang->nc->deadline;
$config->nc->search['fields']['desc']         = $lang->nc->desc;
$config->nc->search['fields']['createdBy']    = $lang->nc->createdBy;
$config->nc->search['fields']['createdDate']  = $lang->nc->createdDate;
$config->nc->search['fields']['resolvedBy']   = $lang->nc->resolvedBy;
$config->nc->search['fields']['resolution']   = $lang->nc->resolution;
$config->nc->search['fields']['resolvedDate'] = $lang->nc->resolvedDate;
$config->nc->search['fields']['closedBy']     = $lang->nc->closedBy;
$config->nc->search['fields']['closedDate']   = $lang->nc->closedDate;

$config->nc->search['params']['title']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->nc->search['params']['id']           = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->nc->search['params']['status']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->nc->search['params']['severity']     = array('operator' => '=',       'control' => 'select', 'values' => $lang->nc->severityList);
$config->nc->search['params']['assignedTo']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->nc->search['params']['execution']    = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->nc->search['params']['auditplan']    = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->nc->search['params']['listID']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->nc->search['params']['type']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->nc->typeList);
$config->nc->search['params']['deadline']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->nc->search['params']['desc']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->nc->search['params']['createdBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->nc->search['params']['createdDate']  = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->nc->search['params']['resolvedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->nc->search['params']['resolution']   = array('operator' => '=',       'control' => 'select', 'values' => $lang->nc->resolutionList);
$config->nc->search['params']['resolvedDate'] = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->nc->search['params']['closedBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->nc->search['params']['closedDate']   = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
