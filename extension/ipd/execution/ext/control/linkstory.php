<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * @param int $objectID
     * @param string $browseType
     * @param int $param
     * @param string $orderBy
     * @param int $recPerPage
     * @param int $pageID
     * @param string $extra
     * @param string $storyType
     */
    public function linkStory($objectID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc', $recPerPage = 50, $pageID = 1, $extra = '', $storyType = 'story')
    {
        $project = $this->loadModel('project')->getByID($objectID, 'project');
        if(isset($project->model) && $project->model == 'ipd' && $this->app->tab == 'project' && $storyType == 'requirement')
        {
            $searchProducts = $this->loadModel('product')->getProductPairsByProject($objectID);
            $this->app->loadModuleConfig('product');
            $this->app->loadLang('story');
            $roadmaps = $this->product->getRoadmapPairs(array_keys($searchProducts));

            $this->config->product->search['fields']['roadmap']           = $this->lang->story->roadmap;
            $this->config->product->search['params']['roadmap']['values'] = arrayUnion(array('' => ''), $roadmaps);
        }

        parent::linkStory($objectID, $browseType, $param, $orderBy, $recPerPage, $pageID, $extra, $storyType);
    }
}
