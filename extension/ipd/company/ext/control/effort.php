<?php
/**
 * The control file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     company
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class company extends control
{
    /**
     * effort.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function effort($date = 'today', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('datatable');

        $super = common::hasPriv('company', 'alleffort');

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('effortList',      $uri);
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');
        $this->session->set('docList',         $uri, 'doc');
        $this->session->set('todoList',        $uri, 'my');
        $this->session->set('issueList',       $uri, 'project');
        $this->session->set('opportunityList', $uri, 'project');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        list($begin, $end) = $this->loadModel('effort')->parseDate($date);

        $dept      = $super ? 0 : $this->app->user->dept;
        $account   = '';
        $product   = 0;
        $project   = 0;
        $execution = 0;
        $userType  = '';
        if($_POST or $date == 'custom')
        {
            if($_POST)
            {
                $data = fixer::input('post')->get();
                $this->session->set('effortCustomData', json_encode($data));
            }
            else
            {
                $data = json_decode($this->session->effortCustomData);
            }

            $dept      = $data->dept;
            $userType  = $data->userType;
            $account   = $data->user;
            $product   = $data->product;
            $project   = isset($data->project) ? $data->project : $project;
            $execution = $data->execution;
            $begin     = $data->begin;
            $end       = $data->end;
            $date      = 'custom';
        }

        /* Get products' list.*/
        $products = $this->loadModel('product')->getPairs();
        $products = arrayUnion(array(''), $products);
        $this->view->products = $products;

        /* Get projects' list.*/
        $projects = $this->loadModel('project')->getPairsByModel('all', 'noclosed');
        $projects = arrayUnion(array(''), $projects);
        $this->view->projects = $projects;

        $executions    = $this->loadModel('execution')->getPairs();
        $executionList = $this->execution->getByIdList(array_keys($executions));
        foreach($executionList as $executionID => $executionInfo)
        {
            if(isset($projects[$executionInfo->project]))
            {
                $executions[$executionInfo->id] = $projects[$executionInfo->project] . '/' . ltrim($executions[$executionInfo->id], '/');
            }
        }
        $executions = arrayUnion(array(''), $executions);
        $this->view->executions = $executions;

        /* Get users.*/
        $users     = arrayUnion(array('' => ''), $this->loadModel('dept')->getDeptUserPairs((int)$dept));
        $this->view->users = $users;

        $mainDepts = $this->loadModel('dept')->getOptionMenu($super ? 0 : $this->app->user->dept);
        unset($mainDepts[0]);
        if($super) $mainDepts = arrayUnion(array('all' => '/' . $this->lang->company->allDept), $mainDepts);
        $this->view->mainDepts = $mainDepts;

        /* The header and position. */
        $this->view->title      = $this->lang->company->common . $this->lang->hyphen . $this->lang->company->effort->common;
        $this->view->position[] = $this->lang->company->effort->common;

        /* Assign. */
        $this->view->efforts   = $this->loadModel('effort')->getList($begin, $end, $account, $product, $execution, $dept, $orderBy, $pager, $project, $userType);
        $this->view->account   = $account;
        $this->view->user      = $account ? $this->loadModel('user')->getById($account) : '';
        $this->view->product   = $product;
        $this->view->project   = $project;
        $this->view->execution = $execution;
        $this->view->dept      = $dept;
        $this->view->userType  = $userType;
        $this->view->begin     = $begin;
        $this->view->end       = $end;
        $this->view->date      = $date;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;

        $this->display();
    }
}
