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
     * Create chart.
     *
     * @param  int    $dimensionID
     * @param  int    $chartID
     * @param  int    $step
     * @access public
     * @return void
     */
    public function create($dimensionID = 0, $chartID = 0, $step = 0)
    {
        $dimensionID = $this->loadModel('dimension')->setSwitcherMenu($dimensionID);

        if(!empty($_POST))
        {
            $chartID = $this->chart->create($dimensionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('chart', $chartID, 'opened');

            $callback = array('target' => 'parent', 'name' => 'locate', 'params' => array('chart', 'design', "chartID=$chartID&step=1"));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->title       = $this->lang->chart->create;
        $this->view->dimensionID = $dimensionID;
        $this->view->groups      = $this->loadModel('tree')->getGroupPairs($dimensionID, 0, 2);
        $this->display();
    }
}
