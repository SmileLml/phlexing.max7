<?php
helper::importControl('execution');
class myExecution extends execution
{
    public function ajaxGetProjectExecutions($projectID = 0, $multiple = '')
    {
        $this->app->loadLang('project');

        $executions = $this->loadModel('execution')->getByProject($projectID);
        $projects   = $this->loadModel('project')->getPairsByIdList(array(), 'all');

        $executionList = array(0 => '');
        foreach($executions as $execution)
        {
            if($multiple && !$execution->multiple) continue;

            if(!$projectID and isset($projects[$execution->project]))                                      $execution->name = $projects[$execution->project] . '/' . $execution->name;
            if(isset($executions[$execution->parent]) && $executions[$execution->parent]->type == 'stage') $execution->name = $executions[$execution->parent]->name . ' / ' . $execution->name;
            if(empty($execution->multiple) and isset($projects[$execution->project]))                      $execution->name = $projects[$execution->project] . "({$this->lang->project->disableExecution})";

            $executionList[$execution->id] = $execution->name;
        }

        if($this->app->getViewType() == 'json') return print(json_encode($executionList));

        return print(html::select('execution', array(0 => '') + $executionList, '', "class='form-control' onchange='loadTeamMembers(this.value)'"));
    }
}
