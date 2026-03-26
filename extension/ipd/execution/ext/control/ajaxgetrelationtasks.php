<?php
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * Ajax 获取关联关系任务下拉列表。
     * Ajax get relation tasks.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  string $taskType
     * @param  int    $appendTask
     * @access public
     * @return void
     */
    public function ajaxGetRelationTasks($projectID, $executionID = 0, $taskID = 0, $taskType = 'pretask', $appendTask = 0)
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

        $taskList = $this->loadModel('task')->getProjectTaskList($projectID, false, $executionID);
        $tasks    = $this->execution->getRelationTasks($taskList, $projectID, $executionID, array($appendTask));

        /* 父任务不允许建立任务关系。 */
        $disabledParent = function($tasks, $disabledTasks = array()) use(&$disabledParent)
        {
            foreach($tasks as $key => $task)
            {
                $tasks[$key]['disabled'] = !empty($task['disabled']) ? $task['disabled'] : in_array($task['value'], $disabledTasks);
                if(empty($task['items'])) continue;

                $tasks[$key]['disabled'] = true;
                $tasks[$key]['items'] = $disabledParent($task['items'], $disabledTasks);
            }
            return $tasks;
        };

        /* 如果不存在已被选中的前置或者后置任务。 */
        if(empty($taskID))
        {
            $tasks = $disabledParent($tasks);
            return $this->send(array('result' => 'success', 'message' => array_values($tasks)));
        }

        /* 获取表单以外已存在的所有任务关系。 */
        $relations = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where('project')->eq($projectID)->fetchAll('id');
        if($_POST) $relations = $this->execution->mergeRelations($relations);

        /* 根据已有的任务关系获取禁选任务。 */
        $disabledTasks = $this->execution->getDisabledTasks($relations, $taskID, $taskType);

        /* 在迭代内建立任务关系时前置和后置只能有一个选择迭代外的任务。 */
        if($executionID && $taskList[$taskID]->execution != $executionID)
        {
            foreach($taskList as $task)
            {
                if($task->execution != $executionID) $disabledTasks[$task->id] = $task->id;
            }
        }
        if(!empty($_POST['selectedValue'])) unset($disabledTasks[$_POST['selectedValue']]);

        $tasks = $disabledParent($tasks, $disabledTasks);

        return $this->send(array('result' => 'success', 'message' => array_values($tasks)));
    }
}
