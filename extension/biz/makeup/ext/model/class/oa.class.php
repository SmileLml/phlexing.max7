<?php
class oaMakeup extends makeupModel
{
    /**
     * Check clickable.
     *
     * @param  object $makeup
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable($makeup, $action)
    {
        $action    = strtolower($action);
        $clickable = commonModel::hasPriv('makeup', $action);
        if(!$clickable) return false;

        $account = $this->app->user->account;

        switch($action)
        {
        case 'edit':
        case 'delete':
            $canEdit = strpos(',wait,draft,reject,', ",{$makeup->status},") !== false && $makeup->createdBy == $account;

            return $canEdit;
        case 'switchstatus':
            $canSwitch = strpos(',wait,draft,', ",{$makeup->status},") !== false && $makeup->createdBy == $account;

            return $canSwitch;
        case 'review':
            $reviewedBy = $this->getReviewedBy($makeup->createdBy);
            $canReview  = strpos(',wait,doing,', ",$makeup->status,") !== false && $reviewedBy == $account;

            return $canReview;
        }

        return true;
    }

    /**
     * Rewrite for get list.
     *
     * @param  string $type
     * @param  string $year
     * @param  string $month
     * @param  string $account
     * @param  string $dept
     * @param  string $status
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getList($type = 'personal', $year = '', $month = '', $account = '', $dept = '', $status = '', $orderBy = 'id_desc')
    {
        $date     = '';
        $length   = 0;
        $position = 0;
        if($year)
        {
            $position = 1;
            $length   = 4;
            $date     = $year;
            if($month)
            {
                $length = 7;
                $date   = "$year-$month";
            }
        }
        elseif($month)
        {
            $date     = $month;
            $position = 6;
            $length   = 2;
        }

        $makeupList = $this->dao->select('t1.*, t2.realname, t2.dept')
            ->from(TABLE_OVERTIME)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on("t1.createdBy=t2.account")
            ->where('t1.type')->eq('compensate')
            ->beginIf($date)
            ->andWhere("SUBSTRING(t1.`begin`, $position, $length)", true)->eq($date)
            ->orWhere("SUBSTRING(t1.`end`, $position, $length)")->eq($date)
            ->markRight(1)
            ->fi()
            ->beginIf($account != '')->andWhere('t1.createdBy')->eq($account)->fi()
            ->beginIf($dept != '')->andWhere('t2.dept')->in($dept)->fi()
            ->beginIf($status != '')->andWhere('t1.status')->eq($status)->fi()
            ->beginIf($type == 'browseReview')->andWhere('t1.status')->eq('wait')->fi()
            ->beginIf($type == 'company')->andWhere('t1.status')->ne('draft')->fi()
            ->orderBy("t2.dept,t1.{$orderBy}")
            ->fetchAll('', false);
        $this->session->set('makeupQueryCondition', $this->dao->get());

        return $this->processStatus($makeupList);
    }

    /**
     * Get reviewer of makeup.
     *
     * @param  string $account
     * @access public
     * @return string
     */
    public function getReviewedBy($account = '')
    {
        $reviewedBy = zget($this->config->attend, 'reviewedBy', '');
        $reviewedBy = zget($this->config->makeup, 'reviewedBy', $reviewedBy);

        /* If reviewer is empty get dept manager as reviewer. */
        if(!$reviewedBy && $account) $reviewedBy = $this->attend->getDeptManager($account);

        return $reviewedBy;
    }
}
