<?php
helper::importControl('programplan');
class myProgramplan extends programplan
{
    public function ajaxCustom()
    {
        $projectID = $this->session->project;
        $project   = $this->loadModel('project')->getByID($projectID);

        if($project->model != 'ipd') unset($this->lang->programplan->stageCustom['point']);
        return parent::ajaxCustom();
    }
}
