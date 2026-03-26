<?php
$config->action->objectNameFields['demandpool']     = 'name';
$config->action->objectNameFields['demand']         = 'title';
$config->action->objectNameFields['charter']        = 'name';
$config->action->objectNameFields['roadmap']        = 'name';
$config->action->objectNameFields['market']         = 'name';
$config->action->objectNameFields['marketreport']   = 'name';
$config->action->objectNameFields['marketresearch'] = 'name';
$config->action->objectNameFields['researchstage']  = 'name';

$config->action->noLinkModules = $config->action->noLinkModules . 'researchstage,';
