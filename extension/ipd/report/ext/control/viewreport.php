<?php
class myReport extends report
{
    public function viewReport($reportID = 0)
    {

        $report = $this->report->getMeasReportByID($reportID);
        $this->report->buildReportList($report->project);

        $this->loadModel('project')->setMenu($report->project);

        $this->view->title      = $report->name;
        $this->view->position[] = $report->name;
        $this->view->report     = $report;
        $this->view->submenu    = 'program';
        $this->view->projectID  = $report->project;
        $this->display();
    }
}
