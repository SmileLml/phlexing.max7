<?php
class project extends control
{
    /**
     * Ajax load stages.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $copyToProduct
     * @access public
     * @return void
     */
    public function ajaxLoadStages($projectID, $productID, $copyToProduct)
    {
        if(empty($productID)) return;

        $project = $this->project->getByID($projectID);
        if($project->model != 'waterfall') return;

        $this->loadModel('stage');
        $this->loadModel('execution');
        $this->loadModel('programplan');

        $copyExecutions = $this->loadModel('execution')->getList($project->id, 'all', 'all', 0, $productID);
        $stageIdList    = array();
        $executionStats = $this->execution->getStatData($project->id, 'all', $productID);
        foreach($executionStats as $execution)
        {
            $stageIdList[$execution->id] = $execution->id;
            if(!empty($execution->children))
            {
                foreach($execution->children as $child) $stageIdList[$child->id] = $child->id;
            }
        }

        $this->view->project     = $project;
        $this->view->executions  = $copyExecutions;
        $this->view->stageIdList = $stageIdList;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->productID   = $copyToProduct;
        $this->view->copyProject = json_decode($this->cookie->copyData);

        $this->display();
    }
}
