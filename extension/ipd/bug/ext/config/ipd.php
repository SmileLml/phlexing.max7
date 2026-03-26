<?php
$config->bug->actionList['confirmDemandRetract']['icon']        = 'search';
$config->bug->actionList['confirmDemandRetract']['text']        = $lang->bug->confirmDemandRetract;
$config->bug->actionList['confirmDemandRetract']['hint']        = $lang->bug->confirmDemandRetract;
$config->bug->actionList['confirmDemandRetract']['url']         = array('module' => 'bug', 'method' => 'confirmDemandRetract', 'params'=> 'objectID={id}&object=bug&extra={confirmeObjectID}');
$config->bug->actionList['confirmDemandRetract']['data-toggle'] = 'modal';

$config->bug->actionList['confirmDemandUnlink']['icon']        = 'search';
$config->bug->actionList['confirmDemandUnlink']['text']        = $lang->bug->confirmDemandUnlink;
$config->bug->actionList['confirmDemandUnlink']['hint']        = $lang->bug->confirmDemandUnlink;
$config->bug->actionList['confirmDemandUnlink']['url']         = array('module' => 'bug', 'method' => 'confirmDemandUnlink', 'params' => 'objectID={id}&object=bug&extra={confirmeObjectID}');
$config->bug->actionList['confirmDemandUnlink']['data-toggle'] = 'modal';

$config->bug->dtable->fieldList['actions']['list'] = $config->bug->actionList;
$config->bug->dtable->fieldList['actions']['menu'] = array(array('confirmDemandRetract|confirmDemandUnlink'), array('confirm', 'resolve', 'close|activate', 'edit', 'copy'));
