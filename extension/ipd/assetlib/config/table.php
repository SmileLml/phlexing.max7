<?php
global $lang, $app;
$app->loadLang('story');
$config->assetlib->dtable = new stdclass();
$config->assetlib->dtable->story       = new stdclass();
$config->assetlib->dtable->importStory = new stdclass();

$config->assetlib->dtable->story->fieldList['id']['name']     = 'id';
$config->assetlib->dtable->story->fieldList['id']['title']    = $lang->idAB;
$config->assetlib->dtable->story->fieldList['id']['fixed']    = 'left';
$config->assetlib->dtable->story->fieldList['id']['required'] = true;
$config->assetlib->dtable->story->fieldList['id']['type']     = 'checkID';
$config->assetlib->dtable->story->fieldList['id']['checkbox'] = true;
$config->assetlib->dtable->story->fieldList['id']['sortType'] = true;
$config->assetlib->dtable->story->fieldList['id']['group']    = 1;

$config->assetlib->dtable->story->fieldList['title']['name']         = 'title';
$config->assetlib->dtable->story->fieldList['title']['title']        = $lang->story->title;
$config->assetlib->dtable->story->fieldList['title']['type']         = 'title';
$config->assetlib->dtable->story->fieldList['title']['link']         = array('url' => helper::createLink('assetlib', 'storyView', 'storyID={id}'));
$config->assetlib->dtable->story->fieldList['title']['fixed']        = 'left';
$config->assetlib->dtable->story->fieldList['title']['sortType']     = true;
$config->assetlib->dtable->story->fieldList['title']['minWidth']     = '342';
$config->assetlib->dtable->story->fieldList['title']['required']     = true;
$config->assetlib->dtable->story->fieldList['title']['nestedToggle'] = true;
$config->assetlib->dtable->story->fieldList['title']['group']        = 1;
$config->assetlib->dtable->story->fieldList['title']['data-app']     = $app->tab;
$config->assetlib->dtable->story->fieldList['title']['styleMap']     = array('--color-link' => 'color');

$config->assetlib->dtable->story->fieldList['pri']['name']     = 'pri';
$config->assetlib->dtable->story->fieldList['pri']['title']    = $lang->priAB;
$config->assetlib->dtable->story->fieldList['pri']['fixed']    = 'left';
$config->assetlib->dtable->story->fieldList['pri']['sortType'] = true;
$config->assetlib->dtable->story->fieldList['pri']['type']     = 'pri';
$config->assetlib->dtable->story->fieldList['pri']['group']    = 2;

$config->assetlib->dtable->story->fieldList['status']['name']      = 'status';
$config->assetlib->dtable->story->fieldList['status']['title']     = $lang->statusAB;
$config->assetlib->dtable->story->fieldList['status']['sortType']  = true;
$config->assetlib->dtable->story->fieldList['status']['type']      = 'status';
$config->assetlib->dtable->story->fieldList['status']['group']     = 3;
$config->assetlib->dtable->story->fieldList['status']['statusMap'] = $lang->assetlib->statusList;

$config->assetlib->dtable->story->fieldList['openedBy']['name']     = 'openedBy';
$config->assetlib->dtable->story->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->assetlib->dtable->story->fieldList['openedBy']['sortType'] = true;
$config->assetlib->dtable->story->fieldList['openedBy']['type']     = 'user';
$config->assetlib->dtable->story->fieldList['openedBy']['group']    = 5;

$config->assetlib->dtable->story->fieldList['openedDate']['name']     = 'openedDate';
$config->assetlib->dtable->story->fieldList['openedDate']['title']    = $lang->story->openedDate;
$config->assetlib->dtable->story->fieldList['openedDate']['sortType'] = true;
$config->assetlib->dtable->story->fieldList['openedDate']['type']     = 'date';
$config->assetlib->dtable->story->fieldList['openedDate']['group']    = 5;

$config->assetlib->dtable->story->fieldList['estimate']['name']     = 'estimate';
$config->assetlib->dtable->story->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->assetlib->dtable->story->fieldList['estimate']['sortType'] = true;
$config->assetlib->dtable->story->fieldList['estimate']['type']     = 'number';
$config->assetlib->dtable->story->fieldList['estimate']['group']    = 5;

$config->assetlib->dtable->story->fieldList['assignedTo']['name']     = 'assignedTo';
$config->assetlib->dtable->story->fieldList['assignedTo']['title']    = $lang->assetlib->approved;
$config->assetlib->dtable->story->fieldList['assignedTo']['width']    = '100';
$config->assetlib->dtable->story->fieldList['assignedTo']['type']     = 'user';
$config->assetlib->dtable->story->fieldList['assignedTo']['sortType'] = false;
$config->assetlib->dtable->story->fieldList['assignedTo']['group']    = 5;

$config->assetlib->dtable->story->fieldList['approvedDate']['name']     = 'approvedDate';
$config->assetlib->dtable->story->fieldList['approvedDate']['title']    = $lang->assetlib->approvedDate;
$config->assetlib->dtable->story->fieldList['approvedDate']['sortType'] = true;
$config->assetlib->dtable->story->fieldList['approvedDate']['type']     = 'date';
$config->assetlib->dtable->story->fieldList['approvedDate']['group']    = 5;

