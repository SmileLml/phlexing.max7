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
class my extends control
{
    /**
     * Construct function.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('user');
        $this->loadModel('dept');

        $this->lang->my->menu->effort['subModule'] = 'my';
    }

    /**
     * My efforts.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function effort($type = 'all', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $type = strtolower($type);
        $this->lang->my->menu->effort['subModule'] = 'my';

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('effortList',      $uri);
        $this->session->set('effortType',      $type);
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');
        $this->session->set('docList',         $uri, 'doc');
        $this->session->set('issueList',       $uri, 'project');
        $this->session->set('riskList',        $uri, 'project');
        $this->session->set('reviewList',      $uri, 'project');

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        list($begin, $end) = $this->loadModel('effort')->parseDate($type);

        $efforts     = $this->effort->getList($begin, $end, $this->app->user->account, 0, 0, 0, $orderBy, $pager);
        $canViewList = array();
        $objectTypeList = array_column($efforts, 'objectType');
        foreach($objectTypeList as $objectType)
        {
            $method = $objectType == 'feedback' && $this->config->vision != 'lite' ? 'adminView' : 'view';
            $canViewList[$objectType] = common::hasPriv($objectType, $method);
        }

        /* Assign. */
        $this->view->title           = $this->lang->my->common . $this->lang->hyphen . $this->lang->my->effort;
        $this->view->efforts         = $this->effort->getList($begin, $end, $this->app->user->account, 0, 0, 0, $orderBy, $pager);
        $this->view->date            = (int)$type == 0 ? date(DT_DATE1, time()) : substr($type, 0, 4) . '-' . substr($type, 4, 2) . '-' . substr($type, 6, 2);
        $this->view->type            = is_numeric($type) ? 'bydate' : $type;
        $this->view->userID          = $this->app->user->id;
        $this->view->pager           = $pager;
        $this->view->orderBy         = $orderBy;
        $this->view->users           = $this->user->getPairs('noletter');;
        $this->view->depts           = $this->dept->getOptionMenu();
        $this->view->products        = arrayUnion(array(''), $this->loadModel('product')->getPairs('', 0, '', 'all'));
        $this->view->projects        = arrayUnion(array(''), $this->loadModel('project')->getPairsByModel());
        $this->view->executions      = arrayUnion(array(''), $this->loadModel('execution')->getPairs(0, 'all'));
        $this->view->productProjects = $this->effort->getProductProjectPairs(array_keys($this->view->products), true);
        $this->view->canViewList     = $canViewList;

        $this->display();
    }
}
