<?php
class zentaomaxProgramplanZen extends programplanZen
{

    /**
     * 生成创建项目阶段视图数据。
     * Build create view data.
     *
     * @param  object $viewData
     * @access public
     * @return void
     */
    public function buildCreateView($viewData)
    {
        if(helper::hasFeature('deliverable'))
        {
            foreach($viewData->plans as $plan)
            {
                if($plan->status == 'closed' && !empty($plan->deliverable) && $this->loadModel('project')->checkUploadedDeliverable($plan)) $plan->hasDeliverable = true;
            }
        }
        return parent::buildCreateView($viewData);
    }

    /**
     * 生成编辑阶段数据。
     * Build edit view data.
     *
     * @param  object $plan
     * @access public
     * @return void
     */
    public function buildEditView($plan)
    {
        if(helper::hasFeature('deliverable') && $plan->status == 'closed' && !empty($plan->deliverable) && $this->loadModel('project')->checkUploadedDeliverable($plan)) $plan->hasDeliverable = true;
        return parent::buildEditView($plan);
    }
}
