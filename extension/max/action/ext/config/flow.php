<?php

$config->action->objectNameFields['workflow']           = 'name';
$config->action->objectNameFields['workflowgroup']      = 'name';
$config->action->objectNameFields['workflowaction']     = 'name';
$config->action->objectNameFields['workflowdatasource'] = 'name';
$config->action->objectNameFields['workflowfield']      = 'name';
$config->action->objectNameFields['workflowlabel']      = 'label';
$config->action->objectNameFields['workflowrule']       = 'name';
$config->action->objectNameFields['workflowreport']     = 'name';

if(!isset($config->action->dynamic)) $config->action->dynamic = new stdclass();
if(!isset($config->action->dynamic->exclude)) $config->action->dynamic->exclude = new stdclass();
if(!isset($config->action->dynamic->exclude->modules)) $config->action->dynamic->exclude->modules = array();
$config->action->dynamic->exclude->modules[] = 'workflowdatasource';
$config->action->dynamic->exclude->modules[] = 'workflowfield';
$config->action->dynamic->exclude->modules[] = 'workflowlabel';
$config->action->dynamic->exclude->modules[] = 'workflowlayout';
$config->action->dynamic->exclude->modules[] = 'workflowrule';

$config->action->noLinkModules .= ',workflowgroup,';
