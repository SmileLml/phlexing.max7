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
     * 发布度量项。
     * Publish a metric.
     *
     * @param  int    $metricID
     * @param  string $from metric|metriclib
     * @access public
     * @return void
     */
    public function publish($metricID, $from = 'metric')
    {
        $metric = $this->metric->getByID($metricID);

        $calcDir = $this->metric->getCalcDir($metric);
        if(!is_dir($calcDir))
        {
            $makeResult  = mkdir($calcDir, 0777, true);
            $errHtml     = '<div class="break-words">' . sprintf($this->lang->metric->dirNotExist, $calcDir) . '</div>';

            if(!$makeResult) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert({message: {html: '$errHtml'}})"));
        }

        $tmpCalc = $this->metric->getCustomCalcFile($metric->code);
        $newCalc = $calcDir . DS . $metric->code . '.php';

        if(!file_exists($newCalc))
        {
            $moveResult = rename($tmpCalc, $newCalc);
            $errHtml    = '<div class="break-words">' . sprintf($this->lang->metric->fileMoveFail, $tmpCalc, $newCalc) . '</div>';

            if(!$moveResult) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert({message: {html: '$errHtml'}})"));
        }

        $publishedMetric = new stdclass();
        $publishedMetric->id              = $metricID;
        $publishedMetric->stage           = 'released';
        $publishedMetric->implementedBy   = $this->app->user->account;
        $publishedMetric->implementedDate = helper::now();
        $this->metric->updateMetric($publishedMetric);

        $this->loadModel('action')->create('metric', $metricID, 'publish', '', '', $this->app->user->account);

        if($from == 'metric')    $location = $this->createLink('metric', 'browse', "scope=$metric->scope");
        if($from == 'metriclib') $location = $this->createLink('metriclib', 'browse', "scope=$metric->scope&period=$metric->dateType");

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $location));
    }
}
