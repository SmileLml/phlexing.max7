<?php
/**
 * @return int|false
 * @param object $story
 * @param int $executionID
 * @param int $bugID
 * @param string $extra
 * @param int $todoID
 */
public function create($story, $executionID = 0, $bugID = 0, $extra = '', $todoID = 0)
{
    return $this->loadExtension('feedback')->create($story, $executionID, $bugID, $extra, $todoID);
}

/**
 * @param string|bool $comment
 * @return bool|int
 * @param int $storyID
 * @param object $story
 */
public function update($storyID, $story, $comment = '')
{
    return $this->loadExtension('feedback')->update($storyID, $story, $comment);
}

/**
 * @return mixed[]|false
 * @param int $storyID
 * @param object $story
 */
public function change($storyID, $story)
{
    return $this->loadExtension('feedback')->change($storyID, $story);
}

/**
 * @param int $storyID
 */
public function recallChange($storyID)
{
    $this->loadExtension('feedback')->recallChange($storyID);
}

/**
 * @return object|false
 * @param int $storyID
 * @param int $version
 * @param bool $setImgSize
 */
public function getByID($storyID, $version = 0, $setImgSize = false)
{
    return $this->loadExtension('feedback')->getById($storyID, $version, $setImgSize);
}

/**
 * @param int $productID
 * @param int $storyID
 */
public function relieveTwins($productID, $storyID)
{
    return $this->loadExtension('feedback')->relieveTwins($productID, $storyID);
}

/**
 * @param int|mixed[] $executionID
 * @param mixed[]|string $excludeStories
 * @param int $productID
 * @param string $orderBy
 * @param string $browseType
 * @param string $param
 * @param string $storyType
 * @param object|null $pager
 */
public function getExecutionStories($executionID = 0, $productID = 0, $orderBy = 't1.`order`_desc', $browseType = 'byModule', $param = '0', $storyType = 'story', $excludeStories = '', $pager = null)
{
    return $this->loadExtension('feedback')->getExecutionStories($executionID, $productID, $orderBy, $browseType, $param, $storyType, $excludeStories, $pager);
}
