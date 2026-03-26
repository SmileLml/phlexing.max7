<?php
/**
 * The control file of metriclib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      qixinzhi <qixinzhi@easycorp.ltd>
 * @package     metriclib
 * @link        http://www.zentao.net
 */
class metriclib extends control
{
    /**
     * 查看度量库列表。
     * Browse metricLib list.
     *
     * @param  string $scope
     * @param  string $period
     * @param  string $viewType
     * @param  bool   $isClearFilter
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($scope = 'project', $period = 'nodate', $viewType = 'history', $isClearFilter = true, $recTotal = 0, $recPerPage = 25, $pageID = 1)
    {
        $this->loadModel('metric');
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $filters = $this->metriclibZen->getFilters($scope, $period, $_POST, (bool)$isClearFilter);

        $metricPairs        = $this->metriclib->getMetricPairs($scope, $period, null, 'all', 'unlimited');
        $defaultMetricPairs = $this->metriclib->getDefaultMetricPairs($scope, $period);

        $defaultDateFilter = $this->metriclib->getDefaultDateFilter($period);
        $filters['metric']    = isset($filters['metric']) ? $filters['metric'] : array_intersect($defaultMetricPairs, array_keys($metricPairs));
        $filters['dateBegin'] = isset($filters['dateBegin']) ? $filters['dateBegin'] : $defaultDateFilter['dateBegin'];
        $filters['dateEnd']   = isset($filters['dateEnd'])   ? $filters['dateEnd']   : $defaultDateFilter['dateEnd'];

        $isHistory = $viewType == 'history';
        if($isHistory)
        {
            list($cols, $data) = $this->metriclib->initTable($scope, $period, $filters, $pager);
        }
        else
        {
            list($cols, $data) = $this->metriclib->initLatestTable($scope, $filters['metric'], $pager);
            $this->view->latestDate = $this->metriclib->getLatestDate($data);
        }

        $this->view->title   = $this->lang->metriclib->common;
        $this->view->scope   = $scope;
        $this->view->period  = $period;
        $this->view->libName = $this->metriclib->getMetricLibName($scope, $period);
        $this->view->cols    = $cols;
        $this->view->data    = $data;
        $this->view->filters = $filters;

        $this->view->tableHeaderHeight = $this->metriclib->getTableHeaderHeight($cols);

        $this->view->viewType      = $viewType;
        $this->view->isHistory     = $isHistory;
        $this->view->viewBtnType   = $isHistory ? 'latest' : 'history';
        $this->view->viewBtnText   = $isHistory ? $this->lang->metriclib->latestView : $this->lang->metriclib->historyView;
        $this->view->canChangeView = ($scope == 'system' and $period == 'nodate');

        $this->view->dtablePager = $pager;
        $this->view->libTree     = $this->metriclibZen->prepareBuiltInTree($scope);
        $this->view->scopeText   = $this->lang->metriclib->scopeList[$scope];
        $this->view->scopeMenu   = $this->metriclibZen->prepareBuiltInMenu();

        $this->view->scopeOptions       = $this->metric->getPairsByScope($scope);
        $this->view->metricOptions      = $metricPairs;
        $this->view->defaultMetricPairs = $defaultMetricPairs;
        $this->view->libDTableTip       = $data ? '' : $this->metriclibZen->getMetriclibTip($filters['metric']);

        $parentScope = $scope != 'system' ? $this->config->metriclib->parentScope[$scope] : '';
        $this->view->parentScope   = $parentScope;
        $this->view->parentOptions = $scope != 'system' ? $this->metric->getPairsByScope($parentScope) : array();


        $this->display();
    }

    /**
     * View details.
     *
     * @param  string $scope
     * @param  string $period
     * @access public
     * @return void
     */
    public function details($scope, $period)
    {
        $this->view->libDetails = $this->metriclibZen->getLibDetails($scope, $period);
        $this->display();
    }

    /**
     * Ajax get options of scope filter
     *
     * @param  string $scope
     * @param  string $parentIdList
     * @access public
     * @return void
     */
    public function ajaxGetFilterOptions($scope, $parentIdList = '')
    {
        $optionPairs = $this->metriclib->getOptionsByParent($scope, $parentIdList);
        return print(json_encode($optionPairs));
    }
}
