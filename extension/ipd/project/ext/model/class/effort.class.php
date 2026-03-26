<?php
class effortProject extends projectModel
{
    /**
     * @param mixed[] $projectIdList
     * @param string $time
     */
    public function getProjectsConsumed($projectIdList, $time = '')
    {
        $projects = array();

        $totalConsumeds = $this->dao->select('t2.project,ROUND(SUM(t1.consumed), 1) AS totalConsumed')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on("t1.objectID=t2.id && t1.objectType='task'")
            ->where('t2.project')->in($projectIdList)
            ->beginIF($time == 'THIS_YEAR')->andWhere('LEFT(t1.`date`, 4)')->eq(date('Y'))->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.parent')->lt(1)
            ->groupBy('t2.project')
            ->fetchAll('project');

        foreach($projectIdList as $projectID)
        {
            $project = new stdClass();
            $project->totalConsumed = isset($totalConsumeds[$projectID]->totalConsumed) ? $totalConsumeds[$projectID]->totalConsumed : 0;
            $projects[$projectID]   = $project;
        }

        return $projects;
    }
}
