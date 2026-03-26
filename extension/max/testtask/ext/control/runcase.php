<?php
helper::importControl('testtask');
class myTesttask extends testtask
{
    /**
     * @param int $runID
     * @param int $caseID
     * @param int $version
     * @param string $confirm
     */
    public function runCase($runID, $caseID = 0, $version = 0, $confirm = '')
    {
        if($this->app->tab == 'devops') $this->view->deployID = $this->session->deployID;
        parent::runCase($runID, $caseID, $version, $confirm);
    }
}
