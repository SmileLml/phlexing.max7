<?php
/**
 * Get roadmaps by products.
 *
 * @param  array|string $products
 * @param  string       $status
 * @access public
 * @return array
 */
public function getRoadmapPairs($products, $status = 'launched')
{
    return $this->dao->select('id,name')->from(TABLE_ROADMAP)
        ->where('product')->in($products)
        ->andWhere('deleted')->eq(0)
        ->andWhere('status')->in($status)
        ->fetchPairs();
}
