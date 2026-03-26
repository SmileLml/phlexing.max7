<?php
global $app;
$app->loadLang('effort');
$config->bug->actionList['createeffort']['icon']        = 'time';
$config->bug->actionList['createeffort']['text']        = $lang->effort->common;
$config->bug->actionList['createeffort']['hint']        = $lang->effort->common;
$config->bug->actionList['createeffort']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'type=bug&bugID={id}');
$config->bug->actionList['createeffort']['data-toggle'] = 'modal';

array_unshift($config->bug->actions->view['mainActions'], 'createeffort');
