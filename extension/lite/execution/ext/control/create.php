<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * @param int $projectID
     * @param int $executionID
     * @param int $copyExecutionID
     * @param int $planID
     * @param string $confirm
     * @param int $productID
     * @param string $extra
     */
    public function create($projectID = 0, $executionID = 0, $copyExecutionID = 0, $planID = 0, $confirm = 'no', $productID = 0, $extra = '')
    {
        $this->config->execution->create->requiredFields = 'name,code,begin,end';
        $this->loadModel('project')->setMenu($projectID);
        parent::create($projectID, $executionID, $copyExecutionID, $planID, $confirm, $productID, $extra);
    }
}
