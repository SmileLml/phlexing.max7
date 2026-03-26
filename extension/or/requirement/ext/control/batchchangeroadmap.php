<?php
helper::importControl('requirement');
class myRequirement extends requirement
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
