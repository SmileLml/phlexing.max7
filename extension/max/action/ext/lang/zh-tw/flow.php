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
$app->loadLang('workflowreport', 'flow');

$lang->action->objectTypes['workflow']           = $lang->workflow->common;
$lang->action->objectTypes['workflowgroup']      = $lang->workflowgroup->common;
$lang->action->objectTypes['workflowaction']     = $lang->workflowaction->common;
$lang->action->objectTypes['workflowfield']      = $lang->workflowfield->common;
$lang->action->objectTypes['workflowdatasource'] = $lang->workflowdatasource->common;
$lang->action->objectTypes['workflowhook']       = $lang->workflowhook->common;
$lang->action->objectTypes['workflowlabel']      = $lang->workflowlabel->common;
$lang->action->objectTypes['workflowlayout']     = $lang->workflowlayout->common;
$lang->action->objectTypes['workflowrule']       = $lang->workflowrule->common;
$lang->action->objectTypes['workflowreport']     = $lang->workflowreport->common;

$lang->action->label->workflow           = $lang->workflow->common           . '|workflow|browseFlow|';
$lang->action->label->workflowdatasource = $lang->workflowdatasource->common . '|workflowdatasource|browse|';
$lang->action->label->workflowrule       = $lang->workflowrule->common       . '|workflowrule|view|id=%s';

/* Workflow */
$lang->action->desc->workflowaction   = '$date, 由 <strong>$actor</strong> %s。' . "\n";
$lang->action->desc->userdefined      = '$date, 由 <strong>$actor</strong> $extra。' . "\n";
$lang->action->desc->executehooks     = '$date, 由 <strong>$actor</strong> 執行擴展動作。' . "\n";
$lang->action->desc->executehooksfail = '$date, 由 <strong>$actor</strong> 執行擴展動作失敗，原因如下：' . "\n";
$lang->action->desc->linked           = '$date, 由 <strong>$actor</strong> 關聯 <strong>$extra</strong>。' . "\n";
$lang->action->desc->linkedto         = '$date, 由 <strong>$actor</strong> 關聯到 <strong>$extra</strong>。' . "\n";
$lang->action->desc->unlinked         = '$date, 由 <strong>$actor</strong> 移除 <strong>$extra</strong>。' . "\n";
$lang->action->desc->unlinkedfrom     = '$date, 由 <strong>$actor</strong> 從 <strong>$extra</strong> 移除。' . "\n";
