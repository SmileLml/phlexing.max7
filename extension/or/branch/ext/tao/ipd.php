<?php
/**
 * @param int $productID
 * @param int $targetBranch
 * @param string $mergedBranches
 * @param object $data
 */
protected function afterMerge($productID, $targetBranch, $mergedBranches, $data)
{
    return $this->loadExtension('ipd')->afterMerge($productID, $targetBranch, $mergedBranches, $data);
}
