<?php
/**
 * @param int $buildID
 * @param mixed[] $storyIdList
 */
public function linkStory($buildID, $storyIdList)
{
    return $this->loadExtension('relation')->linkStory($buildID, $storyIdList);
}

/**
 * @param int $buildID
 * @param int $storyID
 */
public function unlinkStory($buildID, $storyID)
{
    return $this->loadExtension('relation')->unlinkStory($buildID, $storyID);
}

/**
 * @param int $buildID
 * @param mixed[] $storyIDList
 */
public function batchUnlinkStory($buildID, $storyIDList)
{
    return $this->loadExtension('relation')->batchUnlinkStory($buildID, $storyIDList);
}
