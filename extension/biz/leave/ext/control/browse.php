<?php
helper::importControl('leave');
class myLeave extends leave
{
    /**
     * browse 
     * 
     * @param  string $type 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function browse($type = 'personal', $date = '', $orderBy = 'id_desc')
    {
        /* If type is browseReview, display all leaves wait to review. */
        if($type == 'browseReview')
        {
            $date         = '';
            $currentYear  = '';
            $currentMonth = '';
        }
        else
        {
            $monthList    = $this->leave->getAllMonth($type);
            $yearList     = array_keys($monthList);
            $year         = key($monthList);
            if($date == '' or (strlen($date) != 6 and strlen($date) != 4)) $date = empty($monthList) ? '' : $year . key($monthList[$year]);
            $currentYear  = substr($date, 0, 4);
            $currentMonth = strlen($date) == 6 ? substr($date, 4, 2) : '';

            $this->view->currentYear  = $currentYear;
            $this->view->currentMonth = $currentMonth;
            $this->view->monthList    = $monthList;
            $this->view->yearList     = $yearList;
        }

        $leaveList   = array();
        $deptList    = $this->loadModel('dept')->getDeptPairs();
        $deptList[0] = '/';

        if($type == 'personal')
        {
            $leaveList = $this->leave->getList($type, $currentYear, $currentMonth, $this->app->user->account, '', '', $orderBy);
        }
        elseif($type == 'browseReview')
        {
            $reviewedBy = $this->leave->getReviewedBy();
            if($reviewedBy == $this->app->user->account)
            {
                $leaveList = $this->leave->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
            }
            elseif(!$reviewedBy)
            {
                $managedDepts = $this->loadModel('dept')->getDeptManagedByMe($this->app->user->account);
                if($managedDepts) $leaveList = $this->leave->getList($type, $currentYear, $currentMonth, '', array_keys($managedDepts), '', $orderBy);
            }
        }
        elseif($type == 'company')
        {
            $leaveList = $this->leave->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
        }

        $this->view->title          = $this->lang->leave->browse;
        $this->view->type           = $type;
        $this->view->deptList       = $deptList;
        $this->view->users          = $this->loadModel('user')->getDeptPairs();
        $this->view->leaveList      = $leaveList;
        $this->view->date           = $date;
        $this->view->orderBy        = $orderBy;
        $this->view->leftAnnualDays = $this->leave->computeAnnualDays();
        $this->display();
    }

}
