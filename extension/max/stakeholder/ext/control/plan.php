<?php
class myStakeholder extends stakeholder
{
    /**
     * Stakeholder plan list.
     *
     * @param  int $stakeholderID
     * @access public
     * @return void
     */
    public function plan()
    {
        if($_POST)
        {
            $result = $this->stakeholder->savePlan();

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(!$result)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['locate']  = inlink('plan');
            return $this->send($response);
        }

        $this->view->title        = $this->lang->stakeholder->planField->common;
        $this->view->position[]   = $this->lang->stakeholder->planField->common;

        $this->view->plans        = $this->stakeholder->getPlans();
        $this->view->processGroup = $this->stakeholder->getProcessGroup();
        $this->view->activities   = $this->stakeholder->getActivities();
        $this->view->processes    = $this->stakeholder->getProcess();
        $this->view->stakeholders = $this->stakeholder->getListByType();

        $this->display();
    }
}
