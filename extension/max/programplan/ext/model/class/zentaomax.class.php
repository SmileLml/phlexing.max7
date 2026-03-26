<?php
class zentaomaxProgramplan extends programplanModel
{
    /**
     * 更新阶段。
     * Update a plan.
     *
     * @param  int       $planID
     * @param  int       $projectID
     * @param  object    $plan
     * @access public
     * @return bool
     */
    public function update($planID = 0, $projectID = 0, $plan = null)
    {
        if($planID && !empty($plan)) $plan = $this->loadModel('execution')->changeExecutionDeliverable($planID, $plan);
        return parent::update($planID, $projectID, $plan);
    }
}
