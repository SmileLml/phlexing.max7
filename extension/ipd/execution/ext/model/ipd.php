<?php
/**
 * 追加评审点列表到执行列表。
 * Append points to execution list.
 *
 * @param  array  $points
 * @param  array  $rows
 * @param  array  $pendingReviews
 * @access public
 * @return array
 */
public function appendPoints($points, $rows, $pendingReviews = array())
{
    if(!$this->cookie->showStage) return $rows;
    $this->app->loadConfig('project');
    $this->loadModel('review');

    foreach($points as $point)
    {
        $canSubmit = true;
        foreach($this->config->project->execution->dtable->actionsRule['point'] as $actionName)
        {
            $actions = explode('|', $actionName);

            foreach($actions as $name)
            {
                $rawAction = str_replace('Review', '', $name);
                $action    = array('name' => $name);

                if($rawAction == 'submit' && !$point->review) continue;
                if($rawAction == 'create' && $point->review) continue;

                $clickable = $this->review->isClickable($point, $rawAction);
                $disabled  = $clickable ? false : true;

                /* 阶段未开始时，当前评审点不允许评审。*/
                if($rawAction == 'create' && $point->execStatus == 'wait')
                {
                    $disabled       = true;
                    $action['hint'] = $this->lang->review->stageNotStartTip;
                }

                /* 前一评审点未评审通过时，当前评审点不允许评审。*/
                if(!$point->isFirst && $rawAction == 'create' && $point->preResult != 'pass' && !$point->result)
                {
                    $disabled       = true;
                    $action['hint'] = $this->lang->review->prePointNotPassTip;
                }

                if($point->result == 'pass' && in_array($rawAction, array('create', 'submit'))) $disabled = true;
                if($rawAction == 'submit' && $point->result == 'fail') $disabled = false;
                if($rawAction == 'assess') $disabled = !isset($pendingReviews[$point->review]);
                if($rawAction == 'create' && $disabled) $canSubmit = false;
                $action['disabled'] = $disabled;
                $point->actions[]   = $action;
            }
        }

        $point->name = (common::hasPriv('review', 'view') and $point->rawStatus) ? html::a(helper::createLink('review', 'view', "id=$point->review"), $point->text, '', "", "data-app='project'") : "<span>$point->text</span>";
        if(!$point->rawStatus && $canSubmit) $point->name .= html::a(helper::createLink('review', 'create', "projectID=$point->project"), '<i class="icon-confirm"></i></button>', '', 'class="btn btn-link submitBtn" title="' . $this->lang->programplan->submit . '"');
        $point->rawID    = $point->id;
        $point->id       = 'tid' . (string)$point->id;
        $point->end      = $point->deadline;
        $point->left     = '';
        $point->consumed = '';
        $point->estimate = '';
        $point->begin    = '';
        $point->parent   = 'pid' . (string)$point->parent;

        $rows[] = $point;
    }

    return $rows;
}

/**
 * Check ipd stage is ready to begin.
 *
 * @param  array  $stages
 * @access public
 * @return array
 */
public function appendActions($stages = array())
{
    if(empty($stages)) return $stages;
    $projects = $this->loadModel('project')->getPairsByModel('ipd');

    $newStages       = array();
    $ipdProjectGroup = array();
    foreach($stages as $stage)
    {
        $stageProject          = $stage->project;
        $newStages[$stage->id] = $stage;

        if($stage->parallel)                  continue; // 如果是并行阶段，跳过
        if(!isset($projects[$stageProject]))  continue; // 不是IPD项目，跳过
        if($stage->parent != $stage->project) continue; // 不是父阶段，跳过
        $ipdProjectGroup[$stageProject][$stage->order] = $stage;
    }
    if(empty($ipdProjectGroup)) return $stages; // Do not need to append actions (no $ipdProjectGroup)

    foreach($ipdProjectGroup as $project => $projectStages)
    {
        $preStage = '';
        foreach($projectStages as $order => $stage)
        {
            $preStageName  = $preStage ? $preStage->name : '';
            $stage->action = new stdclass();
            $stage->action->start = array('disabled' => false, 'hint' => '', 'preStageName' => $preStageName, 'stageName' => $stage->name);
            $stage->action->close = $stage->action->start;

            /* 上一阶段结束，当前阶段才能开始。*/
            if($preStage && $preStage->status != 'closed')
            {
                $message = sprintf($this->lang->execution->disabledTip->startTip, $preStage->name , $stage->name);
                $stage->action->start['disabled'] = true;
                $stage->action->start['hint']     = $message;
            }

            /* 当前阶段评审点都通过，当前阶段才能关闭。*/
            if(empty($stage->points)) continue;
            foreach($stage->points as $point)
            {
                if($point->result == 'pass') continue;
                $message = $this->lang->execution->disabledTip->closeTip;
                $stage->action->close['disabled'] = true;
                $stage->action->close['hint']     = $message;
            }
            $preStage              = $stage;
            $newStages[$stage->id] = $stage;
        }
    }

    return $newStages;
}

