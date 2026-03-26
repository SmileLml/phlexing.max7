<?php
/**
 * @return int|false
 * @param object $bug
 * @param string $from
 */
public function create($bug, $from = '')
{
    return $this->loadExtension('feedback')->create($bug, $from);
}

/**
 * @return object|false
 * @param int $bugID
 * @param bool $setImgSize
 */
public function getByID($bugID, $setImgSize = false)
{
    return $this->loadExtension('feedback')->getById($bugID, $setImgSize);
}
