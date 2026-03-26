<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * Create a execution.
     *
     * @param int    $projectID
     * @param int    $executionID
     * @param int    $copyExecutionID
     * @param int    $planID
     * @param string $confirm
     * @param int    $productID
     * @param string $extra
     *
     * @access public
     * @return void
     */
    public function create($projectID = 0, $executionID = 0, $copyExecutionID = 0, $planID = 0, $confirm = 'no', $productID = 0, $extra = '')
    {
        if(!empty($_POST['project']))
        {
            $projectID = $_POST['project'];
            $project   = $this->loadModel('project')->getByID($projectID);
            if($project->model == 'ipd')
            {
               if(empty($_POST['parent'])) return $this->send(array('result' => 'fail', 'message' => array('parent' => $this->lang->execution->parentNotEmpty)));

               $this->app->loadLang('programplan');
               $parent = $this->loadModel('execution')->getByID($_POST['parent']);
               $_POST['attribute'] = $parent->attribute;

               if($_POST['begin'] < $parent->begin) return $this->send(array('result' => 'fail', 'message' => array('begin' => sprintf($this->lang->programplan->error->letterParent, $parent->begin))));
               if($_POST['end'] > $parent->end) return $this->send(array('result' => 'fail', 'message' => array('end' => sprintf($this->lang->programplan->error->greaterParent, $parent->end))));
            }
        }

        return parent::create($projectID, $executionID, $copyExecutionID, $planID, $confirm, $productID, $extra);
    }
}
