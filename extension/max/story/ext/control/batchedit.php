<?php
class myStory extends story
{
    /**
     * Batch edit story.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param string $branch
     * @param  string $storyType
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0, $executionID = 0, $branch = '', $storyType = 'story', $from = '')
    {
        $meetings     = $this->loadModel('meeting')->getListByUser('all');
        $meetingPairs = array();
        foreach($meetings as $id => $meeting) $meetingPairs[$id] = $meeting->name;

        $this->view->meetings        = $meetingPairs;
        $this->view->researchReports = $this->loadModel('researchreport')->getPairs();
        parent::batchEdit($productID, $executionID, $branch, $storyType, $from);
    }
}
