<?php
class myReport extends report
{
    public function projectSummary($projectID = 0)
    {
        $this->app->loadLang('product');
        $this->app->loadLang('productplan');
        $this->app->loadLang('story');
        $this->app->loadLang('execution');

        $this->loadModel('project')->setMenu($projectID);

        $this->report->buildReportList($projectID);

        $this->view->title      = $this->lang->report->projectSummary;
        $this->view->position[] = $this->lang->report->projectSummary;
        $this->view->stages     = $this->report->getProjectByType($projectID, 'all');
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->submenu    = 'program';
        $this->view->projectID  = $projectID;
        $this->display();
    }
}
