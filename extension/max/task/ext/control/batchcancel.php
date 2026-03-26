<?php
class myTask extends task
{
    /**
     * 批量取消任务。
     * Batch cancel tasks.
     *
     * @access public
     * @return void
     * @param string $confirm
     */
    public function batchCancel($confirm = 'no')
    {
        if($this->post->taskIdList)
        {
            $taskIdList = array_unique($this->post->taskIdList);

            $taskRelations = $this->dao->select('id')->from(TABLE_RELATIONOFTASKS)->where('pretask')->in($taskIdList)->orWhere('task')->in($taskIdList)->fetchPairs('id');

            if($taskRelations && $confirm == 'no')
            {
                $confirmURL = $this->createLink('task', 'batchCancel', "confirm=yes");
                $postData   = str_replace('taskIdList', 'taskIdList[]', json_encode($_POST));
                return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$this->lang->task->unlinkRelationTip->cancel}', actions: [{key: 'confirm', text: '{$this->lang->task->unlink}', btnType: 'primary', class: 'btn-wide'}, {key: 'cancel'}]}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL', data: $postData});});"));
            }

            $tasks = $this->task->getByIdList($taskIdList);
            foreach($tasks as $task)
            {
                if(!in_array($task->status, $this->config->task->unfinishedStatus)) continue;

                $taskData = $this->buildTaskForCancel($task);
                $this->task->cancel($task, $taskData);
            }
        }

        return $this->send(array('result' => 'success', 'load' => true));
    }
}
