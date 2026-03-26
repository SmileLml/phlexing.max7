<?php
global $lang, $app;

$app->loadLang('story');
$config->charter->dtable = new stdclass();
$config->charter->dtable->fieldList = array();
$config->charter->dtable->fieldList['id']['name']  = 'id';
$config->charter->dtable->fieldList['id']['title'] = $lang->charter->idAB;
$config->charter->dtable->fieldList['id']['type']  = 'id';

$config->charter->dtable->fieldList['name']['name']  = 'name';
$config->charter->dtable->fieldList['name']['fixed'] = 'left';
$config->charter->dtable->fieldList['name']['type']  = 'title';
$config->charter->dtable->fieldList['name']['link']  = array('module' => 'charter', 'method' => 'view', 'params' => "charterID={id}");

$config->charter->dtable->fieldList['level']['name']  = 'level';
$config->charter->dtable->fieldList['level']['title'] = $lang->charter->level;
$config->charter->dtable->fieldList['level']['type']  = 'pri';
$config->charter->dtable->fieldList['level']['show']  = true;

$config->charter->dtable->fieldList['status']['name']      = 'status';
$config->charter->dtable->fieldList['status']['type']      = 'status';
$config->charter->dtable->fieldList['status']['statusMap'] = $lang->charter->statusList;
$config->charter->dtable->fieldList['status']['show']      = true;

$config->charter->dtable->fieldList['category']['name'] = 'category';
$config->charter->dtable->fieldList['category']['type'] = 'category';
$config->charter->dtable->fieldList['category']['map']  = $lang->charter->categoryList;
$config->charter->dtable->fieldList['category']['show'] = true;

$config->charter->dtable->fieldList['market']['name'] = 'market';
$config->charter->dtable->fieldList['market']['type'] = 'category';
$config->charter->dtable->fieldList['market']['map']  = $lang->charter->marketList;
$config->charter->dtable->fieldList['market']['show'] = true;

$config->charter->dtable->fieldList['budget']['name'] = 'budget';
$config->charter->dtable->fieldList['budget']['type'] = 'money';
$config->charter->dtable->fieldList['budget']['show'] = true;

$config->charter->dtable->fieldList['reviewStatusAB']['name']        = 'reviewStatusAB';
$config->charter->dtable->fieldList['reviewStatusAB']['title']       = 'reviewStatus';
$config->charter->dtable->fieldList['reviewStatusAB']['type']        = 'category';
$config->charter->dtable->fieldList['reviewStatusAB']['map']         = $lang->charter->reviewStatusList;
$config->charter->dtable->fieldList['reviewStatusAB']['link']        = array('module' => 'charter', 'method' => 'approvalProgress', 'params' => 'approvalID={approval}');
$config->charter->dtable->fieldList['reviewStatusAB']['data-toggle'] = 'modal';
$config->charter->dtable->fieldList['reviewStatusAB']['show']        = true;

$config->charter->dtable->fieldList['appliedBy']['name']  = 'appliedBy';
$config->charter->dtable->fieldList['appliedBy']['title'] = $lang->charter->abbr->appliedBy;
$config->charter->dtable->fieldList['appliedBy']['type']  = 'user';
$config->charter->dtable->fieldList['appliedBy']['show']  = true;

$config->charter->dtable->fieldList['appliedDate']['name']  = 'appliedDate';
$config->charter->dtable->fieldList['appliedDate']['title'] = $lang->charter->abbr->appliedDate;
$config->charter->dtable->fieldList['appliedDate']['type']  = 'date';
$config->charter->dtable->fieldList['appliedDate']['show']  = true;

$config->charter->dtable->fieldList['actions']['name']     = 'actions';
$config->charter->dtable->fieldList['actions']['title']    = $lang->actions;
$config->charter->dtable->fieldList['actions']['type']     = 'actions';
$config->charter->dtable->fieldList['actions']['sortType'] = false;
$config->charter->dtable->fieldList['actions']['width']    = '';
$config->charter->dtable->fieldList['actions']['list']     = $config->charter->actionList;
$config->charter->dtable->fieldList['actions']['menu']     = array('projectApproval|completionApproval|activateProjectApproval', 'approvalCancel', 'review', 'close', 'edit');

