<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 */
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * 维护任务关系。
     * Maintain relation of execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function maintainRelation($executionID = 0)
    {
        if(!empty($_POST))
        {
            $this->execution->editRelationOfTasks($executionID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate(inlink('relation', "executionID=$executionID"), 'parent'));
        }

        $execution   = $this->commonAction($executionID);
        $tasks       = $this->loadModel('task')->getExecutionTaskPairs($executionID);
        $undoneTasks = $this->task->getExecutionTaskPairs($executionID, 'wait,doing,pause');
        $relations   = $this->execution->getRelationsOfTasks($executionID);

        /* The header and position. */
        $this->view->title      = $this->lang->execution->common . $this->lang->hyphen . $this->lang->execution->gantt->editRelationOfTasks;
        $this->view->position[] = $this->lang->execution->gantt->editRelationOfTasks;

        $this->view->executionID = $executionID;
        $this->view->tasks       = $tasks;
        $this->view->undoneTasks = arrayUnion(array('' => ''), $undoneTasks);
        $this->view->relations   = $relations;
        $this->view->execution   = $execution;
        $this->display();
    }
}
