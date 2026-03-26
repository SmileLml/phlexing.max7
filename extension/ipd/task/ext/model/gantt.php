<?php
/**
 * @return false|mixed[]
 * @param object $oldTask
 * @param object $task
 */
public function start($oldTask, $task)
{
    return $this->loadExtension('gantt')->start($oldTask, $task);
}

/**
 * @return bool|mixed[]
 * @param object $oldTask
 * @param object $task
 */
public function finish($oldTask, $task)
{
    return $this->loadExtension('gantt')->finish($oldTask, $task);
}

public function checkDepend($taskID, $action = 'begin')
{
    return $this->loadExtension('gantt')->checkDepend($taskID, $action);
}

/**
 * @return false|mixed[]
 * @param object $task
 * @param mixed[] $workhour
 */
public function checkWorkhour($task, $workhour)
{
    return $this->loadExtension('gantt')->checkWorkhour($task, $workhour);
}

/**
 * @param object $oldTask
 * @param object $task
 * @param mixed[] $output
 */
public function cancel($oldTask, $task, $output = array())
{
    return $this->loadExtension('gantt')->cancel($oldTask, $task, $output);
}

/**
 * @param mixed[] $tasks
 * @param mixed[] $oldTasks
 */
public function afterBatchUpdate($tasks, $oldTasks = array())
{
    return $this->loadExtension('gantt')->afterBatchUpdate($tasks, $oldTasks);
}
