<?php
/* 如果状态变更为已取消，需要解除任务关联关系。 */
/* 如果有子任务，需要解除子任务关联关系。*/
if($oldTask->status != 'cancel' && $task->status == 'cancel')
{
    $childTasks = array();
    if($task->parent == '-1') $childTasks = $this->dao->select('id')->from(TABLE_TASK)->where('parent')->eq($task->id)->fetchPairs('id');
    $this->loadExtension('gantt')->unlinkRelation($task->id, $childTasks);
}

/* 如果父任务有关联关系，需要解除父任务关联关系。 */
if(isset($task->parent) && $task->parent > 0 && $task->parent != $oldTask->parent)
{
    $this->loadExtension('gantt')->unlinkRelation($task->parent);
}
