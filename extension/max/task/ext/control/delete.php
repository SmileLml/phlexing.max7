<?php
class myTask extends task
{
    /**
     * 删除一个任务。
     * Delete a task.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  string $from
     * @access public
     * @return void
     */
    public function delete($executionID, $taskID, $from = '')
    {
        $task = $this->task->getByID($taskID);

        /* 如果是父任务，先删除所有子任务 */
        if($task->isParent)
        {
            dao::$filterTpl = 'never';

            $childIdList = $this->task->getAllChildId($taskID, false);
            $childTasks  = $this->task->getByIdList($childIdList);
            foreach($childTasks as $childID => $childTask)
            {
                if(strpos(",{$childTask->path},", ",$taskID,") === false) continue;
                $this->task->delete(TABLE_TASK, $childID);
                if($childTask->fromBug != 0) $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->eq($childTask->fromBug)->exec();
                if($childTask->story) $this->loadModel('story')->setStage($childTask->story);
            }
        }

        $this->task->delete(TABLE_TASK, $taskID);
        if($task->parent > 0)
        {
            $this->task->updateParentStatus($task->id);
            $this->loadModel('action')->create('task', $task->parent, 'deleteChildrenTask', '', $taskID);
        }
        if($task->fromBug != 0) $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->eq($task->fromBug)->exec();
        if($task->story) $this->loadModel('story')->setStage($task->story);

        /* 删除任务依赖关系。*/
        $this->loadExtension('gantt')->unlinkRelation($taskID);

        $this->executeHooks($taskID);

        /* 在看板中删除任务时的返回。*/
        /* Respond when delete in kanban. */
        if($from == 'taskkanban') return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "refreshKanban()"));

        $link = $this->session->taskList ? $this->session->taskList : $this->createLink('execution', 'task', "executionID={$task->execution}");
        return $this->send(array('result' => 'success', 'load' => $link, 'closeModal' => true));
    }
}
