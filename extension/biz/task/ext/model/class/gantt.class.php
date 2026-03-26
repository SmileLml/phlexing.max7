<?php
class ganttTask extends taskModel
{
    /**
     * @return false|mixed[]
     * @param object $oldTask
     * @param object $task
     */
    public function start($oldTask, $task)
    {
        $message = $this->checkDepend($oldTask->id, 'begin');
        if($message)
        {
            dao::$errors['message'][] = $message;
            return false;
        }

        if($this->post->left == 0)
        {
            $lastMember = array();
            if($oldTask->mode == 'linear') $lastMember = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($oldTask->id)->orderBy('order desc')->limit(1)->fetch();
            if(empty($lastMember) or $lastMember->account == $this->app->user->account)
            {
                $message = $this->checkDepend($oldTask->id, 'end');
                if($message)
                {
                    dao::$errors = $message;
                    return false;
                }
            }
        }

        return parent::start($oldTask, $task);
    }

    /**
     * @return bool|mixed[]
     * @param object $oldTask
     * @param object $task
     */
    public function finish($oldTask, $task)
    {
        $message = $this->checkDepend($oldTask->id, 'end');
        if($message)
        {
            dao::$errors['message'][] = $message;
            return false;
        }

        return parent::finish($oldTask, $task);
    }

    public function checkDepend($taskID, $action = 'begin')
    {
        $actions = $action;
        if($action == 'end') $actions = 'begin,end';

        $relations     = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where('task')->eq($taskID)->andWhere('action')->in($actions)->fetchAll('pretask');
        $relationTasks = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in(array_keys($relations))->fetchAll('id');
        $task          = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($taskID)->fetch();

        $message = '';
        foreach($relations as $id => $relation)
        {
            $pretask = $relationTasks[$id];
            if($pretask->deleted) continue;
            if($action != $relation->action and $task->status != 'wait') continue;
            if($relation->condition == 'begin' and helper::isZeroDate($pretask->realStarted) and empty($pretask->finishedBy))
            {
                $noticeType = $action == 'begin' ? 'notSS' : 'notSF';
                $message .= sprintf($this->lang->task->gantt->notice->$noticeType, "$id::" . $pretask->name);
            }
            elseif($relation->condition == 'end' and empty($pretask->finishedBy))
            {
                $noticeType = $action == 'begin' ? 'notFS' : 'notFF';
                $message .= sprintf($this->lang->task->gantt->notice->$noticeType, "$id::" . $pretask->name);
            }
        }

        return $message;
    }

    /**
     * @param object $data
     */
    public function addTaskEffort($data)
    {
        if(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'effort.class.php')) return $this->loadExtension('effort')->addTaskEffort($data);
        return parent::addTaskEffort($data);
    }

    /**
     * @return false|mixed[]
     * @param object $task
     * @param mixed[] $workhour
     */
    public function checkWorkhour($task, $workhour)
    {
        $message = $this->checkDepend($task->id, 'begin');
        if($message)
        {
            dao::$errors['message'] = $message;
            return false;
        }

        $workhour = parent::checkWorkhour($task, $workhour);
        if(!$workhour || dao::isError()) return array();

        foreach($workhour as $id => $record)
        {
            if(!empty($record->left)) continue;
            $message = $this->checkDepend($task->id, 'end');
            if($message)
            {
                dao::$errors['message'] = $message;
                return false;
            }
        }
        return $workhour;
    }

    /**
     * 取消一个任务。
     * Cancel a task.
     *
     * @param  object  $oldTask
     * @param  object  $task
     * @param  array   $output
     * @access public
     * @return bool
     */
    public function cancel($oldTask, $task, $output = array())
    {
        $result = parent::cancel($oldTask, $task, $output);
        if(!$result) return false;

        /* 解除任务依赖关系。*/
        $childTasks = array();
        if($oldTask->isParent) $childTasks = $this->dao->select('id')->from(TABLE_TASK)->where('parent')->eq($oldTask->id)->fetchPairs('id');
        $this->unlinkRelation($oldTask->id, $childTasks);
        return $result;
    }

    /**
     * 批量编辑任务后的其他数据处理。
     * other data process after task batch edit.
     *
     * @param  object[] $tasks
     * @param  object[] $oldTasks
     * @access public
     * @return bool
     */
    public function afterBatchUpdate($tasks, $oldTasks = array())
    {
        $result = parent::afterBatchUpdate($tasks, $oldTasks);
        if(!$result) return false;

        /* 如果状态变更为已取消，需要解除任务关联关系。 */
        /* 如果有子任务，需要解除子任务关联关系。*/
        /* 重新查一遍任务是因为父任务的状态会根据子任务重新计算。 */
        $childGroups = $this->dao->select('id,parent')->from(TABLE_TASK)->where('parent')->in(array_keys($tasks))->fetchGroup('parent', 'id');
        $newTasks    = $this->dao->select('id,status')->from(TABLE_TASK)->where('id')->in(array_keys($tasks))->fetchAll('id');
        foreach($newTasks as $taskID => $task)
        {
            $oldTask = zget($oldTasks, $taskID);
            if($oldTask->status != 'cancel' && $task->status == 'cancel')
            {
                $childTasks = zget($childGroups, $taskID, array());
                $this->unlinkRelation($taskID, array_keys($childTasks));
            }
        }

        return $result;
    }

    /**
     * 解除任务关系。
     * Unlink task relation.
     *
     * @param  int    $taskID
     * @param  array  $childTasks
     * @access public
     * @return bool
     */
    public function unlinkRelation($taskID, $childTasks = array())
    {
        $this->dao->delete()->from(TABLE_RELATIONOFTASKS)->where('pretask')->eq($taskID)->orWhere('task')->eq($taskID)->exec();

        if($childTasks) $this->dao->delete()->from(TABLE_RELATIONOFTASKS)->where('pretask')->in($childTasks)->orWhere('task')->in($childTasks)->exec();

        return !dao::isError();
    }
}
