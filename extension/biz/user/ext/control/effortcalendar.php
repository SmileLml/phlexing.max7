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
?>
<?php
class user extends control
{
    /**
     * Effort Calendar.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function effortcalendar($userID)
    {
        $this->app->loadLang('todo');

        $user    = $this->user->getById($userID, 'id');
        $account = $user->account;
        $users   = $this->loadModel('dept')->getDeptUserPairs($this->app->user->dept, 'id');

        $this->view->title      = $this->lang->user->common . $this->lang->hyphen . $this->lang->user->effortcalendar;
        $this->view->position[] = $this->lang->user->effortcalendar;

        $this->view->user     = $user;
        $this->view->userID   = $userID;
        $this->view->efforts  = $this->loadModel('effort')->getEfforts4Calendar($account);
        $this->view->userList = $this->user->setUserList($users, $userID);
        $this->display();
    }
}
