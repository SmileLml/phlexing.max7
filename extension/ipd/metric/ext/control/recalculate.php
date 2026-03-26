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
     * 重算度量项进度。
     * Show recalculate progress.
     *
     * @param  string $calcType  all|inference
     * @param  string $calcRange all|single
     * @param  string $code
     * @access public
     * @return string
     */
    public function recalculate($calcType, $calcRange = 'all', $code = '')
    {
        $metric   = !empty($code) ? $this->metric->getByCode($code) : '';
        $dateType = !empty($code) ? $this->metric->getDateTypeByCode($code) : '';

        $startDate = $this->metric->getInstallDate();
        $endDate   = helper::now();

        $this->view->code      = $code;
        $this->view->metric    = $metric;
        $this->view->dateType  = $dateType;
        $this->view->calcType  = $calcType;
        $this->view->calcRange = $calcRange;
        $this->view->startDate = substr($startDate, 0, 10);
        $this->view->endDate   = substr($endDate, 0, 10);
        $this->display();
    }
}
