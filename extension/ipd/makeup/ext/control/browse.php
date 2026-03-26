<?php
helper::importControl('makeup');
class myMakeup extends makeup
{
    /**
     * Browse makeup.
     * 
     * @param  string   $type 
     * @param  string   $date 
     * @param  string   $orderBy 
     * @access public
     * @return void
     */
    public function browse($type = 'personal', $date = '', $orderBy = 'id_desc')
    {
        /* If type is browseReview, display all makeups wait to review. */
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
            $monthList    = $this->makeup->getAllMonth($type);
            $yearList     = array_keys($monthList);

            $this->view->currentYear  = $currentYear;
            $this->view->currentMonth = $currentMonth;
            $this->view->monthList    = $monthList;
            $this->view->yearList     = $yearList;
        }

        $makeupList  = array();
        $deptList    = $this->loadModel('dept')->getDeptPairs();
        $deptList[0] = '';

        if($type == 'personal')
        {
            $makeupList = $this->makeup->getList($type, $currentYear, $currentMonth, $this->app->user->account, '', '', $orderBy);
        }
        elseif($type == 'browseReview')
        {
            $reviewedBy = $this->makeup->getReviewedBy();
            if($reviewedBy == $this->app->user->account)
            {
                $makeupList = $this->makeup->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
            }
            elseif(!$reviewedBy)
            {
                $managedDepts = $this->loadModel('dept')->getDeptManagedByMe($this->app->user->account);
                if($managedDepts) $makeupList = $this->makeup->getList($type, $currentYear, $currentMonth, '', array_keys($managedDepts), '', $orderBy);
            }
        }
        elseif($type == 'company')
        {
            $makeupList = $this->makeup->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
        }

        $this->session->set('makeupList', $this->app->getURI(true));

        $this->view->title      = $this->lang->makeup->browse;
        $this->view->type       = $type;
        $this->view->deptList   = $deptList;
        $this->view->users      = $this->loadModel('user')->getDeptPairs();
        $this->view->makeupList = $makeupList;
        $this->view->date       = $date;
        $this->view->orderBy    = $orderBy;
        $this->display();
    }
}
