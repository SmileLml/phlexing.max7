<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 */
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * Effort
     *
     * @param  int    $executionID
     * @param  string $date
     * @param  int    $userID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function effort($executionID, $date = 'today', $userID = '', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $account = '';
        if($userID)
        {
            $user    = $this->loadModel('user')->getById($userID, 'id');
            $account = $user->account;
        }

        $this->execution->setMenu($executionID);

        /* Save session. */
        $this->session->set('effortList', $this->app->getURI(true));
        $this->session->set('taskList', $this->app->getURI(true), 'execution');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $this->view->date    = $date;

        /* Get users.*/
        $users     = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'useid');
        $accounts  = $this->user->getPairs('noletter|noclosed');
        $users[''] = $this->lang->user->select;
        $this->view->users    = $users;

        /* The header and position. */
        $this->view->title      = $this->lang->execution->common . $this->lang->hyphen . $this->lang->execution->effort;
        $this->view->position[] = $this->lang->execution->effort;

        /* Assign. */
        list($begin, $end) = $this->loadModel('effort')->parseDate($date);
        $this->view->efforts     = $this->loadModel('effort')->getList($begin, $end, $account, $product = 0, $executionID, $dept = 0, $orderBy, $pager);
        $this->view->today       = (int)$date == 0 ? date('Y-m-d') : substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
        $this->view->users       = $users;
        $this->view->accounts    = $accounts;
        $this->view->userID      = $userID ;
        $this->view->executionID = $executionID;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;

        $this->display();
    }
}
