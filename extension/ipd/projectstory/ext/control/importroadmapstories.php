<?php
class myProjectstory extends projectstory
{
    /**
     * Import roadmap stories.
     *
     * @param  int $projectID
     * @param  int $roadmapID
     * @access public
     * @return void
     */
    public function importRoadmapStories($projectID = 0, $roadmapID = 0)
    {
        $products = array();
        /* Select roadmap stories. */
        $roadmapStories = $this->loadModel('roadmap')->getRoadmapStories($roadmapID);
        if(empty($roadmapStories)) return print(js::locate($this->session->storyList, 'parent'));

        foreach($roadmapStories as $storyID => $story) $products[$storyID] = $story->product;
        $this->loadModel('execution')->linkStory($projectID, array_keys($roadmapStories), $products, '', array(), 'requirement');

        return print(js::locate($this->session->storyList, 'parent'));
    }
}
