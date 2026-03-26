<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 删除项目模板。
     * Delete project template.
     *
     * @param  int   $id
     * @access public
     * @return void
     */
    public function deleteTemplate($id)
    {
        $this->dao->delete()->from(TABLE_PROJECT)->where('id')->eq($id)->exec();
        $this->loadModel('action')->create('projectTemplate', $id, 'deleted');

        $executionIdList = $this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->eq($id)->andWhere('isTpl')->eq('1')->fetchPairs();
        $taskIdList      = $this->dao->select('id')->from(TABLE_TASK)->where('project')->eq($id)->andWhere('isTpl')->eq('1')->fetchPairs();
        $docIdList       = $this->dao->select('id')->from(TABLE_DOC)->where('project')->eq($id)->fetchPairs();

        /* 将项目模板关联的执行、任务、文档的动态删除。 */
        $this->dao->delete()->from(TABLE_ACTION)->where('objectType')->eq('execution')->andWhere('objectID')->in($executionIdList)->exec();
        $this->dao->delete()->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->in($taskIdList)->exec();
        $this->dao->delete()->from(TABLE_ACTION)->where('objectType')->eq('doc')->andWhere('objectID')->in($docIdList)->exec();

        /* 将项目模板关联的执行、任务、文档删除。 */
        $this->dao->delete()->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->exec();
        $this->dao->delete()->from(TABLE_TASK)->where('id')->in($taskIdList)->exec();
        $this->dao->delete()->from(TABLE_DOC)->where('id')->in($docIdList)->exec();
        $this->dao->delete()->from(TABLE_GROUP)->where('project')->eq($id)->exec();
        $this->dao->delete()->from(TABLE_PROGRAMACTIVITY)->where('project')->eq($id)->exec();
        $this->dao->delete()->from(TABLE_AUDITPLAN)->where('project')->eq($id)->exec();

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('project', 'template')));
    }
}