$config->assetlib->dtable->story->fieldList['actions']['name']     = 'actions';
$config->assetlib->dtable->story->fieldList['actions']['title']    = $lang->actions;
$config->assetlib->dtable->story->fieldList['actions']['fixed']    = 'right';
$config->assetlib->dtable->story->fieldList['actions']['required'] = true;
$config->assetlib->dtable->story->fieldList['actions']['width']    = 'auto';
$config->assetlib->dtable->story->fieldList['actions']['minWidth'] = 120;
$config->assetlib->dtable->story->fieldList['actions']['type']     = 'actions';
$config->assetlib->dtable->story->fieldList['actions']['menu']     = array('editStory', 'approveStory', 'removeStory');
$config->assetlib->dtable->story->fieldList['actions']['list']     = $config->assetlib->story->actionList;

$config->assetlib->dtable->importStory->fieldList['id']['name']     = 'id';
$config->assetlib->dtable->importStory->fieldList['id']['title']    = $lang->idAB;
$config->assetlib->dtable->importStory->fieldList['id']['fixed']    = 'left';
$config->assetlib->dtable->importStory->fieldList['id']['required'] = true;
$config->assetlib->dtable->importStory->fieldList['id']['type']     = 'checkID';
$config->assetlib->dtable->importStory->fieldList['id']['checkbox'] = true;
$config->assetlib->dtable->importStory->fieldList['id']['sortType'] = true;
$config->assetlib->dtable->importStory->fieldList['id']['group']    = 1;

$config->assetlib->dtable->importStory->fieldList['title']['name']         = 'title';
$config->assetlib->dtable->importStory->fieldList['title']['title']        = $lang->story->title;
$config->assetlib->dtable->importStory->fieldList['title']['type']         = 'title';
$config->assetlib->dtable->importStory->fieldList['title']['link']         = array('module' => '{type}', 'method' => 'view', 'params' => 'storyID={id}');
$config->assetlib->dtable->importStory->fieldList['title']['fixed']        = 'left';
$config->assetlib->dtable->importStory->fieldList['title']['sortType']     = true;
$config->assetlib->dtable->importStory->fieldList['title']['minWidth']     = '342';
$config->assetlib->dtable->importStory->fieldList['title']['required']     = true;
$config->assetlib->dtable->importStory->fieldList['title']['nestedToggle'] = true;
$config->assetlib->dtable->importStory->fieldList['title']['group']        = 1;
$config->assetlib->dtable->importStory->fieldList['title']['data-toggle']  = 'modal';
$config->assetlib->dtable->importStory->fieldList['title']['data-size']    = 'lg';
$config->assetlib->dtable->importStory->fieldList['title']['styleMap']     = array('--color-link' => 'color');

$config->assetlib->dtable->importStory->fieldList['pri']['name']     = 'pri';
$config->assetlib->dtable->importStory->fieldList['pri']['title']    = $lang->priAB;
$config->assetlib->dtable->importStory->fieldList['pri']['fixed']    = 'left';
$config->assetlib->dtable->importStory->fieldList['pri']['sortType'] = true;
$config->assetlib->dtable->importStory->fieldList['pri']['type']     = 'pri';
$config->assetlib->dtable->importStory->fieldList['pri']['group']    = 2;

$config->assetlib->dtable->importStory->fieldList['status']['name']      = 'status';
$config->assetlib->dtable->importStory->fieldList['status']['title']     = $lang->statusAB;
$config->assetlib->dtable->importStory->fieldList['status']['sortType']  = true;
$config->assetlib->dtable->importStory->fieldList['status']['type']      = 'status';
$config->assetlib->dtable->importStory->fieldList['status']['group']     = 3;
$config->assetlib->dtable->importStory->fieldList['status']['statusMap'] = $lang->story->statusList;

$config->assetlib->dtable->importStory->fieldList['category']['name']      = 'category';
$config->assetlib->dtable->importStory->fieldList['category']['title']     = $lang->story->category;
$config->assetlib->dtable->importStory->fieldList['category']['sortType']  = true;
$config->assetlib->dtable->importStory->fieldList['category']['group']     = 3;
$config->assetlib->dtable->importStory->fieldList['category']['map']       = $lang->story->categoryList;

$config->assetlib->dtable->importStory->fieldList['plan']['name']     = 'plan';
$config->assetlib->dtable->importStory->fieldList['plan']['title']    = $lang->story->planAB;
$config->assetlib->dtable->importStory->fieldList['plan']['sortType'] = true;
$config->assetlib->dtable->importStory->fieldList['plan']['type']     = 'category';
$config->assetlib->dtable->importStory->fieldList['plan']['group']    = 4;

$config->assetlib->dtable->importStory->fieldList['openedBy']['name']     = 'openedBy';
$config->assetlib->dtable->importStory->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->assetlib->dtable->importStory->fieldList['openedBy']['sortType'] = true;
$config->assetlib->dtable->importStory->fieldList['openedBy']['type']     = 'user';
$config->assetlib->dtable->importStory->fieldList['openedBy']['group']    = 5;

$config->assetlib->dtable->importStory->fieldList['openedDate']['name']     = 'openedDate';
$config->assetlib->dtable->importStory->fieldList['openedDate']['title']    = $lang->story->openedDate;
$config->assetlib->dtable->importStory->fieldList['openedDate']['sortType'] = true;
$config->assetlib->dtable->importStory->fieldList['openedDate']['type']     = 'date';
$config->assetlib->dtable->importStory->fieldList['openedDate']['group']    = 5;

$config->assetlib->dtable->importStory->fieldList['estimate']['name']     = 'estimate';
$config->assetlib->dtable->importStory->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->assetlib->dtable->importStory->fieldList['estimate']['sortType'] = true;
$config->assetlib->dtable->importStory->fieldList['estimate']['type']     = 'number';
$config->assetlib->dtable->importStory->fieldList['estimate']['group']    = 5;
