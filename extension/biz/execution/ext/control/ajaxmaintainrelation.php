<?php
class execution extends control
{
    /**
     * 甘特图连线建立任务关系。
     * Maintain task relation from the gantt by ajax.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxMaintainRelation($projectID, $executionID)
    {
        if(!empty($projectID) && empty($executionID))
        {
            $this->project->setMenu($projectID);
        }
        else
        {
            $execution = $this->commonAction($executionID);
            $projectID = $execution->project;
        }

        if(!empty($_POST))
        {
            $this->execution->createRelationOfTasks($projectID, $executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'linkID' => $this->dao->lastInsertID()));
        }
    }
}
