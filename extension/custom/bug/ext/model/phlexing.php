<?php

/**
 * 获取指定的需求 id name 的键值对。
 * Get pairs by list.
 *
 * @param  array|string $bugIdList
 * @access public
 * @return array
 */
public function getPairsByList($bugIdList)
{
    $bugPairs = $this->dao->select('id, title')->from(TABLE_BUG)->where('id')->in($bugIdList)->beginIF($this->config->vision == 'or')->andWhere("FIND_IN_SET('or', t1.vision)")->fi()->fetchPairs();

    return array(0 => '') + $bugPairs;
}
