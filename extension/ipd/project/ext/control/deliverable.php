<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 项目交付物。
     * Project Deliverable.
     *
     * @param  int $projectID
     * @access public
     * @return void
     */
    public function deliverable($projectID = 0)
    {
        $this->project->setMenu($projectID);
        $project     = $this->loadModel('project')->getByID($projectID);
        $projectType = $project->hasProduct ? 'product' : 'project';

        if($_POST)
        {
            $data = form::data($this->config->project->form->deliverable)->get();
            $this->project->saveDeliverable($project, 'project', $data);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $deliverables['create'] = $this->project->getProjectDeliverable($projectID, 0, (int)$project->workflowGroup, $projectType, $project->model, 'whenCreated');
        $deliverables['close']  = $this->project->getProjectDeliverable($projectID, 0, (int)$project->workflowGroup, $projectType, $project->model, 'whenClosed');

        $actions = $this->loadModel('action')->getList('project', $projectID);
        $actions = array_filter($actions, function($action)
        {
            return $action->action == 'managedeliverable';
        });

        $this->view->title        = $this->lang->deliverable->common;
        $this->view->deliverables = $deliverables;
        $this->view->project      = $project;
        $this->view->actions      = $actions;
        $this->display();
    }
}
