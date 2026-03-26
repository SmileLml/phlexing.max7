<?php
class opsRelease extends releaseModel
{
    public function getPairsByProduct($productID, $branch = 0)
    {
        return $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('product')->eq((int)$productID)
            ->beginIF($branch)->andWhere('branch')->eq($branch)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('date DESC')
            ->fetchPairs('id', 'name');
    }

    /**
     * 发布批量关联需求。
     * Link stories to a release.
     *
     * @param  int    $releaseID
     * @param  array  $stories
     * @access public
     * @return bool
     */
    public function linkStory($releaseID, $stories)
    {
        $result = parent::linkStory($releaseID, $stories);
        if(!$result) return $result;

        foreach($stories as $storyID)
        {
            $relation = new stdClass();
            $relation->relation = 'interrated';
            $relation->AID      = $storyID;
            $relation->AType    = 'story';
            $relation->BID      = $releaseID;
            $relation->BType    = 'release';
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }
        return $result;
    }

    /**
     * 移除关联的需求。
     * Unlink a story.
     *
     * @param  int    $releaseID
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function unlinkStory($releaseID, $storyID)
    {
        $result = parent::unlinkStory($releaseID, $storyID);
        if(!$result) return $result;

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('relation')->eq('interrated')
            ->andWhere('AID')->eq($storyID)
            ->andWhere('AType')->eq('story')
            ->andWhere('BID')->eq($releaseID)
            ->andWhere('BType')->eq('release')
            ->exec();
        return $result;
    }

    /**
     * 批量解除发布跟需求的关联。
     * Batch unlink story.
     *
     * @param  int    $releaseID
     * @param  array  $storyIdList
     * @access public
     * @return bool
     */
    public function batchUnlinkStory($releaseID, $storyIdList)
    {
        $result = parent::batchUnlinkStory($releaseID, $storyIdList);
        if(!$result) return $result;

        foreach($storyIdList as $unlinkStoryID)
        {
            $this->dao->delete()->from(TABLE_RELATION)
                ->where('relation')->eq('interrated')
                ->andWhere('AID')->eq($unlinkStoryID)
                ->andWhere('AType')->eq('story')
                ->andWhere('BID')->eq($releaseID)
                ->andWhere('BType')->eq('release')
                ->exec();
        }
        return $result;
    }
}
