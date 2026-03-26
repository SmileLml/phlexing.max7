<?php
class myReport extends report
{
    /**
     * Obtain project workload statistics table data.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function projectWorkload($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        $this->report->buildReportList($projectID);

        $this->view->title      = $this->lang->report->projectWorkload;
        $this->view->position[] = $this->lang->report->projectWorkload;
        $this->view->stages     = $this->loadModel('execution')->getList($projectID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->submenu    = 'program';
        $this->view->projectID  = $projectID;

        $projectIdList = array_keys($this->view->stages);
        $this->view->pvList    = $this->report->getPV($projectIdList);
        $this->view->evList    = $this->report->getEV($projectIdList);
        $this->view->staffList = $this->report->getStaff($projectIdList);

        $this->display();
    }
}
