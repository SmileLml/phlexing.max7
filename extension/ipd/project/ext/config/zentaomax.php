<?php
$config->project->editor->copyproject = array('id' => 'desc', 'tools' => 'simpleTools');

$config->project->multiple['project'] .= ',other,auditplan,';

$config->project->form->create['isTpl'] = array('type' => 'string', 'required' => false, 'default' => '0');

if(helper::hasFeature('deliverable'))
{
    $config->project->form->create['deliverable']      = array('type' => 'array', 'required' => false, 'default' => array());
    $config->project->form->close['deliverable']       = array('type' => 'array', 'required' => false, 'default' => array());
    $config->project->form->deliverable['whenCreated'] = array('type' => 'array', 'required' => false, 'default' => array());
    $config->project->form->deliverable['whenClosed']  = array('type' => 'array', 'required' => false, 'default' => array());
}

$config->project->actionList['deleteTemplate']['url']          = helper::createLink('project', 'deleteTemplate', "id={id}");
$config->project->actionList['deleteTemplate']['data-confirm'] = $lang->project->confirmDeleteTemplate;
$config->project->actionList['deleteTemplate']['hint']         = $lang->project->deleteTemplate;
$config->project->actionList['deleteTemplate']['className']    = 'ajax-submit';
$config->project->actionList['deleteTemplate']['icon']         = 'trash';

$config->project->template = new stdclass();
$config->project->template->dtable = new stdclass();
$config->project->template->dtable->fieldList['id']['title']    = $lang->idAB;
$config->project->template->dtable->fieldList['id']['name']     = 'id';
$config->project->template->dtable->fieldList['id']['type']     = 'checkID';
$config->project->template->dtable->fieldList['id']['group']    = 1;
$config->project->template->dtable->fieldList['id']['required'] = true;

$config->project->template->dtable->fieldList['name']['title']    = $lang->project->templateName;
$config->project->template->dtable->fieldList['name']['name']     = 'name';
$config->project->template->dtable->fieldList['name']['type']     = 'title';
$config->project->template->dtable->fieldList['name']['link']     = array('module' => 'project', 'method' => 'execution', 'params' => 'status=undone&projectID={id}');
$config->project->template->dtable->fieldList['name']['group']    = 1;
$config->project->template->dtable->fieldList['name']['required'] = true;

$config->project->template->dtable->fieldList['workflowGroup']['title']    = $lang->project->workflowGroup;
$config->project->template->dtable->fieldList['workflowGroup']['name']     = 'workflowGroup';
$config->project->template->dtable->fieldList['workflowGroup']['type']     = 'maps';
$config->project->template->dtable->fieldList['workflowGroup']['show']     = true;
$config->project->template->dtable->fieldList['workflowGroup']['sortType'] = true;
$config->project->template->dtable->fieldList['workflowGroup']['group']    = 2;

$config->project->template->dtable->fieldList['status']['title']     = $lang->project->status;
$config->project->template->dtable->fieldList['status']['name']      = 'status';
$config->project->template->dtable->fieldList['status']['type']      = 'status';
$config->project->template->dtable->fieldList['status']['statusMap'] = $lang->project->statusList;
$config->project->template->dtable->fieldList['status']['width']     = '80';
$config->project->template->dtable->fieldList['status']['show']      = true;
$config->project->template->dtable->fieldList['status']['group']     = '3';

$config->project->template->dtable->fieldList['openedBy']['title'] = $lang->project->openedBy;
$config->project->template->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->project->template->dtable->fieldList['openedBy']['type']  = 'user';
$config->project->template->dtable->fieldList['openedBy']['show']  = true;
$config->project->template->dtable->fieldList['openedBy']['group'] = 4;

$config->project->template->dtable->fieldList['openedDate']['title'] = $lang->project->openedDate;
$config->project->template->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->project->template->dtable->fieldList['openedDate']['type']  = 'date';
$config->project->template->dtable->fieldList['openedDate']['show']  = true;
$config->project->template->dtable->fieldList['openedDate']['group'] = 5;

$config->project->template->dtable->fieldList['actions']['name']  = 'actions';
$config->project->template->dtable->fieldList['actions']['title'] = $lang->actions;
$config->project->template->dtable->fieldList['actions']['type']  = 'actions';
$config->project->template->dtable->fieldList['actions']['width'] = '80';
$config->project->template->dtable->fieldList['actions']['list']  = $config->project->actionList;
$config->project->template->dtable->fieldList['actions']['menu']  = array('deleteTemplate');
$config->project->template->dtable->fieldList['actions']['show']  = true;
