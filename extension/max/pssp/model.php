<?php
/**
 * The model file of pssp module of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     pssp
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
class psspModel extends model
{
    /**
     * Get process data.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $model
     * @access public
     * @return void
     */
    public function getProcessData($projectID = 0, $executionID = 0, $model = '')
    {
        $processList  = $this->dao->select('*')->from(TABLE_PROCESS)->where('deleted')->eq(0)->andWhere('model')->eq($model)->orderBy('order_asc')->fetchAll('id');
        $activityList = $this->dao->select('*')->from(TABLE_ACTIVITY)->where('deleted')->eq(0)->orderBy('order_asc')->fetchAll('id');
        $outputList   = $this->dao->select('*')->from(TABLE_ZOUTPUT)->where('deleted')->eq(0)->orderBy('order_asc')->fetchAll('id');

        if($projectID)
        {
            $activityList = $this->processActivityList($activityList, $projectID, $executionID);
            $outputList   = $this->processOutputList($outputList, $projectID, $executionID);
        }

        foreach($activityList as $activity) $activity->outputList = array();
        foreach($processList as $process)   $process->activityList = array();
        foreach($processList as $process)   $process->outputNum = 0;

        foreach($outputList as $output)
        {
            if(!isset($activityList[$output->activity])) continue;
            $activityList[$output->activity]->outputList[] = $output;
        }

        foreach($activityList as $activity)
        {
            if(!isset($processList[$activity->process])) continue;
            $processList[$activity->process]->activityList[] = $activity;
            $processList[$activity->process]->outputNum += empty($activity->outputList) ? 1 : count($activity->outputList);
        }

        $groupedProcessList = array();
        foreach($processList as $process)
        {
             if(!isset($groupedProcessList[$process->type]['rows']))  $groupedProcessList[$process->type]['rows'] = 0;
             $groupedProcessList[$process->type]['processList'][] = $process;
             $groupedProcessList[$process->type]['rows'] += $process->outputNum ? $process->outputNum : 1;
        }

        return $groupedProcessList;
    }

    /**
     * Get processes.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function getProcesses($projectID = 0,$model = '')
    {
        $activityProcess = $this->dao->select('process')->from(TABLE_PROGRAMACTIVITY)
            ->where('result')->eq('yes')
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchPairs();

        $outputProcess   = $this->dao->select('process')->from(TABLE_PROGRAMOUTPUT)
            ->where('result')->eq('yes')
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchPairs();

        $processIdList = $activityProcess + $outputProcess;

        return $this->dao->select('id, name')
            ->from(TABLE_PROCESS)
            ->where('id')->in($processIdList)
            ->beginIF(!empty($model))->andWhere('model')->eq($model)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Process activity list.
     *
     * @param  int    $activityList
     * @param  int    $project
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function processActivityList($activityList, $project, $executionID = 0)
    {
        $projectActivities = $this->dao->select('*')
            ->from(TABLE_PROGRAMACTIVITY)
            ->where('project')->eq($project)
            ->beginIF($executionID != 0)->andWhere('execution')->eq($executionID)->fi()
            ->fetchAll('activity');

        foreach($activityList as $activity)
        {
            if(isset($projectActivities[$activity->id]))
            {
                $activity->result    = $projectActivities[$activity->id]->result;
                $activity->reason    = $projectActivities[$activity->id]->reason;
                $activity->execution = $projectActivities[$activity->id]->execution;
            }
        }
        return $activityList;
    }

    /**
     * Process output list.
     *
     * @param  int    $outputList
     * @param  int    $project
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function processOutputList($outputList, $project, $executionID = 0)
    {
        $projectOutputs = $this->dao->select('*')
            ->from(TABLE_PROGRAMOUTPUT)
            ->where('project')->eq($project)
            ->beginIF($executionID != 0)->andWhere('execution')->eq($executionID)->fi()
            ->fetchAll('output');

        foreach($outputList as $output)
        {
            if(isset($projectOutputs[$output->id]))
            {
                $output->result    = $projectOutputs[$output->id]->result;
                $output->reason    = $projectOutputs[$output->id]->reason;
                $output->execution = $projectOutputs[$output->id]->execution;
            }
        }
        return $outputList;
    }

    /**
     * Get activity pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function getActivityPairs($projectID = 0)
    {
        $object = $this->loadModel('project')->fetchByID($projectID);

        $objectType = '';
        if($object) $objectType = $object->type == 'project' ? 'project' : 'execution';
        return $this->dao->select('t1.id, t1.name')->from(TABLE_ACTIVITY)->alias('t1')
            ->leftJoin(TABLE_PROGRAMACTIVITY)->alias('t2')->on('t1.id=t2.activity')
            ->where('t2.result')->eq('yes')
            ->beginIF($projectID)->andWhere("t2.{$objectType}")->eq($projectID)->fi()
            ->fetchPairs();
    }

    /**
     * Get output pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function getOutputPairs($projectID = 0)
    {
        $object = $this->loadModel('project')->fetchByID($projectID);

        $objectType = '';
        if($object) $objectType = $object->type == 'project' ? 'project' : 'execution';
        return $this->dao->select('t1.id, t1.name')->from(TABLE_ZOUTPUT)->alias('t1')
            ->leftJoin(TABLE_PROGRAMOUTPUT)->alias('t2')->on('t1.id=t2.output')
            ->where('t2.result')->eq('yes')
            ->beginIF($projectID)->andWhere("t2.{$objectType}")->eq($projectID)->fi()
            ->fetchPairs();
    }
}
