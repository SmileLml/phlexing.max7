<?php
class calendarStory extends storyModel
{
    /**
     * Get story by user.
     *
     * @param string $account
     * @param int    $limit
     * @param string $type
     * @param array  $skipProductIDList
     * @param int    $appendStoryID
     * @access public
     * @return array
     */
    public function getUserStoryPairs($account, $limit = 10, $type = 'story', $skipProductIDList = array(), $appendStoryID = 0)
    {
        $storys = array();
        $stmt   = $this->dao->select('t1.id, t1.title, t2.name as product')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.type')->eq($type)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.product')->ne(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->beginIF(!empty($skipProductIDList))->andWhere('t1.product')->notin($skipProductIDList)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->query();

        while($story = $stmt->fetch())
        {
            $storys[$story->id] = empty($story->product) ? $story->title : $story->product . '/' . $story->title;
        }

        return $storys;
    }
}
