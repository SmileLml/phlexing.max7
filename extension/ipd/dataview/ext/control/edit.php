<?php
/**
 * The control file of dataview module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <chunsheng@cnezsoft.com>
 * @package     dataview
 * @version     $Id: control.php 5086 2023-06-06 02:25:22Z
 * @link        http://www.zentao.net
 */
class dataview extends control
{
    /**
     * Edit a dataview.
     *
     * @param  int    $dataviewID
     * @access public
     * @return void
     */
    public function edit($dataviewID)
    {
        if(!empty($_POST))
        {
            $changes = $this->dataview->update($dataviewID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('dataview', $dataviewID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $callback = array('target' => 'parent', 'name' => 'locate', 'params' => array('dataview', 'browse', "type=view&viewID=$dataviewID"));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $dataview = $this->dataview->getByID($dataviewID);

        $this->view->title    = $this->lang->dataview->edit;
        $this->view->groups   = $this->loadModel('tree')->getOptionMenu(0, 'dataview');
        $this->view->dataview = $dataview;
        $this->display();
    }
}
