<?php
/**
 * Update the story order of roadmap.
 *
 * @param  int    $storyID
 * @param  string $oldRoadmapIDList
 * @param  string $roadmapList
 * @access public
 * @return bool
 */
public function updateStoryOrderOfRoadmap($storyID, $roadmapList = '', $oldRoadmapIDList = '')
{
    $roadmapList    = $roadmapList ? explode(',', $roadmapList) : array();
    $oldRoadmapIDList = $oldRoadmapIDList ? explode(',', $oldRoadmapIDList) : array();

    /* Get the ids to be inserted and deleted by comparing roadmap ids. */
    $roadmapsTobeInsert = array_diff($roadmapList, $oldRoadmapIDList);
    $roadmapsTobeDelete = array_diff($oldRoadmapIDList, $roadmapList);

    /* Delete old story sort of roadmap. */
    if(!empty($roadmapsTobeDelete)) $this->dao->delete()->from(TABLE_ROADMAPSTORY)->where('story')->eq($storyID)->andWhere('roadmap')->in($roadmapsTobeDelete)->exec();

    if(!empty($roadmapsTobeInsert))
    {
        /* Get last story order of roadmap list. */
        $maxOrders = $this->dao->select('roadmap, max(`order`) as `order`')->from(TABLE_ROADMAPSTORY)->where('roadmap')->in($roadmapsTobeInsert)->groupBy('roadmap')->fetchPairs();

        foreach($roadmapsTobeInsert as $roadmapID)
        {
            /* Set story order in new roadmap. */
            $data = new stdClass();
            $data->roadmap = $roadmapID;
            $data->story   = $storyID;
            $data->order   = zget($maxOrders, $roadmapID, 0) + 1;

            $this->dao->replace(TABLE_ROADMAPSTORY)->data($data)->exec();
        }
    }

    return !dao::isError();
}

/**
 * Update the story order according to the roadmap.
 *
 * @param  int    $roadmapID
 * @param  array  $sortIDList
 * @param  string $orderBy
 * @param  int    $pageID
 * @param  int    $recPerPage
 * @access public
 * @return bool
 */
public function sortStoriesOfRoadmap($roadmapID, $sortIDList, $orderBy = 'id_desc', $pageID = 1, $recPerPage = 100)
{
    /* Append id for secend sort. */
    $orderBy = common::appendOrder($orderBy);

    /* Get all stories by roadmap. */
    $stories     = $this->loadModel('roadmap')->getRoadmapStories($roadmapID, 'all', $orderBy);
    $storyIDList = array_keys($stories);

    /* Calculate how many numbers there are before the sort list and after the sort list. */
    $frontStoryCount   = $recPerPage * ($pageID - 1);
    $behindStoryCount  = $recPerPage * $pageID;
    $frontStoryIDList  = array_slice($storyIDList, 0, $frontStoryCount);
    $behindStoryIDList = array_slice($storyIDList, $behindStoryCount, count($storyIDList) - $behindStoryCount);

    /* Merge to get a new sort list. */
    $newSortIDList = array_merge($frontStoryIDList, $sortIDList, $behindStoryIDList);
    if(strpos($orderBy, 'order_desc') !== false) $newSortIDList = array_reverse($newSortIDList);

    /* Loop update the story order of roadmap. */
    $order = 1;
    foreach($newSortIDList as $storyID)
    {
        $this->dao->update(TABLE_ROADMAPSTORY)->set('`order`')->eq($order)->where('story')->eq($storyID)->andWhere('roadmap')->eq($roadmapID)->exec();
        $order++;
    }

    return !dao::isError();
}

/**
 * Batch change the roadmap of story.
 *
 * @param  array  $storyIdList
 * @param  int    $roadmapID
 * @access public
 * @return array
 */
public function batchChangeRoadmap($storyIdList, $roadmapID)
{
    return $this->loadExtension('ipd')->batchChangeRoadmap($storyIdList, $roadmapID);
}

/**
 * Batch create stories.
 *
 * @param  array   $stories
 * @access public
 * @return array
 */
public function batchCreate($stories)
{
    return $this->loadExtension('ipd')->batchCreate($stories);
}

/**
 * Batch update stories.
 *
 * @param  array   $stories
 * @access public
 * @return array
 */
public function batchUpdate($stories)
{
    return $this->loadExtension('ipd')->batchUpdate($stories);
}

/**
 * Append affected stories.
 *
 * @param  object $story
 * @access public
 * @return bool
 */
public function getAffectedScope($story)
{
    return $this->loadExtension('ipd')->getAffectedScope($story);
}

/**
 * @param mixed[] $stories
 * @param string $storyType
 * @param mixed[] $demands
 */
public function getTracksByStories($stories, $storyType, $demands = array())
{
    return $this->loadExtension('ipd')->getTracksByStories($stories, $storyType, $demands);
}
