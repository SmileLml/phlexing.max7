<?php
class relationBuild extends buildModel
{
    /**
     * 版本关联需求。
     * Link stories to a build.
     *
     * @param  int    $buildID
     * @param  array  $storyIdList
     * @access public
     * @return void
     */
    public function linkStory($buildID, $storyIdList)
    {
        $result = parent::linkStory($buildID, $storyIdList);
        if(!$result) return $result;

        foreach($storyIdList as $storyID)
        {
            $relation = new stdClass();
            $relation->relation = 'interrated';
            $relation->AID      = $storyID;
            $relation->AType    = 'story';
            $relation->BID      = $buildID;
            $relation->BType    = 'build';
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }
        return $result;
    }

    /**
     * 解除需求关联。
     * Unlink story.
     *
     * @param  int    $buildID
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function unlinkStory($buildID, $storyID)
    {
        $result = parent::unlinkStory($buildID, $storyID);
        if(!$result) return $result;

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('relation')->eq('interrated')
            ->andWhere('AID')->eq($storyID)
            ->andWhere('AType')->eq('story')
            ->andWhere('BID')->eq($buildID)
            ->andWhere('BType')->eq('build')
            ->exec();
        return $result;
    }

    /**
     * 批量解除需求关联。
     * Batch unlink story.
     *
     * @param  int    $buildID
     * @param  array  $storyIDList
     * @access public
     * @return bool
     */
    public function batchUnlinkStory($buildID, $storyIDList)
    {
        $result = parent::batchUnlinkStory($buildID, $storyIDList);
        if(!$result) return $result;

        foreach($storyIDList as $storyID)
        {
            $this->dao->delete()->from(TABLE_RELATION)
                ->where('relation')->eq('interrated')
                ->andWhere('AID')->eq($storyID)
                ->andWhere('AType')->eq('story')
                ->andWhere('BID')->eq($buildID)
                ->andWhere('BType')->eq('build')
                ->exec();
        }
        return $result;
    }
}
