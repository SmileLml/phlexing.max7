<?php
helper::importControl('task');
class myTask extends task
{
    /**
     * @param int $taskID
     * @param string $version
     */
    public function view($taskID, $version = '')
    {
        $version ? $taskSpec  = $this->task->getTaskSpec($taskID, $version) : $taskSpec = '';
        $this->view->taskSpec = $taskSpec;
        $this->view->version  = $version;
        $this->view->designs  = $this->dao->select('id, name')->from(TABLE_DESIGN)->where('deleted')->eq(0)->fetchPairs();
        parent::view($taskID);
    }
}
