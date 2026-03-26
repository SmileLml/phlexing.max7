<?php
class myReport extends report
{
    public function customeredReport($projectID = 0, $templateID = 0, $reportID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        $template = $this->loadModel('measurement')->getTemplateByID($templateID);

        $this->report->buildReportList($projectID);

        $this->view->title      = $template->name;
        $this->view->position[] = $template->name;

        $this->view->submenu   = 'program';
        $this->view->projectID = $projectID;
        $this->view->template  = $template;
        $this->view->reports   = $this->report->getMeasReports($projectID, $templateID);
        $this->display();
    }
}
