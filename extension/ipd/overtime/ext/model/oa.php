<?php
public function isClickable($overtime, $action)
{
    return $this->loadExtension('oa')->isClickable($overtime, $action);
}

public function getList($type = 'personal', $year = '', $month = '', $account = '', $dept = '', $status = '', $orderBy = 'id_desc')
{
    return $this->loadExtension('oa')->getList($type, $year, $month, $account, $dept, $status, $orderBy);
}

public function getReviewedBy($account = '')
{
    return $this->loadExtension('oa')->getReviewedBy($account);
}
