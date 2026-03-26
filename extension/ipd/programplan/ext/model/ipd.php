<?php
/**
 * 判断IPD项目阶段是否允许并行。
 * Determine whether parallelism is allowed in the IPD project phase.
 *
 * @param  int    $projectID
 * @access public
 * @return int
 */
public function checkParallel($projectID)
{
    $started = $this->dao->select('count(id) as count')->from(TABLE_PROJECT)
        ->where('project')->eq($projectID)
        ->andWhere('status')->ne('wait')
        ->fetch('count');
    return $started > 0;
}

/**
 * 检查单个编辑IPD阶段的开始时间和结束时间是否符合规则。
 * Check whether the start and end time of a single edit IPD phase is in accordance with the rules.
 *
 * @param  object $currentStage
 * @access public
 * @return bool
 */
public function checkIpdStageDate($currentStage)
{
    if($currentStage->grade > 1) return true;

    $stages = $this->loadModel('execution')->getList($currentStage->project);
    if($stages) $stages = array_reverse($stages, true);

    $preDate   = $nextDate = '';
    $isCurrent = false;
    foreach($stages as $stage)
    {
        if($stage->grade > 1) continue;
        if(!$stage->enabled) continue;//阶段是否启用
        if($isCurrent)
        {
            $nextDate = $stage->begin;
            break;
        }

        if($stage->attribute == $currentStage->attribute)
        {
            $isCurrent = true;
            continue;
        }

        $preDate = $stage->end;
    }

    if($preDate and $preDate > $_POST['begin']) dao::$errors['begin'] = $this->lang->programplan->error->outOfDate . ': ' . $preDate;
    if($nextDate and $nextDate < $_POST['end']) dao::$errors['end']   = $this->lang->programplan->error->lessOfDate . ': ' . $nextDate;

    return !dao::isError();
}

/**
 * 根据action获取excution。
 * Get new parent and action.
 *
 * @param  array     $statusCount
 * @param  object    $parent
 * @param  int       $startTasks
 * @param  string    $action
 * @param  object    $project
 * @access protected
 * @return array
 */
protected function getNewParentAndAction($statusCount, $parent, $startTasks, $action, $project)
{
    $result       = parent::getNewParentAndAction($statusCount, $parent, $startTasks, $action, $project);
    $newParent    = $result['newParent'];
    $parentAction = $result['parentAction'];

    /* 阶段串行IPD项目的一级阶段的子阶段全部关闭时，需要检查评审点是否全部通过，只有全部通过才可以自动关闭。 */
    if($parentAction == 'closedbychild' && !empty($project->model) && $project->model == 'ipd' && !$project->parallel && $parent->grade =='1')
    {
        if(!isset($parent->projectModel)) $parent->projectModel = $project->model;
        $points       = $this->loadModel('review')->getPointsByProjectID($project->id);
        list($parent) = $this->loadModel('execution')->buildExecutionTree(array($parent), $points);

        /* 当前阶段评审点都通过，当前阶段才可以关闭。 */
        if(!empty($parent->points))
        {
            foreach($parent->points as $point)
            {
                if($point->result == 'pass') continue;
                if($parent->status == 'doing') return array('newParent' => null, 'parentAction' => '');

                $status       = $statusCount == 1 ? 'wait' : 'doing';
                $newParent    = $this->execution->buildExecutionByStatus($status);
                $parentAction = $parent->status == 'wait' ? 'startbychildstart' : 'startbychild' . $action;
                break;
            }
        }
    }
    return array('newParent' => $newParent, 'parentAction' => $parentAction);
}
