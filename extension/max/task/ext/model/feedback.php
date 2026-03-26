<?php
/**
 * @param object $task
 * @param mixed[] $taskIdList
 * @param int $bugID
 * @param int $todoID
 */
public function afterCreate($task, $taskIdList, $bugID, $todoID)
{
    return $this->loadExtension('feedback')->afterCreate($task, $taskIdList, $bugID, $todoID);
}

/**
 * @param object $oldTask
 * @param object $task
 */
public function afterUpdate($oldTask, $task)
{
    $this->loadExtension('feedback')->afterUpdate($oldTask, $task);
}

/**
 * @param object $task
 * @param string $action
 */
public static function isClickable($task, $action)
{
    $action = strtolower($action);
    if($action == 'batchcreate' && empty($task->team) && empty($task->mode) && !in_array($task->status, array('closed', 'cancel'))) return true;

    return parent::isClickable($task, $action);
}

/**
 * @param int $executionID
 * @param string $appendIdList
 * @param int $taskID
 */
public function getParentTaskPairs($executionID, $appendIdList = '', $taskID = 0)
{
    return $this->loadExtension('feedback')->getParentTaskPairs($executionID, $appendIdList, $taskID);
}
