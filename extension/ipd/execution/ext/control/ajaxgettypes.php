<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * @param int $executionID
     */
    public function ajaxGetTypes($executionID = 0)
    {
        $sprints = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('parent')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('type');

        if(!empty($sprints['sprint']) or !empty($sprints['kanban'])) unset($this->lang->execution->typeList['stage']);
        if(!empty($sprints['stage']))
        {
            unset($this->lang->execution->typeList['sprint']);
            unset($this->lang->execution->typeList['kanban']);
        }

        return print(html::select('type', $this->lang->execution->typeList, '', "class='form-control'"));
    }
}
