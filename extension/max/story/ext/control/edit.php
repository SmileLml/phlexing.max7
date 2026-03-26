<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * @param int $storyID
     * @param string $kanbanGroup
     * @param string $storyType
     */
    public function edit($storyID, $kanbanGroup = 'default', $storyType = 'story')
    {
        $this->view->reportPairs = $this->loadModel('researchreport')->getPairs();

        parent::edit($storyID, $kanbanGroup, $storyType);
    }
}
