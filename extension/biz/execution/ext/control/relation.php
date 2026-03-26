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
     * 任务关系列表。
     * Show relation of execution.
     *
     * @param  int    $executionID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function relation($executionID = 0, $recTotal = 0, $recPerPage = 25, $pageID = 1)
    {
        if($this->app->rawModule == 'programplan')
        {
            $projectID   = $executionID;
            $executionID = 0;
            $this->project->setMenu($projectID);
        }
        else
        {
            $execution = $this->commonAction($executionID);
            $projectID = $execution->project;
        }

        /* Load pager and get relations. */
        $this->app->loadClass('pager', true);
        $pager     = new pager($recTotal, $recPerPage, $pageID);
        $relations = $this->execution->getRelationsOfTasks($projectID, $executionID, $pager);

        if(!isset($_SESSION['limitedExecutions'])) $this->execution->getLimitedExecution();

        /* The header and position. */
        $this->view->title      = $this->lang->execution->common . $this->lang->hyphen . $this->lang->execution->gantt->relationOfTasks;
        $this->view->position[] = $this->lang->execution->gantt->relationOfTasks;

        $this->view->executionID = $executionID;
        $this->view->projectID   = $projectID;
        $this->view->tasks       = $this->loadModel('task')->getProjectTaskList($projectID, true, $executionID);
        $this->view->relations   = $relations;
        $this->view->pager       = $pager;
        $this->view->isLimited   = !$this->app->user->admin && strpos(",{$this->session->limitedExecutions},", ",{$executionID},") !== false;
        $this->display();
    }
}
