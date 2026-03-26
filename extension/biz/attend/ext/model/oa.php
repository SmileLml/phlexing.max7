<?php
public function __construct()
{
    parent::__construct();
    if(isset($this->config->attend->noAttendUsers) and isset($this->app->user->account) and strpos(",{$this->config->attend->noAttendUsers},", ",{$this->app->user->account},") !== false)
    {
        unset($this->lang->attend->featurebar->personal);
        $this->lang->oa->menu->attend   = array('link' => $this->lang->attend->attendance.'|attend|department');
    }
}

/**
 * Compute attend's status.
 *
 * @param  object $attend
 * @access public
 * @return string
 */
public function computeStatus($attend)
{
    return $this->loadExtension('oa')->computeStatus($attend);
}

/**
 * Save stat.
 *
 * @param  int    $date
 * @access public
 * @return bool
 */
public function saveStat($date)
{
    return $this->loadExtension('oa')->saveStat($date);
}

/**
 * Set reviewer for attend.
 *
 * @access public
 * @return bool
 */
public function setManager()
{
    return $this->loadExtension('oa')->setManager();
}

/**
 * Judge an action is clickable or not.
 *
 * @param  object $attend
 * @param  string $action
 * @access public
 * @return bool
 */
public function isClickable($attend, $action)
{
    $action    = strtolower($action);
    $clickable = commonModel::hasPriv('attend', $action);
    if(!$clickable) return false;

    $account = $this->app->user->account;

    switch($action)
    {
    case 'review' :
        $reviewedBy = $this->getReviewedBy($attend->account);
        $canReview  = $attend->reviewStatus == 'wait' && $reviewedBy == $account;

        return $canReview;
    }

    return true;
}

public function update($oldAttend, $date, $account)
{
    return $this->loadExtension('oa')->update($oldAttend, $date, $account);
}

public function checkWaitReviews($month)
{
    return $this->loadExtension('oa')->checkWaitReviews($month);
}

public function getDeptManager($month)
{
    return $this->loadExtension('oa')->getDeptManager($month);
}

public function getDetailAttends($date = '', $account = '', $deptID = 0)
{
    return $this->loadExtension('oa')->getDetailAttends($date, $account, $deptID);
}

public function computeAttendStat($stat, $startDate, $endDate)
{
    return $this->loadExtension('oa')->computeAttendStat($stat, $startDate, $endDate);
}

public function signIn($account = '', $date = '')
{
    return $this->loadExtension('oa')->signIn($account, $date);
}
