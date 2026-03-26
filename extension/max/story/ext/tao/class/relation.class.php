<?php
class relationStoryTao extends storyTao
{
    /**
     * 更新孪生需求字段。
     * Update twins.
     *
     * @param  array     $storyIdList
     * @param  int       $mainStoryID
     * @access protected
     * @return void
     */
    protected function updateTwins($storyIdList, $mainStoryID)
    {
        parent::updateTwins($storyIdList, $mainStoryID);
        foreach($storyIdList as $storyID)
        {
            $twins = $storyIdList;
            unset($twins[$storyID]);
            foreach($twins as $twinID)
            {
                $relation = new stdClass();
                $relation->AID      = $storyID;
                $relation->AType    = 'story';
                $relation->relation = 'twin';
                $relation->BID      = $twinID;
                $relation->BType    = 'story';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }
    }
}
