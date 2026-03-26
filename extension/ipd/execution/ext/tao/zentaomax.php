<?php
/**
 * 构造批量更新执行的数据。
 * Build bathc update execution data.
 *
 * @param  object $postData
 * @param  array  $oldExecutions
 * @access public
 * @return array
 */
public function buildBatchUpdateExecutions($postData, $oldExecutions)
{
    return $this->loadExtension('zentaomax')->buildBatchUpdateExecutions($postData, $oldExecutions);
}
