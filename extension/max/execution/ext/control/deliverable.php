<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * 执行交付物。
     * Execution Deliverable.
     *
     * @param  int $executionID
     * @access public
     * @return void
     */
    public function deliverable($executionID = 0)
    {
        $this->loadModel('project');
        $execution   = $this->commonAction($executionID);
        $project     = $this->loadModel('project')->fetchByID($execution->project);
        $projectType = $project->hasProduct ? 'product' : 'project';

        if($_POST)
        {
            $data = form::data($this->config->execution->form->deliverable)->get();
            $this->project->saveDeliverable($execution, 'execution', $data);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $deliverables['close'] = $this->project->getProjectDeliverable(0, $executionID, (int)$execution->workflowGroup, $projectType, $project->model, 'whenClosed');

        $actions = $this->loadModel('action')->getList('execution', $executionID);
        $actions = array_filter($actions, function($action)
        {
            return $action->action == 'managedeliverable';
        });

        $this->view->title        = $this->lang->deliverable->common;
        $this->view->deliverables = $deliverables;
        $this->view->actions      = $actions;
        $this->view->execution    = $execution;
        $this->display();
    }
}
