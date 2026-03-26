<?php
if(helper::hasFeature('deliverable'))
{
    $config->workflowgroup->actionList['deliverable']['icon'] = 'list-box';
    $config->workflowgroup->actionList['deliverable']['hint'] = $lang->workflowgroup->deliverable;
    $config->workflowgroup->actionList['deliverable']['text'] = $lang->workflowgroup->deliverable;
    $config->workflowgroup->actionList['deliverable']['url']  = array('module' => 'workflowgroup', 'method' => 'deliverable', 'params' => 'id={id}');

    $config->workflowgroup->dtable->project->fieldList['actions']['list'] = $config->workflowgroup->actionList;
    $config->workflowgroup->dtable->project->fieldList['actions']['menu'] = array('design', 'release|deactivate', 'edit', 'deliverable', 'delete');;

    if(!isset($config->workflowgroup->form)) $config->workflowgroup->form = new stdclass();
    $config->workflowgroup->form->deliverable['key']         = array('type' => 'string', 'base' => true);
    $config->workflowgroup->form->deliverable['deliverable'] = array('type' => 'array');
    $config->workflowgroup->form->deliverable['required']    = array('type' => 'array');
}
