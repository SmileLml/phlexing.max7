<?php
class orTask extends taskModel
{
    /**
     * Get tasks of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryTasks($storyID)
    {
        return $this->dao->select('id,name')->from(TABLE_TASK)
            ->where('story')->eq((int)$storyID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }
}
