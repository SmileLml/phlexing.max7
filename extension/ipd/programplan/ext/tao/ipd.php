<?php
/**
 * 更新IPD项目下评审点是否启用。
 * Update whether the evaluation point is enabled under the IPD project.
 *
 * @param  int    $projectID
 * @param  array  $points
 * @access public
 * @return bool 
 */
public function updatePoint($projectID = 0, $points = array())
{
    $this->dao->update(TABLE_OBJECT)->set('enabled')->eq(0)->where('project')->eq($projectID)->exec();
    $this->dao->update(TABLE_OBJECT)
        ->set('enabled')->eq(1)
        ->where('project')->eq($projectID)
        ->andWhere('category')->in(implode(',', $points))
        ->exec();
    return true;
}

/**
 * 获取IPD项目下已启用的评审点。 
 * Get the enabled review points under the IPD project. 
 *
 * @param  int    $projectID
 * @access public
 * @return object 
 */
public function getEnabledPoints($projectID)
{
    $this->loadModel('review');
    $ipdStagePoint  = clone $this->config->review->ipdReviewPoint;
    $disabledPoints = $this->dao->select('*')->from(TABLE_OBJECT)
        ->where('enabled')->eq(0)
        ->andWhere('project')->eq($projectID)
        ->fetchPairs('category', 'category');
    if(!$disabledPoints) return $ipdStagePoint;

    foreach($ipdStagePoint as $category => $points) $ipdStagePoint->$category = array_diff($points, $disabledPoints);
    return $ipdStagePoint;
}
