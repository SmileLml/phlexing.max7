<?php
$config->action->objectNameFields['dimension']  = 'name';
$config->action->objectNameFields['chart']      = 'name';
$config->action->objectNameFields['pivot']      = 'name';
$config->action->objectNameFields['screen']     = 'name';
$config->action->objectNameFields['dashboard']  = 'name';
$config->action->objectNameFields['chartgroup'] = 'name';

$config->action->noLinkModules .= 'chart,pivot,screen,dimension,dashboard,chartgroup,';
