<?php
helper::importControl('programplan');
class myprogramplan extends programplan
{
    /**
     * Show import of program plan template.
     *
     * @param  int    $projectID
     * @param  int    $pagerID
     * @param  string $insert
     * @access public
     * @return void
     */
    public function showImport($projectID, $pagerID = 1, $insert = '')
    {
        $this->loadModel('project')->setMenu($projectID);

        /* Set datasource params for initfieldsList.*/
        $this->session->set('taskTransferParams', array('projectID' => $projectID));
        $this->loadModel('transfer');
        $this->loadModel('task');
        $this->loadModel('tree');
        $this->loadModel('user');

        $this->config->task->templateFields = 'execution,' . $this->config->task->templateFields;
        $this->config->task->listFields     = "execution," . $this->config->task->listFields;

        if($_POST) return $this->fetch('task', 'showImport', array('projectID' => $projectID, 'pagerID' => $pagerID, 'insert' => $insert));

        $this->config->task->dtable->fieldList['mode']['control']          = 'picker';
        $this->config->task->dtable->fieldList['mode']['hidden']           = true;
        $this->config->task->dtable->fieldList['estimate']['width']        = '160px';
        $this->config->task->dtable->fieldList['execution']['control']     = 'picker';
        $this->config->task->dtable->fieldList['execution']['dataSource']  = array();
        $this->config->task->dtable->fieldList['module']['dataSource']     = array();
        $this->config->task->dtable->fieldList['story']['dataSource']      = array();
        $this->config->task->dtable->fieldList['assignedTo']['dataSource'] = array();

        $plans          = $this->loadModel('execution')->getByProject($projectID, 'undone');
        $storyGroups    = $this->loadModel('story')->fetchStoriesByProjectIdList(array_keys($plans));
        $dropdownGroups = array();
        foreach($plans as $planID => $plan)
        {
            $this->config->task->dtable->fieldList['execution']['items'][$planID] = "{$plan->name}";
            $modules = $this->tree->getTaskOptionMenu($planID, 0, 'allModule');
            foreach($modules as $moduleID => $moduleName) $dropdownGroups['module'][$planID][] = array('value' => $moduleID, 'text' => $moduleName);

            $stories = zget($storyGroups, $planID, array());
            foreach($stories as $storyID => $story) $dropdownGroups['story'][$planID][] = array('value' => $storyID, 'text' => $story->title);

            $members = $this->user->getTeamMemberPairs($planID, 'execution');
            foreach($members as $account => $realname) $dropdownGroups['assignedTo'][$planID][] = array('value' => $account, 'text' => $realname);
        }

        /* Get page by datas.*/
        $taskData = $this->transfer->readExcel('task', $pagerID, $insert, 'estimate');
        if(empty($taskData)) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->task->noExcelData)));

        $datas = array();
        $teams = array();
        $users = $this->user->getPairs('noletter');
        foreach($taskData->datas as $key => $data)
        {
            $taskID = isset($data->id) ? $data->id : $key;
            $data->project    = $projectID;
            $data->module     = strpos($data->module, '(#') !== false     ? str_replace(')', '', substr($data->module, strpos($data->module, '(#') + 2)) : '';
            $data->story      = strpos($data->story, '(#') !== false      ? str_replace(')', '', substr($data->story, strpos($data->story, '(#') + 2))   : '';
            $data->assignedTo = strpos($data->assignedTo, '(#') !== false ? str_replace(')', '', substr($data->assignedTo, strpos($data->assignedTo, '(#') + 2))   : '';

            preg_match_all("/^([^:]+):([^:]+)$/m", $data->estimate, $matches);
            if(!empty($matches[1])) $data->team = $matches[1];
            if(!empty($matches[2]))
            {
                foreach($matches[2] as $key => $value) $matches[2][$key] = (float) $value;
                $data->estimate = $matches[2];
            }
            if(!empty($data->team))
            {
                foreach($data->team as $account) $teams[$account] = zget($users, $account);
            }

            $datas[$taskID]  = $data;
        }
        $taskData->datas = $datas;

        $taskIdList        = array_filter(array_column($taskData->datas, 'id', 'id'));
        $parentIdList      = array();
        $childrenDateLimit = array();
        $oldTasks          = array();
        if($taskIdList)
        {
            $oldTasks = $this->loadModel('task')->getByIdList($taskIdList);
            $parentIdList = array_column($oldTasks, 'parent', 'parent');

            list($childTasks) = $this->task->getChildTasksByList($taskIdList);
            foreach($childTasks as $parent => $children)
            {
                $childDateLimit = array('estStarted' => '', 'deadline' => '');
                foreach($children as $child)
                {
                    if(!helper::isZeroDate($child->estStarted) && (empty($childDateLimit['estStarted']) || $childDateLimit['estStarted'] > $child->estStarted)) $childDateLimit['estStarted'] = $child->estStarted;
                    if(!helper::isZeroDate($child->deadline)   && (empty($childDateLimit['deadline'])   || $childDateLimit['deadline']   < $child->deadline))   $childDateLimit['deadline']   = $child->deadline;
                }
                $childrenDateLimit[$parent] = $childDateLimit;
            }
        }

        $backLink = $this->createLink('project','execution', "status=undone&projectID={$projectID}");
        if(in_array($this->config->edition, array('max', 'ipd')) && common::hasPriv('programplan', 'browse')) $backLink = $this->createLink('programplan','browse', "projectID={$projectID}");

        $this->view->title             = $this->lang->task->common . $this->lang->hyphen . $this->lang->task->showImport;
        $this->view->datas             = $taskData;
        $this->view->childrenDateLimit = $childrenDateLimit;
        $this->view->oldTasks          = $oldTasks;
        $this->view->parentTasks       = $this->task->getByIdList($parentIdList);
        $this->view->backLink          = $backLink;
        $this->view->projectID         = $projectID;
        $this->view->teams             = $teams;
        $this->view->project           = $this->project->fetchById($projectID);
        $this->view->dropdownGroups    = $dropdownGroups;

        $this->display();
    }
}
