<?php
/**
 * @param int|mixed[] $productID
 * @param string $branch
 * @param string $browseType
 * @param int $queryID
 * @param int $moduleID
 * @param string $type
 * @param string $sort
 * @param object|null $pager
 */
public function getStories($productID, $branch, $browseType, $queryID, $moduleID, $type = 'story', $sort = 'id_desc', $pager = null)
{
    return $this->loadExtension('feedback')->getStories($productID, $branch, $browseType, $queryID, $moduleID, $type, $sort, $pager);
}
