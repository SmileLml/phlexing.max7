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
     *
     * @param  int    $metricID
     * @param  string $from
     * @param  bool   $isVerify
     * @access public
     * @return void
     */
    public function ajaxGetMetricResult($metricID, $from = 'metric')
    {
        $metric = $this->metric->getByID($metricID);

        $this->metric->processImplementTips($metric->code);

        $this->view->metric       = $metric;
        $this->view->verifyCustom = $this->lang->metric->verifyCustom;
        $this->view->from         = $from;
        $this->view->result       = null;
        $this->view->resultHeader = array();
        $this->view->resultData   = array();

        $result = $this->metric->runCustomCalc($metric->code);
        $this->view->result       = $result;
        $this->view->resultHeader = $this->metric->getViewTableHeader($metric);
        $this->view->resultData   = $this->metric->getViewTableData($metric, $result);

        $calcDir  = $this->metric->getCalcDir($metric);
        $calcPath = $calcDir . DS . $metric->code . '.php';
        $this->view->isModuleCalcExist = file_exists($calcPath);
        $this->view->moduleCalcTip     = $this->lang->metric->deleteFile . $this->lang->metric->zentaoPath . "/module/metric/calc/{$metric->scope}/{$metric->purpose}/{$metric->code}.php";

        $this->display();
    }
}
