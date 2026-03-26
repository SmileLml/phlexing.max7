<?php
class ipdStory extends storyModel
{
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
        $now        = helper::now();
        $allChanges = array();
        $oldStories = $this->getByList($storyIdList);
        $lastOrder  = $this->dao->select('`order`')->from(TABLE_ROADMAPSTORY)->where('roadmap')->eq($roadmapID)->orderBy('order_desc')->fetch('order');
        $roadmap    = $this->dao->findByID((int)$roadmapID)->from(TABLE_ROADMAP)->fetch();

        $this->loadModel('story');
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($roadmapID == $oldStory->roadmap) continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->roadmap        = $roadmapID;
            $story->stage          = empty($roadmap) ? 'wait' : ($roadmap->status == 'launched' ? 'incharter' : 'inroadmap');
            $story->parent         = $oldStory->parent;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!dao::isError())
            {
                $this->story->computeParentStage($story);
                $this->dao->delete()->from(TABLE_ROADMAPSTORY)->where('roadmap')->eq($oldStory->roadmap)->andWhere('story')->eq($storyID)->exec();

                $roadmapStory = new stdclass();
                $roadmapStory->roadmap = $roadmapID;
                $roadmapStory->story   = $storyID;
                $roadmapStory->order   = ++ $lastOrder;
                $this->dao->insert(TABLE_ROADMAPSTORY)->data($roadmapStory)->autoCheck()->exec();

                $allChanges[$storyID] = common::createChanges($oldStory, $story);
            }
        }
        return $allChanges;
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
        $story = parent::getAffectedScope($story);
        $users = $this->loadModel('user')->getPairs('pofirst|nodeleted', "{$story->openedBy}");

        $storyIdList = $story->twins ? trim($story->twins, ',') : '';
        if($storyIdList)
        {
            $this->app->loadLang('product');
            $this->config->story->affect->stories = new stdclass();
            $this->config->story->affect->stories->fields = array();
            $this->config->story->affect->stories->fields[] = array('name' => 'id',           'title' => $this->lang->story->id);
            $this->config->story->affect->stories->fields[] = array('name' => 'title',        'title' => $this->lang->story->title);
            $this->config->story->affect->stories->fields[] = array('name' => 'productTitle', 'title' => $this->lang->product->name);
            $this->config->story->affect->stories->fields[] = array('name' => 'status',       'title' => $this->lang->story->status);
            $this->config->story->affect->stories->fields[] = array('name' => 'openedBy',     'title' => $this->lang->story->openedBy);

            $story->stories = $this->dao->select('t1.id, t1.title, t1.status, t1.openedBy, t2.name as productTitle')
                ->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.id')->in($storyIdList)
                ->fetchAll('id');

            foreach($story->stories as $storyInfo)
            {
                $storyInfo->status   = zget($this->lang->story->statusList, $storyInfo->status, '');
                $storyInfo->openedBy = zget($users, $storyInfo->openedBy);
            }
        }

        return $story;
    }

    /**
     * 根据传入的需求获取跟踪矩阵，返回看板格式数据。
     * Get track by stories, return kanban format.
     *
     * @param  array   $stories
     * @param  string  $storyType    demand|epic|requirement|story
     * @param  array   $demands
     * @access public
     * @return array
     */
    public function getTracksByStories($stories, $storyType, $demands = array())
    {
        if(empty($demands)) return array();

        $rootIdList = array_unique(array_column($stories, 'root'));
        $allStories = $this->dao->select('id,demand,parent,color,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('root')->in($rootIdList)->andWhere('deleted')->eq(0)->orderBy('id')->fetchAll('id');
        $stories    = $this->storyTao->mergeChildrenForTrack($allStories, $stories, $storyType);
        $leafNodes  = $this->storyTao->getLeafNodes($stories, $storyType);

        $lanes = $this->storyTao->buildTrackLanes($leafNodes, $storyType, $demands);
        $cols  = $this->storyTao->buildTrackCols($storyType);
        $items = $this->storyTao->buildTrackItems($stories, $leafNodes, $storyType, $demands);

        return array('lanes' => $lanes, 'cols' => $cols, 'items' => $items, 'leafNodes' => $leafNodes);
    }

    /**
     * Batch update stories.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function batchUpdate($stories)
    {
        /* Init vars. */
        $oldStories = $this->getByList(array_keys($stories));

        foreach($stories as $storyID => $story) unset($story->status);

        $this->loadModel('action');
        foreach($stories as $storyID => $story)
        {
            $oldStory = $oldStories[$storyID];
            $this->dao->update(TABLE_STORY)->data($story)
                ->autoCheck()
                ->checkIF($story->closedBy, 'closedReason', 'notempty')
                ->checkIF($story->closedReason == 'done', 'stage', 'notempty')
                ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
                ->where('id')->eq((int)$storyID)
                ->exec();

            if(dao::isError()) return false;

            /* Update story sort of plan when story plan has changed. */
            if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);

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

            $this->executeHooks($storyID);
            if($oldStory->type == 'story' && $story->stage != $oldStory->stage) $this->batchChangeStage(array($storyID), $story->stage);
            if($story->closedReason == 'done') $this->loadModel('score')->create('story', 'close');
            if($story->roadmap != $oldStory->roadmap) $this->storyTao->computeParentStage($oldStory);

            $changes = common::createChanges($oldStory, $story);
            if($changes)
            {
                $actionID = $this->action->create('story', $storyID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            if($this->config->edition != 'open' && $oldStory->feedback && !isset($feedbacks[$oldStory->feedback]))
            {
                $feedbacks[$oldStory->feedback] = $oldStory->feedback;
                $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status);
            }
        }

        $this->loadModel('score')->create('ajax', 'batchEdit');

        return true;
    }

    /**
     * Batch create stories.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function batchCreate($stories)
    {
        $this->loadModel('action');
        $storyIdList = array();
        $link2Plans  = array();
        foreach($stories as $i => $story)
        {
            $storyID = $this->doCreateStory($story);
            if(!$storyID) return array();

            $this->doCreateSpec($storyID, $story);
            if(!empty($story->parent))
            {
                $this->subdivide($story->parent, array($storyID));
                $this->updateParentStatus($storyID, $story->parent, false);
            }
            else
            {
                $this->dao->update(TABLE_STORY)->set('root')->eq($storyID)->set('path')->eq(",{$storyID},")->where('id')->eq($storyID)->exec();
            }

            /* Update product plan stories order. */
            if(!empty($story->reviewer)) $this->doCreateReviewer($storyID, $story->reviewer);

            /* 将需求关联到路标中。 */
            if($story->roadmap)
            {
                $roadmapStory = new stdclass();
                $roadmapStory->roadmap = $story->roadmap;
                $roadmapStory->story   = $storyID;
                $roadmapStory->order   = 0;

                $this->dao->replace(TABLE_ROADMAPSTORY)->data($roadmapStory)->autoCheck()->exec();

                $vision = $story->stage == 'incharter' ? 'or,rnd' : 'or';
                $this->dao->update(TABLE_STORY)->set('vision')->eq($vision)->where('id')->eq($storyID)->exec();
            }

            /* 拆分时，如果父需求已经在已计划、研发立项阶段，则不需要通过拆分的子需求推算父需求的阶段。*/
            if(!empty($story->parent))
            {
                $parentStage = $this->dao->select('stage')->from(TABLE_STORY)->where('id')->eq($story->parent)->fetch('stage');
                if(!in_array($parentStage, array('planned', 'projected'))) $this->setStage($storyID);
            }

            $this->executeHooks($storyID);

            $this->action->create('story', $storyID, 'Opened', '');
            $storyIdList[$i] = $storyID;
        }

        if(!dao::isError())
        {
            /* Remove upload image file and session. */
            if($this->session->storyImagesFile)
            {
                $file     = current($_SESSION['storyImagesFile']);
                $realPath = dirname($file['realpath']);
                if(is_dir($realPath)) $this->app->loadClass('zfile')->removeDir($realPath);
                unset($_SESSION['storyImagesFile']);
            }

            $this->loadModel('score')->create('story', 'create',$storyID);
            $this->score->create('ajax', 'batchCreate');
            foreach($link2Plans as $planID => $stories) $this->action->create('productplan', $planID, 'linkstory', '', $stories);
        }

        return $storyIdList;
    }
}
