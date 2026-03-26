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
     * Browse pivots.
     *
     * @param  int    $dimensionID
     * @param  int    $groupID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($dimensionID = 0, $groupID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $dimensionID = $this->loadModel('dimension')->setSwitcherMenu($dimensionID);

        $this->session->set('pivotList', $this->app->getURI(true));

        /* Load pager and get tracks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->loadModel('tree');
        $group = !empty($groupID) ? $this->tree->getByID($groupID) : '';

        $pivots = $this->pivot->getList($dimensionID, $groupID, $orderBy, $pager);
        $groups = arrayUnion($this->tree->getGroupPairs($dimensionID, 0, 2, 'pivot'), $this->tree->getGroupPairs($dimensionID, 0, 1, 'pivot'));

        $this->pivot->processGroup($pivots, $groups);

        $this->view->title       = $this->lang->pivot->common;
        $this->view->dimensionID = $dimensionID;
        $this->view->groupID     = $groupID;
        $this->view->groupName   = !empty($group) ? $group->name : $this->lang->pivot->allGroup;
        $this->view->groupMenus  = $this->tree->getGroupTreeMenu($dimensionID, 'pivot', $orderBy, $pager);
        $this->view->pivots      = $pivots;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;

        $this->display();
    }
}