$config->charter->roadmapStory = new stdclass();
$config->charter->roadmapStory->fieldList = array();
$config->charter->roadmapStory->fieldList['id']['name']     = 'id';
$config->charter->roadmapStory->fieldList['id']['type']     = 'id';
$config->charter->roadmapStory->fieldList['id']['sortType'] = false;

$config->charter->roadmapStory->fieldList['pri']['name']     = 'pri';
$config->charter->roadmapStory->fieldList['pri']['type']     = 'pri';
$config->charter->roadmapStory->fieldList['pri']['sortType'] = false;

$config->charter->roadmapStory->fieldList['title']['name']         = 'title';
$config->charter->roadmapStory->fieldList['title']['title']        = $lang->story->name;
$config->charter->roadmapStory->fieldList['title']['type']         = 'title';
$config->charter->roadmapStory->fieldList['title']['nestedToggle'] = true;
$config->charter->roadmapStory->fieldList['title']['link']         = array('module' => 'story', 'method' => 'storyView', 'params' => "storyID={id}&version={version}&param=0&storyType={type}");
$config->charter->roadmapStory->fieldList['title']['sortType']     = false;

$config->charter->roadmapStory->fieldList['roadmap']['name']  = 'roadmap';
$config->charter->roadmapStory->fieldList['roadmap']['title'] = $lang->story->roadmap;
$config->charter->roadmapStory->fieldList['roadmap']['type']  = 'category';

$config->charter->roadmapStory->fieldList['module']['name']  = 'module';
$config->charter->roadmapStory->fieldList['module']['title'] = $lang->story->module;
$config->charter->roadmapStory->fieldList['module']['type']  = 'category';

$config->charter->roadmapStory->fieldList['status']['name']      = 'status';
$config->charter->roadmapStory->fieldList['status']['title']     = $lang->story->status;
$config->charter->roadmapStory->fieldList['status']['type']      = 'status';
$config->charter->roadmapStory->fieldList['status']['sortType']  = false;
$config->charter->roadmapStory->fieldList['status']['statusMap'] = $lang->story->statusList;

global $app;
$app->loadLang('program');
$app->loadConfig('program');
$config->charter->programList = new stdclass();
$config->charter->programList->fieldList = array();
$config->charter->programList->fieldList['id']['name']     = 'id';
$config->charter->programList->fieldList['id']['type']     = 'id';
$config->charter->programList->fieldList['id']['sortType'] = false;
$config->charter->programList->fieldList['name']           = $config->program->browse->dtable->fieldList['name'];
$config->charter->programList->fieldList['status']         = $config->program->browse->dtable->fieldList['status'];
$config->charter->programList->fieldList['PM']             = $config->program->browse->dtable->fieldList['PM'];
$config->charter->programList->fieldList['budget']         = $config->program->browse->dtable->fieldList['budget'];
$config->charter->programList->fieldList['begin']          = $config->program->browse->dtable->fieldList['begin'];
$config->charter->programList->fieldList['end']            = $config->program->browse->dtable->fieldList['end'];
$config->charter->programList->fieldList['progress']       = $config->program->browse->dtable->fieldList['progress'];
$config->charter->programList->fieldList['PM']['type']     = 'user';
$config->charter->programList->fieldList['name']['link']   = "RAWJS<function(info){const {row, col} = info; if(row.data.type == 'project') return {url:$.createLink('project', 'view', 'projectID=' + row.data.id)}; if(row.data.type == 'program') return {url:$.createLink('program', 'project', 'programID=' + row.data.id)};}>RAWJS";
unset($config->charter->programList->fieldList['PM']['link']);
if($config->vision == 'or') unset($config->charter->programList->fieldList['name']['link']);
