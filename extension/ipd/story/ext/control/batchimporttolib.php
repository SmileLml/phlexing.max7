<?php
class myStory extends story
{
    /**
     * Batch import to lib.
     *
     * @access public
     * @return void
     */
    public function batchImportToLib()
    {
        $storyIDList = $this->post->storyIdList;
        $stories     = '';
        foreach(explode(',', $storyIDList) as $storyID)
        {
            if(strpos($storyID, '-') !== false)
            {
                list($parent, $child) = explode('-', $storyID);
                $storyID = $child;
            }

            $stories .= ",$storyID";
        }
        $this->story->importToLib(trim($stories, ','));
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'locate' => 'reload'));
    }
}
