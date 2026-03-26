<?php
class relationDesign extends designModel
{
    /**
     * 创建一个设计。
     * Create a design.
     *
     * @param  object   $design
     * @access public
     * @return bool|int
     */
    public function create($design)
    {
        $designID = parent::create($design);
        if(!$designID) return $designID;
        if(!empty($design->story))
        {
            $story = $this->loadModel('story')->getByID($design->story);
            $relation = new stdClass();
            $relation->AID      = $story->id;
            $relation->AType    = $story->type;
            $relation->relation = 'generated';
            $relation->BID      = $designID;
            $relation->BType    = 'design';
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }
        if(!empty($design->docs))
        {
            $docList     = $this->dao->select('id,version')->from(TABLE_DOC)->where('id')->in($design->docs)->fetchPairs();
            $docVersions = array();
            foreach(explode(',', $design->docs) as $docID)
            {
                $docVersions[$docID] = $docList[$docID];

                $relation = new stdClass();
                $relation->relation = 'interrated';
                $relation->AID      = $designID;
                $relation->AType    = 'design';
                $relation->BID      = $docID;
                $relation->BType    = 'doc';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
            $this->dao->update(TABLE_DESIGN)->set('docVersions')->eq(json_encode($docVersions))->where('id')->eq($designID)->exec();
        }
        return $designID;
    }

    /**
     * 编辑一个设计。
     * Update a design.
     *
     * @param  int        $designID
     * @param  object     $design
     * @access public
     * @return bool|array
     */
    public function update($designID = 0, $design = null)
    {
        $oldDesign = $this->getByID($designID);
        $changes   = parent::update($designID, $design);
        if(!$changes) return $changes;

        if($oldDesign->story > 0)
        {
            $this->dao->delete()->from(TABLE_RELATION)
                ->where('relation')->eq('generated')
                ->andWhere('AID')->eq($oldDesign->story)
                ->andWhere('AType')->eq($oldDesign->storyInfo->type)
                ->andWhere('BID')->eq($designID)
                ->andWhere('BType')->eq('design')
                ->exec();
        }
        if($design->story > 0)
        {
            $story = $this->loadModel('story')->getByID($design->story);
            $relation = new stdClass();
            $relation->AID      = $story->id;
            $relation->AType    = $story->type;
            $relation->relation = 'generated';
            $relation->BID      = $designID;
            $relation->BType    = 'design';
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }

        $this->dao->delete()->from(TABLE_RELATION)->where('relation')->eq('interrated')->andWhere('AID')->eq($designID)->andWhere('AType')->eq('design')->andWhere('BType')->eq('doc')->exec();

        $docVersions = array();
        $designDocs  = !empty($design->docs) ? explode(',', $design->docs) : array();
        if(!empty($design->oldDocs)) $designDocs = array_merge($designDocs, $design->oldDocs);
        if($designDocs)
        {
            $docList = $this->dao->select('id,version')->from(TABLE_DOC)->where('id')->in($designDocs)->fetchPairs();
            foreach($designDocs as $docID)
            {
                $docVersions[$docID] = !empty($design->docVersions[$docID]) ? $design->docVersions[$docID] : $docList[$docID];

                $relation = new stdClass();
                $relation->relation = 'interrated';
                $relation->AID      = $designID;
                $relation->AType    = 'design';
                $relation->BID      = $docID;
                $relation->BType    = 'doc';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }

        $this->dao->update(TABLE_DESIGN)
            ->set('docs')->eq(implode(',', $designDocs))
            ->set('docVersions')->eq(json_encode($docVersions))
            ->where('id')->eq($designID)
            ->exec();

        /* 由于关联文档的变更， 需要重新记录一下变更记录。 */
        $newDesign = $this->getByID($designID);
        $changes   = common::createChanges($oldDesign, $newDesign);
        return $changes;
    }
}
