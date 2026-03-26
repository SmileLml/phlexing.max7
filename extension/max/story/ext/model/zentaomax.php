<?php
/**
 * 关闭需求。
 * Close the story.
 *
 * @param  int    $storyID
 * @param  object $postData
 * @access public
 * @return array|false
 */
public function close($storyID, $postData)
{
    $result = parent::close($storyID, $postData);
    if(dao::isError()) return $result;

    $this->syncClose($storyID);

    return $result;
}

/**
 * 批量关闭需求。
 * Batch close the stories.
 *
 * @param  array $stories
 * @access public
 * @return bool
 */
public function batchClose($stories)
{
    $result = parent::batchClose($stories);
    if(dao::isError()) return $result;

    foreach($stories as $storyID => $story) $this->syncClose($storyID);

    return $result;
}

/**
 * Sync story status.
 *
 * @param  int $storyID
 * @access public
 * @return void
 */
public function syncClose($storyID)
{
    $story = $this->getById($storyID);
    if($story->type == 'requirement') return false;

    /* Get all linked requirements.*/
    $relations = $this->getRelation($storyID, $story->type);
    if(empty($relations)) return false;

    /* Get requirement all related stories.*/
    foreach($relations as $id => $title)
    {
        $stories = $this->getRelation($id, 'requirement');

        $storiesStatus = $this->dao->select('status')->from(TABLE_STORY)
            ->where('id')->in(array_keys($stories))
            ->fetchPairs();

        $allClosed = true;
        foreach($storiesStatus as $status)
        {
            if($status != 'closed') $allClosed = false;
        }

        if($allClosed)
        {
            $data = new stdclass();
            $data->assignedTo     = 'closed';
            $data->status         = 'closed';
            $data->lastEditedBy   = $this->app->user->account;
            $data->lastEditedDate = helper::now();
            $data->assignedDate   = helper::now();
            $data->closedDate     = helper::now();
            $data->closedBy       = $this->app->user->account;

            $this->dao->update(TABLE_STORY)->data($data)
                ->autoCheck()
                ->where('id')->eq($id)->exec();
        }
    }
}

/**
 * Import story to asset lib.
 *
 * @param  int|array|string  $storyIDList
 * @access public
 * @return bool
 */
public function importToLib($storyIDList = 0)
{
    $data = fixer::input('post')->get();
    if(empty($data->lib))
    {
        dao::$errors['message'] = sprintf($this->lang->error->notempty, $this->lang->story->lib);
        return false;
    }

    $stories         = $this->getByList($storyIDList);
    $importedStories = $this->dao->select('fromStory,fromVersion')->from(TABLE_STORY)
        ->where('lib')->eq($data->lib)
        ->andWhere('fromStory')->in($storyIDList)
        ->fetchGroup('fromStory');

    if(is_numeric($storyIDList) and isset($importedStories[$storyIDList]))
    {
        dao::$errors['message'] = $this->lang->story->isExist;
        return false;
    }

    /* Remove duplicate story. */
    foreach($stories as $story)
    {
        if(isset($importedStories[$story->id]))
        {
            foreach($importedStories[$story->id] as $improtedStory)
            {
                if($improtedStory->fromVersion == $story->version) unset($stories[$story->id]);
            }
        }
    }

    $now           = helper::now();
    $today         = helper::today();
    $hasApprovePiv = common::hasPriv('assetlib', 'approveStory') or common::hasPriv('assetlib', 'batchApproveStory');
    $this->loadModel('action');

    /* Create story to asset lib. */
    $idMap = array();
    foreach($stories as $story)
    {
        $assetStory = new stdclass();
        $assetStory->title       = $story->title;
        $assetStory->type        = $story->type;
        $assetStory->keywords    = $story->keywords;
        $assetStory->pri         = $story->pri;
        $assetStory->grade       = $story->grade;
        $assetStory->path        = $story->path;
        $assetStory->root        = $story->root;
        $assetStory->parent      = $story->parent;
        $assetStory->isParent    = $story->isParent;
        $assetStory->estimate    = $story->estimate;
        $assetStory->status      = $hasApprovePiv ? 'active' : 'draft';
        $assetStory->category    = $story->category;
        $assetStory->lib         = $data->lib;
        $assetStory->fromStory   = $story->id;
        $assetStory->fromVersion = $story->version;
        $assetStory->openedBy    = $this->app->user->account;
        $assetStory->openedDate  = $now;
        if(!empty($data->assignedTo)) $assetStory->assignedTo = $data->assignedTo;
        if($hasApprovePiv)
        {
            $assetStory->assignedTo   = $this->app->user->account;
            $assetStory->approvedDate = $today;
        }

        $this->dao->insert(TABLE_STORY)->data($assetStory)->exec();
        $assetStoryID = $this->dao->lastInsertID();
        $idMap[$story->id] = $assetStoryID;

        $storySpec          = new stdclass();
        $storySpec->story   = $assetStoryID;
        $storySpec->version = 1;
        $storySpec->title   = $story->title;
        $storySpec->spec    = $story->spec;
        $storySpec->verify  = $story->verify;
        $this->dao->insert(TABLE_STORYSPEC)->data($storySpec)->exec();

        if(!dao::isError()) $this->action->create('story', $assetStoryID, 'import2StoryLib');
    }

    $newStories = $this->getByList($idMap);
    foreach($newStories as $newStory)
    {
        $data = new stdclass();
        $data->parent = isset($idMap[$newStory->parent]) ? $idMap[$newStory->parent] : $newStory->parent;
        $data->root   = isset($idMap[$newStory->root]) ? $idMap[$newStory->root] : $newStory->root;
        $path = '';
        foreach(explode(',', $newStory->path) as $storyID)
        {
            if(!$storyID) continue;
            $path .= isset($idMap[$storyID]) ? $idMap[$storyID] . ',' : $storyID . ',';
        }

        $data->path = trim($path, ',');
        $this->dao->update(TABLE_STORY)->data($data)->where('id')->eq($newStory->id)->exec();
    }

    return true;
}

/**
 * 获取需求关联设计的键值对。
 * Get story design pairs.
 *
 * @param  int    $projectID
 * @param  int    $storyID
 * @access public
 * @return array
 */
public function getDesignPairs($projectID = 0, $storyID = 0)
{
    return $this->dao->select('id, name')->from(TABLE_DESIGN)
        ->where('deleted')->eq(0)
        ->beginIF($storyID)->andWhere('story')->eq($storyID)->fi()
        ->beginIF($projectID)->andwhere('project')->eq($projectID)->fi()
        ->fetchPairs();
}
