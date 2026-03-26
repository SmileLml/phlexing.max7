<?php
global $lang, $app;
$app->loadLang('story');
$config->projectstory->search = array();
$config->projectstory->search['module'] = 'projectstory';
$config->projectstory->search['fields']['id']         = $lang->story->id;
$config->projectstory->search['fields']['title']      = $lang->story->title;
$config->projectstory->search['fields']['keywords']   = $lang->story->keywords;
$config->projectstory->search['fields']['pri']        = $lang->story->pri;
$config->projectstory->search['fields']['estimate']   = $lang->story->estimate;
$config->projectstory->search['fields']['openedBy']   = $lang->story->openedBy;
$config->projectstory->search['fields']['openedDate'] = $lang->story->openedDate;

$config->projectstory->search['params']['id']         = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->projectstory->search['params']['title']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->projectstory->search['params']['keywords']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->projectstory->search['params']['pri']        = array('operator' => '=',       'control' => 'select', 'values' => $this->lang->story->priList);
$config->projectstory->search['params']['estimate']   = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->projectstory->search['params']['openedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->projectstory->search['params']['openedDate'] = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');

$config->projectstory->dtable = new stdclass();
$config->projectstory->dtable->fieldList['id']['name']     = 'id';
$config->projectstory->dtable->fieldList['id']['title']    = $lang->idAB;
$config->projectstory->dtable->fieldList['id']['fixed']    = 'left';
$config->projectstory->dtable->fieldList['id']['required'] = true;
$config->projectstory->dtable->fieldList['id']['type']     = 'checkID';
$config->projectstory->dtable->fieldList['id']['checkbox'] = true;
$config->projectstory->dtable->fieldList['id']['show']     = true;
$config->projectstory->dtable->fieldList['id']['sortType'] = true;
$config->projectstory->dtable->fieldList['id']['group']    = 1;

$config->projectstory->dtable->fieldList['title']['name']         = 'title';
$config->projectstory->dtable->fieldList['title']['title']        = $lang->story->name;
$config->projectstory->dtable->fieldList['title']['type']         = 'title';
$config->projectstory->dtable->fieldList['title']['link']         = array('url' => helper::createLink('story', 'storyView', 'storyID={id}'));
$config->projectstory->dtable->fieldList['title']['fixed']        = 'left';
$config->projectstory->dtable->fieldList['title']['data-toggle']  = 'modal';
$config->projectstory->dtable->fieldList['title']['sortType']     = true;
$config->projectstory->dtable->fieldList['title']['minWidth']     = '342';
$config->projectstory->dtable->fieldList['title']['required']     = true;
$config->projectstory->dtable->fieldList['title']['nestedToggle'] = true;
$config->projectstory->dtable->fieldList['title']['show']         = true;
$config->projectstory->dtable->fieldList['title']['group']        = 1;
$config->projectstory->dtable->fieldList['title']['data-app']     = $app->tab;
$config->projectstory->dtable->fieldList['title']['styleMap']     = array('--color-link' => 'color');

$config->projectstory->dtable->fieldList['pri']['name']     = 'pri';
$config->projectstory->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->projectstory->dtable->fieldList['pri']['sortType'] = true;
$config->projectstory->dtable->fieldList['pri']['type']     = 'pri';
$config->projectstory->dtable->fieldList['pri']['show']     = true;
$config->projectstory->dtable->fieldList['pri']['group']    = 2;

$config->projectstory->dtable->fieldList['estimate']['name']     = 'estimate';
$config->projectstory->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->projectstory->dtable->fieldList['estimate']['sortType'] = true;
$config->projectstory->dtable->fieldList['estimate']['type']     = 'number';
$config->projectstory->dtable->fieldList['estimate']['show']     = true;
$config->projectstory->dtable->fieldList['estimate']['group']    = 2;

$config->projectstory->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->projectstory->dtable->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->projectstory->dtable->fieldList['openedBy']['sortType'] = true;
$config->projectstory->dtable->fieldList['openedBy']['type']     = 'user';
$config->projectstory->dtable->fieldList['openedBy']['show']     = true;
$config->projectstory->dtable->fieldList['openedBy']['group']    = 3;

$config->projectstory->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->projectstory->dtable->fieldList['openedDate']['title']    = $lang->story->openedDate;
$config->projectstory->dtable->fieldList['openedDate']['sortType'] = true;
$config->projectstory->dtable->fieldList['openedDate']['type']     = 'date';
$config->projectstory->dtable->fieldList['openedDate']['group']    = 3;
