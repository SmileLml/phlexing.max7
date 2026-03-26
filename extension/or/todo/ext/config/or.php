<?php
$config->todo->moduleList[] = 'demand';

$config->todo->getUserObjectsMethod['demand'] = 'ajaxGetUserdemands';

$config->todo->project['demand'] = TABLE_DEMAND;

$config->todo->create->form['demand'] = array('required' => false, 'type' => 'string', 'default' => 0);
