<?php
class calendarCompany extends companyModel
{
    /**
     * Get effort.
     *
     * @param  int    $parent
     * @param  date   $begin
     * @param  date   $end
     * @param  int    $product
     * @param  int    $project
     * @param  int    $execution
     * @param  string $account
     * @param  int    $showUser
     * @param  string $userType inside|outside
     *
     * @access public
     * @return array
     */
    public function getEffort($parent, $begin, $end, $product = 0, $project = 0,$execution = 0, $account ='', $showUser = 'all', $userType = '')
    {
        $this->app->loadClass('date');
        $users = $this->loadModel('dept')->getDeptUserPairs((int)$parent, 'account', 'inside', 'queryAll');
        if($account) $users = array($account => $account);

        $efforts = $this->dao->select('t1.*,t2.dept')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.date')->ge($begin)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.date')->le($end)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($product)->andWhere('t1.product')->like("%,$product,%")->fi()
            ->beginIF($project)->andWhere('t1.project')->eq($project)->fi()
            ->beginIF($execution)->andWhere('t1.execution')->eq($execution)->fi()
            ->beginIF(!empty($users))->andWhere('t1.account')->in(array_keys($users))->fi()
            ->beginIF($userType)->andWhere('t2.type')->eq($userType)->fi()
            ->fetchAll('', false);

        /* Set session. */
        $sql = explode('WHERE', $this->dao->get());
        $sql = explode('ORDER', $sql[1]);
        $this->session->set('effortReportCondition', $sql[0]);

        $actions  = array();
        foreach($efforts as $effort) $actions[$effort->account][] = $effort;

        $depts           = $this->dao->select('*')->from(TABLE_DEPT)->fetchAll();
        $effort          = array();
        $selectedUser    = $account;
        $showDeleted     = isset($this->config->user->showDeleted) ? $this->config->user->showDeleted : '0';
        $deptRelation[0] = 'current';
        if($parent == 'all')
        {
            foreach($depts as $dept)
            {
                $deptList = explode(',', $dept->path);
                $deptRelation[$dept->id] = $deptList[1];
            }

            $users = $this->dao->select('account, dept')->from(TABLE_USER)
                ->where('1=1')
                ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
                ->beginIF($selectedUser)->andWhere('account')->eq($selectedUser)->fi()
                ->beginIF(empty($selectedUser) && !$showDeleted)->andWhere('deleted')->eq(0)->fi()
                ->beginIF($userType)->andWhere('type')->eq($userType)->fi()
                ->fetchAll();
        }
        else
        {
            $parentDept = $this->loadModel('dept')->getById($parent);
            $childDepts = $this->dept->getAllChildId($parent);

            foreach($depts as $dept)
            {
                if(in_array($dept->id, $childDepts))
                {
                    $deptList = explode(',', trim(str_replace($parentDept->path, '', $dept->path), ','));
                    $deptRelation[$dept->id] = $dept->id == $parent ? 'current' : $deptList[0];
                }
            }

            $users = $this->dao->select('account, dept')->from(TABLE_USER)
                ->where('1=1')
                ->beginIF($parentDept)->andWhere('dept')->in($childDepts)->fi()
                ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
                ->beginIF($selectedUser)->andWhere('account')->eq($selectedUser)->fi()
                ->beginIF(empty($selectedUser) && !$showDeleted)->andWhere('deleted')->eq(0)->fi()
                ->beginIF($userType)->andWhere('type')->eq($userType)->fi()
                ->fetchAll();
        }

        foreach($users as $user)
        {
            $account  = $user->account;
            $deptID   = zget($deptRelation, $user->dept);
            $showUser = strtolower($showUser);

            if($selectedUser and $selectedUser == $account)
            {
                if(!isset($effort[$deptID][$account])) $effort[$deptID][$account] = array();
            }
            else
            {
                if($showUser == 'notlogged' and isset($actions[$account])) continue;
                if($showUser == 'logged' and !isset($actions[$account])) continue;
                $effort[$deptID][$account] = array();
            }

            if(!isset($actions[$account])) continue;

            foreach($actions[$account] as $action)
            {
                $effort[$deptID][$account][$action->date][] = array('id' => $action->id, 'begin' => date::formatTime($action->begin), 'end' => date::formatTime($action->end), 'work' => $action->work, 'consumed' => $action->consumed);
            }
        }

