<?php
class feedbackProduct extends productModel
{
    /**
     * @param int|mixed[] $productID
     * @param string $branch
     * @param string $browseType
     * @param int $queryID
     * @param int $moduleID
     * @param string $type
     * @param string $sort
     * @param object|null $pager
     */
    public function getStories($productID, $branch, $browseType, $queryID, $moduleID, $type = 'story', $sort = 'id_desc', $pager = null)
    {
        $browseType = ($browseType == 'bymodule' and $this->session->storyBrowseType and $this->session->storyBrowseType != 'bysearch') ? $this->session->storyBrowseType : $browseType;
        if($browseType == 'feedback')
        {
            /* Set modules and browse type. */
            $modules = $moduleID ? $this->loadModel('tree')->getAllChildID($moduleID) : '0';

            if(!$this->loadModel('common')->checkField(TABLE_STORY, 'feedback')) return array();
            $stories = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
                ->where('product')->in($productID)
                ->andWhere('deleted')->eq(0)
                ->beginIF($branch and $branch != 'all')->andWhere("branch")->eq($branch)->fi()
                ->beginIF($modules)->andWhere("module")->in($modules)->fi()
                ->andWhere('feedback')->ne(0)
                ->andWhere('type')->eq($type)
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll();
            return $this->loadModel('story')->mergePlanTitleAndChildren($productID, $stories, $type);
        }

        $this->loadModel('custom');
        $stories  = parent::getStories($productID, $branch, $browseType, $queryID, $moduleID, $type, $sort, $pager);
        $rawQuery = $this->dao->get();
        $relatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($stories), $type, 'byRelation', true);
        foreach($stories as $story) $story->relatedObject = zget($relatedObjectList, $story->id, 0);
        $this->dao->sqlobj->sql = $rawQuery; // For save query condition.
        return $stories;
    }
}
