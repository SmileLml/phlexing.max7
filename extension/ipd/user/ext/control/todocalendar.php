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
     * Todo Calendar.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function todocalendar($userID)
    {
        $this->app->loadLang('effort');

        $user  = $this->user->getById($userID, 'id');
        if(empty($user)) die(js::error($this->lang->notFound) . js::locate($this->createLink('my', 'team')));
        if($user->deleted == 1) die(js::error($this->lang->user->noticeHasDeleted) . js::locate('back'));

        $account = $user->account;
        $deptID  = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users   = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) die(js::error($this->lang->user->error->noAccess) . js::locate('back'));

        $this->view->title      = $this->lang->user->common . $this->lang->hyphen . $this->lang->user->todocalendar;
        $this->view->position[] = $this->lang->user->todocalendar;

        $this->view->user     = $user;
        $this->view->userID   = $userID;
        $this->view->todos    = $this->loadModel('todo')->getTodos4Calendar($account, date('Y'));
        $this->view->userList = $this->user->setUserList($users, $userID);
        $this->display();
    }
}
