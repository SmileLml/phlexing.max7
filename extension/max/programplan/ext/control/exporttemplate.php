<?php
helper::importControl('programplan');
class myprogramplan extends programplan
{
    public function exportTemplate($projectID)
    {
        if($_POST)
        {
            $this->loadModel('task');
            $this->loadModel('file');
            $this->loadModel('tree');
            $this->loadModel('user');
            $project = $this->loadModel('project')->fetchByID($projectID);

            $this->config->task->templateFields = 'execution,' . $this->config->task->templateFields;
            $this->config->task->listFields     = "execution," . $this->config->task->listFields;
            $this->lang->excel->help->task     .= "\n{$this->lang->programplan->exportTaskTip}";

            $this->config->task->dtable->fieldList['execution']['dataSource']  = array();
            $this->config->task->dtable->fieldList['execution']['items']       = array(0 => '');
            $this->config->task->dtable->fieldList['assignedTo']['dataSource'] = array();
            $this->config->task->dtable->fieldList['story']['dataSource']      = array();
            $this->config->task->dtable->fieldList['module']['dataSource']     = array();

            dao::$filterTpl = 'never';
            $plans = $this->loadModel('execution')->getByProject($projectID, 'undone');
            if($plans)
            {
                $this->config->task->dtable->fieldList['execution']['items'] = array();
                $this->config->task->cascade = array('module' => 'execution', 'story' => 'execution', 'assignedTo' => 'execution');

                $storyGroups = $this->loadModel('story')->fetchStoriesByProjectIdList(array_keys($plans));
                foreach($plans as $planID => $plan)
                {
                    $this->config->task->dtable->fieldList['execution']['items'][$planID] = "{$plan->name}(#{$planID})";

                    $modules = $this->tree->getTaskOptionMenu($planID, 0, 'allModule');
                    foreach($modules as $moduleID => $moduleName) $_POST['cascade']['module'][$planID][$moduleID] = "{$moduleName}(#{$moduleID})";

                    $stories = zget($storyGroups, $planID, array());
                    foreach($stories as $storyID => $story) $_POST['cascade']['story'][$planID][$storyID] = "{$story->title}(#{$storyID})";

                    $members = $this->user->getTeamMemberPairs($planID, 'execution');
                    foreach($members as $account => $realname) $_POST['cascade']['assignedTo'][$planID][$account] = "{$realname}(#{$account})";
                }
            }

            $this->post->set('project', $project->name);
            $this->session->set('taskTransferParams', array('projectID' => $projectID));
            $this->fetch('transfer', 'exportTemplate', 'model=task&params=projectID='. $projectID);
        }

        $this->loadModel('transfer');
        $this->display();
    }
}
