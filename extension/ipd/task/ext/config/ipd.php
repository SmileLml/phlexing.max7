<?php
$config->task->actionList['confirmDemandRetract']['icon']        = 'search';
$config->task->actionList['confirmDemandRetract']['text']        = $lang->task->confirmDemandRetract;
$config->task->actionList['confirmDemandRetract']['hint']        = $lang->task->confirmDemandRetract;
$config->task->actionList['confirmDemandRetract']['url']         = array('module' => 'task', 'method' => 'confirmDemandRetract', 'params'=> 'objectID={id}&object=task&extra={confirmeObjectID}');
$config->task->actionList['confirmDemandRetract']['data-toggle'] = 'modal';

$config->task->actionList['confirmDemandUnlink']['icon']        = 'search';
$config->task->actionList['confirmDemandUnlink']['text']        = $lang->task->confirmDemandUnlink;
$config->task->actionList['confirmDemandUnlink']['hint']        = $lang->task->confirmDemandUnlink;
$config->task->actionList['confirmDemandUnlink']['url']         = array('module' => 'task', 'method' => 'confirmDemandUnlink', 'params' => 'objectID={id}&object=task&extra={confirmeObjectID}');
$config->task->actionList['confirmDemandUnlink']['data-toggle'] = 'modal';

$config->task->dtable->fieldList['actions']['list']    = $config->task->actionList;
$config->task->dtable->fieldList['actions']['menu'][0] = array('confirmDemandRetract|confirmDemandUnlink|confirmStoryChange');
