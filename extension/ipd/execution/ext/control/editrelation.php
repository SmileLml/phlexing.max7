<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Qiyu Xie <xieqiyu@chandao.com>
 * @package     execution
 * @version     $Id$
 * @link        https://www.zentao.net
 */
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * 编辑任务关系。
     * Edit relation of task.
     *
     * @param  int    $relationID
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function editRelation($relationID, $projectID = 0, $executionID = 0)
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
            $this->execution->updateRelationOfTask($relationID, $projectID);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('load' => true));
        }

        $relations = $this->execution->getRelationsOfTasks($projectID, $executionID);
        $relation  = isset($relations[$relationID]) ? $relations[$relationID] : null;

        $titleTips          = '';
        $toolTips           = '';
        $taskRelationErrors = $this->execution->checkTaskRelation($relations, $projectID);
        foreach($taskRelationErrors as $currentRelation => $errors)
        {
            if($relationID == $currentRelation)
            {
                if(!empty($errors['preTaskIsParent']) || !empty($errors['afterTaskIsParent']) || !empty($errors['multiplePreTask'])) $titleTips = $this->lang->execution->error->parentTaskRelation;
                foreach($errors as $errorCode => $errorTip)
                {
                    $toolTips .= zget($this->lang->execution->error, $errorCode) . PHP_EOL;
                }
            }
        }

        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
        $this->view->relationID  = $relationID;
        $this->view->relation    = $relation;
        $this->view->titleTips   = $titleTips;
        $this->view->toolTips    = $toolTips;
        $this->view->tasks       = $this->loadModel('task')->getProjectTaskList($projectID, true, $executionID);
        $this->display();
    }
}
