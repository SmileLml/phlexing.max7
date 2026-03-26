<?php
/**
 * The control file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class chart extends control
{
    /**
     * Design chart.
     *
     * @param  int    $chartid
     * @access public
     * @return void
     */
    public function design($chartID)
    {
        $this->chart->checkAccess($chartID, 'browse');

        if(!empty($_POST))
        {
            $this->chart->update($chartID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('chart', $chartID, 'chartreleased');

            $locate = $this->createLink('chart', 'browse');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $chart = $this->chart->getByID($chartID);

        $this->loadModel('dataview');
        $objectFields = array();
        foreach($this->lang->dataview->objects as $object => $objectName) $objectFields[$object] = $this->dataview->getTypeOptions($object);

        $this->loadModel('dimension')->setSwitcherMenu($chart->dimension);

        $this->view->title        = $this->lang->chart->design;
        $this->view->backLink     = inlink('browse', "dimensionID=$chart->dimension");
        $this->view->saveLink     = inlink('design', "chartID=$chartID");
        $this->view->chart        = $chart;
        $this->view->data         = $chart;
        $this->view->fieldList    = $this->chart->getChartFieldList($chartID);
        $this->view->groups       = $this->loadModel('tree')->getGroupPairs($chart->dimension);
        $this->view->objectFields = $objectFields;
        $this->display();
    }
}
