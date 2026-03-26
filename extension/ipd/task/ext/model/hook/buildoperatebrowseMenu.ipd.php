<?php
if(!empty($task->confirmeObject))
{
    $mothed = $task->confirmeObject['type'] == 'confirmedretract' ? 'confirmDemandRetract' : 'confirmDemandUnlink';
    return $this->buildMenu('task', $mothed, "objectID=$task->id&object=task&extra={$task->confirmeObject['id']}", $task, 'view', 'search', '', 'iframe', true);
}
