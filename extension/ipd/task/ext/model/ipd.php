<?php
public function buildOperateViewMenu($task)
{
    if(!empty($task->confirmeObject))
    {
        $mothed = $task->confirmeObject['type'] == 'confirmedretract' ? 'confirmDemandRetract' : 'confirmDemandUnlink';
        return $this->buildMenu('task', $mothed, "objectID=$task->id&object=task&extra={$task->confirmeObject['id']}", $task, 'view', 'search', '', 'iframe', true);
    }
    return parent::buildOperateViewMenu($task);
}
