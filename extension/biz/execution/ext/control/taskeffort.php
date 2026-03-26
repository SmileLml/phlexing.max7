<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 */
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * TaskEffort
     *
     * @param  int    $executionID
     * @param  string $groupBy
     * @param  string $type         noweekend|withweekend
     * @access public
     * @return void
     */
    public function taskEffort($executionID, $groupBy = 'story', $type = 'noweekend')
    {
        $this->app->session->set('taskList',  $this->app->getURI(true), 'execution');

        $this->execution->setMenu($executionID);

        $this->app->loadLang('task');
        $this->lang->task->statusList['changed'] = $this->lang->task->storyChange;

        $taskLang  = $this->lang->task;
        $users     = $this->loadModel('user')->getPairs('noletter');
        $today     = helper::today();
        $execution = $this->execution->getByID($executionID);

        if($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review')))
        {
            if($groupBy == 'story') $groupBy = 'status';
            unset($this->lang->execution->groups['story']);
        }

        $this->app->loadClass('date');
        $tasks = $this->execution->getTaskEffort($executionID);

        $groupTasks  = array();
        $taskAll     = array();
        $groupByList = array();
        $counts      = $tasks['count'];
        unset($tasks['count']);

        foreach($tasks as $task)
        {
            if(isset($task->children))
            {
                foreach($task->children as $child) $groupTasks[] = $child;
            }
            else
            {
                $groupTasks[] = $task;
            }
        }

        $tasks      = $groupTasks;
        $groupTasks = array();

        if(!empty($tasks))
        {
            foreach($tasks as $taskKey)
            {
                $taskAll[] = $taskKey->id;
            }
        }

        $efforts     = $this->loadModel('task')->getTaskDateRecord($taskAll);
        $effortsDate = helper::arrayColumn($efforts, 'date');

        if(!empty($effortsDate))
        {
            $execution->begin = $effortsDate[0] <= $execution->begin ? $effortsDate[0] : $execution->begin;
            $lastDate         = end($effortsDate);
            $execution->end   = $lastDate >= $execution->end ? $lastDate : $execution->end;
        }

        $dateList = date::getDateList($execution->begin, ($today >= $execution->end ? $execution->end : $today), 'Y-m-d', $type, $this->config->execution->weekend);
        foreach($tasks as $task)
        {
            if($groupBy == 'story')
            {
                $groupTasks[$task->story][] = $task;
                $groupByList[$task->story]  = $task->storyTitle;
            }
            elseif($groupBy == 'status')
            {
                $groupTasks[$taskLang->statusList[$task->status]][] = $task;
            }
            elseif($groupBy == 'assignedTo')
            {
                if(isset($task->team) and is_array($task->team))
                {
                    foreach($task->team as $team)
                    {
                        $cloneTask = clone $task;
                        $cloneTask->assignedTo = $team->account;
                        $cloneTask->estimate   = $team->estimate;
                        $cloneTask->consumed   = $team->consumed;
                        $cloneTask->left       = $team->left;
                        if($team->left == 0) $cloneTask->status = 'done';

                        $realname = zget($users, $team->account);
                        $cloneTask->assignedToRealName = $realname;
                        $groupTasks[$realname][] = $cloneTask;
                    }
                }
                else
                {
                    $groupTasks[$task->assignedToRealName][] = $task;
                }
            }
            elseif($groupBy == 'finishedBy')
            {
                if(isset($task->team) and is_array($task->team))
                {
                    $task->consumed = $task->estimate = $task->left = 0;
                    foreach($task->team as $team)
                    {
                        if($team->left != 0)
                        {
                            $task->estimate += $team->estimate;
                            $task->consumed += $team->consumed;
                            $task->left     += $team->left;
                            continue;
                        }

                        $cloneTask = clone $task;
                        $cloneTask->finishedBy = $team->account;
                        $cloneTask->estimate   = $team->estimate;
                        $cloneTask->consumed   = $team->consumed;
                        $cloneTask->left       = $team->left;
                        $cloneTask->status     = 'done';
                        $realname = zget($users, $team->account);
                        $groupTasks[$realname][] = $cloneTask;
                    }
                    if(!empty($task->left)) $groupTasks[$users[$task->finishedBy]][] = $task;
                }
                else
                {
                    $groupTasks[$users[$task->finishedBy]][] = $task;
                }
            }
            elseif($groupBy == 'closedBy')
            {
                $groupTasks[$users[$task->closedBy]][] = $task;
            }
            elseif($groupBy == 'type')
            {
                $groupTasks[$taskLang->typeList[$task->type]][] = $task;
            }
            else
            {
                $groupTasks[$task->$groupBy][] = $task;
            }
        }
        /* Process closed data when group by assignedTo. */
        if($groupBy == 'assignedTo' and isset($groupTasks['Closed']))
        {
            $closedTasks = $groupTasks['Closed'];
            unset($groupTasks['Closed']);
            $groupTasks['closed'] = $closedTasks;
        }

        /* Assign. */
        $this->view->title       = $execution->name . $this->lang->hyphen . $this->lang->execution->taskEffort;
        $this->view->position[]  = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $this->view->position[]  = $this->lang->execution->taskEffort;
        $this->view->execution   = $execution;
        $this->view->executionID = $executionID;
        $this->view->groupBy     = $groupBy;
        $this->view->groupByList = $groupByList;
        $this->view->tasks       = $groupTasks;
        $this->view->type        = $type;
        $this->view->dateList    = $dateList;
        $this->view->counts      = $counts;

        $this->display();
    }
}
