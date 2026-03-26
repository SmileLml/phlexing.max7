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
     * 下载度量项模板文件。
     * Download metric template php file.
     *
     * @param  int $metricID
     * @access public
     * @return void
     */
    public function downloadTemplate($metricID)
    {
        list($fileName, $content) = $this->metric->getMetricPHPTemplate($metricID);

        $this->loadModel('file')->sendDownHeader($fileName, 'php', $content, 'content');
    }
}
