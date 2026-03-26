<?php
/**
 * @param string $storyType
 */
public function buildTrackCols($storyType)
{
    return $this->loadExtension('ipd')->buildTrackCols($storyType);
}

/**
 * @param mixed[] $allStories
 * @param mixed[] $leafNodes
 * @param string $storyType
 * @param mixed[] $demands
 */
public function buildTrackItems($allStories, $leafNodes, $storyType, $demands = array())
{
    return $this->loadExtension('ipd')->buildTrackItems($allStories, $leafNodes, $storyType, $demands);
}

/**
 * @param mixed[] $leafNodes
 * @param string $storyType
 * @param mixed[] $demands
 */
public function buildTrackLanes($leafNodes, $storyType, $demands = array())
{
    return $this->loadExtension('ipd')->buildTrackLanes($leafNodes, $storyType, $demands);
}
