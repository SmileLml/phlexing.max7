<?php
/**
 * Process a task, judge it's status.
 * Extend for php warning.
 *
 * @param  object $task
 * @param  bool   $convertParent
 * @param  array  $workingDays
 * @access public
 * @return object
 */
public function processTask($task, $convertParent = true, $workingDays = array())
{
    $task = parent::processTask($task, $convertParent, $workingDays);

    $design = $task->design > 0 ? $this->loadModel('design')->fetchById($task->design) : '';
    $task->designName = !empty($design->name) ? $design->name : '';

    if($convertParent)
    {
        $task->parent = array();
        foreach(explode(',', trim((string)$task->path, ',')) as $parentID)
        {
            if(!$parentID) continue;
            if($parentID == $task->id) continue;
            $task->parent[] = (int)$parentID;
        }
    }

    return $task;
}

public function printCell($col, $task, $users, $browseType, $branchGroups, $modulePairs = array(), $mode = 'datatable', $child = false, $showBranch = false, $privs = array())
{
    if(!empty($privs))
    {
        $canBatchEdit         = $privs['canBatchEdit'];
        $canBatchClose        = $privs['canBatchClose'];
        $canBatchCancel       = $privs['canBatchCancel'];
        $canBatchChangeModule = $privs['canBatchChangeModule'];
        $canBatchAssignTo     = $privs['canBatchAssignTo'];
    }
    else
    {
        $canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($task) ? $task : null);
        $canBatchClose        = (common::hasPriv('task', 'batchClose', !empty($task) ? $task : null) && strtolower($browseType) != 'closedBy');
        $canBatchCancel       = common::hasPriv('task', 'batchCancel', !empty($task) ? $task : null);
        $canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($task) ? $task : null);
        $canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($task) ? $task : null);
    }

    $canBatchAction = ($canBatchEdit or $canBatchClose or $canBatchCancel or $canBatchChangeModule or $canBatchAssignTo);
    $storyChanged   = (!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion and !in_array($task->status, array('cancel', 'closed')));

    $designChange = ($task->designName && $task->latestDesignVersion > $task->designVersion);
    $canView      = common::hasPriv('task', 'view');

    if($this->config->vision == 'lite')
    {
        $taskLink  = helper::createLink('task', 'view', "taskID=$task->id", '', true);
        $linkClass = 'class="iframe"';
    }
    else
    {
        $taskLink  = helper::createLink('task', 'view', "taskID=$task->id");
        $linkClass = '';
    }
    $account      = $this->app->user->account;
    $id           = $col->id;
    if($col->show)
    {
        $class = "c-{$id}";
        if($id == 'status') $class .= ' task-' . $task->status;
        if($id == 'id')     $class .= ' cell-id';
        if($id == 'name')   $class .= ' text-left';
        if($id == 'deadline' and isset($task->delay)) $class .= ' text-center delayed';
        if($id == 'assignedTo') $class .= ' has-btn text-left';
        if($id == 'lane') $class .= ' text-left';
        if(strpos('progress', $id) !== false) $class .= ' text-right';

        $title = '';
        if($id == 'name')
        {
            $title = " title='{$task->name}'";
            if(!empty($task->children)) $class .= ' has-child';
        }
        if($id == 'story') $title = " title='{$task->storyTitle}'";
        if($id == 'estimate' || $id == 'consumed' || $id == 'left')
        {
            $value = round($task->$id, 1);
            $title = " title='{$value} {$this->lang->execution->workHour}'";
        }
        if($id == 'lane') $title = " title='{$task->lane}'";

        echo "<td class='" . $class . "'" . $title . ">";
        if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('task', $task, $id);
        switch($id)
        {
        case 'id':
            if($canBatchAction)
            {
                echo html::checkbox('taskIDList', array($task->id => '')) . html::a(helper::createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));
            }
            else
            {
                printf('%03d', $task->id);
            }
            break;
        case 'pri':
            echo "<span class='label-pri label-pri-" . $task->pri . "' title='" . zget($this->lang->task->priList, $task->pri, $task->pri) . "'>";
            echo zget($this->lang->task->priList, $task->pri, $task->pri);
            echo "</span>";
            break;
        case 'name':
            if($showBranch) $showBranch = isset($this->config->execution->task->showBranch) ? $this->config->execution->task->showBranch : 1;
            if($task->parent > 0 and isset($task->parentName)) $task->name = "{$task->parentName} / {$task->name}";
            if(!empty($task->product) and isset($branchGroups[$task->product][$task->branch]) and $showBranch) echo "<span class='label label-badge label-outline'>" . $branchGroups[$task->product][$task->branch] . '</span> ';
            if($task->module and isset($modulePairs[$task->module])) echo "<span class='label label-gray label-badge'>" . $modulePairs[$task->module] . '</span> ';
            if($task->parent > 0) echo '<span class="label label-badge label-light" title="' . $this->lang->task->children . '">' . $this->lang->task->childrenAB . '</span> ';
            if(!empty($task->team)) echo '<span class="label label-badge label-light" title="' . $this->lang->task->multiple . '">' . $this->lang->task->multipleAB . '</span> ';
            echo $canView ? html::a($taskLink, $task->name, null, "$linkClass style='color: $task->color' title='$task->name'") : "<span style='color: $task->color'>$task->name</span>";
            if(!empty($task->children)) echo '<a class="task-toggle" data-id="' . $task->id . '"><i class="icon icon-angle-right"></i></a>';
            if($task->fromBug) echo html::a(helper::createLink('bug', 'view', "id=$task->fromBug"), "[BUG#$task->fromBug]", '', "class='bug'");
            break;
        case 'type':
            echo zget($this->lang->task->typeList, $task->type, $task->type);
            break;
        case 'status':
            if($storyChanged)
            {
                print("<span class='status-story status-changed' title='{$this->lang->story->changed}'>{$this->lang->story->changed}</span>");
            }
            elseif($designChange)
            {
                print("<span class='status-design status-changed' title='{$this->lang->task->designChanged}'>{$this->lang->task->designChanged}</span>");
            }
            else
            {
                $statusLabel = $this->processStatus('task', $task);
                print("<span class='status-task status-{$task->status}' title='{$statusLabel}'> " . $statusLabel . "</span>");
            }
            break;
        case 'estimate':
            echo round($task->estimate, 1) . ' ' . $this->lang->execution->workHourUnit;
            break;
        case 'consumed':
            echo round($task->consumed, 1) . ' ' . $this->lang->execution->workHourUnit;
            break;
        case 'left':
            echo round($task->left, 1)     . ' ' . $this->lang->execution->workHourUnit;
            break;
        case 'design':
            echo $task->designName ? html::a(helper::createLink('design', 'view', "id=$task->design"), $task->designName) : '';
            break;
        case 'progress':
            echo "{$task->progress}%";
            break;
        case 'deadline':
            if(!helper::isZeroDate($task->deadline)) echo '<span>' . substr($task->deadline, 5, 6). '</span>';
            break;
        case 'openedBy':
            echo zget($users, $task->openedBy);
            break;
        case 'openedDate':
            echo substr($task->openedDate, 5, 11);
            break;
        case 'estStarted':
            echo helper::isZeroDate($task->estStarted) ? '' : substr($task->estStarted, 5, 11);
            break;
        case 'realStarted':
            echo helper::isZeroDate($task->realStarted) ? '' : substr($task->realStarted, 5, 11);
            break;
        case 'assignedTo':
            $this->printAssignedHtml($task, $users);
            break;
        case 'lane':
            echo mb_substr($task->lane, 0, 8);
            break;
        case 'assignedDate':
            echo helper::isZeroDate($task->assignedDate) ? '' : substr($task->assignedDate, 5, 11);
            break;
        case 'finishedBy':
            echo zget($users, $task->finishedBy);
            break;
        case 'finishedDate':
            echo helper::isZeroDate($task->finishedDate) ? '' : substr($task->finishedDate, 5, 11);
            break;
        case 'canceledBy':
            echo zget($users, $task->canceledBy);
            break;
        case 'canceledDate':
            echo helper::isZeroDate($task->canceledDate) ? '' : substr($task->canceledDate, 5, 11);
            break;
        case 'closedBy':
            echo zget($users, $task->closedBy);
            break;
        case 'closedDate':
            echo helper::isZeroDate($task->closedDate) ? '' : substr($task->closedDate, 5, 11);
            break;
        case 'closedReason':
            echo $this->lang->task->reasonList[$task->closedReason];
            break;
        case 'story':
            if(!empty($task->storyID))
            {
                if(common::hasPriv('story', 'view'))
                {
                    echo html::a(helper::createLink('story', 'view', "storyid=$task->storyID", 'html', true), "<i class='icon icon-{$this->lang->icons['story']}'></i>", '', "class='iframe' title='{$task->storyTitle}'");
                }
                else
                {
                    echo "<i class='icon icon-{$this->lang->icons['story']}' title='{$task->storyTitle}'></i>";
                }
            }
            break;
        case 'mailto':
            $mailto = explode(',', $task->mailto);
            foreach($mailto as $account)
            {
                $account = trim($account);
                if(empty($account)) continue;
                echo zget($users, $account) . ' &nbsp;';
            }
            break;
        case 'lastEditedBy':
            echo zget($users, $task->lastEditedBy);
            break;
        case 'lastEditedDate':
            echo helper::isZeroDate($task->lastEditedDate) ? '' : substr($task->lastEditedDate, 5, 11);
            break;
        case 'actions':
            echo $this->buildOperateMenu($task, 'browse');
            break;
        }
        echo '</td>';
    }
}

