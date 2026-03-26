<?php
/**
 * @param string|int $branch
 * @param mixed[]|string|int $moduleIdList
 * @param int $executionID
 * @param int $productID
 * @param string $type
 * @param string $status
 * @param string $storyType
 * @param bool $hasParent
 */
public function getExecutionStoryPairs($executionID = 0, $productID = 0, $branch = 'all', $moduleIdList = '', $type = 'full', $status = 'all', $storyType = '', $hasParent = true)
{
    if($this->config->vision == 'lite')
    {
        $execution = $this->loadModel('execution')->getById($executionID);
        if($execution->project) $executionID = $execution->project;
    }
    return parent::getExecutionStoryPairs($executionID, $productID, $branch, $moduleIdList, $type, $status, $storyType);
}
