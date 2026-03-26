<?php
class myStory extends story
{
    /**
     * Import story to story lib.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function importToLib($storyID)
    {
        $this->story->importToLib($storyID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'locate' => 'reload'));
    }
}
