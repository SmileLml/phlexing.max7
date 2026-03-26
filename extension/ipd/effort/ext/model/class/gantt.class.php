<?php
class ganttEffort extends effortModel
{
    public function batchCreate()
    {
        $taskIdList = array();
        $lefts      = array();
        foreach($this->post->objectType as $i => $objectType)
        {
            if(empty($this->post->work[$i])) continue;

            if($objectType == 'task')
            {
                $objectID = $this->post->objectID[$i];
                $taskIdList[$objectID] = $objectID;
                $lefts[$objectID]      = $this->post->left[$i];
            }
        }

        $this->loadModel('task');
        $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($taskIdList)->fetchAll('id');
        foreach($tasks as $task)
        {
            if($task->status == 'wait')
            {
                $message = $this->task->checkDepend($task->id, 'begin');
                if($message) die(js::alert($message));
            }
            if(isset($lefts[$task->id]) and $lefts[$task->id] == 0 and strpos('done,cancel,closed', $task->status) === false)
            {
                $message = $this->task->checkDepend($task->id, 'end');
                if($message) die(js::alert($message));
            }
        }

        return parent::batchCreate();
    }
}
