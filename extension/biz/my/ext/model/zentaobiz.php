<?php
public function getReviewingAttends($allDeptList, $managedDeptList)
{
    return $this->loadExtension('zentaobiz')->getReviewingAttends($allDeptList, $managedDeptList);
}

public function getReviewingLeaves($allDeptList, $managedDeptList, $orderBy = 'status')
{
    return $this->loadExtension('zentaobiz')->getReviewingLeaves($allDeptList, $managedDeptList, $orderBy);
}

public function getReviewingOvertimes($allDeptList, $managedDeptList, $orderBy = 'status')
{
    return $this->loadExtension('zentaobiz')->getReviewingOvertimes($allDeptList, $managedDeptList, $orderBy);
}

public function getReviewingMakeups($allDeptList, $managedDeptList, $orderBy = 'status')
{
    return $this->loadExtension('zentaobiz')->getReviewingMakeups($allDeptList, $managedDeptList, $orderBy);
}

public function getReviewingLieus($allDeptList, $managedDeptList, $orderBy = 'status')
{
    return $this->loadExtension('zentaobiz')->getReviewingLieus($allDeptList, $managedDeptList, $orderBy);
}

public function getFeedbacksBySearch($queryID = 0, $orderBy = 'id_desc', $pager = null)
{
    return $this->loadExtension('zentaobiz')->getFeedbacksBySearch($queryID, $orderBy, $pager);
}
