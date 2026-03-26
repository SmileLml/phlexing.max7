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
     * 重算度量项历史数据。
     * Recalculate metric history data.
     *
     * @param  string $calcRange all|single
     * @param  string $code
     * @access public
     * @return string
     */
    public function recalculateSetting($calcRange= 'all', $code = '')
    {
        $this->view->calcRange = $calcRange;
        $this->view->code      = $code;
        $this->display();
    }
}
