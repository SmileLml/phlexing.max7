<?php
class task extends control
{
    /**
     * Change subStatus of a task by ajax.
     *
     * @param  int    $taskID
     * @param  string $subStatus
     * @access public
     * @return void
     */
    public function ajaxChangeSubStatus($taskID, $subStatus)
    {
        $this->dao->update(TABLE_TASK)->set('subStatus')->eq($subStatus)->where('id')->eq($taskID)->exec();
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }
}
