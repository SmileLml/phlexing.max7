<?php
class oaAttend extends attendModel
{
    /**
     * Compute attend's status.
     *
     * @param  object $attend
     * @access public
     * @return string
     */
    public function computeStatus($attend)
    {
        $status = parent::computeStatus($attend);
        if($status != 'lieu' and $this->loadModel('lieu')->isLieu($attend->date, $attend->account)) $status = 'lieu';
        if($status != 'leave' and $this->loadModel('leave')->isLeave($attend->date, $attend->account)) $status = 'leave';
        return $status;
    }

    /**
     * Save stat.
     *
     * @param  string    $date
     * @access public
     * @return bool
     */
    public function saveStat($date)
    {
        foreach($this->post->normal as $account => $normal)
        {
            $data = new stdclass();
            $data->account         = $account;
            $data->normal          = $normal;
            $data->late            = $this->post->late[$account];
            $data->early           = $this->post->early[$account];
            $data->absent          = $this->post->absent[$account];
            $data->trip            = $this->post->trip[$account];
            $data->egress          = $this->post->egress[$account];
            $data->paidLeave       = $this->post->paidLeave[$account];
            $data->unpaidLeave     = $this->post->unpaidLeave[$account];
            $data->timeOvertime    = $this->post->timeOvertime[$account];
            $data->restOvertime    = $this->post->restOvertime[$account];
            $data->holidayOvertime = $this->post->holidayOvertime[$account];
            $data->lieu            = $this->post->lieu[$account];
            $data->deserve         = $this->post->deserve[$account];
            $data->actual          = $this->post->actual[$account];
            $data->month           = $date;
            $data->status          = 'wait';

            $this->dao->replace(TABLE_ATTENDSTAT)->data($data)->autoCheck()->exec();
        }

        return !dao::isError();
    }

    /**
     * Set reviewer for attend.
     *
     * @access public
     * @return bool
     */
    public function setManager()
    {
        $deptList = $this->post->dept;
        foreach($deptList as $id => $dept)
        {
            $this->dao->update(TABLE_DEPT)->set('manager')->eq($dept)->where('id')->eq($id)->exec();
        }

        return !dao::isError();
    }

    public function update($oldAttend, $date, $account)
    {
        $date = date('Y-m-d', strtotime($date));
        return parent::update($oldAttend, $date, $account);
    }

    public function checkWaitReviews($month)
    {
        if(!$month or (strlen($month) != 4 && strlen($month) != 6)) $month = date('Ym');
        $year  = substr($month, 0, 4);
        $month = substr($month, 4, 2);

        $leaves    = $this->loadModel('leave')->getList('browseReview', $year, $month);
        $lieus     = $this->loadModel('lieu')->getList('browseReview', $year, $month);
        $makeups   = $this->loadModel('makeup')->getList('browseReview', $year, $month);
        $overtimes = $this->loadModel('overtime')->getList('browseReview', $year, $month);
        $attends   = $this->dao->select('*')->from(TABLE_ATTEND)->where('reviewStatus')->eq('wait')
            ->beginIF($month)->andWhere('LEFT(date, 7)')->eq("$year-$month")->fi()
            ->beginIF(!$month)->andWhere('LEFT(date, 4)')->eq($year)->fi()
            ->fetchAll();

        $waitReviews = array();
        if($leaves)    $waitReviews[] = 'leave';
        if($lieus)     $waitReviews[] = 'lieu';
        if($makeups)   $waitReviews[] = 'makeup';
        if($overtimes) $waitReviews[] = 'overtime';
        if($attends)   $waitReviews[] = 'attend';

        return $waitReviews;
    }

    /**
     * Get dept manager of an user.
     *
     * @param  string $account
     * @access public
     * @return string
     */
    public function getDeptManager($account)
    {
        static $managers = array();
        if(empty($managers)) $managers = $this->loadModel('user')->getUserManagerPairs();
        $manager = trim(zget($managers, $account, ''), ',');

        return $manager;
    }

