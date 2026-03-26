<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Guangming Sun <sunguangming@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 */
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * TaskLeft
     *
     * @param  int    $executionID
     * @param  string $groupBy
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function computeTaskEffort($begin = '', $end = '')
    {
        if($begin && $end)
        {
            $begin    = date('Y-m-d', strtotime($begin));
            $end      = date('Y-m-d', strtotime($end));
            $diffDate = helper::diffDate($end, $begin);
            if($diffDate > 0 && $diffDate < 365)
            {
                $date     = $this->app->loadClass('date');
                $dateList = $date->getDateList($begin, $end, 'Y-m-d');
                foreach($dateList as $date)
                {
                    $this->execution->computeTaskEffort($date);
                }
            }
        }
        else
        {
            $this->execution->computeTaskEffort();
        }

        echo 'success';
    }
}