/**
 * Build task browse action menu.
 *
 * @param  object $task
 * @param  string $execution
 * @access public
 * @return void
 */
public function buildOperateBrowseMenu($task, $execution = '')
{
    $menu   = '';
    $params = "taskID=$task->id";

    $storyChanged = !empty($task->storyStatus) && $task->storyStatus == 'active' && $task->latestStoryVersion > $task->storyVersion && !in_array($task->status, array('cancel', 'closed'));
    $designChange = isset($task->designName) ? $task->designName && $task->latestDesignVersion > $task->designVersion : false;

    if($storyChanged) return $this->buildMenu('task', 'confirmStoryChange', $params, $task, 'browse', '', 'hiddenwin');
    if($designChange) return $this->buildMenu('task', 'confirmDesignChange', $params, $task, 'browse', 'search', 'hiddenwin');

    $canStart          = ($task->status != 'pause' and common::hasPriv('task', 'start'));
    $canRestart        = ($task->status == 'pause' and common::hasPriv('task', 'restart'));
    $canFinish         = common::hasPriv('task', 'finish');
    $canClose          = common::hasPriv('task', 'close');
    $canRecordEstimate = common::hasPriv('task', 'recordEstimate');
    $canEdit           = common::hasPriv('task', 'edit');
    $canBatchCreate    = ($this->config->vision != 'lite' and common::hasPriv('task', 'batchCreate'));

    $this->app->loadLang('stage');
    $disabled      = '';
    $taskStartTip  = '';
    $taskFinishTip = '';
    $taskRecordTip = '';

    if(!empty($execution) and !$execution->parallel and $execution->status == 'wait' and $this->config->systemMode == 'PLM' and isset($execution->ipdStage) and !$execution->ipdStage['canStart'])
    {
        if(in_array($execution->attribute, array_keys($this->lang->stage->ipdTypeList)))
        {
            $disabled = 'disabled';
            if(!$execution->ipdStage['isFirst']) $taskStartTip  = sprintf($this->lang->execution->disabledTip->taskStartTip, $this->lang->stage->ipdTypeList[$execution->ipdStage['preAttribute']], $this->lang->stage->ipdTypeList[$execution->attribute]);
            if(!$execution->ipdStage['isFirst']) $taskFinishTip = sprintf($this->lang->execution->disabledTip->taskFinishTip, $this->lang->stage->ipdTypeList[$execution->ipdStage['preAttribute']], $this->lang->stage->ipdTypeList[$execution->attribute]);
            if(!$execution->ipdStage['isFirst']) $taskRecordTip = sprintf($this->lang->execution->disabledTip->taskRecordTip, $this->lang->stage->ipdTypeList[$execution->ipdStage['preAttribute']], $this->lang->stage->ipdTypeList[$execution->attribute]);
        }
    }

    if(isset($task->ipdStage->canStart) and empty($task->ipdStage->canStart))
    {
        $disabled = 'disabled';
        if(isset($execution->ipdStage) and !$execution->ipdStage['isFirst']) $taskStartTip  = $task->ipdStage->taskStartTip;
        if(isset($execution->ipdStage) and !$execution->ipdStage['isFirst']) $taskFinishTip = $task->ipdStage->taskFinishTip;
        if(isset($execution->ipdStage) and !$execution->ipdStage['isFirst']) $taskRecordTip = $task->ipdStage->taskRecordTip;
    }

    if($task->status != 'pause') $menu .= $this->buildMenu('task', 'start',   $params, $task, 'browse', '', '', "iframe $disabled", true, $disabled ? 'disabled data-toggle=""' : '', $taskStartTip);
    if($task->status == 'pause') $menu .= $this->buildMenu('task', 'restart', $params, $task, 'browse', '', '', 'iframe', true);

    $menu .= $this->buildMenu('task', 'finish',         $params, $task, 'browse', '', '', "iframe $disabled", true, $disabled ? 'disabled data-toggle=""' : '', $taskFinishTip);
    $menu .= $this->buildMenu('task', 'close',          $params, $task, 'browse', '', '', 'iframe', true);

    if(($canStart or $canRestart or $canFinish or $canClose) and ($canRecordEstimate or $canEdit or $canBatchCreate) and $this->app->rawModule == 'task')
    {
        $menu .= "<div class='dividing-line'></div>";
    }

    $menu .= $this->buildMenu('task', 'recordEstimate', $params, $task, 'browse', 'time', '', "iframe $disabled", true, $disabled ? 'disabled data-toggle=""' : '', $taskRecordTip);
    $menu .= $this->buildMenu('task', 'edit',           $params, $task, 'browse', 'edit', '', '', false);
    if($this->config->vision != 'lite')
    {
        $menu .= $this->buildMenu('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id&ifame=0", $task, 'browse', 'split', '', '', '', '', $this->lang->task->children);
    }

    return $menu;
}

/**
 * Gets the version record of the task.
 *
 * @param $taskID
 * @param $version
 * @access public
 * @return void
 */
public function getTaskSpec($taskID, $version)
{
    return $this->dao->select('*')->from(TABLE_TASKSPEC)
        ->where('task')->eq($taskID)
        ->andWhere('version')->eq($version)
        ->fetch();
}

/**
 * @param object $task
 * @param string $comment
 * @param object $teamData
 * @param mixed[] $drag
 */
public function activate($task, $comment, $teamData, $drag = array())
{
    $changes = parent::activate($task, $comment, $teamData, $drag);
    $now     = helper::now();

    $this->dao->update(TABLE_TASK)->set('activatedDate')->eq($now)->where('id')->eq($task->id)->exec();
    return $changes;
}

/**
 * @return mixed[]|string|false
 * @param object $task
 * @param object|null $teamData
 */
public function update($task, $teamData = null)
{
    $result = parent::update($task, $teamData);

    /* Update planDuration. */
    if($result)
    {
        $estStarted   = $this->post->estStarted;
        $deadline     = $this->post->deadline;
        $planDuration = $this->loadModel('holiday')->getActualWorkingDays($estStarted, $deadline);
        $planDuration = count($planDuration);

        $this->dao->update(TABLE_TASK)->set('planDuration')->eq($planDuration)->where('id')->eq($task->id)->exec();
    }

    return $result;
}

