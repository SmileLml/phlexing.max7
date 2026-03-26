<?php
class myTask extends task
{
    /**
     * 批量创建任务。
     * Batch create tasks.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $moduleID
     * @param  int    $taskID
     * @param  string $cardPosition
     * @access public
     * @return void
     */
    public function batchCreate($executionID, $storyID = 0, $moduleID = 0, $taskID = 0, $cardPosition = '')
    {
        if($taskID) $this->view->splitTaskRelation = $this->dao->select('id')->from(TABLE_RELATIONOFTASKS)->where('pretask')->eq($taskID)->orWhere('task')->eq($taskID)->fetchPairs();

        parent::batchCreate($executionID, $storyID, $moduleID, $taskID, $cardPosition);
    }
}
