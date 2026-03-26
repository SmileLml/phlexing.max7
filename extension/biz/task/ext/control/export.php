<?php
helper::importControl('task');
class mytask extends task
{
    /**
     * @param int $executionID
     * @param string $orderBy
     * @param string $type
     */
    public function export($executionID, $orderBy, $type)
    {
        $this->loadModel('transfer');
        $this->session->set('taskTransferParams', array('executionID' => $executionID));

        $execution = $this->execution->getById($executionID);

        if(!$execution->multiple) $this->config->task->exportFields = str_replace('execution,', 'project,', $this->config->task->exportFields);

        $allExportFields = $this->config->task->exportFields;
        if($execution->type == 'ops' or in_array($execution->attribute, array('request', 'review'))) $allExportFields = str_replace(' story,', '', $allExportFields);

        if($_POST)
        {
            if($type == 'bysearch') $this->config->task->dtable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getAllModulePairs');
            if($this->post->excel == 'excel')
            {
                /* Get users and executions. */
                $users = $this->loadModel('user')->getPairs('noletter');
                $trees = $this->execution->getTree($this->post->executionID);
                $trees = $this->treeToList($trees, $users);

                $this->post->set('exportFields', array('id','title','startTime','assignedTo','pri'));
                $this->post->set('fileType', 'xlsx');
                $this->post->set('fields', $this->lang->task->field);
                $this->post->set('rows', $trees);
                $this->post->set('kind', 'tree');
                unset($_POST['moduleList']);
                unset($_POST['storyList']);
                unset($_POST['priList']);
                unset($_POST['typeList']);
                unset($_POST['listStyle']);
                unset($_POST['extraNum']);
                unset($_POST['excel']);
                $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
            }

            $this->session->set('taskTransferParams', array('executionID' => $executionID));

            /* Get tasks. */
            $tasks = $this->transfer->getQueryDatas('task');
            $tasks = $this->processExportData($tasks, $execution->project);
            $tasks = $this->task->processExportTasks($tasks);
            $tasks = array_combine(array_column($tasks, 'id'), $tasks);

            /* Get related bug. */
            $relatedBugIdList = array();
            foreach($tasks as $task) $relatedBugIdList[$task->fromBug] = $task->fromBug;
            $bugs = $this->loadModel('bug')->getByIdList($relatedBugIdList);
            foreach($tasks as $task) $task->fromBug = empty($task->fromBug) ? '' : "#$task->fromBug " . $bugs[$task->fromBug]->title;

            $this->post->set('rows', $tasks);
            $this->loadModel('file');
            $this->fetch('transfer', 'export', 'model=task&params=executionID=' . $executionID);
        }

        $this->app->loadLang('execution');
        $fileName = $this->lang->task->common;
        $executionName = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch('name');
        if(isset($this->lang->execution->featureBar['task'][$type]))
        {
            $browseType = $this->lang->execution->featureBar['task'][$type];
        }
        else
        {
            $browseType = isset($this->lang->execution->moreSelects['task']['status'][$type]) ? $this->lang->execution->moreSelects['task']['status'][$type] : '';
        }

        $this->view->fileName        = $executionName . $this->lang->dash . $browseType . $fileName;
        $this->view->allExportFields = $allExportFields;
        $this->view->customExport    = true;
        $this->view->orderBy         = $orderBy;
        $this->view->type            = $type;
        $this->view->executionID     = $executionID;
        $this->view->execution       = $execution;
        $this->display();
    }

    /**
     * Tree to list.
     *
     * @param  int    $trees
     * @param  int    $users
     * @access public
     * @return void
     */
    public function treeToList($trees, $users)
    {
        foreach($trees as $task)
        {
            $prefix = '';
            $row    = new stdclass();
            if($task->type == 'module') $prefix = "[{$this->lang->task->moduleAB}] ";
            if($task->type == 'product') $prefix = "[{$this->lang->productCommon}] ";
            if($task->type == 'story') $prefix = "[{$this->lang->task->storyAB}] ";
            if($task->type == 'branch')
            {
                $this->app->loadLang('branch');
                $prefix = "[{$this->lang->branch->common}] ";
            }
            if($task->type == 'task')
            {
                $prefix = "[{$this->lang->task->common}] ";
                if($task->parent > 0 && !$task->isParent) $prefix = "[{$this->lang->task->children}] ";
                if($task->isParent) $prefix = "[{$this->lang->task->parent}] ";

                $row->startTime = !helper::isZeroDate($task->realStarted) ? $task->realStarted : (!helper::isZeroDate($task->estStarted) ? $task->estStarted : '');
            }

            if($task->type == 'task' or $task->type == 'story')
            {
                $row->id         = $task->id;
                $row->title      = $prefix . $task->title;
                $row->assignedTo = zget($users, $task->assignedTo);
                $row->pri        = $task->pri;
            }
            else
            {
                $row->title = $prefix . $task->name;
            }

            $this->tasks[] = $row;
            if(!empty($task->children)) $this->treeToList($task->children, $users);
        }

        return $this->tasks;
    }
}
