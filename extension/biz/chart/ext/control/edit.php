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
     * Edit chart.
     *
     * @param  int    $chartID
     * @access public
     * @return void
     */
    public function edit($chartID)
    {
        if(!empty($_POST))
        {
            $this->chart->edit($chartID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('chart', $chartID, 'edited');

            $callback = array('target' => 'parent', 'name' => 'locate', 'params' => array('chart', 'browse'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $chart       = $this->chart->getByID($chartID);
        $dimensionID = $this->loadModel('dimension')->setSwitcherMenu($chart->dimension);

        $this->view->title  = $this->lang->chart->edit;
        $this->view->chart  = $chart;
        $this->view->groups = $this->loadModel('tree')->getGroupPairs($dimensionID);
        $this->display();
    }
}
