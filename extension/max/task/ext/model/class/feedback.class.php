<?php
class feedbackTask extends taskModel
{
    /**
     * 创建任务后的其他数据处理。
     * Other data process after task create.
     *
     * @param  object $task
     * @param  array  $taskIdList
     * @param  int    $bugID
     * @param  int    $todoID
     * @access public
     * @return bool
     */
    public function afterCreate($task, $taskIdList, $bugID, $todoID)
    {
        if(!empty($task->docs))
        {
            $docList = $this->dao->select('id,version')->from(TABLE_DOC)->where('id')->in($task->docs)->fetchPairs();
            foreach($taskIdList as $taskID)
            {
                $docVersions = array();
                foreach(explode(',', $task->docs) as $docID)
                {
                    $docVersions[$docID] = $docList[$docID];

                    $relation = new stdClass();
                    $relation->relation = 'interrated';
                    $relation->AID      = $taskID;
                    $relation->AType    = 'task';
                    $relation->BID      = $docID;
                    $relation->BType    = 'doc';
                    $relation->product  = 0;
                    $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
                }

                $this->dao->update(TABLE_TASK)->set('docVersions')->eq(json_encode($docVersions))->where('id')->eq($taskID)->exec();
            }
        }
        $feedbackID = $this->post->feedback;
        if(empty($feedbackID))
        {
            $result = parent::afterCreate($task, $taskIdList, $bugID, $todoID);
            if(!$result) return $result;

            foreach($taskIdList as $taskID)
            {
                if($bugID <= 0 && empty($task->story) && empty($task->design)) continue;

                $relation = new stdClass();
                $relation->BID     = $taskID;
                $relation->BType   = 'task';
                $relation->product = 0;
                if($bugID > 0)
                {
                    $relation->AID      = $bugID;
                    $relation->AType    = 'bug';
                    $relation->relation = 'transferredto';
                }
                if(!empty($task->story))
                {
                    $relation->AID      = $task->story;
                    $relation->AType    = 'story';
                    $relation->relation = 'generated';
                }
                if(!empty($task->design))
                {
                    $relation->AID      = $task->design;
                    $relation->AType    = 'design';
                    $relation->relation = 'generated';
                }
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
            return $result;
        }

        $this->loadModel('file');
        $this->loadModel('action');
        $this->setTaskFiles($taskIdList);

        $fileIDPairs = $this->loadModel('file')->copyObjectFiles('task');

        $this->dao->update(TABLE_TASK)->set('feedback')->eq($feedbackID)->where('id')->in($taskIdList)->exec();
        $oldFeedback = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->eq($feedbackID)->fetch();
        foreach($taskIdList as $taskID)
        {
            /* If the task comes from a story, update the stage of the story. */
            if($task->story) $this->loadModel('story')->setStage($task->story);

            $feedback = new stdclass();
            $feedback->status        = 'commenting';
            $feedback->result        = $taskID;
            $feedback->processedBy   = $this->app->user->account;
            $feedback->processedDate = helper::now();
            $feedback->solution      = 'totask';
            $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($feedbackID)->exec();

            $this->action->create('feedback', $feedbackID, 'ToTask', '', $taskID);
            if($oldFeedback->status != 'commenting') $this->action->create('feedback', $feedbackID, 'syncDoingByTask', '', $taskID);
            $this->dao->update(TABLE_ACTION)
                ->set('action')->eq('fromfeedback')
                ->set('extra')->eq($feedbackID)
                ->where('objectType')->eq('task')
                ->andWhere('objectID')->eq($taskID)
                ->andWhere('action')->eq('opened')
                ->exec();

            $relation = new stdClass();
            $relation->AType    = 'feedback';
            $relation->AID      = $feedbackID;
            $relation->relation = 'transferredto';
            $relation->BType    = 'task';
            $relation->BID      = $taskID;
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

            if(!empty($fileIDPairs)) $this->dao->update(TABLE_FILE)->set('objectID')->eq($taskID)->where('id')->in($fileIDPairs)->exec();
            $this->loadModel('feedback')->updateSubStatus($feedbackID, $feedback->status);
        }

        return !dao::isError();
    }

    /**
     * 编辑任务后的其他数据处理：需求与任务的关联关系。
     * Additional data processing after updating tasks: story generated task.
     *
     * @param  object $oldTask
     * @param  object $task
     * @access public
     * @return void
     */
    public function afterUpdate($oldTask, $task)
    {
        $this->dao->delete()->from(TABLE_RELATION)->where('relation')->eq('interrated')->andWhere('AID')->eq($task->id)->andWhere('AType')->eq('task')->andWhere('BType')->eq('doc')->exec();

        $docVersions = array();
        $taskDocs    = !empty($task->docs) ? explode(',', $task->docs) : array();
        if(!empty($task->oldDocs)) $taskDocs = array_merge($taskDocs, $task->oldDocs);
        if($taskDocs)
        {
            $docList     = $this->dao->select('id,version')->from(TABLE_DOC)->where('id')->in($taskDocs)->fetchPairs();
            $docVersions = array();
            foreach($taskDocs as $docID)
            {
                $docVersions[$docID] = !empty($task->docVersions[$docID]) ? $task->docVersions[$docID] : $docList[$docID];

                $relation = new stdClass();
                $relation->relation = 'interrated';
                $relation->AID      = $task->id;
                $relation->AType    = 'task';
                $relation->BID      = $docID;
                $relation->BType    = 'doc';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }

        $this->dao->update(TABLE_TASK)
            ->set('docs')->eq(implode(',', $taskDocs))
            ->set('docVersions')->eq(json_encode($docVersions))
            ->where('id')->eq($task->id)
            ->exec();

        $task->docs        = implode(',', $taskDocs);
        $task->docVersions = json_encode($docVersions);

        parent::afterUpdate($oldTask, $task);
        if($task->story != $oldTask->story)
        {
            if($oldTask->story > 0)
            {
                $this->dao->delete()->from(TABLE_RELATION)
                    ->where('relation')->eq('generated')
                    ->andWhere('AID')->eq($oldTask->story)
                    ->andWhere('AType')->eq('story')
                    ->andWhere('BID')->eq($oldTask->id)
                    ->andWhere('BType')->eq('task')
                    ->exec();
            }
            if($task->story > 0)
            {
                $relation = new stdClass();
                $relation->AID      = $task->story;
                $relation->AType    = 'story';
                $relation->relation = 'generated';
                $relation->BID      = $oldTask->id;
                $relation->BType    = 'task';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }
        if($task->design != $oldTask->design)
        {
            if($oldTask->design > 0)
            {
                $this->dao->delete()->from(TABLE_RELATION)
                    ->where('relation')->eq('generated')
                    ->andWhere('AID')->eq($oldTask->design)
                    ->andWhere('AType')->eq('design')
                    ->andWhere('BID')->eq($oldTask->id)
                    ->andWhere('BType')->eq('task')
                    ->exec();
            }
            if($task->design > 0)
            {
                $relation = new stdClass();
                $relation->AID      = $task->design;
                $relation->AType    = 'design';
                $relation->relation = 'generated';
                $relation->BID      = $oldTask->id;
                $relation->BType    = 'task';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }
    }

    /**
    * 获取父任务 id:name 的数组。
    * Get an array of parent task id:name.
    *
    * @param  int    $executionID
    * @param  string $appendIdList
    * @param  int    $taskID
    * @access public
    * @return array
    */
    public function getParentTaskPairs($executionID, $appendIdList = '', $taskID = 0)
    {
        /*
         过滤多人任务。
         过滤已经记录工时的任务。
         过滤已取消、已关闭的任务。
         过滤自己和后代任务。
        */
        $children = $this->getAllChildId($taskID);
        $taskList = $this->dao->select('id, name, isParent, consumed')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('status')->notin('cancel,closed')
            ->andWhere('execution')->eq($executionID)
            ->andWhere('mode')->eq('')
            ->beginIF($children)->andWhere('id')->notin($children)->fi()
            ->beginIF($appendIdList)->orWhere('id')->in($appendIdList)->fi()
            ->fetchAll();

        $taskPairs = array();
        foreach($taskList as $task)
        {
            if(!$task->isParent && $task->consumed > 0) continue;
            $taskPairs[$task->id] = $task->name;
        }

        return $taskPairs;
    }
}
