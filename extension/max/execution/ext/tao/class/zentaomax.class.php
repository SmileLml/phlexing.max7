<?php
class zentaomaxExecutionTao extends executionTao
{
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
        $executions = parent::buildBatchUpdateExecutions($postData, $oldExecutions);
        if(helper::hasFeature('deliverable'))
        {
            foreach($executions as $execution) $execution = $this->loadExtension('zentaomax')->changeExecutionDeliverable($execution->id, $execution);
        }
        return $executions;
    }
}
