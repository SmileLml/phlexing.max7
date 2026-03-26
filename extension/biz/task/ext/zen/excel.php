<?php
/**
 * 为批量导入任务构造数据。
 * Build tasks for the batch import.
 *
 * @param  int    $executionID
 * @access public
 * @return array
 */
public function buildTasksForImport($executionID)
{
    $now  = helper::now();
    $data = fixer::input('post')->get();
    if(empty($data->team)) $data->team = array();

    $tasks        = array();
    $line         = 1;
    $extendFields = array();

    $requiredFields = $this->config->task->create->requiredFields;
    if($this->config->edition != 'open')
    {
        $extendFields = $this->task->getFlowExtendFields();
        $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

        foreach($extendFields as $extendField)
        {
            if(strpos(",$extendField->rules,", ",$notEmptyRule->id,") !== false) $requiredFields .= ',' . $extendField->field;
        }
    }
    $requiredFields = explode(',', $requiredFields);

    $execution = $this->dao->select('id,project,type,model,taskDateLimit,isTpl')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch();
    if($execution->type == 'project')
    {
        $projectID = $executionID;
        $project   = $execution;
    }
    else
    {
        $projectID = $execution->project;
        $project   = $this->dao->select('id,taskDateLimit')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
    }

    foreach($data->name as $key => $name)
    {
        $name = str_replace('[' . $this->lang->task->multipleAB . '] ', '', $name);
        $name = str_replace('[' . $this->lang->task->parentAB . '] ',   '', $name);
        $name = str_replace('[' . $this->lang->task->childrenAB . '] ', '', $name);
        $name = trim($name);
        if(empty($name)) continue;

        $taskData = new stdclass();
        $taskData->project    = $projectID;
        $taskData->execution  = zget(zget($data, 'execution', array()), $key, $executionID);
        $taskData->module     = (int)$data->module[$key];
        $taskData->name       = trim($name);
        $taskData->desc       = nl2br(strip_tags($this->post->desc[$key], $this->config->allowedTags !== null && is_array($this->config->allowedTags) ? '<' . implode('><', $this->config->allowedTags) . '>' : $this->config->allowedTags));
        $taskData->story      = isset($data->story) ? (int)$data->story[$key] : 0;
        $taskData->pri        = (int)$data->pri[$key];
        $taskData->assignedTo = $data->assignedTo[$key];
        $taskData->type       = $data->type[$key];
        $taskData->estimate   = (float)$data->estimate[$key];
        $taskData->estStarted = !empty($data->estStarted[$key]) ? $data->estStarted[$key] : null;
        $taskData->deadline   = !empty($data->deadline[$key]) ? $data->deadline[$key] : null;
        $taskData->mode       = $data->mode[$key];
        $taskData->isTpl      = $execution->isTpl;
        if($execution->type != 'project') $taskData->execution    = $executionID;
        if(!empty($taskData->assignedTo)) $taskData->assignedDate = $now;

        foreach($extendFields as $extendField)
        {
            $dataArray = $_POST[$extendField->field];
            $taskData->{$extendField->field} = $dataArray[$key];
            if(is_array($taskData->{$extendField->field})) $taskData->{$extendField->field} = join(',', $taskData->{$extendField->field});

            $taskData->{$extendField->field} = htmlSpecialString($taskData->{$extendField->field});
        }

        foreach($requiredFields as $requiredField)
        {
            $requiredField = trim($requiredField);
            if(!empty($requiredField) && empty($taskData->$requiredField)) dao::$errors["{$requiredField}[{$key}]"] = sprintf($this->lang->task->noRequire, $line, $this->lang->task->$requiredField);
        }

        if(!empty($this->config->limitTaskDate)) $this->task->checkEstStartedAndDeadline($taskData->execution, (string)$taskData->estStarted, (string)$taskData->deadline, $key);

        $tasks[$key] = $taskData;
        $line++;
    }

    $parents = $this->getParentForImport($tasks);
    foreach($tasks as $key => $taskData) $this->checkLegallyDate($taskData, $project->taskDateLimit == 'limit', zget($parents, $key, null), $key);

    return $tasks;
}

/**
 * 获取父任务的开始和截止日期。
 * Get the start and end dates of the parent task.
 *
 * @param  array     $tasks
 * @access protected
 * @return array
 */
protected function getParentForImport($tasks)
{
    if(!isset($_POST['level'])) return array();

    $levelList = array();
    $parents   = array();
    $pathPairs = array();
    $idMap     = array();
    if(!empty($_POST['id']))
    {
        $pathPairs = $this->dao->select('id,parent,path')->from(TABLE_TASK)->where('id')->in(($_POST['id']))->fetchPairs('id', 'path');
        $idMap     = array_flip($this->post->id);
    }

    $allTasks = $this->dao->select('id,execution,parent,path,estStarted,deadline')->from(TABLE_TASK)->where('id')->in(array_filter(array_unique(explode(',', implode(',', $pathPairs)))))->fetchAll('id');
    foreach($this->post->level as $key => $level)
    {
        $level = trim($level);
        if($level && preg_match('/^\d+(\.\d+)*$/', $level)) $levelList[$level] = $key;

        $parentTask = null;
        if($level && strrpos($level, '.') !== false)
        {
            $parentTask = new stdClass();
            $parentTask->estStarted = null;
            $parentTask->deadline   = null;
            while(strrpos($level, '.') !== false)
            {
                $parentLevel = substr($level, 0, strrpos($level, '.'));
                if(isset($levelList[$parentLevel]))
                {
                    $task = $tasks[$levelList[$parentLevel]];
                    $parentTask->execution = $task->execution;
                    if(empty($parentTask->estStarted)  && !helper::isZeroDate($task->estStarted)) $parentTask->estStarted = $task->estStarted;
                    if(empty($parentTask->deadline)    && !helper::isZeroDate($task->deadline))   $parentTask->deadline   = $task->deadline;
                    if(!empty($parentTask->estStarted) && !empty($parentTask->deadline)) break;
                }

                $level = $parentLevel;
            }
        }
        elseif(!empty($_POST['id'][$key]) && !$this->post->insert)
        {
            $path = $pathPairs[$this->post->id[$key]];
            $path = array_reverse(array_filter(explode(',', $path)));
            array_shift($path);
            if(empty($path)) continue;

            $parentTask = new stdClass();
            $parentTask->estStarted = null;
            $parentTask->deadline   = null;

            $rootID = end($path);
            $root   = $allTasks[$rootID];
            if(isset($idMap[$rootID])) $root = $tasks[$idMap[$rootID]];
            $parentTask->execution = $root->execution;

            foreach($path as $taskID)
            {
                $task = $allTasks[$taskID];
                if(isset($idMap[$taskID])) $task = $tasks[$idMap[$taskID]];

                if(empty($parentTask->estStarted)  && !helper::isZeroDate($task->estStarted)) $parentTask->estStarted = $task->estStarted;
                if(empty($parentTask->deadline)    && !helper::isZeroDate($task->deadline))   $parentTask->deadline   = $task->deadline;
                if(!empty($parentTask->estStarted) && !empty($parentTask->deadline)) break;
            }
        }
        $parents[$key] = $parentTask;

        $currentTask = $tasks[$key];
        if(!empty($parentTask->execution) && $currentTask->execution != $parentTask->execution)
        {
            $currentTask->execution = $parentTask->execution;
            $currentTask->module    = $parentTask->module;
            $currentTask->story     = $parentTask->story;
        }
    }
    return $parents;
}
