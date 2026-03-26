<?php
class project extends control
{
    /**
     * Ajax load project.
     *
     * @param  int    $programID
     * @param  int    $projectID
     * @param  string $model        scrum|waterfall|kanban|agileplus|waterfallplus
     * @access public
     * @return void
     */
    public function ajaxLoadProject($programID, $projectID, $model)
    {
        $this->loadModel('project');
        $programID = (int)$programID;
        $projectID = (int)$projectID;
        if($projectID)
        {
            $project = $this->project->getById($projectID);
            $projects[$project->id]           = new stdclass();
            $projects[$project->id]->id       = $project->id;
            $projects[$project->id]->name     = $project->name;
            $projects[$project->id]->multiple = $project->multiple;
        }
        else
        {
            if(empty($programID))
            {
                $projectPairs = $this->project->getPairsByModel($model);
            }
            else
            {
                $projectPairs = $this->project->getPairsByProgram($programID, 'all', false, 'order_asc', '', $model);
            }

            $projectInfos = $this->project->getByIdList(array_keys($projectPairs));
            $projects     = array();
            foreach($projectPairs as $projectID => $projectName)
            {
                $project = zget($projectInfos, $projectID, '');
                if(empty($project)) continue;

                $projects[$projectID]           = new stdclass();
                $projects[$projectID]->id       = $project->id;
                $projects[$projectID]->name     = $project->name;
                $projects[$projectID]->multiple = $project->multiple;
            }
        }

        $this->view->projects = $projects;
        $this->view->model    = $model;
        $this->display();
    }
}
