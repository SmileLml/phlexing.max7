<?php
class zentaobizProductplan extends productplanModel
{
    /**
     * Get plans for charter.
     *
     * @param  array  $productIdList
     * @param  string $append
     * @param  string $branchID
     * @access public
     * @return array
     */
    public function getPlansForCharter($productIdList = array(), $append = '', $branchID = '')
    {
        $noclosedPlans = $this->getGroupByProduct($productIdList, 'noclosed');
        $plans         = array();

        foreach($noclosedPlans as $productID => $branchGroups)
        {
            $plans[$productID] = array();
            foreach($branchGroups as $branchKey => $branchPlans)
            {
                if($branchID == 'all' || strpos(",{$branchID},", ",{$branchKey},") !== false) $plans[$productID] = arrayUnion($plans[$productID], $branchPlans);
            }
        }

        $charters = $this->dao->select('id,product,plan,status,reviewStatus,closedReason')->from(TABLE_CHARTER)
            ->where('deleted')->eq(0)
            ->beginIF(count(array_filter($productIdList)) == 1)->andWhere("FIND_IN_SET({$productIdList[0]}, product)")->fi()
            ->fetchAll('id');

        foreach($plans as $productID => $productPlans)
        {
            foreach($productPlans as $planID => $plan)
            {
                if($plan->parent > 0 || $plan->status == 'done')
                {
                    unset($plans[$productID][$planID]);
                    continue;
                }

                $plans[$productID][$planID] = $plan->title;

                foreach($charters as $charter)
                {
                    if(strpos($charter->plan, ",$planID,") !== false)
                    {
                        /* 移除立项状态不是待立项、已立项或者已关闭且关闭原因不是已取消的立项。 */
                        if(($charter->status != 'wait' && $charter->status != 'canceled' && $charter->status != 'closed') || ($charter->status == 'closed' && $charter->closedReason != 'canceled'))
                        {
                            unset($plans[$productID][$planID]);
                            break;
                        }

                        /* 移除立项状态是待立项且审批状态不是待审批的立项。 */
                        if($charter->status == 'wait' && $charter->reviewStatus == 'projectDoing')
                        {
                            unset($plans[$productID][$planID]);
                            break;
                        }

                        /* 移除立项状态是已取消或者已关闭，并且审批状态是激活审批中的立项。 */
                        if(($charter->status == 'canceled' || $charter->status == 'closed') && $charter->reviewStatus == 'activateDoing')
                        {
                            unset($plans[$productID][$planID]);
                            break;
                        }
                    }
                }
            }
        }

        if(!empty($append))
        {
            $appendPlans = $this->getByIDList(explode(',', $append));
            foreach($appendPlans as $plan) $plans[$plan->product][$plan->id] = $plan->title;
        }

        return $plans;
    }
}
