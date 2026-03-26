<?php
/**
 * The model file of effort module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     effort
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class effortModel extends model
{
    const DAY_IN_FUTURE = 20300101;

    /**
     * Time to int
     *
     * @param  string    $date
     * @access public
     * @return int
     */
    public function timeToInt($date)
    {
        $newDate = $date;
        if(strpos($date, ':') !== false)
        {
            list($min, $sec) = explode(':', $date);
            $min     = str_pad($min, 2, '0', STR_PAD_LEFT);
            $sec     = str_pad($sec, 2, '0', STR_PAD_LEFT);
            $newDate = $min . $sec;
        }

        return empty($newDate) ? '2400' : $newDate;
    }

    /**
     * Create a effort
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $consumed
     * @param  string $work
     * @param  string $extra
     * @param  string $date
     * @access public
     * @return int
     */
    public function create($objectType, $objectID, $consumed, $work, $extra = '', $date = '')
    {
        $effort = new stdclass();
        $effort->objectType = $objectType;
        $effort->objectID   = $objectID;
        $effort->account    = $this->app->user->account;
        $effort->work       = $work;
        $effort->date       = empty($date) ? helper::today() : $date;
        $effort->consumed   = $consumed;
        $effort->extra      = $extra;

        $relation          = $this->loadModel('action')->getRelatedFields($objectType, $objectID);
        $effort->product   = $relation['product'];
        $effort->project   = (int)$relation['project'];
        $effort->execution = (int)$relation['execution'];

        $this->dao->insert(TABLE_EFFORT)->data($effort)->exec();
        if(dao::isError()) return false;

        $effortID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('effort', $effortID, 'created');

        return $effortID;
    }

    /**
     * Batch create efforts.
     *
     * @param  date   $date
     * @param  string $account
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        $this->loadModel('task');
        $this->loadModel('action');

        $now        = helper::now();
        $efforts    = fixer::input('post')->get();
        $data       = array();
        $taskIDList = array();
        $today      = helper::today();
        $nonRDUser  = (!empty($_SESSION['user']->feedback) or !empty($_COOKIE['feedbackView'])) ? true : false;
        foreach($efforts->id as $id => $num)
        {
            $rawID = $this->app->rawMethod == 'createforobject' ? $id : $num;
            if(strpos($efforts->objectType[$id], '_') !== false)
            {
                $pos = strpos($efforts->objectType[$id], '_');
                $efforts->objectID[$id]   = substr($efforts->objectType[$id], $pos + 1);
                $efforts->objectType[$id] = substr($efforts->objectType[$id], 0, $pos);
            }
            elseif(empty($efforts->objectID[$id]))
            {
                $efforts->objectType[$id] = 'custom';
                $efforts->objectID[$id]   = 0;
            }

            if(!empty($efforts->work[$id]) or !empty($efforts->consumed[$id]))
            {
                if($efforts->objectType[$id] == 'task' and (empty($efforts->dates[$rawID]) or helper::isZeroDate($efforts->dates[$rawID])) and (empty($efforts->date) or helper::isZeroDate($efforts->date))) return dao::$errors["dates[$rawID]"] = $this->app->rawMethod == 'createforobject' ? $this->lang->task->error->dateEmpty : ($this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->task->error->dateEmpty);

                if($efforts->objectType[$id] == 'task' and isset($efforts->dates[$rawID]) and $efforts->dates[$rawID] > $today) return dao::$errors["dates[$rawID]"] = $this->app->rawMethod == 'createforobject' ? $this->lang->task->error->date : ($this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->task->error->date);

                $efforts->work[$id] = trim($efforts->work[$id]);
                if(empty($efforts->work[$id])) return dao::$errors["work[$rawID]"] = $this->app->rawMethod == 'createforobject' ? sprintf($this->lang->error->notempty, $this->lang->effort->work) : sprintf($this->lang->effort->nowork, $efforts->id[$id]);
                if(!is_numeric($efforts->consumed[$id])) return dao::$errors["consumed[$rawID]"] = $this->app->rawMethod == 'createforobject' ? ($this->lang->effort->consumed . $this->lang->effort->isNumber) : ($this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->consumed . $this->lang->effort->isNumber);

                $consumed = (float)$efforts->consumed[$id];
                if(empty($consumed)) return dao::$errors["consumed[$rawID]"] = $this->app->rawMethod == 'createforobject' ? ($this->lang->effort->consumed . $this->lang->effort->notNegative) : ($this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->consumed . $this->lang->effort->notEmpty);
                if($consumed < 0) return dao::$errors["consumed[$rawID]"] = $this->app->rawMethod == 'createforobject' ? ($this->lang->effort->consumed . $this->lang->effort->notNegative) : ($this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->consumed . $this->lang->effort->notNegative);

                $left = isset($efforts->left[$rawID]) ? $efforts->left[$rawID] : '';
                if(!empty($left) and !is_numeric($left)) return dao::$errors["left[$rawID]"] = $this->app->rawMethod == 'createforobject' ? ($this->lang->effort->left . $this->lang->effort->isNumber) : ($this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->left . $this->lang->effort->isNumber);
                if(!empty($left) and $left < 0) return dao::$errors["left[$rawID]"] = $this->app->rawMethod == 'createforobject' ? ($this->lang->effort->left . $this->lang->effort->notNegative) : ($this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->left . $this->lang->effort->notNegative);
                if($efforts->objectType[$id] == 'task' and !$nonRDUser and empty($left) and !is_numeric($left))  die(js::alert($this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->left . $this->lang->effort->notEmpty));
                $data[$id] = new stdclass();
                $data[$id]->vision    = $this->config->vision;
                $data[$id]->product   = ',0,';
                $data[$id]->execution = 0;
                $data[$id]->objectID  = 0;

                $data[$id]->date       = isset($efforts->dates[$id]) ? $efforts->dates[$id] : $efforts->date;
                $data[$id]->consumed   = $efforts->consumed[$id];
                $data[$id]->account    = $this->app->user->account;
                $data[$id]->work       = $efforts->work[$id];
                $data[$id]->objectType = $efforts->objectType[$id];
                if(isset($efforts->order[$id])) $data[$id]->order = $efforts->order[$id];

                if($data[$id]->date > $now) return dao::$errors["date[$rawID]"] = $this->app->rawMethod == 'createforobject' ? ($this->lang->effort->date . $this->lang->effort->notFuture) : ($this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->date . $this->lang->effort->notFuture);

                if($data[$id]->objectType == 'task')
                {
                    $taskIDList[$efforts->objectID[$id]] = $efforts->objectID[$id];
                    $data[$id]->left = (float)$left;
                }

                if($data[$id]->objectType != 'custom') $data[$id]->objectID = $efforts->objectID[$id];

                if($data[$id]->objectID != 0)
                {
                    $relation = $this->action->getRelatedFields($data[$id]->objectType, $data[$id]->objectID);
                    $data[$id]->product   = $relation['product'];
                    $data[$id]->project   = (int)$relation['project'];
                    $data[$id]->execution = (int)$relation['execution'];
                }

                if(!empty($efforts->execution[$rawID]))
                {
                    $data[$id]->project   = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq((int)$efforts->execution[$rawID])->fetch('project');
                    $data[$id]->execution = (int)$efforts->execution[$rawID];
                }

                if((!empty($efforts->execution[$rawID])) && ($data[$id]->objectID == 0))
                {
                    $products = $this->loadModel('product')->getProducts($efforts->execution[$rawID]);
                    ksort($products);
                    $data[$id]->product = ',' . join(',', array_keys($products)) . ',';
                }
            }
        }

        $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($taskIDList)->fetchAll('id');
        $executionTeams = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in($taskIDList)->orderBy('order')->fetchGroup('task');
        $lastDatePairs  = $this->dao->select('objectID,max(date) as date')->from(TABLE_EFFORT)
            ->where('objectID')->in($taskIDList)->andWhere('objectType')->eq('task')
            ->andWhere('deleted')->eq(0)
            ->groupBy('objectID')
            ->fetchPairs('objectID', 'date');

        $now    = helper::now();
        $errors = array();

        $this->loadModel('story');
        $this->loadModel('task');
        $changedTasks = array();
        foreach($data as $id => $effort)
        {
            $this->dao->insert(TABLE_EFFORT)->data($effort)->autoCheck()->batchCheck($this->config->effort->create->requiredFields, 'notempty')->exec();
            if(dao::isError())
            {
                $errors[$id] = dao::getError();
                continue;
            }

            $effortID = $this->dao->lastInsertID();
            $this->action->create('effort', $effortID, 'created');

            if(isset($efforts->actionID[$id]))
            {
                $this->dao->update(TABLE_ACTION)->set('efforted')->eq(1)
                    ->where('id')->le($efforts->actionID[$id])
                    ->andWhere('actor')->eq($this->app->user->account)
                    ->andWhere('objectType')->eq($effort->objectType)
                    ->andWhere('objectID')->eq($effort->objectID)
                    ->andWhere('date')->ge("$effort->date 00:00:00")
                    ->andWhere('date')->le("$effort->date 23:59:59")
                    ->exec();
            }

            if($effort->objectType == 'story')
            {
                $storyType = $this->dao->select('type')->from(TABLE_STORY)->where('id')->eq($effort->objectID)->fetch('type');
                if($effort->objectType != $storyType) $this->dao->update(TABLE_EFFORT)->set('objectType')->eq($storyType)->where('id')->eq($effortID)->exec();
            }

            if($effort->objectType == 'bug')
            {
                $this->dao->update(TABLE_BUG)->set('lastEditedDate')->eq($now)
                    ->set('lastEditedBy')->eq($this->app->user->account)
                    ->where('id')->eq($effort->objectID)
                    ->exec();
            }

            if($effort->objectType != 'task' and $effort->objectType != 'custom')
            {
                $this->recordAction($effort->objectType, $effort->objectID, 'recordEstimate', $effort->work, $effort->consumed);
                continue;
            }

            if($effort->objectType == 'task')
            {
                $taskID = $effort->objectID;
                $task   = zget($tasks, $taskID, '');
                if(empty($task)) continue;

                $fromAction = false;
                if(!empty($_POST['actionID'][$id]))
                {
                    $action = $this->dao->select('*')->from(TABLE_ACTION)->where('id')->eq($efforts->actionID[$id])->fetch();
                    if(isset($action->action) and ($action->action == 'opened' or $action->action == 'edited')) $fromAction = true;
                }

                $newTask = new stdclass();
                $newTask->id             = $task->id;
                $newTask->parent         = $task->parent;
                $newTask->story          = $task->story;
                $newTask->execution      = $task->execution;
                $newTask->status         = $task->status;
                $newTask->consumed       = $task->consumed + $effort->consumed;
                $newTask->lastEditedBy   = $this->app->user->account;
                $newTask->lastEditedDate = $now;
                $newTask->left           = $task->left;
                if(helper::isZeroDate($task->realStarted)) $newTask->realStarted = $now;

                if(empty($lastDatePairs[$taskID]) or $lastDatePairs[$taskID] <= $effort->date)
                {
                    $newTask->left = $effort->left;
                    $lastDatePairs[$taskID] = $effort->date;
                }

                if(isset($executionTeams[$taskID]))
                {
                    $extra = array('filter' => 'done');
                    if(isset($effort->order)) $extra['order'] = $effort->order;
                    $currentTeam = $this->task->getTeamByAccount($executionTeams[$taskID], $effort->account, $extra);
                }

                $taskStatus = (empty($currentTeam) and strpos('done,pause,cancel,closed', $task->status) === false) || (!empty($currentTeam) and $currentTeam->status != 'done');
                if($newTask->left == 0 and $taskStatus)
                {
                    $newTask->status         = 'done';
                    $newTask->assignedTo     = $task->openedBy;
                    $newTask->assignedDate   = $now;
                    $newTask->finishedBy     = $this->app->user->account;
                    $newTask->finishedDate   = $now;
                    $actionID = $this->action->create('task', $taskID, 'Finished', $effort->work);
                }
                elseif($newTask->status == 'wait')
                {
                    $newTask->status       = 'doing';
                    $newTask->assignedTo   = $this->app->user->account;
                    $newTask->assignedDate = $now;
                    $actionID = $this->action->create('task', $taskID, 'Started', $effort->work);
                }
                elseif($newTask->left != 0 and strpos('done,pause,cancel,closed,pause', $task->status) !== false)
                {
                    $newTask->status         = 'doing';
                    $newTask->assignedTo     = $this->app->user->account;
                    $newTask->finishedBy     = '';
                    $newTask->canceledBy     = '';
                    $newTask->closedBy       = '';
                    $newTask->closedReason   = '';
                    $newTask->finishedDate   = null;
                    $newTask->canceledDate   = null;
                    $newTask->closedDate     = null;
                    $actionID = $this->action->create('task', $taskID, 'Activated', $effort->work);
                }
                else
                {
                    $actionID = $this->action->create('task', $taskID, 'RecordEstimate', $effort->work, $effort->consumed);
                }

                /* Process multi-person task. Update consumed on team table. */
                if(isset($executionTeams[$taskID]))
                {
                    if(!empty($currentTeam))
                    {
                        $teamStatus = $effort->left == 0 ? 'done' : 'doing';
                        $this->dao->update(TABLE_TASKTEAM)->set('left')->eq($effort->left)->set("consumed = consumed + {$effort->consumed}")->set('status')->eq($teamStatus)->where('id')->eq($currentTeam->id)->exec();
                        if($task->mode == 'linear' and empty($effort->order)) $this->task->updateEstimateOrder($effortID, $currentTeam->order);
                        $currentTeam->consumed += $effort->consumed;
                        $currentTeam->left      = $effort->left;
                        $currentTeam->status    = $teamStatus;
                    }

                    $newTask = $this->task->computeMultipleHours($task, $newTask, $executionTeams[$taskID]);
                }

                $this->dao->update(TABLE_ACTION)->set('efforted')->eq('1')->where('id')->eq($actionID)->exec();

                unset($newTask->subStatus);
                $changes = common::createChanges($task, $newTask, 'task');
                if($changes and !empty($actionID)) $this->action->logHistory($actionID, $changes);

                if($changes) $changedTasks[$taskID] = $taskID;
                $tasks[$taskID] = $newTask;
            }
        }

        $this->loadModel('common');
        $this->loadModel('programplan');
        foreach($changedTasks as $taskID)
        {
            $task = $tasks[$taskID];

            $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($taskID)->exec();
            if($task->parent > 0) $this->task->updateParentStatus($task->id);
            if($task->story) $this->story->setStage($task->story);

            if($task->parent > 0)
            {
                if($task->status == 'done') $this->task->updateParentStatus($task->id, $task->parent, 'done');
                $this->task->computeWorkingHours($task->parent);
            }

            $this->common->syncPPEStatus($taskID);
            $this->programplan->computeProgress($task->execution);
        }

        return $errors;
    }

    /**
     * Record action.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $action recordestimate|editestimate|deleteestimate
     * @param  string $work
     * @param  int    $consumed
     * @access public
     * @return void
     */
    public function recordAction($objectType, $objectID, $action, $work = '', $consumed = 0)
    {
        $this->loadModel('action');
        $newConsumed = $this->dao->select('sum(consumed) as consumed')->from(TABLE_EFFORT)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->andWhere('deleted')->eq(0)->fetch();

        $oldConsumed = new stdclass();
        if(strtolower($action) == 'recordestimate') $oldConsumed->consumed = round((float)$newConsumed->consumed - (float)$consumed, 2);
        if(strtolower($action) == 'editestimate')   $oldConsumed->consumed = round((float)$newConsumed->consumed - (float)$consumed, 2);
        if(strtolower($action) == 'deleteestimate') $oldConsumed->consumed = round((float)$newConsumed->consumed + (float)$consumed, 2);

        $changes = common::createChanges($oldConsumed, $newConsumed);
        if($changes or !empty($work))
        {
            if(in_array($objectType, array('story', 'requirement', 'epic'))) $objectType = 'story';
            $actionID = $this->action->create($objectType, $objectID, $action, $work, $consumed);
            $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * update efforts.
     *
     * @param  date $date
     * @access public
     * @return void
     */
    public function batchUpdate($account = '')
    {
        $this->loadModel('action');
        $efforts      = fixer::input('post')->get();
        $effortIDList = $this->post->id;
        $oldEfforts   = $this->dao->select('*')->from(TABLE_EFFORT)->where('id')->in($effortIDList)->andWhere('deleted')->eq(0)->fetchAll('id');

        if(empty($efforts->id)) $efforts->id = array();
        $taskEffortPairs = array();
        foreach($oldEfforts as $effort)
        {
            if($effort->objectType == 'task') $taskEffortPairs[$effort->objectID] = $effort->objectID;
        }
        $lastEffortGroup = $this->dao->select('*')
            ->from(TABLE_EFFORT)
            ->where('objectType')->eq('task')
            ->andWhere('deleted')->eq(0)
            ->andWhere('objectID')->in($taskEffortPairs)
            ->orderBy('date_desc')
            ->fetchGroup('objectID', 'id');

        /* delete efforts.*/
        $deleteIDList = array_diff($effortIDList, $efforts->id);

        if($deleteIDList)
        {
            sort($deleteIDList);

            $taskIDList = array();
            foreach($deleteIDList as $id)
            {
                $effort = $oldEfforts[$id];
                if($effort->objectType == 'task') $taskIDList[] = $effort->objectID;
            }
            $tasks = array();
            if(!empty($taskIDList)) $tasks = $this->loadModel('task')->getByList($taskIDList);

            foreach($deleteIDList as $id)
            {
                $effort = $oldEfforts[$id];
                if($effort->account != $this->app->user->account) continue;
                if($effort->objectType == 'task' and isset($lastEffortGroup[$effort->objectID]))
                {
                    $lastEfforts = $lastEffortGroup[$effort->objectID];

                    reset($lastEfforts);
                    if(key($lastEfforts) == $id and count($lastEfforts) >= 2)
                    {
                        $effort->left = count($lastEfforts) >= 2 ? next($lastEfforts)->left : $effort->left;
                        $effort->last = true;
                        unset($lastEffortGroup[$effort->objectID][$id]);
                    }
                }

                if($effort->objectType == 'task')
                {
                    $this->changeTaskConsumed($effort, 'delete', '', zget($tasks, $effort->objectID, ''));
                    $tasks[$effort->objectID]->consumed -= $effort->consumed;
                }
                $this->dao->update(TABLE_EFFORT)->set('deleted')->eq('1')->where('id')->eq($id)->exec();
                $this->action->create('effort', $effort->id, 'Deleted');
            }
        }

        $executionProjectPairs = $this->dao->select('id,project')->from(TABLE_EXECUTION)->where('id')->in($efforts->execution)->fetchPairs('id');

        /* update efforts.*/
        $data       = array();
        $taskIDList = array();
        foreach($efforts->id as $id)
        {
            $pos = strpos($efforts->objectType[$id], '_');
            $efforts->objectID[$id]   = substr($efforts->objectType[$id], $pos + 1);
            $efforts->objectType[$id] = substr($efforts->objectType[$id], 0, $pos);

            if(!empty($efforts->work[$id]) and (($efforts->objectType[$id] != 'custom' and $efforts->objectID[$id] != '') or $efforts->objectType[$id] == 'custom'))
            {
                if(empty($efforts->work[$id]))           return dao::$errors["work[$id]"] = sprintf($this->lang->effort->nowork, $efforts->id[$id]);
                if(empty($efforts->consumed[$id]))       return dao::$errors["consumed[$id]"] = $this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->consumed . $this->lang->effort->notEmpty;
                if($efforts->consumed[$id] < 0)          return dao::$errors["consumed[$id]"] = $this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->consumed . $this->lang->effort->notNegative;
                if(!is_numeric($efforts->consumed[$id])) return dao::$errors["consumed[$id]"] = $this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->consumed . $this->lang->effort->isNumber;
                if(!empty($efforts->left[$id]) and !is_numeric($efforts->left[$id])) return dao::$errors["left[$id]"] = $this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->left . $this->lang->effort->isNumber;
                if(!empty($efforts->left[$id]) and $efforts->left[$id] < 0)          return dao::$errors["left[$id]"] = $this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->left . $this->lang->effort->notNegative;

                $data[$id] = new stdclass();
                $data[$id]->product   = implode(',', $efforts->product[$id]);
                $data[$id]->execution = !empty($efforts->execution[$id]) ? $efforts->execution[$id] : 0;
                $data[$id]->project   = !empty($data[$id]->execution) ? zget($executionProjectPairs, $data[$id]->execution, 0) : 0;

                $data[$id]->date       = $efforts->date[$id];
                $data[$id]->consumed   = $efforts->consumed[$id];
                $data[$id]->left       = isset($efforts->left[$id]) ? $efforts->left[$id] : $oldEfforts[$id]->left;
                $data[$id]->objectID   = $efforts->objectID[$id];
                $data[$id]->objectType = $efforts->objectType[$id];
                $data[$id]->work       = $efforts->work[$id];

                if($data[$id]->date > helper::now()) return dao::$errors["date[$id]"] = $this->lang->effort->common . $efforts->id[$id] . ' : ' . $this->lang->effort->left . $this->lang->effort->notFuture;

                if($data[$id]->objectType == 'task') $taskIDList[] = $data[$id]->objectID;
            }
        }

        $tasks = array();
        if(!empty($taskIDList)) $tasks = $this->loadModel('task')->getByIdList($taskIDList);

        foreach($data as $id => $effort)
        {
            $oldEffort = $oldEfforts[$id];
            $effort->account = $oldEffort->account;
            $this->dao->update(TABLE_EFFORT)->data($effort)->autoCheck()->where('id')->eq($id)->exec();

            $changes = common::createChanges($oldEffort, $effort);
            if($changes)
            {
                $actionID = $this->action->create('effort', $id, 'Edited');
                $this->action->logHistory($actionID, $changes);

                if($effort->objectType == 'task')
                {
                    $this->changeTaskConsumed($effort, 'add', $oldEffort, zget($tasks, $effort->objectID, ''));
                    if(isset($tasks[$effort->objectID]))
                    {
                        $tasks[$effort->objectID]->consumed = $tasks[$effort->objectID]->consumed + $effort->consumed;
                        if($oldEffort->objectType == 'task' and $oldEffort->objectID == $effort->objectID) $tasks[$effort->objectID]->consumed -= $oldEffort->consumed;
                    }
                }
                if($oldEffort->objectType == 'task' and $oldEffort->objectID != $effort->objectID)
                {
                    $this->changeTaskConsumed($oldEffort, 'delete', '', zget($tasks, $oldEffort->objectID, ''));
                    if(isset($tasks[$effort->objectID])) $tasks[$oldEffort->objectID]->consumed = $tasks[$oldEffort->objectID]->consumed - $oldEffort->consumed;
                }
            }
        }
    }

    /**
     * update a effort.
     *
     * @param  int    $effortID
     * @access public
     * @return void
     */
    public function update($effortID)
    {
        $today     = helper::today();
        $now       = helper::now();
        $oldEffort = $this->getById($effortID);
        $effort    = fixer::input('post')
            ->setDefault('account', $oldEffort->account)
            ->cleanInt('objectID,execution')
            ->join('product', ',')
            ->get();

        if(!empty($effort->product)) $effort->product = ',' . $effort->product . ',';

        if($effort->objectType == 'task')
        {
            $this->app->loadLang('task');
            if(helper::isZeroDate($effort->date)) return dao::$errors['date'] = $this->lang->task->error->dateEmpty;
            if($effort->date > $today) return dao::$errors['date'] = $this->lang->task->error->date;
        }

        if($effort->consumed <= 0) return dao::$errors['consumed'] = sprintf($this->lang->error->gt, $this->lang->effort->consumed, '0');
        if($effort->left < 0)      return dao::$errors['left'] = $this->lang->effort->left . $this->lang->effort->notNegative;

        if(!empty($effort->execution))
        {
            $effort->project = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($effort->execution)->fetch('project');
            if(empty($effort->product))
            {
                $products = $this->loadModel('product')->getProducts($effort->execution);
                ksort($products);
                $effort->product = ',' . join(',', array_keys($products)) . ',';
            }
        }

        if(empty($effort->date)) return dao::$errors['date'] = sprintf($this->lang->error->notempty, $this->lang->effort->date);
        if($effort->date > $now) return dao::$errors['date'] = $this->lang->effort->notFuture;

        $this->dao->update(TABLE_EFFORT)->data($effort)
            ->autoCheck()
            ->batchCheck($this->config->effort->edit->requiredFields, 'notempty')
            ->where('id')->eq($effortID)
            ->exec();

        if(!dao::isError())
        {
            if($effort->objectType != 'task') $this->recordAction($effort->objectType, $effort->objectID, 'editEstimate', $effort->work, $effort->consumed - $oldEffort->consumed);

            if($effort->objectType == 'bug')
            {
                $this->dao->update(TABLE_BUG)->set('lastEditedDate')->eq($now)
                    ->set('lastEditedBy')->eq($this->app->user->account)
                    ->where('id')->eq($effort->objectID)
                    ->exec();
            }

            $changes = common::createChanges($oldEffort, $effort);
            if($changes)
            {
                $effort->id = $effortID;
                $this->changeTaskConsumed($effort, 'add', $oldEffort);
            }
            if($oldEffort->objectType == 'task' and $oldEffort->objectID != $effort->objectID) $this->changeTaskConsumed($oldEffort, 'delete');
            return $changes;
        }
    }

    /**
     * Get info of a effort.
     *
     * @param  int    $effortID
     * @access public
     * @return object|bool
     */
    public function getById($effortID)
    {
        $effort = $this->dao->findById((int)$effortID)->from(TABLE_EFFORT)->fetch();
        if(!$effort) return false;
        $effort->date     = str_replace('-', '', $effort->date);
        $effort->consumed = round($effort->consumed, 2);
        $effort->left     = round($effort->left, 2);
        return $effort;
    }

    /**
     * Parse date
     *
     * @param  string $date
     * @access public
     * @return array
     */
    public function parseDate($date)
    {
        $this->app->loadClass('date');
        if($date == 'today')
        {
            $begin = date('Y-m-d', time());
            $end   = $begin;
        }
        elseif($date == 'yesterday')
        {
            $begin = date::yesterday();
            $end   = $begin;
        }
        elseif($date == 'thisweek')
        {
            extract(date::getThisWeek());
        }
        elseif($date == 'lastweek')
        {
            extract(date::getLastWeek());
        }
        elseif($date == 'thismonth')
        {
            extract(date::getThisMonth());
        }
        elseif($date == 'lastmonth')
        {
            extract(date::getLastMonth());
        }
        elseif($date == 'all')
        {
            $begin = '1970-01-01';
            $end   = date("Y-m-d");
        }
        elseif(is_array($date))
        {
            list($begin, $end) = $date;
        }
        else
        {
            $begin = $date;
            $end   = $date;
        }
        return array(substr($begin, 0, 10), substr($end, 0, 10));
    }

    /**
     * Get effort list of a user.
     *
     * @param  date   $begin
     * @param  date   $end
     * @param  string $account
     * @param  int    $product
     * @param  int    $execution
     * @param  int    $dept
     * @param  string $orderBy
     * @param  object $pager
     * @param  int    $project
     * @access public
     * @return void
     */
    public function getList($begin, $end, $account = '', $product = 0, $execution = 0, $dept = 0, $orderBy = 'date_desc', $pager = null, $project = 0, $userType = '')
    {
        $orderBy = empty($orderBy) ? 'date_desc' : $orderBy;
        $efforts = array();
        $users   = array();
        if($dept)   $users = $this->loadModel('dept')->getDeptUserPairs((int)$dept);
        if($account)$users = array($account => $account);

        $efforts = $this->dao->select('t1.*,t1.work,t1.product,t1.extra,t2.dept')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->beginIF($begin)->andWhere("t1.date")->ge($begin)->fi()
            ->beginIF($end)->andWhere("t1.date")->le($end)->fi()
            ->beginIF($users or $dept)->andWhere('t1.account')->in(array_keys($users))->fi()
            ->beginIF($product)->andWhere('t1.product')->like("%,$product,%")->fi()
            ->beginIF($project)->andWhere('t1.project')->eq($project)->fi()
            ->beginIF($execution)->andWhere('t1.execution')->eq($execution)->fi()
            ->beginIF($userType)->andWhere('t2.type')->eq($userType)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        /* Set session. */
        $sql = explode('WHERE', $this->dao->get());
        $sql = explode('ORDER', $sql[1]);
        $this->session->set('effortReportCondition', $sql[0]);

        $objectIdList = array();
        foreach($efforts as $effort) $objectIdList[$effort->objectType][$effort->objectID] = $effort->objectID;
        list($objectTypeList, $todos) = $this->getEffortTitles($objectIdList);
        foreach($efforts as $effort)
        {
            if(isset($objectTypeList[$effort->objectType]))
            {
                $title = $objectTypeList[$effort->objectType];
                $effort->objectTitle = zget($title, $effort->objectID, '');
                if($effort->objectType == 'todo' and isset($todos[$effort->objectID]))
                {
                    $todo = $todos[$effort->objectID];
                    $effort->objectTitle = $todo->name;
                    if(isset($objectTypeList[$todo->type])) $effort->objectTitle = zget($objectTypeList[$todo->type], $todo->objectID, '');
                }
                if($effort->objectType == 'case') $effort->objectType = 'testcase';
            }
        }
        return $efforts;
    }

    /**
     * Get actions.
     *
     * @param  int    $date
     * @param  int    $account
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getActions($date, $account, $objectType = '', $objectID = '')
    {
        /* Get all actions. */
        $date = is_numeric($date) ? substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2) : $date;
        $dateLength = strlen($date);
        $allActions = $this->dao->select('*,product')->from(TABLE_ACTION)
            ->where('actor')->eq($account)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere("(LEFT(`date`, $dateLength) = '$date')")
            ->beginIF(!empty($objectType))->andWhere('objectType')->eq($objectType)->fi()
            ->beginIF(!empty($objectID))->andWhere('objectID')->eq($objectID)->fi()
            ->andWhere('efforted')->eq(0)
            ->orderBy('id_desc')
            ->limit(30)
            ->fetchAll('id');

        /* Init vars. */
        $taskIdList       = array();
        $storyIdList      = array();
        $executionTask    = array();
        $executionBug     = array();
        $closedExectuions = array();
        if(empty($this->config->CRExecution)) $closedExectuions = $this->loadModel('execution')->getIdList(0, 'closed');
        $deletedExecutions = $this->dao->select('id')->from(TABLE_EXECUTION)->where('deleted')->eq('1')->fetchPairs('id');
        $deletedProducts   = $this->dao->select('id')->from(TABLE_PRODUCT)->where('deleted')->eq('1')->fetchPairs('id');

        foreach($allActions as $id => $action)
        {
            if($action->objectType == 'story') $storyIdList[$action->objectID] = $action->objectID;

            if($action->objectType == 'task')
            {
                if(isset($closedExectuions[$action->execution]) or isset($deletedExecutions[$action->execution]))
                {
                    unset($allActions[$id]);
                    continue;
                }

                $taskIdList[$action->objectID] = $action->objectID;
            }
            elseif($action->objectType == 'bug' or $action->objectType == 'story' or $action->objectType == 'testtask' or $action->objectType == 'feedback')
            {
                $actionProducts = array_filter(explode(',', $action->product));
                if(empty($deletedProducts) or empty($actionProducts)) continue;

                $checkProductDeleted = true;
                foreach($actionProducts as $actionProduct)
                {
                    if(!isset($deletedProducts[$actionProduct]))
                    {
                        $checkProductDeleted = false;
                        break;
                    }
                }
                if($checkProductDeleted)
                {
                    unset($allActions[$id]);
                    continue;
                }
            }
        }

        $taskIdList  = $this->dao->select('id')->from(TABLE_TASK)->where('`id`')->in($taskIdList)->andWhere('deleted')->eq(0)->andWhere('isParent')->eq(0)->andWhere('mode')->ne('linear')->fetchPairs('id', 'id');
        $storyTypes  = $this->dao->select('id,type')->from(TABLE_STORY)->where('`id`')->in($storyIdList)->fetchPairs();
        $teams       = $this->dao->select('task,account')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task', 'account');

        $actions     = array();
        $executions  = array();
        $dealActions = array();
        $parents     = $this->dao->select('parent')->from(TABLE_EXECUTION)->where('type')->eq('stage')->andWhere('grade')->gt(1)->andWhere('deleted')->eq(0)->fetchPairs('parent', 'parent');
        foreach($allActions as $id => $action)
        {
            /* Remove started or finished or multiple or parent or deleted task. */
            if($action->objectType == 'task' and ($action->action == 'started' or $action->action == 'finished')) continue;
            if($action->objectType == 'task' and !isset($taskIdList[$action->objectID])) continue;
            if($action->objectType == 'task' and !isset($teams[$action->objectID][$account])) continue;
            if(!empty($parents[$action->execution])) continue;

            if($action->objectType == 'story') $action->objectType = isset($storyTypes[$action->objectID]) ? $storyTypes[$action->objectID] : 'story';

            if(isset($dealActions[$action->objectType][$action->objectID])) continue;

            if(isset($this->lang->effort->objectTypeList[$action->objectType]))
            {
                $work = $this->getWork($action->objectType, $action->objectID);

                $key      = $action->objectType . '_' . $action->objectID;
                $objectID = $action->objectID;
                if(!isset($work[$objectID])) continue;
                $typeList[$key] = '[' . zget($this->lang->effort->objectTypeList, $action->objectType, $action->objectType) . ']' . $objectID . ':' . $work[$objectID];
                $action->work   = $this->lang->effort->deal . $this->lang->effort->objectTypeList[$action->objectType] . ' : ' . $work[$objectID];

                unset($action->product);

                $actions[$id] = $action;
                $executions[$action->execution] = $action->execution;
                if($action->objectType == 'task') $executionTask[$key] = $action->execution; // Fix bug #1581.
                if($action->objectType == 'bug') $executionBug[$key] = $action->execution; // Fix bug #16446.
                $dealActions[$action->objectType][$action->objectID] = true;
            }
        }

        if(isset($this->lang->effort->objectTypeList['story']))
        {
            $stories = $this->dao->select('t1.id,t1.title,t1.type')->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
                ->where('t1.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->orderBy('id_desc')
                ->fetchAll();

            foreach($stories as $story)
            {
                $key = "{$story->type}_" . $story->id;
                $typeList[$key] = "[{$this->lang->effort->objectTypeList[$story->type]}]" . $story->id . ':' . $story->title;
            }
        }

        /* Get tasks and remove multiple or parent tasks. */
        $tasks = $this->dao->select('t1.id,t1.execution,t1.name,t1.parent,t1.isParent')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_TASKTEAM)->alias('t3')->on('t3.task = t1.id')
            ->where("(t1.assignedTo = '{$this->app->user->account}' OR (t1.mode = 'multi' AND t3.`account` = '{$this->app->user->account}'))")
            ->andWhere('t1.isParent')->eq('0')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->orderBy('id_desc')
            ->fetchAll();

        foreach($tasks as $task)
        {
            if($task->isParent) continue;
            if(isset($closedExectuions[$task->execution])) continue;

            $key                          = 'task_' . $task->id;
            $typeList[$key]               = "[{$this->lang->effort->objectTypeList['task']}]" . $task->id . ':' . $task->name;
            $executionTask[$key]          = $task->execution;
            $executions[$task->execution] = $task->execution;
        }

        if(isset($this->lang->effort->objectTypeList['bug']))
        {
            $bugs = $this->dao->select('t1.id,t1.title,t1.execution')->from(TABLE_BUG)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
                ->where('t1.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy('id_desc')
                ->fetchAll();
            foreach($bugs as $bug)
            {
                $key                         = 'bug_' . $bug->id;
                $typeList[$key]              = "[{$this->lang->effort->objectTypeList['bug']}]" . $bug->id . ':' . $bug->title;
                $executionBug[$key]          = $bug->execution;
                $executions[$bug->execution] = $bug->execution;
            }
        }

        $actions['typeList'] = isset($typeList) ? $typeList : array();
        $executions = $this->loadModel('execution')->getByIdList($executions);
        $projects   = $this->loadModel('project')->getPairsByModel();

        foreach($executions as $execution)
        {
            $executionPrefix = isset($projects[$execution->project]) ? $projects[$execution->project] . '/' : '';
            $actions['executions'][$execution->id] = $executionPrefix . $execution->name;
        }


        if(isset($executionTask)) $actions['executionTask'] = $executionTask;
        if(isset($executionBug)) $actions['executionBug'] = $executionBug;

        return $actions;
    }

    /**
     * Get efforts by account.
     *
     * @param  string    $date
     * @param  string    $account
     * @access public
     * @return object
     */
    public function getByAccount($effortIDList, $account = '')
    {
        $efforts = $this->dao->select('*')->from(TABLE_EFFORT)
            ->where('id')->in($effortIDList)
            ->andWhere('deleted')->eq(0)
            ->beginIF(!empty($account))->andWhere('account')->eq($account)->fi()
            ->fetchAll('id', false);

        if(!empty($efforts))
        {
            $objectIdList = array();
            foreach($efforts as $effort) $objectIdList[$effort->objectType][$effort->objectID] = $effort->objectID;
            list($objectTypeList, $todos) = $this->getEffortTitles($objectIdList);
            $objectTypeList['user']       = $this->loadModel('user')->getPairs('noletter');

            foreach($efforts as $effort)
            {
                $objectType = $effort->objectType;
                $objectID   = $effort->objectID;
                $key        = $objectType . '_' . $objectID;
                $typeList[$key] = isset($objectTypeList[$objectType][$objectID]) ? "[$key]:" . $objectTypeList[$objectType][$objectID] : '';
                if($objectType != 'custom' and isset($objectTypeList[$objectType][$objectID]))
                {
                    $typeList[$key] = strtoupper($objectType) . $objectID . ':' . $objectTypeList[$objectType][$objectID];
                }
                if($objectType == 'todo' and isset($todo) and isset($objectTypeList[$todo->type]))
                {
                    $todo = $todos[$objectID];
                    $typeList[$key] = strtoupper($objectType) . $objectID . ':' . $objectTypeList[$todo->type][$objectID];
                }
                $effort->product = trim($effort->product, ',');
            }

            $vision  = $this->config->vision;
            $stories = $this->dao->select('id,title,type')->from(TABLE_STORY)->where('assignedTo')->eq($account)->andWhere('deleted')->eq('0')->andWhere('vision')->eq($vision)->fetchAll();
            foreach($stories as $story)
            {
                $key            = "{$story->type}_" . $story->id;
                $firstLetter    = strtoupper($story->type[0]);
                $typeList[$key] = "[$firstLetter]" . $story->id . ':' . $story->title;
            }

            $tasks = $this->dao->select('id,name')->from(TABLE_TASK)->where('assignedTo')->eq($account)->andWhere('deleted')->eq('0')->andWhere('vision')->eq($vision)->fetchAll();
            foreach($tasks as $task)
            {
                $key = 'task_' . $task->id;
                $typeList[$key] = '[T]' . $task->id . ':' . $task->name;
            }

            if($vision != 'lite')
            {
                $bugs = $this->dao->select('id,title')->from(TABLE_BUG)->where('assignedTo')->eq($account)->andWhere('deleted')->eq('0')->fetchAll();
                foreach($bugs as $bug)
                {
                    $key = 'bug_' . $bug->id;
                    $typeList[$key] = '[B]' . $bug->id . ':' . $bug->title;
                }
            }

            $efforts['typeList'] = $typeList;
        }
        return $efforts;
    }

    /**
     * Get efforts by object.
     *
     * @param  string    $objectType
     * @param  int       $objectID
     * @param  string    $orderBy
     * @param  string    $extra
     * @access public
     * @return object
     */
    public function getByObject($objectType, $objectID, $orderBy = 'date,id', $extra = '')
    {
        if(in_array($objectType, array('epic', 'requirement', 'story'))) $objectType = 'epic,requirement,story';
        $efforts = $this->dao->select('*')->from(TABLE_EFFORT)
            ->where('objectType')->in($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($extra)->andWhere('extra')->eq($extra)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id', false);
        if(!empty($efforts))
        {
            foreach($efforts as $effort) $idList[$objectType][$effort->objectID] = $effort->objectID;
            list($objectTypeList, $todos) = $this->getEffortTitles($idList);
            $objectTypeList['user']       = $this->loadModel('user')->getPairs('noletter');
            $objectTypeList['custom'][0]  = $this->lang->effort->objectTypeList['custom'];

            $typeList = array();
            foreach($efforts as $effort)
            {
                if(!isset($objectTypeList[$effort->objectType])) continue;

                $key = $effort->objectType . '_' . $effort->objectID;
                $typeList[$key] = "[$key]:" . $objectTypeList[$effort->objectType][$effort->objectID];
            }
            $efforts['typeList'] = $typeList;
        }
        return $efforts;
    }

    /**
     * Get work.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getWork($objectType, $objectID)
    {
        $work = array();
        /* form begin or end for action.*/
        $idList[$objectType][$objectID] = $objectID;
        list($objectTypeList, $todos)   = $this->getEffortTitles($idList);
        if(isset($objectTypeList[$objectType]) && isset($objectTypeList[$objectType][$objectID]))
        {
            $work[$objectID] = $objectTypeList[$objectType][$objectID];
            if($objectType == 'todo')
            {
                $todo = $todos[$objectID];
                if(isset($objectTypeList[$todo->type])) $todo->name = $objectTypeList[$todo->type][$todo->objectID];
                $work[$objectID] = $todo->name;
            }
        }
        return $work;
    }

    /**
     * Change task consumed.
     *
     * @param  object $effort
     * @param  string $action
     * @param  object $oldEffort
     * @param  object $task
     * @access public
     * @return void
     */
    public function changeTaskConsumed($effort, $action = 'add', $oldEffort = '', $task = '')
    {
        if($effort->objectType != 'task') return;

        $this->loadModel('task');
        $this->loadModel('action');

        $action = $action == 'add' ? '+' : '-';
        $now    = helper::now();

        if(empty($task)) $task = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($effort->objectID)->fetch();
        if(!isset($task->mode)) $task->mode = '';
        $teams    = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($effort->objectID)->orderBy('`order`')->fetchAll();
        $consumed = $this->dao->select('sum(consumed) as consumed')->from(TABLE_EFFORT)->where('objectType')->eq('task')->andWhere('objectID')->eq($effort->objectID)->andWhere('deleted')->eq(0)->fetch('consumed');

        if($action == '-')
        {
            $lastTwoEffort = $this->dao->select('*')->from(TABLE_EFFORT)
                ->where('objectID')->eq($effort->objectID)
                ->andWhere('objectType')->eq('task')
                ->andWhere('deleted')->eq(0)
                ->beginIF(isset($effort->id))->orWhere('id')->eq($effort->id)->fi()
                ->orderBy('date_desc,id_desc')
                ->limit(2)
                ->fetchAll();
            $lastEffort    = array_shift($lastTwoEffort);
            $lastTwoEffort = array_shift($lastTwoEffort);
            $isLastEffort  = (isset($effort->id) and (empty($lastEffort) or $lastEffort->id == $effort->id));
        }
        else
        {
            $lastEffort = $this->dao->select('*')->from(TABLE_EFFORT)->where('objectID')->eq($effort->objectID)->andWhere('objectType')->eq('task')->andWhere('deleted')->eq(0)->orderBy('date_desc,id_desc')->limit(1)->fetch();
            if(!empty($oldEffort->id))$isLastEffort = (empty($lastEffort) or $lastEffort->id == $oldEffort->id);
            if(empty($oldEffort->id)) $isLastEffort = $lastEffort->date <= $effort->date;
        }

        $actionID = 0;
        $newTask  = new stdclass();
        $newTask->consumed       = $consumed;
        $newTask->status         = $task->status;
        $newTask->story          = $task->story;
        $newTask->lastEditedBy   = $this->app->user->account;
        $newTask->lastEditedDate = $now;
        if($isLastEffort and $action == '+') $newTask->left = $effort->left;
        if($isLastEffort and $action == '-' and $lastTwoEffort) $newTask->left = $lastTwoEffort->left;
        if($isLastEffort and $newTask->consumed == 0 and $task->status != 'wait')
        {
            $newTask->status       = 'wait';
            $newTask->consumed     = 0;
            $newTask->left         = $task->estimate;
            $newTask->finishedBy   = '';
            $newTask->canceledBy   = '';
            $newTask->closedBy     = '';
            $newTask->closedReason = '';
            $newTask->finishedDate = null;
            $newTask->canceledDate = null;
            $newTask->closedDate   = null;
            if($task->assignedTo == 'closed') $newTask->assignedTo = $this->app->user->account;

            $actionID = $this->action->create('task', $effort->objectID, $action == '+' ? 'Activated' : 'DeleteEstimate', $action == '+' ? $effort->work : '');
        }
        elseif($isLastEffort and isset($newTask->left) and $newTask->left == 0 and strpos('done,cancel,closed', $task->status) === false)
        {
            $newTask->status 		 = 'done';
            $newTask->assignedTo     = $task->openedBy;
            $newTask->assignedDate   = $now;
            $newTask->finishedBy     = $this->app->user->account;
            $newTask->finishedDate   = $now;

            $actionID = $this->action->create('task', $effort->objectID, $action == '+' ? 'Finished' : 'DeleteEstimate', $action == '+' ? $effort->work : '');
        }
        elseif($isLastEffort and isset($newTask->left) and $newTask->left != 0 and strpos('done,pause,cancel,closed', $task->status) !== false)
        {
            $newTask->status         = 'doing';
            $newTask->finishedBy     = '';
            $newTask->canceledBy     = '';
            $newTask->closedBy       = '';
            $newTask->closedReason   = '';
            $newTask->finishedDate   = null;
            $newTask->canceledDate   = null;
            $newTask->closedDate     = null;

            $actionID = $this->action->create('task', $effort->objectID, $action == '+' ? 'Activated' : 'DeleteEstimate', $action == '+' ? $effort->work : '');
        }
        elseif($task->status == 'wait')
        {
            $newTask->status       = 'doing';
            $newTask->assignedTo   = $this->app->user->account;
            $newTask->assignedDate = $now;
            $newTask->realStarted  = date('Y-m-d');

            $actionID = $this->action->create('task', $effort->objectID, $action == '+' ? 'Started' : 'DeleteEstimate', $action == '+' ? $effort->work : '');
        }
        else
        {
            $comment = isset($effort->id) && isset($_POST['work']) && isset($_POST['work'][$effort->id]) ? $this->post->work[$effort->id] : '';
            $actionID = $this->action->create('task', $effort->objectID, $action == '+' ? 'EditEstimate' : 'DeleteEstimate', $comment);
        }

        if(!empty($teams))
        {
            $currentTeam = $this->task->getTeamByAccount($teams, $effort->account, array('effortID' => zget($effort, 'id', 0), 'order' => zget($effort, 'order', '')));
            if($currentTeam)
            {
                $newTeamInfo = new stdClass();
                $newTeamInfo->consumed = $currentTeam->consumed;
                if($action == '+') $newTeamInfo->consumed += $effort->consumed;
                if($action == '-') $newTeamInfo->consumed -= $effort->consumed;
                if(!empty($oldEffort->consumed) and $action == '+') $newTeamInfo->consumed -= $oldEffort->consumed;

                $left = $currentTeam->left;;
                if($action == '+' and isset($newTask->left)) $left = $newTask->left;;
                if($action == '-' and $task->mode == 'multi')
                {
                    $accountEfforts = $this->task->getTaskEstimate($currentTeam->task, $effort->account, zget($effort, 'id', 0));
                    $lastEffort     = array_pop($accountEfforts);
                    if(isset($effort->id) && $lastEffort->id == $effort->id)
                    {
                        $lastTwoEffort = array_pop($accountEfforts);
                        if($lastTwoEffort) $left = $lastTwoEffort->left;
                    }
                }

                if($currentTeam->status != 'done') $newTeamInfo->left = $left;
                if($currentTeam->status == 'done' and $left > 0 and $task->mode == 'multi') $newTeamInfo->left = $left;

                if($currentTeam->status != 'done' and $newTeamInfo->consumed > 0 and $left == 0) $newTeamInfo->status = 'done';
                if($task->mode == 'multi' and $currentTeam->status == 'done' and $left > 0) $newTeamInfo->status = 'doing';
                if($task->mode == 'multi' and $currentTeam->status == 'done' and ($newTeamInfo->consumed == 0 and $left == 0))
                {
                    $newTeamInfo->status = 'doing';
                    $newTeamInfo->left   = $currentTeam->estimate;
                }

                $this->dao->update(TABLE_TASKTEAM)->data($newTeamInfo)->where('id')->eq($currentTeam->id)->exec();

                $currentTeam->consumed = $newTeamInfo->consumed;
                if(isset($newTeamInfo->left))   $currentTeam->left   = $newTeamInfo->left;
                if(isset($newTeamInfo->status)) $currentTeam->status = $newTeamInfo->status;

                $newTask = $this->task->computeMultipleHours($task, $newTask, $teams);
            }
        }

        if(!empty($actionID))
        {
            $this->dao->update(TABLE_ACTION)->set('efforted')->eq('1')->where('id')->eq($actionID)->exec();

            unset($newTask->subStatus);
            $changes = common::createChanges($task, $newTask, 'task');
            if($changes)
            {
                $this->action->logHistory($actionID, $changes);
                if($newTask->consumed == 0)$this->loadModel('action')->create('task', $effort->objectID, 'Adjusttasktowait');
            }
        }
        if(empty($newTask->consumed)) $newTask->consumed = 0;
        $this->dao->update(TABLE_TASK)->data($newTask)->where('id')->eq($effort->objectID)->exec();

        if($task->parent > 0) $this->task->updateParentStatus($task->id);
        if($newTask->story) $this->loadModel('story')->setStage($newTask->story);
        if($task->feedback) $this->loadModel('feedback')->updateStatus('task', $task->feedback, $newTask->status, $task->status);
    }

    /**
     * Create append link
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function createAppendLink($objectType, $objectID)
    {
        if(!common::hasPriv('effort', 'createForObject')) return false;

        /* Determines whether an object is editable. */
        if($objectType == 'case') $objectType = 'testcase';
        $object = $this->loadModel($objectType)->getByID($objectID);
        if(!common::canBeChanged($objectType, $object)) return false;

        $isOldModule = in_array($objectType, $this->config->effort->oldViewModules);
        $recordLink  = helper::createLink('effort', 'createForObject', "objectType=$objectType&objectID=$objectID", '', true);
        $link        = $isOldModule ? $recordLink : '#';
        $attr        = $isOldModule ? '' : "data-toggle='modal'";
        $iconClass   = in_array($objectType, array('release', 'build')) ? 'text-primary' : '';
        return html::a($recordLink, "<i class='icon-green-effort-createForObject icon-time {$iconClass}'></i> " . $this->lang->effort->common, '', "class='ghost btn btn-default iframe' {$attr}");
    }

    /**
     * Get main depts
     *
     * @access public
     * @return array
     */
    public function getMainDepts()
    {
        $depts = array();
        $mainDepts = $this->dao->select('*')->from(TABLE_DEPT)->where('grade')->eq(1)->fetchAll();
        $depts[0]  = $this->lang->effort->allDept;
        foreach($mainDepts as $mainDept) $depts[$mainDept->id] = $mainDept->name;
        return $depts;
    }

    /**
     * Get all depts.
     *
     * @access public
     * @return array
     */
    public function getAllDepts()
    {
        $depts = array();
        $mainDepts = $this->dao->select('*')->from(TABLE_DEPT)->fetchAll('id', false);
        $depts[0]  = $this->lang->company->allDept;
        foreach($mainDepts as $mainDept)
        {
            if($mainDept->parent)
            {
                $name = '';
                foreach(explode(',', $mainDept->path) as $pathID)
                {
                    if(!empty($pathID)) $name .= $mainDepts[$pathID]->name . ' / ';
                }
                $depts[$mainDept->id] = rtrim($name, ' / ');
            }
            else
            {
                $depts[$mainDept->id] = $mainDept->name;
            }
        }
        return $depts;
    }

    /**
     * Print cell.
     *
     * @param  object $col
     * @param  object $effort
     * @param  string $mode
     * @param  array  $executions
     * @access public
     * @return void
     */
    public function printCell($col, $effort, $mode = 'datatable', $executions = array())
    {
        $canView  = common::hasPriv('effort', 'view');
        $account  = $this->app->user->account;
        $id       = $col->id;
        if($col->show)
        {
            $class = '';
            $title = '';
            if($id == 'work') $title = " title='{$effort->work}'";
            if($id == 'objectType' and isset($effort->objectTitle)) $title = " title='{$effort->objectTitle}'";

            if($id == 'work' or $id == 'objectType') $class .= ' c-name';

            if($id == 'product')
            {
                static $products;
                if(empty($products)) $products = $this->loadModel('product')->getPairs('', 0, '', 'all');

                $effort->productName = '';
                $effortProducts      = explode(',', trim($effort->product, ','));
                foreach($effortProducts as $productID) $effort->productName .= zget($products, $productID, '') . ' ';
                $title = " title='{$effort->productName}'";
            }
            if($id == 'execution')
            {
                $effort->executionName = zget($executions, $effort->execution, '');
                $title = " title='{$effort->executionName}'";
            }
            if($id == 'dept')
            {
                static $depts;
                if(empty($depts)) $depts = $this->loadModel('dept')->getOptionMenu();
                $effort->deptName = zget($depts, $effort->dept, '');
                $title = " title='{$effort->deptName}'";
            }

            echo "<td class='c-{$id}" . $class . "'" . $title . ">";
            switch($id)
            {
            case 'id':
                if($this->app->getModuleName() == 'my')
                {
                    echo html::checkbox('effortIDList', array($effort->id => sprintf('%03d', $effort->id)));
                }
                else
                {
                    printf('%03d', $effort->id);
                }
                break;
            case 'date':
                echo $effort->date;
                break;
            case 'account':
                static $users;
                if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');
                echo zget($users, $effort->account);
                break;
            case 'dept':
                echo $effort->deptName;
                break;
            case 'work':
                echo $canView ? html::a(helper::createLink('effort', 'view', "id=$effort->id&from=my", '', true), $effort->work, '', "class='iframe'") : $effort->work;
                break;
            case 'consumed':
                echo $effort->consumed;
                break;
            case 'left':
                echo $effort->objectType == 'task' ? $effort->left : '';
                break;
            case 'objectType':
                if($effort->objectType != 'custom')
                {
                    $viewLink = helper::createLink($effort->objectType, 'view', "id=$effort->objectID");
                    $objectTitle = zget($this->lang->effort->objectTypeList, $effort->objectType, strtoupper($effort->objectType)) . " #{$effort->objectID} " . $effort->objectTitle;
                    echo common::hasPriv($effort->objectType, 'view') ? html::a($viewLink, $objectTitle) : $objectTitle;
                }
                break;
            case 'product':
                echo $effort->productName;
                break;
            case 'execution':
                echo $effort->executionName;
                break;
            case 'actions':
                common::printIcon('effort', 'edit',   "id=$effort->id", $effort, 'list', '', '', 'iframe', true);
                common::printIcon('effort', 'delete', "id=$effort->id", $effort, 'list', 'trash', 'hiddenwin');
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Get ranzhi leave users
     *
     * @access public
     * @return array
     */
    public function getRanzhiLeaveUsers()
    {
        if(!extension_loaded('curl')) return false;

        $address   = $this->config->sso->addr;
        $parsedURL = parse_url($address);

        $ranzhiHost   = $parsedURL['scheme'] . "://" . $parsedURL['host'];
        $ranzhiConfig = commonModel::http($ranzhiHost . '/sys/index.php?mode=getconfig');
        $ranzhiConfig = json_decode($ranzhiConfig);

        $zentaoRequestType = $this->config->requestType;
        $zentaoWebRoot     = $this->config->webRoot;

        $this->config->requestType = $ranzhiConfig->requestType;
        $this->config->webRoot     = '/';
        $getLeaverLink  = $ranzhiHost . '/sys' . helper::createLink('sso', 'leaveUsers');
        $getLeaverLink .= strpos($getLeaverLink, '?') !== false ? '&' : '?';
        $getLeaverLink .= "code={$this->config->sso->code}&key={$this->config->sso->key}";

        $this->config->requestType = $zentaoRequestType;
        $this->config->webRoot     = $zentaoWebRoot;

        $leaveUsers = commonModel::http($getLeaverLink);
        return json_decode($leaveUsers, true);
    }

    /**
     * Get effort count.
     *
     * @param  string $account
     * @access public
     * @return int
     */
    public function getCount($account = '')
    {
        if(empty($account)) $account = $this->app->user->account;
        return $this->dao->select('COUNT(1) AS count')->from(TABLE_EFFORT)->where('account')->eq($account)->andWhere('vision')->eq($this->config->vision)->andWhere('deleted')->eq(0)->fetch('count');
    }

    /**
     * Get recently executions
     *
     * @param  string status
     * @param  int    limit
     * @param  array  filterID
     * @access public
     * @return array
     */
    public function getRecentlyExecutions($status = 'all', $limit = 20, $filterID = array())
    {
        if(!empty($filterID))
        {
            $executionID = array_diff(explode(',', $this->app->user->view->sprints), $filterID);
        }
        else
        {
            $executionID = $this->app->user->view->sprints;
        }

        $executions = $this->dao->select('t1.id as id, t1.name as name, t2.name as project, t1.multiple, t1.type, t1.grade, t1.path')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->in($this->config->vision == 'lite' ? 'kanban' : 'stage,sprint,kanban')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($executionID)->fi()
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($status == 'noclosed')->andWhere('t1.status')->ne('closed')->fi()
            ->orderBy('id_desc')
            ->beginIF(!empty($limit))->limit($limit)->fi()
            ->fetchAll('id');

        $this->app->loadLang('project');
        $executionPairs = array();
        $parents        = $this->dao->select('distinct parent,parent')->from(TABLE_EXECUTION)->where('type')->eq('stage')->andWhere('grade')->gt(1)->andWhere('deleted')->eq(0)->fetchPairs();
        foreach($executions as $id => $execution)
        {
            if(!empty($parents[$execution->id])) continue;

            if($execution->type == 'stage' and $execution->grade > 1)
            {
                $parentExecutions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($execution->path, ','))->andWhere('type')->in('stage,kanban,sprint')->orderBy('grade')->fetchPairs();
                $execution->name  = implode('/', $parentExecutions);
            }

            $executionPairs[$id] = $execution->project . '/' . $execution->name;
            if(empty($execution->multiple)) $executionPairs[$id] = $execution->project . "({$this->lang->project->disableExecution})";
        }

        return $executionPairs;
    }

    /**
     * Get join executions
     *
     * @param  string status
     * @param  int    limit
     * @access public
     * @return array
     */
    public function getJoinExecution($status = 'all', $limit = 20)
    {
        $executions = $this->dao->select('t1.id as id, t1.name as name, t2.name as project, t1.multiple, t1.type, t1.grade, t1.path')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->leftJoin(TABLE_TEAM)->alias('t3')->on('t1.id=t3.root')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->in($this->config->vision == 'lite' ? 'kanban' : 'stage,sprint,kanban')
            ->andWhere('t3.type')->eq('execution')
            ->andWhere('t3.account')->eq($this->app->user->account)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($status == 'noclosed')->andWhere('t1.status')->ne('closed')->fi()
            ->orderBy('t3.join_desc, t3.id_desc')
            ->beginIF(!empty($limit))->limit($limit)->fi()
            ->fetchAll('id');

        $this->app->loadLang('project');
        $executionPairs = array();
        $parents        = $this->dao->select('distinct parent,parent')->from(TABLE_EXECUTION)->where('type')->eq('stage')->andWhere('grade')->gt(1)->andWhere('deleted')->eq(0)->fetchPairs();
        foreach($executions as $id => $execution)
        {
            if(!empty($parents[$execution->id])) continue;

            if($execution->type == 'stage' and $execution->grade > 1)
            {
                $parentExecutions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($execution->path, ','))->andWhere('type')->in('stage,kanban,sprint')->orderBy('grade')->fetchPairs();
                $execution->name  = implode('/', $parentExecutions);
            }

            $executionPairs[$id] = $execution->project . '/' . $execution->name;
            if(empty($execution->multiple)) $executionPairs[$id] = $execution->project . "({$this->lang->project->disableExecution})";
        }

        return $executionPairs;
    }

    /**
     * Get effort titles.
     *
     * @param  array  $objectIdList
     * @access public
     * @return array
     */
    public function getEffortTitles($objectIdList)
    {
        $this->app->loadConfig('action');
        $todos = array();
        $objectTypeList = array();
        foreach($objectIdList as $objectType => $idList)
        {
            $table = zget($this->config->objectTables, $objectType, '');
            $field = zget($this->config->action->objectNameFields, $objectType, '');
            if($table and $field)
            {
                $objectTypeList[$objectType] = $this->dao->select("id,$field")->from($table)->where('id')->in($idList)->andWhere('deleted')->eq('0')->fetchPairs('id', $field);
                if($objectType == 'todo')
                {
                    $todos = $this->dao->select('*')->from(TABLE_TODO)->where('id')->in($idList)->fetchAll('id');
                    $todoLinkedObject = array();
                    foreach($todos as $todo)
                    {
                        if(!empty($todo->objectID)) $todoLinkedObject[$todo->type][$todo->objectID] = $todo->objectID;
                    }
                    if($todoLinkedObject)
                    {
                        foreach($todoLinkedObject as $linkedType => $linkedIdList)
                        {
                            $table = zget($this->config->objectTables, $linkedType, '');
                            $field = zget($this->config->action->objectNameFields, $linkedType, '');
                            if($table and $field)
                            {
                                $linkedObjects = $this->dao->select("id,$field")->from($table)->where('id')->in($linkedIdList)->fetchPairs('id', $field);
                                if(!isset($objectTypeList[$linkedType])) $objectTypeList[$linkedType] = array();
                                $objectTypeList[$linkedType] = arrayUnion($objectTypeList[$linkedType], $linkedObjects);
                            }
                        }
                    }
                }
            }
        }
        return array($objectTypeList, $todos);
    }

    /**
     * Convert estimate to effort.
     *
     * @access public
     * @return bool
     */
    public function convertEstToEffort()
    {
        $estimates = $this->dao->select('*')->from(TABLE_TASKESTIMATE)->orderBy('id')->fetchAll();

        $this->loadModel('action');
        foreach($estimates as $estimate)
        {
            $relation = $this->action->getRelatedFields('task', $estimate->task);

            $effort = new stdclass();
            $effort->objectType = 'task';
            $effort->objectID   = $estimate->task;
            $effort->product    = $relation['product'];
            $effort->project    = (int)$relation['project'];
            $effort->account    = $estimate->account;
            $effort->work       = empty($estimate->work) ? $this->lang->effort->handleTask : $estimate->work;
            $effort->date       = $estimate->date;
            $effort->left       = $estimate->left;
            $effort->consumed   = $estimate->consumed;
            $effort->vision     = $this->config->vision;
            $effort->order      = $estimate->order;

            $this->dao->insert(TABLE_EFFORT)->data($effort)->exec();
            $this->dao->delete()->from(TABLE_TASKESTIMATE)->where('id')->eq($estimate->id)->exec();
        }
        return true;
    }

    /**
     * Convert effort to estimate.
     *
     * @access public
     * @return bool
     */
    public function convertEffortToEst()
    {
        $efforts = $this->dao->select('*')->from(TABLE_EFFORT)->where('objectType')->eq('task')->andWhere('deleted')->eq(0)->orderBy('id')->fetchAll('id', false);
        foreach($efforts as $effort)
        {
            $estimate = new stdclass();
            $estimate->task     = $effort->objectID;
            $estimate->account  = $effort->account;
            $estimate->date     = $effort->date;
            $estimate->left     = $effort->left;
            $estimate->consumed = $effort->consumed;
            $estimate->work     = $effort->work;
            $estimate->order    = $effort->order;

            $this->dao->insert(TABLE_TASKESTIMATE)->data($estimate)->exec();
        }
        return true;
    }

    /**
     * Get title when create for object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function getModalTitle($objectType, $objectID)
    {
        switch($objectType)
        {
        case 'task':
            $task = $this->loadModel('task')->getByID($objectID);
            return $task->name;
        case 'story':
            $story = $this->loadModel('story')->getByID($objectID);
            return $story->title;
        case 'bug':
            $bug = $this->loadModel('bug')->getByID($objectID);
            return $bug->title;
        case 'testcase':
            $testcase = $this->loadModel('testcase')->getByID($objectID);
            return $testcase->title;
        case 'issue':
            $issue = $this->loadModel('issue')->getByID($objectID);
            return $issue->title;
        case 'risk':
            $risk = $this->loadModel('risk')->getByID($objectID);
            return $risk->name;
        case 'feedback':
            $feedback = $this->loadModel('feedback')->getByID($objectID);
            return $feedback->title;
        case 'ticket':
            $ticket = $this->loadModel('ticket')->getByID($objectID);
            return $ticket->title;
        default:
            return $this->lang->effort->create;
        }
    }

    /**
     * 获取影子产品跟项目ID的键值对。
     * Get the key-value pair of shadow product and project ID.
     *
     * @param  array  $productIdList
     * @param  int    $isShadow
     * @access public
     * @return array
     */
    public function getProductProjectPairs($productIdList = array(), $isShadow = false)
    {
        return $this->dao->select('t1.product,t1.project')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->where('t3.type')->eq('project')
            ->beginIF(!empty($productIdList))->andWhere('t1.product')->in($productIdList)->fi()
            ->beginIF($isShadow)->andWhere('t2.shadow')->eq('1')->fi()
            ->fetchPairs();
    }
}
