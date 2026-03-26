<?php
helper::importControl('overtime');
class myOvertime extends overtime
{
    /**
     * Browse overtime.
     * 
     * @param  string   $type 
     * @param  string   $date 
     * @param  string   $orderBy 
     * @access public
     * @return void
     */
    public function browse($type = 'personal', $date = '', $orderBy = 'id_desc')
    {
        /* If type is browseReview, display all overtimes wait to review. */
        if($type == 'browseReview')
        {
            $date         = '';
            $currentYear  = '';
            $currentMonth = '';
        }
        else
        {
            if($date == '' or (strlen($date) != 6 and strlen($date) != 4)) $date = date("Ym");
            $currentYear  = substr($date, 0, 4);
            $currentMonth = strlen($date) == 6 ? substr($date, 4, 2) : '';
            $monthList    = $this->overtime->getAllMonth($type);
            $yearList     = array_keys($monthList);

            $this->view->currentYear  = $currentYear;
            $this->view->currentMonth = $currentMonth;
            $this->view->monthList    = $monthList;
            $this->view->yearList     = $yearList;
        }

        $overtimeList = array();
        $deptList     = $this->loadModel('dept')->getDeptPairs();
        $deptList[0]  = '';

        if($type == 'personal')
        {
            $overtimeList = $this->overtime->getList($type, $currentYear, $currentMonth, $this->app->user->account, '', '', $orderBy);
        }
        elseif($type == 'browseReview')
        {
            $reviewedBy = $this->overtime->getReviewedBy();
            if($reviewedBy == $this->app->user->account)
            {
                $overtimeList = $this->overtime->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
            }
            elseif(!$reviewedBy)
            {
                $managedDepts = $this->loadModel('dept')->getDeptManagedByMe($this->app->user->account);
                if($managedDepts) $overtimeList = $this->overtime->getList($type, $currentYear, $currentMonth, '', array_keys($managedDepts), '', $orderBy);
            }
        }
        elseif($type == 'company')
        {
            $overtimeList = $this->overtime->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
        }

        $this->session->set('overtimeList', $this->app->getURI(true));

        $this->view->title        = $this->lang->overtime->browse;
        $this->view->type         = $type;
        $this->view->deptList     = $deptList;
        $this->view->users        = $this->loadModel('user')->getDeptPairs();
        $this->view->overtimeList = $overtimeList;
        $this->view->date         = $date;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }
}
