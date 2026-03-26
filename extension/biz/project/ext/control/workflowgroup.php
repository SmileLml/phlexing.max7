<?php
class myProject extends project
{
    /**
     * Project workflow group.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     * @param string $confirm
     */
    public function workflowGroup($projectID, $confirm = 'no')
    {
        $this->session->set('workflowList', $this->app->getURI(true));
        $this->project->setMenu($projectID);

        $project       = $this->project->fetchByID($projectID);
        $workflowGroup = $this->loadModel('workflowgroup')->getByID($project->workflowGroup);

        if(strtolower($this->server->request_method) == 'post')
        {
            if($confirm == 'no')
            {
                $formUrl = $this->createLink('project', 'workflowGroup', "projectID={$projectID}&confirm=yes");
                return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm('{$this->lang->project->confirmChangeWorkflowGroup}').then((res) => {if(res) $.ajaxSubmit({url: '$formUrl'});});"));
            }
            else
            {
                $this->project->copyWorkflowGroup($project);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
            }
        }

        $oldUserRights = $this->app->user->rights;
        $apps = $this->loadModel('workflow')->getApps();
        $apps['project'] = $this->lang->project->common;
        $this->app->user->rights = $oldUserRights; // 调用workflow getApps会重设用户权限，这样用户自己定义的工作流就看不到了。

        if($workflowGroup->objectID)
        {
            $flows = $this->workflowgroup->getFlows($workflowGroup, 'id_asc');
            if(!$project->multiple) unset($flows['productplan']); // 无迭代项目没有产品计划
            $this->view->flows = $flows;
        }

        $this->view->title   = $this->lang->project->workflowGroup;
        $this->view->project = $project;
        $this->view->group   = $workflowGroup;
        $this->view->apps    = $apps;
        $this->display();
    }
}
