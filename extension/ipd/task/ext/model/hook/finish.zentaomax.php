<?php
    /* Update realDuration. */
    $finishedDate = $this->post->finishedDate;
    $taskInfo     = $this->dao->select('realStarted')->from(TABLE_TASK)->where('id')->eq($oldTask->id)->fetch();
    $realStarted  = $taskInfo->realStarted;

    if(!empty($realStarted) and $realStarted != '0000-00-00 00:00:00')
    {
        $realDuration = $this->loadModel('holiday')->getActualWorkingDays($realStarted, $finishedDate);
        $realDuration = count($realDuration);

        $this->dao->update(TABLE_TASK)->set('realDuration')->eq($realDuration)->where('id')->eq($oldTask->id)->exec();
    }
?>
