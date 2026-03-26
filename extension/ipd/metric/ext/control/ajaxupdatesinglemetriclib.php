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
     * 计算一个度量项的历史数据。
     * Update history metric lib of single metric.
     *
     * @param  string $code
     * @param  string $date
     * @param  string $calcType
     * @access public
     * @return void
     */
    public function ajaxUpdateSingleMetricLib($code, $date, $calcType)
    {
        $date = str_replace('_', '-', $date);
        $dateType = $this->metric->getDateTypeByCode($code);

        if($calcType == 'inference') $isCalcByCron = $this->metric->isCalcByCron($code, $date, $dateType);

        if($calcType == 'all' || !$isCalcByCron)
        {
            $calc   = $this->metric->calculateMetricByCode($code);
            $record = $this->metricZen->getRecordByCodeAndDate($code, $calc, $date, 'single');

            $records = array();
            $records[$code] = $record;
            $this->metric->insertMetricLib($records, 'inference');
        }
    }
}
