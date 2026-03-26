<?php
/**
 * The model file of milestone module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     milestone
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class milestoneModel extends model
{
    /**
     * Get page nav.
     *
     * @param int $projectID
     * @param int $executionID
     * @param int $productID
     * @access public
     * @return void
     */
    public function getPageNav($projectID, $executionID, $productID)
    {
        $milestones = $this->loadModel('programplan')->getMilestones($projectID);
        if(empty($milestones)) return false;

        $current          = zget($milestones, $executionID) ? zget($milestones, $executionID) : current($milestones);
        $currentProjectID = $executionID ? $executionID : key($milestones);
        $project          = $this->loadModel('execution')->getByID($projectID);

        $selectHtml = '';
        $products         = $this->loadModel('product')->getProductPairsByProject($projectID);
        $currentProductID = $productID ? $productID : $this->product->getProductIDByProject($currentProjectID);
        if(!$currentProductID) $currentProductID = key($products);
        $productName      = $this->dao->findByID($currentProductID)->from(TABLE_PRODUCT)->fetch('name');
        $pinYin           = common::convert2Pinyin($products);

        if($project->stageBy == 'product')
        {
            $selectHtml    .= "<div class='btn-group angle-btn'>";
            $selectHtml    .= "<a data-toggle='dropdown' class='btn' title=$productName>" . $productName . " <span class='caret'></span></a>";
            $selectHtml    .= '<div id="dropMenu" class="dropdown-menu search-list load-indicator" data-ride="searchList">';
            $selectHtml    .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $selectHtml    .= '<div class="list-group"><div class="table-row"><div class="table-col col-left"><div class="list-group">';
            foreach($products as $id => $name)
            {
                $selectHtml .= html::a(helper::createLink('milestone', 'index', "project={$projectID}&execution=0&productID=$id"), "<i class='icon icon-folder-outline'></i> " . $name, '', "title='{$name}' data-key='" . zget($pinYin, $name, '') . "'");
            }
            $selectHtml .='</div></div></div></div></div></div>';
        }

        $milestones = $this->loadModel('programplan')->getMilestoneByProduct($currentProductID, $projectID);
        $current    = zget($milestones, $executionID) ? zget($milestones, $executionID) : current($milestones);
        $currentProjectID = $executionID ? $executionID : key($milestones);
        if(!$current) $current = $this->lang->noData;

        $pinYin = common::convert2Pinyin($milestones);

        $selectHtml    .= "<div class='btn-group angle-btn'>";
        $selectHtml    .= "<a data-toggle='dropdown' class='btn' title=$current>" . $current . " <span class='caret'></span></a>";
        $selectHtml    .= '<div id="dropMenu" class="dropdown-menu search-list load-indicator" data-ride="searchList">';
        $selectHtml    .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $selectHtml    .= '<div class="list-group"><div class="table-row"><div class="table-col col-left"><div class="list-group">';
        foreach($milestones as $id => $name)
        {
            $selectHtml .= html::a(helper::createLink('milestone', 'index', "project={$projectID}&execution=$id"), "<i class='icon icon-folder-outline'></i> " . $name, '', "title='{$name}' data-key='" . zget($pinYin, $name, '') . "'");
        }
        $selectHtml .='</div></div></div></div></div></div>';
        return array($selectHtml, $currentProjectID);
    }

    /**
     * Get basic info.
     *
     * @param object $execution
     * @access public
     * @return object
     */
    public function getBasicInfo($execution)
    {
        $project = $this->loadModel('execution')->getByID($execution->project);

        /* Get startedWeeks and finishedWeeks.*/
        $execution->startedWeeks  = helper::isZeroDate($execution->realBegan) || helper::isZeroDate($project->realBegan) ? 0 : ceil((strtotime($execution->realBegan) - strtotime($project->realBegan)) / 3600 / 24 / 7);
        $execution->finishedWeeks = helper::isZeroDate($execution->realEnd)   || helper::isZeroDate($project->realBegan) ? 0 : ceil((strtotime($execution->realEnd)   - strtotime($project->realBegan)) / 3600 / 24 / 7);
        $execution->offset        = helper::isZeroDate($execution->realEnd)   ? 0 : helper::diffDate($execution->end, $execution->realEnd);

        $basicInfo = new stdclass();
        $basicInfo->project = $project;
        $basicInfo->execution = $execution;

        return $basicInfo;
    }

    /**
     * Get process.
     *
     * @param int $execution
     * @access public
     * @return object
     */
    public function getProcess($execution)
    {
        $executionEnd = $this->dao->select('end')->from(TABLE_EXECUTION)->where('id')->eq($execution->id)->fetch('end');

        $process = new stdclass();

        $process->milestonePV = $this->getMilestonePV($execution->project, $execution->id, $executionEnd);
        $process->nowPV       = $this->getSoFarPV($execution->project);

        $process->milestoneEV = $this->getMilestoneEV($execution->project, $execution->id, $executionEnd);
        $process->nowEV       = $this->getSoFarEV($execution->project);

        $process->milestoneAC = $this->getMilestoneAC($execution->project, $execution->id, $executionEnd);
        $process->nowAC       = $this->getSoFarAC($execution->project);

        $process->milestoneSPI = $process->milestonePV == 0 ? 0 : round($process->milestoneEV / $process->milestonePV, 2);
        $process->nowSPI       = $process->nowPV == 0 ? 0 : round($process->nowEV / $process->nowPV, 2);

        $process->milestoneCPI = $process->milestoneAC == 0 ? 0 : round($process->milestoneEV / $process->milestoneAC, 2);
        $process->nowCPI       = $process->nowAC == 0 ? 0 : round($process->nowEV / $process->nowAC, 2);

        $process->milestoneSV  = $process->milestonePV == 0 ? 0 : round(($process->milestoneEV - $process->milestonePV) / $process->milestonePV, 2) * 100;
        $process->nowSV        = $process->nowPV == 0 ? 0 : round(($process->nowEV - $process->nowPV) / $process->nowPV, 2) * 100;

        $process->milestoneCV  = $process->milestoneAC == 0 ? 0 : round(($process->milestoneEV - $process->milestoneAC) / $process->milestoneAC, 2) * 100;
        $process->nowCV        = $process->nowAC == 0 ? 0 : round(($process->nowEV - $process->nowAC) / $process->nowAC, 2) * 100;

        $process->spiMin = '';
        $process->spiMax = '';
        $process->svMin  = '';
        $process->svMax  = '';
        $process->cpiMin = '';
        $process->cpiMax = '';
        $process->cvMin  = '';
        $process->cvMax  = '';
        $process->cvMax  = '';
        $process->cvMax  = '';
        $process->cvMax  = '';
        $process->nowSpiTip       = '';
        $process->nowCpiTip       = '';
        $process->milestoneSpiTip = '';
        $process->milestoneCpiTip = '';
        $spiTip = isset($this->config->custom->SPI) ? json_decode($this->config->custom->SPI->progressTip) : new stdclass();
        $svTip  = isset($this->config->custom->SV)  ? json_decode($this->config->custom->SV->progressTip) : new stdclass();
        $cpiTip = isset($this->config->custom->CPI) ? json_decode($this->config->custom->CPI->costTip) : new stdclass();
        $cvTip  = isset($this->config->custom->CV)  ? json_decode($this->config->custom->CV->costTip) : new stdclass();

        foreach($spiTip as $tip)
        {
            if($tip->min <= $process->milestoneSPI and $process->milestoneSPI < $tip->max) $process->milestoneSpiTip = $tip->tip;
            if($tip->min <= $process->nowSPI and $process->nowSPI < $tip->max) $process->nowSpiTip = $tip->tip;
            if($tip->range)
            {
                $process->spiMin = $tip->min;
                $process->spiMax = $tip->max;
            }
        }

        foreach($svTip as $tip)
        {
            if($tip->range)
            {
                $process->svMin = $tip->min;
                $process->svMax = $tip->max;
            }
        }

        foreach($cpiTip as $tip)
        {
            if($tip->min <= $process->milestoneCPI and $process->milestoneCPI < $tip->max) $process->milestoneCpiTip = $tip->tip;
            if($tip->min <= $process->nowCPI and $process->nowCPI < $tip->max) $process->nowCpiTip = $tip->tip;
            if($tip->range)
            {
                $process->cpiMin = $tip->min;
                $process->cpiMax = $tip->max;
            }
        }

        foreach($cvTip as $tip)
        {
            if($tip->range)
            {
                $process->cvMin = $tip->min;
                $process->cvMax = $tip->max;
            }
        }

        return $process;
    }

    /**
     * Get charts.
     *
     * @param object $execution
     * @access public
     * @return array
     */
    public function getCharts($execution)
    {
        $this->loadModel('weekly');
        $charts  = array();
        $project = $this->loadModel('project')->getByID($execution->project);

        $projectWeekly = $this->dao->select('weekStart,pv,ev,ac')->from(TABLE_WEEKLYREPORT)
            ->where('project')->eq($execution->project)
            ->andWhere('weekStart')->ge($project->begin)
            ->andWhere('weekStart')->le($execution->end)
            ->orderBy('weekStart_asc')
            ->fetchAll('weekStart');

        $charts['PV'] = '[';
        $charts['EV'] = '[';
        $charts['AC'] = '[';
        foreach($projectWeekly as $weekStart => $data)
        {
            $charts['labels'][] = $weekStart;
            $charts['PV']      .= $data->pv . ',';
            $charts['EV']      .= $data->ev . ',';
            $charts['AC']      .= $data->ac . ',';
        }

        $charts['PV'] .= ']';
        $charts['EV'] .= ']';
        $charts['AC'] .= ']';

        return $charts;
    }

    /**
     * Get PV of milestone.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $limitDate
     * @access public
     * @return string
     */
    public function getMilestonePV($projectID, $executionID, $limitDate = '')
    {
        $executionIdList = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('end')->le($limitDate)
            ->orderBy('id')
            ->fetchPairs('id', 'id');

        $PV = $this->dao->select('SUM(estimate) as estimate')->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('isParent')->eq('0')
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('cancel')
            ->fetch('estimate');

        return sprintf("%.2f", $PV);
    }

    /**
     * Get PV so far.
     *
     * @param int $projectID
     * @access public
     * @return string
     */
    public function getSoFarPV($projectID)
    {
        $executions = $this->dao->select('id,begin,end')->from(TABLE_EXECUTION)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id')
            ->fetchAll('id');

        $this->loadModel('holiday');
        $today = helper::today();
        $PV    = 0;
        $stmt  = $this->dao->select('id,execution,estStarted,deadline,estimate')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('isParent')->eq('0')
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('cancel')
            ->query();
        while($task = $stmt->fetch())
        {
            $execution = $executions[$task->execution];
            if(helper::isZeroDate($task->estStarted)) $task->estStarted = $execution->begin;
            if(helper::isZeroDate($task->deadline))   $task->deadline   = $execution->end;

            if($task->deadline <= $today)
            {
                $PV += $task->estimate;
            }
            elseif($task->estStarted < $today and $task->deadline > $today)
            {
                $fullDays   = $this->holiday->getActualWorkingDays($task->estStarted, $task->deadline);
                $passedDays = $this->holiday->getActualWorkingDays($task->estStarted, $today);
                if(count($fullDays) > 0) $PV += round(count($passedDays) / count($fullDays) * $task->estimate, 2);
            }
        }

        return sprintf("%.2f", $PV);
    }

    /**
     * Get EV of milestone.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $limitDate
     * @access public
     * @return string
     */
    public function getMilestoneEV($projectID, $executionID, $limitDate = '')
    {
        $executionIdList = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('end')->le($limitDate)
            ->orderBy('id')
            ->fetchPairs('id', 'id');

        $stmt = $this->dao->select('id,execution,status,closedReason,estimate,consumed,`left`')->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('isParent')->eq('0')
            ->andWhere('deleted')->eq(0)
            ->andWhere('consumed')->gt(0)
            ->andWhere('status')->ne('cancel')
            ->query();

        $EV = 0;
        while($task = $stmt->fetch())
        {
            if($task->status == 'done' or $task->closedReason == 'done')
            {
                $EV += $task->estimate;
            }
            else
            {
                $task->progress = $task->consumed / ($task->consumed + $task->left);
                $EV += round($task->estimate * $task->progress, 2);
            }
        }

        return sprintf("%.2f", $EV);
    }

    /**
     * Get EV so far.
     *
     * @param int $projectID
     * @access public
     * @return string
     */
    public function getSoFarEV($projectID)
    {
        $executions = $this->dao->select('id,begin,end')->from(TABLE_EXECUTION)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id')
            ->fetchAll('id');

        $today = helper::today();
        $EV    = 0;
        $stmt  = $this->dao->select('id,execution,estStarted,deadline,estimate,consumed,`left`,status,closedReason')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('isParent')->eq('0')
            ->andWhere('deleted')->eq(0)
            ->andWhere('consumed')->gt(0)
            ->andWhere('status')->ne('cancel')
            ->query();
        while($task = $stmt->fetch())
        {
            $execution = $executions[$task->execution];
            if(helper::isZeroDate($task->estStarted)) $task->estStarted = $execution->begin;
            if(helper::isZeroDate($task->deadline))   $task->deadline   = $execution->end;

            if($task->deadline <= $today or ($task->estStarted < $today and $task->deadline > $today))
            {
                if($task->status == 'done' or $task->closedReason == 'done')
                {
                    $EV += $task->estimate;
                }
                else
                {
                    $task->progress = $task->consumed / ($task->consumed + $task->left);
                    $EV += round($task->estimate * $task->progress, 2);
                }
            }
        }

        return sprintf("%.2f", $EV);
    }

    /**
     * Get AC of milestone.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $limitDate
     * @access public
     * @return float
     */
    public function getMilestoneAC($projectID, $executionID, $limitDate = '')
    {
        $executionIdList = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('end')->le($limitDate)
            ->orderBy('id')
            ->fetchPairs('id', 'id');
        $consumed = $this->dao->select('sum(consumed) as consumed')
            ->from(TABLE_EFFORT)
            ->where('execution')->in($executionIdList)
            ->fetch('consumed');
        if(!$consumed) $consumed = 0;

        return round($consumed, 2);
    }

    /**
     * Get AC so far.
     *
     * @param int $projectID
     * @access public
     * @return float
     */
    public function getSoFarAC($projectID)
    {
        $executionIdList = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id')
            ->fetchPairs('id', 'id');

        $today = helper::today();
        $consumed = $this->dao->select('sum(consumed) as consumed')->from(TABLE_EFFORT)
            ->where('execution')->in($executionIdList)
            ->andWhere('date')->le($today . ' 23:59:59')
            ->fetch('consumed');
        if(!$consumed) $consumed = 0;

        return round($consumed, 2);
    }

    /**
     * Get product quality.
     *
     * @param object $execution
     * @access public
     * @return array
     */
    public function getProductQuality($execution)
    {
        $productID = $this->loadModel('product')->getProductIDByProject($execution->id);
        $stages    = $this->loadModel('programplan')->getPairs($execution->project, $productID);
        $reviews   = $this->loadModel('review')->getPairs($execution->project, $productID);
        unset($stages[0]);

        foreach($reviews as $reviewID => $reviewName)
        {
            $reviewEstimate[$reviewID] = 0;

            foreach($stages as $stageID => $stageName)
            {
                $productQuality['stages'][$stageID]['total'] = 0;
                $bugs = $this->dao->select('COUNT(1) AS bugs')->from(TABLE_BUG)
                    ->where('execution')->eq($stageID)
                    ->andWhere('identify')->eq($reviewID)
                    ->andWhere('resolution')->notin('bydesign,duplicate,notrepro,willnotfix')
                    ->andWhere('deleted')->eq(0)
                    ->fetch('bugs');

                $issues = $this->dao->select('COUNT(1) AS issues')->from(TABLE_REVIEWISSUE)
                    ->where('injection')->eq($stageID)
                    ->andWhere('review')->eq($reviewID)
                    ->andWhere('resolution')->notin('bydesign,duplicate,notrepro,willnotfix')
                    ->andWhere('deleted')->eq(0)
                    ->fetch('issues');

                $reviewBugNum[$reviewID]                                 = isset($reviewBugNum[$reviewID]) ? ($bugs + $reviewBugNum[$reviewID]) : $bugs;
                $productQuality['stages'][$stageID]['name']              = $stageName;
                $productQuality['stages'][$stageID][$reviewID]['counts'] = ($bugs + $issues)  == 0 ? 0 : (int)($bugs + $issues);
                $productQuality['stages'][$stageID]['totalBugNum']       = isset($productQuality['stages'][$stageID]['totalBugNum']) ? $productQuality['stages'][$stageID]['totalBugNum'] + $productQuality['stages'][$stageID][$reviewID]['counts'] : 0;
            }

            /* Build review information of name. */
            $productQuality['reviews'][$reviewID]['name'] = $reviewName;

            /* Calculate the total story estimate of review. */
            $stories = $this->dao->select('data')->from(TABLE_REVIEW)->alias('t1')
                ->leftJoin(TABLE_OBJECT)->alias('t2')->on('t1.object = t2.id')
                ->where('t1.id')->eq($reviewID)
                ->andWhere('t2.deleted')->eq(0)
                ->fetch();
            $stories = json_decode($stories->data);
            $stories = isset($stories->story) ? $stories->story : array();
            $productQuality['reviews'][$reviewID]['reviewEstimate'] = 0;
            if(!empty($stories))
            {
                foreach($stories as $storyID => $story)
                {
                    if(empty($story)) continue;
                    if($story->estimate) $productQuality['reviews'][$reviewID]['reviewEstimate'] += $story->estimate;
                }
            }

            /* Calculate the identifyRate of review. */
            $projectBugNum = $this->dao->select('COUNT(1) AS bugNum')->from(TABLE_BUG)
                ->where('project')->eq($execution->project)
                ->andWhere('deleted')->eq(0)
                ->fetch('bugNum');
            if($projectBugNum < 1 or $reviewBugNum[$reviewID] < 1)
            {
                $productQuality['reviews'][$reviewID]['identifyRate'] = '0%';
            }
            else
            {
                $productQuality['reviews'][$reviewID]['identifyRate'] = round(($reviewBugNum[$reviewID] / $projectBugNum * 100), 2) . '%';
            }

        }

        if(isset($productQuality))
        {
            /* Calculate the total story estimate, bugs, identifyRate. */
            foreach($productQuality['reviews'] as $reviewID => $review)
            {
                $productQuality['totalEstimate'] = isset($productQuality['totalEstimate']) ? $productQuality['totalEstimate'] + $review['reviewEstimate'] : $review['reviewEstimate'];
            }
            foreach($productQuality['stages'] as $stage)
            {
                $totalBugNum = isset($totalBugNum) ? $totalBugNum + $stage['totalBugNum'] : $stage['totalBugNum'];
            }
            if($totalBugNum < 1 or $projectBugNum < 1)
            {
                $productQuality['totalIdentifyRate'] = '0%';
            }
            else
            {
                $productQuality['totalIdentifyRate'] = round(($totalBugNum / $projectBugNum * 100), 2) . '%';
            }

            /* Calculate the injection of stages. */
            foreach($stages as $stageID => $stage)
            {
                if($productQuality['totalEstimate']  < 1 or $productQuality['stages'][$stageID]['totalBugNum'] < 1)
                {
                    $productQuality['stages'][$stageID]['injection'] = '0%';
                }
                else
                {
                    $productQuality['stages'][$stageID]['injection'] = round(($productQuality['stages'][$stageID]['totalBugNum'] / $productQuality['totalEstimate'] * 100), 2) . '%';
                }
            }

            if(isset($productQuality['stages']))
            {
                foreach($productQuality['stages'] as $stageID => $stages)
                {
                    $total = 0;
                    foreach($stages as $reviewID => $stage) $total += (int) zget($stage, 'counts', 0);
                    $productQuality['stages'][$stageID]['total'] = $total;
                }
            }
        }
        else
        {
            $productQuality = array();
        }

        return $productQuality;
    }

    /**
     * Get workhours.
     *
     * @param object $execution
     * @access public
     * @return array
     */
    public function getWorkhours($execution)
    {
        $productID = $this->loadModel('product')->getProductIDByProject($execution->id);
        $stages    = $this->loadModel('programplan')->getPairs($execution->project, $productID, 'leaf');
        unset($stages[0]);

        $dev    = 0;
        $to     = 0;
        $review = 0;
        $qa     = 0;
        foreach($stages as $stageID => $stageName)
        {
            $workhours[$stageID]['name']     = $stageName;
            $workhours[$stageID]['dev']      = $this->getWorkhourByType($stageID, 'devel');
            $workhours[$stageID]['to']       = $this->getTo($stageID);
            $workhours[$stageID]['review']   = $this->getReviewHours($stageID, $execution->id);
            $workhours[$stageID]['qa']       = $this->getWorkhourByType($stageID, 'test');;
            $workhours[$stageID]['count']    = $workhours[$stageID]['dev'] + $workhours[$stageID]['to'] + $workhours[$stageID]['review'] + $workhours[$stageID]['qa'];
            $workhours[$stageID]['qaToDev']  = ($workhours[$stageID]['dev'] + $workhours[$stageID]['to']) == 0 ? 0 : round($workhours[$stageID]['qa'] / ($workhours[$stageID]['dev'] + $workhours[$stageID]['to']), 2);

            $dev    += $workhours[$stageID]['dev'];
            $to     += $workhours[$stageID]['to'];
            $review += $workhours[$stageID]['review'];
            $qa     += $workhours[$stageID]['qa'];
        }

        $workhours['count']['dev']    = $dev;
        $workhours['count']['to']     = $to;
        $workhours['count']['review'] = $review;
        $workhours['count']['qa']     = $qa;
        $workhours['count']['total']  = $dev + $to + $review + $qa;

        return $workhours;
    }

    /**
     * Get workhour by type.
     *
     * @param int $stageID
     * @param string $type
     * @access public
     * @return float
     */
    public function getWorkhourByType($stageID, $type)
    {
        $consumed = $this->dao->select('sum(consumed) as consumed')->from(TABLE_TASK)->where('execution')->eq($stageID)->andWhere('type')->eq($type)->fetch('consumed');
        $consumed = $consumed ? $consumed : 0;
        return round($consumed, 2);
    }

    /**
     * Get review hours.
     *
     * @param int $stageID
     * @param int $executionID
     * @access public
     * @return float
     */
    public function getReviewHours($stageID, $executionID = 0)
    {
        $productID = $this->loadModel('product')->getProductIDByProject($executionID);
        $stage     = $this->loadModel('programplan')->getByID($stageID);
        $consumed  = 0;
        $consumed += $this->getWorkhourByType($stageID, 'review');
        $attribute = isset($this->config->milestone->{$stage->attribute}) ? $this->config->milestone->{$stage->attribute} : '';

        $reviewConsumed = $this->dao->select('sum(t1.consumed) as consumed')->from(TABLE_REVIEWRESULT)->alias('t1')
            ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.review=t2.id')
            ->leftJoin(TABLE_OBJECT)->alias('t3')->on('t2.object=t3.id')
            ->where('t3.category')->in($attribute)
            ->andWhere('t3.product')->eq($productID)
            ->fetch('consumed');
        $reviewConsumed = $reviewConsumed ? $reviewConsumed : 0;

        $consumed += $reviewConsumed;
        return round($consumed, 2);
    }

    /**
     * Get to.
     *
     * @param int $stageID
     * @access public
     * @return float
     */
    public function getTo($stageID)
    {
        $tasks = $this->dao->select('id, activatedDate')->from(TABLE_TASK)->where('execution')->eq($stageID)->andWhere('activatedDate IS NOT NULL')->fetchPairs();
        $to = 0;
        foreach($tasks as $taskID => $activatedDate)
        {
            $consumed = $this->dao->select('sum(consumed) as consumed')->from(TABLE_EFFORT)
                ->where('objectType')->eq('task')
                ->andWhere('objectID')->eq($taskID)
                ->beginIF(strpos($activatedDate, '0000-00-00') === false)->andWhere('date')->ge($activatedDate)->fi()
                ->fetch('consumed');
            $to += floatval($consumed);
        }

        return round($to, 2);
    }

    /**
     * Get risks of project.
     *
     * @param int $projectID
     * @access public
     * @return array
     */
    public function getProjectRisk($projectID)
    {
        return $this->dao->select('*, rate as riskRate')->from(TABLE_RISK)
            ->where('status')->eq('active')
            ->andWhere('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('riskRate_desc')
            ->limit(5)
            ->fetchAll('id', false);
    }

    /**
     * Get stage demand.
     *
     * @param int $projectID
     * @param int $productID
     * @param array $stageList
     * @access public
     * @return array
     */
    public function getStageDemand($projectID, $productID, $stageList = array())
    {
        $productList = array();
        foreach($stageList as $stageID => $name) $productList[$stageID] = $productID;
        $stages = $this->dao->select('begin,end,id')->from(TABLE_EXECUTION)->where('id')->in(array_keys($stageList))->fetchAll('id');

        $originStory = array();
        $afterStory  = array();
        $changeStory = array();

        foreach($stages as $id => $stage)
        {
            $productID = $productList[$id];
            if($productID === 0) continue;

            $originStory[$id] = $this->dao->select('count(id) as total')->from(TABLE_STORY)
                ->where('product')->eq($productID)
                ->andWhere('type')->eq('requirement')
                ->andWhere('openedDate')->between($stage->begin, $stage->end)
                ->fetch('total');

            $afterStory[$id] = $this->dao->select('count(id) as total')->from(TABLE_STORY)
                ->where('product')->eq($productID)
                ->andWhere('type')->eq('requirement')
                ->andWhere('openedDate')->between($stage->begin, $stage->end)
                ->andWhere('deleted')->eq(0)
                ->fetch('total');

            $sql  = 'select count(id) as total from ' . TABLE_STORY;
            $sql .= " where (product = $productID and type = 'requirement' and openedDate between '$stage->begin' and '$stage->end' and deleted = '1')";
            $sql .= " or (product = $productID and type = 'requirement' and openedDate between '$stage->begin' and '$stage->end' and version > 1)";
            $changeStory[$id] = $this->dao->query($sql)->fetch();
        }

        $stageInfo = array('origin' => array(), 'after' => array(), 'change' => array());
        $beginID   = 0;

        foreach($stageList as $key => $stage)
        {
            $beginID === 0 ? $stageInfo['origin'][$key] = $originStory[$key] :  $stageInfo['origin'][$key] = $afterStory[$beginID];
            $stageInfo['after'][$key]  = $afterStory[$key];
            $stageInfo['change'][$key] = $changeStory[$key]->total;
            $beginID = $key;
        }

        return $stageInfo;
    }

    /**
     * Get measures.
     *
     * @param int $projectID
     * @param array $executions
     * @access public
     * @return array
     */
    public function getMeasures($projectID, $executions)
    {
        if(empty($executions)) return array();
        return $this->dao->select('id,contents')->from(TABLE_SOLUTIONS)
            ->where('project')->eq($projectID)
            ->andWhere('execution')->in($executions)
            ->andWhere('type')->eq('measures')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id', 'contents');
    }

    /**
     * Ajax add measures.
     *
     * @access public
     * @return void
     */
    public function ajaxAddMeasures()
    {
        $data = fixer::input('post')->get();
        $this->dao->update(TABLE_SOLUTIONS)
            ->set('deleted')->eq(1)
            ->where('project')->eq($data->projectID)
            ->andWhere('execution')->eq($data->executionID)
            ->andWhere('type')->eq('measures')
            ->exec();

        foreach($data->measures as $item)
        {
            $item = trim($item);
            if(empty($item)) continue;

            $addData = new stdClass();
            $addData->project   = $data->projectID;
            $addData->execution = $data->executionID;
            $addData->contents  = $item;
            $addData->type      = 'measures';
            $addData->addedBy   = $this->app->user->account;
            $addData->addedDate = helper::today();
            $addData->support   = '';
            $addData->measures  = $item;

            $this->dao->insert(TABLE_SOLUTIONS)->data($addData)->autoCheck()->exec();
            if(dao::isError()) return print(js::error(dao::getError()));
        }
    }

    public function saveOtherProblem()
    {
        $data = fixer::input('post')->get();

        $this->dao->update(TABLE_SOLUTIONS)
            ->set('deleted')->eq(1)
            ->where('project')->eq($data->projectID)
            ->andWhere('execution')->eq($data->executionID)
            ->andWhere('type')->eq('otherproblem')
            ->exec();

        foreach($data->contents as $key => $contents)
        {
            $contents = trim($contents);
            if(empty($contents)) continue;

            $addData = new stdClass();
            $addData->project   = $data->projectID;
            $addData->execution = $data->executionID;
            $addData->contents  = $contents;
            $addData->support   = $data->support[$key];
            $addData->measures  = $data->measures[$key];
            $addData->type      = 'otherproblem';
            $addData->addedBy   = $this->app->user->account;
            $addData->addedDate = helper::today();

            $this->dao->insert(TABLE_SOLUTIONS)->data($addData)->autoCheck()->exec();
            if(dao::isError()) return print(js::error(dao::getError()));
        }
    }

    public function otherProblemsList($projectID, $executions)
    {
        $list = $this->dao->select('*')
            ->from(TABLE_SOLUTIONS)
            ->where('project')->eq($projectID)
            ->andWhere('execution')->in($executions)
            ->andWhere('type')->eq('otherproblem')
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id', false);

        return $list;
   }

    public function getNextMilestone($execution, $stageList)
    {
        $nextID = $this->dao->select('min(id) as id')->from(TABLE_PROJECT)
            ->where('begin')->gt($execution->begin)
            ->andWhere('project')->eq($execution->project)
            ->andWhere('milestone')->eq(1)
            ->fetch('id');

        $stageID = array_keys($stageList);
        $nextID  = in_array($nextID, $stageID) ? $nextID : 0;

        $totalDays = $this->dao->select('sum(days) as days')->from(TABLE_PROJECT)
            ->where('id')->in($stageID)
            ->andWhere('project')->eq($execution->project)
            ->andWhere('deleted')->eq(0)
            ->fetch('days');

        $totalHours = $this->dao->select('sum(days * hours) as totalHours')->from(TABLE_TEAM)
            ->where('root')->in($stageID)
            ->fetch('totalHours');

        $nextHours = 0;
        $nextDays  = 0;
        if($nextID)
        {
            $nextDays = $this->dao->select('days')->from(TABLE_PROJECT)
                ->where('id')->eq($nextID)
                ->andWhere('project')->eq($execution->project)
                ->andWhere('deleted')->eq(0)
                ->fetch('days');

            $nextHours = $this->dao->select('sum(days * hours) as totalHours')->from(TABLE_TEAM)
                ->where('root')->eq($nextID)
                ->fetch('totalHours');
        }

        $result             = new stdClass();
        $result->nextDays   = empty($nextDays)   ? 0 : $nextDays;
        $result->nextHours  = empty($nextHours)  ? 0 : $nextHours;
        $result->totalDays  = empty($totalDays)  ? 0 : $totalDays;
        $result->totalHours = empty($totalHours) ? 0 : $totalHours;
        return $result;
    }

    /**
     * Get stages of milestone.
     *
     * @param object $execution
     * @access public
     * @return array
     */
    public function getStagesOfMilestone($execution)
    {
        return $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('path')->like($execution->path . '%')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id');
    }

    /**
     * Ajax save estimate.
     *
     * @param int $taskID
     * @param float $estimate
     * @access public
     * @return void
     */
    public function ajaxSaveEstimate($taskID, $estimate)
    {
        $this->dao->update(TABLE_PROJECT)
            ->set('estimate')
            ->eq($estimate)
            ->where('id')->eq($taskID)
            ->exec();

        if(dao::isError())
        {
            echo js::error(dao::getError());
        }
    }
}
