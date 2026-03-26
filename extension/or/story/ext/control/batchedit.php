<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * Batch edit story.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  string $branch
     * @param  string $storyType
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0, $executionID = 0, $branch = '', $storyType = 'story', $from = '')
    {
        $stories        = $this->getStoriesByChecked();
        $appendRoadmaps = $stories ? array_column($stories, 'roadmap') : array();

        $this->loadModel('roadmap');
        $this->view->roadmaps    = $this->roadmap->getPairs($productID, $branch, 'noclosed', 0, $appendRoadmaps);
        $this->view->allRoadmaps = $this->roadmap->getList();
        $this->view->mailto      = $this->loadModel('user')->getPairs('pofirst|nodeleted|noclosed');

        parent::batchEdit($productID, $executionID, $branch, $storyType, $from);
    }
}
