<?php
class testtask extends control
{
    public function deployCaseResults($deployID, $caseID = 0, $version = 1)
    {
        $case    = $this->loadModel('testcase')->getByID($caseID, $version);
        $results = $this->testtask->getDeployResults($deployID, $caseID);

        $this->view->case     = $case;
        $this->view->runID    = 0;
        $this->view->deployID = $deployID;
        $this->view->results  = $results;
        $this->view->builds   = $this->loadModel('build')->getBuildPairs($case->product, 0, '');
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed, noletter');

        die($this->display());
    }
}
