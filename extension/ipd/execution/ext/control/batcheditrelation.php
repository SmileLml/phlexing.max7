<?php
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * Batch edit relation of task.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchEditRelation($projectID = 0, $executionID = 0)
    {
        $backID = !empty($projectID) && empty($executionID) ? $projectID : $executionID;
        if(!empty($projectID) && empty($executionID))
        {
            $this->project->setMenu($projectID);
        }
        else
        {
            $execution = $this->commonAction($executionID);
            $projectID = $execution->project;
        }

        if(!empty($_POST['pretask']))
        {
            $this->execution->editRelationOfTasks($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink($this->app->rawModule, 'relation', "executionID=$backID")));
        }

        if(!$this->post->relationIdList) $this->locate($this->createLink($this->app->rawModule, 'relation', "executionID=$backID"));

        $relationIdList = $this->post->relationIdList;
        $relations      = $this->execution->getRelationsOfTasks($projectID, $executionID);
        foreach(array_keys($relations) as $relationID) if(!in_array($relationID, $relationIdList)) unset($relations[$relationID]);

        $titleTips          = '';
        $taskRelationErrors = $this->execution->checkTaskRelation($relations, $projectID);
        foreach($taskRelationErrors as $relationID => $errors)
        {
            if(!empty($errors['preTaskIsParent']) || !empty($errors['afterTaskIsParent']) || !empty($errors['multiplePreTask'])) $titleTips = $this->lang->execution->error->parentTaskRelation;
            foreach($errors as $errorCode => $errorTip)
            {
                $taskRelationErrors[$relationID][$errorCode] = zget($this->lang->execution->error, $errorCode);
            }
        }

        /* The header and position. */
        $this->view->title          = $this->lang->execution->common . $this->lang->hyphen . $this->lang->execution->gantt->editRelationOfTasks;
        $this->view->executionID    = $executionID;
        $this->view->projectID      = $projectID;
        $this->view->tasks          = $this->loadModel('task')->getProjectTaskList($projectID, true, $executionID);
        $this->view->relations      = $relations;
        $this->view->titleTips      = $titleTips;
        $this->view->relationErrors = $taskRelationErrors;
        $this->display();
    }
}
