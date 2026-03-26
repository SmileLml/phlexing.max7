<?php
helper::importControl('task');
class mytask extends task
{
    /**
     * Show import of task template.
     *
     * @param  int    $executionID
     * @param  int    $pagerID
     * @param  string $insert
     * @access public
     * @return void
     */
    public function showImport($executionID, $pagerID = 1, $insert = '')
    {
        $this->loadModel('execution')->setMenu($executionID);

        /* Set datasource params for initfieldsList.*/
        $this->session->set('taskTransferParams', array('executionID' => $executionID));
        $this->loadModel('transfer');

        $this->config->task->dtable->fieldList['execution']['control'] = 'picker';
        $this->config->task->dtable->fieldList['execution']['hidden']  = true;
        $this->config->task->dtable->fieldList['mode']['control']      = 'picker';
        $this->config->task->dtable->fieldList['mode']['hidden']       = true;
        $this->config->task->dtable->fieldList['estimate']['width']    = '160px';

        if($_POST)
        {
            $tasks = $this->taskZen->buildTasksForImport($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $taskIDList = $this->task->createFromImport($tasks);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->lang->saveSuccess;
            foreach($taskIDList as $taskID) $message = $this->executeHooks($taskID);

            $execution = $this->execution->fetchById($executionID);
            $locate    = $this->createLink($execution->type == 'project' ? 'programplan' : 'task', 'showImport', "executionID=$executionID&pagerID=" . ($this->post->pagerID + 1) . "&insert=" . zget($_POST, 'insert', ''));
            if($this->post->isEndPage)
            {
                $locate = $this->createLink('execution','task', "executionID={$executionID}");
                if($execution->type == 'project')
                {
                    $locate = $this->createLink('project','execution', "status=undone&projectID={$executionID}");
                    if(in_array($this->config->edition, array('max', 'ipd')) && common::hasPriv('programplan', 'browse')) $locate = $this->createLink('programplan','browse', "projectID={$executionID}");
                }
            }
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => $locate));
        }

        /* Get page by datas.*/
        $taskData = $this->transfer->readExcel('task', $pagerID, $insert, 'estimate');
        if(empty($taskData)) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->task->noExcelData)));

        $datas = array();
        foreach($taskData->datas as $key => $data)
        {
            $taskID = !empty($data->id) ? $data->id : $key;

            preg_match_all("/^([^:]+):([^:]+)$/m", $data->estimate, $matches);
            if(!empty($matches[1])) $data->team = $matches[1];
            if(!empty($matches[2]))
            {
                foreach($matches[2] as $key => $value) $matches[2][$key] = (float) $value;
                $data->estimate = $matches[2];
            }

            $data->execution = $executionID;
            $datas[$taskID]  = $data;
        }
        $taskData->datas = $datas;

        $taskIdList        = array_filter(array_column($taskData->datas, 'id', 'id'));
        $parentIdList      = array();
        $oldTasks          = array();
        $childrenDateLimit = array();
        if($taskIdList)
        {
            $oldTasks = $this->loadModel('task')->getByIdList($taskIdList);
            $parentIdList = array_column($oldTasks, 'parent', 'parent');

            list($childTasks) = $this->task->getChildTasksByList($taskIdList);
            foreach($childTasks as $parent => $children)
            {
                $childDateLimit = array('estStarted' => '', 'deadline' => '');
                foreach($children as $child)
                {
                    if(!helper::isZeroDate($child->estStarted) && (empty($childDateLimit['estStarted']) || $childDateLimit['estStarted'] > $child->estStarted)) $childDateLimit['estStarted'] = $child->estStarted;
                    if(!helper::isZeroDate($child->deadline)   && (empty($childDateLimit['deadline'])   || $childDateLimit['deadline']   < $child->deadline))   $childDateLimit['deadline']   = $child->deadline;
                }
                $childrenDateLimit[$parent] = $childDateLimit;
            }
        }

        $execution = $this->execution->fetchById($executionID);
        if(!$execution->multiple) $this->view->projectID = $executionID;

        $this->view->title             = $this->lang->task->common . $this->lang->hyphen . $this->lang->task->showImport;
        $this->view->datas             = $taskData;
        $this->view->childrenDateLimit = $childrenDateLimit;
        $this->view->oldTasks          = $oldTasks;
        $this->view->parentTasks       = $this->task->getByIdList($parentIdList);
        $this->view->backLink          = $this->createLink('execution', 'task', "executionID={$executionID}");
        $this->view->executionID       = $executionID;
        $this->view->project           = $this->loadModel('project')->fetchById($execution->project);

        if($this->config->vision == 'lite') $this->view->projectID = $executionID;

        $this->display();
    }
}
