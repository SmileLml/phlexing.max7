<?php
class ipdBranchTao extends branchTao
{
    /**
     * @param int $productID
     * @param int $targetBranch
     * @param string $mergedBranches
     * @param object $data
     */
    protected function afterMerge($productID, $targetBranch, $mergedBranches, $data)
    {
        $this->dao->update(TABLE_ROADMAP)->set('branch')->eq($targetBranch)->where('branch')->in($mergedBranches)->exec();     // Update roadmap branch.

        return parent::afterMerge($productID, $targetBranch, $mergedBranches, $data);
    }
}
