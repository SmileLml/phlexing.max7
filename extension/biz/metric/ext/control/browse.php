<?php
/**
 * The control file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song
 * @package     metric
 * @version     $Id: control.php 5086 2023-06-06 02:25:22Z
 * @link        http://www.zentao.net
 */
class metric extends control
{
    /**
     * 查看度量项列表。
     * Browse metric list.
     *
     * @param  string $scope
     * @param  string $stage
     * @param  int    $param
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($scope = 'project', $stage = 'all', $param = 0, $type = 'bydefault', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        unset($this->config->metric->dtable->definition->fieldList['actions']['list']['delete']);
        $this->loadModel('search');
        $this->metric->processScopeList();

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = $type == 'bydefault' ? 0 : (int)$param;
        $actionURL = $this->createLink('metric', 'browse', "scope=$scope&stage=$stage&param=myQueryID&type=bysearch");
        $this->metric->buildSearchForm($queryID, $actionURL);

        $metrics = $this->metric->getList($scope, $stage, $param, $type, $queryID, $orderBy, $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'metric', true); // Process the sql, get the conditon partion, save it to session.

        $metrics    = $this->metricZen->prepareActionPriv($metrics);
        $modules    = $this->metric->getModuleTreeList($scope);
        $metricTree = $this->metricZen->prepareTree($scope, $stage, $modules);
        $scopeList  = $this->metricZen->prepareScopeList();

        $oldMetricPairs = array();
        foreach($metrics as $metric)
        {
            if($this->metric->isOldMetric($metric)) $oldMetricPairs[$metric->id] = $metric->fromID;
        }

        $this->view->title          = $this->lang->metric->common;
        $this->view->metrics        = $metrics;
        $this->view->oldMetricPairs = $oldMetricPairs;
        $this->view->pager          = $pager;
        $this->view->orderBy        = $orderBy;
        $this->view->param          = $param;
        $this->view->metricTree     = $metricTree;
        $this->view->closeLink      = $this->inlink('browse', 'scope=' . $scope);
        $this->view->type           = $type;
        $this->view->stage          = $stage;
        $this->view->scopeList      = $scopeList;
        $this->view->scope          = $scope;
        $this->view->scopeText      = $this->lang->metric->scopeList[$scope];

        $users = $this->loadModel('user')->getPairs('noletter');
        if(!isset($users['system'])) $users = arrayUnion($users, array('system' => 'system'));
        $this->view->users = $users;

        $this->display();
    }
}
