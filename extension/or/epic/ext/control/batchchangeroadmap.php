<?php
helper::importControl('epic');
class myEpic extends epic
{
    /**
     * Batch change the roadmap of epic.
     *
     * @param  int    $roadmapID
     * @access public
     * @return int
     */
    public function batchChangeRoadmap($roadmapID)
    {
        echo $this->fetch('story', 'batchChangeRoadmap', "roadmapID=$roadmapID");
    }
}
