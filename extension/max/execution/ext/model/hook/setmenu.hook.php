<?php
/* Set menu for stage. */
if(empty($executionID))
{
    $executions = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
    if(!$executionID and $this->session->execution) $executionID = $this->session->execution;
    if(!$executionID or !in_array($executionID, array_keys($executions))) $executionID = key($executions);
}

/* Unset story, bug, build and testtask if type is ops. */
$execution = $this->getByID((int)$executionID);
if($execution and $execution->type == 'stage')
{
    $attribute = $execution->attribute;
    if($attribute and isset($this->lang->stage->attribute[$attribute]))
    {
        $this->lang->execution->menu = $this->lang->stage->attribute[$attribute]->menu;
        $this->lang->execution->dividerMenu = $this->lang->stage->attribute[$attribute]->dividerMenu;
    }

    /* Some pages of some stages jump to the task page. */
    $moduleName = $this->app->getModuleName();
    $methodName = $this->app->getMethodName();
    if(in_array($attribute, array('request', 'review')) and in_array($moduleName . '-' . $methodName, array('execution-build', 'execution-bug', 'repo-create'))) die(js::locate(helper::createLink('execution', 'task', "executionID=$executionID")));
}

$project = !empty($execution) ? $this->loadModel('project')->getByID($execution->project) : null;
$model   = isset($project->model) ? $project->model : '';
if(in_array($model, array('scrum', 'agileplus', 'waterfallplus')))
{
    $featureList = $this->config->featureGroup->$model;
    foreach($featureList as $feature)
    {
        if(!helper::hasFeature("{$model}_$feature"))
        {
            if($feature == 'process') $feature = 'pssp';
            unset($this->lang->execution->menu->other['dropMenu']->{$feature});
        }
    }
}
