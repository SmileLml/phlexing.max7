<?php
class myTask extends task
{
    /**
     * @param int $taskID
     * @param string $cardPosition
     * @param string $from
     */
    public function cancel($taskID, $cardPosition = '', $from = '')
    {
        $this->view->cardPosition = $cardPosition;
        $this->view->from         = $from;
        $this->view->taskRelation = $this->dao->select('id')->from(TABLE_RELATIONOFTASKS)->where('pretask')->eq($taskID)->orWhere('task')->eq($taskID)->fetchPairs();

        return parent::cancel($taskID, $cardPosition, $from);
    }
}
