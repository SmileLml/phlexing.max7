<?php
class myStory extends story
{
    /**
     * Ajax get design.
     *
     * @param  int    $storyID
     * @param  int    $designID
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function ajaxGetDesign($storyID, $designID = 0, $executionID = 0)
    {
        if($executionID) $execution = $this->loadModel('execution')->getByID($executionID);
        $designs = $this->story->getDesignPairs(isset($execution->project) ? $execution->project : 0, $storyID);

        $items = array();
        foreach($designs as $designID => $designName) $items[] = array('value' => $designID, 'text' => $designName, 'keys' => $designName);
        return print(json_encode($items));
    }
}
