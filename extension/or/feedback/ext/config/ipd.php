<?php
$config->feedback->actionList['toDemand']['icon']        = 'lightbulb';
$config->feedback->actionList['toDemand']['hint']        = $lang->feedback->toDemand;
$config->feedback->actionList['toDemand']['url']         = array('module' => 'feedback', 'method' => 'toDemand', 'params' => 'poolID=0&demandID=0&extra=fromType=feedback,fromID={id}');
$config->feedback->actionList['toDemand']['data-toggle'] = 'modal';
$config->feedback->actionList['toDemand']['data-size']   = 'lg';

$config->feedback->dtable->fieldList['actions']['list'] = $config->feedback->actionList;
$config->feedback->dtable->fieldList['actions']['menu'] = array('edit', 'review', 'reply', 'toDemand', 'toUserStory', 'toTodo', 'close', 'delete');
