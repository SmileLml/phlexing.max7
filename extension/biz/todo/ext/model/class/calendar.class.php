<?php
class calendarTodo extends todoModel
{
    public function getTodos4Calendar($account = '', $year = '')
    {
        $lastMonth = (string)($year - 1) . '-12';
        if($account == '') $account = $this->app->user->account;
        $todos = $this->dao->select('*')->from(TABLE_TODO)
            ->where('cycle')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($account != 'all')
            ->andWhere("(assignedTo = '$account' or (assignedTo = '' and account = '$account'))")
            ->fi()
            ->beginIF($year)->andWhere("(LEFT(`date`, 4) = '$year' OR LEFT(`date`, 7) = '$lastMonth')")->fi()
            ->orderBy('date, status')
            ->fetchAll('id');

        /* Set session. */
        $this->session->set('todoReportCondition', '');
        $sql = explode('WHERE', $this->dao->get());

        if(isset($sql[1]))
        {
            $sql = explode('ORDER', $sql[1]);
            $this->session->set('todoReportCondition', $sql[0]);
        }

        $this->app->loadConfig('action');
        $events = array();
        foreach($todos as $id => $todo)
        {
            $event       = array();
            $event['id'] = $id;

            $title = '';
            $table = zget($this->config->objectTables, $todo->type, '');
            $field = zget($this->config->action->objectNameFields, $todo->type, '');
            if(!empty($table) and !empty($field) and !empty($todo->objectID))
            {
                $title = $this->dao->select("id,$field")->from($table)->where('id')->eq($todo->objectID)->fetch($field);
            }

            $event['title']   = empty($title) ? $todo->name : $title;
            $event['objectID'] = $todo->objectID;
            $event['status']  = $todo->status;
            $event['pri']     = zget($this->lang->todo->priList, $todo->pri);

            if((string)$todo->begin == '2400' and (string)$todo->end == '2400')
            {
                $event['allDay'] = true;
                $event['start']  = $todo->date;
            }
            else
            {
                $startDate = $todo->date . ' ' . substr($todo->begin, 0, 2) . ':' . substr($todo->begin, 2, 2) . ':00';
                $endDate   = $todo->date . ' ' . substr($todo->end, 0, 2)   . ':' . substr($todo->end, 2, 2)   . ':00';
                $event['allDay'] = false;
                $event['start']  = strtotime($startDate) * 1000;
                $event['end']    = strtotime($endDate) * 1000;
            }

            $event['finish'] = $todo->status == 'done' || $todo->status == 'closed';
            $event['url']    = helper::createLink('todo', 'view', "id=$id&from=my", '', true);

            $events[] = $event;
        }
        return json_encode($events);
    }

    public function getWeekTodos($begin, $end, $account = '')
    {
        $this->app->loadClass('date');
        if($account == '') $account = $this->app->user->account;

        $stmt = $this->dao->select('*')->from(TABLE_TODO)
            ->where('cycle')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->andWhere('account', true)->eq($account)
            ->orWhere('assignedTo')->eq($account)
            ->markRight(1)
            ->andWhere('date')->ge($begin)
            ->andWhere('date')->le($end)
            ->orderBy('date')
            ->query();

        $todos = array();
        while($todo = $stmt->fetch())
        {
            if($todo->type == 'bug')   $todo->name = $this->dao->findById($todo->objectID)->from(TABLE_BUG)->fetch('title');
            if($todo->type == 'task')  $todo->name = $this->dao->findById($todo->objectID)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'story') $todo->name = $this->dao->findById($todo->objectID)->from(TABLE_STORY)->fetch('title');
            $todo->begin = date::formatTime($todo->begin);
            $todo->end   = date::formatTime($todo->end);

            /* If is private, change the title to private. */
            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;
            $todos[] = $todo;
        }
        return $todos;
    }

    /**
     * Get unfinished stories, tasks, and bugs by users.
     *
     * @param string $account
     *
     * @access public
     * @return array
     */
    public function getTodos4Side($account = '')
    {
        if($account == '') $account = $this->app->user->account;

        /* Get executions and products for which change forbidden. */
        $closedProducts    = empty($this->config->CRProduct) ? $this->loadModel('product')->getList(0, 'closed') : array();
        $closedProjects    = empty($this->config->CRProject) ? $this->loadModel('program')->getProjectList(0, 'closed') : array();
        $skipProductIDList = array_keys($closedProducts);
        $skipProjectIDList = array_keys($closedProjects);

        $todos          = array();
        $todos['bug']      = $this->loadModel('bug')->getUserBugPairs($account, true, 0, $skipProductIDList, $skipProjectIDList);
        $todos['task']     = $this->loadModel('task')->getUserTaskPairs($account, 'wait,doing', $skipProjectIDList);
        $todos['story']    = $this->loadModel('story')->getUserStoryPairs($account, 10, 'story', $skipProductIDList);
        if($this->config->vision == 'rnd') $todos['testtask'] = $this->loadModel('testtask')->getUserTestTaskPairs($account, 0, 'wait,doing', $skipProductIDList, $skipProjectIDList);

        if($this->config->enableER) $todos['epic']        = $this->story->getUserStoryPairs($account, 10, 'epic', $skipProductIDList);
        if($this->config->URAndSR)  $todos['requirement'] = $this->story->getUserStoryPairs($account, 10, 'requirement', $skipProductIDList);

        if(in_array($this->config->edition, array('max', 'ipd')) && $this->config->vision == 'rnd')
        {
            $todos['issue']       = $this->loadModel('issue')->getUserIssuePairs($account, 0, 'unconfirmed,active', $skipProjectIDList);
            $todos['risk']        = $this->loadModel('risk')->getUserRiskPairs($account, 0, 'active', $skipProjectIDList);
            $todos['opportunity'] = $this->loadModel('opportunity')->getUserOpportunityPairs($account, 0, 'active', $skipProjectIDList);
        }

        if($this->config->edition == 'max' or $this->config->edition == 'ipd') $todos['review'] = $this->loadModel('review')->getUserReviewPairs($account, 0, 'wait', $skipProjectIDList);
        if($this->config->edition != 'open') $todos['feedback'] = $this->loadModel('feedback')->getUserFeedbackPairs($account);

        $userTodos = $this->dao->select('objectID,type')->from(TABLE_TODO)
            ->where('account')->eq($account)
            ->andWhere('deleted')->eq('0')
            ->andWhere('type')->in($this->config->todo->moduleList)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetchGroup('type', 'objectID');

        foreach($todos as $type => $todo)
        {
            if(!empty($todo) && !empty($userTodos[$type]))
            {
                $intersect = array_intersect(array_keys($todo), array_keys($userTodos[$type]));
                if(!empty($intersect)) foreach($intersect as $id) unset($todos[$type][$id]);
            }
        }

        return $todos;
    }
}
