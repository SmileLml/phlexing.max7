<?php
class oaLieu extends lieuModel
{
    /**
     * Check clickable.
     *
     * @param  object $lieu
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable($lieu, $action)
    {
        $action    = strtolower($action);
        $clickable = commonModel::hasPriv('lieu', $action);
        if(!$clickable) return false;

        $account = $this->app->user->account;

        switch($action)
        {
        case 'edit':
        case 'delete':
            $canEdit = strpos(',wait,draft,reject,', ",{$lieu->status},") !== false && $lieu->createdBy == $account;

            return $canEdit;
        case 'switchstatus':
            $canSwitch = strpos(',wait,draft,', ",{$lieu->status},") !== false && $lieu->createdBy == $account;

            return $canSwitch;
        case 'review':
            $reviewedBy = $this->getReviewedBy($lieu->createdBy);
            $canReview  = strpos(',wait,doing,', ",$lieu->status,") !== false && $reviewedBy == $account;

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

        $lieuList = $this->dao->select('t1.*, t2.realname, t2.dept')
            ->from(TABLE_LIEU)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on("t1.createdBy=t2.account")
            ->where(1)
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
        $this->session->set('lieuQueryCondition', $this->dao->get());

        return $this->processStatus($lieuList);
    }

    /**
     * Get reviewer of lieu.
     *
     * @param  string $account
     * @access public
     * @return string
     */
    public function getReviewedBy($account = '')
    {
        $reviewedBy = zget($this->config->attend, 'reviewedBy', '');
        $reviewedBy = zget($this->config->lieu, 'reviewedBy', $reviewedBy);

        /* If reviewer is empty get dept manager as reviewer. */
        if(!$reviewedBy && $account) $reviewedBy = $this->attend->getDeptManager($account);

        return $reviewedBy;
    }
}
