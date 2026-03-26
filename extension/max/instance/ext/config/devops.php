<?php
global $lang, $app;
if(!isset($config->instance->dtable)) $config->instance->dtable = new stdclass();

$config->instance->dtable->custom = new stdclass();

$config->instance->dtable->custom->fieldList = array();
$config->instance->dtable->custom->fieldList['name']['title'] = $lang->instance->custom->name;
$config->instance->dtable->custom->fieldList['name']['name']  = strpos($app->clientLang, 'zh') === false ? 'name' : 'label';
$config->instance->dtable->custom->fieldList['name']['group'] = '1';

$config->instance->dtable->custom->fieldList['value']['title']   = $lang->instance->custom->value;
$config->instance->dtable->custom->fieldList['value']['type']    = common::hasPriv('instance', 'manage') ? 'control' : 'text';
$config->instance->dtable->custom->fieldList['value']['control'] = 'input';
$config->instance->dtable->custom->fieldList['value']['group']   = '2';

$config->instance->dtable->custom->fieldList['desc']['title'] = $lang->instance->custom->desc;
$config->instance->dtable->custom->fieldList['desc']['group'] = '3';
