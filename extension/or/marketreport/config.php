<?php
$config->marketreport = new stdclass();
$config->marketreport->create = new stdclass();
$config->marketreport->edit   = new stdclass();

$config->marketreport->create->requiredFields = 'name';
$config->marketreport->edit->requiredFields   = $config->marketreport->create->requiredFields;

$config->marketreport->editor = new stdclass();
$config->marketreport->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->marketreport->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->marketreport->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

$config->marketreport->datatable = new stdclass();
$config->marketreport->datatable->defaultField = array('id', 'name', 'status', 'owner', 'market', 'research', 'openedBy', 'openedDate', 'actions');

$config->marketreport->datatable->fieldList['id']['title']    = 'idAB';
$config->marketreport->datatable->fieldList['id']['fixed']    = 'left';
$config->marketreport->datatable->fieldList['id']['width']    = '70';
$config->marketreport->datatable->fieldList['id']['required'] = 'yes';

$config->marketreport->datatable->fieldList['name']['title']    = 'name';
$config->marketreport->datatable->fieldList['name']['fixed']    = 'left';
$config->marketreport->datatable->fieldList['name']['width']    = 'auto';
$config->marketreport->datatable->fieldList['name']['required'] = 'yes';

$config->marketreport->datatable->fieldList['status']['title']    = 'status';
$config->marketreport->datatable->fieldList['status']['fixed']    = 'no';
$config->marketreport->datatable->fieldList['status']['width']    = '60';
$config->marketreport->datatable->fieldList['status']['required'] = 'no';

$config->marketreport->datatable->fieldList['owner']['title']      = 'owner';
$config->marketreport->datatable->fieldList['owner']['fixed']      = 'no';
$config->marketreport->datatable->fieldList['owner']['width']      = '90';
$config->marketreport->datatable->fieldList['owner']['required']   = 'no';

$config->marketreport->datatable->fieldList['market']['title']    = 'market';
$config->marketreport->datatable->fieldList['market']['fixed']    = 'no';
$config->marketreport->datatable->fieldList['market']['width']    = '140';
$config->marketreport->datatable->fieldList['market']['required'] = 'no';

$config->marketreport->datatable->fieldList['research']['title']    = 'research';
$config->marketreport->datatable->fieldList['research']['fixed']    = 'no';
$config->marketreport->datatable->fieldList['research']['width']    = '140';
$config->marketreport->datatable->fieldList['research']['required'] = 'no';

$config->marketreport->datatable->fieldList['source']['title']    = 'source';
$config->marketreport->datatable->fieldList['source']['fixed']    = 'no';
$config->marketreport->datatable->fieldList['source']['width']    = '100';
$config->marketreport->datatable->fieldList['source']['required'] = 'no';

$config->marketreport->datatable->fieldList['openedBy']['title']    = 'openedByAB';
$config->marketreport->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->marketreport->datatable->fieldList['openedBy']['width']    = '90';
$config->marketreport->datatable->fieldList['openedBy']['required'] = 'no';

$config->marketreport->datatable->fieldList['openedDate']['title']    = 'openedDate';
$config->marketreport->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->marketreport->datatable->fieldList['openedDate']['width']    = '100';
$config->marketreport->datatable->fieldList['openedDate']['required'] = 'no';

$config->marketreport->datatable->fieldList['lastEditedBy']['title']    = 'lastEditedBy';
$config->marketreport->datatable->fieldList['lastEditedBy']['fixed']    = 'no';
$config->marketreport->datatable->fieldList['lastEditedBy']['width']    = '95';
$config->marketreport->datatable->fieldList['lastEditedBy']['required'] = 'no';

$config->marketreport->datatable->fieldList['lastEditedDate']['title']    = 'lastEditedDate';
$config->marketreport->datatable->fieldList['lastEditedDate']['fixed']    = 'no';
$config->marketreport->datatable->fieldList['lastEditedDate']['width']    = '100';
$config->marketreport->datatable->fieldList['lastEditedDate']['required'] = 'no';

$config->marketreport->datatable->fieldList['actions']['title']    = 'actions';
$config->marketreport->datatable->fieldList['actions']['fixed']    = 'right';
$config->marketreport->datatable->fieldList['actions']['width']    = '120';
$config->marketreport->datatable->fieldList['actions']['required'] = 'yes';
