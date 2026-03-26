<?php
/**
 * 获取批量创建需求后的跳转地址。
 * Get after batch create location.
 *
 * @param  int       $productID
 * @param  string    $branch
 * @param  int       $executionID
 * @param  int       $storyID
 * @param  string    $storyType
 * @access protected
 * @return string
 */
protected function getAfterBatchCreateLocation($productID, $branch, $executionID, $storyID, $storyType)
{
    if($this->config->vision == 'or') return $this->createLink('product', 'browse', "productID=$productID&branch=$branch&browseType=assignedtome&queryID=0&storyType=$storyType");

    return parent::getAfterBatchCreateLocation($productID, $branch, $executionID, $storyID, $storyType);
}

/**
 * 构建编辑需求数据。
 * Build story for edit
 *
 * @param  int       $storyID
 * @access protected
 * @return object|false
 */
protected function buildStoryForEdit($storyID)
{
    $story    = parent::buildStoryForEdit($storyID);
    $oldStory = $this->story->getById($storyID);

    if(!$story) return false;

    if($story->roadmap != $oldStory->roadmap)
    {
        $roadmap      = $this->loadModel('roadmap')->getById($story->roadmap);
        $story->stage = 'wait';
        if($roadmap) $story->stage = $roadmap->status == 'launched' ? 'incharter' : 'inroadmap';
    }

    return $story;
}

/**
 * 构建批量创建需求数据。
 * Build stories for batch create.
 *
 * @param  int       $productID
 * @param  string    $storyType
 * @access protected
 * @return array
 */
protected function buildStoriesForBatchCreate($productID, $storyType)
{
    $roadmaps    = $this->loadModel('roadmap')->getList();
    $forceReview = $this->story->checkForceReview($storyType);
    $fields      = $this->config->story->form->batchCreate;
    $account     = $this->app->user->account;
    $now         = helper::now();
    $saveDraft   = $this->post->status == 'draft';
    if($forceReview) $fields['reviewer']['required'] = true;

    $stories = form::batchData($fields)->get();
    foreach($stories as $i => $story)
    {
        $story->type       = $storyType;
        $story->status     = (empty($story->reviewer) && !$forceReview) ? 'active' : 'reviewing';
        $story->status     = $saveDraft ? 'draft' : $story->status;
        $story->product    = $productID;
        $story->openedBy   = $account;
        $story->vision     = $this->config->vision;
        $story->openedDate = $now;
        $story->version    = 1;
        $story->roadmap    = 0;

        if(in_array($this->app->tab, array('project', 'execution')))
        {
            $story->stage = 'projected';
        }

        if($story->roadmap && isset($roadmaps[$story->roadmap]))
        {
            $story->stage = $roadmaps[$story->roadmap]->status == 'launched' ? 'incharter' : 'inroadmap';
        }

        !empty($story->assignedTo) && $story->assignedDate = $now;
        if($this->post->uploadImage && $this->post->uploadImage[$i]) $story->uploadImage = $this->post->uploadImage[$i];
    }

    return $stories;
}
