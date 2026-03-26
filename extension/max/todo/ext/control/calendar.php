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
class todo extends control
{
    public function calendar($userID = '')
    {
        if(!$userID) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;
        $this->app->loadLang('effort');
        $this->session->set('todoList', inlink('calendar', "userID=$userID"));

        if(file_exists($this->app->getExtensionRoot() . 'biz/effort/model.php')) $this->view->effortCount = $this->loadModel('effort')->getCount($account);
        $todoList = $this->todo->getTodos4Side($account);

        $this->view->title        = $this->lang->todo->calendar;
        $this->view->position[]   = $this->lang->todo->common;
        $this->view->position[]   = $this->lang->todo->calendar;
        $this->view->date         = date(DT_DATE1, time());
        $this->view->todoCount    = $this->todo->getCount($account);
        $this->view->userID       = $this->app->user->id;
        $this->view->todoList     = $todoList;
        $this->view->todoProjects = $this->todo->getTodoProjects($todoList);

        $this->display();
    }
}
