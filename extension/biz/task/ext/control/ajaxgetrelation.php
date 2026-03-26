<?php
class myTask extends task
{
    /**
     * 获取任务的关联关系。
     * Get task relation.
     *
     * @param  string $taskIdList
     * @access public
     * @return void
     */
    public function ajaxGetRelation($taskIdList = '')
    {
        if(empty($taskIdList)) return;

        $taskRelations = $this->dao->select('id')->from(TABLE_RELATIONOFTASKS)->where('pretask')->in($taskIdList)->orWhere('task')->in($taskIdList)->fetchPairs('id');
        if(empty($taskRelations)) return;

        return $this->send($taskRelations);
    }
}