    /**
     * Get detail attends.
     *
     * @param  string $date
     * @param  string $account
     * @param  int    $deptID
     * @access public
     * @return array
     */
    public function getDetailAttends($date = '', $account = '', $deptID = 0)
    {
        $currentYear  = substr($date, 0, 4);
        $currentMonth = substr($date, 4, 2);
        $startDate    = "{$currentYear}-{$currentMonth}-01";
        $endDate      = date('Y-m-d', strtotime("$startDate +1 month -1 days"));
        $dayNum       = date('t', strtotime("{$currentYear}-{$currentMonth}"));
        if($currentYear . $currentMonth == date('Ym') && $dayNum > date('d')) $dayNum = date('d');

        $deptList = arrayUnion(array(''), $this->loadModel('dept')->getDeptPairs());
        $userList = $this->loadModel('user')->getList();
        $users    = array();
        foreach($userList as $user) $users[$user->account] = $user;

        /* Get attends. */
        $attendList = array();
        if($account)
        {
            $user    = $users[$account];
            $attends = $this->getByAccount($account, $startDate, $endDate < helper::today() ? $endDate : helper::today());
            $attendList[$user->dept][$account] = $attends;
        }
        else
        {
            if($deptID)
            {
                $attendList = $this->getByDept($deptID, $startDate, $endDate < helper::today() ? $endDate : helper::today());
            }
            else
            {
                $attendList = $this->getByDept(array_keys($deptList), $startDate, $endDate < helper::today() ? $endDate : helper::today());
            }
        }

        $attends = array();
        foreach($attendList as $dept => $deptAttends)
        {
            ksort($deptAttends);
            foreach($deptAttends as $account => $userAttends)
            {
                if(strpos(",{$this->config->attend->noAttendUsers},", ",$account,") !== false) continue;

                for($day = 1; $day <= $dayNum; $day++)
                {
                    if($day < 10) $day = '0' . $day;
                    $currentDate = "{$currentYear}-{$currentMonth}-{$day}";

                    $attend = zget($userAttends, $currentDate, '');
                    if(!$attend) continue;

                    $attend->dept     = isset($users[$account]) ? $deptList[$users[$account]->dept] : '';
                    $attend->realname = isset($users[$account]) ? $users[$account]->realname : '';
                    $attend->dayName  = $this->lang->datepicker->dayNames[(int)date('w', strtotime($currentDate))];

                    $desc = '';
                    if($attend->hoursList)
                    {
                        foreach($attend->hoursList as $status => $hours) $desc .= zget($this->lang->attend->statusList, $status) . $hours . 'h ';
                    }
                    elseif($attend->status == 'late' && !empty($attend->signIn))
                    {
                        $seconds = strtotime($attend->signIn) - strtotime($this->config->attend->signInLimit);
                        $desc   .= $this->lang->attend->statusList['late'] . $this->computeDesc($seconds);
                    }
                    elseif($attend->status == 'early' && !empty($attend->signOut))
                    {
                        $seconds = strtotime($this->config->attend->signOutLimit) - strtotime($attend->signOut);
                        $desc   .= $this->lang->attend->statusList['early'] . $this->computeDesc($seconds);
                    }
                    elseif($attend->status == 'both')
                    {
                        $desc = $this->lang->attend->statusList['late'];
                        if(!empty($attend->signIn))
                        {
                            $seconds = strtotime($attend->signIn) - strtotime($this->config->attend->signInLimit);
                            $desc   .= $this->computeDesc($seconds);
                        }

                        $desc .= ', ' . $this->lang->attend->statusList['early'];
                        if(!empty($attend->signOut))
                        {
                            $seconds = strtotime($this->config->attend->signOutLimit) - strtotime($attend->signOut);
                            $desc   .= $this->computeDesc($seconds);
                        }
                    }
                    else
                    {
                        $desc .= zget($this->lang->attend->statusList, $attend->status);
                    }
                    $attend->desc = $desc;

                    $attends[] = $attend;
                }
            }
        }

        return $attends;
    }

    /**
     * Compute attend stat.
     *
     * @param  array  $stat
     * @param  string $startDate
     * @param  string $endDate
     * @access public
     * @return array
     */
    public function computeAttendStat($stat, $startDate, $endDate)
    {
        $attends = $this->getGroupByAccount($startDate, $endDate < helper::today() ? $endDate : helper::today());

        /* Update stat with attends. */
        foreach($attends as $account => $accountAttends)
        {
            if(!isset($stat[$account])) continue;

            foreach($accountAttends as $attend)
            {
                $stat[$account]->actual++;
                if($attend->status == 'normal') $stat[$account]->normal ++;
                if($attend->status == 'late' or $attend->status == 'both')
                {
                    $stat[$account]->late ++;
                }
                if($attend->status == 'early' or ($attend->status == 'both' and $attend->signOut != '00:00:00' and $attend->date != helper::today()))
                {
                    $stat[$account]->early ++;
                }
                unset($stat[$account]->absentDates[$attend->date]);
            }
        }

        return $stat;
    }

    /**
     * sign in.
     *
     * @param  string $account
     * @param  string $date
     * @access public
     * @return bool
     */
    public function signIn($account = '', $date = '')
    {
        if($this->config->attend->signInClient == 'desktop' && $this->app->getViewType() != 'html')
        {
            return array('result' => 'fail', 'message' => sprintf($this->lang->attend->signInClientError, $this->lang->attend->clientList[$this->config->attend->signInClient]));
        }

        if(!$this->checkIP()) return array('result' => 'fail', 'message' => $this->lang->attend->note->IPDenied);

        if($account == '') $account = $this->app->user->account;
        if($date == '')    $date    = date('Y-m-d');

        $device = $this->app->getClientDevice();
        $client = $this->getClient();
        $attend = $this->dao->select('*')->from(TABLE_ATTEND)->where('account')->eq($account)->andWhere('`date`')->eq($date)->fetch();
        if(empty($attend))
        {
            $attend = new stdclass();
            $attend->account = $account;
            $attend->date    = $date;
            $attend->signIn  = helper::time();
            $attend->ip      = helper::getRemoteIp();
            $attend->device  = $device;
            $attend->client  = $client;
            $this->dao->insert(TABLE_ATTEND)->data($attend)->autoCheck()->exec();
            return !dao::isError();
        }

        if($attend->signIn == '' or $attend->signIn == '00:00:00')
        {
            $attend->signIn = helper::time();
            $attend->ip     = helper::getRemoteIp();
            $attend->device = $device;
            $attend->client = $client;

            if(empty($attend->manualIn)) $attend->manualIn = null;
            if(empty($attend->manualOut)) $attend->manualOut = null;
            if(empty($attend->reviewedDate)) $attend->reviewedDate = null;
            $this->dao->update(TABLE_ATTEND)->data($attend)->where('id')->eq($attend->id)->exec();
            return !dao::isError();
        }

        return true;
    }
}
