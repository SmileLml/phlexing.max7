<?php
/**
 * @param int $productID
 * @param string $branch
 * @param string $modules
 * @param string $type
 * @param string $orderBy
 * @param object $pager
 */
public function getFeedbackStories($productID, $branch, $modules, $type, $orderBy, $pager)
{
    $sql = $this->dao->select("t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_STORY)->alias('t1');

    $stories = $sql->where('t1.product')->in($productID)
        ->andWhere('t1.deleted')->eq(0)
        ->andWhere('t1.vision')->eq($this->config->vision)
        ->andWhere('t1.type')->eq($type)
        ->andWhere('t1.feedback')->ne(0)
        ->beginIF($branch != 'all')->andWhere("t1.branch")->eq($branch)->fi()
        ->beginIF($modules)->andWhere("t1.module")->in($modules)->fi()
        ->orderBy($orderBy)
        ->page($pager)
        ->fetchAll('id');
    return $this->mergePlanTitleAndChildren($productID, $stories, $type);
}
