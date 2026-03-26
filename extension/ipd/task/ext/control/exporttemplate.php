<?php
helper::importControl('task');
class mytask extends task
{
    public function exportTemplate($executionID)
    {
        if($_POST)
        {
            $execution = $this->loadModel('execution')->getByID($executionID);
            $project   = $this->loadModel('project')->getByID($execution->project);

            $this->config->task->create->requiredFields = str_replace('execution,', '', $this->config->task->create->requiredFields);
            $this->post->set('execution', $execution->name);
            if(!$execution->multiple) $this->post->set('project', $project->name);

            $this->session->set('taskTransferParams', array('executionID' => $executionID));
            $this->fetch('transfer', 'exportTemplate', 'model=task&params=executionID='. $executionID);
        }

        $this->loadModel('transfer');
        $this->display();
    }
}
