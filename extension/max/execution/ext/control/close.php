<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * 关闭迭代。
     * Close execution.
     *
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function close($executionID, $from = 'execution')
    {
        $execution    = $this->commonAction($executionID);
        $deliverables = $this->loadModel('project')->getProjectDeliverable(0, $executionID, 0, '', '', 'whenClosed');
        if($_POST)
        {
            /* 交付物必填校验。 */
            $postData = fixer::input('post')->add('id', $executionID)->get();
            if(!empty($postData->deliverable))
            {
                foreach($deliverables as $deliverable)
                {
                    /* 没有上传附件，并且没有选择文档，并且没有历史上传的附件。 */
                    if(!empty($deliverable['required']) && empty($_FILES['deliverable']['name'][$deliverable['id']]) && empty($postData->deliverable[$deliverable['id']]['doc']) && empty($postData->deliverable[$deliverable['id']]['fileID']))
                    {
                        dao::$errors["deliverable[{$deliverable['id']}]"] = sprintf($this->lang->error->notempty, $this->lang->project->deliverableAbbr);;
                    }
                }
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if($this->config->edition == 'ipd')
            {
                $result = $this->execution->checkStageStatus($executionID, 'close');
                if($result['disabled']) return $this->send(array('result' => 'fail', 'message' => $result['message']));
            }
        }
        else
        {
            $execution = $this->execution->fetchByID($executionID);

            $this->view->deliverables = $deliverables;
            $this->view->project      = $this->project->fetchByID($execution->project);
        }
        return parent::close($executionID, $from);
    }
}
