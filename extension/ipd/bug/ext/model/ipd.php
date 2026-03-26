<?php
/**
 * 获取执行的 bug。
 * Get bugs of a execution.
 *
 * @param  int          $executionID
 * @param  int          $productID
 * @param  int|string   $branchID
 * @param  string|array $builds
 * @param  string       $type
 * @param  int          $param
 * @param  string       $orderBy
 * @param  string       $excludeBugs
 * @param  object       $pager
 * @access public
 * @return array
 */
public function getExecutionBugs($executionID, $productID = 0, $branchID = 'all', $builds = '0', $type = '', $param = 0, $orderBy = 'id_desc', $excludeBugs = '', $pager = null)
{
    $bugs = parent::getExecutionBugs($executionID, $productID, $branchID, $builds, $type, $param, $orderBy, $excludeBugs, $pager);

    /* 追加是否需要确认撤销/移除操作的信息到数据列表中。*/
    /* Build confirmeObject. */
    return $this->loadModel('story')->getAffectObject($bugs, 'bug');
}
