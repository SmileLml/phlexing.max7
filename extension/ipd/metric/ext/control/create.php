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
     * 创建度量项。
     * Create a metric.
     *
     * @param  string  $scope  default scope
     * @param  string  $period default period
     * @param  string  $from   metric|metriclib
     * @access public
     * @return void
     */
    public function create($scope = 'system', $period = 'nodate', $from = 'metric')
    {
        unset($this->lang->metric->scopeList['other']);
        unset($this->lang->metric->purposeList['other']);
        unset($this->lang->metric->objectList['other']);
        unset($this->lang->metric->objectList['review']);

        if(!empty($_POST))
        {
            if(!empty($_POST['code']) and !validater::checkREG($_POST['code'], '/^[A-Za-z_0-9]+$/')) dao::$errors['code'] = $this->lang->metric->tips->noticeCode;

            $metricData = $this->metricZen->buildMetricForCreate();
            $metricData->unit = isset($_POST['customUnit']) ? $_POST['addunit'] : $_POST['unit'];

            $metricID = $this->metric->create($metricData);

            if(empty($metricID) || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($from == 'metric')    $location = $this->createLink('metric', 'browse', "scope=$metricData->scope");
            if($from == 'metriclib') $location = $this->createlink('metriclib', 'browse', "scope=$scope&period=$period");
            $response = $this->metricZen->responseAfterCreate($metricID, $this->post->afterCreate, $from, $location);

            return $this->send($response);
        }

        $this->metric->processObjectList();
        $this->metric->processUnitList();
        $this->view->scope  = $scope;
        $this->view->period = $period;
        $this->view->from   = $from;
        $this->display();
    }
}
