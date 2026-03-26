<?php
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * 根据后置任务ID获取禁用的任务。
     * AJAX: Get disabled tasks by post task id.
     *
     * @param  int    $executionID
     * @param  int    $relationID
     * @access public
     * @return void
     */
    public function ajaxGetDisabledTasks($executionID = 0, $relationID = 0)
    {
        $this->commonAction($executionID);

        $relations  = $this->execution->getRelationsOfTasks($executionID);
        $postTaskID = isset($relations[$relationID]) ? $relations[$relationID]->task : 0;
        if(!$postTaskID) return $this->send(json_encode(array()));

        $relationTasks = $this->execution->getDisabledTasks($relations, $postTaskID, 'task');
        $conflicts     = array();
        foreach($relationTasks as $relationTaskID)
        {
            foreach($relationTasks as $taskID)
            {
                foreach($relations as $relation)
                {
                    if($relation->pretask == $relationTaskID && $relation->task    == $taskID) $conflicts[] = $relation->id;
                    if($relation->task    == $relationTaskID && $relation->pretask == $taskID) $conflicts[] = $relation->id;
                }
            }
        }

        return $this->send(array_unique($conflicts));
    }
}
