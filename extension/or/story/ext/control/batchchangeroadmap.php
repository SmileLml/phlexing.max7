<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * Batch change the roadmap of story.
     *
     * @param  int    $roadmapID
     * @access public
     * @return int
     */
    public function batchChangeRoadmap($roadmapID)
    {
        if(empty($_POST['storyIdList']) and empty($storyIdList)) return print(js::locate($this->session->storyList, 'parent'));
        $storyIdList = array_unique($this->post->storyIdList);
        $storyIdList = array_combine(array_values($storyIdList), array_values($storyIdList));

        $stories     = $this->story->getByList($storyIdList, 'requirement');
        $showConfirm = false;
        $storyStatus = array('active' => 0);
        if($roadmapID)
        {
            foreach($stories as $story)
            {
                $status = $story->status;
                if(!isset($storyStatus[$status])) $storyStatus[$status] = 0;
                $storyStatus[$status] ++;
                if($status != 'active')
                {
                    $showConfirm = true;
                    unset($storyIdList[$story->id]);
                }
            }
        }

        $allChanges  = $this->story->batchChangeRoadmap($storyIdList, $roadmapID);
        if(dao::isError()) return $this->sendError(dao::getError());
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');

        if($showConfirm)
        {
            $activeCount = $storyStatus['active'];
            unset($storyStatus['active']);
            $statusTips = array();
            foreach($storyStatus as $status => $statusCount) $statusTips[] = sprintf($this->lang->story->statusCount, $statusCount, zget($this->lang->story->statusList, $status));
            return $this->send(array('result' => 'fail', 'load' => array('alert' => sprintf($this->lang->story->changeRoadmapTip, $activeCount, implode('、', $statusTips)), 'locate' => $this->session->storyList)));
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }
}
