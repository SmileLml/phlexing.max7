<?php
helper::importControl('todo');
class mytodo extends todo
{
    public function export($userID, $orderBy, $date = '')
    {
        if($date)
        {
            $date  = str_replace('_', '-', $date);
            $time  = strtotime($date);
            $begin = date('Y-m', $time) . '-01';
            $end   = date('Y-m', strtotime('+1 month', $time)) . '-01';
            if($this->session->todoReportCondition) $this->session->todoReportCondition .= ' AND ';
            $this->session->todoReportCondition .= "(date >= '$begin' AND date < '$end')";
        }
        return parent::export($userID, $orderBy);
    }
}
