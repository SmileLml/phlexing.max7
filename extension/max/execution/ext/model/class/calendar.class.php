<?php
class calendarExecution extends executionModel
{
    /**
     * Get tasks for calendar.
     *
     * @param  int    $executionID
     * @param  string $status all|undone|needconfirm|assignedtome|delayed|finishedbyme|myinvolved
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTasks4Calendar($executionID, $status = 'all', $orderBy = 'status_asc, id_desc', $pager = null)
    {
        $tasks     = $this->loadmodel('task')->getExecutionTasks($executionID, 0, $status, array(), $orderBy, $pager);
        $actions   = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('execution')->eq($executionID)->andWhere('action')->in('started,finished,edited')->orderBy('id_asc')->fetchGroup('objectID');
        $execution = $this->getById($executionID);

        foreach($tasks as $taskID => $task)
        {
            if(!empty($task->children))
            {
                foreach($task->children as $task) $tasks[$task->id] = $task;
                unset($tasks[$taskID]);
            }
        }

        $events = array();
        foreach($tasks as $task)
        {
            $event = array();
            $event['id']     = $task->id;
            $event['title']  = $task->name;
            $event['status'] = $task->status;
            $event['start']  = '';
            $event['end']    = '';
            if(!helper::isZeroDate($task->estStarted)) $event['start']  = $task->estStarted;
            if(!helper::isZeroDate($task->realStarted)) $event['start'] = $task->realStarted;
            if(!helper::isZeroDate($task->deadline))    $event['end']   = $task->deadline;
            if(empty($event['start'])) $event['start'] = $execution->begin;
            if(empty($event['end']))   $event['end']   = $execution->end;
            if($task->parent > 0 && $task->isParent == '0') $event['title'] = '[' . $this->lang->task->childrenAB . ']' . $event['title'];

            if(isset($actions[$task->id]))
            {
                foreach($actions[$task->id] as $action)
                {
                    if($action->action == 'edited' && !in_array($task->status, array('doing', 'done'))) continue;

                    $event['actor']  = $action->actor;
                    $event['action'] = $action->action;
                    if($action->action == 'started')
                    {
                        $event['start'] = $action->date;
                    }
                    elseif($action->action == 'finished')
                    {
                        $event['end'] = $action->date;
                    }
                }
                if(!empty($event['action']) && $event['action'] == 'edited') $event['action'] = $task->status == 'doing' ? 'started' : 'finished';
            }

            /* Judge the date of task, make sure the date in one day. */
            if($event['start'] == $execution->begin and $event['end'] == $execution->end) $event['end'] = $execution->begin;
            if(helper::diffDate($event['end'], $event['start']) > 1)
            {
                if($event['start'] != $execution->begin) $event['end']   = $event['start'];
                if($event['start'] == $execution->begin) $event['start'] = $event['end'];
            }

            $date = date('Y-m-d', strtotime($event['start']));
            $event['cacheId'] = substr($date, 0, 7);
            $event['url']     = helper::createLink("task", 'view', "id=$task->id", '', true);

            /* Fix bug for safari display */
            $event['start'] = date(DT_DATE1, strtotime($event['start']));
            $event['end']   = date(DT_DATE1, strtotime($event['end']));
            $events[$event['cacheId']][$date][] = $event;
        }
        ksort($events);
        return $events;
    }

    /**
     * Get efforts for calendar.
     *
     * @param  int    $executionID
     * @param  string $account
     * @param  string $year
     * @access public
     * @return json
     */
    public function getEfforts4Calendar($executionID, $account = '', $year = '')
    {
        $efforts = $this->dao->select('t1.*,t2.dept')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.execution')->eq($executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($account != '')->andWhere('t1.account')->eq($account)->fi()
            ->beginIF($year)->andWhere("(LEFT(t1.`date`, 4) = '$year')")->fi()
            ->fetchAll('id', false);

        /* Set session. */
        $this->session->set('effortReportCondition', '');
        $sql = explode('WHERE', $this->dao->get());
        if(isset($sql[1]))
        {
            $sql = explode('ORDER', $sql[1]);
            $this->session->set('effortReportCondition', $sql[0]);
        }

        $events = array();
        $users  = $this->loadModel('user')->getPairs('noletter');
        $space  = common::checkNotCN() ? ' ' : '';
        foreach($efforts as $id => $effort)
        {
            $event['id']       = $effort->id;
            $event['start']    = $effort->date;
            $event['end']      = $effort->date;
            $event['url']      = helper::createLink('effort', 'view', "id=$effort->id", '', true);
            $event['consumed'] = $effort->consumed;
            $event['title']    = $effort->work;
            $event['divTitle'] = zget($users, $effort->account) . $space . $effort->work;
            if(strpos($effort->work, $this->lang->execution->finished) !== false) $event['title'] = str_replace($this->lang->execution->finished, zget($users, $effort->account) . "<i class='icon-task-finish icon-checked'></i>",  $effort->work);
            if(strpos($effort->work, $this->lang->execution->started) !== false) $event['title'] = str_replace($this->lang->execution->started, zget($users, $effort->account) . "<i class='icon-task-start icon-play'></i>",  $effort->work);
            $event['account']  = $effort->account;
            if($effort->objectType == 'custom') $event['url']   = '';
            if($effort->objectType != 'custom') $event['title'] = '[' . strtoupper($effort->objectType[0]) . ']' . $event['title'];

            $events[] = $event;
        }
        return json_encode($events);
    }
}