/**
 * 获取执行列表数据。
 * Get statData.
 *
 * @param  int         $projectID
 * @param  string      $browseType
 * @param  int         $productID
 * @param  int         $branch
 * @param  bool        $withTasks
 * @param  string|int  $param
 * @param  string      $orderBy
 * @param  object|null $pager
 * @access public
 * @return array
 */
public function getStatData($projectID = 0, $browseType = 'undone', $productID = 0, $branch = 0, $withTasks = false, $param = '', $orderBy = 'order_asc', $pager = null)
{
    $executions = parent::getStatData($projectID, $browseType, $productID, $branch, $withTasks, $param, $orderBy, $pager);
    $project    = $this->loadModel('project')->getByID($projectID);
    if(!$this->app->tab == 'project' || ($project && $project->model != 'ipd')) return $executions;

    $points     = $this->loadModel('review')->getPointsByProjectID($projectID);
    $executions = $this->buildExecutionTree($executions, $points);
    $executions = $this->appendActions($executions);

    return $executions;
}

/**
 * 构建执行树。
 * Build execution tree.
 *
 * @param  array  $executions
 * @param  array  $points
 *
 * @access public
 * @return array
 */
public function buildExecutionTree($executions = array(), $points = array())
{
    $prePointResult = '';
    $isFirst        = true;
    $reviewDeadline = array();
    $this->loadModel('review');
    $this->loadModel('programplan');
    foreach($executions as $execution)
    {
        if($execution->projectModel == 'ipd' && $execution->grade == 1) $execution->isIpdStage = true;

        if($execution->grade > 1) continue; // 如果是子阶段，跳过
        if(!isset($this->config->review->ipdReviewPoint->{$execution->attribute})) continue;
        $reviewDeadline[$execution->id]['stageEnd']   = $execution->end;
        $reviewDeadline[$execution->id]['stageBegin'] = $execution->begin;

        foreach($points as $id => $point)
        {
            if(!in_array($point->category, $this->config->review->ipdReviewPoint->{$execution->attribute})) continue;

            $data = new stdclass();
            $data->id         = $execution->id . '-' . $point->category . '-' . $point->id;
            $data->type       = 'point';
            $data->text       = "<i class='icon-seal'></i> " . $point->title;
            $data->name       = $point->title;
            $data->rawName    = $point->title;
            $data->open       = true;
            $data->duration   = 1;
            $data->parent     = $execution->id;
            $data->review     = $point->review;
            $data->result     = $point->result;
            $data->approval   = $point->approval;
            $data->project    = $execution->project;
            $data->begin      = $data->deadline = $data->endDate = $point->deadline;
            $data->realBegan  = $point->realBegan;
            $data->realEnd    = $point->lastReviewedDate;;
            $data->rawStatus  = $point->status;
            $data->status     = $point->status ? $point->status : $this->lang->programplan->wait;
            $data->preResult  = $prePointResult;
            $data->execStatus = $execution->status;
            $data->isFirst    = $isFirst;
            $data->deadline   = $this->programplan->getPointEndDate($execution->id, $point, $reviewDeadline);

            if($data->status == 'wait') $data->status = zget($this->lang->review->statusList, $data->status);
            if($isFirst) $isFirst = false;

            $execution->points[]   = $data;
            $execution->isIpdStage = true;
            $prePointResult        = $point->result;
        }
    }
    return $executions;
}

/*
 * 构建任务操作按钮。
 * Build task actions.
 *
 * @param  array  $executions
 * @access public
 * @return array
 */
/**
 * @param mixed[] $executions
 */
public function buildTaskActions($executions = array())
{
    foreach($executions as $execution)
    {
        if(empty($execution->tasks)) continue;
        if(empty($execution->action->start['disabled'])) continue;

        $startAction = $execution->action->start;
        foreach($execution->tasks as $task)
        {
            foreach($task->actions as $key => $action)
            {
                if(!in_array($action['name'], array('startTask', 'finishTask', 'recordWorkhour'))) continue;
                $action['disabled'] = true;
                if(isset($startAction['preStageName']) && $action['name'] == 'startTask')      $action['hint'] = sprintf($this->lang->execution->disabledTip->taskStartTip,  $startAction['preStageName'], $startAction['stageName']);
                if(isset($startAction['preStageName']) && $action['name'] == 'finishTask')     $action['hint'] = sprintf($this->lang->execution->disabledTip->taskFinishTip, $startAction['preStageName'], $startAction['stageName']);
                if(isset($startAction['preStageName']) && $action['name'] == 'recordWorkhour') $action['hint'] = sprintf($this->lang->execution->disabledTip->taskRecordTip, $startAction['preStageName'], $startAction['stageName']);
                $task->actions[$key] = $action;
            }
        }
    }
    return $executions;
}

/**
 * 生成dtable的行数据。
 * Generate row for dtable.
 *
 * @param  array  $executions
 * @param  array  $users
 * @param  array  $avatarList
 * @access public
 * @return array
 */
