<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * @param int $productID
     * @param string $orderBy
     * @param int $executionID
     * @param string $browseType
     * @param string $storyType
     */
    public function export($productID, $orderBy, $executionID = 0, $browseType = '', $storyType = 'story')
    {
        if(in_array('excel', $this->lang->exportFileTypeList))
        {
            unset($this->lang->exportFileTypeList['excel']);
            $this->lang->exportFileTypeList = arrayUnion(array('excel' => 'excel', 'word' => 'word'), $this->lang->exportFileTypeList);
        }
        else
        {
            $this->lang->exportFileTypeList = arrayUnion(array('word' => 'word'), $this->lang->exportFileTypeList);
        }

        return parent::export($productID, $orderBy, $executionID, $browseType, $storyType);
    }
}
