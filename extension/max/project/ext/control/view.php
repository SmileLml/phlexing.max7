<?php
class myProject extends project
{
    /**
     * View a project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function view($projectID = 0)
    {
        $project = $this->project->fetchByID($projectID);
        if(empty($project->workflowGroup) && $project->model != 'kanban')
        {
            $model         = in_array($project->model, array('scrum', 'agileplus')) ? 'scrum' : 'waterfall';
            $type          = $project->hasProduct ? 'product' : 'project';
            $workflowGroup = $this->dao->select('*')->from(TABLE_WORKFLOWGROUP)->where('projectModel')->eq($model)->andWhere('projectType')->eq($type)->andWhere('main')->eq('1')->fetch();
            $this->dao->update(TABLE_PROJECT)->set('workflowGroup')->eq($workflowGroup->id)->where('id')->eq($projectID)->exec();
        }

        if($project->isTpl)
        {
            $this->config->project->actions->view['mainActions']   = array();
            $this->config->project->actions->view['suffixActions'] = array('deleteTemplate');
            $this->lang->project->statusList['doing'] = $this->lang->project->inUse;
        }

        parent::view($projectID);
    }
}
