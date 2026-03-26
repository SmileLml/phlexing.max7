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
     * Browse charts.
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

        $this->session->set('chartList', $this->app->getURI(true));

        /* Load pager and get tracks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->loadModel('tree');
        $group = !empty($groupID) ? $this->tree->getByID($groupID) : '';

        $this->view->title       = $this->lang->chart->common;
        $this->view->dimensionID = $dimensionID;
        $this->view->groupID     = $groupID;
        $this->view->groupName   = !empty($group) ? $group->name : $this->lang->chart->allGroup;
        $this->view->groupTree   = $this->tree->getGroupTree($dimensionID, 'chart', $orderBy, $pager);
        $this->view->charts      = $this->chart->getList($dimensionID, $groupID, $orderBy, $pager);
        $this->view->groups      = arrayUnion($this->tree->getGroupPairs($dimensionID), $this->tree->getGroupPairs($dimensionID, 0, 1));
        $this->view->users       = $this->loadModel('user')->getPairs('noletter,noempty,noclosed');
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;

        $this->display();
    }
}
