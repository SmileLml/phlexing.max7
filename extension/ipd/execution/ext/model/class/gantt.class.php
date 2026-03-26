<?php
class ganttExecution extends executionModel
{
    /**
     * 合并表单以及数据库数据。
     * Merge Relations.
     *
     * @param  array  $relations
     * @access public
     * @return array
     */
    public function mergeRelations($relations = array())
    {
        if(!empty($_POST['id'])) $ids = (array) $_POST['id'];
        $pretasks  = (array) $_POST['pretask'];
        $tasks     = (array) $_POST['task'];
        $condition = (array) $_POST['condition'];
        $action    = (array) $_POST['action'];

        foreach($pretasks as $index => $data)
        {
            if(empty($data) || empty($tasks[$index])) continue;

            $relation = new stdClass();
            $relation->pretask   = $data;
            $relation->pretask   = $data;
            $relation->task      = $tasks[$index];
            $relation->action    = $action[$index];
            $relation->condition = $condition[$index];
            if(!empty($ids)) $relation->id = $ids[$index];

            if(!empty($relation->id))
            {
                $relations[$relation->id] = $relation;
            }
            else
            {
                $relations[] = $relation;
            }

        }
        ksort($relations);

        return $relations;
    }

    /**
     * 获取关联关系任务下拉列表。
     * Get relations tasks.
     *
     * @param  array  $taskList
     * @param  int    $projectID
     * @param  int    $execution
     * @param  array  $appendTasks
     * @access public
     * @return array
     * @param int $executionID
     */
    public function getRelationTasks($taskList, $projectID, $executionID = 0, $appendTasks = array())
    {
        $executionList = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->beginIF($this->config->vision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->andWhere('type')->ne('kanban')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->fetchAll('id');

        $executions = $this->buildRelationExecution($executionList, $taskList, $projectID, $executionID, $appendTasks);
        return $executions;
    }

    /**
     * 构建任务关系的执行树状结构。
     * Build relation execution.
     *
     * @param  array  $executionList
     * @param  array  $taskList
     * @param  int    $parent
     * @param  int    $executionID
     * @param  array  $appendTasks
     * @access public
     * @return array
     */
    public function buildRelationExecution($executionList, $taskList, $parent, $executionID, $appendTasks)
    {
        $executions = array();
        /* 迭代内优先展示当前迭代相关的数据。 */
        foreach($executionList as $execution)
        {
            if($execution->parent != $parent || strpos(",$execution->path,", ",$executionID,") === false) continue;

            $items = $this->buildRelationExecution($executionList, $taskList, $execution->id, $executionID, $appendTasks);
            if(empty($items)) $items = $this->buildRelationTask($taskList, 0, $execution->id, $appendTasks);
            if(!empty($items)) $executions[] = array('text' => $execution->name, 'value' => 'execution' . $execution->id, 'items' => $items);
        }

        /* 后展示其他迭代相关的数据。 */
        foreach($executionList as $execution)
        {
            if($execution->parent != $parent || strpos(",$execution->path,", ",$executionID,") !== false) continue;

            $items = $this->buildRelationExecution($executionList, $taskList, $execution->id, $executionID, $appendTasks);
            if(empty($items)) $items = $this->buildRelationTask($taskList, 0, $execution->id, $appendTasks);
            if(!empty($items)) $executions[] = array('text' => $execution->name, 'value' => 'execution' . $execution->id, 'items' => $items);
        }
        return array_values($executions);
    }

    /**
     * 构建任务关系的任务树状结构。
     * Build relation task.
     *
     * @param  array  $taskList
     * @param  int    $parent
     * @param  int    $executionID
     * @param  array  $appendTasks
     * @access public
     * @return array
     */
    public function buildRelationTask($taskList, $parent, $executionID, $appendTasks)
    {
        $tasks = array();
        foreach($taskList as $taskID => $task)
        {
            /* 加int是因为对外发布的版本函数的参数没有做限制。 */
            if((int)$task->execution !== (int)$executionID) continue;
            if((int)$task->parent    !== (int)$parent)      continue;
            if(strpos(',wait,doing,pause,', ",{$task->status},") === false && !in_array($taskID, $appendTasks)) continue;
            if(!common::hasDBPriv($task, 'task')) continue;

            $tasks[$taskID] = array('text' => $task->name, 'value' => $taskID, 'disabled' => !empty($task->isParent) ? true : false);

            $items = $this->buildRelationTask($taskList, $task->id, $executionID, $appendTasks);
            if(!empty($items)) $tasks[$taskID]['items'] = $items;
        }
        return array_values($tasks);
    }

    /**
     * 获取需要被禁用的任务列表。
     * Get disabled tasks.
     *
     * @param  array  $taskRelations 任务关系数组
     * @param  string $taskID        当前任务ID
     * @param  string $taskType      当前任务是前置任务还是后置任务，前置任务：pretask 后置任务：task
     * @access public
     * @return array
     */
    public function getDisabledTasks($taskRelations, $taskID, $taskType)
    {
        $disabledTasks = array();

        /* 同一个任务之间不能建立以来关系。 */
        $disabledTasks[$taskID] = $taskID;

        /* 两个任务之间只能建立一种依赖关系。 */
        foreach($taskRelations as $relation)
        {
            if($taskType == 'pretask' && $taskID == $relation->pretask) $disabledTasks[$relation->task]    = $relation->task;
            if($taskType == 'task'    && $taskID == $relation->task)    $disabledTasks[$relation->pretask] = $relation->pretask;
        }

        /* 任务路径不能形成闭环。 */
        $disabledTasks = $this->getClosedLoopTasks($taskID, $taskType, $taskRelations, $disabledTasks);

        return $disabledTasks;
    }

    /**
     * 获取会导致闭环的任务列表。
     * Get closed loop tasks。
     *
     * @param  int    $taskID
     * @param  string $taskType
     * @param  array  $taskRelations
     * @param  array  $disabledTasks
     * @access public
     * @return array
     */
    public function getClosedLoopTasks($taskID, $taskType, $taskRelations, $disabledTasks)
    {
        foreach($taskRelations as $index => $relation)
        {
            if($taskType == 'pretask' && $taskID == $relation->task)
            {
                $disabledTasks[$relation->pretask] = $relation->pretask;
                unset($taskRelations[$index]);
                $disabledTasks = $this->getClosedLoopTasks($relation->pretask, $taskType, $taskRelations, $disabledTasks);
            }
            if($taskType == 'task' && $taskID == $relation->pretask)
            {
                $disabledTasks[$relation->task] = $relation->task;
                unset($taskRelations[$index]);
                $disabledTasks = $this->getClosedLoopTasks($relation->task, $taskType, $taskRelations, $disabledTasks);
            }
        }
        return $disabledTasks;
    }

    /**
     * 添加任务关系。
     * Create relation of tasks.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return bool
     */
    public function createRelationOfTasks($projectID, $executionID)
    {
        $tasks          = $this->loadModel('task')->getProjectTaskList($projectID);
        $relations      = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where('project')->eq($projectID)->fetchAll('id');
        $mergeRelations = $this->mergeRelations($relations);
        $this->checkRelation($mergeRelations, $projectID, $tasks);
        if(dao::isError()) return false;

        $postData = fixer::input('post')->get();
        foreach($postData->pretask as $index => $pretask)
        {
            if(empty($pretask) || empty($postData->task[$index])) continue;
            $relation = new stdclass();
            $relation->pretask   = $pretask;
            $relation->condition = $postData->condition[$index];
            $relation->task      = $postData->task[$index];
            $relation->action    = $postData->action[$index];
            $relation->execution = $tasks[$relation->task]->execution . ',' . $tasks[$pretask]->execution;
            $relation->project   = $projectID;

            $this->dao->insert(TABLE_RELATIONOFTASKS)->data($relation)->exec();
        }
        return !dao::isError();
    }

    /**
     * 更新任务关系。
     * Update relation of task.
     *
     * @param  int    $relationID
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function updateRelationOfTask($relationID, $projectID)
    {
        $postData  = fixer::input('post')->get();
        $relations = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where('project')->eq($projectID)->andWhere('id')->ne($relationID)->fetchAll('id');
        $relations[$relationID] = $postData;

        $tasks = $this->loadModel('task')->getProjectTaskList($projectID);
        $this->checkRelation($relations, $projectID, $tasks);
        if(dao::isError()) return false;

        if(!empty($postData->pretask) && !empty($postData->task))
        {
            $postData->execution = $tasks[$postData->task]->execution . ',' . $tasks[$postData->pretask]->execution;
            $this->dao->update(TABLE_RELATIONOFTASKS)->data($postData)->where('id')->eq($relationID)->exec();
        }
        return !dao::isError();
    }

    /**
     * 校验任务关系正确性。
     * Check relations.
     *
     * @param  array   $relations
     * @param  int     $projectID
     * @param  array   $tasks
     * @access public
     * @return bool
     */
    public function checkRelation($relations = array(), $projectID = 0, $tasks = array())
    {
        /* Whether there is conflict between the judgment task relations.*/
        foreach($relations as $index => $relation)
        {
            if(empty($relation)) continue;
            if(!empty($relation->id)) continue;
            if(empty($relation->pretask) || empty($relation->task)) continue;

            /* 已完成、已取消和已关闭的任务不能建立任务关系。 */
            if(in_array($tasks[$relation->pretask]->status, array('done', 'cancel', 'closed')) || in_array($tasks[$relation->task]->status, array('done', 'cancel', 'closed')))
            {
                dao::$errors = $this->lang->execution->error->wrongTaskStatus;
                return false;
            }

            /* 父任务不能创建任务关系。 */
            if($tasks[$relation->pretask]->isParent)
            {
                dao::$errors = $this->lang->execution->error->preTaskIsParent;
                return false;
            }
            if($tasks[$relation->task]->isParent)
            {
                dao::$errors = $this->lang->execution->error->afterTaskIsParent;
                return false;
            }

            /* 任务关系的前后置任务不能相同。 */
            if($relation->pretask == $relation->task)
            {
                dao::$errors = $this->lang->execution->gantt->warning->noEditSame;
                return false;
            }

            /* 任务关系之间不能存在闭环。 */
            if($this->checkClosedLoop(clone $relation, $relations) !== false)
            {
                dao::$errors = $this->lang->execution->error->closedLoop;
                return false;
            }

            /* 不能存在两个相同前后置任务的任务关系。 */
            foreach($relations as $newIndex => $newRelation)
            {
                if(empty($newRelation)) continue;
                if(empty($newRelation->pretask) || empty($newRelation->task)) continue;
                if($newIndex == $index) continue;

                if($relation->pretask == $newRelation->pretask && $relation->task == $newRelation->task)
                {
                    $taskStatus = zget($this->lang->execution->gantt->preTaskStatus, $newRelation->condition);
                    $taskAction = zget($this->lang->execution->gantt->taskActions,   $newRelation->action);
                    $errorLabel = "$newIndex: {$tasks[$newRelation->pretask]->name} {$taskStatus} {$tasks[$newRelation->task]->name} {$taskAction}";
                    dao::$errors = sprintf($this->lang->execution->gantt->warning->noEditRepeat, $index, $errorLabel);
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 批量更新任务关系。
     * Edit relation of tasks.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function editRelationOfTasks($projectID)
    {
        $relations      = fixer::input('post')->get();
        $tasks          = $this->loadModel('task')->getProjectTaskList($projectID);
        $oldRelations   = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where('project')->eq($projectID)->andWhere('id')->notin(array_keys($relations->pretask))->fetchAll('id');
        $mergeRelations = $this->mergeRelations($oldRelations);

        $this->checkRelation($mergeRelations, $projectID, $tasks);
        if(dao::isError()) return false;

        /* update relations.*/
        $data = new stdclass();
        foreach($relations->pretask as $index => $relation)
        {
            if($relations->pretask[$index] != '' && $relations->task[$index] != '')
            {
                $data->pretask   = $relations->pretask[$index];
                $data->condition = $relations->condition[$index];
                $data->task      = $relations->task[$index];
                $data->action    = $relations->action[$index];
                $data->execution = $tasks[$data->task]->execution . ',' . $tasks[$data->pretask]->execution;

                $this->dao->update(TABLE_RELATIONOFTASKS)->data($data)->where('id')->eq($index)->exec();
            }
        }
        return !dao::isError();
    }

    /**
     * 获取任务关系列表。
     * Get relations of tasks.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getRelationsOfTasks($projectID, $executionID, $pager = null)
    {
        $relations = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where('project')->eq($projectID)->beginIF($executionID)->andWhere("FIND_IN_SET($executionID, `execution`)")->fi()->page($pager)->fetchAll('id');
        foreach($relations as $relation) $relation->type = ($relation->condition == 'begin' ? 'S' : 'F') . ($relation->action == 'begin' ? 'S' : 'F');
        return $relations;
    }

    /**
     * 获取甘特图数据。
     * Get data for gantt.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return string
     */
    public function getDataForGantt($executionID, $type, $orderBy)
    {
        $this->loadModel('task');
        $relations  = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where("FIND_IN_SET($executionID, `execution`)")->fetchGroup('task', 'pretask');
        $taskGroups = $this->dao->select('t1.*, t2.realname,t3.branch')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.assignedTo = t2.account')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->where('t1.execution')->eq($executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.status')->ne('cancel')
            ->orderBy("{$type}_asc,id_asc")
            ->fetchGroup($type, 'id');

        $products     = $this->loadModel('product')->getProducts($executionID, 'all', '', false);
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
        $branches     = array();
        foreach($branchGroups as $product => $productBranch)
        {
            foreach($productBranch as $branchID => $branchName) $branches[$branchID] = $branchName;
        }

        $execution     = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch();
        $taskDateLimit = $this->dao->select('taskDateLimit')->from(TABLE_PROJECT)->where('id')->eq($execution->project)->fetch('taskDateLimit');

        if($type == 'story') $stories = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('story')->in(array_keys($taskGroups))->fetchGroup('story', 'version');
        if($type == 'module')
        {
            $showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
            $modules       = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');
            $orderedGroup  = array();
            foreach($modules as $moduleID => $moduleName)
            {
                if(isset($taskGroups[$moduleID])) $orderedGroup[$moduleID] = $taskGroups[$moduleID];
            }
            $taskGroups = $orderedGroup;
        }
        if($type == 'assignedTo') $users = $this->loadModel('user')->getPairs('noletter');

        $groupID    = 0;
        $ganttGroup = array();
        list($orderField, $orderDirect) = $this->parseOrderBy($orderBy);

        /* Fix bug #24555. */
        $taskIdList  = array();
        $parentTasks = array();
        foreach($taskGroups as $group => $tasks)
        {
            $taskIdList = array_merge($taskIdList, array_keys($tasks));
            foreach($tasks as $task)
            {
                if($task->isParent) $parentTasks[$task->id] = $task;
            }
        }

        $teamGroups = $this->dao->select('t1.*,t2.realname')->from(TABLE_TASKTEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.task')->in($taskIdList)
            ->orderBy('t1.order')
            ->fetchGroup('task', 'account');

        $begin = $end = helper::today();
        foreach($taskGroups as $group => $tasks)
        {
            foreach($tasks as $id => $task)
            {
                if($task->mode == 'multi')
                {
                    if($type == 'assignedTo')
                    {
                        $team = zget($teamGroups, $id, array());
                        foreach($team as $account => $member)
                        {
                            if($account == $group) continue;
                            if(!isset($taskGroups[$account])) $taskGroups[$account] = array();

                            $taskGroups[$account][$id] = clone $task;
                            $taskGroups[$account][$id]->id         = $id . '_' . $account;
                            $taskGroups[$account][$id]->realID     = $id;
                            $taskGroups[$account][$id]->assignedTo = $account;
                            $taskGroups[$account][$id]->realname   = $member->realname;
                        }
                    }
                    else
                    {
                        $task->assignedTo = $this->lang->task->team;
                    }
                }

                /* Set deadline and begin date for compute delay days. */
                $deadline = helper::isZeroDate($task->deadline) ? $execution->end : $task->deadline;
                $begin    = $deadline < $begin ? $deadline : $begin;
            }
        }

        $workingDays = $this->loadModel('holiday')->getActualWorkingDays($begin, $end);
        foreach($taskGroups as $group => $tasks)
        {
            $groupID --;
            $groupName = $group;
            if($type == 'type')       $groupName = zget($this->lang->task->typeList, $group);
            if($type == 'module')     $groupName = zget($modules, $group);
            if($type == 'assignedTo') $groupName = $group ? zget($users, $group) : $this->lang->task->noAssigned;
            if($type == 'story')
            {
                $task = current($tasks);
                if(isset($stories[$group][$task->storyVersion]))
                {
                    $story = $stories[$group][$task->storyVersion];
                    $groupName = $story->title;
                    unset($taskGroups[$group]);
                    $group = $groupName;
                }
                if((string)$groupName === '0') $groupName = $this->lang->task->noStory;
            }

            $data             = new stdclass();
            $data->id         = $groupID;
            $data->text       = $groupName;
            $data->start_date = '';
            $data->deadline   = '';
            $data->priority   = '';
            $data->owner_id   = '';
            $data->progress   = '';
            $data->parent     = 0;
            $data->open       = true;

            $groupKey = $type == 'story' ? $groupID : $groupID . $group;
            $ganttGroup[$groupKey]['common'] = $data;

            $totalConsumed = 0;
            $totalHours    = 0;
            $totalLeft     = 0;
            $totalEstimate = 0;
            $minStartDate  = '';
            $maxDeadline   = '';
            $minRealBegan  = '';
            $maxRealEnd    = '';
            $ganttItems    = array();
            $orderKeys     = array();
            $today         = helper::today();
            foreach($tasks as $id => $task)
            {
                $ganttItem = $this->buildGanttItem(($task->parent > 0 and isset($tasks[$task->parent])) ? $task->parent : $groupID, $task, $execution, $branches, $taskDateLimit == 'limit' ? zget($parentTasks, $task->parent, null) : null);

                /* Compute delay days. */
                if(!in_array($task->status, array('cancel', 'closed')) || ($task->status == 'closed' && !helper::isZeroDate($task->finishedDate) && $task->closedReason != 'cancel')) $task = $this->task->computeDelay($task, $ganttItem->deadline, $workingDays);
                $ganttItem->isDelay    = isset($task->delay) && $task->delay > 0;
                $ganttItem->delay      = $ganttItem->isDelay ? $this->lang->programplan->delayList[1] : $this->lang->programplan->delayList[0];
                $ganttItem->delayDays  = $ganttItem->isDelay ? $task->delay : 0;

                $ganttItems[$id] = $ganttItem;

                $taskLeft = ($task->status == 'wait' and $task->left == 0) ? $task->estimate : $task->left;

                $totalConsumed += $task->consumed;
                $totalHours    += $taskLeft + $task->consumed;
                $totalLeft     += $taskLeft;
                $totalEstimate += $task->estimate;

                if(empty($minStartDate)) $minStartDate = $ganttItem->start_date;
                if(!empty($ganttItem->start_date) and strtotime($ganttItem->start_date) < strtotime($minStartDate)) $minStartDate = $ganttItem->start_date;

                if(empty($maxDeadline)) $maxDeadline = $ganttItem->deadline;
                if(strtotime($ganttItem->deadline) > strtotime($maxDeadline)) $maxDeadline = $ganttItem->deadline;

                if(empty($minRealBegan)) $minRealBegan = $ganttItem->realBegan;
                if(!empty($ganttItem->realBegan) and strtotime($ganttItem->realBegan) < strtotime($minRealBegan)) $minRealBegan = $ganttItem->realBegan;

                if(empty($maxRealEnd)) $maxRealEnd = $ganttItem->realEnd;
                if(strtotime($ganttItem->realEnd) > strtotime($maxRealEnd)) $maxRealEnd = $ganttItem->realEnd;

                $orderKeys[$id] = $orderField ? $ganttItem->{$orderField} : $id;
                if($orderField == 'start_date') $orderKeys[$id] = date('Y-m-d', strtotime($orderKeys[$id]));

                $ganttItem->isDelay = false;

                /* Check delay. */
                if(in_array($task->status, $this->config->task->unfinishedStatus) && !empty($ganttItem->deadline) && !helper::isZeroDate($ganttItem->deadline) && $today > $ganttItem->deadline) $ganttItem->isDelay = true;
            }

            if($orderField)
            {
                if($orderDirect == 'asc')  asort($orderKeys);
                if($orderDirect == 'desc') arsort($orderKeys);
            }
            foreach($orderKeys as $id => $fieldValue) $ganttGroup[$groupKey][$id] = $ganttItems[$id];

            $ganttGroup[$groupKey]['common']->progress   = $totalHours == 0 ? 0 : round($totalConsumed / $totalHours, 4);
            $ganttGroup[$groupKey]['common']->start_date = $minStartDate;
            $ganttGroup[$groupKey]['common']->deadline   = $maxDeadline;
            $ganttGroup[$groupKey]['common']->realBegan  = $minRealBegan;
            $ganttGroup[$groupKey]['common']->realEnd    = $maxRealEnd;
            $ganttGroup[$groupKey]['common']->consumed   = $totalConsumed;
            $ganttGroup[$groupKey]['common']->estimate   = $totalEstimate;
            $ganttGroup[$groupKey]['common']->left       = $totalLeft;
            $ganttGroup[$groupKey]['common']->duration   = helper::diffDate($maxDeadline, $minStartDate) + 1;
        }
        if($type == 'story') krsort($ganttGroup);

        $executionGantt = array();
        foreach($ganttGroup as $groupID => $tasks)
        {
            foreach($tasks as $task)
            {
                $task->color         = $this->lang->execution->gantt->stage->color;
                $task->progressColor = $this->lang->execution->gantt->stage->progressColor;
                $task->textColor     = $this->lang->execution->gantt->stage->textColor;
                if(isset($task->pri))
                {
                    $task->color         = zget($this->lang->execution->gantt->color, $task->pri, $this->lang->execution->gantt->defaultColor);
                    $task->progressColor = zget($this->lang->execution->gantt->progressColor, $task->pri, $this->lang->execution->gantt->defaultProgressColor);
                    $task->textColor     = zget($this->lang->execution->gantt->textColor, $task->pri, $this->lang->execution->gantt->defaultTextColor);
                }
                $task->bar_height = $this->lang->execution->gantt->bar_height;
                $task->allowLinks = $execution->type == 'kanban' ? false : true;

                $executionGantt['data'][] = $task;
            }
        }
        foreach($relations as $taskID => $preTasks)
        {
            foreach($preTasks as $preTask => $relation)
            {
                $link['id']     = $relation->id;
                $link['source'] = $preTask;
                $link['target'] = $taskID;
                $link['type']   = $this->config->execution->gantt->linkType[$relation->condition][$relation->action];
                $executionGantt['links'][] = $link;
            }
        }
        return json_encode($executionGantt);
    }

    /**
     * 构建甘特图元素。
     * Build gantt item.
     *
     * @param  int    $groupID
     * @param  object $task
     * @param  object $execution
     * @param  array  $branches
     * @param  object $parent
     * @access public
     * @return object
     */
    public function buildGanttItem($groupID, $task, $execution, $branches, $parent = null)
    {
        $today       = helper::today();
        $account     = $this->app->user->account;
        $ganttFields = $this->config->execution->ganttCustom->ganttFields;
        $showID      = strpos($ganttFields, 'id') !== false ? 1 : 0;
        $showBranch  = strpos($ganttFields, 'branch') !== false ? 1 : 0;

        $start = $task->estStarted;
        $end   = $task->deadline;
        if($parent && helper::isZeroDate($start) && !helper::isZeroDate($parent->estStarted)) $start = $parent->estStarted;
        if($parent && helper::isZeroDate($end) && !helper::isZeroDate($parent->deadline))     $end   = $parent->deadline;
        if(helper::isZeroDate($start)) $start = $execution->begin;
        if(helper::isZeroDate($end))   $end   = $execution->end;

        $start = date('d-m-Y', strtotime($start));
        $end   = date('Y-m-d', strtotime($end));

        $name  = '';
        if($showID) $name .= '#' . (empty($task->realID) ? $task->id : $task->realID) . ' ';
        if(isset($branches[$task->branch]) and $showBranch) $name .= "<span class='label label-info'>{$branches[$task->branch]}</span> ";
        $name    .= $task->name;
        $taskPri  = zget($this->lang->task->priList, $task->pri);
        $taskPri  = mb_substr($taskPri, 0, 1, 'UTF-8');
        $priIcon  = "<span class='label-pri label-pri-$task->pri' title='$taskPri'>$taskPri</span> ";

        $data             = new stdclass();
        $data->id         = $task->id;
        $data->text       = $priIcon . $name;
        $data->start_date = $start;
        $data->deadline   = $end;
        $data->pri        = $task->pri;
        $data->estimate   = $task->estimate;
        $data->consumed   = $task->consumed;
        $data->left       = $task->left;
        $data->openedBy   = $task->openedBy;
        $data->finishedBy = $task->finishedBy;
        $data->duration   = helper::diffDate($end, $start) + 1;
        $data->owner_id   = $task->assignedTo;
        $data->progress   = ($task->consumed + $task->left) == 0 ? 0 : round($task->consumed / ($task->consumed + $task->left), 4);
        $data->parent     = $groupID;
        $data->status     = isset($this->lang->task->statusList[$task->status]) ? $this->lang->task->statusList[$task->status] : '';
        $data->open       = true;
        $data->realBegan  = helper::isZeroDate($task->realStarted) ? '' : date('Y-m-d', strtotime($task->realStarted));
        $data->realEnd    = helper::isZeroDate($task->finishedDate) ? '' : date('Y-m-d', strtotime($task->finishedDate));

        return $data;
    }

    /**
     * 删除任务关系。
     * Delete relation.
     *
     * @param  int    $relationID
     * @access public
     * @return bool
     */
    public function deleteRelation($relationID)
    {
        $this->dao->delete()->from(TABLE_RELATIONOFTASKS)->where('id')->eq($relationID)->exec();
        return true;
    }

    /**
     * Parse orderBy.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function parseOrderBy($orderBy)
    {
        $orderField  = '';
        $orderDirect = '';
        if($orderBy)
        {
            $orderBy     = str_replace('_', ' ', $orderBy);
            $orderBy     = explode(' ', $orderBy);
            $orderDirect = end($orderBy);
            if($orderDirect == 'asc' or $orderDirect == 'desc')
            {
                array_pop($orderBy);
            }
            else
            {
                $orderDirect = 'asc';
            }
            $orderField = join('_', $orderBy);
        }

        return array($orderField, $orderDirect);
    }

    /**
     * Build kanban orderBy.
     *
     * @param  string $field
     * @param  string $currentOrder
     * @param  string $currentDirect
     * @access public
     * @return array
     */
    public function buildKanbanOrderBy($field, $currentOrder, $currentDirect)
    {
        $fieldOrderBy = "{$field}_asc";
        $fieldClass   = "{$field}_head sort";
        if($currentOrder == $field)
        {
            $fieldOrderBy  = "{$field}_";
            $fieldOrderBy .= $currentDirect == 'asc' ? 'desc' : 'asc';
            $fieldClass   .= $currentDirect == 'asc' ? ' sort-up' : ' sort-down';
        }

        return array($fieldOrderBy, $fieldClass);
    }

    /**
     * 检查任务关系是否会导致闭环。
     * Check closed loop.
     *
     * @param  object $taskRelation
     * @param  array  $relations
     * @access public
     * @return bool
     */
    public function checkClosedLoop($taskRelation, $relations)
    {
        foreach($relations as $index => $relation)
        {
            if($taskRelation->pretask == $relation->task)
            {
                if($taskRelation->task == $relation->pretask) return true;

                $taskRelation->pretask = $relation->pretask;
                unset($relations[$index]);
                return $this->checkClosedLoop($taskRelation, $relations);
            }
        }
        return false;
    }


    /**
     * 检查任务关系的异常点。
     * Check task relation.
     *
     * @param  array  $relations
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function checkTaskRelation($relations, $projectID)
    {
        $tasks  = $this->loadModel('task')->getProjectTaskList($projectID);
        $result = array();
        foreach($relations as $relation)
        {
            $preTask   = $tasks[$relation->pretask];
            $afterTask = $tasks[$relation->task];
            if($preTask->isParent)   $result[$relation->id]['preTaskIsParent']   = 'preTaskIsParent';
            if($afterTask->isParent) $result[$relation->id]['afterTaskIsParent'] = 'afterTaskIsParent';

            if($this->checkClosedLoop(clone $relation, $relations) !== false) $result[$relation->id]['closedLoop'] = 'closedLoop';
        }
        return $result;
    }
}
