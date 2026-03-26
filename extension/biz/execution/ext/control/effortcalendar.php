<?php
/**
 * The control file of calendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
helper::importControl('execution');
class myexecution extends execution
{
    public function effortCalendar($executionID, $userID = '')
    {
        $this->app->loadLang('effort');
        $this->execution->setMenu($executionID);

        /* Get users.*/
        $accounts  = $this->execution->getTeamMembers($executionID);
        $users[''] = $this->lang->user->select;
        foreach($accounts as $user) $users[$user->userID] = $user->realname;

        /* The header and position. */
        $this->view->title      = $this->lang->execution->common . $this->lang->hyphen . $this->lang->execution->effortCalendar;
        $this->view->position[] = $this->lang->execution->effortCalendar;

        /* Assign. */
        $this->view->users       = $users;
        $this->view->userID      = $userID ;
        $this->view->executionID = $executionID;

        $this->display();
    }
}
