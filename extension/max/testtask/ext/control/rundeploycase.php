<?php
class testtask extends control
{
    public function runDeployCase($deployID, $caseID = 0, $version = 1)
    {
        $run = new stdclass();
        $run->case = $this->loadModel('testcase')->getById($caseID, $version);

        $preAndNext = $this->loadModel('common')->getPreAndNextObject('testcase', $caseID);
        if(!empty($_POST))
        {
            $caseResult = $this->testtask->createResult();
            if(dao::isError()) die(js::error(dao::getError()));

            /* set cookie for ajax load caselist when close colorbox. */
            setcookie('selfClose', 1);

            if($preAndNext->next)
            {
                $nextCaseID  = $preAndNext->next->id;
                $nextVersion = $preAndNext->next->version;

                $response['result'] = 'success';
                $response['next']   = 'success';
                $response['locate'] = inlink('runDeployCase', "deployID=$deployID&caseID=$nextCaseID&version=$nextVersion");
                return $this->send($response);
            }
            else
            {
                $response['result'] = 'success';
                $response['locate'] = 'reload';
                $response['target'] = 'parent';
                return $this->send($response);
            }
        }

        $preCase  = array();
        $nextCase = array();
        if($preAndNext->pre)
        {
            $preCase['caseID']  = $preAndNext->pre->id;
            $preCase['version'] = $preAndNext->pre->version;
        }
        if($preAndNext->next)
        {
            $nextCase['caseID']  = $preAndNext->next->id;
            $nextCase['version'] = $preAndNext->next->version;
        }

        $this->view->run      = $run;
        $this->view->preCase  = $preCase;
        $this->view->nextCase = $nextCase;
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed, noletter');
        $this->view->caseID   = $caseID;
        $this->view->version  = $version;
        $this->view->deployID = $deployID;

        $this->display();
    }
}
