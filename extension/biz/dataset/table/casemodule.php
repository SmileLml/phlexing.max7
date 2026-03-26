<?php
$this->app->loadLang('tree');

$schema = new stdclass();

$schema->primaryTable = 'casemodule';

$schema->tables = array();
$schema->tables['casemodule'] = 'zt_module';

$schema->joins = array();

$schema->fields = array();
$schema->fields['id']   = array('type' => 'number', 'name' => $this->lang->dataset->id);
$schema->fields['name'] = array('type' => 'string', 'name' => $this->lang->tree->module);

$schema->objects = array();
