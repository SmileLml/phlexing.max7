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
     * Query pivot.
     *
     * @param  int    $pivotID
     * @access public
     * @return void
     */
    public function query($pivotID)
    {
        if(!empty($_POST))
        {
            $this->pivot->querySave($pivotID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('pivot', $pivotID, 'query');

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('pivot', 'design', "pivotID=$pivotID")));
        }

        $this->app->loadLang('dataview');

        $pivot = $this->pivot->getByID($pivotID);

        $this->view->title         = $this->lang->pivot->query;
        $this->view->saveLink      = inlink('query', "pivotID=$pivotID");
        $this->view->backLink      = inlink('browse');
        $this->view->data          = $pivot;
        $this->view->fieldSettings = isset($pivot->fieldSettings) ? $pivot->fieldSettings : new stdclass();
        $this->display();
    }
}
