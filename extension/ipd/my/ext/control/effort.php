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
helper::importControl('my');
class mymy extends my
{
    /**
     * My todos.
     *
     * @param  string $type
     * @param  string $account
     * @param  string $status
     * @access public
     * @return void
     */
    public function effort($type = 'today', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $type = strtolower($type);
        $this->lang->my->menu->effort['subModule'] = 'my';

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('effortList', $uri);
        $this->session->set('effortType', $type);

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* The header and position. */
        $this->view->title      = $this->lang->my->common . $this->lang->hyphen . $this->lang->my->effort;
        $this->view->position[] = $this->lang->my->effort;

        $this->loadModel('effort');

        $this->loadModel('datatable');
        $this->config->effort->datatable->defaultField[] = 'actions';
        $this->config->effort->datatable->fieldList['actions']['title']    = 'actions';
        $this->config->effort->datatable->fieldList['actions']['fixed']    = 'right';
        $this->config->effort->datatable->fieldList['actions']['width']    = '90';
        $this->config->effort->datatable->fieldList['actions']['required'] = 'yes';

        list($begin, $end) = $this->effort->parseDate($type);

        $efforts     = $this->effort->getList($begin, $end, $this->app->user->account, 0, 0, 0, $orderBy, $pager);
        $canViewList = array();
        $objectTypeList = array_column($efforts, 'objectType');
        foreach($objectTypeList as $objectType)
        {
            $method = $objectType == 'feedback' && $this->config->vision != 'lite' ? 'adminView' : 'view';
            $canViewList[$objectType] = common::hasPriv($objectType, $method);
        }

        /* Assign. */
        $this->view->efforts         = $efforts;
        $this->view->date            = (int)$type == 0 ? date(DT_DATE1, time()) : substr($type, 0, 4) . '-' . substr($type, 4, 2) . '-' . substr($type, 6, 2);
        $this->view->type            = is_numeric($type) ? 'bydate' : $type;
        $this->view->account         = $this->app->user->account;
        $this->view->pager           = $pager;
        $this->view->orderBy         = $orderBy;
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->depts           = $this->dept->getOptionMenu();
        $this->view->products        = arrayUnion(array(0 => ''), $this->loadModel('product')->getPairs('', 0, '', 'all'));
        $this->view->projects        = arrayUnion(array(0 => ''), $this->loadModel('project')->getPairsByModel());
        $this->view->executions      = arrayUnion(array(0 => ''), $this->loadModel('execution')->getPairs(0, 'all', 'multiple'));
        $this->view->productProjects = $this->effort->getProductProjectPairs(array_keys($this->view->products), true);
        $this->view->canViewList     = $canViewList;

        $this->display();
    }
}
