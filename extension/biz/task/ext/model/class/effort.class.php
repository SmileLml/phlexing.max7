<?php
class effortTask extends taskModel
{
    /**
     * Extend for add task estimate in biz
     *
     * @param  object    $data
     * @access public
     * @return int
     */
    public function addTaskEffort($data)
    {
        $this->app->loadLang('effort');
        $oldTask = $this->getById($data->task);

        $relation = $this->loadModel('action')->getRelatedFields('task', $data->task);

        $action = $data->left == 0 ? 'finished' : 'started';
        if(($oldTask->status != 'wait' and $oldTask->status != 'pause') and $action == 'started') $action = 'edited';
        if(($oldTask->status == 'done' or $oldTask->status == 'closed' or $oldTask->status == 'cancel') and $action == 'finished') $action = 'edited';
        if($this->app->rawMethod == 'start') $action = 'started';

        $effort = new stdclass();
        $effort->objectType = 'task';
        $effort->objectID   = $data->task;
        $effort->execution  = $oldTask->execution;
        $effort->product    = $relation['product'];
        $effort->project    = (int)$relation['project'];
        $effort->account    = $data->account;
        $effort->date       = $data->date;
        $effort->consumed   = $data->consumed;
        $effort->left       = $data->left;
        $effort->work       = !empty($data->work) ? strip_tags($data->work) : $this->lang->action->label->$action . $this->lang->effort->objectTypeList['task'] . " : " . $oldTask->name;
        $effort->vision     = $this->config->vision;
        $effort->order      = isset($data->order) ? $data->order : 0;
        $this->dao->insert(TABLE_EFFORT)->data($effort)->autoCheck()->exec();

        $effortID = $this->dao->lastInsertID();
        $this->action->create('effort', $effortID, 'created', '', '', '', false);

        return $effortID;
    }

    /**
     * Get taskList date record.
     *
     * @param  int|array $taskID
     * @access public
     * @return array
     */
    public function getTaskDateRecord($taskID)
    {
        return $this->dao->select('id,date')->from(TABLE_EFFORT)->where('objectID')->in($taskID)
            ->andWhere('objectType')->eq('task')
            ->andWhere('deleted')->eq('0')
            ->orderBy('date')
            ->fetchAll('id');
    }
}
