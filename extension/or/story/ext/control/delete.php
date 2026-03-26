<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * Delete a story.
     *
     * @param  int    $storyID
     * @param  string $confirm   yes|no
     * @param  string $from      taskkanban
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function delete($storyID, $confirm = 'no', $from = '', $storyType = 'story')
    {
        $story = $this->story->getById($storyID);
        if($confirm == 'yes' and $story->demand) $this->loadModel('demand')->changeDemandStatus($story->demand, $storyID);

        return parent::delete($storyID, $confirm, $from, $storyType);
    }
}
