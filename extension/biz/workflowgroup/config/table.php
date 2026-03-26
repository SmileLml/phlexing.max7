<?php
global $lang, $app;

$config->workflowgroup->dtable = new stdclass();
$config->workflowgroup->dtable->product = new stdclass();
$config->workflowgroup->dtable->project = new stdclass();

$config->workflowgroup->dtable->product->fieldList['id']['title']    = $lang->idAB;
$config->workflowgroup->dtable->product->fieldList['id']['type']     = 'checkID';
$config->workflowgroup->dtable->product->fieldList['id']['sortType'] = true;
$config->workflowgroup->dtable->product->fieldList['id']['required'] = true;
$config->workflowgroup->dtable->product->fieldList['id']['fixed']    = false;

$config->workflowgroup->dtable->product->fieldList['name']['title']        = $lang->workflowgroup->name;
$config->workflowgroup->dtable->product->fieldList['name']['fixed']        = 'left';
$config->workflowgroup->dtable->product->fieldList['name']['type']         = 'nestedTitle';
$config->workflowgroup->dtable->product->fieldList['name']['sortType']     = true;
$config->workflowgroup->dtable->product->fieldList['name']['fixed']        = false;
$config->workflowgroup->dtable->product->fieldList['name']['width']        = '156px';
$config->workflowgroup->dtable->product->fieldList['name']['data-toggle']  = 'modal';
$config->workflowgroup->dtable->product->fieldList['name']['link']         = array('url' => array('module' => 'workflowgroup', 'method' => 'view', 'params' => 'workflowgroupID={id}'));
$config->workflowgroup->dtable->product->fieldList['name']['required']     = true;

$config->workflowgroup->dtable->product->fieldList['status']['title']     = $lang->statusAB;
$config->workflowgroup->dtable->product->fieldList['status']['type']      = 'status';
$config->workflowgroup->dtable->product->fieldList['status']['statusMap'] = $lang->workflowgroup->statusList;
$config->workflowgroup->dtable->product->fieldList['status']['sortType']  = true;

$config->workflowgroup->dtable->product->fieldList['desc']['title']    = $lang->workflowgroup->desc;
$config->workflowgroup->dtable->product->fieldList['desc']['type']     = 'text';
$config->workflowgroup->dtable->product->fieldList['desc']['sortType'] = false;

$config->workflowgroup->dtable->product->fieldList['actions']['title']    = $lang->actions;
$config->workflowgroup->dtable->product->fieldList['actions']['type']     = 'actions';
$config->workflowgroup->dtable->product->fieldList['actions']['width']    = '160px';
$config->workflowgroup->dtable->product->fieldList['actions']['list']     = $config->workflowgroup->actionList;
$config->workflowgroup->dtable->product->fieldList['actions']['menu']     = array('design', 'release|deactivate', 'edit', 'delete');
$config->workflowgroup->dtable->product->fieldList['actions']['required'] = true;

$config->workflowgroup->dtable->project->fieldList['id']   = $config->workflowgroup->dtable->product->fieldList['id'];
$config->workflowgroup->dtable->project->fieldList['name'] = $config->workflowgroup->dtable->product->fieldList['name'];

$config->workflowgroup->dtable->project->fieldList['projectModel']['title']    = $lang->workflowgroup->projectModel;
$config->workflowgroup->dtable->project->fieldList['projectModel']['type']     = 'category';
$config->workflowgroup->dtable->project->fieldList['projectModel']['map']      = $lang->workflowgroup->projectModelList;
$config->workflowgroup->dtable->project->fieldList['projectModel']['sortType'] = true;

$config->workflowgroup->dtable->project->fieldList['projectType']['title']    = $lang->workflowgroup->projectType;
$config->workflowgroup->dtable->project->fieldList['projectType']['type']     = 'category';
$config->workflowgroup->dtable->project->fieldList['projectType']['sortType'] = true;
$config->workflowgroup->dtable->project->fieldList['projectType']['map']      = $lang->workflowgroup->projectTypeList;

