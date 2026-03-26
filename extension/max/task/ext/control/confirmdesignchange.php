<?php
class myTask extends task
{
    /**
     * Confirm desgin change
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function confirmDesignChange($taskID)
    {
        $task   = $this->task->getById($taskID);
        $design = $this->loadModel('design')->getByID($task->design);
        $this->dao->update(TABLE_TASK)->set('designVersion')->eq($design->version)->where('id')->eq($taskID)->exec();
        $this->loadModel('action')->create('task', $taskID, 'designConfirmed', '', $design->version);

        die(js::reload('parent'));
    }
}
