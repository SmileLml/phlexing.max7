<?php
$config->kanban->default->risk = new stdclass();
$config->kanban->default->risk->name  = $lang->risk->common;
$config->kanban->default->risk->color = '#FF0000';
$config->kanban->default->risk->order = '40';

$config->kanban->riskColumnStatusList = array();
$config->kanban->riskColumnStatusList['active']   = 'active';
$config->kanban->riskColumnStatusList['track']    = 'active';
$config->kanban->riskColumnStatusList['hangup']   = 'hangup';
$config->kanban->riskColumnStatusList['canceled'] = 'canceled';
$config->kanban->riskColumnStatusList['closed']   = 'closed';
