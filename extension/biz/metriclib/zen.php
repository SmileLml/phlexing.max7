<?php
/**
 * The zen file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easysoft.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */
class metriclibZen extends metriclib
{
    /**
     * 返回某个范围的度量库。
     * Prepare builtin scope tree.
     *
     * @param  array  $scope
     * @access public
     * @return array
     */
    protected function prepareBuiltInTree($scope)
    {
        $moduleTree = array();
        $periodList = array_keys($this->lang->metriclib->periodTextList);

        foreach($periodList as $period)
        {
            $id   = "{$scope}_{$period}";
            $name = $this->lang->metriclib->periodTextList[$period];
            $viewType = ($scope == 'system' and $period == 'nodate') ? 'latest' : 'history';

            $moduleTree[$id] = (object)array
            (
                'id'   => $id,
                'parent' => 0,
                'name' => $name,
                'url'  => $this->inlink('browse', "scope=$scope&period=$period&viewType=$viewType")
            );
        }

        return $moduleTree;
    }

    /**
     * 返回内置的范围下拉菜单列表。
     * Buildin scope dropdown menu list.
     *
     * @access public
     * @return array
     */
    protected function prepareBuiltInMenu()
    {
        $menuList = array();
        foreach($this->lang->metriclib->scopeList as $scope => $scopeName)
        {
            $url = $this->inlink('browse', "scope=$scope");
            if($scope == 'system') $url = $this->inlink('browse', "scope=system&period=nodate&viewType=latest");
            $menuList[] = array('key' => $scope, 'text' => $scopeName, 'url' => $url);
        }

        return $menuList;
    }

    /**
     * Get filters.
     *
     * @param  string $scope
     * @param  string $period
     * @param  array  $post
     * @param  bool   $isClear
     * @access public
     * @return array
     */
    protected function getFilters($scope, $period, $post, $isClear = false)
    {
        $sessionName = $scope . ucfirst($period) . 'Library';

        if(!empty($post)) $this->session->set($sessionName, $_POST);
        if($isClear) $this->session->set($sessionName, array());

        return $this->session->$sessionName;
    }

    /**
     * 返回内置的范围下拉菜单列表。
     * Buildin scope dropdown menu list.
     *
     * @param  array  $scope
     * @access public
     * @return array
     * @param mixed[] $codeList
     */
    public function getMetriclibTip($codeList)
    {
        if(empty(array_filter($codeList)))
        {
            $tipKey = 'empty';
        }
        else
        {
            $metrics = $this->loadModel('metric')->getMetricsByCodeList($codeList);
            $haveCalcTime = false;
            foreach($metrics as $metric) if($metric->lastCalcTime) $haveCalcTime = true;

            $tipKey = $haveCalcTime ? 'nodata' : 'notrun';
        }

        return $this->lang->metriclib->metriclibTip[$tipKey];
    }

    /**
     * Get details of metric library.
     *
     * @param  string $scope
     * @param  string $period
     * @access public
     * @return array
     */
    public function getLibDetails($scope, $period)
    {
        $details = array();
        $details['name']      = array('name' => $this->lang->metriclib->name, 'text' => sprintf($this->lang->metriclib->libraryName, $this->lang->metriclib->scopeList[$scope], $this->lang->metriclib->periodTextList[$period]));
        $details['code']      = array('name' => $this->lang->metriclib->code, 'text' => $this->config->metriclib->periodCodeList[$period] . ucfirst($scope) . 'Library');
        $details['scope']     = array('name' => $this->lang->metriclib->scope, 'text' => $this->lang->metriclib->scopeList[$scope]);
        $details['period']    = array('name' => $this->lang->metriclib->period, 'text' => $this->lang->metriclib->periodList[$period]);
        $details['desc']      = array('name' => $this->lang->metriclib->desc, 'text' => '');
        $details['createdBy'] = array('name' => $this->lang->metriclib->createdBy, 'text' => $this->lang->metriclib->createdInfo);

        return $details;
    }
}
