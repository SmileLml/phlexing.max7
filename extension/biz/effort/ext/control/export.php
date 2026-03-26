<?php
helper::importControl('effort');
class myeffort extends effort
{
    public function export($userID, $orderBy = 'id_desc', $date = '', $executionID = 0)
    {
        if($date)
        {
            $date  = str_replace('_', '-', $date);
            $time  = strtotime($date);
            $begin = date('Y-m', $time) . '-01';
            $end   = date('Y-m', strtotime('+1 month', $time)) . '-01';
            if($this->session->effortReportCondition) $this->session->effortReportCondition .= ' AND ';
            $this->session->effortReportCondition .= "(t1.date >= '$begin' AND t1.date < '$end')";
        }
        if($executionID)
        {
            $execution = $this->loadModel('execution')->getByID($executionID);
            $this->view->fileName = $execution->name . $this->lang->dash . $this->lang->effort->common;
        }

        return parent::export($userID, $orderBy);
    }
}
