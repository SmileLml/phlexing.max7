<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class pivot extends control
{
    /**
     * Create pivot.
     *
     * @param  int    $dimensionID
     * @param  int    $pivotID
     * @param  int    $step
     * @access public
     * @return void
     */
    public function create($dimensionID = 0, $pivotID = 0, $step = 0)
    {
        $dimensionID = $this->loadModel('dimension')->setSwitcherMenu($dimensionID);

        if(!empty($_POST))
        {
            $pivotID = $this->pivot->create($dimensionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('pivot', $pivotID, 'opened');

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'locate' => $this->createLink('pivot', 'design', "pivotID=$pivotID&step=1")));
        }

        $this->view->title       = $this->lang->pivot->create;
        $this->view->dimensionID = $dimensionID;
        $this->view->groups      = $this->loadModel('tree')->getGroupPairs($dimensionID, 0, 2, 'pivot');
        $this->display();
    }
}
