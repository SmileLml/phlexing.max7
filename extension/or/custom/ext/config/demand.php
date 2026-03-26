<?php
$config->custom->canAdd['demand']  = 'priList,categoryList,sourceList,reasonList,durationList,bsaList';
$config->custom->canAdd['charter'] = 'charterList';
$config->custom->canAdd['market']  = 'strategyList,maturityList,competitionList,speedList,ppmList';

$config->custom->requiredModules[100] = 'demand';
$config->custom->requiredModules[105] = 'charter';

$config->custom->fieldList['demand']['create'] = 'source,pri,spec,verify';
$config->custom->fieldList['demand']['change'] = 'spec,verify,comment';
$config->custom->fieldList['demand']['close']  = 'comment';
$config->custom->fieldList['demand']['review'] = 'comment,reviewedDate';

$config->custom->customFields['demand'] = array('custom' => array('createFields', 'batchCreateFields'));

$config->custom->relateObjectList['demand']   = $lang->demand->common;
$config->custom->relateObjectFields['demand'] = array('id', 'pri', 'title', 'relation', 'pool', 'createdBy', 'assignedTo', 'status');
