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
     * 保存计算后的度量项对象。
     * Save calculated metric to file.
     *
     * @access public
     * @return void
     */
    public function ajaxSaveCalculatedMetrics()
    {
        $calcList            = $this->metric->getCalcInstanceList();
        $classifiedCalcGroup = $this->metric->classifyCalc($calcList);

        foreach($classifiedCalcGroup as $calcGroup)
        {
            try
            {
                $statement = $this->metricZen->prepareDataset($calcGroup);
                if(empty($statement)) continue;

                $this->metricZen->calcMetric($statement, $calcGroup->calcList);

            }
            catch(Exception $e)
            {
                a($this->metricZen->formatException($e));
            }
            catch(Error $e)
            {
                a($this->metricZen->formatException($e));
            }
        }

        foreach($classifiedCalcGroup as $calcGroup)
        {
            foreach($calcGroup->calcList as $code => $calc)
            {
                $calc->dateType = $this->metric->getDateTypeByCode($code);
                $calc->setDAO(null);
                $calc->setHolidays(null);
            }
        }

        file_put_contents($this->app->getTmpRoot() . 'calc', json_encode($classifiedCalcGroup));
    }
}
