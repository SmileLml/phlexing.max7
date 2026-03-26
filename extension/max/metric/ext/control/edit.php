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
     * 编辑度量项。
     * Edit a metric.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function edit($id, $viewType = 'browse')
    {
        unset($this->lang->metric->scopeList['other']);
        unset($this->lang->metric->purposeList['other']);
        unset($this->lang->metric->objectList['other']);
        unset($this->lang->metric->objectList['review']);

        $metric = $this->metric->getByID($id);

        if(!empty($_POST))
        {
            $metricData = $this->metricZen->buildMetricForEdit();
            $metricData->unit = isset($_POST['customUnit']) ? $_POST['addunit'] : $_POST['unit'];

            if($metric->type == 'sql')
            {
                $metricData->type = 'php';
                $metricData->builtin = 0;
            }

            $metricID = $this->metric->update($id, $metricData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($viewType == 'view') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));

            $location = $this->createLink('metric', 'browse', "scope=$metricData->scope");
            $response = $this->metricZen->responseAfterEdit($id, $this->post->afterEdit, $location);

            return $this->send($response);
        }

        $this->metric->processObjectList();
        $this->metric->processUnitList();

        $this->view->metric = $metric;
        $this->view->viewType = $viewType;
        $this->display();
    }
}
