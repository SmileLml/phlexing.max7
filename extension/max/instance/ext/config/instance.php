<?php
$config->instance->actionList['terminal']['icon']      = 'remote';
$config->instance->actionList['terminal']['data-size'] = 'sm';
$config->instance->actionList['terminal']['text']      = $lang->instance->terminal;
$config->instance->actionList['terminal']['hint']      = $lang->instance->terminal;
$config->instance->actionList['terminal']['url']       = array('module' => 'instance', 'method' => 'terminal', 'params' => "id={id}");
$config->instance->actionList['terminal']['target']    = '_blank';

$config->instance->actions->view['mainActions'][] = 'terminal';
