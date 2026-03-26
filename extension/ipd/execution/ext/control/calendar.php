<?php
/**
 * The control file of calendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
helper::importControl('execution');
class myexecution extends execution
{
    public function calendar($executionID)
    {
        $users = $this->loadModel('user')->getPairs('noletter');

        $events    = array();
        $startDate = '';
        $space     = common::checkNotCN() ? ' ' : '';
        $taskGroup = $this->execution->getTasks4Calendar($executionID);
        foreach($taskGroup as $cacheId => $dateGroup)
        {
            ksort($dateGroup);
            foreach($dateGroup as $date => $tasks)
            {
                if(empty($startDate)) $startDate = $date;
                foreach($tasks as $task)
                {
                    if(isset($task['action']) && isset($task['actor']))
                    {
                        $icon = $task['action'] == 'finished' ? "<i class='icon-task-finish icon-checked'></i>" : "<i class='icon-task-start icon-play'></i>";
                        $task['action']    = $this->lang->execution->{$task['action']};
                        $task['actor']     = zget($users, $task['actor']);
                        $task['divTitle']  = $task['actor'] . $space . $task['action'] . $space . $this->lang->task->common . ': ' . $task['title'];
                        $task['iconTitle'] = $task['actor'] . $icon . $this->lang->task->common . ': ' . $task['title'];
                    }
                    $events[] = $task;
                }
            }
        }

        $this->execution->setMenu($executionID);
        $this->view->title       = $this->lang->execution->calendar;
        $this->view->executionID = $executionID;
        $this->view->startDate   = $startDate;
        $this->view->events      = $events;
        $this->view->execution   = $this->execution->getByID($executionID);
        $this->view->startDate   = $startDate;
        $this->view->orderBy     = 'status_asc,id_desc';
        $this->display();
    }
}
