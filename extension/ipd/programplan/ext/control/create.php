<?php
helper::importControl('programplan');
class myProgramplan extends programplan
{
    /**
     * Create a project plan.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $planID
     * @param  string $executionType
     * @param  string $from
     * @param  int    $syncData
     * @access public
     * @return void
     */
    public function create($projectID = 0, $productID = 0, $planID = 0, $executionType = 'stage', $from = '', $syncData = 0)
    {
        $this->loadModel('review');
        $paraller = isset($_POST['parallel']) ? reset($_POST['parallel']) : true;
        $project  = $this->loadModel('project')->getById($projectID);

        /* 如果阶段不允许并行，则检查计划起止时间。*/
        if($project->model == 'ipd' && !$paraller && !$planID)
        {
            $begin   = $_POST['begin'];
            $end     = $_POST['end'];
            $enabled = $_POST['enabled'];

            /* 过滤掉已经禁用的阶段。导致key不连续 如array('1' => '2024-01-02', '3' => '2024-01-01')。*/
            foreach($begin as $index => $value)
            {
                if(!isset($enabled[$index]) || $enabled[$index] == 'off') unset($begin[$index], $end[$index]);
            }

            /* Only get level 0 index. */
            $level0List = array();
            foreach($_POST['level'] as $index => $level)
            {
                if(!isset($begin[$index]) || $level != 0) continue;
                $level0List[] = $index;
            }

            $preDate  = '';
            $nextDate = '';
            foreach($level0List as $index => $value)
            {
                $currentBegin = $begin[$value]; // 当前开始时间
                $currentEnd   = $end[$value];   // 当前结束时间
                $preKey       = isset($level0List[$index - 1]) ? $level0List[$index - 1] : ''; // 前一个阶段的key
                $nextKey      = isset($level0List[$index + 1]) ? $level0List[$index + 1] : ''; // 下一个阶段的key
                $preDate      = isset($end[$preKey])     ? $end[$preKey]     : ''; // 前一个阶段的结束时间
                $nextDate     = isset($begin[$nextKey])  ? $begin[$nextKey]  : ''; // 下一个阶段的开始时间

                if($preDate == '') continue;

                if($currentBegin < $preDate) dao::$errors["begin[{$value}]"] = $this->lang->programplan->error->outOfDate;
                if($nextDate and $currentEnd > $nextDate) dao::$errors["end[{$value}]"] = $this->lang->programplan->error->lessOfDate;
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $this->view->enabledPoints  = $this->programplan->getEnabledPoints($projectID);
        $this->view->reviewedPoints = $this->review->getPointsByProjectID($projectID, 'category');
        $this->view->canParallel    = $this->programplan->checkParallel($projectID);

        return parent::create($projectID, $productID, $planID, $executionType, $from, $syncData);
    }
}
