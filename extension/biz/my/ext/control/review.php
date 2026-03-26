<?php
class my extends control
{
    public function review($type = 'all', $orderBy = 'status')
    {
        $account = $this->app->user->account;

        /* Get dept info. */
        $allDeptList = $this->loadModel('dept')->getDeptPairs();
        $allDeptList['0'] = '/';
        $managedDeptList = array();
        $tmpDept = $this->dept->getDeptManagedByMe($account);
        foreach($tmpDept as $d) $managedDeptList[$d->id] = $d->name;

        $attends   = array();
        $leaves    = array();
        $overtimes = array();
        $makeups   = array();
        $lieus     = array();

        if($type == 'all' || $type == 'attend')   $attends   = $this->my->getReviewingAttends($allDeptList, $managedDeptList);
        if($type == 'all' || $type == 'leave')    $leaves    = $this->my->getReviewingLeaves($allDeptList, $managedDeptList, $orderBy);
        if($type == 'all' || $type == 'overtime') $overtimes = $this->my->getReviewingOvertimes($allDeptList, $managedDeptList, $orderBy);
        if($type == 'all' || $type == 'makeup')   $makeups   = $this->my->getReviewingMakeups($allDeptList, $managedDeptList, $orderBy);
        if($type == 'all' || $type == 'lieu')     $lieus     = $this->my->getReviewingLieus($allDeptList, $managedDeptList, $orderBy);

        /* The header and position. */
        $this->view->title      = $this->lang->my->common . $this->lang->hyphen . $this->lang->my->review;
        $this->view->position[] = $this->lang->my->review;

        $this->view->title        = $this->lang->my->review;
        $this->view->attends      = $attends;
        $this->view->leaveList    = $leaves;
        $this->view->overtimeList = $overtimes;
        $this->view->makeupList   = $makeups;
        $this->view->lieuList     = $lieus;
        $this->view->deptList     = $allDeptList;
        $this->view->users        = $this->loadModel('user')->getDeptPairs();
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }
}
