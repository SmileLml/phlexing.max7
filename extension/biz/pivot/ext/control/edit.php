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
     * Edit pivot.
     *
     * @param  int    $pivotID
     * @param  string $from browse|preview
     * @param  string $params
     * @access public
     * @return void
     */
    public function edit($pivotID, $from = 'browse', $params = '')
    {
        if(!empty($_POST))
        {
            $this->pivot->edit($pivotID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('pivot', $pivotID, 'edited');

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $pivot       = $this->pivot->getByID($pivotID);
        $dimensionID = $this->loadModel('dimension')->setSwitcherMenu($pivot->dimension);
        $groups      = $this->loadModel('tree')->getGroupPairs($dimensionID, 0, 2, 'pivot');

        /* 如果没有，说明是一级菜单下的分组，追加 */
        if(strpos($pivot->group, ',') !== false)
        {
            $pivotGroups = explode(',', $pivot->group);
            foreach($pivotGroups as $pivotGroup)
            {
                if(!isset($groups[$pivotGroup])) $groups[$pivotGroup] = '/' . $this->dao->select('name')->from(TABLE_MODULE)->where('id')->eq($pivotGroup)->fetch('name');
            }
        }
        elseif(!isset($groups[$pivot->group]))
        {
            $groups[$pivot->group] = '/' . $this->dao->select('name')->from(TABLE_MODULE)->where('id')->eq($pivot->group)->fetch('name');
        }

        $this->view->pivot  = $pivot;
        $this->view->title  = $this->lang->pivot->edit;
        $this->view->groups = $groups;
        $this->display();
    }
}
