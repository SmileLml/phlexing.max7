<?php
public function getPairsByProduct($productID, $branch = 0)
{
    return $this->loadExtension('ops')->getPairsByProduct($productID, $branch);
}

/**
 * @param int $releaseID
 * @param mixed[] $stories
 */
public function linkStory($releaseID, $stories)
{
    return $this->loadExtension('ops')->linkStory($releaseID, $stories);
}

/**
 * @param int $releaseID
 * @param int $storyID
 */
public function unlinkStory($releaseID, $storyID)
{
    return $this->loadExtension('ops')->unlinkStory($releaseID, $storyID);
}

/**
 * @param int $releaseID
 * @param mixed[] $storyIdList
 */
public function batchUnlinkStory($releaseID, $storyIdList)
{
    return $this->loadExtension('ops')->batchUnlinkStory($releaseID, $storyIdList);
}
