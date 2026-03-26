<?php
class marketresearchZen extends marketresearch
{
    /**
     * 构建调研团队成员信息。
     * Build research team member information.
     *
     * @param  array  $currentMembers
     * @param  array  $deptUsers
     * @param  int    $days
     * @access protected
     * @return array
     */
    protected function buildMembers($currentMembers, $deptUsers, $days)
    {
        $teamMembers = array();
        foreach($currentMembers as $account => $member)
        {
            $member->memberType = 'default';
            $teamMembers[$account] = $member;
        }

        $roles = $this->loadModel('user')->getUserRoles(array_keys($deptUsers));
        foreach($deptUsers as $deptAccount => $userName)
        {
            if(isset($currentMembers[$deptAccount])) continue;

            $deptMember = new stdclass();
            $deptMember->memberType = 'dept';
            $deptMember->account    = $deptAccount;
            $deptMember->role       = zget($roles, $deptAccount, '');
            $deptMember->days       = $days;
            $deptMember->hours      = $this->config->execution->defaultWorkhours;
            $deptMember->limited    = 'no';

            $teamMembers[$deptAccount] = $deptMember;
        }

        for($j = 0; $j < 5; $j ++)
        {
            $newMember = new stdclass();
            $newMember->memberType = 'add';
            $newMember->account    = '';
            $newMember->role       = '';
            $newMember->days       = $days;
            $newMember->hours      = $this->config->execution->defaultWorkhours;
            $newMember->limited    = 'no';

            $teamMembers[] = $newMember;
        }

        return $teamMembers;
    }

    /**
     * Get footer summary.
     *
     * @param mixed $stats
     * @access public
     * @return void
     */
    public function getSummary($stats = array())
    {
        $parentStage = 0;
        $childStage  = 0;
        $tasks       = 0;
        foreach($stats as $object)
        {
            if($object->type == 'stage' && $object->grade == 1) $parentStage++;
            if($object->type == 'stage' && $object->grade > 1) $childStage++;
            if($object->type == 'research') $tasks++;
        }

        $this->lang->marketresearch->summary = sprintf($this->lang->marketresearch->summary, $parentStage, $childStage, $tasks);
    }
}
