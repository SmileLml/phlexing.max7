<?php
class feedbackStory extends storyModel
{
    /**
     * Create story from feedback.
     *
     * @param  object $story
     * @param  int    $executionID
     * @param  int    $bugID
     * @param  string $extra
     * @param  int    $todoID
     * @access public
     * @return int|false
     */
    public function create($story, $executionID = 0, $bugID = 0, $extra = '', $todoID = 0)
    {
        $type = $this->post->type;
        if($this->post->feedback || $this->post->ticket)
        {
            $fileIDPairs = $this->loadModel('file')->copyObjectFiles($type);
            if(isset($_POST['deleteFiles'])) unset($_POST['deleteFiles']);
        }

        $storyDocs = !empty($story->docs) ? $story->docs : '';
        unset($story->docs);

        $storyID = parent::create($story, $executionID, $bugID, $extra, $todoID);

        $story = $this->loadModel('story')->getByID($storyID);

        if($this->config->vision == 'or' && $story->roadmap)
        {
            $roadmapStory = new stdclass();
            $roadmapStory->roadmap = $story->roadmap;
            $roadmapStory->story   = $storyID;
            $roadmapStory->order   = 0;

            $this->dao->replace(TABLE_ROADMAPSTORY)->data($roadmapStory)->exec();

            $vision = $story->stage == 'incharter' ? 'or,rnd' : 'or';
            $this->dao->update(TABLE_STORY)->set('vision')->eq($vision)->where('id')->eq($storyID)->exec();
        }

        if($storyDocs)
        {
            $docList       = $this->dao->select('id,version')->from(TABLE_DOC)->where('id')->in($storyDocs)->fetchPairs();
            $storyVersions = array();
            foreach(explode(',', $storyDocs) as $docID)
            {
                $storyVersions[$docID] = $docList[$docID];

                $relation = new stdClass();
                $relation->relation = 'interrated';
                $relation->AID      = $story->id;
                $relation->AType    = $story->type;
                $relation->BID      = $docID;
                $relation->BType    = 'doc';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }

            $this->dao->update(TABLE_STORYSPEC)
                ->set('docs')->eq($storyDocs)
                ->set('docVersions')->eq(json_encode($storyVersions))
                ->where('story')->eq($storyID)
                ->andWhere('version')->eq($story->version)
                ->exec();
        }

        if(empty($_POST['feedback']) && empty($_POST['ticket'])) return $storyID;

        /* If story is from feedback, record action for feedback and add files to story from feedback. */
        if($this->post->feedback)
        {
            $feedbackID  = $this->post->feedback;
            $objectID    = $feedbackID;
            $oldFeedback = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->eq($feedbackID)->fetch();

            $feedback = new stdclass();
            $feedback->result   = $storyID;
            $feedback->solution = $type == 'requirement' ? 'touserstory' : "to{$type}";

            if($this->config->vision != 'or')
            {
                $feedback->status        = 'commenting';
                $feedback->processedBy   = $this->app->user->account;
                $feedback->processedDate = helper::now();
            }

            $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($feedbackID)->exec();
            $this->loadModel('action')->create('feedback', $feedbackID, $feedback->solution, '', $storyID);
            if($oldFeedback->status != 'commenting') $this->action->create('feedback', $feedbackID, $type == 'requirement' ? 'syncDoingByUserStory' : ('syncDoingBy' . $type), '', $storyID);

            /* Record the action of twins story. */
            if(!empty($story->twins))
            {
                foreach(explode(',', $story->twins) as $twinsStoryID)
                {
                    if(empty($twinsStoryID) || $twinsStoryID == $storyID) continue;
                    $this->action->create('feedback', $feedbackID, 'ToStory', '', $twinsStoryID);
                    if($oldFeedback->status != 'commenting') $this->action->create('feedback', $feedbackID, 'syncDoingByStory', '', $twinsStoryID);
                }
            }

            $feedbackTransferredStories = "{$storyID},{$story->twins}";
            foreach(explode(',', trim($feedbackTransferredStories, ',')) as $feedbackTransferredStoryID)
            {
                if(empty($feedbackTransferredStoryID)) continue;

                $relation = new stdClass();
                $relation->product  = 0;
                $relation->AID      = $feedbackID;
                $relation->AType    = 'feedback';
                $relation->relation = 'transferredto';
                $relation->BID      = $feedbackTransferredStoryID;
                $relation->BType    = $story->type;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }

            if(isset($feedback->status)) $this->loadModel('feedback')->updateSubStatus($feedbackID, $feedback->status);
        }

        /* If story is from feedback, record action for feedback and add files to story from feedback. */
        if($this->post->ticket)
        {
            $ticketID   = $this->post->ticket;
            $objectID   = $ticketID;
            $objectType = 'ticket';

            $ticket = new stdClass();
            $ticket->ticketId   = $ticketID;
            $ticket->objectId   = $storyID;
            $ticket->objectType = 'story';

            $this->dao->insert(TABLE_TICKETRELATION)->data($ticket)->exec();

            $this->loadModel('action')->create('ticket', $ticketID, 'ToStory', '', $storyID);

            /* Record the action and relation of twins story. */
            if(!empty($story->twins))
            {
                foreach(explode(',', $story->twins) as $twinsStoryID)
                {
                    if(empty($twinsStoryID) || $twinsStoryID == $storyID) continue;

                    $ticket->objectId = $twinsStoryID;
                    $this->dao->insert(TABLE_TICKETRELATION)->data($ticket)->exec();

                    $this->action->create('ticket', $ticketID, 'ToStory', '', $twinsStoryID);
                }
            }

            $ticketTransferredStories = "{$storyID},{$story->twins}";
            foreach(explode(',', trim($ticketTransferredStories, ',')) as $ticketTransferredStoryID)
            {
                if(empty($ticketTransferredStoryID)) continue;

                $relation = new stdClass();
                $relation->product  = 0;
                $relation->AID      = $ticketID;
                $relation->AType    = 'ticket';
                $relation->relation = 'transferredto';
                $relation->BID      = $ticketTransferredStoryID;
                $relation->BType    = $story->type;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }

        if(($this->post->feedback || $this->post->ticket) && isset($objectID) && !empty($fileIDPairs))
        {
            if(!empty($fileIDPairs)) $this->dao->update(TABLE_FILE)->set('objectID')->eq($storyID)->where('id')->in($fileIDPairs)->exec();
            $storyFiles = $this->dao->select('id')->from(TABLE_FILE)->where('objectType')->eq($type)->andWhere('objectID')->eq($storyID)->andWhere('deleted')->eq('0')->fetchPairs();
            $this->dao->update(TABLE_STORYSPEC)->set('files')->eq(join(',', $storyFiles))->where('story')->eq($storyID)->andWhere('version')->eq(1)->exec();
        }

        return $storyID;
    }

    /**
     * 更新需求。
     * Update a story.
     *
     * @param  int      $storyID
     * @access public
     * @return bool|int
     * @param string|bool $comment
     * @param object $story
     */
    public function update($storyID, $story, $comment = '')
    {
        $oldStory    = $this->getByID($storyID);
        $storyDocs   = $story->docs;
        $oldDocs     = $story->oldDocs;
        $docVersions = $story->docVersions;
        unset($story->docs);
        unset($story->oldDocs);
        unset($story->docVersions);

        $actionID = parent::update($storyID, $story, $comment);
        if(dao::isError()) return false;

        if(strpos(',draft,changing,', ",{$oldStory->status},") !== false)
        {
            $newStory = $this->getByID($storyID);
            $this->dao->delete()->from(TABLE_RELATION)->where('relation')->eq('interrated')->andWhere('AID')->eq($storyID)->andWhere('AType')->eq($newStory->type)->andWhere('BType')->eq('doc')->exec();

            $storyVersions = array();
            $storyDocs     = !empty($storyDocs) ? explode(',', $storyDocs) : array();
            if(!empty($oldDocs)) $storyDocs = array_merge($storyDocs, $oldDocs);
            if($storyDocs)
            {
                $docList = $this->dao->select('id,version')->from(TABLE_DOC)->where('id')->in($storyDocs)->fetchPairs();
                foreach($storyDocs as $docID)
                {
                    $storyVersions[$docID] = !empty($docVersions[$docID]) ? $docVersions[$docID] : $docList[$docID];

                    $relation = new stdClass();
                    $relation->relation = 'interrated';
                    $relation->AID      = $storyID;
                    $relation->AType    = $newStory->type;
                    $relation->BID      = $docID;
                    $relation->BType    = 'doc';
                    $relation->product  = 0;
                    $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
                }
            }

            $this->dao->update(TABLE_STORYSPEC)
                ->set('docs')->eq(implode(',', $storyDocs))
                ->set('docVersions')->eq(json_encode($storyVersions))
                ->where('story')->eq($storyID)
                ->andWhere('version')->eq($newStory->version)
                ->exec();

            /* 由于关联文档的变更， 需要重新记录一下变更记录。 */
            $newStory = $this->getByID($storyID);
            $oldStory->docVersions = json_encode($oldStory->docVersions);
            $newStory->docVersions = json_encode($newStory->docVersions);
            $changes = common::createChanges($oldStory, $newStory);
            if($changes)
            {
                if($actionID)
                {
                    $this->dao->delete()->from(TABLE_ACTION)->where('id')->eq($actionID)->exec();
                    $this->dao->delete()->from(TABLE_HISTORY)->where('action')->eq($actionID)->exec();
                }
                $actionID = $this->loadModel('action')->create('story', $storyID, 'Edited', $comment);
                $this->loadModel('action')->logHistory($actionID, $changes);
            }
        }

        if($this->config->vision == 'or')
        {
            if($this->config->edition == 'ipd' and !empty($oldStory->demand))
            {
                $otherURS = $this->dao->select('id')->from(TABLE_STORY)
                    ->where('product')->eq($oldStory->product)
                    ->andWhere('demand')->eq($oldStory->demand)
                    ->andWhere('type')->eq('requirement')
                    ->andWhere('deleted')->eq(0)
                    ->fetchPairs('id');

                $demand = $this->loadModel('demand')->getByID($oldStory->demand);
                $demand->product = trim($demand->product, ',') . ",$story->product";
                if(empty($otherURS)) $demand->product = str_replace(",$oldStory->product,", ',', ",$demand->product,");

                $demand->product = implode(',', array_unique(explode(',', $demand->product)));
                $this->dao->update(TABLE_DEMAND)->set('product')->eq(trim($demand->product, ','))->where('id')->eq($oldStory->demand)->exec();

                $distributedProducts = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in(trim($demand->product, ','))->fetchPairs();
                $actionExtra = '';
                foreach($distributedProducts as $productID => $productName) $actionExtra .= ", #$productID $productName";
                $this->action->create('demand', $oldStory->demand, 'ManageDistributedProducts', '', trim($actionExtra, ', '));
            }

            if($oldStory->roadmap != $story->roadmap)
            {
                $this->dao->delete()->from(TABLE_ROADMAPSTORY)->where('story')->eq($storyID)->exec();

                if($story->roadmap)
                {
                    $roadmapStory = new stdclass();
                    $roadmapStory->roadmap = $story->roadmap;
                    $roadmapStory->story   = $storyID;
                    $roadmapStory->order   = 0;

                    $this->dao->replace(TABLE_ROADMAPSTORY)->data($roadmapStory)->autoCheck()->exec();
                }

                $vision = $story->stage == 'incharter' ? 'or,rnd' : 'or';
                $this->dao->update(TABLE_STORY)->set('vision')->eq($vision)->where('id')->eq($storyID)->exec();
            }
        }

        return $actionID;
    }

    /**
     * 变更需求。
     * Change a story.
     *
     * @param  int    $storyID
     * @param  object $story
     * @access public
     * @return array  the change of the story.
     */
    public function change($storyID, $story)
    {
        $oldStory    = $this->getByID($storyID);
        $storyDocs   = $story->docs;
        $oldDocs     = $story->oldDocs;
        $docVersions = $story->docVersions;
        unset($story->docs);
        unset($story->oldDocs);
        unset($story->docVersions);

        $changes = parent::change($storyID, $story);
        if(dao::isError()) return false;

        $newStory = $this->getByID($storyID);
        $this->dao->delete()->from(TABLE_RELATION)->where('relation')->eq('interrated')->andWhere('AID')->eq($storyID)->andWhere('AType')->eq($newStory->type)->andWhere('BType')->eq('doc')->exec();

        $storyVersions = array();
        $storyDocs     = !empty($storyDocs) ? explode(',', $storyDocs) : array();
        if(!empty($oldDocs)) $storyDocs = array_merge($storyDocs, $oldDocs);
        if($storyDocs)
        {
            $docList = $this->dao->select('id,version')->from(TABLE_DOC)->where('id')->in($storyDocs)->fetchPairs();
            foreach($storyDocs as $docID)
            {
                $storyVersions[$docID] = !empty($docVersions[$docID]) ? $docVersions[$docID] : $docList[$docID];

                $relation = new stdClass();
                $relation->relation = 'interrated';
                $relation->AID      = $storyID;
                $relation->AType    = $newStory->type;
                $relation->BID      = $docID;
                $relation->BType    = 'doc';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }

        $this->dao->update(TABLE_STORYSPEC)
            ->set('docs')->eq(implode(',', $storyDocs))
            ->set('docVersions')->eq(json_encode($storyVersions))
            ->where('story')->eq($storyID)
            ->andWhere('version')->eq($newStory->version)
            ->exec();

        /* 由于关联文档的变更， 需要重新记录一下变更记录。 */
        $newStory = $this->getByID($storyID);
        $oldStory->docVersions = json_encode($oldStory->docVersions);
        $newStory->docVersions = json_encode($newStory->docVersions);
        $changes = common::createChanges($oldStory, $newStory);

        return $changes;
    }

    /**
     * 撤销需求变更。
     * Recall the story change.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function recallChange($storyID)
    {
        parent::recallChange($storyID);

        $newStory = $this->getByID($storyID);
        $this->dao->delete()->from(TABLE_RELATION)->where('relation')->eq('interrated')->andWhere('AID')->eq($storyID)->andWhere('AType')->eq($newStory->type)->andWhere('BType')->eq('doc')->exec();

        $storyDocs = !empty($newStory->docs) ? explode(',', $newStory->docs) : array();
        foreach($storyDocs as $docID)
        {
            $relation = new stdClass();
            $relation->relation = 'interrated';
            $relation->AID      = $storyID;
            $relation->AType    = $newStory->type;
            $relation->BID      = $docID;
            $relation->BType    = 'doc';
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }
    }

    /**
     * Get story by id.
     *
     * @param  int    $storyID
     * @param  int    $version
     * @param  bool   $setImgSize
     * @access public
     * @return object|false
     */
    public function getByID($storyID, $version = 0, $setImgSize = false)
    {
        $story = parent::getById($storyID, $version, $setImgSize);

        if(!empty($story->feedback))
        {
            $feedback = $this->loadModel('feedback')->getById($story->feedback);
            $story->feedbackTitle = $feedback->title;
        }

        return $story;
    }

    /**
     * 解除孪生需求。
     * Relieve twins stories.
     *
     * @param  int $productID
     * @param  int $storyID
     * @access public
     * @return bool
     */
    public function relieveTwins($productID, $storyID)
    {
        $result = parent::relieveTwins($productID, $storyID);
        if(!$result) return $result;

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('relation')->eq('twin')
            ->andWhere("( `AID` = $storyID AND `AType` = 'story' )", true)
            ->orWhere("( `BID` = $storyID AND `BType` = 'story' ))")
            ->exec();
        return $result;
    }

    /**
     * Get stories list of a execution.
     *
     * @param  int|array    $executionID
     * @param  int          $productID
     * @param  string       $orderBy
     * @param  string       $browseType
     * @param string $param
     * @param  string       $storyType
     * @param  array|string $excludeStories
     * @param  object|null  $pager
     * @access public
     * @return array
     */
    public function getExecutionStories($executionID = 0, $productID = 0, $orderBy = 't1.`order`_desc', $browseType = 'byModule', $param = '0', $storyType = 'story', $excludeStories = '', $pager = null)
    {
        $stories   = parent::getExecutionStories($executionID, $productID, $orderBy, $browseType, $param, $storyType, $excludeStories, $pager);
        $rawQuery  = $this->dao->get();
        $storyType = $storyType == 'all' ? 'epic,requirement,story' : $storyType;
        $relatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($stories), $storyType, 'byRelation', true);
        foreach($stories as $story) $story->relatedObject = zget($relatedObjectList, $story->id, 0);
        $this->dao->sqlobj->sql = $rawQuery;
        return $stories;
    }
}
