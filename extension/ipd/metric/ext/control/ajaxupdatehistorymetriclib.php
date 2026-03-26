<?php
/**
 * The control file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xinzhi Qi
 * @package     metric
 * @version     $Id: control.php 5086 2023-06-06 02:25:22Z
 * @link        http://www.zentao.net
 */
class metric extends control
{
    /**
     * 更新所有度量项某天的度量数据。
     * Update the metric data of a certain day for all metrics.
     *
     * @param  string $date
     * @param  string $calcType
     * @access public
     * @return void
     */
    public function ajaxUpdateHistoryMetricLib($date, $calcType)
    {
        $date = str_replace('_', '-', $date);
        $calcList = $this->metric->getCalcInstanceList();

        $classifiedCalcGroup = json_decode(file_get_contents($this->app->getTmpRoot() . 'calc'));

        $records = array();
        foreach($classifiedCalcGroup as $calcGroup)
        {
            foreach($calcGroup->calcList as $code => $calc)
            {
                if($calcType == 'inference')
                {
                    $dateType = $calc->dateType;
                    $isCalcByCron = $this->metric->isCalcByCron($code, $date, $dateType);

                    if($dateType == 'nodate' || $isCalcByCron) continue;
                }

                $calcObj = $calcList[$code];
                $calcObj->result = json_decode(json_encode($calc->result), true);

                $inferenceRecord = $this->metricZen->getRecordByCodeAndDate($code, $calcObj, $date, 'all');
                if(!empty($inferenceRecord)) $records[$code] = $inferenceRecord;
            }
        }
        $this->metric->insertMetricLib($records, 'inference');
    }
}
