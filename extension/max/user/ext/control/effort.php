<?php
/**
 * The control file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class user extends control
{
    /**
     * My efforts.
     *
     * @param  string $userID
     * @param  string $type
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function effort($userID = '', $type = 'today', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('effortList', $uri);

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $this->view->pager   = $pager;

        list($begin, $end) = $this->loadModel('effort')->parseDate($type);
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->user->getById($userID, 'id');
        $account = $user->account;
        $users   = $this->loadModel('dept')->getDeptUserPairs($this->app->user->dept, 'id');
        if(!isset($users[$userID])) die(js::error($this->lang->user->error->noAccess) . js::locate('back'));

        /* The header and position. */
        $this->view->title      = $this->lang->user->common . $this->lang->hyphen . $this->lang->user->effort;
        $this->view->position[] = $this->lang->user->effort;

        $this->lang->admin->menu    = $this->lang->my->menu;
        $this->lang->noMenuModule[] = 'user';

        /* Assign. */
        $this->view->efforts  = $this->loadModel('effort')->getList($begin, $end, $account, $product = 0, $execution = 0, $dept = 0, $orderBy = 'date_desc', $pager);
        $this->view->date     = (int)$type == 0 ? date(DT_DATE1, time()) : substr($type, 0, 4) . '-' . substr($type, 4, 2) . '-' . substr($type, 6, 2);
        $this->view->type     = $type;
        $this->view->userID   = $userID;
        $this->view->user     = $user;
        $this->view->userList = $this->user->setUserList($users, $userID);

        $this->display();
    }
}
