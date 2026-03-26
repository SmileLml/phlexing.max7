<?php
global $app;
$app->loadLang('workflow', 'flow');
$app->loadLang('workflowaction', 'flow');
$app->loadLang('workflowfield', 'flow');
$app->loadLang('workflowdatasource', 'flow');
$app->loadLang('workflowhook', 'flow');
$app->loadLang('workflowlabel', 'flow');
$app->loadLang('workflowlayout', 'flow');
$app->loadLang('workflowrule', 'flow');

$lang->action->objectTypes['workflow']           = $lang->workflow->common;
$lang->action->objectTypes['workflowaction']     = $lang->workflowaction->common;
$lang->action->objectTypes['workflowfield']      = $lang->workflowfield->common;
$lang->action->objectTypes['workflowdatasource'] = $lang->workflowdatasource->common;
$lang->action->objectTypes['workflowhook']       = $lang->workflowhook->common;
$lang->action->objectTypes['workflowlabel']      = $lang->workflowlabel->common;
$lang->action->objectTypes['workflowlayout']     = $lang->workflowlayout->common;
$lang->action->objectTypes['workflowrule']       = $lang->workflowrule->common;

$lang->action->label->workflow           = $lang->workflow->common           . '|workflow|browse|';
$lang->action->label->workflowaction     = $lang->workflowaction->common     . '|workflowaction|browse|module=%s';
$lang->action->label->workflowfield      = $lang->workflowfield->common      . '|workflowfield|browse|module=%s';
$lang->action->label->workflowdatasource = $lang->workflowdatasource->common . '|workflowdatasource|browse|';
$lang->action->label->workflowhook       = $lang->workflowhook->common       . '|workflowhook|browse|module=%s';
$lang->action->label->workflowlabel      = $lang->workflowlabel->common      . '|workflowlabel|browse|module=%s';
$lang->action->label->workflowlayout     = $lang->workflowlayout->common     . '|workflowlayout|admin|module=%s';
$lang->action->label->workflowrule       = $lang->workflowrule->common       . '|workflowrule|view|id=%s';

/* Workflow */
$lang->action->desc->workflowAction = '$date, %s by <strong>$actor</strong> .' . "\n";
$lang->action->desc->userdefined    = '$date, <strong>$actor</strong> $extra.' . "\n";
$lang->action->desc->executehooks   = '$date, executed hooks by <strong>$actor</strong>.' . "\n";
$lang->action->desc->linked         = '$date, linked <strong>$extra</strong> bởi <strong>$actor</strong>。' . "\n";
$lang->action->desc->linkedto       = '$date, liên kết tới <strong>$extra</strong> bởi <strong>$actor</strong>。' . "\n";
$lang->action->desc->unlinked       = '$date, removed <strong>$extra</strong> bởi <strong>$actor</strong>。' . "\n";
$lang->action->desc->unlinkedfrom   = '$date, removed from <strong>$extra</strong> bởi <strong>$actor</strong>。' . "\n";
