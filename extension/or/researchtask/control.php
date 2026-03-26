<?php
/**
 * The control file of researchtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hucheng Tang <tanghucheng@easycorp.ltd>
 * @package     researchtask
 * @link        https://www.zentao.net
 */
class researchtask extends control
{
    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        global $lang;
        $this->loadModel('execution');
        $this->loadModel('market');
        $this->loadModel('task');

        $lang->execution->common = $this->lang->execution->stage;
        $lang->projectCommon     = $this->lang->marketresearch->common;
        $lang->executionCommon   = $this->lang->execution->stage;
    }

    /**
     * Create research task.
     *
     * @param  int    $researchID
     * @param  int    $stageID
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function create($researchID = 0, $stageID = 0, $taskID = 0)
    {
        $marketID = $this->market->getIdByResearch($researchID);
        $this->market->setMenu($marketID);

        $task = new stdClass();
        $task->assignedTo = '';
        $task->name       = '';
        $task->type       = '';
        $task->pri        = '3';
        $task->estimate   = '';
        $task->desc       = '';
        $task->estStarted = '';
        $task->deadline   = '';
        $task->mailto     = '';
        $task->color      = '';
        if($taskID > 0) $task = $this->task->getByID($taskID);

        if(!empty($_POST))
        {
            $response['result'] = 'success';
            $_POST['project']   = $researchID;
            if(empty($_POST['assignedTo'])) $_POST['assignedTo'] = array('');

            /* Create task here. */
            $tasksID = $this->researchtask->create($_POST['execution'], $researchID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            /* Create actions. */
            $this->loadModel('action');
            foreach($tasksID as $taskID)
            {
                /* if status is exists then this task has exists not new create. */
                if($taskID['status'] == 'exists') continue;

                $taskID = $taskID['id'];
                $this->action->create('task', $taskID, 'Opened', '');
            }

            if($this->post->after == 'continueAdding')
            {
                $response['message'] = $this->lang->task->successSaved . $this->lang->researchtask->afterChoices['continueAdding'];
                $response['locate']  = $this->createLink('researchtask', 'create', "researchID=$researchID&stageID=$stageID");
                return $this->send($response);
            }
            elseif($this->post->after == 'toTaskList')
            {
                $response['message'] = $this->lang->saveSuccess;
                $response['locate']  = $this->createLink('marketresearch', 'task', "researchID=$researchID");
                return $this->send($response);
            }
        }

        $this->view->title    = $this->lang->researchtask->create;
        $this->view->stageID  = $stageID;
        $this->view->taskID   = $taskID;
        $this->view->task     = $task;
        $this->view->members  = $this->loadModel('user')->getTeamMemberPairs($researchID, 'project', 'nodeleted');
        $this->view->stages   = $this->loadModel('programplan')->getPairs($researchID, 0, 'leaf');
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->marketID = $marketID;

        $this->display();
    }

    /**
     * Batch create task.
     *
     * @param int    $executionID
     * @param int    $taskID
     * @param string $extra
     *
     * @access public
     * @return void
     */
    public function batchCreate($executionID = 0, $taskID = 0, $extra = '')
    {
        $execution  = $this->loadModel('execution')->getById($executionID);
        $marketID   = $this->market->getIdByResearch($execution->project);
        $taskLink   = $this->createLink('marketresearch', 'task', "executionID=$execution->project");
        $parentTask = $this->loadModel('task')->fetchById($taskID);

        $this->market->setMenu($marketID);

        if(!empty($_POST))
        {
            $taskData = form::batchData()->get();
            foreach($taskData as $task) $task->left = $task->estimate;

            $taskIdList = $this->task->batchCreate($taskData, array());
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Update other data related to the task after it is created. */
            $this->task->afterBatchCreate($taskIdList, $taskID ? $parentTask : null);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => isInModal() ? true : $taskLink));
        }

        if($taskID) $parentTitle = $parentTask->name;
        if($taskID) $this->view->parentPri = $parentTask->pri;

        /* When common task are child tasks, query whether common task are consumed. */
        $taskConsumed = 0;
        if($taskID) $taskConsumed = $this->dao->select('consumed')->from(TABLE_TASK)->where('id')->eq($taskID)->andWhere('parent')->eq(0)->fetch('consumed');

        $this->view->title        = $parentTitle . $this->lang->hyphen . $this->lang->task->batchCreateChildren;
        $this->view->execution    = $execution;
        $this->view->parent       = $taskID;
        $this->view->members      = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->taskConsumed = $taskConsumed;
        $this->view->marketID     = $marketID;
        $this->display();
    }

    /**
     * Edit a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function edit($taskID)
    {
        $this->loadModel('task');
        $this->loadModel('execution');
        $task     = $this->task->getById($taskID);
        $stage    = $this->execution->getById($task->execution);
        $marketID = $this->market->getIdByResearch($stage->project);

        $this->market->setMenu($marketID);

        if(!empty($_POST))
        {
            $_POST['team'] = $task->team;
            $this->loadModel('action');
            $taskData = form::data($this->config->task->form->edit)
                ->add('id', (int)$taskID)
                ->add('left', $task->left)
                ->get();
            $changes  = $this->task->update($taskData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $action   = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('task', $taskID, $action, $this->post->comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('status' => 'success', 'data' => $taskID));
            }
            else
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => isInModal() ? 'reload' : $this->createLink('marketresearch', 'task', "researchID={$task->project}")));
            }
        }

        $tasks = $this->task->getParentTaskPairs($task->execution, $task->parent, $task->id);
        if(isset($tasks[$taskID])) unset($tasks[$taskID]);

        $this->view->task     = $task;
        $this->view->tasks    = $tasks;
        $this->view->title    = $this->lang->task->edit . 'TASK' . $this->lang->hyphen . $this->view->task->name;
        $this->view->stageID  = $task->execution;
        $this->view->members  = $this->loadModel('user')->getTeamMemberPairs($task->project, 'project', 'nodeleted');
        $this->view->stages   = $this->execution->getByProject($task->project, 'all', 0, true);
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->actions  = $this->loadModel('action')->getList('task', $taskID);
        $this->view->marketID = $marketID;

        $this->display();
    }

    public function view($taskID)
    {
        $taskID = (int)$taskID;
        $task   = $this->task->getById($taskID, true);
        $this->loadModel('execution');
        $execution = $this->execution->getById($task->execution);
        $marketID  = $this->market->getIdByResearch($execution->project);

        $this->market->setMenu($marketID);
        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        /* Update action. */
        if($task->assignedTo == $this->app->user->account) $this->loadModel('action')->read('task', $taskID);

        $title = "TASK#$task->id $task->name / $execution->name";

        $this->view->title     = $title;
        $this->view->execution = $execution;
        $this->view->task      = $task;
        $this->view->actions   = $this->loadModel('action')->getList('task', $taskID);
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->marketID  = $marketID;
        $this->display();
    }

    /**
     * Start a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function start($taskID)
    {
        echo $this->fetch('task', 'start', "taskID=$taskID");
    }

    /**
     * Finish a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function finish($taskID)
    {
        echo $this->fetch('task', 'finish', "taskID=$taskID");
    }

    /**
     * Delete a task.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  string $from
     * @access public
     * @return void
     */
    public function delete($executionID, $taskID, $from = 'market')
    {
        echo $this->fetch('task', 'delete', "executionID=$executionID&taskID=$taskID&from=$from");
    }

    /**
     * Close a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function close($taskID)
    {
        echo $this->fetch('task', 'close', "taskID=$taskID");
    }

    /**
     * Cancel a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function cancel($taskID)
    {
        echo $this->fetch('task', 'cancel', "taskID=$taskID");
    }

    /**
     * Activate a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function activate($taskID)
    {
        echo $this->fetch('task', 'activate', "taskID=$taskID");
    }

    /**
     * Record consumed and estimate.
     *
     * @param  int    $taskID
     * @param  string $from
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function recordWorkhour($taskID, $from = '', $orderBy = '')
    {
        echo $this->fetch('task', 'recordWorkhour', "taskID=$taskID&from=$from&orderBy=$orderBy");
    }

    /**
     * 编辑一条日志。
     * Edit a effort.
     *
     * @param  int    $effortID
     * @access public
     * @return void
     */
    public function editEffort($effortID)
    {
        echo $this->fetch('task', 'editEffort', "effortID=$effortID");
    }

    /**
     * 删除任务工时。
     * Delete the work hour from the task.
     *
     * @param  int    $effortID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteWorkhour($effortID, $confirm = 'no')
    {
        echo $this->fetch('task', 'deleteWorkhour', "effortID=$effortID&confirm=$confirm");
    }

    /**
     * Update assign of task.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  string $kanbanGroup
     * @param  string $from
     * @access public
     * @return void
     */
    public function assignTo($executionID, $taskID)
    {
        echo $this->fetch('task', 'assignTo', "executionID=$executionID&taskID=$taskID");
    }
}