public function generateRow($executions, $users, $avatarList)
{
    $executions      = parent::generateRow($executions, $users, $avatarList);
    $pendingReviews  = $this->loadModel('approval')->getPendingReviews('review');
    $startActionInfo = array();
    foreach($executions as $execution)
    {
        foreach($execution->actions as $key => $action)
        {
            $actionName = $action['name'];
            if($actionName != 'start' && $actionName != 'close')   continue;
            /* 如果是子阶段的开始动作，则继承父阶段的开始动作。*/
            if($execution->grade > 1 && $actionName == 'start' && isset($startActionInfo[$execution->parent]))
            {
                $execution->actions[$key]         = $startActionInfo[$execution->parent];
                if(!isset($execution->action)) $execution->action = new stdclass();
                $execution->action->start         = $startActionInfo[$execution->parent];
                $startActionInfo[$execution->id]  = $startActionInfo[$execution->parent];
                continue;
            }
            if(empty($execution->action->$actionName['disabled']))
            {
                /* 阶段关闭未被禁用时，如果阶段的开始动作被禁用，阶段的关闭动作也被禁用。 */
                if($actionName == 'close' && isset($execution->action->start) && !empty($execution->action->start['disabled']))
                {
                    $execution->actions[$key]['disabled'] = true;
                    $execution->action->close['disabled'] = true;
                    $execution->actions[$key]['hint']     = '';
                    $execution->action->close['hint']     = '';
                }
                continue;
            }

            $action['disabled'] = $execution->action->$actionName['disabled'];
            $action['hint']     = $execution->action->$actionName['hint'];

            if($actionName == 'start')
            {
                $startActionInfo[$execution->id] = $action;
                $execution->action->start        = $action;
            }
            $execution->actions[$key] = $action;
        }

        if(!empty($execution->points)) $executions = $this->appendPoints($execution->points, $executions, $pendingReviews);
    }
    if($this->cookie->showTask) $executions = $this->buildTaskActions($executions);

    return $executions;
}

/*
 * 检查阶段能否开启或关闭。
 * Check ipd stage is ready to begin.
 *
 * @param  int    $executionID
 * @param  string $option
 * @access public
 * @return array
 */
/**
 * @param int $executionID
 * @param string $option
 */
public function checkStageStatus($executionID = 0, $option = 'start')
{
    $execution = $this->getById($executionID);
    $project   = $this->loadModel('project')->getById($execution->project);
    if(isset($project->model) && $project->model == 'ipd')
    {
        $executions = $this->getStatData($execution->project, 'all');
        $execution  = isset($executions[$executionID]) ? $executions[$executionID] : $execution;
        /* 子阶段的开始动作继承父阶段的开始动作。 */
        if(!isset($execution->action) && $execution->grade > 1)
        {
            $pathExecutions        = explode(',', trim($execution->path, ','));
            $firstGradeExecutionID = isset($pathExecutions[1]) ? $pathExecutions[1] : 0;
            if($firstGradeExecutionID && !empty($executions[$firstGradeExecutionID]) && !empty($executions[$firstGradeExecutionID]->action) && !empty($executions[$firstGradeExecutionID]->action->start))
            {
                if(!isset($execution->action)) $execution->action = new stdclass();
                $execution->action->start = $executions[$firstGradeExecutionID]->action->start;
                if(!empty($execution->action->start['disabled']))
                {
                    /* 如果父阶段的开始动作被禁用，子阶段的关闭动作也被禁用。 */
                    if(!isset($execution->action->close)) $execution->action->close = new stdclass();
                    $execution->action->close = $execution->action->start;
                }
            }
        }
        if(!empty($execution->action->$option['hint']))
        {
            return array('message' => $execution->action->$option['hint'], 'disabled' => true);
        }
    }
    return array('disabled' => false);
}

/**
 * 根据产品/执行等信息获取任务列表
 * Get tasks by product/execution etc.
 *
 * @param  int       $productID
 * @param  int|array $executionID
 * @param  array     $executions
 * @param  string    $browseType
 * @param  int       $queryID
 * @param  int       $moduleID
 * @param  string    $sort
 * @param  object    $pager
 * @access public
 * @return array
 */
public function getTasks($productID, $executionID, $executions, $browseType, $queryID, $moduleID, $sort, $pager = null)
{
    $tasks = parent::getTasks($productID, $executionID, $executions, $browseType, $queryID, $moduleID, $sort, $pager);
    return $this->loadModel('story')->getAffectObject($tasks, 'task');
}

/**
 * 判断操作按钮是否可以点击。
 * Judge an action is clickable or not.
 *
 * @param  object $execution
 * @param  string $action
 * @access public
 * @return bool
 */
public static function isClickable($execution, $action)
{
    if($action != 'delete') return parent::isClickable($execution, $action);

    if($action == 'delete')
    {
        if((!empty($execution->isIpdStage) || (isset($execution->projectInfo) && $execution->projectInfo->model == 'ipd')) && $execution->grade == 1)
        {
            return false;
        }
        else
        {
            return parent::isClickable($execution, $action);
        }
    }
}
