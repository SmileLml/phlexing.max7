<?php
$config->my->bug->actionList['confirmDemandRetract']['icon']        = 'search';
$config->my->bug->actionList['confirmDemandRetract']['text']        = $lang->bug->confirmDemandRetract;
$config->my->bug->actionList['confirmDemandRetract']['hint']        = $lang->bug->confirmDemandRetract;
$config->my->bug->actionList['confirmDemandRetract']['url']         = array('module' => 'bug', 'method' => 'confirmDemandRetract', 'params'=> 'objectID={id}&object=bug&extra={confirmeObjectID}');
$config->my->bug->actionList['confirmDemandRetract']['data-toggle'] = 'modal';

$config->my->bug->actionList['confirmDemandUnlink']['icon']        = 'search';
$config->my->bug->actionList['confirmDemandUnlink']['text']        = $lang->bug->confirmDemandUnlink;
$config->my->bug->actionList['confirmDemandUnlink']['hint']        = $lang->bug->confirmDemandUnlink;
$config->my->bug->actionList['confirmDemandUnlink']['url']         = array('module' => 'bug', 'method' => 'confirmDemandUnlink', 'params' => 'objectID={id}&object=bug&extra={confirmeObjectID}');
$config->my->bug->actionList['confirmDemandUnlink']['data-toggle'] = 'modal';

$config->my->bug->dtable->fieldList['actions']['list'] = $config->bug->actionList;
$config->my->bug->dtable->fieldList['actions']['menu'] = array(array('confirmDemandRetract|confirmDemandUnlink'), array('confirm', 'resolve', 'close|activate', 'edit', 'copy'));