$config->workflowgroup->dtable->project->fieldList['status']  = $config->workflowgroup->dtable->product->fieldList['status'];
$config->workflowgroup->dtable->project->fieldList['desc']    = $config->workflowgroup->dtable->product->fieldList['desc'];
$config->workflowgroup->dtable->project->fieldList['actions'] = $config->workflowgroup->dtable->product->fieldList['actions'];

$config->workflowgroup->dtable->design = new stdclass();
$config->workflowgroup->dtable->design->fieldList['name']['title']        = $lang->workflowgroup->workflow->name;
$config->workflowgroup->dtable->design->fieldList['name']['fixed']        = false;
$config->workflowgroup->dtable->design->fieldList['name']['type']         = 'nestedTitle';
$config->workflowgroup->dtable->design->fieldList['name']['width']        = '200px';
$config->workflowgroup->dtable->design->fieldList['name']['sortType']     = true;
$config->workflowgroup->dtable->design->fieldList['name']['required']     = true;

$config->workflowgroup->dtable->design->fieldList['module']['title']     = $lang->workflowgroup->workflow->module;
$config->workflowgroup->dtable->design->fieldList['module']['type']      = 'text';
$config->workflowgroup->dtable->design->fieldList['module']['sortType']  = true;

$config->workflowgroup->dtable->design->fieldList['app']['title']     = $lang->workflowgroup->workflow->app;
$config->workflowgroup->dtable->design->fieldList['app']['type']      = 'category';
$config->workflowgroup->dtable->design->fieldList['app']['sortType']  = true;

$config->workflowgroup->dtable->design->fieldList['buildin']['title']    = $lang->workflowgroup->workflow->buildin;
$config->workflowgroup->dtable->design->fieldList['buildin']['type']     = 'text';
$config->workflowgroup->dtable->design->fieldList['buildin']['width']    = '50px';
$config->workflowgroup->dtable->design->fieldList['buildin']['align']    = 'center';
$config->workflowgroup->dtable->design->fieldList['buildin']['sortType'] = true;

$config->workflowgroup->dtable->design->fieldList['exclusive']['title']    = $lang->workflowgroup->workflow->exclusive;
$config->workflowgroup->dtable->design->fieldList['exclusive']['type']     = 'text';
$config->workflowgroup->dtable->design->fieldList['exclusive']['width']    = '120px';
$config->workflowgroup->dtable->design->fieldList['exclusive']['align']    = 'center';
$config->workflowgroup->dtable->design->fieldList['exclusive']['sortType'] = false;

$config->workflowgroup->dtable->design->fieldList['status']['title']     = $lang->statusAB;
$config->workflowgroup->dtable->design->fieldList['status']['type']      = 'status';
$config->workflowgroup->dtable->design->fieldList['status']['statusMap'] = $lang->workflowgroup->statusList;
$config->workflowgroup->dtable->design->fieldList['status']['sortType']  = true;

$config->workflowgroup->dtable->design->fieldList['desc']['title']    = $lang->workflowgroup->workflow->desc;
$config->workflowgroup->dtable->design->fieldList['desc']['type']     = 'text';
$config->workflowgroup->dtable->design->fieldList['desc']['sortType'] = false;

$config->workflowgroup->dtable->design->fieldList['actions']['title']    = $lang->actions;
$config->workflowgroup->dtable->design->fieldList['actions']['type']     = 'actions';
$config->workflowgroup->dtable->design->fieldList['actions']['width']    = '140px';
$config->workflowgroup->dtable->design->fieldList['actions']['list']     = $config->workflowgroup->actionList;
$config->workflowgroup->dtable->design->fieldList['actions']['menu']     = array('setExclusive', 'designBuildin|designCustom', 'activateFlow|deactivateFlow');
$config->workflowgroup->dtable->design->fieldList['actions']['required'] = true;
