<?php
$config->custom->notSetMethods[] = 'storyGrade';
$config->custom->notSetMethods[] = 'epicGrade';
$config->custom->notSetMethods[] = 'requirementGrade';

$config->custom->relateObjectList['demand']   = $lang->demand->common;
$config->custom->relateObjectFields['demand'] = array('id', 'relation', 'pri', 'title', 'pool', 'createdBy', 'assignedTo', 'status');

$config->custom->form->setCharterInfo['type'] = array('type' => 'string', 'required' => true);
