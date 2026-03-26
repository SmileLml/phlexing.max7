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
class effort extends control
{
    public function calendar($userID = '')
    {
        if(!$userID) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $this->view->title = $this->lang->effort->calendar;
        $this->view->date  = date(DT_DATE1, time());
        $this->view->user  = $user;

        if(file_exists($this->app->getExtensionRoot() . 'biz/effort/model.php')) $this->view->effortCount = $this->effort->getCount($account);
        $this->view->todoCount  = $this->loadModel('todo')->getCount($account);

        $this->display();
    }
}
