<?php
/**
 * The model file of researchtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hucheng Tang<tanghucheng@easycorp.ltd>
 * @package     researchtask
 * @link        https://www.zentao.net
 */
class researchtaskModel extends Model
{
    /**
     * Readjust task.
     *
     * @param  object $oldProject
     * @param  object $project
     * @param  string $type project|execution
     * @access public
     * @return void
     */
    public function readjustTask($oldProject, $project, $type)
    {
        $beginTimeStamp = strtotime($project->begin);
        $tasks = $this->dao->select('id,estStarted,deadline,status')->from(TABLE_TASK)
            ->where('deadline')->notZeroDate()
            ->andWhere('status')->in('wait,doing')
            ->beginIF($type == 'project')->andWhere('project')->eq($project->id)->fi()
            ->beginIF($type == 'execution')->andWhere('execution')->eq($project->id)->fi()
            ->fetchAll();
        foreach($tasks as $task)
        {
            if($task->status == 'wait' and !helper::isZeroDate($task->estStarted))
            {
                $taskDays   = helper::diffDate($task->deadline, $task->estStarted);
                $taskOffset = helper::diffDate($task->estStarted, $oldProject->begin);

                $estStartedTimeStamp = $beginTimeStamp + $taskOffset * 24 * 3600;
                $estStarted = date('Y-m-d', $estStartedTimeStamp);
                $deadline   = date('Y-m-d', $estStartedTimeStamp + $taskDays * 24 * 3600);

                if($estStarted > $project->end) $estStarted = $project->end;
                if($deadline > $project->end)   $deadline   = $project->end;
                $this->dao->update(TABLE_TASK)->set('estStarted')->eq($estStarted)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
            }
            else
            {
                $taskOffset = helper::diffDate($task->deadline, $oldProject->begin);
                $deadline   = date('Y-m-d', $beginTimeStamp + $taskOffset * 24 * 3600);

                if($deadline > $project->end) $deadline = $project->end;
                $this->dao->update(TABLE_TASK)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
            }
        }
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object    $task
     * @param  string    $action
     * @access public
     * @return bool
     */
    public static function isClickable($object, $action)
    {
        $action = strtolower($action);
        $parent = isset($object->rawParent) ? $object->rawParent : $object->parent;

        if($action == 'start'          && $parent < 0) return false;
        if($action == 'finish'         && $parent < 0) return false;
        if($action == 'pause'          && $parent < 0) return false;
        if($action == 'assignto'       && $parent < 0) return false;
        if($action == 'close'          && $parent < 0) return false;
        if($action == 'batchcreate'    && !empty($obejct->team)) return false;
        if($action == 'batchcreate'    && $parent > 0) return false;
        if($action == 'recordWorkhour' && $parent < 0) return false;
        if($action == 'delete'         && $parent < 0) return false;

        if($action == 'start')    return $object->status == 'wait';
        if($action == 'restart')  return $object->status == 'pause';
        if($action == 'pause')    return $object->status == 'doing';
        if($action == 'assignto') return $object->status != 'closed' && $object->status != 'cancel';
        if($action == 'close')    return $object->status == 'done'   || $object->status == 'cancel';
        if($action == 'activate') return $object->status == 'done'   || $object->status == 'closed'  || $object->status == 'cancel';
        if($action == 'finish')   return $object->status != 'done'   && $object->status != 'closed'  && $object->status != 'cancel';
        if($action == 'cancel')   return $object->status != 'done'   && $object->status != 'closed'  && $object->status != 'cancel';

        if($action == 'create' && isset($object->type) && $object->type == 'stage') return $object->canCreateTask;

        return true;
    }

    /**
     * Create a task.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function create($executionID, $researchID = 0)
    {
        $this->loadModel('task');
        if((float)$this->post->estimate < 0)
        {
            dao::$errors[] = $this->lang->task->error->recordMinus;
            return false;
        }

        $executionID    = (int)$executionID;
        $assignedTo     = '';
        $taskIdList     = array();
        $taskFiles      = array();
        $requiredFields = "," . $this->config->task->create->requiredFields . ",";

        $this->loadModel('file');
        $task = fixer::input('post')
            ->setDefault('execution', $executionID)
            ->setDefault('estimate,left,story', 0)
            ->setDefault('status', 'wait')
            ->setDefault('project', $researchID)
            ->setIF($this->post->estimate != false, 'left', $this->post->estimate)
            ->setIF(strpos($requiredFields, 'estStarted') !== false, 'estStarted', helper::isZeroDate($this->post->estStarted) ? '' : $this->post->estStarted)
            ->setIF(strpos($requiredFields, 'deadline') !== false, 'deadline', helper::isZeroDate($this->post->deadline) ? '' : $this->post->deadline)
            ->setIF(strpos($requiredFields, 'estimate') !== false, 'estimate', $this->post->estimate)
            ->setIF(strpos($requiredFields, 'left') !== false, 'left', $this->post->left)
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->setIF(is_numeric($this->post->consumed), 'consumed', (float)$this->post->consumed)
            ->setIF(is_numeric($this->post->left),     'left',     (float)$this->post->left)
            ->setIF(!$this->post->estStarted, 'estStarted', null)
            ->setIF(!$this->post->deadline, 'deadline', null)
            ->setDefault('openedBy',   $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('vision', $this->config->vision)
            ->cleanINT('execution,story,module')
            ->stripTags($this->config->task->editor->create['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->remove('after,files,labels,assignedTo,uid,storyEstimate,storyDesc,storyPri,team,teamSource,teamEstimate,teamConsumed,teamLeft,teamMember,multiple,teams,contactList,selectTestStory,testStory,testPri,testEstStarted,testDeadline,testAssignedTo,testEstimate,sync,otherLane,region,lane,estStartedDitto,deadlineDitto')
            ->add('version', 1)
            ->get();

        foreach($this->post->assignedTo as $assignedTo)
        {
            $task->assignedTo = $assignedTo;
            if($assignedTo) $task->assignedDate = helper::now();

            $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->create['id'], $this->post->uid);

            if(strpos($requiredFields, ',estimate,') !== false)
            {
                if(strlen(trim($task->estimate)) == 0) dao::$errors['estimate'] = sprintf($this->lang->error->notempty, $this->lang->task->estimate);
                $requiredFields = str_replace(',estimate,', ',', $requiredFields);
            }

            if(strpos($requiredFields, ',estStarted,') !== false and !isset($task->estStarted)) dao::$errors['estStarted'] = sprintf($this->lang->error->notempty, $this->lang->task->estStarted);
            if(strpos($requiredFields, ',deadline,') !== false and !isset($task->deadline)) dao::$errors['deadline'] = sprintf($this->lang->error->notempty, $this->lang->task->deadline);
            if(isset($task->estStarted) and isset($task->deadline) and !helper::isZeroDate($task->deadline) and $task->deadline < $task->estStarted) dao::$errors['deadline'] = sprintf($this->lang->error->ge, $this->lang->task->deadline, $task->estStarted);

            if(dao::isError()) return false;

            $requiredFields = trim($requiredFields, ',');

            /* Replace the error tip when execution is empty. */
            $this->lang->task->execution = $this->lang->researchtask->execution;

            $this->dao->insert(TABLE_TASK)->data($task, $skip = 'gitlab,gitlabProject')
                ->autoCheck()
                ->batchCheck($requiredFields, 'notempty')
                ->checkIF($task->estimate != '', 'estimate', 'float')
                ->checkIF(!helper::isZeroDate($task->deadline), 'deadline', 'ge', $task->estStarted)
                ->checkFlow()
                ->exec();
            if(dao::isError()) return false;

            $taskID = $this->dao->lastInsertID();

            $this->dao->update(TABLE_TASK)->set('path')->eq(",$taskID,")->where('id')->eq($taskID)->exec();

            $taskSpec = new stdClass();
            $taskSpec->task       = $taskID;
            $taskSpec->version    = $task->version;
            $taskSpec->name       = $task->name;

            if($task->estStarted) $taskSpec->estStarted = $task->estStarted;
            if($task->deadline)   $taskSpec->deadline   = $task->deadline;

            $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
            if(dao::isError()) return false;

            $this->file->updateObjectID($this->post->uid, $taskID, 'task');
            if(!empty($taskFiles))
            {
                foreach($taskFiles as $taskFile)
                {
                    $taskFile->objectID = $taskID;
                    $this->dao->insert(TABLE_FILE)->data($taskFile)->exec();
                }
            }
            else
            {
                $taskFileTitle = $this->file->saveUpload('task', $taskID);
                $taskFiles     = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in(array_keys($taskFileTitle))->fetchAll('id');
                foreach($taskFiles as $fileID => $taskFile) unset($taskFiles[$fileID]->id);
            }

            if(!dao::isError()) $this->loadModel('score')->create('task', 'create', $taskID);
            $taskIdList[$assignedTo] = array('status' => 'created', 'id' => $taskID);
        }
        return $taskIdList;
    }
}
