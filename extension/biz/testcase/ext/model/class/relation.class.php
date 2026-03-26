<?php
class relationTestcase extends testcaseModel
{
    /**
     * 创建一个用例。
     * Create a case.
     *
     * @param  object $case
     * @access public
     * @return bool|int
     */
    public function create($case)
    {
        $caseID = parent::create($case);
        if(!$caseID) return $caseID;

        $relation = new stdClass();
        $relation->relation = 'generated';
        $relation->BID      = $caseID;
        $relation->BType    = 'testcase';
        $relation->product  = 0;
        if(!empty($case->story))
        {
            $relation->AID   = $case->story;
            $relation->AType = 'story';
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }
        if(!empty($case->fromBug))
        {
            $relation->AID   = $case->fromBug;
            $relation->AType = 'bug';
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }
        return $caseID;
    }

    /**
     * 更新用例。
     * Update a case.
     *
     * @param  object $case
     * @param  object $oldCase
     * @param  array  $testtasks
     * @access public
     * @return bool|array
     */
    public function update($case, $oldCase, $testtasks = array())
    {
        $changes = parent::update($case, $oldCase, $testtasks);
        if(!$changes) return $changes;
        if($oldCase->story > 0)
        {
            $this->dao->delete()->from(TABLE_RELATION)
                ->where('relation')->eq('generated')
                ->andWhere('AID')->eq($oldCase->story)
                ->andWhere('AType')->eq('story')
                ->andWhere('BID')->eq($oldCase->id)
                ->andWhere('BType')->eq('testcase')
                ->exec();
        }
        if(isset($case->story) && $case->story > 0)
        {
            $relation = new stdClass();
            $relation->AID      = $case->story;
            $relation->AType    = 'story';
            $relation->relation = 'generated';
            $relation->BID      = $oldCase->id;
            $relation->BType    = 'testcase';
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }
        return $changes;
    }
}
