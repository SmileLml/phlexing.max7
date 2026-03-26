<?php
class zentaobizMy extends myModel
{
    /**
     * Get reviewing attends
     *
     * @param  array    $allDeptList
     * @param  array    $managedDeptList
     * @access public
     * @return array
     */
    public function getReviewingAttends($allDeptList, $managedDeptList)
    {
        $this->loadModel('attend');
        $account  = $this->app->user->account;
        $deptList = array();
        if(!empty($this->config->attend->reviewedBy) and $this->config->attend->reviewedBy == $account) $deptList = $allDeptList;
        if(empty($this->config->attend->reviewedBy)) $deptList = $managedDeptList;

        $deptList = array_keys($deptList);
        if($this->app->user->admin) $deptList = '';
        if(!$this->app->user->admin and empty($deptList)) return array();
        return $this->attend->getWaitAttends($deptList);
    }

    /**
     * Get reviewing leaves.
     *
     * @param  array  $allDeptList
     * @param  array  $managedDeptList
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getReviewingLeaves($allDeptList, $managedDeptList, $orderBy = 'status')
    {
        $account    = $this->app->user->account;
        $deptList   = array();
        $leaves     = array();
        $reviewedBy = $this->loadModel('leave')->getReviewedBy();
        if($reviewedBy and $reviewedBy == $account) $deptList = $allDeptList;
        if(!$reviewedBy) $deptList = $managedDeptList;

        $deptList = array_keys($deptList);
        if($this->app->user->admin) $deptList = '';
        if(!$this->app->user->admin and empty($deptList)) return array();
        $leaves = $this->leave->getList('browseReview', $year = '', $month = '', '', $deptList, $status = 'wait,pass', $orderBy);
        foreach($leaves as $id => $leave)
        {
            if(!$this->leave->isClickable($leave, 'review')) unset($leaves[$id]);
        }
        return $leaves;
    }

    /**
     * Get reviewing overtimes.
     *
     * @param  array  $allDeptList
     * @param  array  $managedDeptList
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getReviewingOvertimes($allDeptList, $managedDeptList, $orderBy = 'status')
    {
        $account    = $this->app->user->account;
        $deptList   = array();
        $overtimes  = array();
        $reviewedBy = $this->loadModel('overtime')->getReviewedBy();
        if($reviewedBy and $reviewedBy == $account) $deptList = $allDeptList;
        if(!$reviewedBy) $deptList = $managedDeptList;

        $deptList = array_keys($deptList);
        if($this->app->user->admin) $deptList = '';
        if(!$this->app->user->admin and empty($deptList)) return array();
        $overtimes = $this->overtime->getList('browseReview', $year = '', $month = '', '', $deptList, $status = 'wait', $orderBy);
        foreach($overtimes as $id => $overtime)
        {
            if(!$this->overtime->isClickable($overtime, 'review')) unset($overtimes[$id]);
        }
        return $overtimes;
    }

    /**
     * Get reviewing makeups.
     *
     * @param  array  $allDeptList
     * @param  array  $managedDeptList
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getReviewingMakeups($allDeptList, $managedDeptList, $orderBy = 'status')
    {
        $account    = $this->app->user->account;
        $deptList   = array();
        $makeups    = array();
        $reviewedBy = $this->loadModel('makeup')->getReviewedBy();
        if($reviewedBy and $reviewedBy == $account) $deptList = $allDeptList;
        if(!$reviewedBy) $deptList = $managedDeptList;

        $deptList = array_keys($deptList);
        if($this->app->user->admin) $deptList = '';
        if(!$this->app->user->admin and empty($deptList)) return array();
        $makeups = $this->makeup->getList('browseReview', $year = '', $month = '', '', $deptList, $status = 'wait', $orderBy);
        foreach($makeups as $id => $makeup)
        {
            if(!$this->makeup->isClickable($makeup, 'review')) unset($makeups[$id]);
        }
        return $makeups;
    }

    /**
     * Get reviewing lieus.
     *
     * @param  array  $allDeptList
     * @param  array  $managedDeptList
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getReviewingLieus($allDeptList, $managedDeptList, $orderBy = 'status')
    {
        $account    = $this->app->user->account;
        $deptList   = array();
        $lieus      = array();
        $reviewedBy = $this->loadModel('lieu')->getReviewedBy();
        if($reviewedBy and $reviewedBy == $account) $deptList = $allDeptList;
        if(!$reviewedBy) $deptList = $managedDeptList;

        $deptList = array_keys($deptList);
        if($this->app->user->admin) $deptList = '';
        if(!$this->app->user->admin and empty($deptList)) return array();
        $lieus = $this->lieu->getList('browseReview', $year = '', $month = '', '', $deptList, $status = 'wait', $orderBy);
        foreach($lieus as $id => $lieu)
        {
            if(!$this->lieu->isClickable($lieu, 'review')) unset($lieus[$id]);
        }
        return $lieus;
    }

    /**
     * 通过搜索获取反馈数据。
     * Get feedback data by search.
     *
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return array
     */
    public function getFeedbacksBySearch($queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $this->loadModel('search');
        $query         = $queryID ? $this->search->getQuery($queryID) : '';
        $rawMethod     = $this->app->rawMethod;
        $account       = $this->app->user->account;
        $feedbackQuery = $rawMethod . 'FeedbackQuery';
        $feedbackForm  = $rawMethod . 'FeedbackForm';

        if($query)
        {
            $this->session->set($feedbackQuery, $query->sql);
            $this->session->set($feedbackForm, $query->form);
        }
        if($this->session->$feedbackQuery == false) $this->session->set($feedbackQuery, ' 1 = 1');

        /* Distinguish between repeated fields. */
        $feedbackQuery = $this->session->$feedbackQuery;
        if(strpos($feedbackQuery, '`id`')     !== false) $feedbackQuery = str_replace('`id`', 't1.`id`', $feedbackQuery);
        if(strpos($feedbackQuery, '`type`')   !== false) $feedbackQuery = str_replace('`type`', 't1.`type`', $feedbackQuery);
        if(strpos($feedbackQuery, '`status`') !== false) $feedbackQuery = str_replace('`status`', 't1.`status`', $feedbackQuery);

        $feedbackSQL = '';
        if($rawMethod == 'contribute')
        {
            $feedbackSQL = $this->dao->select('objectID')->from(TABLE_ACTION)
                ->where('actor')->eq($account)
                ->andWhere('objectType')->eq('feedback')
                ->andWhere('action')->in('assigned,reviewed')
                ->get();
        }

        return $this->dao->select('t1.*,t2.dept')->from(TABLE_FEEDBACK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
            ->where($feedbackQuery)
            ->andWhere('t1.deleted')->eq('0')
            ->beginIF($rawMethod == 'work')->andWhere('t1.assignedTo')->eq($account)->fi()
            ->beginIF($rawMethod == 'contribute')
            ->andWhere('t1.openedBy', 1)->eq($account)
            ->orWhere('t1.closedBy')->eq($account)
            ->orWhere('t1.id')->subIn($feedbackSQL)
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }
}
