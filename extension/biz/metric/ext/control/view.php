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
     * 度量项详情页。
     * View a metric.
     *
     * @param  int    $metricID
     * @access public
     * @return void
     */
    public function view($metricID)
    {
        $this->metric->processUnitList();

        $metric = $this->metric->getByID($metricID);
        $isOldMetric = $this->metric->isOldMetric($metric);
        if($isOldMetric) $measurement = $this->metric->getOldMetricByID($metric->fromID);

        if($_POST && $isOldMetric)
        {
            $result = $this->metric->createSqlFunction($measurement->configure, $measurement);
            if($result['result'] != 'success') return $this->send($result);

            foreach($this->post->varName as $i => $varName)
            {
                if(empty($varName)) return $this->send(array('result' => 'fail', 'errors' => $this->lang->metric->tips->noticeVarName));
                $params[$varName]['showName'] = zget($this->post->showName, $i, '');

                $errors = array();
                if($params[$varName]['showName'] == '') $errors[] = sprintf($this->lang->metric->tips->showNameMissed, $varName);
                if(empty($this->post->queryValue[$i]))  $errors[] = sprintf($this->lang->metric->tips->noticeQueryValue, $varName);

                if(!empty($errors)) return $this->send(array('result' => 'fail', 'errors' => join("<br>", $errors)));

                $params[$varName]['varName']      = $varName;
                $params[$varName]['varType']      = zget($this->post->varType, $i, 'input');
                $params[$varName]['showName']     = zget($this->post->showName, $i, '');
                $params[$varName]['options']      = $this->post->options[$i];
                $params[$varName]['defaultValue'] = zget($this->post->defaultValue, $i, '');
            }

            $this->dao->update(TABLE_BASICMEAS)
                ->set('configure')->eq($measurement->configure)
                ->set('params')->eq(json_encode($params))
                ->where('id')->eq($metric->fromID)
                ->exec();

            $params       = $this->metric->processPostParams();
            $measFunction = $this->metric->getSqlFunctionName($measurement);
            $queryResult  = $this->metric->execSqlMeasurement($measurement, $params);

            if($queryResult === false) return $this->send(array('result' => 'fail', 'message' => $this->metric->errorInfo));
            return $this->send(array('result' => 'success', 'queryResult' => sprintf($this->lang->metric->saveSqlMeasSuccess, $queryResult)));
        }

        if(!$isOldMetric) $result = $this->metric->getResultByCode($metric->code);

        $metric->isUsed = (int)$this->loadModel('screen')->checkIFChartInUse($metric->id, 'metric');

        $this->view->title          = $metric->name;
        $this->view->metric         = $metric;
        $this->view->isOldMetric    = $isOldMetric;
        $this->view->isCalcExists   = $this->metric->checkCalcExists($metric);
        $this->view->result         = $result;
        $this->view->resultHeader   = $this->metric->getViewTableHeader($metric);
        $this->view->resultData     = $this->metric->getViewTableData($metric, $result);
        $this->view->legendBasic    = $this->metricZen->getBasicInfo($this->view);
        $this->view->createEditInfo = $this->metricZen->getCreateEditInfo($this->view);
        $this->view->actions        = $this->loadModel('action')->getList('metric', $metricID);
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->preAndNext     = $this->loadModel('common')->getPreAndNextObject('metric', $metricID);
        if((int)$metric->fromID !== 0) $this->view->oldMetricInfo = $this->metricZen->getOldMetricInfo($metric->fromID);

        if($isOldMetric)
        {
            $params = json_decode($measurement->params, true);
            $this->view->measurement = $measurement;
            $this->view->params      = empty($params) ? array() : json_decode($measurement->params, true);
        }
        else
        {
            $this->view->result       = $result;
            $this->view->resultHeader = $this->metric->getViewTableHeader($metric);
            $this->view->resultData   = $this->metric->getViewTableData($metric, $result);
        }

        $this->display();
    }
}
