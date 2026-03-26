<?php
public function isClickable($leave, $action)
{
    return $this->loadExtension('oa')->isClickable($leave, $action);
}

public function getList($type = 'personal', $year = '', $month = '', $account = '', $dept = '', $status = '', $orderBy = 'id_desc')
{
    return $this->loadExtension('oa')->getList($type, $year, $month, $account, $dept, $status, $orderBy);
}

public function getReviewedBy($account = '', $module = 'leave', $app = 'oa')
{
    return $this->loadExtension('oa')->getReviewedBy($account, $module, $app);
}

public function getAllMonth($type)
{
    return $this->loadExtension('oa')->getAllMonth($type);
}
