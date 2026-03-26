<?php
$config->block->modules['common']->moreLinkList->dynamic = 'my|dynamic|';

global $app;
$app->loadLang('marketresearch');
$config->block->task->dtable->fieldList['name']['title']    = $lang->researchtask->name;
$config->block->task->dtable->fieldList['name']['link']     = array('module' => 'researchtask', 'method' => 'view', 'params' => 'taskID={id}');
$config->block->task->dtable->fieldList['name']['data-app'] = 'market';
