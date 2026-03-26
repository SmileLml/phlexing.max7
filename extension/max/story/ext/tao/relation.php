<?php
/**
 * @param mixed[] $storyIdList
 * @param int $mainStoryID
 */
protected function updateTwins($storyIdList, $mainStoryID)
{
    $this->loadExtension('relation')->updateTwins($storyIdList, $mainStoryID);
}
