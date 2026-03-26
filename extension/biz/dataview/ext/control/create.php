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
     * Create a dataview.
     *
     * @param  string $step
     * @access public
     * @return void
     */
    public function create($step = 'query')
    {
        if(!empty($_POST))
        {
            $viewID = $this->dataview->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('dataview', $viewID, 'opened');

            $callback = array('target' => 'parent', 'name' => 'locate', 'params' => array('browse', "type=view&viewID=$viewID"));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'locate' => $this->inlink('query', "viewID=$viewID")));
        }

        $this->view->title     = $this->lang->dataview->create;
        $this->view->saveLink  = inlink('create', "step=create", '', true);
        $this->view->backLink  = inlink('browse', "type=view");
        $this->view->groups = $this->loadModel('tree')->getOptionMenu(0, 'dataview');
        $this->display();
    }
}
