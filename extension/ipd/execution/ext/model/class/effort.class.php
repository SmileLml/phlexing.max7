<?php
/**
 * The model file of effort module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     effort
 * @link        https://www.zentao.net
 */
class effortExecution extends executionModel
{
    /**
     * Compute task effort.
     *
     * @param  string  $date
     * @access public
     * @return void
     */
    public function computeTaskEffort($date = '')
    {
        if(!$date) $date = helper::today();
        $processedTaskIdList = $this->dao->select('objectID, sum(consumed) as consumed')->from(TABLE_EFFORT)
            ->where("date")->eq($date)
            ->andWhere("objectType")->eq('task')
            ->andWhere('deleted')->eq(0)
            ->groupBy('objectID')
            ->fetchPairs('objectID');
        $tasks = $this->dao->select('t1.id as task, t1.execution, t1.left')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere("t1.id")->in(array_keys($processedTaskIdList))
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll('task');

        /* Update the consumed of task modified date in the burn table.*/
        foreach($tasks as $task)
        {
            $task->date     = $date;
            $task->consumed = zget($processedTaskIdList, $task->task);
            $this->dao->replace(TABLE_BURN)->data($task)->exec();
        }

        /* Fix for task #9017. When create effort in current date, but effort date is not current date. */
        $efforts = $this->dao->select('t1.*')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on("t1.id=t2.objectID")
            ->where('t2.objectType')->eq('effort')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere("LEFT(t2.date, 10)")->eq($date)
            ->andWhere("t1.date")->ne($date)
            ->andWhere("t1.objectType")->eq('task')
            ->andWhere("t2.action")->ne('deleted')
            ->orderBy('id')
            ->fetchAll('id');

        if($efforts)
        {
            foreach($efforts as $effort)
            {
                $sumConsumed = $this->dao->select('sum(consumed) as sumConsumed')->from(TABLE_EFFORT)
                    ->where('date')->eq($effort->date)
                    ->andWhere('deleted')->eq(0)
                    ->andWhere('objectID')->eq($effort->objectID)
                    ->andWhere('objectType')->eq($effort->objectType)
                    ->fetch('sumConsumed');

                $burn = new stdclass();
                $burn->execution  = $effort->execution;
                $burn->task     = $effort->objectID;
                $burn->date     = $effort->date;
                $burn->consumed = $sumConsumed;
                $burn->left     = $effort->left;

                $this->dao->replace(TABLE_BURN)->data($burn)->exec();
            }
        }
    }

    /**
     * Get task effort.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getTaskEffort($executionID)
    {
        $burns = $this->dao->select('`task`, `date`, `left`, `consumed`')->from(TABLE_BURN)
            ->where('execution')->eq($executionID)
            ->andWhere('`task`')->gt(0)
            ->fetchGroup('task', 'date');
        $tasks = $this->loadModel('task')->getExecutionTasks($executionID, 0, 'all', array(), 'story_desc');

        $taskEfforts = array();
        $totalCount  = new stdClass();
        foreach($tasks as $id => $task)
        {
            $tmpTask = unserialize(serialize($task));
            unset($tmpTask->desc);

            if(isset($burns[$id]))
            {
                foreach($burns[$id] as $date => $burn)
                {
                    if(empty($burn->left) and empty($burn->consumed)) continue;

                    if(!isset($totalCount->{$date}))
                    {
                        $totalCount->{$date} = new stdclass();
                        $totalCount->{$date}->countLeft = 0;
                        $totalCount->{$date}->countConsumed = 0;
                    }
                    if(!isset($tmpTask->burn)) $tmpTask->burn = new stdClass();
                    $tmpTask->burn->{$date} = new stdClass();
                    $tmpTask->burn->{$date}->left     = $burn->left;
                    $tmpTask->burn->{$date}->consumed = $burn->consumed;

                    $totalCount->{$date}->countLeft     += $burn->left;
                    $totalCount->{$date}->countConsumed += $burn->consumed;
                }
            }
            if(isset($task->team) and is_array($task->team)) $tmpTask->multiple = 1;
            if(isset($task->children))
            {
                $tmpTask->children = array();
                foreach($task->children as $children)
                {
                    $tmpChildren = json_encode($children);
                    $tmpChildren = json_decode($tmpChildren);
                    $tmpChildren->assignedToRealName = $children->assignedToRealName;

                    if(isset($burns[$children->id]))
                    {
                        foreach($burns[$children->id] as $date => $burn)
                        {
                            if(empty($burn->left) and empty($burn->consumed)) continue;

                            if(!isset($totalCount->{$date}))
                            {
                                $totalCount->{$date} = new stdclass();
                                $totalCount->{$date}->countLeft = 0;
                                $totalCount->{$date}->countConsumed = 0;
                            }

                            if(!isset($tmpChildren->burn)) $tmpChildren->burn = new stdClass();
                            $tmpChildren->burn->{$date} = new stdClass();
                            $tmpChildren->burn->{$date}->left     = $burn->left;
                            $tmpChildren->burn->{$date}->consumed = $burn->consumed;

                            $totalCount->{$date}->countLeft     += $burn->left;
                            $totalCount->{$date}->countConsumed += $burn->consumed;
                        }
                    }

                    $tmpTask->children[$tmpChildren->id] = $tmpChildren;
                }
            }
            $taskEfforts[$id] = $tmpTask;
        }
        $taskEfforts['count'] = $totalCount;
        return $taskEfforts;
    }
}
