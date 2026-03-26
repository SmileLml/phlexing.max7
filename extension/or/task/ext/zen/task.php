<?php
/**
 * 指派后返回响应。
 * Response after assignto.
 *
 * @param  int       $taskID
 * @param  string    $from   ''|taskkanban
 * @access protected
 * @return array
 */
protected function responseAfterAssignTo($taskID, $from)
{
    /* 如果使调研任务，则返回当前页面。*/
    /* If it is a research task, return the current page. */
    if(in_array($this->app->rawModule, array('researchtask', 'marketresearch'))) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true);

    return parent::responseAfterAssignTo($taskID, $from);
}

/**
 * 记录工时后返回响应。
 * Response after record
 *
 * @param  object    $task
 * @param  array     $changes
 * @param  string    $from
 * @access protected
 * @return int|array
 */
protected function responseAfterRecord($task, $changes, $from)
{
    /* 如果使调研任务，则返回当前页面。*/
    /* If it is a research task, return the current page. */
    if(in_array($this->app->rawModule, array('researchtask', 'marketresearch'))) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true);

    return parent::responseAfterRecord($task, $changes, $from);
}