        return $effort;
    }

    /**
     * Get todo.
     *
     * @param  int    $parent
     * @param  date   $begin
     * @param  date   $end
     * @param  object $pager
     * @access public
     * @return array
     *
     */
    public function getTodo($parent, $begin, $end, $pager = null)
    {
        $this->app->loadClass('date');
        $parent  = (int)$parent;
        $end     = date('Y-m-d', strtotime("$end +1 day"));
        $actions = $this->dao->select('*')->from(TABLE_TODO)
            ->where('date')->ge($begin)
            ->andWhere('date')->le($end)
            ->andWhere('deleted')->eq('0')
            ->fetchGroup('assignedTo');

        /* get task and bug when todo's type is task or bug. */
        $todoTaskID = $this->dao->select('objectID')->from(TABLE_TODO)
            ->where('date')->ge($begin)
            ->andWhere('date')->le($end)
            ->andWhere('type')->eq('task')
            ->andWhere('deleted')->eq('0')
            ->fetchPairs('objectID');
        $todoBugID = $this->dao->select('objectID')->from(TABLE_TODO)
            ->where('date')->ge($begin)
            ->andWhere('date')->le($end)
            ->andWhere('type')->eq('bug')
            ->andWhere('deleted')->eq('0')
            ->fetchPairs('objectID');
        $tasks = $this->dao->select('id, name')->from(TABLE_TASK)->where('id')->in($todoTaskID)->fetchPairs('id', 'name');
        $bugs  = $this->dao->select('id, title')->from(TABLE_BUG)->where('id')->in($todoBugID)->fetchPairs('id', 'title');

        foreach($actions as $account => $todos)
        {
            foreach($todos as $todo)
            {
                if($todo->type == 'task') $todo->name = $tasks[$todo->objectID];
                if($todo->type == 'bug')  $todo->name = $bugs[$todo->objectID];
            }
        }

        $depts   = $this->dao->select('*')->from(TABLE_DEPT)->fetchAll();
        $todo    = array();
        $orderBy = 'dept,account';
        $deptRelation[0] = 'current';
        if($parent != 0)
        {
            $parentDept = $this->loadModel('dept')->getById($parent);
            $childDepts = $this->dept->getAllChildId($parent);

            foreach($depts as $dept)
            {
                if(in_array($dept->id, $childDepts))
                {
                    $deptList = explode(',', trim(str_replace($parentDept->path, '', $dept->path), ','));
                    $deptRelation[$dept->id] = $dept->id == $parent ? 'current' : $deptList[0];
                }
            }

            $users = $this->dao->select('account, dept')->from(TABLE_USER)
                ->where('dept')->in($childDepts)
                ->andWhere('deleted')->eq(0)
                ->andWhere("FIND_IN_SET('{$this->config->vision}', visions)")
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll();
        }
        else
        {
            foreach($depts as $dept)
            {
                $deptList = explode(',', $dept->path);
                $deptRelation[$dept->id] = $dept->id == $parent ? 'current' : $deptList[1];
            }
            $users = $this->dao->select('account, dept')->from(TABLE_USER)->where('deleted')->eq(0)
                ->andWhere("FIND_IN_SET('{$this->config->vision}', visions)")
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll();
        }

        foreach($users as $user)
        {
            $account = $user->account;
            $deptID  = $deptRelation[$user->dept];

            if(!isset($todo[$deptID])) $todo[$deptID] = array();
            if(!isset($todo[$deptID][$account])) $todo[$deptID][$account] = array();

            if(!isset($actions[$account])) continue;
            foreach($actions[$account] as $action)
            {
                if(!isset($todo[$deptID][$account][$action->date])) $todo[$deptID][$account][$action->date] = array();
                $todo[$deptID][$account][$action->date][] = array('begin' => date::formatTime($action->begin), 'end' => date::formatTime($action->end), 'todo' => $action->name);
            }
        }
        return $todo;
    }

    /**
     * Get children of department.
     *
     * @param  int    $parent
     * @access public
     * @return array
     */
    public function getChildren($parent)
    {
        $deptID = $parent == 'all' ? 0 : $parent;
        $depts = $this->dao->select('*')->from(TABLE_DEPT)->where('parent')->eq($deptID)->fetchAll();
        $children = array();
        foreach($depts as $dept)
        {
            $children[$dept->id] = $dept->name;
        }
        return $children;
    }

    /**
     * 获取指定部门下的用户列表。
     * Get user pairs of a department.
     *
     * @param  int    $deptID
     * @param  string $type    inside|outside
     * @param  string $params  all
     * @access public
     * @return array
     */
    public function getDeptUserPairs($deptID = 0, $type = 'inside', $params = '')
    {
        if(strpos($params, 'queryAll') === false) return $this->loadModel('dept')->getDeptUserPairs($deptID, 'id', $type, $params);

        $childDepts = $this->loadModel('dept')->getAllChildID($deptID);
        $type       = $type == 'outside' ? 'outside' : 'inside';

        $userList = $this->dao->select("id, account, realname, deleted")->from(TABLE_USER)
            ->where('1=1')
            ->beginIF(strpos($params, 'all') === false)->andWhere('type')->eq($type)->fi()
            ->beginIF($childDepts)->andWhere('dept')->in($childDepts)->fi()
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->orderBy('account')
            ->fetchAll();
        if(empty($userList)) return array();

        $userPairs = array();
        foreach($userList as $user) $userPairs[$user->id] = $user->deleted ? $user->account : $user->realname;
        return $userPairs;
    }
}
