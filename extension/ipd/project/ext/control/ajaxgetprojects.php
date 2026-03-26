<?php
class project extends control
{
    /**
     * Ajax get projects.
     *
     * @param  int    $programID
     * @param  int    $projectID
     * @param  string $model        scrum|waterfall|kanban|agileplus|waterfallplus
     * @access public
     * @return void
     */
    public function ajaxGetProjects($programID, $model)
    {
        $items = array();
        if(empty($programID))
        {
            $projectPairs = $this->project->getPairsByModel($model);
        }
        else
        {
            $projectPairs = $this->project->getPairsByProgram($programID, 'all', false, 'order_asc', '', $model);
        }

        foreach($projectPairs as $id => $name) $items[] = array('value' => $id, 'text' => $name);

        return print(json_encode($items));
    }
}
