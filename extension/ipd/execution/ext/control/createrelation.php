<?php
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * 添加任务关系。
     * Create relation of execution.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function createRelation($projectID = 0, $executionID = 0)
    {
        if(!empty($projectID) && empty($executionID))
        {
            $this->project->setMenu($projectID);
        }
        else
        {
            $execution = $this->commonAction($executionID);
            $projectID = $execution->project;
        }

        if(!empty($_POST))
        {
            $this->execution->createRelationOfTasks($projectID, $executionID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $executionID = !empty($projectID) && empty($executionID) ? $projectID : $executionID;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink($this->app->rawModule, 'relation', "executionID=$executionID")));
        }

        /* The header and position. */
        $this->view->title       = $this->lang->execution->common . $this->lang->hyphen . $this->lang->execution->createRelation;
        $this->view->executionID = $executionID;
        $this->view->projectID   = $projectID;
        $this->display();
    }
}
