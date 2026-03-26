<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * Link related requirements.
     *
     * @param  int    $storyID
     * @param  string $browseType
     * @param  string $excludeStories
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkRequirements($storyID, $browseType = '', $excludeStories = '', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!empty($_SESSION['project']))
        {
            $project = $this->loadModel('project')->getByID($this->session->project, 'project');
            if(isset($project->model) && $project->model == 'ipd' && $this->app->tab == 'project')
            {
                $searchProducts = $this->loadModel('product')->getProductPairsByProject($this->session->project);
                $this->app->loadLang('story');
                $roadmaps = $this->product->getRoadmapPairs(array_keys($searchProducts));

                $this->config->product->search['fields']['roadmap']           = $this->lang->story->roadmap;
                $this->config->product->search['params']['roadmap']['values'] = arrayUnion(array('' => ''), $roadmaps);
            }
        }

        parent::linkRequirements($storyID, $browseType, $excludeStories, $param, $recTotal, $recPerPage, $pageID);
    }
}
