<?php
/**
 * @param mixed[] $plans
 */
public function create($plans, $projectID = 0, $productID = 0, $parentID = 0, $syncData = 0)
{
    return $this->loadExtension('zentaoipd')->create($plans, $projectID, $productID, $parentID, $syncData);
}
