<?php
helper::importControl('execution');
class myExecution extends execution
{
    public function ajaxGetAttribute($executionID)
    {
        echo $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch('attribute');
    }
}
