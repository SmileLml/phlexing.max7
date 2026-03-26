<?php
/**
 * Add task estimate.
 *
 * @param  object   $data
 * @access public
 * @return int
 */
public function addTaskEffort($data)
{
    return $this->loadExtension('effort')->addTaskEffort($data);
}


/**
 * Get taskList date record.
 *
 * @param  int|array $taskID
 * @access public
 * @return array
 */
public function getTaskDateRecord($taskID)
{
    return $this->loadExtension('effort')->getTaskDateRecord($taskID);
}
