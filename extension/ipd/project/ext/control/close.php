<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 关闭一个项目。
     * Close a project.
     *
     * @param  int $projectID
     * @access public
     *
     * @return void
     */
    public function close($projectID)
    {
        $this->project->setMenu($projectID);
        $project      = $this->project->fetchByID($projectID);
        $deliverables = $this->project->getProjectDeliverable($projectID, 0, 0, '', '', 'whenClosed');
        if($_POST)
        {
            /* 交付物必填校验。 */
            $project = form::data($this->config->project->form->close)->get();
            if(!empty($project->deliverable))
            {
                foreach($deliverables as $deliverable)
                {
                    /* 没有上传附件，并且没有选择文档，并且没有历史上传的附件。 */
                    if(!empty($deliverable['required']) && empty($_FILES['deliverable']['name'][$deliverable['id']]) && empty($project->deliverable[$deliverable['id']]['doc']) && empty($project->deliverable[$deliverable['id']]['fileID']))
                    {
                        dao::$errors["deliverable[{$deliverable['id']}]"] = sprintf($this->lang->error->notempty, $this->lang->project->deliverableAbbr);;
                    }
                }
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }
        }
        else
        {
            $this->view->deliverables = $deliverables;
        }
        return parent::close($projectID);
    }
}
