<?php
global $app;
$app->loadLang('effort');
$config->story->actionList['createeffort']['icon']        = 'time';
$config->story->actionList['createeffort']['text']        = $lang->effort->common;
$config->story->actionList['createeffort']['hint']        = $lang->effort->common;
$config->story->actionList['createeffort']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'type=story&storyID={id}');
$config->story->actionList['createeffort']['data-toggle'] = 'modal';

array_unshift($config->story->actions->view['mainActions'], 'createeffort');
