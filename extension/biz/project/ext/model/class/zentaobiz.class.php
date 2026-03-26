<?php
class zentaobizProject extends projectModel
{
    /**
     * 复制流程模板。
     * Copy workflow group
     *
     * @param object $project
     * @return void
     */
    public function copyWorkflowGroup($project)
    {
        $workflowGroup = $this->loadModel('workflowgroup')->getByID($project->workflowGroup);
        if(!$workflowGroup) return false;

        unset($workflowGroup->id);
        $workflowGroup->name        = $project->name . $this->lang->workflowgroup->flow;
        $workflowGroup->objectID    = $project->id;
        $workflowGroup->createdDate = helper::now();
        $workflowGroup->main        = 0;
        $workflowGroup->code        = '';
        $workflowGroup->editedDate  = null;

        $this->dao->insert(TABLE_WORKFLOWGROUP)->data($workflowGroup)->exec();
        $newWorkflowGroupID = $this->dao->lastInsertId();

        $this->dao->update(TABLE_PROJECT)->set('workflowGroup')->eq($newWorkflowGroupID)->where('id')->eq($project->id)->exec();

        $tableList = array(
            TABLE_WORKFLOW,
            TABLE_WORKFLOWFIELD,
            TABLE_WORKFLOWACTION,
            TABLE_WORKFLOWLABEL,
            TABLE_WORKFLOWLAYOUT,
            TABLE_WORKFLOWUI
        );

        foreach($tableList as $table)
        {
            $this->copyWorkflow($project->workflowGroup, $newWorkflowGroupID, $table);
        }
    }

    /**
     * 复制流程。
     * Copy workflow
     *
     * @param int    $workflowGroupID
     * @param int    $newWorkflowGroupID
     * @param string $table
     * @return void
    */
    public function copyWorkflow($workflowGroupID, $newWorkflowGroupID, $table = '')
    {
        $dataList = $this->dao->select('*')->from($table)->where('group')->eq($workflowGroupID)->fetchAll();
        foreach($dataList as $data)
        {
            unset($data->id);
            $data->group = $newWorkflowGroupID;

            if(isset($data->editedDate))     unset($data->editedDate);
            if(isset($data->lastEditedDate)) unset($data->lastEditedDate);

            if($table == TABLE_WORKFLOWFIELD && $data->role == 'custom') $data->role = 'quote';

            $this->dao->insert($table)->data($data)->exec();
        }
    }
}
