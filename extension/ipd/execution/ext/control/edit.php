<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * Edit a execution.
     *
     * @param  int    $executionID
     * @param  string $action
     * @param  string $extra
     * @param  string $newPlans
     * @param  string $confirm
     *
     * @access public
     * @return void
     */
    public function edit($executionID, $action = 'edit', $extra = '', $newPlans = '', $confirm = 'no')
    {
        $execution = $this->loadModel('execution')->getByID($executionID);
        $project   = $this->loadModel('project')->getByID($execution->project);
        if($_POST and $project->model == 'ipd' and !$execution->parallel)
        {
            $this->loadModel('programplan')->checkIpdStageDate($execution);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        return parent::edit($executionID, $action, $extra, $newPlans, $confirm);
    }
}
